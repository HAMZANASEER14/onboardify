<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    public function useCase()
{
    return view('onboarding.use-case');
}
// public function saveUseCase(Request $request)
// {
//     $request->validate([
//         'business_type' => 'required'
//     ]);

//     $user = auth()->user();

//     $user->profile()->updateOrCreate(
//         ['user_id' => $user->id],
//         [
//             'business_type' => $request->business_type,
//         ]
//     );

//     return redirect('/profile/create');
// }
public function saveUseCase(Request $request)
{
    $request->validate([
        'business_type' => 'required'
    ]);

    $user = auth()->user();

    $user->profile()->updateOrCreate(
        [],
        [
            'business_type' => $request->business_type,
        ]
    );

    return redirect('/profile/create');
}
}