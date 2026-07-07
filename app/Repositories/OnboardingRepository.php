<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Contracts\OnboardingRepositoryInterface;

class OnboardingRepository implements OnboardingRepositoryInterface
{
    public function saveUseCase(User $user, string $businessType): void
    {
        $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'business_type' => $businessType,
            ]
        );
    }
}