<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Edit Your Profile – Onboardify</title>
    
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    
    {{-- Alpine.js --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    {{-- ✅ Phone Input Library CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/css/intlTelInput.css">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* ✅ Phone Input Styling */
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
            width: 100%;
        }
        .iti__flag-container:hover {
            background: #f3f4f6;
        }
        .iti__country-list {
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-green-50 via-white to-emerald-50 flex items-center justify-center px-4 py-6 sm:py-10 font-sans antialiased text-gray-900">

    <div class="w-full max-w-xl">

        {{-- Logo --}}
        <div class="text-center mb-6 sm:mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 sm:w-16 sm:h-16 rounded-2xl shadow-lg shadow-[#2D6A4F]/20 mb-3" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-8 h-8 sm:w-9 sm:h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <h1 class="text-gray-900 text-xl sm:text-2xl font-bold tracking-tight">Onboardify</h1>
            <p class="text-gray-500 text-xs sm:text-sm mt-1">Edit your profile</p>
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
        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-2xl sm:rounded-3xl p-5 sm:p-8 shadow-xl shadow-gray-200/50">

            <h2 class="text-gray-900 text-base sm:text-lg font-semibold mb-4 sm:mb-6 tracking-tight">Your Details</h2>

            <form method="POST" action="{{ route('profile.update') }}" id="profile-form" class="space-y-4 sm:space-y-5" enctype="multipart/form-data" x-data="{                 
                bio: '{{ old('bio', optional($profile)->bio) }}', 
                bioMax: 200,
                loading: false,
                selectedRole: '{{ old('role', $userRole) }}',
                businessType: '{{ old('business_type', optional($profile)->business_type) }}' || 'company',
                companyName: '{{ old('company_name', optional($profile)->company_name) }}',
                industry: '{{ old('industry', optional($profile)->industry) }}',
                domain: '{{ old('domain', optional($profile)->domain) }}',
                location: '{{ old('location', optional($profile)->location) }}',
                currentRole: '{{ $userRole }}'
            }">
                @csrf
                @method('PUT')

                {{-- Info Box: Cannot Change From Admin --}}
                @if($userRole === 'admin')
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-amber-800 font-medium text-sm">Admin Account</p>
                                <p class="text-amber-700 text-xs mt-1">You cannot change your role from Admin. If you want to leave your admin role, please transfer ownership of your team first.</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Role Selection (Disabled if Admin) --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">
                        Account Type <span class="text-red-500">*</span>
                    </label>
                    
                    <select 
                        name="role" 
                        x-model="selectedRole"
                        :disabled="currentRole === 'admin'"
                        required
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed disabled:bg-gray-100"
                    >
                        <option value="admin" {{ old('role', $userRole) == 'admin' ? 'selected' : '' }}>
                            Business Owner (Admin) 
                        </option>
                        <option value="employee" {{ old('role', $userRole) == 'employee' ? 'selected' : '' }}>
                            Employee
                        </option>
                        <option value="client" {{ old('role', $userRole) == 'client' ? 'selected' : '' }}>
                            Client
                        </option>
                    </select>
                    
                    @error('role')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                {{-- Profile Picture Upload --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">
                        Profile Picture <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <div class="flex items-center gap-4">
                        {{-- Image Preview (Shows current picture or default avatar) --}}
                        <div class="flex-shrink-0">
                            <img id="picture-preview" 
                                 src="{{ $profile && $profile->profile_picture ? asset('storage/' . $profile->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode(optional($profile)->first_name ?: 'User') . '&background=2D6A4F&color=fff&size=128' }}" 
                                 alt="Profile Preview" 
                                 class="w-16 h-16 sm:w-20 sm:h-20 rounded-full object-cover border-2 border-white shadow-md bg-gray-100">
                        </div>
                        
                        {{-- File Input Button --}}
                        <div class="flex-1">
                            <label for="picture" class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:border-gray-300 transition-all duration-200">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Change Image</span>
                                <input id="picture" name="picture" type="file" class="hidden" accept="image/jpeg,image/png,image/jpg,image/gif,image/webp" onchange="previewImage(event)">
                            </label>
                            <p class="text-xs text-gray-500 mt-1.5">JPG, PNG, or GIF. Max 2MB.</p>
                            
                            @error('picture')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- First & Last Name --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">
                            First name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="first_name"
                            value="{{ old('first_name', optional($profile)->first_name) }}"
                            required
                            autofocus
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition-all duration-200"
                            :class="{'border-red-400 bg-red-50': '{{ $errors->has('first_name') }}'}"
                            placeholder="Jane"
                        >
                        @error('first_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Last name</label>
                        <input
                            type="text"
                            name="last_name"
                            value="{{ old('last_name', optional($profile)->last_name) }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition-all duration-200"
                            :class="{'border-red-400 bg-red-50': '{{ $errors->has('last_name') }}'}"
                            placeholder="Smith"
                        >
                        @error('last_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Company Name --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">
                        Company name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        name="company_name"
                        x-model="companyName"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition-all duration-200"
                        placeholder="Acme Inc."
                    >
                    @error('company_name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Industry & Domain --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Industry</label>
                        <input
                            type="text"
                            name="industry"
                            x-model="industry"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="Technology"
                        >
                        @error('industry')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Domain</label>
                        <input
                            type="url"
                            name="domain"
                            x-model="domain"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="acme.com"
                        >
                        @error('domain')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Phone & Location --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        
                        {{-- ✅ Phone Input with Country Code --}}
                        <input 
                            type="tel" 
                            id="phone" 
                            value="{{ old('full_phone', optional($profile)->phone) }}"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all duration-200"
                            placeholder="555 123 4567"
                        >
                        
                        {{-- Hidden Input to store the FULL number with country code --}}
                        <input type="hidden" name="full_phone" id="full_phone" value="{{ old('full_phone', optional($profile)->phone) }}">

                        @error('full_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">Location</label>
                        <input
                            type="text"
                            name="location"
                            x-model="location"
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2.5 sm:py-3 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition-all duration-200"
                            placeholder="New York, USA"
                        >
                        @error('location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Bio with Character Counter --}}
                <div>
                    <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1.5">
                        Bio <span class="text-gray-400 font-normal">(optional)</span>
                    </label>
                    <textarea
                        x-model="bio"
                        name="bio"
                        rows="3"
                        maxlength="200"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl px-3 sm:px-4 py-2 sm:py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition-all duration-200 resize-none"
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

                {{-- Hidden business_type field (preserved from initial creation) --}}
                <input type="hidden" name="business_type" value="{{ old('business_type', optional($profile)->business_type) }}">

                {{-- Submit Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button
                        type="submit"
                        :disabled="loading"
                        class="flex-1 text-white font-semibold py-3 sm:py-3.5 rounded-xl text-sm transition-all duration-200 shadow-lg shadow-[#2D6A4F]/20 hover:shadow-[#2D6A4F]/30 hover:-translate-y-0.5 active:translate-y-0 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0 flex items-center justify-center gap-2 hover:opacity-90"
                        style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                    >
                        <span x-show="!loading">Update Profile →</span>
                        <span x-show="loading" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Updating...
                        </span>
                    </button>
                    <a href="{{ route('profile.show') }}" 
                        class="flex-1 text-center bg-gray-100 hover:bg-gray-200 active:bg-gray-300 text-gray-700 font-semibold py-3 sm:py-3.5 rounded-xl text-sm transition-all duration-200">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

    {{-- ✅ Phone Input Script --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js"></script>
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

        const input = document.querySelector("#phone");
        const fullPhoneInput = document.querySelector("#full_phone");
        let intlInput;

        if (input) {
            intlInput = window.intlTelInput(input, {
                initialCountry: "us",
                separateDialCode: true,
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.19/js/utils.min.js",
            });

            input.addEventListener("change", function () {
                const fullNumber = intlInput.getNumber();
                if (fullPhoneInput) {
                    fullPhoneInput.value = fullNumber || input.value;
                }
            });

            // Update on input (debounce)
            let debounceTimer;
            input.addEventListener("input", function () {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    const fullNumber = intlInput.getNumber();
                    if (fullPhoneInput) {
                        fullPhoneInput.value = fullNumber || input.value;
                    }
                }, 500);
            });

            // Set initial value if exists
            if (input.value && fullPhoneInput && !fullPhoneInput.value) {
                fullPhoneInput.value = input.value;
            }
        }
    </script>

</body>
</html>