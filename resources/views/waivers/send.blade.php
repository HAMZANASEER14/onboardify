<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Waiver – Onboardify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<div class="bg-white border-b border-gray-200 shadow-sm px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="text-2xl">📋</span>
        <span class="font-bold text-lg text-gray-900">Onboardify</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500 text-sm">Send Waiver</span>
    </div>
    <a href="{{ route('waivers.index') }}"
       class="text-gray-500 hover:text-gray-900 text-sm transition">← Back to Waivers</a>
</div>

<div class="max-w-xl mx-auto px-6 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">📤 Send Waiver</h1>
        <p class="text-gray-500 text-sm mt-1">
            Sending: <strong>{{ $waiver->title }}</strong>
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
        <form action="{{ route('waivers.send', $waiver) }}" method="POST">
            @csrf

            {{-- Client Name --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Client Name <span class="text-red-500">*</span>
                </label>
                <input type="text"
                       name="client_name"
                       value="{{ old('client_name') }}"
                       placeholder="John Doe"
                       required
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
            </div>

            {{-- Client Email --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Client Email <span class="text-red-500">*</span>
                </label>
                <input type="email"
                       name="client_email"
                       value="{{ old('client_email') }}"
                       placeholder="john@example.com"
                       required
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
            </div>

            {{-- Waiver Summary --}}
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 mb-6">
                <p class="text-xs text-gray-500 mb-1">Waiver</p>
                <p class="text-gray-900 font-medium text-sm">{{ $waiver->title }}</p>
                <p class="text-gray-500 text-xs mt-1">
                    {{ count($waiver->fields ?? []) }} fields
                    • {{ $waiver->require_signature ? 'Signature required' : 'No signature' }}
                </p>
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl text-sm transition">
                📧 Send Waiver via Email
            </button>

        </form>
    </div>
</div>

</body>
</html>