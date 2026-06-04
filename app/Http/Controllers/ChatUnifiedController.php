<?php
namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class ChatUnifiedController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Personal chats
        $personalChats = Conversation::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->with(['sender', 'receiver', 'messages' => fn($q) => $q->latest()->limit(1)])
            ->get()
            ->map(function ($conv) use ($userId) {
                $other   = $conv->otherUser($userId);
                $lastMsg = $conv->messages->first();
                return [
                    'type'      => 'personal',
                    'id'        => $conv->id,
                    'name' => $other?->name ?? 'Unknown User',
                    'avatar'    => null,
                    'initial'   => strtoupper(substr($other->name, 0, 1)),
                    'last_msg'  => $lastMsg?->content ?? 'No messages yet',
                    'last_time' => $lastMsg?->created_at,
                    'route'     => route('chat.show', $other),
                    'other_id'  => $other->id,
                ];
            });

        // Group chats
        $groupChats = Auth::user()->groups()
            ->with('latestMessage.user')
            ->get()
            ->map(function ($group) {
                $lastMsg = $group->latestMessage;
                return [
                    'type'      => 'group',
                    'id'        => $group->id,
                    'name'      => $group->name,
                    'avatar'    => $group->avatar,
                    'initial'   => strtoupper(substr($group->name, 0, 1)),
                    'last_msg'  => $lastMsg
                                    ? $lastMsg->user->name . ': ' . $lastMsg->message
                                    : 'No messages yet',
                    'last_time' => $lastMsg?->created_at,
                    'route'     => route('groups.show', $group),
                    'group_id'  => $group->id,
                ];
            });

        // Merge and sort by latest message
        $chats = $personalChats->concat($groupChats)
            ->sortByDesc('last_time')
            ->values();

        return view('chat.unified', compact('chats'));
    }
}