<?php

namespace App\Repositories\Contracts;

use App\Models\Invite;

interface InviteRepositoryInterface
{
    public function createFailed(int $adminId, string $email, string $role, string $reason, string $source): Invite;
    public function createPending(int $adminId, string $email, string $role, string $source): Invite;
    
    public function alreadyInvited(int $adminId, string $email): bool;
public function paginateForAdmin(int $adminId, int $perPage = 10);
public function resend(Invite $invite): Invite;

}