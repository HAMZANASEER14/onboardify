<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface OnboardingRepositoryInterface
{
    public function saveUseCase(User $user, string $businessType): void;
}