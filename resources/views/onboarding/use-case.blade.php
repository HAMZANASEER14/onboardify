<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Use Case – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center px-4 py-10 font-sans antialiased text-gray-900">

    <div 
        class="w-full max-w-4xl"
        x-data="{ loaded: false }" 
        x-init="setTimeout(() => loaded = true, 50)"
        x-show="loaded"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak
    >

        {{-- Header & Stepper --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20 mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            
            <h1 class="text-gray-900 text-3xl font-bold tracking-tight">What best describes your business?</h1>
            <p class="text-gray-500 text-sm mt-2">This helps us tailor your Onboardify experience.</p>

            {{-- Modern Stepper --}}
            <div class="flex items-center justify-center gap-3 mt-8">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold shadow-lg shadow-blue-600/30">1</div>
                    <span class="text-sm font-semibold text-blue-600 hidden sm:inline">Use Case</span>
                </div>
                <div class="w-12 h-0.5 bg-gray-200 rounded-full"></div>
                <div class="flex items-center gap-2 opacity-60">
                    <div class="w-8 h-8 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-semibold">2</div>
                    <span class="text-sm font-medium text-gray-500 hidden sm:inline">Details</span>
                </div>
            </div>
        </div>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-700 text-sm px-4 py-3.5 rounded-2xl mb-8 flex items-center justify-center gap-3 max-w-2xl mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span>Please select a business type to continue.</span>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="/onboarding/use-case">
            @csrf

            @php
            $types = [
                ['value' => 'gym',          'icon' => '🏋️', 'label' => 'Gym / Fitness',     'desc' => 'Studios, gyms & trainers'],
                ['value' => 'health',        'icon' => '🏥', 'label' => 'Health',             'desc' => 'Clinics & wellness centers'],
                ['value' => 'tech',          'icon' => '💻', 'label' => 'Tech Startup',       'desc' => 'Software & SaaS companies'],
                ['value' => 'entrepreneur',  'icon' => '🚀', 'label' => 'Entrepreneur',       'desc' => 'Solo founders & startups'],
                ['value' => 'nonprofit',     'icon' => '💙', 'label' => 'Nonprofit',          'desc' => 'Charities & volunteer orgs'],
                ['value' => 'education',     'icon' => '🎓', 'label' => 'Education',          'desc' => 'Schools, coaching & courses'],
                ['value' => 'adventure',     'icon' => '🪂', 'label' => 'Adventure Sports',   'desc' => 'Extreme & outdoor sports'],
                ['value' => 'guiding',       'icon' => '🏔️', 'label' => 'Guiding & Tours',   'desc' => 'Hiking, tours & travel guides'],
                ['value' => 'ecommerce',     'icon' => '🛒', 'label' => 'E-Commerce',         'desc' => 'Online stores & retail'],
                ['value' => 'realestate',    'icon' => '🏠', 'label' => 'Real Estate',        'desc' => 'Agencies & property managers'],
                ['value' => 'restaurant',    'icon' => '🍽️', 'label' => 'Restaurant / Food',  'desc' => 'Cafes, restaurants & catering'],
                ['value' => 'creative',      'icon' => '🎨', 'label' => 'Creative Agency',    'desc' => 'Design, media & marketing'],
                ['value' => 'legal',         'icon' => '⚖️', 'label' => 'Legal / Finance',    'desc' => 'Law firms & financial services'],
                ['value' => 'other',         'icon' => '✨', 'label' => 'Other',              'desc' => 'Something else entirely'],
            ];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($types as $type)
                <button
                    type="submit"
                    name="business_type"
                    value="{{ $type['value'] }}"
                    class="group relative bg-white border border-gray-200 rounded-2xl p-5 text-left transition-all duration-200 
                           hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/10 hover:-translate-y-1 
                           focus:outline-none focus:ring-4 focus:ring-blue-500/20 w-full"
                >
                    {{-- Icon Container --}}
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-2xl mb-4 
                                group-hover:bg-blue-600 group-hover:scale-110 transition-all duration-200 shadow-sm">
                        {{ $type['icon'] }}
                    </div>
                    
                    {{-- Text Content --}}
                    <div class="text-gray-900 font-semibold text-sm group-hover:text-blue-700 transition-colors">
                        {{ $type['label'] }}
                    </div>
                    <div class="text-gray-500 text-xs mt-1.5 leading-relaxed group-hover:text-blue-600/70 transition-colors">
                        {{ $type['desc'] }}
                    </div>

                    {{-- Hover Arrow Indicator --}}
                    <div class="absolute top-5 right-5 text-gray-300 group-hover:text-blue-500 group-hover:translate-x-1 transition-all duration-200 opacity-0 group-hover:opacity-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </button>
                @endforeach
            </div>

        </form>

    </div>

</body>
</html>