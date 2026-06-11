<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use App\Models\GroupMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatUnifiedController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // ── Personal chats ────────────────────────────────────────
        $personalChats = Conversation::where(function ($q) use ($userId) {
                            $q->where('sender_id', $userId)
                              ->orWhere('receiver_id', $userId);
                         })
                         ->with(['messages' => fn($q) => $q->latest()->limit(1)])
                         ->get()
                         ->map(function ($conv) use ($userId) {
                             $other = $conv->sender_id === $userId
                                 ? User::find($conv->receiver_id)
                                 : User::find($conv->sender_id);
                             $last  = $conv->messages->first();
                             return [
                                 'type'      => 'personal',
                                 'id'        => $conv->id,
                                 'name'      => $other?->name ?? 'Unknown',
                                 'initial'   => strtoupper(substr($other?->name ?? 'U', 0, 1)),
                                 'avatar'    => null,
                                 'last_msg'  => $last?->content ?? $last?->body ?? '',
                                 'last_time' => $last?->created_at,
                                 'route'     => route('chat.show', $other?->id),
                             ];
                         });

        // ── Group chats ───────────────────────────────────────────
        $groupChats = Group::whereHas('members', fn($q) => $q->where('user_id', $userId))
                      ->with(['messages' => fn($q) => $q->latest()->limit(1)])
                      ->get()
                      ->map(function ($group) {
                          $last = $group->messages->first();
                          return [
                              'type'      => 'group',
                              'id'        => $group->id,
                              'name'      => $group->name,
                              'initial'   => strtoupper(substr($group->name, 0, 1)),
                              'avatar'    => $group->avatar ?? null,
                              'last_msg'  => $last?->content ?? $last?->body ?? '',
                              'last_time' => $last?->created_at,
                              'route'     => route('groups.show', $group->id),
                          ];
                      });

        // ── Merge & sort by latest message ────────────────────────
        $chats = $personalChats->concat($groupChats)
                    ->sortByDesc(fn($c) => $c['last_time'])
                    ->values();

        // ── Users for modals ──────────────────────────────────────
        $users = User::where('id', '!=', $userId)->get();

        return view('chat.unified', compact('chats', 'users'));
    }

    // ── Store new group (regular form POST) ───────────────────────
    public function storeGroup(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'members'   => 'array',
            'members.*' => 'exists:users,id',
        ]);

        $group = Group::create([
            'name'        => $request->name,
            'description' => $request->description,
            'created_by'  => auth()->id(),
            'avatar'      => null,
        ]);

        // Add creator + selected members
        $members = collect($request->members ?? []);
        $members->push(auth()->id());
        $group->members()->createMany(
            $members->unique()->map(fn($id) => ['user_id' => $id])->toArray()
        );

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('groups', 'public');
            $group->update(['avatar' => $path]);
        }

        // If AJAX request return JSON, otherwise redirect
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'chat'    => [
                    'type'    => 'group',
                    'name'    => $group->name,
                    'initial' => strtoupper(substr($group->name, 0, 1)),
                    'route'   => route('groups.show', $group->id),
                    'avatar'  => null,
                ],
            ]);
        }

        // Regular form POST — redirect back to chats
        return redirect()->route('chats')->with('success', 'Group "' . $group->name . '" created!');
    }
}