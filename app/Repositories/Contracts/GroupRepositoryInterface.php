<?php

namespace App\Repositories\Contracts;

use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use App\Models\GroupMessage;

interface GroupRepositoryInterface
{
    public function getForUser(User $user): Collection;
    public function create(array $validated, User $creator): Group;
    public function addMember(Group $group, int $userId): void;
    public function removeMember(Group $group, User $user): void;
    public function delete(Group $group): void;
    public function createMessage(Group $group, int $userId, array $data, $attachment = null): GroupMessage;
}