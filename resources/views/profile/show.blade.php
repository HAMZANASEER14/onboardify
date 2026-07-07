<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen overflow-y-auto bg-gray-50 text-gray-900 flex justify-center px-4 py-6 sm:py-10 font-sans antialiased">

    <div class="w-full max-w-xl ">

        {{-- Logo --}}
        <div class="text-center mb-5 sm:mb-8">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl mx-auto flex items-center justify-center shadow-sm"
                 style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
            </div>
            <h1 class="text-gray-900 text-xl sm:text-2xl font-bold mt-2 sm:mt-3">Onboardify</h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1 sm:mt-2">Your profile</p>
        </div>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs sm:text-sm px-4 py-2.5 sm:py-3 rounded-xl mb-4 sm:mb-6 text-center flex items-center justify-center gap-2 shadow-sm">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl p-5 sm:p-8 shadow-sm">

            {{-- Avatar + Name --}}
            <div class="flex items-center gap-3 sm:gap-4 pb-4 sm:pb-6 mb-4 sm:mb-6 border-b border-gray-100">
                @if($profile && $profile->profile_picture)
                    {{-- Show uploaded profile picture --}}
                    <img src="{{ asset('storage/' . $profile->profile_picture) }}" 
                         alt="Profile Picture" 
                         class="w-12 h-12 sm:w-14 sm:h-14 rounded-full object-cover border border-gray-200 shrink-0">
                @else
                    {{-- Fallback: Show initial letter if no picture is uploaded --}}
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-[#52b788]/10 text-[#2D6A4F] flex items-center justify-center text-lg sm:text-xl font-bold shrink-0 border border-[#52b788]/30">
                        {{ strtoupper(substr(optional($profile)->first_name ?? auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="overflow-hidden min-w-0">
                    <div class="text-gray-900 font-semibold text-base sm:text-lg truncate">
                        {{ optional($profile)->first_name }} {{ optional($profile)->last_name }}
                    </div>
                    <div class="text-gray-500 text-xs sm:text-sm truncate">{{ auth()->user()->email }}</div>
                </div>
            </div>

            {{-- Profile Fields --}}
            <div class="space-y-3 sm:space-y-4">

                @if (optional($profile)->business_type)
                <div class="flex items-center justify-between text-xs sm:text-sm gap-3">
                    <span class="text-gray-500 shrink-0">Business Type</span>
                    <span class="bg-[#52b788]/10 border border-[#52b788]/30 text-[#2D6A4F] text-[10px] sm:text-xs px-2.5 sm:px-3 py-1 rounded-full font-semibold capitalize truncate">
                        {{ $profile->business_type }}
                    </span>
                </div>
                @endif

                @if (optional($profile)->company_name)
                <div class="flex items-center justify-between text-xs sm:text-sm gap-3">
                    <span class="text-gray-500 shrink-0">Company</span>
                    <span class="text-gray-900 font-medium truncate">{{ $profile->company_name }}</span>
                </div>
                @endif

                @if (optional($profile)->industry)
                <div class="flex items-center justify-between text-xs sm:text-sm gap-3">
                    <span class="text-gray-500 shrink-0">Industry</span>
                    <span class="text-gray-900 font-medium truncate">{{ $profile->industry }}</span>
                </div>
                @endif

                @if (optional($profile)->domain)
                <div class="flex items-center justify-between text-xs sm:text-sm gap-3">
                    <span class="text-gray-500 shrink-0">Domain</span>
                    <a href="https://{{ $profile->domain }}" target="_blank"
                        class="text-[#2D6A4F] hover:text-[#0B3D2E] font-medium transition underline decoration-[#52b788]/30 hover:decoration-[#0B3D2E] truncate">
                        {{ $profile->domain }}
                    </a>
                </div>
                @endif

                @if (optional($profile)->phone)
                <div class="flex items-center justify-between text-xs sm:text-sm gap-3">
                    <span class="text-gray-500 shrink-0">Phone</span>
                    <span class="text-gray-900 font-medium truncate">{{ $profile->phone }}</span>
                </div>
                @endif

                @if (optional($profile)->location)
                <div class="flex items-center justify-between text-xs sm:text-sm gap-3">
                    <span class="text-gray-500 shrink-0">Location</span>
                    <span class="text-gray-900 font-medium truncate">{{ $profile->location }}</span>
                </div>
                @endif

                @if (optional($profile)->bio)
                <div class="text-xs sm:text-sm pt-3 sm:pt-4 mt-3 sm:mt-4 border-t border-gray-100">
                    <span class="text-gray-500 block mb-1.5 sm:mb-2 font-medium">Bio</span>
                    <p class="text-gray-700 leading-relaxed">{{ $profile->bio }}</p>
                </div>
                @endif

                {{-- Show message if profile is incomplete --}}
                @if (!optional($profile)->first_name)
                <div class="text-center py-3 sm:py-4 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-amber-800 text-xs sm:text-sm font-medium">Your profile is incomplete.</p>
                    <a href="/profile/create" class="text-amber-700 hover:text-amber-900 text-xs sm:text-sm font-semibold transition mt-1 inline-block">
                        Complete it now →
                    </a>
                </div>
                @endif

            </div>

            {{-- Actions --}}
            <div class="mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-100 flex flex-col gap-2.5 sm:gap-3">
                <a href="{{ route('profile.edit') }}"
                    class="w-full text-center text-white font-semibold py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm transition shadow-sm hover:shadow-md hover:opacity-90"
                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    Edit Profile
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full text-center bg-gray-50 hover:bg-red-50 border border-gray-200 hover:border-red-200 text-gray-600 hover:text-red-600 font-semibold py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm transition">
                        Logout
                    </button>
                </form>
            </div>

        </div>

    </div>

</body>
</html>