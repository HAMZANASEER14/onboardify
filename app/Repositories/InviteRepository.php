<?php

namespace App\Repositories;

use App\Models\Invite;
use App\Repositories\Contracts\InviteRepositoryInterface;

class InviteRepository implements InviteRepositoryInterface
{
    public function createFailed(int $adminId, string $email, string $role, string $reason, string $source): Invite
    {
        return Invite::create([
            'admin_id'       => $adminId,
            'email'          => $email,
            'role'           => $role,
            'status'         => 'failed',
            'failure_reason' => $reason,
            'source'         => $source,
        ]);
    }

    public function createPending(int $adminId, string $email, string $role, string $source): Invite
    {
        return Invite::create([
            'admin_id' => $adminId,
            'email'    => $email,
            'role'     => $role,
            'status'   => 'pending',
            'source'   => $source,
        ]);
    }

    public function paginateAll(int $perPage = 10)
    {
        return Invite::latest()->paginate($perPage);
    }
}