<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Choose Your Plan – Onboardify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-10 font-sans antialiased">

    <div class="w-full max-w-4xl">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <span class="text-4xl">📋</span>
            <h1 class="text-gray-900 text-2xl font-bold mt-2 tracking-tight">Onboardify</h1>
            <p class="text-gray-500 text-sm mt-1">Step 3 of 3 — Choose your plan</p>
        </div>

        {{-- Progress --}}
        <div class="flex items-center gap-2 justify-center mb-10">
            <div class="h-1.5 w-24 bg-blue-600 rounded-full"></div>
            <div class="h-1.5 w-24 bg-blue-600 rounded-full"></div>
            <div class="h-1.5 w-24 bg-blue-600 rounded-full"></div>
        </div>

        {{-- Flash Message --}}
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-xl mb-6 text-center flex items-center justify-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        {{-- Plans Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @foreach($plans as $index => $plan)
            <div class="bg-white border rounded-2xl p-6 flex flex-col relative transition-all duration-200 hover:shadow-md
                {{ $index === 1 ? 'border-blue-500 ring-1 ring-blue-500 shadow-md' : 'border-gray-200' }}">

                {{-- Popular Badge (middle plan) --}}
                @if($index === 1)
                    <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                        <span class="bg-blue-600 text-white text-xs font-semibold px-3 py-1 rounded-full shadow-sm">
                            Most Popular
                        </span>
                    </div>
                @endif

                {{-- Plan Name --}}
                <h2 class="text-gray-900 text-lg font-bold mb-1">{{ $plan->name }}</h2>
                <p class="text-gray-500 text-xs mb-6">
                    {{ $plan->description ?? 'Perfect for your needs' }}
                </p>

                {{-- Price --}}
                <div class="mb-6">
                    <span class="text-gray-900 text-4xl font-bold">${{ $plan->price }}</span>
                    <span class="text-gray-500 text-sm">/month</span>
                </div>

                {{-- Features --}}
                <ul class="space-y-3 mb-8 flex-1">
                    @foreach(explode(',', $plan->features ?? '') as $feature)
                        @if(trim($feature))
                        <li class="flex items-center gap-3 text-sm text-gray-600">
                            <span class="text-emerald-600 font-bold flex-shrink-0">✓</span>
                            {{ trim($feature) }}
                        </li>
                        @endif
                    @endforeach
                </ul>

                {{-- Select Button --}}
                <form method="POST" action="/plans/select">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <button type="submit"
                        class="w-full py-3 rounded-xl text-sm font-semibold transition shadow-sm hover:shadow-md
                            {{ $index === 1
                                ? 'bg-blue-600 hover:bg-blue-700 text-white'
                                : 'bg-white hover:bg-gray-50 text-gray-900 border border-gray-300' }}">
                        Select {{ $plan->name }}
                    </button>
                </form>

            </div>
            @endforeach

        </div>

        {{-- Back link --}}
        <p class="text-center text-gray-500 text-sm mt-8">
            <a href="/profile" class="hover:text-gray-700 transition font-medium">← Back to profile</a>
        </p>

    </div>

</body>
</html>