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
            'expires_at' => now()->addDays(7),
        ]);
    }

   public function paginateForAdmin(int $adminId, int $perPage = 10)
{
    return Invite::where('admin_id', $adminId)->latest()->paginate($perPage);
}
    public function alreadyInvited(int $adminId, string $email): bool
{
    return Invite::where('admin_id', $adminId)
        ->where('email', $email)
        ->whereIn('status', ['pending', 'sent', 'joined'])
        ->where('expires_at', '>', now())
        ->exists();
}
public function resend(Invite $invite): Invite
{
    $invite->update([
        'status'     => 'pending',
        'expires_at' => now()->addDays(7),
        'failure_reason' => null,
    ]);

    SendInviteJob::dispatch($invite);

    return $invite;
}
}