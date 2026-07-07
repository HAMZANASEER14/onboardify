<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Plan – Onboardify</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">

    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-6 font-sans antialiased">

    <div class="w-full max-w-xl">

        {{-- Logo --}}
        <div class="text-center mb-3">
            <span class="text-3xl">💳</span>
            <h1 class="text-gray-900 text-xl font-bold mt-1 tracking-tight">Onboardify</h1>
            <p class="text-gray-500 text-xs mt-0.5">Step 3 of 3 — Choose your plan</p>
        </div>

        {{-- Progress --}}
        <div class="flex items-center gap-2 justify-center mb-6">
            <div class="h-1 w-16 bg-[#2D6A4F] rounded-full"></div>
            <div class="h-1 w-16 bg-[#2D6A4F] rounded-full"></div>
            <div class="h-1 w-16 bg-[#2D6A4F] rounded-full"></div>
        </div>

        {{-- Plans Grid --}}
       <div class="max-w-3xl mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">

            @foreach($plans as $index => $plan)
           <div class="bg-white border border-gray-200 rounded-xl p-3 flex flex-col relative transition-all duration-200 hover:shadow-md">

                {{-- Plan Name --}}
                <h2 class="text-gray-900 text-base font-bold mb-0.5">{{ $plan->name }}</h2>
                <p class="text-gray-500 text-[11px] mb-3">
                    @if($plan->name === 'Monthly') Billed every month
                    @elseif($plan->name === '1 Year') Billed once a year
                    @else Best value — billed every 2 years
                    @endif
                </p>

                {{-- Price --}}
                <div class="mb-1.5">
                    <span class="text-gray-400 text-xs font-medium">SGD</span>
                    <span class="text-gray-900 text-3xl font-bold">${{ $plan->price }}</span>
                    <span class="text-gray-500 text-xs">
                        @if($plan->name === 'Monthly') /mo
                        @elseif($plan->name === '1 Year') /yr
                        @else /2 yrs
                        @endif
                    </span>
                </div>

                {{-- Savings badge --}}
                @if($plan->name === '1 Year')
                    <span class="text-[10px] text-green-600 bg-green-50 px-2 py-0.5 rounded-full w-fit mb-3">Save 2 months</span>
                @elseif($plan->name === '2 Years')
                    <span class="text-[10px] text-green-600 bg-green-50 px-2 py-0.5 rounded-full w-fit mb-3">Save 5 months</span>
                @else
                    <div class="mb-3"></div>
                @endif

                {{-- Features --}}
                <ul class="space-y-2 mb-5 flex-1">
                    @foreach(explode(',', $plan->features ?? '') as $feature)
                        @if(trim($feature))
                        <li class="flex items-center gap-2 text-xs text-gray-600">
                            <span class="text-[#2D6A4F] font-bold flex-shrink-0">✓</span>
                            {{ trim($feature) }}
                        </li>
                        @endif
                    @endforeach
                </ul>

                {{-- Select Button --}}
                <form method="POST" action="/plans/select">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                   <button
    type="submit"
    class="w-full py-2.5 rounded-lg text-xs font-semibold text-white bg-[#2D6A4F] hover:bg-[#0B3D2E] transition shadow-sm">
    Get {{ $plan->name }}
</button>
                </form>

            </div>
            @endforeach

        </div>

        <p class="text-center text-gray-500 text-xs mt-5">
            <a href="/profile" class="hover:text-[#2D6A4F] transition font-medium">← Back to profile</a>
        </p>

    </div>
</body>
</html>