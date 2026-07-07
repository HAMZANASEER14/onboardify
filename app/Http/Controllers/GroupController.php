<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreGroupRequest;
use App\Http\Requests\AddMemberRequest;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use App\Repositories\Contracts\GroupRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function __construct(private GroupRepositoryInterface $groups) {}

    public function index()
    {
        $groups = $this->groups->getForUser(Auth::user());

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('groups.create', compact('users'));
    }

    public function store(StoreGroupRequest $request)
    {
        $group = $this->groups->create($request->validated(), auth()->user());

        return redirect()->route('groups.show', $group->id)
            ->with('success', 'Group created successfully!');
    }

    public function show(Group $group)
    {
        // Only members can view
        abort_unless(
            $group->members()->where('user_id', Auth::id())->exists(),
            403
        );

        $messages = $group->messages()
                          ->with('user')
                          ->latest()
                          ->take(50)
                          ->get()
                          ->reverse();

        $members = $group->users()->get();
        $isAdmin = $group->isAdmin(Auth::id());

        return view('groups.show', compact('group', 'messages', 'members', 'isAdmin'));
    }

    public function addMember(AddMemberRequest $request, Group $group)
    {
        $this->groups->addMember($group, $request->validated('user_id'));

        return back()->with('success', 'Member added.');
    }

    public function removeMember(Group $group, User $user)
    {
        abort_unless($group->isAdmin(Auth::id()), 403);

        $this->groups->removeMember($group, $user);

        return back()->with('success', 'Member removed.');
    }

    public function destroy(Group $group)
    {
        abort_unless($group->isAdmin(Auth::id()), 403);

        $this->groups->delete($group);

        return redirect()->route('groups.index')->with('success', 'Group deleted.');
    }
}