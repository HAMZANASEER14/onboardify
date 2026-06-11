<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center px-4 py-10 font-sans antialiased text-gray-900">

    <div class="w-full max-w-xl">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <span class="text-4xl">📋</span>
            <h1 class="text-gray-900 text-2xl font-bold mt-2 tracking-tight">Onboardify</h1>
            <p class="text-gray-500 text-sm mt-1">Step 2 of 2 — Complete your profile</p>
        </div>

        {{-- Progress --}}
        <div class="flex items-center gap-2 justify-center mb-8">
            <div class="h-1.5 w-24 bg-blue-600 rounded-full shadow-[0_0_10px_rgba(37,99,235,0.3)]"></div>
            <div class="h-1.5 w-24 bg-blue-600 rounded-full shadow-[0_0_10px_rgba(37,99,235,0.3)]"></div>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-700 text-sm px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl p-8 shadow-xl shadow-gray-200/50">

            <h2 class="text-gray-900 text-lg font-semibold mb-6 tracking-tight">Your Details</h2>

            <form method="POST" action="{{ url('/profile') }}" class="space-y-5">
                @csrf

               {{-- Hidden business_type from use-case step --}}
                <input type="hidden" name="business_type" value="{{ old('business_type', optional($profile)->business_type) }}">

                {{-- Show it as read-only badge --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Business Type</label>
                    <div class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex items-center justify-between hover:border-blue-200 transition-colors">
                        <span class="text-blue-900 text-sm capitalize font-medium">
                            {{ optional($profile)->business_type ?? 'Not selected' }}
                        </span>
                        <a href="/onboarding/use-case" class="text-blue-600 hover:text-blue-800 text-xs font-semibold transition-colors">
                            Change →
                        </a>
                    </div>
                </div>

                {{-- First & Last Name --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">First name</label>
                        <input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name', optional($profile)->first_name) }}"
                            required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="Jane"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Last name</label>
                        <input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', optional($profile)->last_name) }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="Smith"
                        >
                    </div>
                </div>

                {{-- Company Name (hidden for solo) --}}
                <div id="company-wrap">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Company name</label>
                    <input
                        type="text"
                        name="company_name"
                        value="{{ old('company_name', optional($profile)->company_name) }}"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 transition-all duration-200"
                        placeholder="Acme Inc."
                    >
                </div>

                {{-- Industry & Domain --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Industry</label>
                        <input
                            type="text"
                            name="industry"
                            value="{{ old('industry', optional($profile)->industry) }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="Technology"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Domain</label>
                        <input
                            type="text"
                            name="domain"
                            value="{{ old('domain', optional($profile)->domain) }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="acme.com"
                        >
                    </div>
                </div>

                {{-- Phone & Location --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', optional($profile)->phone) }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="+1 555 0100"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Location</label>
                        <input
                            type="text"
                            name="location"
                            value="{{ old('location', optional($profile)->location) }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="New York, USA"
                        >
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Bio <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea
                        name="bio"
                        rows="3"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 hover:border-gray-300 transition-all duration-200 resize-none"
                        placeholder="Tell clients about yourself..."
                    >{{ old('bio', optional($profile)->bio) }}</textarea>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white font-semibold py-3.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 hover:-translate-y-0.5 active:translate-y-0 mt-2"
                >
                    Save Profile →
                </button>

            </form>
        </div>

        {{-- Back link --}}
        <p class="text-center text-gray-500 text-sm mt-6">
            <a href="/profile" class="text-gray-500 hover:text-blue-600 font-medium transition-colors">← Back to profile</a>
        </p>

    </div>

</body>
</html>