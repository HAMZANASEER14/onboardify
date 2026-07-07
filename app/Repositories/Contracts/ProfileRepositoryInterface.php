<?php

namespace App\Repositories\Contracts;

use App\Models\Profile;
use App\Models\Team;
use App\Models\Invite;
use App\Models\User;

interface ProfileRepositoryInterface
{
    public function findPendingInviteForEmail(string $email): ?Invite;

    public function findTeamByOwnerId(int $ownerId): ?Team;

    public function findTeamByInviteCode(string $inviteCode): ?Team;

    public function findInviteForTeamAndEmail(string $email): ?Invite;

    public function createTeam(string $name, int $ownerId): Team;

    public function markInviteAsJoined(string $email): void;

    public function saveUserRoleAndJoinDate(User $user, string $role): void;

    public function updateOrCreateProfile(int $userId, array $data): Profile;
}