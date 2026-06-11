<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Onboardify - Streamline Your Client Onboarding</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="font-sans antialiased text-gray-600 bg-white">

    <!-- Navigation -->
    <nav class="fixed w-full z-50 bg-white/90 backdrop-blur-lg border-b border-gray-100 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center gap-2.5">
                    <div class="w-9 h-9 bg-blue-600 rounded-lg flex items-center justify-center shadow-md shadow-blue-600/20">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl text-gray-900 tracking-tight">Onboardify</span>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#features" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-200">Features</a>
                    <a href="#how-it-works" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-200">How it Works</a>
                    <a href="#pricing" class="text-sm font-medium text-gray-600 hover:text-blue-600 transition-colors duration-200">Pricing</a>
                </div>

                <!-- Auth Buttons -->
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-gray-700 hover:text-blue-600 transition-colors duration-200">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-all duration-200 shadow-sm hover:shadow-md hover:-translate-y-0.5">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="pt-28 pb-16 lg:pt-36 lg:pb-24 bg-gradient-to-b from-blue-50/50 to-white relative overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[800px] h-[600px] bg-gradient-to-br from-blue-100/30 to-indigo-100/30 rounded-full blur-3xl -z-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold mb-6 shadow-sm">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                </span>
                The #1 Client Onboarding Platform
            </div>
            
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 tracking-tight mb-6 leading-tight">
                Streamline Your <span class="text-blue-600 relative">Client Onboarding
                    <svg class="absolute -bottom-2 left-0 w-full" viewBox="0 0 300 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10C50 4 100 2 150 2C200 2 250 4 298 10" stroke="#2563eb" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </span>
            </h1>
            
            <p class="max-w-2xl mx-auto text-lg sm:text-xl text-gray-600 mb-10 leading-relaxed">
                Create, send, and track digital waivers and onboarding forms in seconds. 
                Say goodbye to paperwork, lost documents, and manual follow-ups.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30 hover:-translate-y-0.5">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition-all duration-200 shadow-lg shadow-blue-600/20 hover:shadow-xl hover:shadow-blue-600/30 hover:-translate-y-0.5">
                        Start Free Trial
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                    </a>
                    <a href="#how-it-works" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-gray-700 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 hover:-translate-y-0.5">
                        See How It Works
                    </a>
                @endauth
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-base font-semibold text-blue-600 uppercase tracking-wide mb-3">Features</h2>
                <p class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Everything you need to onboard clients faster</p>
                <p class="text-lg text-gray-600">Stop chasing signatures. Onboardify gives you the tools to automate your workflow and keep your business compliant.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:border-blue-200 hover:shadow-xl hover:shadow-blue-600/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Digital Waivers & Forms</h3>
                    <p class="text-gray-600 leading-relaxed">Build custom, mobile-friendly forms and waivers in minutes. No coding or design skills required.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-600/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-indigo-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Real-Time Tracking</h3>
                    <p class="text-gray-600 leading-relaxed">Know exactly when a client opens, views, or signs a document. Get instant email notifications.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-8 bg-gray-50 rounded-2xl border border-gray-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-600/5 transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Secure & Compliant</h3>
                    <p class="text-gray-600 leading-relaxed">Enterprise-grade encryption keeps your client data safe. Automatically archive signed documents for legal compliance.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20 lg:py-28 bg-gradient-to-b from-gray-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-base font-semibold text-blue-600 uppercase tracking-wide mb-3">How It Works</h2>
                <p class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Get started in three simple steps</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                <!-- Connecting Line (Desktop Only) -->
                <div class="hidden md:block absolute top-16 left-[16.66%] right-[16.66%] h-0.5 bg-gradient-to-r from-blue-200 via-blue-300 to-blue-200"></div>

                <!-- Step 1 -->
                <div class="text-center relative">
                    <div class="w-32 h-32 mx-auto bg-white border-4 border-blue-100 rounded-full flex items-center justify-center mb-6 shadow-lg shadow-blue-600/10 relative z-10">
                        <span class="text-4xl font-bold text-blue-600">1</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Create Your Form</h3>
                    <p class="text-gray-600 leading-relaxed max-w-xs mx-auto">Use our intuitive drag-and-drop builder to create waivers, intake forms, or contracts tailored to your business.</p>
                </div>

                <!-- Step 2 -->
                <div class="text-center relative">
                    <div class="w-32 h-32 mx-auto bg-white border-4 border-blue-100 rounded-full flex items-center justify-center mb-6 shadow-lg shadow-blue-600/10 relative z-10">
                        <span class="text-4xl font-bold text-blue-600">2</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Send to Clients</h3>
                    <p class="text-gray-600 leading-relaxed max-w-xs mx-auto">Share a secure link via email, SMS, or embed it directly on your website. Clients can sign from any device.</p>
                </div>

                <!-- Step 3 -->
                <div class="text-center relative">
                    <div class="w-32 h-32 mx-auto bg-white border-4 border-blue-100 rounded-full flex items-center justify-center mb-6 shadow-lg shadow-blue-600/10 relative z-10">
                        <span class="text-4xl font-bold text-blue-600">3</span>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Track & Manage</h3>
                    <p class="text-gray-600 leading-relaxed max-w-xs mx-auto">Monitor submission status in real-time from your dashboard. Download or auto-archive completed forms instantly.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 lg:py-28 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-3xl p-8 sm:p-12 md:p-16 text-center shadow-2xl shadow-blue-600/20 relative overflow-hidden">
                <!-- Background decoration -->
                <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-blue-500 rounded-full opacity-20 blur-3xl"></div>
                <div class="absolute bottom-0 right-0 translate-x-1/2 translate-y-1/2 w-96 h-96 bg-blue-800 rounded-full opacity-20 blur-3xl"></div>
                
                <div class="relative z-10">
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold text-white mb-6 leading-tight">Ready to simplify your onboarding?</h2>
                    <p class="text-blue-100 text-lg sm:text-xl mb-10 max-w-2xl mx-auto leading-relaxed">Join hundreds of businesses saving hours every week with Onboardify. Start your free trial today, no credit card required.</p>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold text-blue-600 bg-white rounded-xl hover:bg-gray-50 transition-all duration-200 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            Get Started for Free
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-50 border-t border-gray-200 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Onboardify. All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-blue-600 transition-colors duration-200">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>