<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Payment – Onboardify</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://js.stripe.com/v3/"></script>
</head>
<body class="h-screen bg-gray-50 flex items-center justify-center px-4 py-4 font-sans antialiased">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-0.5">
            <span class="text-2xl">💳</span>
            <h1 class="text-gray-900 text-xl font-bold mt-0.5 tracking-tight">Onboardify</h1>
            <p class="text-gray-500 text-xs mt-0.5">Complete your subscription</p>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm">

            {{-- Plan Summary --}}
            <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-200">
                <div>
                    <div class="text-gray-900 font-semibold text-sm">{{ $plan->name }} Plan</div>
                    <div class="text-gray-500 text-xs mt-0.5">
                        @if($plan->name === 'Monthly') Billed every month
                        @elseif($plan->name === '1 Year') Billed once a year
                        @else Billed every 2 years
                        @endif
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[#2D6A4F] font-bold text-base">SGD ${{ $plan->price }}</div>
                    <div class="text-gray-500 text-xs">
                        @if($plan->name === 'Monthly') /mo
                        @elseif($plan->name === '1 Year') /yr
                        @else /2 yrs
                        @endif
                    </div>
                </div>
            </div>

            {{-- Test Mode Banner
            <div class="bg-amber-50 border border-amber-200 text-amber-700 text-xs px-3 py-2 rounded-xl mb-4 flex items-start gap-2">
                <span class="flex-shrink-0 mt-0.5">🧪</span>
                <span>Test mode — use card <strong>4242 4242 4242 4242</strong>, any future expiry, any CVC</span>
            </div> --}}

            {{-- Payment Form --}}
            <form id="payment-form" class="space-y-3">
                <input type="hidden" id="plan-id" value="{{ $plan->id }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Card number</label>
                    <div id="card-number-element"
                        class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5"></div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Expiry</label>
                        <div id="card-expiry-element"
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CVC</label>
                        <div id="card-cvc-element"
                            class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cardholder name</label>
                    <input type="text" id="cardholder-name"
                        value="{{ auth()->user()->name ?? '' }}"
                        class="w-full bg-white border border-gray-300 text-gray-900 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-[#2D6A4F] focus:ring-1 focus:ring-[#2D6A4F] transition"
                        placeholder="Jane Smith" required>
                </div>

                <div id="card-errors" class="hidden bg-red-50 border border-red-200 text-red-600 text-sm px-3 py-2 rounded-xl flex items-center gap-2">
                    <span>⚠️</span>
                    <span id="card-errors-text"></span>
                </div>

                <button type="submit" id="submit-btn"
                    class="w-full text-white font-semibold py-2.5 rounded-xl text-sm transition shadow-sm hover:shadow-md hover:opacity-90 flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    <span>🔒</span>
                    <span id="btn-label">Pay SGD ${{ $plan->price }}</span>
                </button>

                <p class="text-center text-gray-500 text-xs">No real charge · Test mode only</p>
            </form>

        </div>

        <p class="text-center text-gray-500 text-xs mt-2">
            <a href="/plans" class="hover:text-[#2D6A4F] transition font-medium">← Back to plans</a>
        </p>
    </div>

    <script>
        const stripe   = Stripe('{{ config("services.stripe.key") }}');
        const elements = stripe.elements();

        const style = {
            base: {
                fontSize: '14px',
                fontFamily: 'Inter, sans-serif',
                color: '#111827',
                '::placeholder': { color: '#9ca3af' },
            },
            invalid: { color: '#ef4444' }
        };

        const cardNumber = elements.create('cardNumber', { style });
        const cardExpiry = elements.create('cardExpiry', { style });
        const cardCvc    = elements.create('cardCvc',    { style });

        cardNumber.mount('#card-number-element');
        cardExpiry.mount('#card-expiry-element');
        cardCvc.mount('#card-cvc-element');

        [cardNumber, cardExpiry, cardCvc].forEach(el => {
            el.on('change', (event) => {
                const errorDiv  = document.getElementById('card-errors');
                const errorText = document.getElementById('card-errors-text');
                if (event.error) {
                    errorText.textContent = event.error.message;
                    errorDiv.classList.remove('hidden');
                } else {
                    errorDiv.classList.add('hidden');
                }
            });
        });

     document.getElementById('payment-form').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn       = document.getElementById('submit-btn');
    const errorDiv  = document.getElementById('card-errors');
    const errorText = document.getElementById('card-errors-text');

    if (btn.disabled) return; // prevent double submission

    btn.disabled  = true;
            btn.innerHTML = `
                <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
                <span>Processing...</span>`;
            errorDiv.classList.add('hidden');

            const planId    = document.getElementById('plan-id').value;
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // Step 1: Get client_secret
            let clientSecret;
            try {
                const intentRes = await fetch('/payment/create-intent', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({ plan_id: planId }),
                });

                const intentData = await intentRes.json();
                if (intentData.error) throw new Error(intentData.error);
                clientSecret = intentData.client_secret;

            } catch (err) {
                errorText.textContent = err.message;
                errorDiv.classList.remove('hidden');
                resetBtn();
                return;
            }

            // Step 2: Confirm payment
            const { paymentIntent, error } = await stripe.confirmCardPayment(clientSecret, {
                payment_method: {
                    card: cardNumber,
                    billing_details: {
                        name: document.getElementById('cardholder-name').value,
                    },
                },
            });

            if (error) {
                errorText.textContent = error.message;
                errorDiv.classList.remove('hidden');
                resetBtn();
                return;
            }

            // Step 3: Save subscription
            try {
                const saveRes = await fetch('/payment/process', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                    },
                    body: JSON.stringify({
                        plan_id:           planId,
                        payment_intent_id: paymentIntent.id,
                    }),
                });

                const result = await saveRes.json();
                if (result.success) {
                    window.location.href = '/dashboard?success=1';
                } else {
                    throw new Error(result.error ?? 'Something went wrong.');
                }

            } catch (err) {
                errorText.textContent = err.message;
                errorDiv.classList.remove('hidden');
                resetBtn();
            }
        });

        function resetBtn() {
            const btn = document.getElementById('submit-btn');
            btn.disabled  = false;
            btn.innerHTML = '<span>🔒</span><span>Pay SGD ${{ $plan->price }}</span>';
        }
    </script>

</body>
</html>