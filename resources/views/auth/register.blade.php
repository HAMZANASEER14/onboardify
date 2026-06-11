<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        [x-cloak] { display: none !important; }
        .strength-bar { transition: width 0.3s ease, background-color 0.3s ease; }
        html, body { 
            height: 100%; 
            overflow: hidden; 
        }
    </style>
</head>
<body class="h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex items-center justify-center px-4 font-sans antialiased text-gray-900">

<div 
    class="w-full max-w-md max-h-full flex flex-col"
    x-data="registerForm()" 
    x-init="setTimeout(() => loaded = true, 50)"
    x-show="loaded"
    x-transition:enter="transition ease-out duration-500"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-cloak
>

    {{-- Logo & Header --}}
    <div class="text-center mb-4">
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/20 mb-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
        </div>
        <h1 class="text-gray-900 text-2xl font-bold tracking-tight">Create an account</h1>
        <p class="text-gray-500 text-sm mt-1">Join Onboardify today.</p>
    </div>

    {{-- Card --}}
    <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl p-6 shadow-xl shadow-gray-200/50 overflow-y-auto max-h-[calc(100vh-280px)]">

        <form method="POST" action="{{ route('register.post') }}" class="space-y-4"
              @submit.prevent="canSubmit ? $el.submit() : null">
            @csrf

            {{-- Name --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Full name</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input type="text" name="name" required
                        x-model="name"
                        @input="formatName($event)"
                         value="{{ old('name') }}"
                        :class="name ? (nameError ? 'border-red-400 bg-red-50' : 'border-green-400 bg-green-50') : 'border-gray-200 bg-gray-50'"
                        class="w-full rounded-xl pl-10 pr-10 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 border"
                        placeholder="Jane Smith">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span x-show="nameOk" class="text-green-500 text-sm">&#10003;</span>
                        <span x-show="name && nameError" class="text-red-500 text-sm">&#10007;</span>
                    </div>
                </div>
                <p x-show="name && nameError" x-text="nameError" class="text-red-500 text-xs mt-1 ml-1"></p>
            </div>

            {{-- Email --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Email address</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <input type="email" name="email" required
                        x-model="email"
                        @input="serverEmailError = ''"
                        value="{{ old('email') }}"
                        :class="email ? ((emailError || serverEmailError) ? 'border-red-400 bg-red-50' : 'border-green-400 bg-green-50') : 'border-gray-200 bg-gray-50'"
                        class="w-full rounded-xl pl-10 pr-10 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 border"
                        placeholder="you@example.com">
                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                        <span x-show="emailOk && !serverEmailError" class="text-green-500 text-sm">&#10003;</span>
                        <span x-show="email && (emailError || serverEmailError)" class="text-red-500 text-sm">&#10007;</span>
                    </div>
                </div>
                <p x-show="email && emailError && !serverEmailError" x-text="emailError" class="text-red-500 text-xs mt-1 ml-1"></p>
                <p x-show="serverEmailError" x-text="serverEmailError" class="text-red-500 text-xs mt-1 ml-1"></p>
            </div>

            {{-- Password --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input :type="showPassword ? 'text' : 'password'" name="password" required
                        x-model="password"
                        :class="password ? (passwordError ? 'border-red-400 bg-red-50' : 'border-green-400 bg-green-50') : 'border-gray-200 bg-gray-50'"
                        class="w-full rounded-xl pl-10 pr-12 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 border"
                        placeholder="Min 8 chars, A-Z, 0-9, symbol">
                    <button type="button" @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                        <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>

                {{-- Strength bar --}}
                <div x-show="password" class="mt-2">
                    <div class="flex justify-between mb-1">
                        <span class="text-xs text-gray-500">Strength</span>
                        <span class="text-xs font-semibold"
                            :class="strengthScore<=2?'text-red-500':strengthScore===3?'text-yellow-500':strengthScore===4?'text-blue-500':'text-green-500'"
                            x-text="strengthLabel"></span>
                    </div>
                    <div class="h-1.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full strength-bar"
                            :class="strengthScore<=2?'bg-red-500':strengthScore===3?'bg-yellow-500':strengthScore===4?'bg-blue-500':'bg-green-500'"
                            :style="'width:'+(strengthScore/5*100)+'%'"></div>
                    </div>
                    <div class="grid grid-cols-2 gap-x-3 gap-y-1 mt-2">
                        <div class="flex items-center gap-1 text-xs" :class="hasLength?'text-green-600':'text-gray-400'">
                            <span x-text="hasLength ? '✓' : '+'"></span> 8+ chars
                        </div>
                        <div class="flex items-center gap-1 text-xs" :class="hasUpper?'text-green-600':'text-gray-400'">
                            <span x-text="hasUpper ? '✓' : '+'"></span> Uppercase
                        </div>
                        <div class="flex items-center gap-1 text-xs" :class="hasLower?'text-green-600':'text-gray-400'">
                            <span x-text="hasLower ? '✓' : '+'"></span> Lowercase
                        </div>
                        <div class="flex items-center gap-1 text-xs" :class="hasNumber?'text-green-600':'text-gray-400'">
                            <span x-text="hasNumber ? '✓' : '+'"></span> Number
                        </div>
                        <div class="flex items-center gap-1 text-xs col-span-2" :class="hasSpecial?'text-green-600':'text-gray-400'">
                            <span x-text="hasSpecial ? '✓' : '+'"></span> Special (!@#$)
                        </div>
                    </div>
                </div>
                <p x-show="password && passwordError" x-text="passwordError" class="text-red-500 text-xs mt-1 ml-1"></p>
            </div>

            {{-- Confirm Password --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Confirm password</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation" required
                        x-model="passwordConfirm"
                        :class="passwordConfirm ? (confirmError ? 'border-red-400 bg-red-50' : 'border-green-400 bg-green-50') : 'border-gray-200 bg-gray-50'"
                        class="w-full rounded-xl pl-10 pr-12 py-2.5 text-sm placeholder-gray-400 focus:outline-none focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 transition-all duration-200 border"
                        placeholder="Repeat password">
                    <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
                        class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 focus:outline-none transition-colors">
                        <svg x-show="!showPasswordConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <svg x-show="showPasswordConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:none">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                <p x-show="passwordConfirm && confirmError" x-text="confirmError" class="text-red-500 text-xs mt-1 ml-1"></p>
            </div>

            {{-- Submit --}}
            <button type="submit"
                :disabled="!canSubmit"
                :class="canSubmit ? 'bg-blue-600 hover:bg-blue-700 active:bg-blue-800 cursor-pointer shadow-lg shadow-blue-600/20 hover:shadow-blue-600/30 hover:-translate-y-0.5 active:translate-y-0' : 'bg-gray-300 cursor-not-allowed'"
                class="w-full text-white font-semibold py-3 rounded-xl text-sm transition-all duration-200 flex items-center justify-center gap-2 mt-1">
                <span>Create Account</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>

            <p class="text-center text-xs text-gray-500 mt-3">
                By creating an account, you agree to our
                <a href="#" class="underline hover:text-blue-600 transition-colors duration-200">Terms</a> and
                <a href="#" class="underline hover:text-blue-600 transition-colors duration-200">Privacy Policy</a>.
            </p>
        </form>
    </div>

    {{-- Switch to login --}}
    <p class="text-center text-gray-500 text-sm mt-4">
        Already have an account?
        <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-semibold hover:underline transition-all">
            Sign in
        </a>
    </p>

</div>

<script>
function registerForm() {
    return {
        loaded: false,
        name: '{{ old('name') }}',
        email: '{{ old('email') }}',
        password: '',
        passwordConfirm: '',
        showPassword: false,
        showPasswordConfirm: false,

        serverEmailError: '{{ $errors->first('email') }}',

        get nameError() {
            if (!this.name) return '';
            if (/\d/.test(this.name)) return 'Name should not contain numbers.';
            if (!/^[A-Z]/.test(this.name)) return 'Name must start with a capital letter.';
            if (this.name.trim().split(/\s+/).length < 2) return 'Please enter your first and last name.';
            return '';
        },
        get nameOk() { return this.name.length > 0 && this.nameError === ''; },

        get emailError() {
            if (!this.email) return '';
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.email) ? '' : 'Please enter a valid email address.';
        },

        get emailOk() {
            return this.email.length > 0 && this.emailError === '' && !this.serverEmailError;
        },

        get hasLength()  { return this.password.length >= 8; },
        get hasUpper()   { return /[A-Z]/.test(this.password); },
        get hasLower()   { return /[a-z]/.test(this.password); },
        get hasNumber()  { return /[0-9]/.test(this.password); },
        get hasSpecial() { return /[^A-Za-z0-9]/.test(this.password); },

        get strengthScore() {
            return [this.hasLength, this.hasUpper, this.hasLower, this.hasNumber, this.hasSpecial].filter(Boolean).length;
        },
        get strengthLabel() {
            if (!this.password) return '';
            if (this.strengthScore <= 2) return 'Weak';
            if (this.strengthScore === 3) return 'Fair';
            if (this.strengthScore === 4) return 'Good';
            return 'Strong';
        },

        get passwordError() {
            if (!this.password) return '';
            if (!this.hasLength)  return 'Password must be at least 8 characters.';
            if (!this.hasUpper)   return 'Add at least one uppercase letter (A-Z).';
            if (!this.hasLower)   return 'Add at least one lowercase letter (a-z).';
            if (!this.hasNumber)  return 'Add at least one number (0-9).';
            if (!this.hasSpecial) return 'Add at least one special character (!@#$%...).';
            return '';
        },
        get passwordOk() { return this.password.length > 0 && this.passwordError === ''; },

        get confirmError() {
            if (!this.passwordConfirm) return '';
            return this.passwordConfirm !== this.password ? 'Passwords do not match.' : '';
        },
        get confirmOk() { return this.passwordConfirm.length > 0 && this.confirmError === ''; },

        get canSubmit() {
            return this.nameOk && this.emailOk && !this.serverEmailError && this.passwordOk && this.confirmOk;
        },

        formatName(e) {
            this.name = e.target.value.replace(/\b\w/g, c => c.toUpperCase());
        }
    }
}
</script>

</body>
</html>