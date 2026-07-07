@extends('layouts.app')

@section('title', 'Messages')

@push('styles')
    <style>
        .chat-page-content {
            margin: -1rem;
            padding: 0.5rem;
        }

        @media (min-width: 768px) {
            .chat-page-content {
                margin: -2rem;
                padding: 0.5rem;
            }
        }

        #chat-wrapper {
            height: calc(100vh - 90px);
            max-height: none;
            border-radius: 0 !important;
            border-left: none !important;
            border-right: none !important;
            border-bottom: none !important;
            margin-top: 0.5rem;
        }

        .chat-item.active {
            background: rgba(45, 106, 79, 0.06);
            border-left: 3px solid #2D6A4F;
        }

        .chat-item {
            border-left: 3px solid transparent;
        }

        #chat-list::-webkit-scrollbar {
            width: 3px;
        }

        #chat-list::-webkit-scrollbar-thumb {
            background: #e5e7eb;
            border-radius: 4px;
        }

        @media (max-width: 767px) {
            #chat-wrapper {
                height: calc(100vh - 100px);
                max-height: calc(100vh - 100px);
            }
        }

        /* Hide the global footer on this page */
        footer {
            display: none !important;
        }

        /* Force dark chat area (make unified chat page dark by default) */
        #chat-wrapper {
            background: #071018 !important;
            border-color: #0f1720 !important;
        }

        #chat-sidebar {
            background: #071018 !important;
            border-right-color: #0f1720 !important;
        }

        #chat-sidebar .px-4.py-4 {
            background: transparent !important;
            border-bottom-color: #0f1720 !important;
        }

        #chat-list .chat-item {
            border-bottom-color: rgba(255, 255, 255, 0.03);
        }

        #chat-list .chat-item:hover {
            background: rgba(255, 255, 255, 0.02);
        }

        .chat-item.active {
            background: rgba(45, 106, 79, 0.06);
            border-left-color: #2D6A4F;
        }

        #chat-container {
            background: transparent !important;
        }

        /* Limit the scope so only chat page `.bg-white` elements are affected */
        #chat-wrapper .bg-white {
            background: transparent !important;
        }

        .text-gray-900 {
            color: #e6eef0 !important;
        }

        .text-gray-700 {
            color: #cbd5d9 !important;
        }

        .text-gray-400 {
            color: #9ca3af !important;
        }

        /* Search input */
        #chat-sidebar input#search-input {
            background: #071018 !important;
            color: #e6eef0 !important;
        }

        #chat-sidebar input#search-input::placeholder {
            color: #9ca3af !important;
        }

        /* Scrollbar thumb */
        #chat-list::-webkit-scrollbar-thumb {
            background: #0f1720 !important;
        }

        /* Keep existing dark-mode selectors as well (for toggles) */
        .dark #chat-wrapper {
            background: #071018 !important;
            border-color: #0f1720 !important;
        }

        /* Remove bottom padding reserved for footer on layout container when on chats page */
        div[class*="px-4"][class*="pb-6"] {
            padding-bottom: 0 !important;
        }

        /* Make iframe container dark so empty iframe area looks dark until iframe content loads */
        #chat-container,
        #chat-wrapper,
        #main-panel,
        #chat-frame {
            background: #071018 !important;
        }

        /* Ensure right-side panel and iframe fill available height and allow flex children to scroll */
        #chat-container {
            height: 100%;
        }

        #chat-container,
        #main-panel,
        #chat-frame {
            min-height: 0;
        }

        #chat-frame {
            color-scheme: dark;
            height: 100%;
            display: block;
        }

        /* Force page to occupy full height and remove any bottom spacing that shows as white */
        html,
        body {
            height: 100%;
            background: #071018 !important;
        }

        body {
            margin: 0;
            padding: 0;
        }

        /* Remove bottom padding reserved by layout container */
        div[class*="px-4"][class*="pb-6"] {
            padding-bottom: 0 !important;
            margin-bottom: 0 !important;
        }

        /* Ensure chat wrapper reaches the very bottom */
        #chat-wrapper {
            height: calc(100vh - 60px) !important;
            margin-bottom: 0 !important;
        }

        /* Hide global footer if present */
        footer {
            display: none !important;
        }
    </style>
@endpush

@section('content')
    <div class="chat-page-content">

        <div id="chat-page-header"></div>

        {{-- Chat Wrapper --}}
        <div id="chat-wrapper" class="bg-white overflow-hidden flex w-full border-t border-gray-100">

            {{-- ══════════════════════════
             CHAT LIST SIDEBAR
        ══════════════════════════ --}}
            <div id="chat-sidebar" class="w-full md:w-80 border-r border-gray-100 flex flex-col shrink-0 bg-white">

                {{-- Sidebar Header --}}
                <div class="px-4 py-4 border-b border-gray-100 bg-white shrink-0">
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="font-bold text-gray-900 text-base">
                            Messages
                            @if (count($chats) > 0)
                                <span
                                    class="ml-1.5 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-full align-middle"
                                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">{{ count($chats) }}</span>
                            @endif
                        </h2>

                        <div class="relative">
                            <button id="actions-toggle"
                                class="w-8 h-8 rounded-full flex items-center justify-center text-white shadow-sm transition hover:opacity-90"
                                style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)" title="New chat or group">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>

                            <div id="actions-menu"
                                class="hidden absolute top-10 right-0 w-44 bg-white border border-gray-100 rounded-xl shadow-lg z-50 py-1">
                                <button type="button" data-action="personal"
                                    class="action-item w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" style="color:#2D6A4F" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                    </svg>
                                    New Chat
                                </button>
                                <button type="button" data-action="group"
                                    class="action-item w-full text-left px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 flex items-center gap-2">
                                    <svg class="w-4 h-4" style="color:#40916c" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    New Group
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <input type="text" id="search-input" placeholder="Search conversations"
                            class="w-full pl-9 pr-4 py-2 bg-gray-100 border-0 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#2D6A4F]/20 transition">
                    </div>
                </div>

                {{-- Chat List --}}
                <div id="chat-list" class="flex-1 overflow-y-auto">
                    @forelse($chats as $chat)
                        <a href="{{ $chat['route'] }}"
                            class="chat-item flex items-center gap-3 px-4 py-3.5 hover:bg-gray-50 border-b border-gray-50 transition cursor-pointer"
                            data-name="{{ strtolower($chat['name']) }}" data-user-id="{{ $chat['user_id'] ?? '' }}"
                            data-type="{{ $chat['type'] }}"
                            onclick="openChat(event, '{{ $chat['route'] }}', '{{ $chat['user_id'] ?? '' }}')">

                            <div class="relative shrink-0">
                                <div class="w-11 h-11 rounded-full flex items-center justify-center font-semibold text-base overflow-hidden
                            {{ $chat['type'] === 'group' ? 'bg-amber-100 text-amber-700' : 'text-white' }}"
                                    @if ($chat['type'] !== 'group') style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)" @endif>
                                    @if ($chat['type'] === 'group' && $chat['avatar'])
                                        <img src="{{ asset('storage/' . $chat['avatar']) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        {{ $chat['initial'] }}
                                    @endif
                                </div>


                                @if ($chat['type'] === 'group')
                                    <span
                                        class="online-dot absolute bottom-0 right-0 w-2.5 h-2.5 bg-purple-400 border-2 border-white rounded-full"></span>
                                @else
                                    <span
                                        class="online-dot absolute bottom-0 right-0 w-2.5 h-2.5 bg-gray-300 border-2 border-white rounded-full"
                                        data-user-id="{{ $chat['user_id'] }}">
                                    </span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-0.5">
                                    <span class="font-semibold text-gray-900 text-sm truncate">{{ $chat['name'] }}</span>
                                    <div class="flex items-center gap-1.5 shrink-0 ml-2">
                                        {{-- Unread badge --}}
                                        @if (($chat['unread_count'] ?? 0) > 0)
                                            <span
                                                class="unread-badge text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center"
                                                style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                                                data-user-id="{{ $chat['user_id'] ?? '' }}">
                                                {{ ($chat['unread_count'] ?? 0) > 9 ? '9+' : $chat['unread_count'] }}
                                            </span>
                                        @else
                                            <span
                                                class="unread-badge hidden text-white text-[10px] font-bold min-w-[18px] h-[18px] px-1 rounded-full flex items-center justify-center"
                                                style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                                                data-user-id="{{ $chat['user_id'] ?? '' }}">
                                            </span>
                                        @endif
                                        <span class="text-gray-400 text-[10px]">
                                            {{ $chat['last_time'] ? $chat['last_time']->diffForHumans(null, true) : '' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-gray-400 text-xs truncate">
                                    {{ $chat['last_msg'] ?: 'No messages yet' }}
                                </div>
                            </div>

                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center py-16 text-center px-6">
                            <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3"
                                style="background: linear-gradient(135deg, rgba(11,61,46,0.1), rgba(45,106,79,0.1))">
                                <svg class="w-7 h-7" style="color: #2D6A4F" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm font-medium mb-1">No chats yet</p>
                            <button type="button" data-action="personal"
                                class="action-item text-xs font-semibold mt-2 px-4 py-2 rounded-xl text-white"
                                style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                Start a conversation
                            </button>
                        </div>
                    @endforelse
                </div>

            </div>

            {{-- ══════════════════════════
             MAIN CHAT PANEL
        ══════════════════════════ --}}
            <div id="chat-container" class="flex-1 flex-col min-w-0 h-full hidden md:flex">

                <div class="md:hidden flex items-center gap-3 px-4 py-3 border-b border-gray-100 bg-white shrink-0">
                    <button onclick="goBackToList()"
                        class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <span class="font-semibold text-gray-900 text-sm">Back</span>
                </div>

                <div id="main-panel" class="flex-1 flex flex-col items-center justify-center bg-gray-50/30">
                    <div class="w-16 h-16 rounded-3xl flex items-center justify-center mb-4"
                        style="background: linear-gradient(135deg, rgba(11,61,46,0.08), rgba(45,106,79,0.08))">
                        <svg class="w-8 h-8" style="color: #2D6A4F" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-700 text-base mb-1">Your Messages</h3>
                    <p class="text-gray-400 text-sm text-center max-w-xs">Select a conversation to start chatting</p>
                </div>

                <iframe id="chat-frame" src="" frameborder="0"
                    class="flex-1 w-full border-none hidden"></iframe>

            </div>
        </div>

        {{-- Modal Overlay --}}
        <div id="modal-overlay" class="fixed inset-0 bg-black/50 z-40 hidden" onclick="closeModals()"></div>

        {{-- Personal Chat Modal --}}
        <div id="personal-chat-modal"
            class="fixed hidden z-50 bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md mx-4"
            style="top:50%;left:50%;transform:translate(-50%,-50%);max-height:85vh;">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900">New Chat</h3>
                    <p class="text-gray-500 text-xs mt-0.5">Search by name or email</p>
                </div>
                <button onclick="closeModals()"
                    class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="px-5 py-3 border-b border-gray-100">
                <div class="relative">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" id="user-search-input" placeholder="Type name or email..."
                        class="w-full pl-9 pr-4 py-2 bg-gray-100 border-0 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#2D6A4F]/20 transition">
                </div>
            </div>
            <div id="user-search-results" class="overflow-y-auto p-4 space-y-2" style="max-height: calc(85vh - 140px);">
                <div id="search-placeholder" class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <p class="text-gray-500 text-sm">Type at least 2 characters to search</p>
                </div>
                <div id="search-loading" class="hidden text-center py-8">
                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-[#2D6A4F]"></div>
                    <p class="text-gray-500 text-sm mt-2">Searching...</p>
                </div>
                <div id="search-empty" class="hidden text-center py-8">
                    <p class="text-gray-500 text-sm">No users found</p>
                </div>
                <div id="search-results-list"></div>
            </div>
        </div>

        {{-- Group Chat Modal --}}
        <div id="group-chat-modal"
            class="fixed hidden z-50 bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md mx-4"
            style="top:50%;left:50%;transform:translate(-50%,-50%);max-height:85vh;">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-gray-900">New Group</h3>
                    <p class="text-gray-500 text-xs mt-0.5">Create a group chat</p>
                </div>
                <button onclick="closeModals()"
                    class="w-8 h-8 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <form method="POST" action="{{ route('groups.store') }}" enctype="multipart/form-data"
                class="flex flex-col" style="max-height: calc(85vh - 80px);">
                @csrf
                <div class="overflow-y-auto flex-1">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Group Name *</label>
                        <input name="name" type="text" placeholder="e.g. Design Team" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#2D6A4F] focus:ring-2 focus:ring-[#2D6A4F]/10 transition">
                    </div>
                    <div class="px-5 py-4 border-b border-gray-100">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Description</label>
                        <textarea name="description" rows="2" placeholder="What's this group about?"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#2D6A4F] focus:ring-2 focus:ring-[#2D6A4F]/10 transition resize-none"></textarea>
                    </div>
                    <div class="px-5 py-4 border-b border-gray-100">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Add Members (search by
                            email)</label>
                        <div class="relative">
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" id="group-member-search" placeholder="Type name or email..."
                                class="w-full pl-9 pr-4 py-2 bg-gray-100 border-0 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-[#2D6A4F]/20 transition">
                        </div>
                        <div id="selected-members" class="flex flex-wrap gap-2 mt-3"></div>
                        <div id="group-search-results" class="mt-3 space-y-2 max-h-48 overflow-y-auto">
                            <div id="group-search-placeholder" class="text-center py-4">
                                <p class="text-gray-500 text-xs">Type at least 2 characters to search</p>
                            </div>
                            <div id="group-search-loading" class="hidden text-center py-4">
                                <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-[#2D6A4F]">
                                </div>
                            </div>
                            <div id="group-search-empty" class="hidden text-center py-4">
                                <p class="text-gray-500 text-xs">No users found</p>
                            </div>
                            <div id="group-search-list"></div>
                        </div>
                    </div>
                    <div class="px-5 py-4 border-b border-gray-100">
                        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Avatar (optional)</label>
                        <input type="file" name="avatar" accept="image/*"
                            class="w-full text-sm text-gray-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold hover:file:bg-[#2D6A4F]/10 transition">
                    </div>
                    <div id="selected-members-input"></div>
                </div>
                <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex gap-2">
                    <button type="button" onclick="closeModals()"
                        class="flex-1 py-2.5 rounded-xl bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 py-2.5 rounded-xl text-white text-sm font-semibold transition shadow-sm"
                        style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        Create Group
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const isMobile = () => window.innerWidth < 768;

        const actionsToggle = document.getElementById('actions-toggle');
        const actionsMenu = document.getElementById('actions-menu');
        const modalOverlay = document.getElementById('modal-overlay');
        const personalModal = document.getElementById('personal-chat-modal');
        const groupModal = document.getElementById('group-chat-modal');

        const userSearchInput = document.getElementById('user-search-input');
        const searchResultsList = document.getElementById('search-results-list');
        const searchPlaceholder = document.getElementById('search-placeholder');
        const searchLoading = document.getElementById('search-loading');
        const searchEmpty = document.getElementById('search-empty');
        const groupMemberSearch = document.getElementById('group-member-search');
        const groupSearchList = document.getElementById('group-search-list');
        const groupSearchPlaceholder = document.getElementById('group-search-placeholder');
        const groupSearchLoading = document.getElementById('group-search-loading');
        const groupSearchEmpty = document.getElementById('group-search-empty');
        const selectedMembersContainer = document.getElementById('selected-members');
        const selectedMembersInput = document.getElementById('selected-members-input');

        let selectedMembers = [];
        let searchTimeout;
        let groupSearchTimeout;

        // ── Open Chat ──
        function openChat(e, url, userId) {
            if (e) e.preventDefault();

            document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
            if (e && e.currentTarget) e.currentTarget.classList.add('active');
            if ((!e || !e.currentTarget) && userId) {
                const item = document.querySelector(`.chat-item[data-user-id="${userId}"]`);
                if (item) item.classList.add('active');
            }
            if (url) sessionStorage.setItem('activeChatUrl', url);
            if (userId) sessionStorage.setItem('activeChatUserId', String(userId));
            const mainPanel = document.getElementById('main-panel');
            const frame = document.getElementById('chat-frame');
            if (mainPanel) mainPanel.style.display = 'none';
            if (frame) {
                frame.classList.remove('hidden');
                frame.style.display = 'block';
                const isDark = document.documentElement.classList.contains('dark') ||
                    localStorage.getItem('theme') === 'dark';
                const separator = url.includes('?') ? '&' : '?';
                frame.src = url + separator + 'dark=' + (isDark ? '1' : '0');
            }

            // Clear unread badge immediately when chat is opened
            if (userId) clearUnreadBadge(userId);

            if (isMobile()) {
                const sidebar = document.getElementById('chat-sidebar');
                const wrapper = document.getElementById('chat-wrapper');
                const container = document.getElementById('chat-container');
                if (sidebar) sidebar.classList.add('hidden');
                if (wrapper) {
                    wrapper.style.position = 'fixed';
                    wrapper.style.inset = '0';
                    wrapper.style.zIndex = '40';
                    wrapper.style.height = '100vh';
                }
                if (container) {
                    container.classList.remove('hidden');
                    container.classList.add('flex');
                }
            }
        }

        // ── Back to list (Mobile) ──
        function goBackToList() {
            const sidebar = document.getElementById('chat-sidebar');
            const wrapper = document.getElementById('chat-wrapper');
            const container = document.getElementById('chat-container');
            const frame = document.getElementById('chat-frame');
            const mainPanel = document.getElementById('main-panel');

            if (sidebar) sidebar.classList.remove('hidden');
            if (wrapper) {
                wrapper.style.position = '';
                wrapper.style.inset = '';
                wrapper.style.zIndex = '';
                wrapper.style.height = '';
            }
            if (container) {
                container.classList.add('hidden');
                container.classList.remove('flex');
            }
            if (frame) {
                frame.classList.add('hidden');
                frame.style.display = 'none';
                frame.src = '';
            }
            if (mainPanel) mainPanel.style.display = 'flex';
        }

        // ── Sidebar search ──
        const sidebarSearchInput = document.getElementById('search-input');
        if (sidebarSearchInput) {
            sidebarSearchInput.addEventListener('input', function() {
                const q = this.value.toLowerCase();
                document.querySelectorAll('.chat-item').forEach(item => {
                    item.style.display = item.dataset.name.includes(q) ? 'flex' : 'none';
                });
            });
        }

        // ── Dropdown ──
        if (actionsToggle && actionsMenu) {
            actionsToggle.addEventListener('click', (e) => {
                e.stopPropagation();
                actionsMenu.classList.toggle('hidden');
            });
            document.addEventListener('click', (e) => {
                if (!actionsMenu.contains(e.target) && !actionsToggle.contains(e.target)) {
                    actionsMenu.classList.add('hidden');
                }
            });
        }

        // ── Modals ──
        function openModal(modal) {
            if (modalOverlay) modalOverlay.classList.remove('hidden');
            if (modal) modal.classList.remove('hidden');
            if (actionsMenu) actionsMenu.classList.add('hidden');
        }

        function closeModals() {
            if (modalOverlay) modalOverlay.classList.add('hidden');
            if (personalModal) personalModal.classList.add('hidden');
            if (groupModal) groupModal.classList.add('hidden');

            if (userSearchInput) userSearchInput.value = '';
            if (searchResultsList) searchResultsList.innerHTML = '';
            if (searchPlaceholder) searchPlaceholder.classList.remove('hidden');
            if (searchLoading) searchLoading.classList.add('hidden');
            if (searchEmpty) searchEmpty.classList.add('hidden');

            if (groupMemberSearch) groupMemberSearch.value = '';
            if (groupSearchList) groupSearchList.innerHTML = '';
            if (groupSearchPlaceholder) groupSearchPlaceholder.classList.remove('hidden');
            if (groupSearchLoading) groupSearchLoading.classList.add('hidden');
            if (groupSearchEmpty) groupSearchEmpty.classList.remove('hidden');

            selectedMembers = [];
            updateSelectedMembersUI();
        }

        document.querySelectorAll('.action-item[data-action]').forEach(btn => {
            btn.addEventListener('click', function() {
                if (this.dataset.action === 'personal' && personalModal) openModal(personalModal);
                else if (this.dataset.action === 'group' && groupModal) openModal(groupModal);
            });
        });

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeModals();
        });

        // ── Personal Chat Search ──
        if (userSearchInput) {
            userSearchInput.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(searchTimeout);
                if (query.length < 2) {
                    if (searchResultsList) searchResultsList.innerHTML = '';
                    if (searchPlaceholder) searchPlaceholder.classList.remove('hidden');
                    if (searchLoading) searchLoading.classList.add('hidden');
                    if (searchEmpty) searchEmpty.classList.add('hidden');
                    return;
                }
                searchTimeout = setTimeout(() => performUserSearch(query), 300);
            });
        }

        async function performUserSearch(query) {
            if (searchPlaceholder) searchPlaceholder.classList.add('hidden');
            if (searchEmpty) searchEmpty.classList.add('hidden');
            if (searchLoading) searchLoading.classList.remove('hidden');
            if (searchResultsList) searchResultsList.innerHTML = '';

            try {
                const response = await fetch(`/chat/search-users?q=${encodeURIComponent(query)}`);
                const users = await response.json();
                if (searchLoading) searchLoading.classList.add('hidden');

                if (users.length === 0) {
                    if (searchEmpty) searchEmpty.classList.remove('hidden');
                    return;
                }

                users.forEach(user => {
                    const userCard = document.createElement('div');
                    userCard.className =
                        'flex items-center gap-3 px-4 py-3 rounded-xl border border-gray-100 hover:border-[#2D6A4F]/30 hover:bg-[#2D6A4F]/5 transition group cursor-pointer';
                    userCard.innerHTML = `
                <div class="w-9 h-9 rounded-full text-white flex items-center justify-center font-semibold text-sm shrink-0" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    ${user.name.charAt(0).toUpperCase()}
                </div>
                <div class="flex-1 min-w-0">
                    <div class="font-semibold text-gray-900 text-sm">${user.name}</div>
                    <div class="text-gray-400 text-xs truncate">${user.email}</div>
                </div>
                <svg class="w-4 h-4 text-gray-300 group-hover:text-[#2D6A4F] transition shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            `;

                    userCard.addEventListener('click', function() {
                        closeModals();

                        // If chat already exists in sidebar, just click it
                        const existingChat = document.querySelector(
                            `.chat-item[data-user-id="${user.id}"]`);
                        if (existingChat) {
                            existingChat.click();
                            return;
                        }

                        // New chat — add to sidebar and load in iframe (NO page reload)
                        addChatToSidebar(user);
                        openChat(null, `/chat/${user.id}`, user.id);
                    });

                    if (searchResultsList) searchResultsList.appendChild(userCard);
                });
            } catch (error) {
                console.error('Search failed:', error);
                if (searchLoading) searchLoading.classList.add('hidden');
                if (searchEmpty) searchEmpty.classList.remove('hidden');
            }
        }

        // ── Add new chat to sidebar dynamically ──
        function addChatToSidebar(user) {
            const chatList = document.getElementById('chat-list');
            if (!chatList) return;
            if (document.querySelector(`.chat-item[data-user-id="${user.id}"]`)) return;

            const chatUrl = `/chat/${user.id}`;
            const item = document.createElement('a');
            item.href = chatUrl;
            item.className =
                'chat-item flex items-center gap-3 px-4 py-3.5 hover:bg-gray-50 border-b border-gray-50 transition cursor-pointer';
            item.dataset.name = user.name.toLowerCase();
            item.dataset.userId = user.id;
            item.dataset.type = 'personal';

            item.innerHTML = `
        <div class="relative shrink-0">
            <div class="w-11 h-11 rounded-full flex items-center justify-center font-semibold text-base text-white"
                 style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                ${user.name.charAt(0).toUpperCase()}
            </div>
            <span class="online-dot absolute bottom-0 right-0 w-2.5 h-2.5 bg-gray-300 border-2 border-white rounded-full"
                  data-user-id="${user.id}"></span>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center justify-between mb-0.5">
                <span class="font-semibold text-gray-900 text-sm truncate">${user.name}</span>
            </div>
            <div class="text-gray-400 text-xs truncate">No messages yet</div>
        </div>
    `;

            item.addEventListener('click', (e) => {
                e.preventDefault();
                openChat(e, chatUrl, user.id);
            });

            // Insert at top of list so it's immediately visible
            chatList.prepend(item);
        }

        // ── Group Member Search ──
        if (groupMemberSearch) {
            groupMemberSearch.addEventListener('input', function() {
                const query = this.value.trim();
                clearTimeout(groupSearchTimeout);
                if (query.length < 2) {
                    if (groupSearchList) groupSearchList.innerHTML = '';
                    if (groupSearchPlaceholder) groupSearchPlaceholder.classList.remove('hidden');
                    if (groupSearchLoading) groupSearchLoading.classList.add('hidden');
                    if (groupSearchEmpty) groupSearchEmpty.classList.add('hidden');
                    return;
                }
                groupSearchTimeout = setTimeout(() => searchGroupMembers(query), 300);
            });
        }

        async function searchGroupMembers(query) {
            if (groupSearchPlaceholder) groupSearchPlaceholder.classList.add('hidden');
            if (groupSearchEmpty) groupSearchEmpty.classList.add('hidden');
            if (groupSearchLoading) groupSearchLoading.classList.remove('hidden');
            if (groupSearchList) groupSearchList.innerHTML = '';

            try {
                const response = await fetch(`/chat/search-users?q=${encodeURIComponent(query)}`);
                const users = await response.json();
                if (groupSearchLoading) groupSearchLoading.classList.add('hidden');

                if (users.length === 0) {
                    if (groupSearchEmpty) groupSearchEmpty.classList.remove('hidden');
                    return;
                }

                const availableUsers = users.filter(user => !selectedMembers.find(m => m.id === user.id));
                if (availableUsers.length === 0) {
                    if (groupSearchEmpty) {
                        groupSearchEmpty.classList.remove('hidden');
                        const p = groupSearchEmpty.querySelector('p');
                        if (p) p.textContent = 'All matching users already added';
                    }
                    return;
                }

                availableUsers.forEach(user => {
                    const userCard = document.createElement('div');
                    userCard.className =
                        'flex items-center gap-3 px-3 py-2 border border-gray-100 rounded-xl hover:border-[#2D6A4F]/30 hover:bg-[#2D6A4F]/5 transition cursor-pointer';
                    userCard.innerHTML = `
                    <input type="checkbox" class="accent-[#2D6A4F] w-4 h-4" onchange="toggleGroupMember(${user.id}, '${user.name.replace(/'/g, "\\'")}', '${user.email}')">
                    <div class="w-7 h-7 rounded-full text-white flex items-center justify-center font-semibold text-xs shrink-0" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        ${user.name.charAt(0).toUpperCase()}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-sm text-gray-800 font-medium">${user.name}</div>
                        <div class="text-xs text-gray-400 truncate">${user.email}</div>
                    </div>
                `;
                    if (groupSearchList) groupSearchList.appendChild(userCard);
                });
            } catch (error) {
                console.error('Search failed:', error);
                if (groupSearchLoading) groupSearchLoading.classList.add('hidden');
                if (groupSearchEmpty) groupSearchEmpty.classList.remove('hidden');
            }
        }

        function toggleGroupMember(id, name, email) {
            const existingIndex = selectedMembers.findIndex(m => m.id === id);
            if (existingIndex > -1) selectedMembers.splice(existingIndex, 1);
            else selectedMembers.push({
                id,
                name,
                email
            });
            updateSelectedMembersUI();
        }

        function updateSelectedMembersUI() {
            if (!selectedMembersContainer || !selectedMembersInput) return;
            selectedMembersContainer.innerHTML = '';
            selectedMembersInput.innerHTML = '';

            if (selectedMembers.length === 0) {
                selectedMembersContainer.innerHTML = '<p class="text-xs text-gray-400">No members selected yet</p>';
                return;
            }

            selectedMembers.forEach(member => {
                const tag = document.createElement('div');
                tag.className =
                    'flex items-center gap-2 px-3 py-1.5 bg-[#2D6A4F]/10 border border-[#2D6A4F]/30 rounded-lg';
                tag.innerHTML = `
                <span class="text-xs font-medium text-[#2D6A4F]">${member.name}</span>
                <button type="button" onclick="toggleGroupMember(${member.id}, '${member.name.replace(/'/g, "\\'")}', '${member.email}')" class="text-[#40916c] hover:text-[#0B3D2E]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
                selectedMembersContainer.appendChild(tag);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'members[]';
                input.value = member.id;
                selectedMembersInput.appendChild(input);
            });

            const countLabel = document.createElement('p');
            countLabel.className = 'text-xs text-gray-500 mt-2 w-full';
            countLabel.textContent = `${selectedMembers.length} member${selectedMembers.length !== 1 ? 's' : ''} selected`;
            selectedMembersContainer.appendChild(countLabel);
        }

        updateSelectedMembersUI();

        // ── Auto-open from URL param ──
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('user');
            if (userId) {
                const chatItem = document.querySelector(`.chat-item[data-user-id="${userId}"]`);
                if (chatItem) {
                    setTimeout(() => {
                        chatItem.click();
                        window.history.replaceState({}, document.title, '/chats');
                    }, 300);
                } else {
                    window.location.href = `/chat/${userId}`;
                }
                return;
            }

            const savedUrl = sessionStorage.getItem('activeChatUrl');
            const savedUserId = sessionStorage.getItem('activeChatUserId');
            if (savedUrl) {
                const chatItem = savedUserId ?
                    document.querySelector(`.chat-item[data-user-id="${savedUserId}"]`) :
                    null;

                if (chatItem) chatItem.classList.add('active');

                const mainPanel = document.getElementById('main-panel');
                const frame = document.getElementById('chat-frame');
                if (mainPanel) mainPanel.style.display = 'none';
                if (frame) {
                    frame.classList.remove('hidden');
                    frame.style.display = 'block';
                    const isDark = document.documentElement.classList.contains('dark') || localStorage.getItem(
                        'theme') === 'dark';
                    const separator = savedUrl.includes('?') ? '&' : '?';
                    frame.src = savedUrl + separator + 'dark=' + (isDark ? '1' : '0');
                }
            }
        });


        // ── Unread badge helper ──
        function clearUnreadBadge(userId) {
            document.querySelectorAll(`.unread-badge[data-user-id="${userId}"]`).forEach(badge => {
                badge.classList.add('hidden');
                badge.textContent = '';
            });
        }

        // ── Pusher Presence Channel — real online/offline dots ──
        // Joins 'presence-online'; dots start gray and turn green only when user is confirmed online
        if (window.Echo) {
            window.Echo.join('presence-online')
                .here((users) => {
                    // Called once with everyone currently online
                    users.forEach(u => setOnlineDot(u.id, true));
                })
                .joining((user) => {
                    // Someone just came online
                    setOnlineDot(user.id, true);
                })
                .leaving((user) => {
                    // Someone just went offline (tab closed, session ended)
                    setOnlineDot(user.id, false);
                });
        }

        function setOnlineDot(userId, isOnline) {
            // Update all dots in the sidebar for this user
            document.querySelectorAll(`.online-dot[data-user-id="${userId}"]`).forEach(dot => {
                dot.classList.toggle('bg-green-400', isOnline);
                dot.classList.toggle('bg-gray-300', !isOnline);
            });
        }
    </script>
@endpush
