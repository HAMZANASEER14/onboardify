<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment – Onboardify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-10 font-sans antialiased">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <span class="text-4xl">📋</span>
            <h1 class="text-gray-900 text-2xl font-bold mt-2 tracking-tight">Onboardify</h1>
            <p class="text-gray-500 text-sm mt-1">Complete your subscription</p>
        </div>

        {{-- Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <span>⚠️</span> {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white border border-gray-200 rounded-2xl p-8 shadow-sm">

            {{-- Plan Summary --}}
            <div class="flex items-center justify-between pb-6 mb-6 border-b border-gray-200">
                <div>
                    <div class="text-gray-900 font-semibold">{{ $plan->name }} Plan</div>
                    <div class="text-gray-500 text-xs mt-1">Billed after 7-day free trial</div>
                </div>
                <div class="text-right">
                    <div class="text-emerald-600 font-bold text-lg">FREE</div>
                    <div class="text-gray-500 text-xs">then ${{ $plan->price }}/mo</div>
                </div>
            </div>

            {{-- Test Mode Banner --}}
            <div class="bg-amber-50 border border-amber-200 text-amber-700 text-xs px-4 py-3 rounded-xl mb-6 flex items-start gap-2">
                <span class="flex-shrink-0 mt-0.5">🧪</span>
                <span>Test mode — no real payment will be charged</span>
            </div>

            {{-- Simple Form — no Stripe JS needed --}}
            <form method="POST" action="/payment/process" class="space-y-5">
                @csrf
                <input type="hidden" name="plan_id" value="{{ $plan->id }}">

                {{-- Fake Card Number --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Card number</label>
                    <input
                        type="text"
                        value="4242 4242 4242 4242"
                        disabled
                        class="w-full bg-gray-50 border border-gray-200 text-gray-400 rounded-xl px-4 py-3 text-sm cursor-not-allowed"
                    >
                </div>

                {{-- Fake Expiry & CVC --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">Expiry</label>
                        <input
                            type="text"
                            value="12/26"
                            disabled
                            class="w-full bg-gray-50 border border-gray-200 text-gray-400 rounded-xl px-4 py-3 text-sm cursor-not-allowed"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">CVC</label>
                        <input
                            type="text"
                            value="123"
                            disabled
                            class="w-full bg-gray-50 border border-gray-200 text-gray-400 rounded-xl px-4 py-3 text-sm cursor-not-allowed"
                        >
                    </div>
                </div>

                {{-- Cardholder Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Cardholder name</label>
                    <input
                        type="text"
                        name="cardholder_name"
                        value="{{ auth()->user()->name ?? '' }}"
                        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-4 py-3 text-sm placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 transition"
                        placeholder="Jane Smith"
                        required
                    >
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition shadow-sm hover:shadow-md flex items-center justify-center gap-2"
                >
                    <span>🔒</span> Confirm Payment (Test Mode)
                </button>

                <p class="text-center text-gray-500 text-xs">
                    No real charge · Test mode only
                </p>

            </form>
        </div>

        {{-- Back link --}}
        <p class="text-center text-gray-500 text-sm mt-6">
            <a href="/plans" class="hover:text-gray-700 transition font-medium">← Back to plans</a>
        </p>

    </div>

</body>
</html>