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
use App\Http\Controllers\TemplateController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\BulkInviteController;

Route::get('/', fn() => view('welcome'));
Route::get('/log',fn() => redirect()->route('login'));
// Auth Routes
Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',    [AuthController::class, 'login'])->name('login.post');
// Registration Routes
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::get('/register/prepare', [AuthController::class, 'prepareRegister'])->name('register.prepare');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout',   [AuthController::class, 'logout'])->name('logout')->middleware('auth');
//  Email Verification Routes
Route::get('/email/verify', fn() => view('auth.verify-email'))
    ->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    $user = $request->user();
    session()->forget('pending_company_code');
    $invite = \App\Models\Invite::where('email', $user->email)
        ->whereIn('status', ['sent', 'pending', 'joined'])
        ->latest()
        ->first();
    if ($invite) {
        $team = \App\Models\Team::where('owner_id', $invite->admin_id)->first();
        if ($team) {
            return redirect('/profile/create?company_code=' . urlencode($team->invite_code))
                ->withHeaders([
                    'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
                    'Pragma'        => 'no-cache',
                    'Expires'       => 'Sat, 01 Jan 2000 00:00:00 GMT',
                ]);
        }
    }
    return redirect('/onboarding/use-case');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('status', 'verification-link-sent');
})->middleware(['auth', 'throttle:1,1'])->name('verification.send');

// ── Public Sign Routes (no auth needed) ──────────────────────────
Route::get('/sign/{token}', [WaiverController::class, 'sign'])->name('waivers.sign');
Route::post('/sign/{token}', [WaiverController::class, 'submitSign'])->name('waivers.submit-sign');

// ── Authenticated Routes ──────────────────────────────────────────
Route::middleware(['auth'])->group(function () {
Route::get('/notifications/fetch', [NotificationController::class, 'fetch'])
    ->name('notifications.fetch');
Route::post('/notifications/dismiss-all', [NotificationController::class, 'dismissAll'])
    ->name('notifications.dismiss-all');
       require base_path('routes/channels.php');
    // Onboarding
    Route::get('/onboarding/use-case',  [OnboardingController::class, 'useCase']);
    Route::post('/onboarding/use-case', [OnboardingController::class, 'saveUseCase']);
    // Profile
    Route::get('/profile',        [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/create', [ProfileController::class, 'create'])->name('profile.create');
    Route::post('/profile',       [ProfileController::class, 'store'])->name('profile.store');
    Route::get('/profile/edit',   [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile',        [ProfileController::class, 'update'])->name('profile.update');
    //  NEW: Fetch team data by invite code (for auto-fill)
    Route::get('/api/team/{code}', function ($code) {
        $team = \App\Models\Team::where('invite_code', strtoupper($code))->first();
        
        if (!$team) {
            return response()->json(['error' => 'Invalid invite code'], 404);
        }
        // Get the admin's profile to fetch company details
        $adminProfile = \App\Models\Profile::where('user_id', $team->owner_id)->first();

        return response()->json([
            'team_name' => $team->name,
            'company_name' => $adminProfile->company_name ?? $team->name,
            'industry' => $adminProfile->industry ?? '',
            'domain' => $adminProfile->domain ?? '',
            'location' => $adminProfile->location ?? '',
            'phone' => $adminProfile->phone ?? '',
        ]);
    })->name('api.team.fetch');

   // Plans
Route::get('/plans',[PlanController::class, 'index'])->middleware('check.subscription') ->name('plans');
Route::post('/plans/select', [PlanController::class, 'select'])
    ->middleware('check.subscription')
    ->name('plans.select');
// Payment
Route::get('/payment',[PaymentController::class, 'index'])->middleware('check.subscription') ->name('payment');
Route::post('/payment/process', [PaymentController::class, 'processPayment']) ->middleware('check.subscription') ->name('payment.process');
Route::post('/payment/create-intent', [PaymentController::class, 'createIntent']) ->middleware('check.subscription') ->name('payment.create-intent');
    // Dashboard
   Route::get('/dashboard', function () {
    $user = auth()->user();

    return match($user->role) {
        'admin' => \App\Models\Subscription::where('user_id', $user->id)
                        ->where('status', 'active')
                        ->exists()
                    ? redirect()->route('admin.dashboard')
                    : redirect()->route('plans'),
        'employee' => redirect()->route('employee.dashboard'),
        'client'   => redirect()->route('client.portal'),
        default    => redirect('/onboarding/use-case'),
    };
})->middleware(['verified'])->name('dashboard');

    // ── Submissions dashboard + new action routes ─────────────────
    Route::get('/dashboard/submissions',
        [WaiverController::class, 'mySubmissions'])
        ->middleware(['prevent.back'])
        ->name('submissions.index');

    Route::get('/dashboard/submissions/export',
        [WaiverController::class, 'exportSubmissions'])
        ->name('submissions.export');

    Route::get('/dashboard/submissions/{send}/responses',
        [WaiverController::class, 'submissionResponses'])
        ->name('submissions.responses');

    Route::post('/dashboard/submissions/{send}/remind',
        [WaiverController::class, 'remindSubmission'])
        ->name('submissions.remind');

    Route::get('/dashboard/submissions/{send}/download',
        [WaiverController::class, 'downloadSubmission'])
        ->name('submissions.download');

        // Personal Chat
   // Personal Chat
Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');

// IMPORTANT: Specific routes BEFORE wildcard routes!
Route::get('/chat/search-users', [ChatUnifiedController::class, 'searchUsers'])
    ->name('chat.search-users');

Route::get('/chat/{user}', [ChatController::class, 'show'])->name('chat.show');
Route::post('/chat/{conversation}/send', [ChatController::class, 'send'])->name('chat.send');
Route::post('/chat/{conversation}/typing', [ChatController::class, 'typing'])->name('chat.typing');

// Unified Chat (sidebar interface)
Route::get('/chats', [ChatUnifiedController::class, 'index'])->name('chats');
Route::post('/chats/groups', [ChatUnifiedController::class, 'storeGroup'])->name('chats.groups.store');

// Group Chat
Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
Route::post('/groups/{group}/messages', [GroupMessageController::class, 'store'])->name('groups.messages.store');
Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');

    // Waivers
    Route::resource('waivers', WaiverController::class);
    Route::get('waivers/{waiver}/send',  [WaiverController::class, 'sendForm'])->name('waivers.sendForm');
    Route::post('waivers/{waiver}/send', [WaiverController::class, 'send'])->name('waivers.send');
 
    // Create a group (controller enforces admin/employee only)
    Route::post('/groups', [GroupController::class, 'store'])
        ->name('groups.store');

}); // ← Close main auth group

// ── ADMIN ROUTES ──────────────────────────────────────────────────
Route::middleware(['auth', 'check.profile', 'role:admin', 'require.subscription'])->prefix('admin')->group(function () {    
    Route::get('/dashboard', function () {
        return view('dashboard'); 
    })->name('admin.dashboard');

    // Task Management
    Route::get('/tasks', [\App\Http\Controllers\Admin\TaskController::class, 'index'])->name('admin.tasks.index');
    Route::get('/tasks/create', [\App\Http\Controllers\Admin\TaskController::class, 'create'])->name('admin.tasks.create');
    Route::post('/tasks', [\App\Http\Controllers\Admin\TaskController::class, 'store'])->name('admin.tasks.store');
    Route::get('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'show'])->name('admin.tasks.show');
    Route::patch('/tasks/{task}/status', [\App\Http\Controllers\Admin\TaskController::class, 'updateStatus'])->name('admin.tasks.updateStatus');
    Route::delete('/tasks/{task}', [\App\Http\Controllers\Admin\TaskController::class, 'destroy'])->name('admin.tasks.destroy');

    // Salary Slip Management
    Route::get('/salary', [\App\Http\Controllers\Admin\SalarySlipController::class, 'index'])->name('admin.salary.index');
    Route::get('/salary/create', [\App\Http\Controllers\Admin\SalarySlipController::class, 'create'])->name('admin.salary.create');
    Route::post('/salary', [\App\Http\Controllers\Admin\SalarySlipController::class, 'store'])->name('admin.salary.store');
    Route::get('/salary/{salarySlip}/download', [\App\Http\Controllers\Admin\SalarySlipController::class, 'download'])->name('admin.salary.download');
    Route::delete('/salary/{salarySlip}', [\App\Http\Controllers\Admin\SalarySlipController::class, 'destroy'])->name('admin.salary.destroy');

    // Resource Routes
    Route::resource('clients', \App\Http\Controllers\ClientController::class);
    Route::resource('templates', \App\Http\Controllers\TemplateController::class);

    // Groups
    Route::resource('groups', GroupController::class);
    Route::post('groups/{group}/messages',         [GroupMessageController::class, 'store'])->name('groups.messages.store');
    Route::post('groups/{group}/members',          [GroupController::class, 'addMember'])->name('groups.members.add');
    Route::delete('groups/{group}/members/{user}', [GroupController::class, 'removeMember'])->name('groups.members.remove');

    // Templates
    Route::post('/templates/{templateId}/use',  [TemplateController::class, 'use'])->name('templates.use');
    Route::get('/bulk-invite', [App\Http\Controllers\Admin\BulkInviteController::class, 'show'])
    ->name('admin.bulk-invite');

Route::post('/bulk-invite', [App\Http\Controllers\Admin\BulkInviteController::class, 'process'])
    ->name('admin.bulk-invite.process');

Route::get('/invites', [BulkInviteController::class, 'index'])
    ->name('admin.invites.index');

Route::post('/invites/{invite}/remind', [App\Http\Controllers\Admin\BulkInviteController::class, 'remind'])
    ->name('admin.invites.remind');
}); // ← Close admin group

// ── EMPLOYEE ROUTES ───────────────────────────────────────────────
Route::middleware(['auth', 'check.profile', 'role:employee'])->prefix('employee')->group(function () {
    
    Route::get('/dashboard', function () {
        return view('employee.dashboard');
    })->name('employee.dashboard');

    // Employee Task Routes
    Route::get('/my-tasks', [\App\Http\Controllers\Employee\TaskController::class, 'index'])->name('employee.tasks.index');
    Route::patch('/tasks/{task}/status', [\App\Http\Controllers\Employee\TaskController::class, 'updateStatus'])->name('employee.tasks.updateStatus');
Route::get('/salary/{salarySlip}/download', [\App\Http\Controllers\Admin\SalarySlipController::class, 'employeeDownload'])->name('salary.download');
    // Employee Salary Routes
    Route::get('/my-salary', [\App\Http\Controllers\Employee\SalarySlipController::class, 'index'])->name('employee.salary.index');
    Route::get('/salary/{salarySlip}/download', [\App\Http\Controllers\Employee\SalarySlipController::class, 'download'])->name('employee.salary.download');

}); // ← Close employee group
 
   

// ── CLIENT ROUTES ─────────────────────────────────────────────────
Route::middleware(['auth', 'check.profile', 'role:client'])->prefix('client')->group(function () {
    
    Route::get('/portal', function () {
        return view('client.portal');
    })->name('client.portal');

}); // ← Close client group