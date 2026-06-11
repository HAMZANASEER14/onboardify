<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center px-4 font-sans antialiased text-gray-900">

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
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>
            </div>
            <h1 class="text-gray-900 text-3xl font-bold tracking-tight">Check your inbox</h1>
            <p class="text-gray-500 text-sm mt-3 leading-relaxed">
                We sent a verification link to
                <span class="inline-block mt-1 px-2.5 py-1 bg-blue-50 text-blue-700 font-semibold rounded-lg text-sm border border-blue-100">
                    {{ Auth::user()->email }}
                </span>
                <br class="sm:hidden"> Click the link to activate your account.
            </p>
        </div>

        {{-- Success Flash Message --}}
        @if (session('status') === 'verification-link-sent')
            <div class="mb-6 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm px-4 py-3.5 rounded-2xl flex items-center gap-3 text-left">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="leading-relaxed">A new verification link has been sent to your email address.</span>
            </div>
        @endif

        {{-- Action Card --}}
        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl p-8 shadow-xl shadow-gray-200/50 space-y-4">

            {{-- Resend Form --}}
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Resend Verification Email
                </button>
            </form>

            {{-- Divider --}}
            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
            </div>

            {{-- Logout Form --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="w-full bg-gray-50 hover:bg-gray-100 active:bg-gray-200 text-gray-700 font-semibold py-3.5 rounded-xl text-sm transition-all duration-200 border border-gray-200 hover:border-gray-300 flex items-center justify-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Login
                </button>
            </form>

        </div>

        {{-- Footer Help Text --}}
        <p class="text-gray-500 text-sm mt-8 leading-relaxed">
            Can't find it? 
            <span class="text-gray-400">Check your spam or promotions folder, or use the button above.</span>
        </p>

    </div>

</body>
</html>