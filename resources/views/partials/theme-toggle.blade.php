{{--
    Theme toggle button. Include anywhere you want the sun/moon switch
    to appear (top-right corner of auth pages, header of dashboard, etc).

    Requires Alpine.js to already be loaded on the page, and
    @include('partials.theme-init') to already be in head.

    Usage:
    @include('partials.theme-toggle')

    Position options (pass before including):
    @include('partials.theme-toggle', ['themeTogglePosition' => 'static'])        -- flows inline, no positioning
    @include('partials.theme-toggle', ['themeTogglePosition' => 'fixed'])         -- default: fixed top-right
    @include('partials.theme-toggle', ['themeTogglePosition' => 'fixed-bottom']) -- fixed bottom-right
--}}
@php
    $positionClasses = match($themeTogglePosition ?? 'fixed') {
        'fixed' => 'fixed top-5 right-5 z-50',
        'fixed-bottom' => 'fixed bottom-5 right-5 z-50',
        default => '', // 'static' or anything else: no positioning, flows inline
    };
@endphp
<button
    type="button"
    x-data
    @click="
        const html = document.documentElement;
        const goingDark = !html.classList.contains('dark');
        html.classList.toggle('dark', goingDark);
        localStorage.setItem('onboardify-theme', goingDark ? 'dark' : 'light');
    "
    class="{{ $positionClasses }}
           w-10 h-10 rounded-full flex items-center justify-center
           bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10
           text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/20
           shadow-sm transition-colors duration-200"
    aria-label="Toggle light/dark theme"
    title="Toggle theme"
>
    {{-- Sun icon, shown in dark mode, click to go light --}}
    <svg class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    {{-- Moon icon, shown in light mode, click to go dark --}}
    <svg class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
</button>