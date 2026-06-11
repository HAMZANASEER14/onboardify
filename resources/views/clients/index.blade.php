<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clients – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-gradient { background: linear-gradient(160deg, #0f4c75 0%, #1b6ca8 50%, #0a9396 100%); }
        .nav-active { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-item { color: rgba(255,255,255,0.65); transition: all 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .client-row:hover .row-actions { opacity: 1; }
        .row-actions { opacity: 0; transition: opacity 0.2s; }
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
            <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
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
            <a href="{{ route('clients.index') }}" class="nav-active flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
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
                Signatures
            </a>
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
                <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium nav-item">
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
            <h1 class="text-sm font-semibold text-gray-500 hidden md:block">Home / <span class="text-gray-900">Clients</span></h1>
            <div class="flex items-center gap-3 ml-auto">
                <a href="{{ route('clients.create') }}"
                   class="flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm"
                   style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Client
                </a>
            </div>
        </header>

        <div class="flex-1 p-4 md:p-8">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Page Title + Stats --}}
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900">Clients</h2>
                <p class="text-gray-500 text-sm mt-1">Manage and track all your clients in one place.</p>
            </div>

            {{-- Stats Row --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $clients->count() }}</div>
                        <div class="text-xs text-gray-500 font-medium">Total Clients</div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #0a9396, #14b8a6)">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $clients->where('status','active')->count() }}</div>
                        <div class="text-xs text-gray-500 font-medium">Active</div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 border border-gray-100 shadow-sm flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #f97316, #fb923c)">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-2xl font-extrabold text-gray-900">{{ $clients->where('created_at', '>=', now()->startOfMonth())->count() }}</div>
                        <div class="text-xs text-gray-500 font-medium">Added This Month</div>
                    </div>
                </div>
            </div>

            @if($clients->isEmpty())
                {{-- Empty State --}}
                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-16 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-gray-800 font-bold text-base mb-1">No clients yet</h3>
                    <p class="text-gray-400 text-sm mb-6">Add your first client to start sending documents.</p>
                    <a href="{{ route('clients.create') }}"
                       class="inline-flex items-center gap-2 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm"
                       style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add First Client
                    </a>
                </div>
            @else
                {{-- Search Bar --}}
                <div class="mb-4 flex items-center gap-3">
                    <div class="relative flex-1 max-w-sm">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input id="client-search" type="text" placeholder="Search clients..."
                               class="w-full pl-9 pr-4 py-2 text-sm bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-100 focus:border-blue-300 transition">
                    </div>
                    <span class="text-xs text-gray-400 font-medium">{{ $clients->count() }} clients</span>
                </div>

                {{-- Clients Table --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <table class="w-full text-sm" id="clients-table">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/70">
                                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Client</th>
                                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden sm:table-cell">Phone</th>
                                <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 uppercase tracking-wide hidden md:table-cell">Added</th>
                                <th class="px-6 py-3.5"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($clients as $client)
                            <tr class="client-row hover:bg-gray-50/70 transition group">
                                {{-- Avatar + Name --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                             style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                            {{ strtoupper(substr($client->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-gray-900 text-sm">{{ $client->name }}</span>
                                    </div>
                                </td>
                                {{-- Email --}}
                                <td class="px-6 py-4 text-gray-500 text-sm">{{ $client->email }}</td>
                                {{-- Phone --}}
                                <td class="px-6 py-4 text-gray-500 text-sm hidden sm:table-cell">
                                    {{ $client->phone ?? '—' }}
                                </td>
                                {{-- Date --}}
                                <td class="px-6 py-4 text-gray-400 text-xs hidden md:table-cell">
                                    {{ $client->created_at->format('M d, Y') }}
                                </td>
                                {{-- Actions --}}
                                <td class="px-6 py-4">
                                    <div class="row-actions flex items-center justify-end gap-2">
                                        {{-- Send Waiver --}}
                                        <a href="{{ route('waivers.index') }}?client={{ $client->id }}"
                                           class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg text-white transition shadow-sm"
                                           style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                            Send
                                        </a>
                                        {{-- Delete --}}
                                        <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                              onsubmit="return confirm('Delete {{ $client->name }}? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit"
                                                    class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-50 hover:bg-red-100 border border-red-100 text-red-500 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </main>

    {{-- Chat Button --}}
    <a href="{{ route('chats') }}" class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-50">
        <button class="w-12 h-12 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-200 transform hover:scale-105"
                style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2a2 2 0 012 2c0 .74-.4 1.387-1 1.731V7h1a7 7 0 017 7v1a3 3 0 01-3 3h-1v1a2 2 0 01-2 2H9a2 2 0 01-2-2v-1H6a3 3 0 01-3-3v-1a7 7 0 017-7h1V5.731A2 2 0 0112 2zm0 7a5 5 0 00-5 5v1a1 1 0 001 1h8a1 1 0 001-1v-1a5 5 0 00-5-5zm-2 4a1 1 0 110 2 1 1 0 010-2zm4 0a1 1 0 110 2 1 1 0 010-2z"/>
            </svg>
        </button>
    </a>

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

            // Live search
            const searchInput = document.getElementById('client-search');
            if (searchInput) {
                searchInput.addEventListener('input', function () {
                    const query = this.value.toLowerCase();
                    document.querySelectorAll('#clients-table tbody tr').forEach(row => {
                        const name  = row.querySelector('td:first-child')?.textContent.toLowerCase() ?? '';
                        const email = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() ?? '';
                        row.style.display = (name.includes(query) || email.includes(query)) ? '' : 'none';
                    });
                });
            }
        });
    </script>

</body>
</html>