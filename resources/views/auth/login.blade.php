<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Onboardify</title>
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
            const isDark = saved === 'dark'; // default to light now
            document.documentElement.classList.toggle('dark', isDark);
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }
        html, body { height: 100%; overflow-y: auto; }

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
<body class="bg-gray-50 dark:bg-[#121212] flex items-center justify-center px-4 font-sans antialiased text-gray-900 dark:text-gray-100 transition-colors duration-300"
      style="min-height: 100vh; min-height: 100dvh;">
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
        class="w-full max-w-md max-h-full flex flex-col"
        x-data="{ loaded: false }"
        x-init="setTimeout(() => loaded = true, 50)"
        x-show="loaded"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak
    >

        {{-- Logo & Header --}}
        <div class="text-center mb-3">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl
                        text-white shadow-lg shadow-green-900/20 dark:shadow-white/10 mb-2"
                 style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
            </div>
            <h1 class="text-gray-900 dark:text-white text-2xl font-bold tracking-tight">Welcome back</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Please enter your details to sign in to Onboardify.</p>
        </div>

        {{-- Card --}}
        <div class="relative glossy-card rounded-3xl p-8 shadow-2xl">

            {{-- Error Message --}}
            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20
                            text-red-600 dark:text-red-300 text-sm px-4 py-3 rounded-2xl mb-6 flex items-start gap-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="leading-relaxed">{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}" class="space-y-5 relative z-10">
                @csrf

                {{-- Email Field --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Email address</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-[#2D6A4F] dark:group-focus-within:text-white transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                       <input
    id="email"
    type="email"
    name="email"
    autocomplete="username" 
    autofocus
                         class="w-full bg-gray-50 dark:bg-black/30 border
{{ $errors->has('email') ? 'border-red-400 dark:border-red-500' : 'border-gray-200 dark:border-white/10' }}
       text-gray-900 dark:text-white rounded-xl pl-11 pr-12 py-3 text-sm
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   focus:outline-none focus:bg-white dark:focus:bg-black/40
                                   focus:border-[#2D6A4F] dark:focus:border-white/30
                                   focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-white/10
                                   transition-all duration-200"
                            placeholder="you@example.com"
                        >
                    </div>
                </div>

                {{-- Password Field --}}
                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1.5">Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500 group-focus-within:text-[#2D6A4F] dark:group-focus-within:text-white transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 
         2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
<input 
   id="password" name="password"
   :type="showPassword ? 'text' : 'password'"
    autocomplete="current-password"
class="w-full bg-gray-50 dark:bg-black/30 border
       {{ $errors->has('email') ? 'border-red-400 dark:border-red-500' : 'border-gray-200 dark:border-white/10' }}                                   text-gray-900 dark:text-white rounded-xl pl-11 pr-12 py-3 text-sm
                                   placeholder-gray-400 dark:placeholder-gray-500
                                   focus:outline-none focus:bg-white dark:focus:bg-black/40
                                   focus:border-[#2D6A4F] dark:focus:border-white/30
                                   focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-white/10
                                   transition-all duration-200"
                            placeholder="••••••••"
                        >
                        <button
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none transition-colors"
                            aria-label="Toggle password visibility"
                        >
                            <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                   </button>
    </div>

    {{-- ✅ Add this error message --}}
    @if($errors->has('email'))
        <p class="text-red-500 dark:text-red-400 text-xs mt-1.5 pl-1">
            Invalid email or password. Please try again.
        </p>
    @endif
</div>
                {{-- Remember Me & Forgot Password --}}
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 dark:border-gray-600 bg-transparent text-[#2D6A4F] dark:text-white focus:ring-[#2D6A4F]/20 dark:focus:ring-white/20 w-4 h-4 transition cursor-pointer">
                        <span class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-gray-800 dark:group-hover:text-gray-200 transition-colors select-none">Remember me</span>
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:underline transition-all">
                            Forgot password?
                        </a>
                    @endif
                </div>

                {{-- Submit Button --}}
                <button
                    type="submit"
                    class="w-full text-white font-bold py-3.5 rounded-xl text-sm
                           transition-all duration-200 shadow-lg shadow-green-900/20
                           hover:-translate-y-0.5 hover:brightness-110 active:translate-y-0
                           flex items-center justify-center gap-2 mt-6"
                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                >
                    <span>Sign In</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </button>
            </form>

        </div>

        {{-- Switch to register --}}
        <p class="text-center text-gray-900 dark:text-gray-200 text-sm mt-3">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold hover:text-[#2D6A4F] hover:text-[#0B3D2E]
          dark:text-[#07f1a7] dark:hover:text-white">
                Create an account
            </a>
        </p>

    </div>

</body>
</html>