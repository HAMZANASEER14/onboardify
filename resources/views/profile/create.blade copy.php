<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Profile – Onboardify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-950 flex items-center justify-center px-4 py-10">

    <div class="w-full max-w-xl">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <span class="text-4xl">📋</span>
            <h1 class="text-white text-2xl font-bold mt-2">Onboardify</h1>
            <p class="text-gray-400 text-sm mt-1">Step 2 of 2 — Complete your profile</p>
        </div>

        {{-- Progress --}}
        <div class="flex items-center gap-2 justify-center mb-8">
            <div class="h-1.5 w-24 bg-blue-600 rounded-full"></div>
            <div class="h-1.5 w-24 bg-blue-600 rounded-full"></div>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 text-red-400 text-sm px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-8">

            <h2 class="text-white text-lg font-semibold mb-6">Your Details</h2>

            <form method="POST" action="{{ url('/profile') }}" class="space-y-5">
                @csrf

                {{-- Hidden business_type from use-case step --}}
                <input type="hidden" name="business_type"
                    value="{{ old('business_type', optional($profile)->business_type) }}">

                {{-- Show selected business type as read-only --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-1.5">Business Type</label>
                    <div class="bg-gray-800 border border-gray-700 rounded-xl px-4 py-3 flex items-center justify-between">
                        <span class="text-white text-sm capitalize">
                            {{ optional($profile)->business_type ?? 'Not selected' }}
                        </span>
                        <a href="/onboarding/use-case"
                            class="text-blue-400 hover:text-blue-300 text-xs transition">
                            Change →
                        </a>
                    </div>
                </div>

                {{-- First & Last Name --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">First name</label>
                        <input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name', optional($profile)->first_name) }}"
                            required
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                            placeholder="Jane"
                        >
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Last name</label>
                        <input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', optional($profile)->last_name) }}"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                            placeholder="Smith"
                        >
                    </div>
                </div>

                {{-- Company Name --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-1.5">Company name</label>
                    <input
                        type="text"
                        name="company_name"
                        value="{{ old('company_name', optional($profile)->company_name) }}"
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                        placeholder="Acme Inc."
                    >
                </div>

                {{-- Industry & Domain --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Industry</label>
                        <input
                            type="text"
                            name="industry"
                            value="{{ old('industry', optional($profile)->industry) }}"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                            placeholder="Technology"
                        >
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Domain</label>
                        <input
                            type="text"
                            name="domain"
                            value="{{ old('domain', optional($profile)->domain) }}"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                            placeholder="acme.com"
                        >
                    </div>
                </div>

                {{-- Phone & Location --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Phone</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone', optional($profile)->phone) }}"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                            placeholder="+1 555 0100"
                        >
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400 mb-1.5">Location</label>
                        <input
                            type="text"
                            name="location"
                            value="{{ old('location', optional($profile)->location) }}"
                            class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                            placeholder="New York, USA"
                        >
                    </div>
                </div>

                {{-- Bio --}}
                <div>
                    <label class="block text-sm text-gray-400 mb-1.5">
                        Bio <span class="text-gray-600">(optional)</span>
                    </label>
                    <textarea
                        name="bio"
                        rows="3"
                        class="w-full bg-gray-800 border border-gray-700 text-white rounded-xl px-4 py-3 text-sm placeholder-gray-500 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition resize-none"
                        placeholder="Tell clients about yourself..."
                    >{{ old('bio', optional($profile)->bio) }}</textarea>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl text-sm transition"
                >
                    Save Profile →
                </button>

            </form>
        </div>

        {{-- Back link --}}
        <p class="text-center text-gray-600 text-sm mt-6">
            <a href="/profile" class="hover:text-gray-400 transition">← Back to profile</a>
        </p>

    </div>

</body>
</html> -->