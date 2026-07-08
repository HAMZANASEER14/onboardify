@auth
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
    </div>


    {{-- Nav --}}
    <nav class="flex-1 px-3 space-y-0.5 overflow-y-auto">

        {{-- Dashboard Link (Role-specific) --}}
        @php
            $dashboardRoute = match(auth()->user()->role) {
                'admin' => 'admin.dashboard',
                'employee' => 'employee.dashboard',
                'client' => 'client.portal',
                default => 'dashboard',
            };
        @endphp

        <a href="{{ route($dashboardRoute) }}"
           class="{{ request()->routeIs($dashboardRoute) ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        {{-- ADMIN ONLY LINKS --}}
        @if(auth()->user()->role === 'admin')

            <a href="{{ route('waivers.index') }}"
               class="{{ request()->routeIs('waivers.*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                waivers
            </a>

            <a href="{{ route('clients.index') }}"
               class="{{ request()->routeIs('clients.*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Clients
            </a>

            <a href="{{ route('templates.index') }}"
               class="{{ request()->routeIs('templates.*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/>
                </svg>
                Templates
            </a>

            <a href="{{ route('submissions.index') }}"
               class="{{ request()->routeIs('submissions.*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                </svg>
                View Reports
            </a>

            {{-- Task Management --}}
            <a href="{{ route('admin.tasks.index') }}"
               class="{{ request()->routeIs('admin.tasks.*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Tasks
            </a>

            {{-- Salary Management --}}
            <a href="{{ route('admin.salary.index') }}"
               class="{{ request()->routeIs('admin.salary.*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Salary Slips
            </a>

        @endif

        {{-- EMPLOYEE ONLY LINKS --}}
        @if(auth()->user()->role === 'employee')

            <a href="{{ route('employee.tasks.index') }}"
               class="{{ request()->routeIs('employee.tasks.*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                My Tasks
            </a>

            <a href="{{ route('employee.salary.index') }}"
               class="{{ request()->routeIs('employee.salary.*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                My Salary
            </a>

        @endif

        {{-- CLIENT ONLY LINKS --}}
      @if(auth()->user()->role === 'client')

    <a href="{{ route('client.waivers') }}"
       class="{{ request()->routeIs('client.waivers') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        My Documents
    </a>

@endif

        {{-- COMMON LINKS (Everyone sees these) --}}
        <div class="border-t border-white/10 my-3"></div>

        {{-- Messages with Unread Badge --}}
        @php
            $myId = auth()->id();
            $unreadCount = \App\Models\Message::where('user_id', '!=', $myId)
                ->where('is_read', false)
                ->whereHas('conversation', function($q) use ($myId) {
                    $q->where('sender_id', $myId)->orWhere('receiver_id', $myId);
                })
                ->count();
        @endphp

        <a href="{{ route('chats') }}"
           class="{{ request()->routeIs('chats*') ? 'nav-active' : 'nav-item' }} flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium relative">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            Messages
            @if($unreadCount > 0)
                <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full min-w-[20px] text-center">
                    {{ $unreadCount > 99 ? '99+' : $unreadCount }}
                </span>
            @endif
        </a>
    </nav>

</aside>
@endauth