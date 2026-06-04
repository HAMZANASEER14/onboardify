<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;

class AuthController extends Controller
{
    // ── Helper — decide where to send user based on progress ──────
  private function redirectBasedOnProgress($user)
{
    // Step 1 — Email not verified
    if (!$user->hasVerifiedEmail()) {
        return redirect()->route('verification.notice');
    }

    // Step 2 — No use case selected
    if (!$user->profile || !$user->profile->business_type) {
        return redirect('/onboarding/use-case');
    }

    // Step 3 — Profile not completed
    if (!$user->profile->first_name) {
        return redirect('/profile/create');
    }

    // Step 4 — No subscription found
    $hasSubscription = \App\Models\Subscription::where('user_id', $user->id)->exists();
    if (!$hasSubscription) {
        return redirect('/plans');
    }

    // Step 5 — Everything done → dashboard
    return redirect('/dashboard');
}

    // ── Show Login Page ───────────────────────────────────────────
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnProgress(Auth::user());
        }
        return view('auth.login');
    }

    // ── Handle Login ──────────────────────────────────────────────
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return $this->redirectBasedOnProgress(Auth::user());
        }

        return back()
            ->withErrors(['email' => 'These credentials do not match our records.'])
            ->onlyInput('email');
    }

    // ── Show Register Page ────────────────────────────────────────
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnProgress(Auth::user());
        }
        return view('auth.register');
    }

    // ── Handle Register ───────────────────────────────────────────
    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    // ── Logout ────────────────────────────────────────────────────
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}