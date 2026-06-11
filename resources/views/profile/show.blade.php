<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 flex items-center justify-center px-4 py-10 font-sans antialiased">

    <div class="w-full max-w-xl">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <span class="text-4xl">📋</span>
            <h1 class="text-gray-900 text-2xl font-bold mt-2">Onboardify</h1>
            <p class="text-gray-500 text-sm mt-1">Your profile</p>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 text-center flex items-center justify-center gap-2 shadow-sm">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 shadow-sm">

            {{-- Avatar + Name --}}
            <div class="flex items-center gap-4 pb-6 mb-6 border-b border-gray-100">
                <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xl font-bold shrink-0 border border-blue-200">
                    {{ strtoupper(substr(optional($profile)->first_name ?? auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-gray-900 font-semibold text-lg truncate">
                        {{ optional($profile)->first_name }} {{ optional($profile)->last_name }}
                    </div>
                    <div class="text-gray-500 text-sm truncate">{{ auth()->user()->email }}</div>
                </div>
            </div>

            {{-- Profile Fields --}}
            <div class="space-y-4">

                @if (optional($profile)->business_type)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Business Type</span>
                    <span class="bg-blue-50 border border-blue-200 text-blue-700 text-xs px-3 py-1 rounded-full font-semibold capitalize">
                        {{ $profile->business_type }}
                    </span>
                </div>
                @endif

                @if (optional($profile)->company_name)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Company</span>
                    <span class="text-gray-900 font-medium">{{ $profile->company_name }}</span>
                </div>
                @endif

                @if (optional($profile)->industry)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Industry</span>
                    <span class="text-gray-900 font-medium">{{ $profile->industry }}</span>
                </div>
                @endif

                @if (optional($profile)->domain)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Domain</span>
                    <a href="https://{{ $profile->domain }}" target="_blank"
                        class="text-blue-600 hover:text-blue-800 font-medium transition underline decoration-blue-200 hover:decoration-blue-800">
                        {{ $profile->domain }}
                    </a>
                </div>
                @endif

                @if (optional($profile)->phone)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Phone</span>
                    <span class="text-gray-900 font-medium">{{ $profile->phone }}</span>
                </div>
                @endif

                @if (optional($profile)->location)
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500">Location</span>
                    <span class="text-gray-900 font-medium">{{ $profile->location }}</span>
                </div>
                @endif

                @if (optional($profile)->bio)
                <div class="text-sm pt-4 mt-4 border-t border-gray-100">
                    <span class="text-gray-500 block mb-2 font-medium">Bio</span>
                    <p class="text-gray-700 leading-relaxed">{{ $profile->bio }}</p>
                </div>
                @endif

                {{-- Show message if profile is incomplete --}}
                @if (!optional($profile)->first_name)
                <div class="text-center py-4 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-amber-800 text-sm font-medium">Your profile is incomplete.</p>
                    <a href="/profile/create" class="text-amber-700 hover:text-amber-900 text-sm font-semibold transition mt-1 inline-block">
                        Complete it now →
                    </a>
                </div>
                @endif

            </div>

            {{-- Actions --}}
            <div class="mt-8 pt-6 border-t border-gray-100 flex flex-col gap-3">
                <a href="/profile/create"
                    class="w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition shadow-sm hover:shadow-md">
                    Edit Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-center bg-gray-50 hover:bg-red-50 border border-gray-200 hover:border-red-200 text-gray-600 hover:text-red-600 font-semibold py-3 rounded-xl text-sm transition">
                        Logout
                    </button>
                </form>
            </div>

        </div>

    </div>

</body>
</html>