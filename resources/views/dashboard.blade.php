<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dashboard – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-gradient { background: linear-gradient(160deg, #0f4c75 0%, #1b6ca8 50%, #0a9396 100%); }
        .nav-active { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-item { color: rgba(255,255,255,0.65); transition: all 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        .card-orange { background: linear-gradient(135deg, #f97316 0%, #fb923c 100%); }
        .card-teal   { background: linear-gradient(135deg, #0a9396 0%, #14b8a6 100%); }
        .card-blue   { background: linear-gradient(135deg, #1b6ca8 0%, #3b82f6 100%); }
        .card-purple { background: linear-gradient(135deg, #7c3aed 0%, #a78bfa 100%); }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex font-sans antialiased">

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    {{-- ── Sidebar ── --}}
    <aside id="sidebar" class="sidebar-gradient fixed inset-y-0 left-0 z-50 w-60 transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col shadow-2xl">

        {{-- Logo --}}
        <div class="px-5 py-5 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="font-bold text-white text-base tracking-tight">Onboardify</span>
            </div>
            <button id="close-sidebar" class="md:hidden text-white/60 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- User Profile Card --}}
        <div class="mx-4 mb-4 bg-white/10 backdrop-blur rounded-2xl p-4 border border-white/10">
            <div class="flex items-center gap-3">
                <div class="w-11 h-11 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center text-white font-bold text-base shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <div class="text-white font-semibold text-sm truncate">{{ optional(auth()->user()->profile)->first_name ?? auth()->user()->name }}</div>
                    <div class="text-white/50 text-[11px] truncate">WELCOME TO DASHBOARD</div>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 space-y-0.5 overflow-y-auto">
            <a href="{{ route('dashboard') }}"
               class="nav-active flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
            <a href="{{ route('waivers.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Documents
            </a>
            <a href="{{ route('clients.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Clients
            </a>
            <a href="{{ route('templates.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                </svg>
                Templates
            </a>
            <a href="{{ route('submissions.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                View Reports
            </a>

            {{-- Divider --}}
            <div class="border-t border-white/10 my-3"></div>

            <a href="{{ route('chats') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                Messages
            </a>
            <a href="/profile" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Settings
            </a>
        </nav>

        {{-- Logout --}}
        <div class="p-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium nav-item">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Content ── --}}
    <main class="flex-1 md:ml-60 min-h-screen flex flex-col">

        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-200 px-4 md:px-8 h-14 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <button id="mobile-menu-btn" class="md:hidden text-gray-500 hover:text-gray-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-sm font-semibold text-gray-500 hidden md:block">Home / <span class="text-gray-900">Dashboard</span></h1>
            <div class="flex items-center gap-3 ml-auto">
                <a href="{{ route('waivers.create') }}"
                   class="flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm"
                   style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Waiver
                </a>
            </div>
        </header>

        <div class="flex-1 p-4 md:p-8">

            {{-- Flash --}}
            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Trial Banner --}}
            @if (!auth()->user()->subscription)
                <div class="bg-blue-50 border border-blue-100 text-blue-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        You are on a <strong class="ml-1">7-day free trial</strong>. Add payment to keep access.
                    </div>
                    <a href="{{ route('plans') }}" class="text-blue-700 font-semibold text-xs hover:underline whitespace-nowrap">Upgrade →</a>
                </div>
            @endif

            {{-- Stats --}}
            @php
                $totalSent    = \App\Models\WaiverSend::where('sent_by', auth()->id())->count();
                $totalSigned  = \App\Models\WaiverSend::where('sent_by', auth()->id())->where('status','signed')->count();
                $totalPending = \App\Models\WaiverSend::where('sent_by', auth()->id())->where('status','pending')->count();
                $totalClients = \App\Models\Client::where('user_id', auth()->id())->count();
                $recentWaivers = \App\Models\Waiver::where('user_id', auth()->id())->latest()->take(5)->get();
                $recentSigned  = \App\Models\WaiverSend::with('waiver')->where('sent_by', auth()->id())->where('status','signed')->latest()->take(5)->get();
                $conversionRate = $totalSent > 0 ? round(($totalSigned / $totalSent) * 100, 0) : 0;
            @endphp

            {{-- Welcome + Stats Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-5 mb-6">

                {{-- Welcome Card --}}
                <div class="lg:col-span-1 rounded-2xl p-6 text-white shadow-lg flex flex-col justify-between" style="background: linear-gradient(135deg, #0f4c75 0%, #0a9396 100%);">
                    <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xl mb-4">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-bold text-lg leading-tight">{{ optional(auth()->user()->profile)->first_name ?? auth()->user()->name }}</div>
                        <div class="text-white/60 text-xs mt-0.5 uppercase tracking-wide">Welcome back</div>
                    </div>
                    <div class="mt-4 pt-4 border-t border-white/20 flex items-center justify-between">
                        <span class="text-white/60 text-xs">{{ now()->format('l, d M Y') }}</span>
                    </div>
                </div>

                {{-- Stat: Sent --}}
                <div class="card-orange rounded-2xl p-5 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <span class="text-white/70 text-xs font-medium bg-white/10 px-2 py-0.5 rounded-full">Total</span>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">{{ $totalSent }}</div>
                    <div class="text-white/80 text-sm font-medium">Forms Sent</div>
                    <div class="mt-3 w-full bg-white/20 rounded-full h-1.5">
                        <div class="bg-white h-1.5 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                {{-- Stat: Signed --}}
                <div class="card-teal rounded-2xl p-5 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-white/70 text-xs font-medium bg-white/10 px-2 py-0.5 rounded-full">{{ $conversionRate }}%</span>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">{{ $totalSigned }}</div>
                    <div class="text-white/80 text-sm font-medium">Signed</div>
                    <div class="mt-3 w-full bg-white/20 rounded-full h-1.5">
                        <div class="bg-white h-1.5 rounded-full" style="width: {{ $conversionRate }}%"></div>
                    </div>
                </div>

                {{-- Stat: Clients --}}
                <div class="card-blue rounded-2xl p-5 text-white shadow-lg">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-9 h-9 bg-white/20 rounded-xl flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <span class="text-white/70 text-xs font-medium bg-white/10 px-2 py-0.5 rounded-full">Active</span>
                    </div>
                    <div class="text-4xl font-extrabold mb-1">{{ $totalClients }}</div>
                    <div class="text-white/80 text-sm font-medium">Clients</div>
                    <div class="mt-3 w-full bg-white/20 rounded-full h-1.5">
                        <div class="bg-white h-1.5 rounded-full" style="width: 75%"></div>
                    </div>
                </div>
            </div>

            {{-- Charts + Activity Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-6">

                {{-- Activity Chart --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-gray-900">Submission Activity</h3>
                            <p class="text-gray-400 text-xs mt-0.5">Waivers sent & signed over time</p>
                        </div>
                        <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1.5 rounded-lg font-medium">Last 7 Days</span>
                    </div>
                    <div style="height: 200px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>

                {{-- Status Donut --}}
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="font-bold text-gray-900">Status</h3>
                            <p class="text-gray-400 text-xs mt-0.5">Distribution</p>
                        </div>
                    </div>
                    <div class="flex justify-center" style="height: 160px;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="flex justify-center gap-4 mt-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-teal-500"></span>
                            <span class="text-xs text-gray-600">Signed</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-orange-400"></span>
                            <span class="text-xs text-gray-600">Pending</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-2.5 h-2.5 rounded-full bg-gray-300"></span>
                            <span class="text-xs text-gray-600">Other</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

                {{-- Quick Actions --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <h3 class="font-bold text-gray-900 text-sm mb-4">Quick Actions</h3>
                    <div class="space-y-2">
                        <a href="{{ route('waivers.create') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition group border border-gray-100 hover:border-blue-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Create Waiver</span>
                            <svg class="w-3.5 h-3.5 text-gray-300 ml-auto group-hover:text-blue-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('waivers.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition group border border-gray-100 hover:border-teal-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #0a9396, #14b8a6)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">View Documents</span>
                            <svg class="w-3.5 h-3.5 text-gray-300 ml-auto group-hover:text-teal-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="{{ route('submissions.index') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition group border border-gray-100 hover:border-orange-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #f97316, #fb923c)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">View Reports</span>
                            <svg class="w-3.5 h-3.5 text-gray-300 ml-auto group-hover:text-orange-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                        <a href="/profile/create"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-gray-50 transition group border border-gray-100 hover:border-purple-100">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #7c3aed, #a78bfa)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <span class="text-sm font-medium text-gray-700">Edit Profile</span>
                            <svg class="w-3.5 h-3.5 text-gray-300 ml-auto group-hover:text-purple-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Recent Waivers --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900 text-sm">Recent Waivers</h3>
                        <a href="{{ route('waivers.index') }}" class="text-teal-600 text-xs font-medium hover:underline">View all →</a>
                    </div>
                    @if($recentWaivers->isEmpty())
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-xs mb-3">No waivers yet</p>
                            <a href="{{ route('waivers.create') }}"
                               class="text-white text-xs font-semibold px-4 py-2 rounded-lg transition"
                               style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                Create first waiver
                            </a>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($recentWaivers as $waiver)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                        <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <span class="text-xs font-medium text-gray-800 truncate">{{ $waiver->title }}</span>
                                </div>
                                <a href="{{ route('waivers.show', $waiver) }}" class="text-teal-600 text-[10px] font-semibold hover:underline shrink-0 ml-2">Open</a>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Recent Signatures --}}
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-gray-900 text-sm">Recent Signatures</h3>
                        <a href="{{ route('submissions.index') }}" class="text-teal-600 text-xs font-medium hover:underline">View all →</a>
                    </div>
                    @if($recentSigned->isEmpty())
                        <div class="flex flex-col items-center justify-center py-8 text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-xs mb-3">No signatures yet</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            @foreach($recentSigned as $send)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                                <div class="flex items-center gap-2.5 min-w-0">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-teal-400 to-blue-500 flex items-center justify-center text-white text-[10px] font-bold shrink-0">
                                        {{ strtoupper(substr($send->client_name ?? '?', 0, 1)) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-xs font-medium text-gray-800 truncate">{{ $send->client_name }}</div>
                                        <div class="text-[10px] text-gray-400 truncate">{{ optional($send->waiver)->title }}</div>
                                    </div>
                                </div>
                                <span class="bg-teal-100 text-teal-700 text-[10px] font-semibold px-2 py-0.5 rounded-full shrink-0 ml-2">Signed</span>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </main>

    {{-- Chatbot Button --}}
    <!-- <a href="{{ route('chats') }}" class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-50">
        <button class="w-12 h-12 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-200 transform hover:scale-105"
                style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2a2 2 0 012 2c0 .74-.4 1.387-1 1.731V7h1a7 7 0 017 7v1a3 3 0 01-3 3h-1v1a2 2 0 01-2 2H9a2 2 0 01-2-2v-1H6a3 3 0 01-3-3v-1a7 7 0 017-7h1V5.731A2 2 0 0112 2zm0 7a5 5 0 00-5 5v1a1 1 0 001 1h8a1 1 0 001-1v-1a5 5 0 00-5-5zm-2 4a1 1 0 110 2 1 1 0 010-2zm4 0a1 1 0 110 2 1 1 0 010-2z"/>
            </svg>
        </button>
    </a> -->

    <script>
        // Sidebar toggle
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const closeSidebarBtn = document.getElementById('close-sidebar');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            function toggleSidebar() {
                sidebar.classList.toggle('-translate-x-full');
                overlay.classList.toggle('hidden');
            }
            if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
            if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);

            // Activity Chart
            const actCtx = document.getElementById('activityChart').getContext('2d');
            new Chart(actCtx, {
                type: 'line',
                data: {
                    labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
                    datasets: [
                        {
                            label: 'Sent',
                            data: [3,5,4,7,6,8,5],
                            borderColor: '#1b6ca8',
                            backgroundColor: 'rgba(27,108,168,0.08)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#1b6ca8',
                            pointRadius: 4,
                        },
                        {
                            label: 'Signed',
                            data: [1,3,2,5,4,6,3],
                            borderColor: '#0a9396',
                            backgroundColor: 'rgba(10,147,150,0.08)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#0a9396',
                            pointRadius: 4,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 11 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 11 } } }
                    }
                }
            });

            // Status Donut
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Signed', 'Pending', 'Other'],
                    datasets: [{
                        data: [{{ $totalSigned }}, {{ $totalPending }}, Math.max(0, {{ $totalSent }} - {{ $totalSigned }} - {{ $totalPending }})],
                        backgroundColor: ['#0a9396', '#f97316', '#e5e7eb'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>

</body>
</html>