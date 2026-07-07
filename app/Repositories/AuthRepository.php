<?php

namespace App\Repositories;

use App\Models\Invite;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function createUser(array $data, ?Invite $invite): User
    {
        $userData = [
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'joined_at' => now(),
        ];

        if ($invite) {
            $userData['role'] = $invite->role;

            $admin = $invite->admin;
            if ($admin) {
                if ($admin->company_id) {
                    $userData['company_id'] = $admin->company_id;
                } elseif ($admin->team_id) {
                    $userData['team_id'] = $admin->team_id;
                }
            }
        }

        return User::create($userData);
    }

    public function markInviteJoined(Invite $invite): void
    {
        $invite->update([
            'status'    => 'joined',
            'joined_at' => now(),
        ]);
    }

    public function hasActiveSubscription(User $user): bool
    {
        return Subscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }
}