<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Repositories\Contracts\AuthRepositoryInterface;

class AuthController extends Controller
{
    public function __construct(private AuthRepositoryInterface $auth) {}
     private function redirectBasedOnProgress($user)
    {
        // Step 1 — Email not verified
        if (!$user->hasVerifiedEmail()) {
            return redirect()->route('verification.notice');
        }
            //   Handle invited users (employees/clients) separately ──
        if (in_array($user->role, ['employee', 'client'])) {
            $needsProfileCompletion = false;
            if (!$user->profile) {
                $needsProfileCompletion = true;
            } else {
                if (empty($user->profile->phone) || empty($user->profile->profile_picture)) {
                    $needsProfileCompletion = true;
                }
            }

            if ($needsProfileCompletion) {
                return redirect('/profile/create'); // Send to profile creation
            }
            
            // If profile is complete, send to their specific dashboard
            return match($user->role) {
                'employee' => redirect('/employee/dashboard'),
                'client'   => redirect('/client/portal'),
                default    => redirect('/dashboard'),
            };
        }

        // ── Admin flow continues below ──
        
        // Step 2 — No use case selected (check profile exists AND has business_type)
        if (!$user->profile || !$user->profile->business_type) {
            return redirect('/onboarding/use-case');
        }

        // Step 3 — Profile incomplete (check ALL required fields)
        if (!$user->profile->first_name) {
            return redirect('/profile/create');
        }

        // Step 4 — Role not set (this happens during profile creation)
        if (!$user->role || $user->role === 'admin') {
            if ($user->role === 'admin' && !$user->team_id) {
                return redirect('/profile/create');
            }
        }

        // Step 5 — No subscription found (skip for employee/client who joined via invite)
      if ($user->role === 'admin') {
 if (!$this->auth->hasActiveSubscription($user)) {
    return redirect('/plans');
}
}

        // Step 6 — Everything done → redirect based on role
        return match($user->role) {
            'admin'    => redirect('/admin/dashboard'),
            'employee' => redirect('/employee/dashboard'),
            'client'   => redirect('/client/portal'),
            default    => redirect('/dashboard'),
        };
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
 public function login(LoginRequest $request)
{
    $credentials = $request->validated();

    if (Auth::attempt($credentials, $request->boolean('remember'))) {
        $request->session()->regenerate();
        return $this->redirectBasedOnProgress(Auth::user());
    }

    return back()
        ->withErrors(['email' => 'Invalid email or password. Please try again.'])
        ->onlyInput('email');
}
   public function prepareRegister(Request $request)
{
  return view('auth.register', [
    'companyCode' => $request->input('code'),      // ← reads "code"
    'inviteEmail' => $request->input('email')
  ]);
}
    // ── Show Register Page ────────────────────────────────────────
   public function showRegister(Request $request)
{
    
    return redirect()->route('register.prepare', [
        'company_code' => $request->query('company_code', ''),  // ← sends "company_code"
        'email'        => $request->query('email', ''),  
    ]);     

 $companyCode = $request->query('company_code', '');$inviteEmail = $request->query('email', '');

// Store company code in session
if ($companyCode) {
    session(['pending_company_code' => $companyCode]);
}

return view('auth.register', [
    'companyCode' => $companyCode ?: null,
    'inviteEmail' => $inviteEmail ?: null,  
]);
    }
    // ── Handle Register ───────────────────────────────────────────
    public function register(RegisterRequest $request)
{
    $validated = $request->validated();
    $invite = $request->resolvedInvite;

    if ($request->filled('company_code')) {
        session(['pending_company_code' => $request->company_code]);
    }

    $user = $this->auth->createUser($validated, $invite);

    if ($invite) {
        $this->auth->markInviteJoined($invite);
    }

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

        return redirect()->route('login')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma'        => 'no-cache',
            'Expires'       => 'Sat, 01 Jan 2000 00:00:00 GMT',
        ]);
    }
}