<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invitation – {{ $client->name }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-6">

<div class="bg-white rounded-2xl shadow-lg p-8 max-w-md w-full text-center">

    <!-- Success Icon -->
    <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
        <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>

    <h2 class="text-xl font-bold text-gray-900 mb-1">Client Added!</h2>
    <p class="text-gray-500 text-sm mb-6">
        Share this waiver link or QR code with <strong>{{ $client->name }}</strong>
    </p>

    <!-- QR Code -->
    <div class="bg-gray-50 rounded-xl p-4 mb-4 flex justify-center">
        {!! QrCode::size(180)->generate($link) !!}
    </div>

    <!-- Link -->
    <div class="flex items-center gap-2 bg-gray-100 rounded-xl px-4 py-3 mb-6">
        <span class="text-xs text-gray-600 truncate flex-1">{{ $link }}</span>
        <button onclick="navigator.clipboard.writeText('{{ $link }}'); this.innerText='Copied!'"
            class="text-xs font-semibold text-blue-600 hover:text-blue-700 shrink-0">
            Copy
        </button>
    </div>

    <!-- Actions -->
    <div class="flex gap-3">
        <a href="{{ route('clients.index') }}"
           class="flex-1 py-2.5 rounded-xl border border-gray-200 text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
            Back to Clients
        </a>
        <button onclick="window.print()"
           class="flex-1 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition">
            Print QR
        </button>
    </div>

</div>

</body>
</html>