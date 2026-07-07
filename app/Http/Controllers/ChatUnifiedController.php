<?php
namespace App\Http\Controllers;
use App\Http\Requests\StoreGroupRequest;
use App\Repositories\Contracts\ChatRepositoryInterface;
use Illuminate\Http\Request;
use App\Models\Users;
class ChatUnifiedController extends Controller
{
    public function __construct(private ChatRepositoryInterface $chats) {}

    public function index(Request $request)
    {
        $userId = auth()->id();

        // ── Personal + group chats, merged & sorted ──────────────
        $chats = $this->chats->getPersonalChats($userId)
            ->concat($this->chats->getGroupChats($userId))
            ->sortByDesc(fn ($c) => $c['last_time'] ? $c['last_time']->timestamp : 0)
            ->values();

        // ── Auto-open conversation ────────────────────────────────
        $autoOpenConversation = null;
        $autoOpenUserId = $request->query('user');
        if ($autoOpenUserId) {
            $autoOpenConversation = $this->chats->findOrCreateConversation(
                $userId,
                (int) $autoOpenUserId
            );
        }
        return view('chat.unified', compact('chats', 'autoOpenConversation'));
    }

  public function searchUsers(Request $request) 
    {
        $search = $request->input('q');

        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        $users = $this->chats->searchUsers($search, auth()->id());

        return response()->json($users);
    }

    public function storeGroup(StoreGroupRequest $request)
    {
        $group = $this->chats->createGroup($request->validated(), auth()->id());

        if ($request->hasFile('avatar')) {
            $group = $this->chats->attachAvatar($group, $request->file('avatar'));
        }
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'chat'    => [
                    'type'    => 'group',
                    'name'    => $group->name,
                    'initial' => strtoupper(substr($group->name, 0, 1)),
                    'route'   => route('groups.show', $group->id),
                    'avatar'  => $group->avatar,
                ],
            ]);
        }
        return redirect()->route('chats')->with('success', 'Group "' . $group->name . '" created!');
    }
}