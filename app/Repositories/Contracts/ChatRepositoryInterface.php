<?php

namespace App\Repositories\Contracts;

use App\Models\Conversation;
use App\Models\Group;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

interface ChatRepositoryInterface
{
    // From ChatUnifiedController
    public function getPersonalChats(int $userId): Collection;
    public function getGroupChats(int $userId): Collection;
    public function findOrCreateConversation(int $userId, int $otherUserId): Conversation;
    public function searchUsers(string $term, int $excludeUserId, int $limit = 10): Collection;
    public function createGroup(array $data, int $creatorId): Group;
    public function attachAvatar(Group $group, UploadedFile $avatar): Group;

    // From ChatController
    public function getUnreadCounts(int $authId): Collection;
    public function getConversationBetween(int $userId, int $otherUserId): Conversation;
    public function markMessagesRead(Conversation $conversation, int $senderId): void;
    public function getMessagesWithSender(Conversation $conversation): Collection;
    public function storeMessage(Conversation $conversation, int $userId, array $data, ?UploadedFile $file = null): Message;
}