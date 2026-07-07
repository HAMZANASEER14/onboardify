<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Use Case – Onboardify</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        
        /* Smooth card animations */
        .use-case-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .use-case-card:hover {
            transform: translateY(-4px);
        }
        
        /* Icon container animation */
        .icon-wrapper {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .use-case-card:hover .icon-wrapper {
            transform: scale(1.1) rotate(-5deg);
        }
        
        /* Gradient text on hover */
        .gradient-text {
            background: linear-gradient(135deg, #0B3D2E 0%, #2D6A4F 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .use-case-card:hover .gradient-text {
            opacity: 1;
        }
        
        /* Pulse animation for selected state */
        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(45, 106, 79, 0.7);
            }
            70% {
                transform: scale(1);
                box-shadow: 0 0 0 10px rgba(45, 106, 79, 0);
            }
            100% {
                transform: scale(0.95);
                box-shadow: 0 0 0 0 rgba(45, 106, 79, 0);
            }
        }
        
        .pulse-ring {
            animation: pulse-ring 2s cubic-bezier(0.455, 0.03, 0.515, 0.955) infinite;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-green-50 to-emerald-50 flex items-center justify-center px-4 py-4 font-sans antialiased text-gray-900">

    <div 
        class="w-full max-w-3xl max-len-3xl"
        x-data="{ loaded: false }" 
        x-init="setTimeout(() => loaded = true, 50)"
        x-show="loaded"
        x-transition:enter="transition ease-out duration-500"
        x-transition:enter-start="opacity-0 translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak
    >

        {{-- Header & Stepper --}}
        <div class="text-center mb-4">
            <div class="inline-flex items-center justify-center w-15 h-15 rounded-3xl mb-2 pulse-ring"
                 style="background: linear-gradient(135deg, #0B3D2E 0%, #2D6A4F 100%)">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
            
           <h1 class="text-gray-900 text-2xl sm:text-2xl md:text-2xl font-bold tracking-tight mb-">What best describes your business?</h1>
            <p class="text-gray-500 text-base">This helps us tailor your Onboardify experience.</p>

            {{-- Modern Stepper --}}
            <div class="flex items-center justify-center gap-3 mt-3">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full text-white flex items-center justify-center text-sm font-bold shadow-lg"
                         style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 hidden sm:inline">Use Case</span>
                </div>
                <div class="w-16 h-1 bg-gradient-to-r from-[#0B3D2E] to-[#52b788] rounded-full"></div>
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-semibold border-2 border-gray-300">2</div>
                    <span class="text-sm font-medium text-gray-500 hidden sm:inline">Details</span>
                </div>
            </div>
        </div>

        {{-- Error Message --}}
        @if ($errors->any())
            <div class="bg-red-50 border-2 border-red-200 text-red-700 text-sm px-5 py-4 rounded-2xl mb-8 flex items-center justify-center gap-3 max-w-2xl mx-auto shadow-sm">
                <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <span class="font-medium">Please select a business type to continue.</span>
            </div>
        @endif

        {{-- Form --}}
        <form method="POST" action="/onboarding/use-case">
            @csrf

            @php
            $types = [
                [
                    'value' => 'gym',
                    'label' => 'Gym / Fitness',
                    'desc' => 'Studios, gyms & trainers',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>',
                    'color' => 'from-orange-400 to-red-500'
                ],
                [
                    'value' => 'health',
                    'label' => 'Healthcare',
                    'desc' => 'Clinics & wellness centers',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
                    'color' => 'from-green-400 to-emerald-600'
                ],
                [
                    'value' => 'tech',
                    'label' => 'Tech Startup',
                    'desc' => 'Software & SaaS companies',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>',
                    'color' => 'from-[#2D6A4F] to-[#0B3D2E]'
                ],
                [
                    'value' => 'entrepreneur',
                    'label' => 'Entrepreneur',
                    'desc' => 'Solo founders & startups',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>',
                    'color' => 'from-purple-400 to-pink-600'
                ],
                [
                    'value' => 'nonprofit',
                    'label' => 'Nonprofit',
                    'desc' => 'Charities & volunteer orgs',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>',
                    'color' => 'from-pink-400 to-rose-600'
                ],
                [
                    'value' => 'education',
                    'label' => 'Education',
                    'desc' => 'Schools, coaching & courses',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/></svg>',
                    'color' => 'from-yellow-400 to-orange-500'
                ],
                [
                    'value' => 'adventure',
                    'label' => 'Adventure Sports',
                    'desc' => 'Extreme & outdoor sports',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                    'color' => 'from-cyan-400 to-blue-600'
                ],
                [
                    'value' => 'guiding',
                    'label' => 'Guiding & Tours',
                    'desc' => 'Hiking, tours & travel guides',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
                    'color' => 'from-[#40916c] to-[#52b788]'
                ],
                [
                    'value' => 'ecommerce',
                    'label' => 'E-Commerce',
                    'desc' => 'Online stores & retail',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                    'color' => 'from-indigo-400 to-purple-600'
                ],
                [
                    'value' => 'realestate',
                    'label' => 'Real Estate',
                    'desc' => 'Agencies & property managers',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                    'color' => 'from-amber-400 to-orange-600'
                ],
                [
                    'value' => 'restaurant',
                    'label' => 'Restaurant / Food',
                    'desc' => 'Cafes, restaurants & catering',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                    'color' => 'from-red-400 to-pink-600'
                ],
                [
                    'value' => 'creative',
                    'label' => 'Creative Agency',
                    'desc' => 'Design, media & marketing',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>',
                    'color' => 'from-fuchsia-400 to-purple-600'
                ],
                [
                    'value' => 'legal',
                    'label' => 'Legal / Finance',
                    'desc' => 'Law firms & financial services',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/></svg>',
                    'color' => 'from-slate-400 to-gray-600'
                ],
                [
                    'value' => 'consulting',
                    'label' => 'Consulting',
                    'desc' => 'Professional services & advisors',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
                    'color' => 'from-[#2D6A4F] to-[#52b788]'
                ],
                [
                    'value' => 'manufacturing',
                    'label' => 'Manufacturing',
                    'desc' => 'Production & industrial',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>',
                    'color' => 'from-gray-500 to-slate-700'
                ],
                [
                    'value' => 'other',
                    'label' => 'Other',
                    'desc' => 'Something else entirely',
                    'icon' => '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>',
                    'color' => 'from-[#0B3D2E] to-[#40916c]'
                ],
            ];
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
                @foreach ($types as $type)
                <button
                    type="submit"
                    name="business_type"
                    value="{{ $type['value'] }}"
                    class="use-case-card group relative bg-white border-2 border-gray-100 rounded-2xl p-6 text-left 
                           hover:border-[#2D6A4F] hover:shadow-2xl hover:shadow-green-500/20
                           focus:outline-none focus:ring-4 focus:ring-[#2D6A4F]/20 w-full overflow-hidden"
                >
                    {{-- Background Gradient on Hover --}}
                    <div class="absolute inset-0 opacity-0 group-hover:opacity-5 transition-opacity duration-300"
                         style="background: linear-gradient(135deg, #0B3D2E 0%, #2D6A4F 100%)"></div>
                    
                    {{-- Icon Container --}}
                    <div class="icon-wrapper w-7 h-7 rounded-2xl bg-gradient-to-br {{ $type['color'] }} 
                                flex items-center justify-center mb-1 shadow-lg relative z-10">
                        <div class="w-3 h-3 text-white">
                            {!! $type['icon'] !!}
                        </div>
                    </div>
                    
                    {{-- Text Content --}}
                    <div class="relative z-10">
                        <div class="text-gray-900 font-bold text-base mb-1.5 group-hover:text-transparent gradient-text transition-all">
                            {{ $type['label'] }}
                        </div>
                        <div class="text-gray-500 text-sm leading-relaxed group-hover:text-gray-600 transition-colors">
                            {{ $type['desc'] }}
                        </div>
                    </div>

                    {{-- Hover Arrow Indicator --}}
                    <div class="absolute top-2 right-2 text-gray-300 group-hover:text-[#2D6A4F] group-hover:translate-x-1 
                                transition-all duration-300 opacity-0 group-hover:opacity-100">
                        <svg class="w-1 h-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </button>
                @endforeach
            </div>

        </form>

    </div>

</body>
</html>