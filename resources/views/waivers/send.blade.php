@extends('layouts.app')

@section('title', 'Send Waiver')

@section('content')

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('waivers.index') }}" class="hover:text-[#2D6A4F] transition">Documents</a>
                <span>/</span>
                <a href="{{ route('waivers.show', $waiver) }}" class="hover:text-[#2D6A4F] transition">{{ $waiver->title }}</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Send</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">📤 Send Waiver</h1>
            <p class="text-gray-500 text-sm mt-1">Sending: <strong class="text-gray-700">{{ $waiver->title }}</strong></p>
        </div>
        <a href="{{ route('waivers.show', $waiver) }}" 
           class="text-sm font-medium text-gray-600 hover:text-gray-900 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition">
            ← Back to Waiver
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="max-w-3xl space-y-6">

        {{-- ── Section 1: Send via Email ── --}}
        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
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
       class="w-1/3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition">
<input type="email"
       name="emails[]"
       placeholder="email@example.com"
       class="flex-1 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition">
                        <button type="button"
                                onclick="removeRow(this)"
                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center transition text-lg font-bold shrink-0 remove-btn"
                                style="display:none">✕</button>
                    </div>
                </div>

                {{-- Add More --}}
                <button type="button"
                        onclick="addRow()"
                        class="flex items-center gap-2 text-[#2D6A4F] hover:text-[#0B3D2E] text-sm font-medium transition mb-6">
                    <span class="w-5 h-5 rounded-full bg-green-100 flex items-center justify-center text-xs font-bold text-[#2D6A4F]">+</span>
                    Add another recipient
                </button>

                <button type="submit"
                        class="w-full text-white font-semibold py-3 rounded-xl text-sm transition shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2"
                        style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    Send to All Recipients
                </button>
            </form>
        </div>

        {{-- ── Section 2: Shareable Link ── --}}
        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6">
            <h2 class="font-semibold text-gray-900 text-base mb-1">🔗 Shareable Link</h2>
            <p class="text-gray-500 text-xs mb-4">Anyone with this link can fill and sign the waiver</p>

            <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3">
                <span class="text-sm text-gray-600 truncate flex-1" id="share-link">{{ $link }}</span>
                <button onclick="copyLink()"
                        id="copy-btn"
                        class="text-xs font-semibold text-[#2D6A4F] hover:text-[#0B3D2E] shrink-0 transition">
                    Copy
                </button>
            </div>
        </div>

        {{-- ── Section 3: QR Code ── --}}
        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 text-center">
            <h2 class="font-semibold text-gray-900 text-base mb-1">📱 QR Code</h2>
            <p class="text-gray-500 text-xs mb-5">Clients scan this to open and sign the waiver instantly</p>

            <div class="flex justify-center mb-5">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($link) }}"
                     alt="QR Code"
                     class="rounded-xl border border-gray-100 p-2 shadow-sm">
            </div>

            <div class="flex gap-3 justify-center">
                <button onclick="window.print()"
                        class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                    🖨️ Print QR
                </button>
                <a href="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($link) }}"
                   download="waiver-qr-{{ $waiver->slug }}.png"
                   target="_blank"
                   class="px-5 py-2.5 text-white text-sm font-semibold rounded-xl transition shadow-sm hover:shadow-md"
                   style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    ⬇️ Download QR
                </a>
            </div>
        </div>

    </div>

@endsection

@push('scripts')
<script>
    function addRow() {
        const list = document.getElementById('email-list');
        const row = document.createElement('div');
        row.className = 'email-row flex items-center gap-2';
      row.innerHTML = `
    <input type="text" name="names[]" placeholder="Full Name"
        class="w-1/3 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition">
    <input type="email" name="emails[]" placeholder="email@example.com"
        class="flex-1 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-3 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition">
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
@endpush