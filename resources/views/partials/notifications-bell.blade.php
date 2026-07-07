@auth
{{-- 🔔 Bell Notification Dropdown --}}
<div class="relative" x-data="bellNotifications()" x-init="init()" @click.outside="open = false">

    {{-- Bell Button --}}
    <button @click="toggle()"
        class="relative w-9 h-9 rounded-xl flex items-center justify-center
               text-gray-500 dark:text-gray-400
               hover:bg-gray-100 dark:hover:bg-white/10
               border border-gray-200 dark:border-white/10
               transition-all duration-200">

        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>

        {{-- Badge --}}
        <span x-show="count > 0"
              x-text="count > 9 ? '9+' : count"
              class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1
                     bg-red-500 text-white text-[10px] font-bold
                     rounded-full flex items-center justify-center"
              style="display: none;">
        </span>
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-[#1a1a1a]
                border border-gray-200 dark:border-white/10
                rounded-2xl shadow-xl z-50 overflow-hidden"
         style="display: none;">

      {{-- Header --}}
<div class="flex items-center justify-between px-4 py-3
            border-b border-gray-100 dark:border-white/10">
    <h3 class="text-sm font-bold text-gray-900 dark:text-white">
        Notifications
    </h3>
    <div class="flex items-center gap-2">
        <span x-show="count > 0"
              x-text="count + ' new'"
              class="text-xs bg-red-100 dark:bg-red-500/20
                     text-red-600 dark:text-red-400
                     font-semibold px-2 py-0.5 rounded-full"
              style="display: none;">
        </span>
        {{-- ✅ NEW: Clear all button --}}
        <button @click="clearAll()"
                x-show="notifications.length > 0"
                class="text-xs font-semibold text-gray-400 hover:text-red-500 transition"
                style="display: none;">
            Clear all
        </button>
    </div>
</div>

        {{-- List --}}
        <div class="max-h-96 overflow-y-auto divide-y divide-gray-100 dark:divide-white/5">

            {{-- Loading --}}
            <div x-show="loading"
                 class="flex items-center justify-center py-8">
                <svg class="animate-spin w-5 h-5 text-[#2D6A4F]"
                     fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v8z"/>
                </svg>
            </div>

            {{-- Empty --}}
            <div x-show="!loading && notifications.length === 0"
                 class="flex flex-col items-center justify-center py-10 px-4 text-center">
                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-white/10
                            flex items-center justify-center mb-3">
                    <svg class="w-6 h-6 text-gray-400" fill="none"
                         stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                    All caught up!
                </p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                    No new notifications
                </p>
            </div>

            {{-- Items --}}
            <template x-for="(n, i) in notifications" :key="i">
                <a :href="n.url"
                   class="flex items-start gap-3 px-4 py-3
                          hover:bg-gray-50 dark:hover:bg-white/5
                          transition-colors duration-150 cursor-pointer">

                    {{-- Icon --}}
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 mt-0.5"
                         :class="{
                             'bg-blue-100 dark:bg-blue-500/20'  : n.type === 'message',
                             'bg-green-100 dark:bg-green-500/20': n.type === 'task',
                             'bg-red-100 dark:bg-red-500/20'    : n.type === 'due',
                             'bg-purple-100 dark:bg-purple-500/20': n.type === 'waiver',
                             'bg-yellow-100 dark:bg-yellow-500/20': n.type === 'member',
                         }">

                        {{-- Message --}}
                        <svg x-show="n.type === 'message'"
                             class="w-4 h-4 text-blue-600 dark:text-blue-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>

                        {{-- Task --}}
                        <svg x-show="n.type === 'task'"
                             class="w-4 h-4 text-green-600 dark:text-green-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                        </svg>

                        {{-- Due --}}
                        <svg x-show="n.type === 'due'"
                             class="w-4 h-4 text-red-600 dark:text-red-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>

                        {{-- Waiver --}}
                        <svg x-show="n.type === 'waiver'"
                             class="w-4 h-4 text-purple-600 dark:text-purple-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>

                        {{-- Member --}}
                        <svg x-show="n.type === 'member'"
                             class="w-4 h-4 text-yellow-600 dark:text-yellow-400"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white truncate"
                           x-text="n.title"></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 line-clamp-2"
                           x-text="n.body"></p>
                        <p class="text-[11px] text-gray-400 dark:text-gray-500 mt-1"
                           x-text="n.time"></p>
                    </div>
                </a>
            </template>
        </div>

        {{-- Footer --}}
        <div x-show="notifications.length > 0"
             class="px-4 py-2.5 border-t border-gray-100 dark:border-white/10
                    bg-gray-50 dark:bg-white/5 grid grid-cols-3 gap-2"
             style="display: none;">
            <a href="/chats"
               class="text-xs font-semibold text-center text-[#2D6A4F]
                      dark:text-[#4ade80] hover:underline">
                💬 Chats
            </a>
            <a href="/admin/tasks"
               class="text-xs font-semibold text-center text-[#2D6A4F]
                      dark:text-[#4ade80] hover:underline">
                ✅ Tasks
            </a>
            <a href="/waivers"
               class="text-xs font-semibold text-center text-[#2D6A4F]
                      dark:text-[#4ade80] hover:underline">
                📋 Waivers
            </a>
        </div>

    </div>
</div>
@endauth