@extends('layouts.app')

@section('title', 'Dashboard – Onboardify')

@section('page-title', 'Dashboard')
@section('header-actions')
    <a href="{{ route('waivers.create') }}"
       class="flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm hover:opacity-90"
       style="background: linear-gradient(135deg, #0B3D2E, #023b21);">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        <span class="hidden sm:inline">New Waiver</span>
    </a>
@endsection

@section('content')
@php
    $userId = auth()->id();
    $user = auth()->user();
    $isAdmin = $user->role === 'admin';
    $isEmployee = $user->role === 'employee';
    $isClient = $user->role === 'client';
    $team = $user->team;

    // 1. Identify Client Type (Invited via link vs Added directly by admin)
    $isInvitedClient = false;
    if ($isClient) {
        $isInvitedClient = \App\Models\Invite::where('email', $user->email)
                                ->where('status', 'joined')
                                ->exists();
    }

    // 2. Determine if we should show the full dashboard (charts, stats)
    $showFullDashboard = $isAdmin || $isEmployee || $isInvitedClient;

    // 3. Initialize default data (prevents crashes for Type 2 Added Clients)
    $dashboardData = [
        'totalSent' => 0, 'totalSigned' => 0, 'totalPending' => 0, 'totalClients' => 0,
        'conversionRate' => 0, 'signedPct' => 0, 'pendingPct' => 0, 'otherPct' => 0,
        'chartData' => collect(), 'teamCounts' => null,
    ];

    // 4. Only run heavy database queries if the user is allowed to see the full dashboard
    if ($showFullDashboard) {
        $dashboardData = \Illuminate\Support\Facades\Cache::remember(
            "dashboard_data_{$userId}", 60,
            function () use ($userId, $team, $isAdmin, $isEmployee, $isClient, $isInvitedClient) {
                
                // ── FIX: Query logic for different roles ──
                if ($isClient && $isInvitedClient) {
                    // Invited Client: Find their Client record, then query WaiverSend directly
                    // This avoids the missing 'user_id' column error in waiver_submissions
                    $clientRecord = \App\Models\Client::where('user_id', $userId)->first();
                    
                    if ($clientRecord) {
                        // If client_id stores the Client model's ID
                        $statsQuery = \App\Models\WaiverSend::where('client_id', $clientRecord->id);
                    } else {
                        // Fallback: If client_id accidentally stores the User's ID directly
                        $statsQuery = \App\Models\WaiverSend::where('client_id', $userId);
                    }
                } else if ($isEmployee) {
                    $statsQuery = \App\Models\WaiverSubmission::where('sent_by', $userId);
              } else {
    // Admin
    $statsQuery = \App\Models\WaiverSend::where('sent_by', $userId)->whereNotNull('client_id');
}

                $counts = $statsQuery->selectRaw("
                    COUNT(*) as total,
                    SUM(status = 'signed')  as signed,
                    SUM(status = 'pending') as pending
                ")->first();

                $totalSent    = (int) $counts->total;
                $totalSigned  = (int) $counts->signed;
                $totalPending = (int) $counts->pending;
                $totalClients = $isAdmin ? \App\Models\Client::where('user_id', $userId)->count() : 0;
                $conversionRate = $totalSent > 0 ? round(($totalSigned / $totalSent) * 100) : 0;
                $signedPct      = $totalSent > 0 ? round(($totalSigned  / $totalSent) * 100) : 0;
                $pendingPct     = $totalSent > 0 ? round(($totalPending / $totalSent) * 100) : 0;
                $otherPct       = max(0, 100 - $signedPct - $pendingPct);

                // ── Chart data logic ──
                if ($isEmployee) {
                    $chartQuery = \App\Models\WaiverSubmission::where('sent_by', $userId)
                        ->where('created_at', '>=', now()->subDays(6)->startOfDay());
                } elseif ($isClient && $isInvitedClient) {
                    // FIX: Correct chart query for invited clients
                    $clientRecord = \App\Models\Client::where('user_id', $userId)->first();
                    if ($clientRecord) {
                        $chartQuery = \App\Models\WaiverSend::where('client_id', $clientRecord->id)
                            ->where('created_at', '>=', now()->subDays(6)->startOfDay());
                    } else {
                        $chartQuery = \App\Models\WaiverSend::where('client_id', $userId)
                            ->where('created_at', '>=', now()->subDays(6)->startOfDay());
                    }
              } else {
    // Admin
    $chartQuery = \App\Models\WaiverSend::where('sent_by', $userId)
        ->whereNotNull('client_id')
        ->where('created_at', '>=', now()->subDays(6)->startOfDay());
}

                $chartRaw = $chartQuery
                    ->selectRaw("DATE(created_at) as date, status, COUNT(*) as total")
                    ->groupBy('date', 'status')->get()->groupBy('date');
                
                $chartData = collect();
                for ($i = 6; $i >= 0; $i--) {
                    $date    = now()->subDays($i)->toDateString();
                    $dayRows = $chartRaw->get($date, collect());
                    $chartData->push([
                        'date'   => now()->subDays($i)->format('D'),
                        'sent'   => $dayRows->sum('total'),
                        'signed' => $dayRows->where('status', 'signed')->sum('total'),
                    ]);
                }

                $teamCounts = null;
                if ($team && $isAdmin) {
                    $teamCounts = \App\Models\User::where('team_id', $team->id)
                        ->selectRaw("COUNT(*) as members, SUM(role = 'employee') as employees, SUM(role = 'client') as clients_count")
                        ->first();
                }

                return compact('totalSent','totalSigned','totalPending','totalClients',
                    'conversionRate','signedPct','pendingPct','otherPct','chartData','teamCounts');
            }
        );
    }

    extract($dashboardData);
    $trialEndsAt  = $user->trial_ends_at ?? null;
    $hasActiveSubscription = (bool) $user->subscription;
    $trialExpired = $trialEndsAt && now()->gt($trialEndsAt);
    $trialDaysLeft = $trialEndsAt ? max(0, now()->diffInDays($trialEndsAt, false)) : null;
@endphp

<div class="w-full max-w-full overflow-x-hidden mt-2 mb-2">
    {{-- Flash Messages --}}
    @if (session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span class="text-xs sm:text-sm">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            <span class="text-xs sm:text-sm">{{ session('error') }}</span>
        </div>
    @endif

    {{-- ── KPI CARDS ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-3 lg:gap-4 mb-4">

              {{-- Welcome Card --}}
        <div class="col-span-2 lg:col-span-1 rounded-2xl text-white shadow-md relative min-w-0">
            
            {{-- 1. Background Layer (Clipped for rounded corners) --}}
            <div class="absolute inset-0 rounded-2xl overflow-hidden" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
            </div>

            {{-- 2. Content Layer (Not clipped, allows dropdown to show fully) --}}
            <div class="relative p-4 flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center text-white font-bold text-base shrink-0">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <div class="font-bold text-sm truncate">{{ optional($user->profile)->first_name ?? $user->name }}</div>
                    <div class="text-white/60 text-[11px] mt-0.5">
                        @if($isAdmin) Admin Dashboard @elseif($isEmployee) Employee Portal @else Client Portal @endif
                    </div>
                </div>
                @if($team && $isAdmin)
                <div class="relative" id="company-dropdown-wrap">
                    <button id="company-btn" aria-expanded="false" title="Company info"
                        class="w-8 h-8 rounded-lg bg-white/15 hover:bg-white/25 transition flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </button>
                    <div id="company-panel" class="hidden absolute right-0 top-10 z-50 w-56 bg-white dark:bg-[#0d3d26] rounded-xl shadow-xl border border-gray-100 dark:border-[rgba(45,106,79,0.3)] p-4" style="max-width:calc(100vw - 2rem);">
                        <div class="flex items-center gap-2 mb-3 pb-3 border-b border-gray-100 dark:border-[rgba(45,106,79,0.2)]">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:rgba(45,106,79,0.1);">
                                <svg class="w-4 h-4" style="color:#2D6A4F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $team->name }}</p>
                                <p class="text-xs text-gray-400">Your company</p>
                            </div>
                        </div>
                        @if($teamCounts)
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <div class="text-center bg-gray-50 dark:bg-[rgba(45,106,79,0.1)] rounded-lg py-2">
                                <p class="text-[10px] text-gray-400 mb-0.5">Members</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $teamCounts->members }}</p>
                            </div>
                            <div class="text-center bg-gray-50 dark:bg-[rgba(45,106,79,0.1)] rounded-lg py-2">
                                <p class="text-[10px] text-gray-400 mb-0.5">Staff</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $teamCounts->employees }}</p>
                            </div>
                            <div class="text-center bg-gray-50 dark:bg-[rgba(45,106,79,0.1)] rounded-lg py-2">
                                <p class="text-[10px] text-gray-400 mb-0.5">Clients</p>
                                <p class="text-sm font-bold text-gray-800 dark:text-white">{{ $teamCounts->clients_count }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="rounded-lg px-3 py-2.5 flex items-center justify-between gap-2" style="background:rgba(45,106,79,0.06);">
                            <div class="min-w-0 flex-1">
                                <p class="text-[10px] font-medium mb-0.5" style="color:rgba(45,106,79,0.7);">Invite code</p>
                                <p class="text-base font-bold tracking-widest font-mono" style="color:#2D6A4F;">{{ $team->invite_code }}</p>
                            </div>
                            <button id="copy-invite-btn" data-code="{{ $team->invite_code }}"
                                class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition hover:opacity-80"
                                style="background:rgba(45,106,79,0.1);" title="Copy invite code">
                                <svg id="copy-icon" class="w-4 h-4" style="color:#2D6A4F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        @if($isAdmin)
        {{-- Forms Sent --}}
        <div class="rounded-2xl p-4 text-white shadow-md relative overflow-hidden min-w-0"
             style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
            {{-- <div class="absolute -top-3 -right-3 w-16 h-16 rounded-full bg-white/5"></div> --}}
            <div class="flex items-start justify-between mb-3">
                <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wide">Forms Sent</p>
                <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" style="transform:rotate(90deg)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $totalSent }}</div>
            <div class="text-white/50 text-[11px] mt-1">Total dispatched</div>
        </div>

        {{-- Signed --}}
        <div class="rounded-2xl p-4 text-white shadow-md relative overflow-hidden min-w-0"
             style="background: linear-gradient(135deg, #1a5c3a, #40916c);">
            <div class="flex items-start justify-between mb-3">
                <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wide">KPI Signed</p>
                <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $totalSigned }}</div>
            <div class="text-white/60 text-[11px] mt-1">{{ $conversionRate }}% Conversion</div>
        </div>

        {{-- Clients --}}
        <div class="rounded-2xl p-4 text-white shadow-md relative overflow-hidden min-w-0"
             style="background: linear-gradient(135deg, #2D6A4F, #52b788);">
            <div class="flex items-start justify-between mb-3">
                <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wide">Clients</p>
                <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $totalClients }}</div>
            <div class="text-white/50 text-[11px] mt-1">Active clients</div>
        </div>

        @else
        <div class="col-span-2 rounded-2xl p-4 text-white shadow-md relative overflow-hidden min-w-0"
             style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wide mb-1">Documents Signed</p>
                    <div class="text-3xl font-extrabold text-white">{{ $totalSigned }}</div>
                    <div class="text-white/50 text-[11px] mt-1">Total completed</div>
                </div>
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── CHART + DONUT ROW ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4 mb-4">

        {{-- Activity Line/Area Chart — takes 2/3 width --}}
        <div class="lg:col-span-2 rounded-2xl p-4 sm:p-5 shadow-sm border
                    bg-white dark:bg-[#0d3d26] border-[rgba(45,106,79,0.15)] dark:border-[rgba(45,106,79,0.35)]">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 mb-1">
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-base">Activity Overview – Last 7 Days</h3>
                </div>
                <div class="flex gap-3">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#2D6A4F;"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">{{ $isAdmin ? 'Sent' : 'Received' }}</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-2.5 h-2.5 rounded-full inline-block" style="background:#52b788;"></span>
                        <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Signed</span>
                    </div>
                </div>
            </div>
            {{-- Totals row --}}
            <div class="flex gap-6 mb-4">
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">Total Sent</p>
                    <p class="text-xl font-extrabold text-gray-900 dark:text-white">{{ $totalSent }}</p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 dark:text-gray-500 uppercase tracking-wide">Total Signed</p>
                    <p class="text-xl font-extrabold" style="color:#2D6A4F;">{{ $totalSigned }}</p>
                </div>
            </div>
            <div class="relative w-full" style="height:200px;">
                <canvas id="activityLineChart"></canvas>
            </div>
        </div>

        {{-- Donut Chart — takes 1/3 width --}}
        <div class="rounded-2xl p-4 sm:p-5 shadow-sm border
                    bg-white dark:bg-[#0d3d26] border-[rgba(45,106,79,0.15)] dark:border-[rgba(45,106,79,0.35)]">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm sm:text-base mb-4">Waiver Status</h3>
            <div class="relative flex items-center justify-center" style="height:160px;">
                <canvas id="donutChart"></canvas>
                {{-- Center label --}}
                <div class="absolute flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-2xl font-extrabold text-gray-900 dark:text-white">{{ $totalSent }}</span>
                    <span class="text-[10px] text-gray-400 dark:text-gray-500 font-medium">TOTAL</span>
                </div>
            </div>
            {{-- Legend --}}
            <div class="mt-4 space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#2D6A4F;"></span>
                        <span class="text-xs text-gray-600 dark:text-gray-300">Signed</span>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-white">{{ $totalSigned }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#52b788;"></span>
                        <span class="text-xs text-gray-600 dark:text-gray-300">Pending</span>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-white">{{ $totalPending }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full shrink-0" style="background:#d4a017;"></span>
                        <span class="text-xs text-gray-600 dark:text-gray-300">Other</span>
                    </div>
                    <span class="text-xs font-bold text-gray-800 dark:text-white">{{ max(0, $totalSent - $totalSigned - $totalPending) }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── QUICK ACTIONS + STATUS ROW ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-3 sm:gap-4">

        {{-- Quick Actions — 2/3 --}}
        <div class="lg:col-span-2 rounded-2xl p-4 sm:p-5 shadow-sm border
                    bg-white dark:bg-[#0d3d26] border-[rgba(45,106,79,0.15)] dark:border-[rgba(45,106,79,0.35)]">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-4">Quick Actions</h3>
            <div class="grid grid-cols-3 sm:grid-cols-4 lg:grid-cols-5 gap-4 sm:gap-5">
                @if($isAdmin)
                   <a href="{{ route('admin.invites.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 transition" style="background:linear-gradient(135deg,#0B3D2E,#2D6A4F)">
        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7zM21 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
    </div>
    <span class="text-[10px] sm:text-[11px] font-medium text-gray-700 dark:text-gray-200 text-center leading-tight">Invites</span>
</a>
                    <a href="{{ route('admin.tasks.create') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 transition" style="background:linear-gradient(135deg,#1a5c3a,#40916c)">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <span class="text-[10px] sm:text-[11px] font-medium text-gray-700 dark:text-gray-200 text-center leading-tight">Assign Task</span>
                    </a>
                    <a href="{{ route('admin.salary.create') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 transition" style="background:linear-gradient(135deg,#2D6A4F,#52b788)">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                        </div>
                        <span class="text-[10px] sm:text-[11px] font-medium text-gray-700 dark:text-gray-200 text-center leading-tight">Upload Salary</span>
                    </a>
                    <a href="{{ route('admin.bulk-invite') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 transition" style="background:linear-gradient(135deg,#0B3D2E,#40916c)">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                        </div>
                        <span class="text-[10px] sm:text-[11px] font-medium text-gray-700 dark:text-gray-200 text-center leading-tight">Bulk Invite</span>
                    </a>
                @else
                    <a href="{{ route('waivers.index') }}" class="flex flex-col items-center gap-2 group">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 transition" style="background:linear-gradient(135deg,#0B3D2E,#2D6A4F)">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <span class="text-[10px] sm:text-[11px] font-medium text-gray-700 dark:text-gray-200 text-center leading-tight">My Documents</span>
                    </a>
                @endif
                <a href="{{ route('submissions.index') }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-105 transition" style="background:linear-gradient(135deg,#1a5c3a,#2D6A4F)">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <span class="text-[10px] sm:text-[11px] font-medium text-gray-700 dark:text-gray-200 text-center leading-tight">View Reports</span>
                </a>
            </div>
        </div>

        {{-- Status Overview — 1/3 --}}
        @if($isAdmin)
        <div class="rounded-2xl p-4 sm:p-5 shadow-sm border
                    bg-white dark:bg-[#0d3d26] border-[rgba(45,106,79,0.15)] dark:border-[rgba(45,106,79,0.35)]">
            <h3 class="font-bold text-gray-900 dark:text-white text-sm mb-1">Status Overview</h3>
            <p class="text-gray-400 dark:text-gray-500 text-[10px] mb-4">Current waiver pipeline</p>
            @if($totalSent === 0)
                <div class="text-center py-6">
                    <svg class="w-10 h-10 mx-auto mb-2" style="color:rgba(45,106,79,0.3)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p class="text-gray-400 text-xs">No waivers sent yet.</p>
                    <a href="{{ route('waivers.create') }}" class="text-xs font-semibold mt-1 inline-block hover:underline" style="color:#2D6A4F;">Send your first →</a>
                </div>
            @else
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Signed</span>
                            <span class="text-xs font-bold" style="color:#2D6A4F;">{{ $totalSigned }} ({{ $signedPct }}%)</span>
                        </div>
                        <div class="w-full rounded-full h-2 overflow-hidden" style="background:rgba(45,106,79,0.12);">
                            <div class="h-2 rounded-full transition-all duration-700" style="background:linear-gradient(90deg,#0B3D2E,#2D6A4F);width:{{ $signedPct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Pending</span>
                            <span class="text-xs font-bold" style="color:#40916c;">{{ $totalPending }} ({{ $pendingPct }}%)</span>
                        </div>
                        <div class="w-full rounded-full h-2 overflow-hidden" style="background:rgba(45,106,79,0.12);">
                            <div class="h-2 rounded-full transition-all duration-700" style="background:linear-gradient(90deg,#2D6A4F,#52b788);width:{{ $pendingPct }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-end mb-1.5">
                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300">Other / Draft</span>
                            <span class="text-xs font-bold text-gray-400 dark:text-gray-500">{{ max(0,$totalSent-$totalSigned-$totalPending) }} ({{ $otherPct }}%)</span>
                        </div>
                        <div class="w-full rounded-full h-2 overflow-hidden" style="background:rgba(45,106,79,0.12);">
                            <div class="h-2 rounded-full transition-all duration-700" style="background:rgba(45,106,79,0.35);width:{{ $otherPct }}%"></div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        @endif

    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Company dropdown ──
    const companyBtn  = document.getElementById('company-btn');
    const companyPanel = document.getElementById('company-panel');
    const companyWrap  = document.getElementById('company-dropdown-wrap');
    if (companyBtn && companyPanel) {
        companyBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = companyPanel.classList.toggle('hidden');
            companyBtn.setAttribute('aria-expanded', String(!isHidden));
        });
        document.addEventListener('click', function(e) {
            if (companyWrap && !companyWrap.contains(e.target)) {
                companyPanel.classList.add('hidden');
                companyBtn.setAttribute('aria-expanded', 'false');
            }
        });
    }

    // ── Clipboard copy ──
    const copyBtn = document.getElementById('copy-invite-btn');
    if (copyBtn) {
        copyBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            navigator.clipboard.writeText(this.dataset.code).then(() => {
                const icon = document.getElementById('copy-icon');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
                    setTimeout(() => {
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>';
                    }, 2000);
                }
                this.title = 'Copied!';
                setTimeout(() => { this.title = 'Copy invite code'; }, 2000);
            }).catch(err => console.error('Failed to copy:', err));
        });
    }

    const isDark = document.documentElement.classList.contains('dark');
    const tickColor = isDark ? '#86efac' : '#2D6A4F';
    const gridColor = isDark ? 'rgba(45,106,79,0.1)' : 'rgba(45,106,79,0.07)';

    // ── Area/Line Chart ──
    const lineCanvas = document.getElementById('activityLineChart');
    if (lineCanvas) {
        const lCtx = lineCanvas.getContext('2d');

        const areaGrad1 = lCtx.createLinearGradient(0, 0, 0, 200);
        areaGrad1.addColorStop(0, 'rgba(45,106,79,0.35)');
        areaGrad1.addColorStop(1, 'rgba(45,106,79,0.0)');

        const areaGrad2 = lCtx.createLinearGradient(0, 0, 0, 200);
        areaGrad2.addColorStop(0, 'rgba(82,183,136,0.25)');
        areaGrad2.addColorStop(1, 'rgba(82,183,136,0.0)');

        new Chart(lCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chartData->pluck('date')) !!},
                datasets: [
                    {
                        label: '{{ $isAdmin ? "Sent" : "Received" }}',
                        data: {!! json_encode($chartData->pluck('sent')) !!},
                        borderColor: '#2D6A4F',
                        borderWidth: 2.5,
                        backgroundColor: areaGrad1,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#2D6A4F',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Signed',
                        data: {!! json_encode($chartData->pluck('signed')) !!},
                        borderColor: '#52b788',
                        borderWidth: 2.5,
                        backgroundColor: areaGrad2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#52b788',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? 'rgba(11,61,46,0.95)' : 'rgba(11,61,46,0.90)',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        usePointStyle: true,
                        boxPadding: 4
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        min: 0,
                        ticks: {
                            stepSize: 1, precision: 0,
                            font: { size: 10 },
                            color: tickColor, padding: 8,
                            callback: v => Number.isInteger(v) ? v : null
                        },
                        grid: { color: gridColor, drawBorder: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { font: { size: 10 }, color: tickColor }
                    }
                }
            }
        });
    }

    // ── Donut Chart ──
    const donutCanvas = document.getElementById('donutChart');
    if (donutCanvas) {
        const dCtx = donutCanvas.getContext('2d');
        const signed  = {{ $totalSigned }};
        const pending = {{ $totalPending }};
        const other   = Math.max(0, {{ $totalSent }} - signed - pending);
        const total   = signed + pending + other;

        new Chart(dCtx, {
            type: 'doughnut',
            data: {
                labels: ['Signed', 'Pending', 'Other'],
                datasets: [{
                    data: total > 0 ? [signed, pending, other] : [1, 0, 0],
                    backgroundColor: ['#2D6A4F', '#52b788', '#d4a017'],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: isDark ? 'rgba(11,61,46,0.95)' : 'rgba(11,61,46,0.90)',
                        padding: 10,
                        cornerRadius: 8,
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 }
                    }
                }
            }
        });
    }

});
</script>
@endpush