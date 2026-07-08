<?php

namespace App\Repositories;

use App\Models\Invite;
use App\Models\Subscription;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Models\client;

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

    $user = User::create($userData);

    // Link this new user to the Client row the admin already has for them.
    if ($invite && $invite->role === 'client' && $invite->admin_id) {
        Client::where('user_id', $invite->admin_id)
            ->where('email', $invite->email)
            ->update(['portal_user_id' => $user->id]);
    }

    return $user;
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