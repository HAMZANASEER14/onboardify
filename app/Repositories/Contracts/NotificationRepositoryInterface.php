<?php

namespace App\Repositories\Contracts;

use App\Models\User;
use Illuminate\Support\Collection;

interface NotificationRepositoryInterface
{
    public function getUnreadMessages(int $userId): Collection;
    public function getPendingTasks(int $userId, User $user): Collection;
    public function getDueTasks(int $userId, User $user): Collection;
    public function getWaiverNotifications(int $userId, User $user): Collection;
    public function getNewMemberNotifications(int $userId, User $user): Collection;
    public function getDismissedKeys(int $userId): Collection;
    public function insertDismissals(array $rows): void;
    public function markChatRead(int $userId): void;
}