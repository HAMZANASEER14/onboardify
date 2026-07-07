@extends('layouts.app')

@section('title', 'New Chat')

@section('content')

    {{-- Page Header with Breadcrumb --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('chats') }}" class="hover:text-[#2D6A4F] transition">Messages</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">New Chat</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Start a Conversation</h1>
            <p class="text-gray-500 text-sm mt-1">Select a user to begin messaging</p>
        </div>
        <a href="{{ route('chats') }}"
           class="text-sm font-medium text-gray-600 hover:text-gray-900 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back to Chats
        </a>
    </div>

    {{-- Chat Container --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex" style="height: calc(100vh - 240px); min-height: 500px;">

        {{-- ── Sidebar (User List) ── --}}
        <div class="w-full md:w-80 border-r border-gray-100 flex flex-col shrink-0">

            {{-- Header --}}
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <h2 class="font-bold text-gray-900 text-base">All Users</h2>
                    <span class="text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
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
                        class="w-full pl-8 pr-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] transition">
                </div>
            </div>

            {{-- User List --}}
            <div class="flex-1 overflow-y-auto" id="user-list">
                @forelse($users as $u)
                <a href="{{ route('chat.show', $u) }}"
                   target="chat-window"
                   class="flex items-center gap-3 px-4 py-3.5 hover:bg-gray-50 border-b border-gray-50 transition group user-item"
                   data-name="{{ strtolower($u->name) }}"
                   data-user-id="{{ $u->id }}">
                    <div class="relative shrink-0">
                        <div class="w-10 h-10 rounded-full text-white flex items-center justify-center font-semibold text-sm" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        {{-- Online dot — starts gray, JS turns it green if user is online --}}
                        <span
                            class="online-dot absolute bottom-0 right-0 w-2.5 h-2.5 bg-gray-300 border-2 border-white rounded-full transition-colors duration-300"
                            data-user-id="{{ $u->id }}">
                        </span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="font-semibold text-gray-900 text-sm truncate">{{ $u->name }}</span>
                            <div class="flex items-center gap-1.5 shrink-0">
                                {{-- Unread badge — only shown if count > 0 --}}
                                @php $unread = $unreadCounts[$u->id] ?? 0; @endphp
                                @if($unread > 0)
                                <span
                                    class="unread-badge text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center"
                                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                                    data-user-id="{{ $u->id }}">
                                    {{ $unread > 9 ? '9+' : $unread }}
                                </span>
                                @else
                                <span
                                    class="unread-badge hidden text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center"
                                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                                    data-user-id="{{ $u->id }}">
                                </span>
                                @endif
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-[#2D6A4F] transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </div>
                        </div>
                        <div class="text-gray-400 text-xs truncate mt-0.5">{{ $u->email }}</div>
                    </div>
                </a>
                @empty
                <div class="flex flex-col items-center justify-center py-16 text-center px-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center mb-3" style="background: linear-gradient(135deg, rgba(11,61,46,0.1), rgba(45,106,79,0.1))">
                        <svg class="w-6 h-6" style="color: #2D6A4F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">No users yet</p>
                </div>
                @endforelse
            </div>

        </div>

        {{-- ── Chat Window (Right side) ── --}}
        <div class="hidden md:flex flex-1 relative bg-white">
            {{-- Empty State --}}
            <div id="chat-empty-state" class="absolute inset-0 flex flex-col items-center justify-center bg-gray-50/50">
                <div class="w-16 h-16 rounded-3xl flex items-center justify-center mb-5" style="background: linear-gradient(135deg, rgba(11,61,46,0.1), rgba(45,106,79,0.1))">
                    <svg class="w-8 h-8" style="color: #2D6A4F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 text-lg mb-1">Select a User</h3>
                <p class="text-gray-500 text-sm text-center max-w-xs">Choose someone from the list to start a new conversation</p>
            </div>

            <iframe
                name="chat-window"
                id="chat-iframe"
                class="absolute inset-0 w-full h-full border-0 hidden"
                title="Chat Window">
            </iframe>
        </div>

    </div>

    {{-- Mobile overlay --}}
    <div id="mobile-chat-overlay" class="fixed inset-0 bg-white z-50 hidden md:hidden">
        <div class="h-full flex flex-col">
            <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between bg-white">
                <button id="mobile-chat-back" class="flex items-center gap-2 text-sm font-medium text-gray-600 hover:text-gray-900">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Back to Users
                </button>
            </div>
            <iframe
                name="mobile-chat-window"
                id="mobile-chat-iframe"
                class="flex-1 w-full border-0"
                title="Mobile Chat Window">
            </iframe>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // ── Search filter ──
    document.getElementById('search-users').addEventListener('input', function () {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.user-item').forEach(item => {
            item.style.display = item.dataset.name.includes(q) ? '' : 'none';
        });
    });

    // ── Desktop iframe ──
    const chatIframe     = document.getElementById('chat-iframe');
    const chatEmptyState = document.getElementById('chat-empty-state');
    const userLinks      = document.querySelectorAll('.user-item');

    userLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            const openedUserId = this.dataset.userId;

            if (window.innerWidth >= 768) {
                e.preventDefault();

                chatIframe.classList.remove('hidden');
                chatEmptyState.classList.add('hidden');
                chatIframe.src = this.href;

                userLinks.forEach(l => l.classList.remove('bg-[#2D6A4F]/10'));
                this.classList.add('bg-[#2D6A4F]/10');

                // Clear unread badge for this user immediately on click
                clearUnreadBadge(openedUserId);

            } else {
                e.preventDefault();
                const mobileOverlay = document.getElementById('mobile-chat-overlay');
                const mobileIframe  = document.getElementById('mobile-chat-iframe');

                mobileIframe.src = this.href;
                mobileOverlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                clearUnreadBadge(openedUserId);
            }
        });
    });

    // ── Mobile back ──
    document.getElementById('mobile-chat-back').addEventListener('click', function () {
        const mobileOverlay = document.getElementById('mobile-chat-overlay');
        const mobileIframe  = document.getElementById('mobile-chat-iframe');
        mobileOverlay.classList.add('hidden');
        mobileIframe.src = '';
        document.body.style.overflow = '';
    });

    // ── Clear unread badge for a user ──
    function clearUnreadBadge(userId) {
        document.querySelectorAll(`.unread-badge[data-user-id="${userId}"]`).forEach(badge => {
            badge.classList.add('hidden');
            badge.textContent = '';
        });
    }

    // ── Pusher Presence Channel for online/offline dots ──
    // Uses Laravel Echo which is already set up in your app.js
    if (window.Echo) {
        window.Echo.join('presence-online')
            .here((users) => {
                // users = array of all currently online users when YOU join
                users.forEach(u => setOnline(u.id, true));
            })
            .joining((user) => {
                // fires when someone comes online
                setOnline(user.id, true);
            })
            .leaving((user) => {
                // fires when someone goes offline
                setOnline(user.id, false);
            });
    }

    function setOnline(userId, isOnline) {
        document.querySelectorAll(`.online-dot[data-user-id="${userId}"]`).forEach(dot => {
            dot.classList.toggle('bg-green-400', isOnline);
            dot.classList.toggle('bg-gray-300', !isOnline);
        });
        // Also update the dot inside the open iframe if it's the same user
        try {
            const iframe = document.getElementById('chat-iframe');
            if (iframe && iframe.contentWindow) {
                const iframeDot = iframe.contentWindow.document.getElementById('header-online-dot');
                if (iframeDot) {
                    iframeDot.classList.toggle('bg-green-400', isOnline);
                    iframeDot.classList.toggle('bg-gray-300', !isOnline);
                }
            }
        } catch (e) { /* cross-origin guard */ }
    }
</script>
@endpush