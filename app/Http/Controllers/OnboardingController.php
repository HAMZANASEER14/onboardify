<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUseCaseRequest;
use App\Repositories\Contracts\OnboardingRepositoryInterface;

class OnboardingController extends Controller
{
    public function __construct(private OnboardingRepositoryInterface $onboarding) {}

    public function useCase()
    {
        return view('onboarding.use-case');
    }

    public function saveUseCase(SaveUseCaseRequest $request)
    {
        $user = auth()->user();

        $this->onboarding->saveUseCase($user, $request->validated('business_type'));

        return redirect('/profile/create');
    }
}