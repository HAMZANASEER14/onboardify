<header class="border-b border-[#2D6A4F]/20 dark:border-white/5 px-4 md:px-6 py-2 flex items-center justify-between sticky top-0 z-30 bg-white dark:bg-[#0a1f17] transition-colors duration-300 shadow-sm">    {{-- Left: hamburger + page title --}}
    <div class="flex items-center gap-3 min-w-0">
        @auth
        <button id="mobile-menu-btn" type="button" aria-label="Open navigation menu"
                class="md:hidden w-9 h-9 rounded-lg flex items-center justify-center
       text-[#2D6A4F] dark:text-[#4ade80]
       hover:bg-[#2D6A4F]/10 dark:hover:bg-white/10
       active:scale-95 transition shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        @endauth

<h1 class="text-base md:text-lg font-bold text-[#0B3D2E] dark:text-white truncate">    
            @yield('page-title', 'Onboardify')
        </h1>
    </div>

    {{-- Right: theme toggle + profile dropdown --}}
    <div class="flex items-center gap-2 shrink-0">

        {{-- Page-specific actions slot --}}
        @yield('header-actions')

      @auth
        {{-- 🔔 Bell Notifications --}}
        @include('partials.notifications-bell')

        {{-- Theme Toggle --}}
        @include('partials.theme-toggle', ['themeTogglePosition' => 'static'])

        {{-- Profile Dropdown --}}
        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
            <button @click="open = !open"
                    class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-gray-200 dark:border-[#333]
                           hover:bg-gray-100 dark:hover:bg-[#2a2a2a] transition text-left">
                               {{-- Avatar --}}
                @if(auth()->user()->profile && auth()->user()->profile->profile_picture)
                    {{-- Show uploaded profile picture --}}
                    <img src="{{ asset('storage/' . auth()->user()->profile->profile_picture) }}" 
                         alt="Profile Picture" 
                         class="w-8 h-8 rounded-full object-cover border border-gray-200 dark:border-[#333] shrink-0">
                @else
                    {{-- Fallback: Show initial letter if no picture is uploaded --}}
                    <div class="w-8 h-8 rounded-full bg-[#1b6ca8] flex items-center justify-center text-white font-bold text-sm shrink-0">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                {{-- Name & Role --}}
                <div class="hidden sm:block min-w-0">
                    <div class="text-sm font-semibold text-gray-900 dark:text-white truncate leading-tight">
                        {{ optional(auth()->user()->profile)->first_name ?? auth()->user()->name }}
                    </div>
                    <div class="text-[11px] text-gray-500 dark:text-gray-400 uppercase leading-tight">
                        {{ auth()->user()->role ?? 'user' }}
                    </div>
                </div>
                {{-- Chevron --}}
                <svg class="w-4 h-4 text-gray-400 shrink-0 transition-transform duration-200"
                     :class="{ 'rotate-180': open }"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Dropdown Menu --}}
            <div x-show="open"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#1e1e1e] border border-gray-200 dark:border-[#333] rounded-2xl shadow-lg py-1.5 z-50"
                 style="display: none;">

                {{-- User info header --}}
                <div class="px-4 py-2 border-b border-gray-100 dark:border-[#2a2a2a]">
                    <p class="text-xs font-semibold text-gray-900 dark:text-white truncate">
                        {{ optional(auth()->user()->profile)->first_name ?? auth()->user()->name }}
                    </p>
                    <p class="text-[11px] text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>

                <a href="/profile"
                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#2a2a2a] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M5.121 17.804A9 9 0 1112 21a9 9 0 01-6.879-3.196z"/>
                    </svg>
                    Profile
                </a>

                <a href="/profile"
                   class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-[#2a2a2a] transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Settings
                </a>

                <div class="border-t border-gray-100 dark:border-[#2a2a2a] my-1"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>
</header>