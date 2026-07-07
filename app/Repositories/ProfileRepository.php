<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\Team;
use App\Models\Invite;
use App\Models\User;
use App\Repositories\Contracts\ProfileRepositoryInterface;
use Illuminate\Support\Str;

class ProfileRepository implements ProfileRepositoryInterface
{
    public function findPendingInviteForEmail(string $email): ?Invite
    {
        return Invite::where('email', $email)
            ->whereIn('status', ['sent', 'pending', 'joined'])
            ->latest()
            ->first();
    }

    public function findTeamByOwnerId(int $ownerId): ?Team
    {
        return Team::where('owner_id', $ownerId)->first();
    }

    public function findTeamByInviteCode(string $inviteCode): ?Team
    {
        return Team::where('invite_code', strtoupper($inviteCode))
            ->with('owner.profile')
            ->first();
    }

    public function findInviteForTeamAndEmail(string $email): ?Invite
    {
        return Invite::where('email', $email)
            ->whereIn('status', ['pending', 'joined'])
            ->first();
    }

    public function createTeam(string $name, int $ownerId): Team
    {
        return Team::create([
            'name'        => $name,
            'invite_code' => strtoupper(Str::random(8)),
            'owner_id'    => $ownerId,
        ]);
    }

    public function markInviteAsJoined(string $email): void
    {
        Invite::where('email', $email)
            ->where('status', 'pending')
            ->update([
                'status'    => 'joined',
                'joined_at' => now(),
            ]);
    }

    public function saveUserRoleAndJoinDate(User $user, string $role): void
    {
        $user->role      = $role;
        $user->joined_at = now();
        $user->save();
    }

    public function updateOrCreateProfile(int $userId, array $data): Profile
    {
        return Profile::updateOrCreate(
            ['user_id' => $userId],
            $data
        );
    }
}