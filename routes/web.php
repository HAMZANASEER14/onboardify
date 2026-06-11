<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\WaiverController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupMessageController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ChatUnifiedController;

// ── Root ──────────────────────────────────────────────────────────
Route::get('/', fn() => view('welcome'));
Route::get('/log', fn() => redirect()->route('login'));

// ── Auth ──────────────────────────────────────────────────────────
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::any('/logout',   [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── Email Verification ────────────────────────────────────────────
Route::get('/email/verify', fn() => view('auth.verify-email'))
    ->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/onboarding/use-case');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// ── Public Sign Routes (no auth needed) ──────────────────────────
Route::get('/sign/{slug}',  [WaiverController::class, 'sign'])->name('waivers.sign');
Route::post('/sign/{slug}', [WaiverController::class, 'submitSign'])->name('waivers.submit-sign');

// ── Authenticated Routes ──────────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Onboarding
    Route::get('/onboarding/use-case',  [OnboardingController::class, 'useCase']);
    Route::post('/onboarding/use-case', [OnboardingController::class, 'saveUseCase']);

    // Profile
    Route::get('/profile',        [ProfileController::class, 'show']);
    Route::get('/profile/create', [ProfileController::class, 'create']);
    Route::post('/profile',       [ProfileController::class, 'store']);

    // Plans
    Route::get('/plans',         [PlanController::class, 'index'])->name('plans');
    Route::post('/plans/select', [PlanController::class, 'select'])->name('plans.select');

    // Payment
    Route::get('/payment',          [PaymentController::class, 'index'])->name('payment');
    Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
    Route::get('/payment/success',  [PaymentController::class, 'success'])->name('payment.success');

    // Dashboard
  Route::get('/dashboard', function () {
    return view('dashboard', [
        'user'    => auth()->user(),
        'profile' => auth()->user()->profile,
    ]);
})->middleware(['auth', 'verified', 'prevent.back'])->name('dashboard');

Route::get('/dashboard/submissions', [WaiverController::class, 'mySubmissions'])
    ->middleware(['auth', 'prevent.back'])
    ->name('submissions.index');
    
    // Personal Chat
    Route::get('/chat',                        [ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{user}',                 [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send',   [ChatController::class, 'send'])->name('chat.send');
    Route::post('/chat/{conversation}/typing', [ChatController::class, 'typing'])->name('chat.typing');

    // Unified Chat (sidebar)
    Route::get('/chats',  [ChatUnifiedController::class, 'index'])->name('chats');
    Route::post('/chats/groups', [ChatUnifiedController::class, 'storeGroup'])->name('chats.groups.store');

    // Waivers
    Route::resource('waivers', WaiverController::class);
    Route::get('waivers/{waiver}/send',  [WaiverController::class, 'sendForm'])->name('waivers.sendForm');
    Route::post('waivers/{waiver}/send', [WaiverController::class, 'send'])->name('waivers.send');

    // Clients
    Route::resource('clients', ClientController::class);

    // Groups
    Route::resource('groups', GroupController::class);
    Route::post('groups/{group}/messages',            [GroupMessageController::class, 'store'])->name('groups.messages.store');
    Route::post('groups/{group}/members',             [GroupController::class, 'addMember'])->name('groups.members.add');
    Route::delete('groups/{group}/members/{user}',    [GroupController::class, 'removeMember'])->name('groups.members.remove');
// Route::get('/clients/{client}/invitation/{token}', [ClientController::class, 'invitation'])
    //  ->name('clients.invitation');
// Clients (read-only — auto created when waiver is sent)
    Route::get('/clients',             [ClientController::class, 'index'])->name('clients.index');
    Route::get('/clients/{client}',    [ClientController::class, 'show'])->name('clients.show');
    Route::delete('/clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
// Templates
// Templates
Route::get('/templates', [WaiverController::class, 'templates'])->name('templates.index');
Route::post('/templates/{templateId}/use', [WaiverController::class, 'useTemplate'])->name('templates.use');
}); // ← closing auth group