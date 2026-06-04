<?php
namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Auth::user()->groups()->withCount('members')
                      ->with('latestMessage.user')
                      ->get();

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('groups.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string|max:255',
            'members'     => 'required|array|min:1',
            'members.*'   => 'exists:users,id',
            'avatar'      => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['name', 'description']);
        $data['created_by'] = Auth::id();

        if ($request->hasFile('avatar')) {
            $data['avatar'] = $request->file('avatar')->store('groups', 'public');
        }

        $group = Group::create($data);

        // Add creator as admin
        $group->members()->create([
            'user_id' => Auth::id(),
            'role'    => 'admin',
        ]);

        // Add selected members
        foreach ($request->members as $userId) {
            $group->members()->create([
                'user_id' => $userId,
                'role'    => 'member',
            ]);
        }

        return redirect()->route('groups.show', $group)
                         ->with('success', 'Group created!');
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

    public function addMember(Request $request, Group $group)
    {
        abort_unless($group->isAdmin(Auth::id()), 403);

        $request->validate(['user_id' => 'required|exists:users,id']);

        $group->members()->firstOrCreate([
            'user_id' => $request->user_id,
        ], ['role' => 'member']);

        return back()->with('success', 'Member added.');
    }

    public function removeMember(Group $group, User $user)
    {
        abort_unless($group->isAdmin(Auth::id()), 403);

        $group->members()->where('user_id', $user->id)->delete();

        return back()->with('success', 'Member removed.');
    }

    public function destroy(Group $group)
    {
        abort_unless($group->isAdmin(Auth::id()), 403);

        $group->delete();

        return redirect()->route('groups.index')->with('success', 'Group deleted.');
    }
}