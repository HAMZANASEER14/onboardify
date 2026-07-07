<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Complete Your Profile – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">

    {{-- Tailwind CSS  --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{--  Phone Input Library CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* Phone Input Styling */
        .iti__flag-container {
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .iti__selected-flag {
            border-top-left-radius: 0.75rem;
            border-bottom-left-radius: 0.75rem;
        }
        #phone {
            border-top-left-radius: 0 !important;
            border-bottom-left-radius: 0 !important;
        }
        .iti {
    position: relative;
}
   .iti__country-list {
    left: 0;
    right: auto;
    max-width: 400%;
}
        .iti__flag-container:hover { background: #f3f4f6; }
        .iti__country-list {
            border-radius: 0.75rem;
            box-shadow: 0 10px 10px -3px rgba(0, 0, 0, 0.1);
        }

        /* Compact fit on laptop / large short screens */
        @media (min-width: 1024px) and (max-height: 800px) {
            body { align-items: center; }
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 flex justify-center items-start px-4 py-6 sm:py-10 lg:py-4">
<div class="w-full max-w-md">

    {{-- Logo --}}
    <div class="text-center mb-2 sm:mb-2 lg:mb-1">
        <div class="inline-flex items-center justify-center w-7 h-7 sm:w-16 sm:h-16 lg:w-10 lg:h-10 rounded-2xl shadow-lg shadow-[#2D6A4F]/20 mb-1" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
            <svg class="w-8 h-8 sm:w-9 sm:h-9 lg:w-5 lg:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
        </div>
        <h1 class="text-gray-900 text-xl sm:text-2xl lg:text-lg font-bold tracking-tight">Onboardify</h1>
        <p class="text-gray-500 text-xs sm:text-sm lg:text-xs mt-1">Step 2 of 2 — Complete your profile</p>
    </div>

    {{-- Progress Bar --}}
    <div class="flex items-center gap-2 justify-center mb-6 sm:mb-8 lg:mb-3">
        <div class="flex items-center gap-2">
            <div class="h-1.5 w-16 sm:w-24 bg-[#2D6A4F] rounded-full relative">
                <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-5 h-5 bg-[#2D6A4F] rounded-full border-2 border-white flex items-center justify-center shadow-md">
                    <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="h-1.5 w-16 sm:w-24 bg-[#2D6A4F] rounded-full shadow-[0_0_10px_rgba(45,106,79,0.3)] relative">
            <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-5 h-5 bg-[#2D6A4F] rounded-full border-2 border-white shadow-md animate-pulse"></div>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-6 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    {{-- Error Messages --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-100 text-red-700 text-sm px-4 py-3 rounded-xl mb-6">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="font-semibold mb-1">Please fix the following errors:</p>
                    <ul class="list-disc list-inside space-y-1 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-2xl sm:rounded-3xl p-5 sm:p-8 lg:p-5 shadow-xl shadow-gray-200/50">

        <h2 class="text-gray-900 text-base sm:text-lg lg:text-base font-semibold mb-1 sm:mb-3 lg:mb-2 tracking-tight">Your Details</h2>

        <form method="POST" action="{{ url('/profile') }}" id="profile-form" class="space-y-1 sm:space-y-2 lg:space-y-1.5" enctype="multipart/form-data" x-data="{
            bio: '{{ old('bio', optional($profile)->bio) }}',
            bioMax: 200,
            loading: false,
            selectedRole: '{{ old('role', $userRole) }}',
            businessType: '{{ old('business_type', $prefill['business_type'] ?? optional($profile)->business_type) }}' || 'company',
            inviteCode: '{{ old('invite_code', $companyCode) }}',
            isLocked: {{ $companyCode ? 'true' : 'false' }},
            companyName: '{{ old('company_name', $prefill['company_name'] ?? optional($profile)->company_name) }}',
            industry: '{{ old('industry', $prefill['industry'] ?? optional($profile)->industry) }}',
            domain: '{{ old('domain', $prefill['domain'] ?? optional($profile)->domain) }}',
            location: '{{ old('location', $prefill['location'] ?? optional($profile)->location) }}',
            fetchingTeam: false,
            teamFound: false,

            async fetchTeamData() {
                if (this.inviteCode.length < 6) {
                    this.teamFound = false;
                    return;
                }

                this.fetchingTeam = true;
                try {
                    const response = await fetch(`/api/team/${this.inviteCode}`);
                    if (response.ok) {
                        const data = await response.json();
                        this.companyName = data.company_name;
                        this.industry = data.industry;
                        this.domain = data.domain;
                        this.location = data.location;
                        this.teamFound = true;
                    } else {
                        this.teamFound = false;
                        this.companyName = '';
                        this.industry = '';
                        this.domain = '';
                        this.location = '';
                    }
                } catch (error) {
                    this.teamFound = false;
                } finally {
                    this.fetchingTeam = false;
                }
            }
        }">
            @csrf
            {{-- Sends business_type to the server --}}
            <input type="hidden" name="business_type" value="{{ old('business_type', $prefill['business_type'] ?? $profile->business_type ?? 'company') }}">

            {{-- Role Selection --}}
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Account Type <span class="text-red-500">*</span>
                </label>

               <select
    name="role"
    x-model="selectedRole"
    {{ count($availableRoles) === 1 ? 'disabled' : '' }}
                    class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200 {{ count($availableRoles) === 1 ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                >
                    @foreach ($availableRoles as $role)
                        <option value="{{ $role }}" {{ old('role', $userRole) == $role ? 'selected' : '' }}>
                            @if ($role === 'admin') Business Owner (Admin)
                            @elseif ($role === 'employee') Employee
                            @else Client
                            @endif
                        </option>
                    @endforeach
                </select>

                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
                @if (count($availableRoles) === 1)
                    <input type="hidden" name="role" value="{{ $availableRoles[0] }}">
                @endif

                {{-- Note for invited users --}}
                @if ($companyCode)
                    <p class="text-xs text-[#2D6A4F] mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Your role has been set by your invitation.
                    </p>
                @endif
            </div>

            {{-- Business Type — locked from onboarding step 1 --}}
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Business Type
                </label>
                <div class="w-full bg-gray-100 border border-[#2D6A4F]/30 text-gray-700 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm cursor-not-allowed select-none capitalize flex items-center justify-between">
                    <span x-text="businessType.replace('_', ' ')"></span>
                    <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <p class="text-xs text-[#2D6A4F] mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    Selected during signup —
                    <a href="/onboarding/use-case" class="underline hover:text-[#0B3D2E]">change it here</a>
                </p>
                @error('business_type')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Invite Code (Shown ONLY for Employee/Client) --}}
            <div x-show="selectedRole !== 'admin'" x-transition>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Company Invite Code <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input
                        type="text"
                        name="invite_code"
                        x-model="inviteCode"
                        @input.debounce.500ms="fetchTeamData()"
                        x-init="if(isLocked) fetchTeamData()"
                        :readonly="isLocked"
                        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200 uppercase tracking-widest font-bold"
                        :class="{'bg-gray-100 cursor-not-allowed !text-gray-600': isLocked}"
                       placeholder="ENTER 8-CHARACTER CODE""
                        :required="selectedRole !== 'admin'"
                    >
                    <div x-show="fetchingTeam" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="animate-spin h-5 w-5 text-[#2D6A4F]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div x-show="teamFound && !fetchingTeam" class="absolute right-3 top-1/2 -translate-y-1/2">
                        <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-1" x-show="!teamFound && inviteCode.length >= 6 && !fetchingTeam && !isLocked">
                    Invalid invite code. Please check with your admin.
                </p>
                <p class="text-xs text-[#2D6A4F] mt-1" x-show="teamFound && !isLocked">
                    ✓ Company found! Details auto-filled below.
                </p>
                <p class="text-xs text-[#2D6A4F] mt-1 flex items-center gap-1" x-show="isLocked">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Your invite code has been pre-filled by your administrator.
                </p>
                @error('invite_code')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- First & Last Name --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        First name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="first_name"
                        value="{{ old('first_name', $prefill['first_name'] ?? optional($profile)->first_name) }}"
autofocus
                        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200"
                        :class="{'border-red-400 bg-red-50': '{{ $errors->has('first_name') }}'}"
                        placeholder="Jane"
                    >
                    @error('first_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Last name</label>
                    <input
                        type="text"
                        name="last_name"
                        value="{{ old('last_name', $prefill['last_name'] ?? optional($profile)->last_name) }}"
                        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200"
                        :class="{'border-red-400 bg-red-50': '{{ $errors->has('last_name') }}'}"
                        placeholder="Smith"
                    >
                    @error('last_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Company Name --}}
            <div x-show="selectedRole === 'admin' || teamFound">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Company name <span x-show="selectedRole === 'admin'" class="text-red-500">*</span>
                </label>
                <input
                    type="text"
                    name="company_name"
                    x-model="companyName"
                    :readonly="selectedRole !== 'admin' && teamFound"
                    :class="{'bg-gray-100 cursor-not-allowed': selectedRole !== 'admin' && teamFound}"
                    class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200"
                    :placeholder="selectedRole === 'admin' ? 'Acme Inc.' : 'Enter invite code first'"
                >
                <p class="text-xs text-gray-500 mt-1" x-show="selectedRole !== 'admin' && teamFound">
                    Auto-filled from company data
                </p>
            </div>

            {{-- Profile Picture Upload --}}
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Profile Picture <span class="text-gray-400 font-normal">(optional)</span>
                </label>
                <div class="flex items-center gap-4">
                    {{-- Image Preview --}}
                    <div class="flex-shrink-0">
                        <img id="picture-preview"
                             src="{{ $profile && $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(optional($profile)->first_name ?: 'User') . '&background=2D6A4F&color=fff&size=128' }}"
                             alt="Profile Preview"
                             class="w-16 h-16 sm:w-20 sm:h-20 lg:w-12 lg:h-12 rounded-full object-cover border-2 border-white shadow-md bg-gray-100">
                    </div>

                    {{-- File Input Button --}}
                    <div class="flex-1">
                        <label for="picture" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 lg:py-1.5 bg-white border border-gray-300 rounded-xl text-sm font-medium text-gray-700 shadow-sm hover:bg-white hover:border-gray-400 transition-all duration-200">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span>Choose Image</span>
<input id="picture" name="picture" type="file" class="hidden" onchange="previewImage(event)">                        </label>
                        <p class="text-xs text-gray-500 mt-1.5">JPG, PNG, or GIF. Max 2MB.</p>

                        @error('picture')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Industry & Domain --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4" x-show="selectedRole === 'admin' || teamFound">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Industry</label>
                    <input
                        type="text"
                        name="industry"
                        x-model="industry"
                        :readonly="selectedRole !== 'admin' && teamFound"
                        :class="{'bg-gray-100 cursor-not-allowed': selectedRole !== 'admin' && teamFound}"
                        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200"
                        placeholder="Technology"
                    >
                </div>
          <div>
    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
        Domain
    </label>

    <input
        type="text"

        x-model="domain"
        :readonly="selectedRole !== 'admin' && teamFound"
        :class="{'bg-gray-100 cursor-not-allowed': selectedRole !== 'admin' && teamFound}"
        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200"
        placeholder="example.com"
    >
</div>
                    {{-- Hidden field that actually submits the full URL --}}
                    <input type="hidden" name="domain" :value="'https://' + domain.replace(/^https?:\/\//i, '')">
                </div>
            

            {{-- Phone & Location --}}
<div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4" x-show="selectedRole === 'admin' || teamFound">
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                        Phone Number <span class="text-red-500">*</span>
                    </label>

                    {{-- Phone Input with Country Code --}}
                    <input
                        type="tel"
                        id="phone"
                        value="{{ old('phone', $prefill['phone'] ?? optional($profile)->phone) }}"
                        {{ $companyCode ? 'readonly' : '' }}
                        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all duration-200 {{ $companyCode ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        placeholder="555 123 4567"
                    >

                    {{-- Hidden Input to store the FULL number with country code --}}
                    <input type="hidden" name="phone" id="full_phone" value="{{ old('phone', $prefill['phone'] ?? optional($profile)->phone) }}">

                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">Location</label>
                    <input
                        type="text"
                        name="location"
                        x-model="location"
                        :readonly="selectedRole !== 'admin' && teamFound"
                        :class="{'bg-gray-100 cursor-not-allowed': selectedRole !== 'admin' && teamFound}"
                        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 lg:py-2 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200"
                        placeholder="New York, USA"
                    >
                </div>
            </div>

            {{-- Bio with Character Counter --}}
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1">
                    Bio <span class="text-gray-400 font-normal">(optional)</span>
                </label>
               <textarea
    x-model="bio"
    name="bio"
    rows="2"
                    class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 lg:py-1.5 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-400 transition-all duration-200 resize-none"
                    :class="{'border-red-400 bg-red-50': '{{ $errors->has('bio') }}'}"
                    placeholder="Tell us about yourself..."
                ></textarea>
                <div class="flex justify-between items-center mt-1">
                    @error('bio')
                        <p class="text-red-500 text-xs">{{ $message }}</p>
                    @else
                        <span></span>
                    @enderror
                    <p class="text-xs text-gray-500" x-text="`${bio.length}/${bioMax} characters`"></p>
                </div>
            </div>

            {{-- Submit Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                <button
                    type="submit"
                    :disabled="loading"
                    class="flex-1 text-white font-semibold py-3 sm:py-3.5 lg:py-2.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-[#2D6A4F]/20 hover:shadow-[#2D6A4F]/30 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex items-center justify-center gap-2 hover:opacity-90"
                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                >
                    <span x-show="!loading">Save Profile →</span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Saving...
                    </span>
                </button>
            </div>

        </form>
    </div>

    {{-- Back link --}}
    <p class="text-center text-gray-500 text-xs sm:text-sm mt-6 lg:mt-3">
        <a href="/onboarding/use-case" class="text-gray-500 hover:text-[#2D6A4F] font-medium transition-colors inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to previous step
        </a>
    </p>

</div>

{{-- Phone Input Library JavaScript --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js"></script>

<script>
    // Profile Picture Live Preview
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('picture-preview').src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const input = document.querySelector("#phone");
        const hiddenInput = document.querySelector("#full_phone");

        // Initialize the plugin WITHOUT IP detection
        const iti = window.intlTelInput(input, {
            initialCountry: "us", // Default to US (change to your preference)
            separateDialCode: true,
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.js",
            preferredCountries: ['us', 'gb', 'ca', 'au', 'pk', 'in'],
        });

        // Set initial value if exists
        if (hiddenInput.value) {
            iti.setNumber(hiddenInput.value);
        }

        // When the form is submitted, grab the full number
       const form = document.getElementById('profile-form');
form.addEventListener('submit', function(e) {
    if (iti.isValidNumber()) {
        hiddenInput.value = iti.getNumber();
    }
    // No else/preventDefault — let it submit either way; backend validates.
});

        // Update hidden input as user changes country
        input.addEventListener('countrychange', function() {
            if (iti.isValidNumber()) {
                hiddenInput.value = iti.getNumber();
            }
        });
    });
</script>
</body>
</html>