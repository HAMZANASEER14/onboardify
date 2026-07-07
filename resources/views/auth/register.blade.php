<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – Onboardify</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            safelist: [
                'border-red-400', 'bg-red-50', 'dark:border-red-500/50', 'dark:bg-red-500/10',
                'border-[#52b788]', 'bg-[#52b788]/10', 'dark:border-[#52b788]/50', 'dark:bg-[#52b788]/10',
                'border-gray-200', 'bg-gray-50', 'dark:border-white/10', 'dark:bg-black/30'
            ]
        };
    </script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        (function () {
            const saved = localStorage.getItem('onboardify-theme');
            document.documentElement.classList.toggle('dark', saved === 'dark');
        })();
    </script>
    <style>
        [x-cloak] { display: none !important; }
        .strength-bar { transition: width 0.3s ease, background-color 0.3s ease; }
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; overflow: auto; }

        .dark .glossy-card {
            background: linear-gradient(135deg, #3a3a3a 0%, #1f1f1f 40%, #0a0a0a 100%);
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.9), inset 0 1px 1px rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.05);
        }
        .dark .glossy-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,0.05) 45%, transparent 50%);
            pointer-events: none;
            border-radius: inherit;
        }
        .glossy-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fb 40%, #eef0f3 100%);
            box-shadow: 0 25px 50px -12px rgba(15,23,42,0.12), inset 0 1px 1px rgba(255,255,255,0.8);
            border: 1px solid rgba(15,23,42,0.06);
        }
        .glossy-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,0.4) 45%, transparent 50%);
            pointer-events: none;
            border-radius: inherit;
        }

        .page-wrap {
            height:100vh;
            height: 100dvh;
            height: auto;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 1rem;
            overflow-y: auto;
        }
        .form-header { text-align: center; margin-bottom: 0.75rem; flex-shrink: 0; }
        .form-card { width: 100%; max-width: 440px; flex: 1 1 auto; min-height: 0; display: flex; flex-direction: column; }
        .card-inner { flex: 1 1 auto; min-height: 0; overflow-y: auto; scrollbar-width: thin; }
        .card-inner::-webkit-scrollbar { width: 3px; }
        .form-footer { flex-shrink: 0; text-align: center; padding-top: 0.75rem; }

        @media (max-height: 650px) {
            .form-header { margin-bottom: 0.4rem; }
            .form-header .logo-icon { width: 44px !important; height: 44px !important; }
            .form-header h1 { font-size: 1.1rem !important; }
            .form-header p  { display: none; }
            .space-y-reduced > * + * { margin-top: 0.75rem !important; }
            .card-inner { padding: 1.25rem !important; }
        }
        @media (max-height: 380px) {
             .form-header h1 { font-size: 1rem !important; }
    .card-inner { padding: 1rem !important; }
        }
    </style>
</head>
<body class="bg-gray-50 dark:bg-[#121212] text-gray-900 dark:text-gray-100 font-sans antialiased transition-colors duration-300">

{{-- Theme toggle --}}
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
    <svg class="w-5 h-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
    </svg>
    <svg class="w-5 h-5 block dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
    </svg>
</button>

<div class="page-wrap"
     x-data="registerForm()"
     x-init="setTimeout(() => loaded = true, 50)"
     x-show="loaded"
     x-transition:enter="transition ease-out duration-500"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-cloak>

    {{-- Header --}}
    <div class="form-header w-full max-w-[440px]">
        <div class="inline-flex items-center justify-center logo-icon w-12 h-12 rounded-2xl text-white shadow-lg shadow-green-900/20 dark:shadow-white/10 mb-2"
             style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
            </svg>
        </div>
        <h1 class="text-gray-900 dark:text-white text-xl font-bold tracking-tight">Create an account</h1>
        <p class="text-gray-500 dark:text-gray-400 text-xs mt-0.5">Join Onboardify today.</p>
    </div>

    {{-- Card --}}
    <div class="form-card">
        <div class="relative glossy-card rounded-3xl h-full flex flex-col overflow-hidden">
            <div class="card-inner p-6">
<form method="POST" action="{{ route('register.post') }}" class="space-y-reduced space-y-3 relative z-10">
    @csrf
                    {{-- Pass company code if present --}}
                    @if(!empty($companyCode ?? null))
                        <input type="hidden" name="company_code" value="{{ $companyCode }}">
                    @endif

                    {{-- ✅ FIX: Single hidden email field for invited users (not two conflicting fields) --}}
                    @if(!empty($inviteEmail ?? null))
                        <input type="hidden" name="email" value="{{ $inviteEmail }}">
                    @endif

             {{-- Name Field --}}
<div>
    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Full name</label>
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
            <svg class="h-4 w-4 text-gray-400 dark:text-gray-500 group-focus-within:text-[#2D6A4F] dark:group-focus-within:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
        </div>
<input type="text" name="name"
    value="{{ old('name') }}"
    class="w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:bg-white dark:focus:bg-black/40 focus:border-[#2D6A4F] dark:focus:border-white/30 focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-white/10 transition-all border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/30"
    placeholder="jane">
    </div>
</div>
                    {{-- ✅ FIX: Email Field — two completely separate branches, no conflict --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Email address</label>

                        @if(!empty($inviteEmail ?? null))
                            {{-- INVITED USER: display-only div (not a real input, not submitted) --}}
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-[#2D6A4F] dark:text-[#52b788]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="w-full rounded-xl pl-10 pr-9 py-2.5 text-sm
                                            text-gray-900 dark:text-white
                                            bg-gray-100 dark:bg-white/5
                                            border border-[#52b788] dark:border-[#52b788]/50
                                            cursor-not-allowed select-none">
                                    {{ $inviteEmail }}
                                </div>
                                <div class="absolute inset-y-0 right-0 pr-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-[#2D6A4F] dark:text-[#52b788]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-1 pl-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                This email was invited by your admin and cannot be changed.
                            </p>

                        @else
                            {{-- NORMAL USER: editable input with Alpine x-model --}}
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                    <svg class="h-4 w-4 text-gray-400 dark:text-gray-500 group-focus-within:text-[#2D6A4F] dark:group-focus-within:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
<input type="email" name="email" autocomplete="username"
    value="{{ old('email') }}"
    class="w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:bg-white dark:focus:bg-black/40 focus:border-[#2D6A4F] dark:focus:border-white/30 focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-white/10 transition-all border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/30"
    placeholder="you@example.com">
</div>
@error('email')
    <p class="text-red-500 dark:text-red-400 text-xs mt-1 pl-1">{{ $message }}</p>
@enderror
@endif
</div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 dark:text-gray-500 group-focus-within:text-[#2D6A4F] dark:group-focus-within:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
<input :type="showPassword ? 'text' : 'password'" name="password" autocomplete="new-password"
    class="w-full rounded-xl pl-10 pr-9 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:bg-white dark:focus:bg-black/40 focus:border-[#2D6A4F] dark:focus:border-white/30 focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-white/10 transition-all border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/30"
    placeholder="Create a password">
    <button type="button" @click="showPassword = !showPassword"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none transition-colors">
                                <svg x-show="!showPassword" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
@error('password')
    <p class="text-red-500 dark:text-red-400 text-xs mt-1 pl-1">{{ $message }}</p>
@enderror
                </div>
                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Confirm password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-gray-400 dark:text-gray-500 group-focus-within:text-[#2D6A4F] dark:group-focus-within:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
<input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" autocomplete="new-password"
    class="w-full rounded-xl pl-10 pr-10 py-2.5 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:bg-white dark:focus:bg-black/40 focus:border-[#2D6A4F] dark:focus:border-white/30 focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-white/10 transition-all border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-black/30"
    placeholder="Repeat password">           
                    <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 dark:text-gray-500 hover:text-gray-900 dark:hover:text-white focus:outline-none transition-colors">
                                <svg x-show="!showPasswordConfirm" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPasswordConfirm" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>
<button type="submit"
    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
    class="w-full font-bold py-3 rounded-xl text-sm transition-all duration-200 flex items-center justify-center gap-2 mt-1 text-white cursor-pointer shadow-lg shadow-green-900/20 hover:-translate-y-0.5 hover:brightness-110 active:translate-y-0">
                        <span>Create Account</span>
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </button>

                    <p class="text-center text-[10px] text-gray-400 dark:text-gray-500 mt-1">
                        By creating an account, you agree to our
                        <a href="#" class="underline hover:text-gray-700 dark:hover:text-white transition-colors text-[#2D6A4F] dark:text-[#52b788]">Terms</a> and
                        <a href="#" class="underline hover:text-gray-700 dark:hover:text-white transition-colors text-[#2D6A4F] dark:text-[#52b788]">Privacy Policy</a>
                    </p>
                </form>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <div class="form-footer w-full max-w-[440px]">
        <p class="text-gray-500 dark:text-gray-500 text-sm">
            Already have an account?
            <a href="{{ route('login') }}"
               class="font-semibold hover:underline transition-all text-[#2D6A4F] hover:text-[#0B3D2E] dark:text-[#52b788] dark:hover:text-white">
                Sign in
            </a>
        </p>
    </div>

</div>

<script>
function registerForm() {
    return {
        loaded: false,
        showPassword: false,
        showPasswordConfirm: false,
    }
}
</script>

</body>
</html>