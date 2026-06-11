<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Messages – Onboardify</title>
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
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex font-sans antialiased">

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    {{-- ── Sidebar (Same as Dashboard) ── --}}
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
                    <div class="text-white/50 text-[11px] truncate">MESSAGES</div>
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
                Signatures
            </a>

            {{-- Divider --}}
            <div class="border-t border-white/10 my-3"></div>

            <a href="{{ route('chats') }}" class="nav-active flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
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
            <h1 class="text-sm font-semibold text-gray-500 hidden md:block">Home / <span class="text-gray-900">Messages</span> / <span class="text-gray-900">New Chat</span></h1>
            <div class="flex items-center gap-3 ml-auto">
                <a href="{{ route('chats') }}"
                   class="flex items-center gap-1.5 text-gray-700 text-sm font-semibold px-4 py-2 rounded-lg transition border border-gray-200 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Chats
                </a>
            </div>
        </header>

        <div class="flex-1 p-4 md:p-8">

            {{-- Chat Container --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex" style="height: calc(100vh - 140px);">

                {{-- ── Sidebar ── --}}
                <div class="w-80 border-r border-gray-100 flex flex-col shrink-0">

                    {{-- Header --}}
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <h2 class="font-bold text-gray-900 text-base">All Users</h2>
                            <span class="text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                {{ count($users) }}
                            </span>
                        </div>
                    </div>

                    {{-- Search --}}
                    <div class="px-4 py-3 border-b border-gray-50">
                        <div class="relative">
                            <svg class="w-3.5 h-3.5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" id="search-users" placeholder="Search users"
                                class="w-full pl-8 pr-3 py-2 bg-gray-100 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500/20 transition">
                        </div>
                    </div>

                    {{-- User List --}}
                    <div class="flex-1 overflow-y-auto" id="user-list">
                        @forelse($users as $u)
                        <a href="{{ route('chat.show', $u) }}"
                           class="flex items-center gap-3 px-4 py-3.5 hover:bg-gray-50 border-b border-gray-50 transition group user-item"
                           data-name="{{ strtolower($u->name) }}">
                            <div class="relative shrink-0">
                                <div class="w-10 h-10 rounded-full text-white flex items-center justify-center font-semibold text-sm" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-gray-300 border-2 border-white rounded-full"></span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-gray-900 text-sm truncate">{{ $u->name }}</span>
                                    <svg class="w-4 h-4 text-gray-300 group-hover:text-blue-500 transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </div>
                                <div class="text-gray-400 text-xs truncate mt-0.5">{{ $u->email }}</div>
                            </div>
                        </a>
                        @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background: linear-gradient(135deg, rgba(27,108,168,0.1), rgba(10,147,150,0.1))">
                                <svg class="w-6 h-6" style="color: #1b6ca8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No users yet</p>
                        </div>
                        @endforelse
                    </div>

                </div>

                {{-- ── Empty State (no chat selected) ── --}}
                <div class="flex-1 flex flex-col items-center justify-center bg-gray-50/50">
                    <div class="w-16 h-16 rounded-3xl flex items-center justify-center mb-5" style="background: linear-gradient(135deg, rgba(27,108,168,0.1), rgba(10,147,150,0.1))">
                        <svg class="w-8 h-8" style="color: #1b6ca8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-1">Start a Conversation</h3>
                    <p class="text-gray-500 text-sm text-center max-w-xs">Select a user from the left to start messaging</p>
                </div>

            </div>

        </div>
    </main>

    <script>
        // Search filter
        document.getElementById('search-users').addEventListener('input', function() {
            const q = this.value.toLowerCase();
            document.querySelectorAll('.user-item').forEach(item => {
                item.style.display = item.dataset.name.includes(q) ? '' : 'none';
            });
        });

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
        });
    </script>

</body>
</html>