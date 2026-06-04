<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard – Onboardify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex font-sans antialiased">

    {{-- ── Mobile Overlay ──────────────────────────────────────── --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity"></div>

    {{-- ── Sidebar ─────────────────────────────────────────────── --}}
    <aside id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col">
        {{-- Logo & Close Button (Mobile) --}}
        <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-xl">📋</span>
                <span class="text-gray-900 font-bold text-base tracking-tight">Onboardify</span>
            </div>
            <button id="close-sidebar" class="md:hidden text-gray-500 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Nav Links --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <a href="/dashboard" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-semibold bg-blue-600 text-white shadow-sm transition-all duration-200">
                <span>🏠</span> Dashboard
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200">
                <span>📝</span> Documents
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200">
                <span>👥</span> Clients
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200">
                <span>📄</span> Templates
            </a>
            <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200">
                <span>✍️</span> Signatures
            </a>
            <a href="/profile" class="flex items-center gap-3 px-3 py-2 rounded-lg text-xs font-medium text-gray-600 hover:bg-gray-100 hover:text-gray-900 transition-all duration-200">
                <span>⚙️</span> Settings
            </a>
        </nav>

        {{-- User Info --}}
        <div class="px-4 py-3 border-t border-gray-200">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0 border border-blue-200">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-gray-900 text-xs font-semibold truncate">{{ auth()->user()->name }}</div>
                    <div class="text-gray-500 text-[10px] truncate">{{ auth()->user()->email }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="w-full text-left text-gray-500 hover:text-red-600 hover:bg-red-50 text-[11px] font-medium transition flex items-center gap-2 px-2 py-1.5 rounded-md">
                    <span>🚪</span> Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Content ─────────────────────────────────────────── --}}
    <main class="flex-1 md:ml-64 p-3 md:p-6 pt-14 md:pt-6 transition-all duration-300">

        {{-- Mobile Header --}}
        <div class="md:hidden fixed top-0 left-0 right-0 z-30 bg-white border-b border-gray-200 px-3 h-14 flex items-center justify-between">
            <button id="mobile-menu-btn" class="text-gray-600 hover:text-gray-900 p-1.5 -ml-1.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <span class="text-gray-900 font-bold text-base tracking-tight">Onboardify</span>
            <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold border border-blue-200">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs px-3 py-2 rounded-lg mb-4 flex items-center gap-2 shadow-sm">
                <span class="flex-shrink-0">✅</span> {{ session('success') }}
            </div>
        @endif

        {{-- Header --}}
        <div class="mb-4">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div>
                    <h1 class="text-xl md:text-2xl font-bold text-gray-900">
                        Welcome, {{ optional(auth()->user()->profile)->first_name ?? auth()->user()->name }}! 
                    </h1>
                    <p class="text-gray-500 text-xs md:text-sm mt-0.5">Track Your Team's Progress.</p>
                </div>
                <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs md:text-sm font-semibold px-3 py-2 rounded-lg transition shadow-sm flex items-center justify-center gap-1.5 whitespace-nowrap">
                    <span>+</span> New Waiver
                </button>
            </div>

            @if (!auth()->user()->subscription)
                <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs px-3 py-2 rounded-lg mt-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 shadow-sm">
                    <div class="flex items-start sm:items-center gap-2">
                        <span class="flex-shrink-0">⏳</span>
                        <span>You are on a <strong>7-day free trial</strong>. Add payment to keep access.</span>
                    </div>
                    <a href="/plans" class="text-amber-700 hover:text-amber-900 text-[10px] font-bold underline transition whitespace-nowrap">
                        Upgrade Now →
                    </a>
                </div>
            @endif
        </div>

        {{-- Stats Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
            <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="text-xl sm:text-2xl mb-1">📝</div>
                <div class="text-lg sm:text-xl font-bold text-gray-900">0</div>
                <div class="text-gray-500 text-[10px] sm:text-xs mt-0.5 font-medium">Waivers Sent</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="text-xl sm:text-2xl mb-1">✍️</div>
                <div class="text-lg sm:text-xl font-bold text-gray-900">0</div>
                <div class="text-gray-500 text-[10px] sm:text-xs mt-0.5 font-medium">Signatures Collected</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="text-xl sm:text-2xl mb-1">⏳</div>
                <div class="text-lg sm:text-xl font-bold text-gray-900">0</div>
                <div class="text-gray-500 text-[10px] sm:text-xs mt-0.5 font-medium">Pending Signatures</div>
            </div>
            <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm hover:shadow-md transition-shadow duration-200">
                <div class="text-xl sm:text-2xl mb-1">👥</div>
                <div class="text-lg sm:text-xl font-bold text-gray-900">0</div>
                <div class="text-gray-500 text-[10px] sm:text-xs mt-0.5 font-medium">Total Clients</div>
            </div>
        </div>

    {{-- Quick Actions + Account Info --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4">

    {{-- Quick Actions --}}
    <div class="bg-white border border-gray-200 rounded-lg p-3 shadow-sm">
        <h2 class="text-gray-900 font-bold text-sm mb-3">Quick Actions</h2>
        <div class="grid grid-cols-3 gap-2">
            

           <a href="{{ route('waivers.create') }}">
            
            
    <button class="bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-lg p-2.5 text-center transition-all duration-200 group">
        <div class="text-xl mb-1 group-hover:scale-110 transition-transform">🧾</div>
        <div class="text-gray-700 group-hover:text-blue-700 text-[10px] font-semibold leading-tight">Create Waiver</div>
    </button>
</a>



            <button class="bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-lg p-2.5 text-center transition-all duration-200 group">
                <div class="text-xl mb-1 group-hover:scale-110 transition-transform">👤</div>
                <div class="text-gray-700 group-hover:text-blue-700 text-[10px] font-semibold leading-tight">Invite Client</div>
            </button>
            <button class="bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-lg p-2.5 text-center transition-all duration-200 group">
                <div class="text-xl mb-1 group-hover:scale-110 transition-transform">📑</div>
                <div class="text-gray-700 group-hover:text-blue-700 text-[10px] font-semibold leading-tight">View Docs</div>
            </button>
            <button class="bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-lg p-2.5 text-center transition-all duration-200 group">
                <div class="text-xl mb-1 group-hover:scale-110 transition-transform">🧩</div>
                <div class="text-gray-700 group-hover:text-blue-700 text-[10px] font-semibold leading-tight">New Template</div>
            </button>
            <button class="bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-lg p-2.5 text-center transition-all duration-200 group">
                <div class="text-xl mb-1 group-hover:scale-110 transition-transform">📊</div>
                <div class="text-gray-700 group-hover:text-blue-700 text-[10px] font-semibold leading-tight">Reports</div>
            </button>
            <a href="/profile/create" class="bg-gray-50 hover:bg-blue-50 border border-gray-200 hover:border-blue-200 rounded-lg p-2.5 text-center transition-all duration-200 group">
                <div class="text-xl mb-1 group-hover:scale-110 transition-transform">⚙️</div>
                <div class="text-gray-700 group-hover:text-blue-700 text-[10px] font-semibold leading-tight">Edit Profile</div>
            </a>
        </div>
    </div>

        {{-- Recent Activity --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Recent Waivers --}}
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-gray-900 font-bold text-sm">Recent Waivers</h2>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-[10px] font-semibold transition whitespace-nowrap">View all →</a>
                </div>
                <div class="flex flex-col items-center justify-center py-4 sm:py-6 text-center px-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center text-xl sm:text-2xl mb-2">📝</div>
                    <p class="text-gray-500 text-xs font-medium">No waivers sent yet</p>
                    <button class="mt-3 bg-blue-600 hover:bg-blue-700 text-white text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                        Create first waiver
                    </button>
                </div>
            </div>

            {{-- Recent Signatures --}}
            <div class="bg-white border border-gray-200 rounded-xl p-3 sm:p-4 shadow-sm">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="text-gray-900 font-bold text-sm">Recent Signatures</h2>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-[10px] font-semibold transition whitespace-nowrap">View all →</a>
                </div>
                <div class="flex flex-col items-center justify-center py-4 sm:py-6 text-center px-2">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gray-100 rounded-full flex items-center justify-center text-xl sm:text-2xl mb-2">✍️</div>
                    <p class="text-gray-500 text-xs font-medium">No signatures collected yet</p>
                    <button class="mt-3 bg-blue-600 hover:bg-blue-700 text-white text-[10px] sm:text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                        Invite first client
                    </button>
                </div>
            </div>
        </div>

    </main>

    {{-- Chatbot Button --}}
    <a href="{{ route('chats') }}" class="fixed bottom-4 right-4 md:bottom-6 md:right-6 z-50">
        <button id="chatbot-btn" class="w-10 h-10 md:w-12 md:h-12 bg-blue-600 hover:bg-blue-700 text-white rounded-full shadow-xl flex items-center justify-center transition-all duration-200 transform hover:scale-105 hover:shadow-2xl">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 2a2 2 0 012 2c0 .74-.4 1.387-1 1.731V7h1a7 7 0 017 7v1a3 3 0 01-3 3h-1v1a2 2 0 01-2 2H9a2 2 0 01-2-2v-1H6a3 3 0 01-3-3v-1a7 7 0 017-7h1V5.731A2 2 0 0112 2zm0 7a5 5 0 00-5 5v1a1 1 0 001 1h8a1 1 0 001-1v-1a5 5 0 00-5-5zm-2 4a1 1 0 110 2 1 1 0 010-2zm4 0a1 1 0 110 2 1 1 0 010-2z"/>
            </svg>
        </button>
    </a>

    {{-- Mobile Menu Script --}}
    <script>
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
        });
    </script>

</body>
</html>