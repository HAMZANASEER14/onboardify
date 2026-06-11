<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat with {{ $user->name }} – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    @vite('resources/css/app.css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-gradient { background: linear-gradient(160deg, #0f4c75 0%, #1b6ca8 50%, #0a9396 100%); }
        .nav-active { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-item { color: rgba(255,255,255,0.65); transition: all 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        #messages { scroll-behavior: smooth; }
        #messages::-webkit-scrollbar { width: 4px; }
        #messages::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .bubble-mine { border-radius: 18px 18px 4px 18px; background: linear-gradient(135deg, #1b6ca8, #0a9396); }
        .bubble-theirs { border-radius: 18px 18px 18px 4px; }
        #msg-input::-webkit-scrollbar { display: none; }
        .message-enter { animation: fadeUp 0.2s ease; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<!-- <body class="bg-gray-100 text-gray-900 min-h-screen flex font-sans antialiased">

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
    </aside> -->

    {{-- ── Main Content ── --}}
    <main class="flex-1 md:ml-60 min-h-screen flex flex-col">
<!-- 
        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-200 px-4 md:px-8 h-14 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <button id="mobile-menu-btn" class="md:hidden text-gray-500 hover:text-gray-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-sm font-semibold text-gray-500 hidden md:block">Home / <span class="text-gray-900">Messages</span> / <span class="text-gray-900">{{ $user->name }}</span></h1>
            <div class="flex items-center gap-3 ml-auto">
                <a href="{{ route('chats') }}"
                   class="flex items-center gap-1.5 text-gray-700 text-sm font-semibold px-4 py-2 rounded-lg transition border border-gray-200 hover:bg-gray-50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    All Chats
                </a>
            </div>
        </header> -->

        <div class="flex-1 p-4 md:p-8">

            {{-- Chat Container --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col" style="height: calc(120vh - 100px);">

                {{-- Chat Header --}}
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <div class="w-11 h-11 rounded-full text-white flex items-center justify-center font-semibold text-sm" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 border-2 border-white rounded-full"></span>
                        </div>
                        <div>
                            <div class="font-bold text-gray-900">{{ $user->name }}</div>
                            <div class="text-gray-400 text-xs">{{ strtolower(str_replace(' ', '', $user->name)) }}</div>
                        </div>
                    </div>
                </div>

                {{-- Messages --}}
                <div id="messages" class="flex-1 overflow-y-auto px-6 py-5 space-y-4 bg-gray-50/50">

                    @php $lastDate = null; @endphp

                    @foreach ($messages as $msg)
                        @php
                            $msgDate = $msg->created_at->format('Y-m-d');
                            $isMine  = $msg->user_id === auth()->id();
                            $today   = now()->format('Y-m-d');
                            $yesterday = now()->subDay()->format('Y-m-d');
                        @endphp

                        {{-- Date Separator --}}
                        @if($lastDate !== $msgDate)
                            <div class="flex items-center gap-3 my-4">
                                <div class="flex-1 h-px bg-gray-200"></div>
                                <span class="text-gray-400 text-xs font-medium px-2">
                                    @if($msgDate === $today) Today
                                    @elseif($msgDate === $yesterday) Yesterday
                                    @else {{ $msg->created_at->format('M d, Y') }}
                                    @endif
                                </span>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>
                            @php $lastDate = $msgDate; @endphp
                        @endif

                        {{-- Message --}}
                        <div class="flex items-end gap-2 {{ $isMine ? 'flex-row-reverse' : '' }}">
                            @if(!$isMine)
                                <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-semibold text-xs shrink-0 mb-1" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} max-w-xs lg:max-w-md">
                                @if(!$isMine)
                                    <span class="text-xs font-medium text-gray-600 mb-1 ml-1">{{ $user->name }}
                                        <span class="text-gray-400 font-normal ml-1">{{ $msg->created_at->format('l, h:i A') }}</span>
                                    </span>
                                @endif
                                <div class="px-4 py-2.5 text-sm leading-relaxed word-break
                                    {{ $isMine
                                        ? 'text-white bubble-mine'
                                        : 'bg-white text-gray-800 bubble-theirs border border-gray-100' }}">
                                    {{ $msg->content ?? $msg->body }}
                                </div>
                                @if($isMine)
                                    <span class="text-[10px] text-gray-400 mt-1 mr-1">{{ $msg->created_at->format('h:i A') }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Empty state --}}
                    @if($messages->isEmpty())
                        <div class="flex flex-col items-center justify-center h-full py-20 text-center">
                            <div class="w-16 h-16 rounded-3xl flex items-center justify-center mb-4" style="background: linear-gradient(135deg, rgba(27,108,168,0.1), rgba(10,147,150,0.1))">
                                <svg class="w-8 h-8" style="color: #1b6ca8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No messages yet</p>
                            <p class="text-gray-400 text-xs mt-1">Say hi to {{ $user->name }}!</p>
                        </div>
                    @endif

                </div>

                {{-- Input Area --}}
                <div class="px-6 py-4 border-t border-gray-100 bg-white shrink-0">
                    <div class="flex items-end gap-3">
                        <div class="flex-1 relative">
                            <textarea
                                id="msg-input"
                                rows="1"
                                placeholder="Type your message..."
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm text-gray-800 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition resize-none overflow-hidden"
                                style="min-height: 46px; max-height: 120px;"
                                onInput="this.style.height='46px'; this.style.height=Math.min(this.scrollHeight, 120)+'px'"></textarea>
                        </div>
                        <button id="send-btn"
                            class="px-5 py-3 text-white text-sm font-semibold rounded-xl transition shadow-sm shrink-0 flex items-center gap-2" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                            <span>Send</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </main>

    <script>
        window.CONVERSATION_ID = {{ $conversation->id }};
        window.AUTH_USER_ID    = {{ auth()->id() }};

        // Auto scroll to bottom
        const messagesEl = document.getElementById('messages');
        messagesEl.scrollTop = messagesEl.scrollHeight;

        // Send on Enter (Shift+Enter for new line)
        document.getElementById('msg-input').addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                document.getElementById('send-btn').click();
            }
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

    @vite('resources/js/app.js')
    @vite('resources/js/chat.js')

</body>
</html>