<?php

namespace App\Repositories\Contracts;

use App\Models\Invite;
use App\Models\User;

interface AuthRepositoryInterface
{
    public function createUser(array $data, ?Invite $invite): User;
    public function markInviteJoined(Invite $invite): void;
    public function hasActiveSubscription(User $user): bool;
}