<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        // ✅ Set theme BEFORE paint to avoid a flash of the wrong theme
        (function () {
            const saved = localStorage.getItem('onboardify-theme');
            const isDark = saved === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }
        html, body { height: 90%; overflow: hidden; }

        /* Dark Glossy Card Effects */
        .dark .glossy-card {
            background: linear-gradient(135deg, #3a3a3a 0%, #1f1f1f 40%, #0a0a0a 100%);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9), inset 0 1px 1px rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .dark .glossy-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,0.05) 45%, transparent 50%);
            pointer-events: none;
            border-radius: inherit;
        }

        /* Light Glossy Card Effects */
        .glossy-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fb 40%, #eef0f3 100%);
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.12), inset 0 1px 1px rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(15, 23, 42, 0.06);
        }
        .glossy-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,0.4) 45%, transparent 50%);
            pointer-events: none;
            border-radius: inherit;
        }
    </style>
</head>
<body class="h-screen bg-gray-50 dark:bg-[#121212] flex items-center justify-center px-4 font-sans antialiased text-gray-900 dark:text-gray-100 transition-colors duration-300">

    {{-- Theme toggle — fixed top-right corner --}}
    <button
        type="button"
        x-data
        @click="
            const html = document.documentElement;
            const goingDark = !html.classList.contains('dark');
            html.classList.toggle('dark', goingDark);
            localStorage.setItem('onboardify-theme', goingDark ? 'dark' : 'light');
        "
        class="fixed top-5 right-5 z-50 w-10 h-10 rounded-full flex items-center justify-center
               bg-white dark:bg-white/10 border border-gray-200 dark:border-white/10
               text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-white/20
               shadow-sm transition-colors duration-200"
        aria-label="Toggle light/dark theme"
        title="Toggle theme"
    >
        {{-- Sun icon — shows in dark mode (click to go light) --}}
        <svg class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
        </svg>
        {{-- Moon icon — shows in light mode (click to go dark) --}}
        <svg class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
        </svg>
    </button>

    <div 
        class="w-full max-w-md text-center"
        x-data="{ loaded: false }" 
        x-init="setTimeout(() => loaded = true, 50)"
        x-show="loaded"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak
    >

        {{-- Header & Icon --}}
        <div class="mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl text-white shadow-lg shadow-green-900/20 dark:shadow-white/10 mb-4"
                 style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-gray-900 dark:text-white text-3xl font-bold tracking-tight">Check your inbox</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-3 leading-relaxed">
                We sent a verification link to
                <span class="inline-block mt-1 px-2.5 py-1 bg-[#52b788]/10 dark:bg-[#52b788]/10 text-[#2D6A4F] dark:text-[#52b788] font-semibold rounded-lg text-sm border border-[#52b788]/20 dark:border-[#52b788]/20">
                    {{ Auth::user()->email }}
                </span>
                <br class="sm:hidden"> Click the link to activate your account.
            </p>
        </div>

        {{-- Success Flash Message --}}
        @if (session('status') === 'verification-link-sent')
            <div class="mb-6 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 text-emerald-700 dark:text-emerald-300 text-sm px-4 py-3.5 rounded-2xl flex items-center gap-3 text-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="leading-relaxed">A new verification link has been sent to your email address.</span>
            </div>
        @endif

        {{-- Action Card --}}
        <div class="relative glossy-card rounded-3xl p-8 shadow-2xl space-y-4">

            {{-- Resend Form --}}
            <form method="POST" action="{{ route('verification.send') }}" class="relative z-10">
                @csrf
                <button type="submit"
                    class="w-full text-white font-semibold py-3.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-green-900/20 hover:-translate-y-0.5 hover:brightness-110 active:translate-y-0 flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Resend Verification Email
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200 dark:border-white/10"></div>
                </div>
            </div>

            {{-- Logout Form --}}
            <form method="POST" action="{{ route('logout') }}" class="relative z-10">
                @csrf
                <button type="submit"
                    class="w-full bg-gray-50 dark:bg-white/5 hover:bg-gray-100 dark:hover:bg-white/10 active:bg-gray-200 dark:active:bg-white/15 text-gray-700 dark:text-gray-300 font-semibold py-3.5 rounded-xl text-sm transition-all duration-200 border border-gray-200 dark:border-white/10 hover:border-gray-300 dark:hover:border-white/20 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Login
                </button>
            </form>

        </div>

        {{-- Footer Help Text --}}
        <p class="text-gray-500 dark:text-gray-500 text-sm mt-8 leading-relaxed">
            Can't find it? 
            <span class="text-gray-400 dark:text-gray-600">Check your spam or promotions folder, or use the button above.</span>
        </p>

    </div>

</body>
</html>