<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Waiver – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

{{-- Header --}}
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

<div class="max-w-2xl mx-auto px-6 py-8 space-y-6">

    <div>
        <h1 class="text-2xl font-bold text-gray-900">📤 Send Waiver</h1>
        <p class="text-gray-500 text-sm mt-1">Sending: <strong>{{ $waiver->title }}</strong></p>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    {{-- ── Section 1: Send via Email ── --}}
    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
        <h2 class="font-semibold text-gray-900 text-base mb-1">📧 Send via Email</h2>
        <p class="text-gray-500 text-xs mb-5">Add one or multiple recipients</p>

        <form action="{{ route('waivers.send', $waiver) }}" method="POST">
            @csrf

            <div id="email-list" class="space-y-3 mb-4">
                {{-- First row --}}
                <div class="email-row flex items-center gap-2">
                    <input type="text"
                           name="names[]"
                           placeholder="Full Name"
                           required
                           class="w-1/3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-blue-500 transition">
                    <input type="email"
                           name="emails[]"
                           placeholder="email@example.com"
                           required
                           class="flex-1 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-blue-500 transition">
                    <button type="button"
                            onclick="removeRow(this)"
                            class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center transition text-lg font-bold shrink-0 remove-btn"
                            style="display:none">✕</button>
                </div>
            </div>

            {{-- Add More --}}
            <button type="button"
                    onclick="addRow()"
                    class="flex items-center gap-2 text-blue-600 hover:text-blue-700 text-sm font-medium transition mb-6">
                <span class="w-5 h-5 rounded-full bg-blue-100 flex items-center justify-center text-xs font-bold">+</span>
                Add another recipient
            </button>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl text-sm transition">
                📧 Send to All Recipients
            </button>
        </form>
    </div>

    {{-- ── Section 2: Shareable Link ── --}}
    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
        <h2 class="font-semibold text-gray-900 text-base mb-1">🔗 Shareable Link</h2>
        <p class="text-gray-500 text-xs mb-4">Anyone with this link can fill and sign the waiver</p>

        <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
            <span class="text-sm text-gray-600 truncate flex-1" id="share-link">{{ $link }}</span>
            <button onclick="copyLink()"
                    id="copy-btn"
                    class="text-xs font-semibold text-blue-600 hover:text-blue-700 shrink-0 transition">
                Copy
            </button>
        </div>
    </div>

    {{-- ── Section 3: QR Code ── --}}
<div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 text-center">
    <h2 class="font-semibold text-gray-900 text-base mb-1">📱 QR Code</h2>
    <p class="text-gray-500 text-xs mb-5">Clients scan this to open and sign the waiver instantly</p>

    <div class="flex justify-center mb-5">
        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($link) }}"
             alt="QR Code"
             class="rounded-xl border border-gray-100 p-2">
    </div>

    <div class="flex gap-3 justify-center">
        <button onclick="window.print()"
                class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
            🖨️ Print QR
        </button>
        <a href="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($link) }}"
           download="waiver-qr-{{ $waiver->slug }}.png"
           target="_blank"
           class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl transition">
            ⬇️ Download QR
        </a>
    </div>
</div>

</div>

<script>
    function addRow() {
        const list = document.getElementById('email-list');
        const row = document.createElement('div');
        row.className = 'email-row flex items-center gap-2';
        row.innerHTML = `
            <input type="text" name="names[]" placeholder="Full Name" required
                class="w-1/3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-blue-500 transition">
            <input type="email" name="emails[]" placeholder="email@example.com" required
                class="flex-1 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-blue-500 transition">
            <button type="button" onclick="removeRow(this)"
                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center transition text-lg font-bold shrink-0">✕</button>
        `;
        list.appendChild(row);
        updateRemoveButtons();
    }

    function removeRow(btn) {
        btn.closest('.email-row').remove();
        updateRemoveButtons();
    }

    function updateRemoveButtons() {
        const rows = document.querySelectorAll('.email-row');
        rows.forEach((row, index) => {
            const btn = row.querySelector('.remove-btn');
            if (btn) btn.style.display = rows.length > 1 ? 'flex' : 'none';
        });
    }

    function copyLink() {
        const link = document.getElementById('share-link').innerText;
        navigator.clipboard.writeText(link);
        const btn = document.getElementById('copy-btn');
        btn.innerText = 'Copied!';
        setTimeout(() => btn.innerText = 'Copy', 2000);
    }
</script>

</body>
</html>