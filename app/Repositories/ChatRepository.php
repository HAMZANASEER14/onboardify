<?php

namespace App\Repositories;

use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use App\Repositories\Contracts\ChatRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class ChatRepository implements ChatRepositoryInterface
{
    // ── From ChatUnifiedController ─────────────────────────────

    public function getPersonalChats(int $userId): Collection
    {
        return Conversation::where(function ($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
            })
            ->latest()
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
            ->get()
            ->map(function ($conv) use ($userId) {
                $other = $conv->sender_id === $userId
                    ? User::find($conv->receiver_id)
                    : User::find($conv->sender_id);
                $last = $conv->messages->first();

                return [
                    'type'      => 'personal',
                    'id'        => $conv->id,
                    'name'      => $other?->name ?? 'Unknown',
                    'initial'   => strtoupper(substr($other?->name ?? 'U', 0, 1)),
                    'avatar'    => null,
                    'last_msg'  => $last?->content ?? $last?->body ?? '',
                    'last_time' => $last?->created_at ?? $conv->created_at,
                    'route'     => route('chat.show', $other?->id),
                    'user_id'   => $other?->id,
                ];
            });
    }

    public function getGroupChats(int $userId): Collection
    {
        return Group::whereHas('members', fn ($q) => $q->where('user_id', $userId))
            ->latest()
            ->with(['messages' => fn ($q) => $q->latest()->limit(1)])
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
                    'last_time' => $last?->created_at ?? $group->created_at,
                    'route'     => route('groups.show', $group->id),
                ];
            });
    }

    public function findOrCreateConversation(int $userId, int $otherUserId): Conversation
    {
        $conversation = Conversation::where(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $userId)->where('receiver_id', $otherUserId);
            })
            ->orWhere(function ($q) use ($userId, $otherUserId) {
                $q->where('sender_id', $otherUserId)->where('receiver_id', $userId);
            })
            ->first();

        return $conversation ?? Conversation::create([
            'sender_id'   => $userId,
            'receiver_id' => $otherUserId,
        ]);
    }

    public function searchUsers(string $term, int $excludeUserId, int $limit = 10): Collection
    {
        return User::where('id', '!=', $excludeUserId)
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                      ->orWhere('email', 'like', "%{$term}%");
            })
            ->select('id', 'name', 'email')
            ->limit($limit)
            ->get();
    }

    public function createGroup(array $data, int $creatorId): Group
    {
        $group = Group::create([
            'name'        => $data['name'],
            'description' => $data['description'] ?? null,
            'created_by'  => $creatorId,
            'avatar'      => null,
        ]);

        $members = collect($data['members'] ?? []);
        $members->push($creatorId);

        $group->members()->createMany(
            $members->unique()->map(fn ($id) => ['user_id' => $id])->toArray()
        );

        return $group;
    }

    public function attachAvatar(Group $group, UploadedFile $avatar): Group
    {
        $path = $avatar->store('groups', 'public');
        $group->update(['avatar' => $path]);

        return $group;
    }

    // ── From ChatController ────────────────────────────────────

    public function getUnreadCounts(int $authId): Collection
    {
        return Conversation::where('sender_id', $authId)
            ->orWhere('receiver_id', $authId)
            ->with(['messages' => function ($q) use ($authId) {
                $q->where('user_id', '!=', $authId)
                  ->where('is_read', false);
            }])
            ->get()
            ->mapWithKeys(function ($conv) use ($authId) {
                $otherId = $conv->sender_id === $authId
                    ? $conv->receiver_id
                    : $conv->sender_id;

                return [$otherId => $conv->messages->count()];
            });
    }

    public function getConversationBetween(int $userId, int $otherUserId): Conversation
    {
        return Conversation::between($userId, $otherUserId);
    }

    public function markMessagesRead(Conversation $conversation, int $senderId): void
    {
        $conversation->messages()
            ->where('user_id', $senderId)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    public function getMessagesWithSender(Conversation $conversation): Collection
    {
        return $conversation->messages()->with('user')->get();
    }

    public function storeMessage(Conversation $conversation, int $userId, array $data, ?UploadedFile $file = null): Message
    {
        $filePath = null;
        $fileName = null;
        $fileType = null;

        if ($file) {
            $fileName = $file->getClientOriginalName();
            $fileType = $file->getMimeType();
            $filePath = $file->store('chat-files', 'public');
        }

        return $conversation->messages()->create([
            'user_id'   => $userId,
            'content'   => $data['content'] ?? '',
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_type' => $fileType,
        ]);
    }
}