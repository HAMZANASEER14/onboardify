@extends('layouts.app')

@section('title', 'Bulk Invite – Onboardify')
@section('page-title', 'Bulk Invite')

@section('content')

<div class="h-full flex flex-col w-full">

    {{-- Page Header --}}
    <div class="mb-4 flex-shrink-0">
        <h1 class="text-xl font-bold text-gray-900">Invite Team Members</h1>
        <p class="text-gray-500 text-xs mt-0.5">Send invite emails to new employees or clients.</p>
    </div>

    {{-- Flash Messages (compact, scrollable if long, but never pushes page) --}}
    @if(session('success') || session('error') || session('invalid_emails') || session('duplicate_emails'))
        <div class="mb-4 flex-shrink-0 space-y-2">

            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs px-3 py-2 rounded-lg flex items-start gap-2">
                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 text-xs px-3 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('invalid_emails'))
                <div class="bg-amber-50 border border-amber-200 text-amber-800 text-xs px-3 py-2 rounded-lg">
                    <p class="font-semibold mb-1">⚠️ Invalid emails skipped ({{ count(session('invalid_emails')) }}):</p>
                    <ul class="space-y-0.5 text-[11px] leading-snug">
                        @foreach(session('invalid_emails') as $invalid)
                            <li>• <strong>{{ $invalid['email'] }}</strong> — {{ $invalid['reason'] }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('duplicate_emails'))
                <div class="bg-blue-50 border border-blue-200 text-blue-800 text-xs px-3 py-2 rounded-lg">
                    <p class="font-semibold mb-1">🔄 Duplicate emails skipped ({{ count(session('duplicate_emails')) }}):</p>
                    <ul class="space-y-0.5 text-[11px] leading-snug">
                        @foreach(session('duplicate_emails') as $dup)
                            <li>• <strong>{{ $dup['email'] }}</strong> (row {{ $dup['row'] }})</li>
                        @endforeach
                    </ul>
                </div>
            @endif

        </div>
    @endif

    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex-1 flex flex-col min-h-0">

        {{-- Method Tabs --}}
        <div class="border-b border-gray-100 bg-gray-50 flex-shrink-0">
            <div class="flex">
                <button type="button" onclick="switchMethod('manual')" id="tab-manual"
                    class="flex-1 px-4 py-3 text-sm font-semibold text-center transition-all border-b-2 border-[#2D6A4F] text-[#2D6A4F] bg-white flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Quick Invite
                </button>
                <button type="button" onclick="switchMethod('csv')" id="tab-csv"
                    class="flex-1 px-4 py-3 text-sm font-semibold text-center transition-all border-b-2 border-transparent text-gray-500 hover:text-gray-700 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Bulk Upload (CSV)
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.bulk-invite.process') }}" enctype="multipart/form-data" class="p-5 sm:p-6 flex-1 flex flex-col min-h-0">
            @csrf
            <input type="hidden" name="invite_method" id="invite_method" value="manual">

            {{-- Role Selector --}}
            <div class="mb-4 flex-shrink-0">
                <label for="role" class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Invite Role <span class="text-red-500">*</span>
                </label>
                <select name="role" id="role" class="w-full px-3.5 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all bg-white">
                    <option value="employee">Employee (Team Member)</option>
                    <option value="client">Client (Customer/Partner)</option>
                </select>
            </div>

            {{-- ═══════════════════════════════════════════
                 OPTION 1: Manual Email Input
            ═══════════════════════════════════════════ --}}
            <div id="section-manual" class="flex-1 flex flex-col min-h-0">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Email Addresses <span class="text-red-500">*</span>
                </label>
               <div id="email-tags-box"
         class="w-full flex-1 min-h-[110px] px-3 py-2.5 border border-gray-200 rounded-xl text-sm focus-within:border-[#2D6A4F] focus-within:ring-4 focus-within:ring-[#2D6A4F]/10 transition-all flex flex-wrap gap-2 content-start cursor-text">

        {{-- Tags get injected here by JS --}}

        <input type="text" id="email-tag-input"
               class="flex-1 min-w-[160px] border-0 outline-none text-sm py-1 px-1"
               placeholder="john@example.com, sarah@example.com...">
    </div>

    {{-- This is what actually gets submitted — JS keeps it in sync --}}
    <textarea name="manual_emails" id="manual_emails_hidden" class="hidden"></textarea>

    <p class="text-[11px] text-gray-400 mt-1.5">
        Type an email and press <strong>Enter</strong>, <strong>comma</strong>, or <strong>Tab</strong> to add it.
    </p>
            </div>

            {{-- ═══════════════════════════════════════════
                 OPTION 2: CSV Upload
            ═══════════════════════════════════════════ --}}
            <div id="section-csv" class="hidden flex-1 flex flex-col min-h-0">
                <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                    Upload CSV File <span class="text-red-500">*</span>
                </label>

                <div class="border-2 border-dashed border-gray-200 rounded-xl p-5 text-center hover:border-[#52b788] transition-colors flex-1 flex flex-col items-center justify-center min-h-[110px]">
                    <input
                        type="file"
                        name="csv_file"
                        accept=".csv,.txt"
                        class="hidden"
                        id="csv-input"
                        onchange="updateFileName(this)"
                    >
                    <label for="csv-input" class="cursor-pointer w-full">
                        <svg class="w-9 h-9 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-700">Click to upload CSV</p>
                        <p class="text-[11px] text-gray-400 mt-0.5">CSV or TXT files only (max 2MB)</p>
                        <p id="file-name" class="text-xs text-[#2D6A4F] font-semibold mt-2 hidden"></p>
                    </label>
                </div>

                <div class="mt-2 flex items-center justify-center gap-1.5 flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <a href="data:text/csv;charset=utf-8,email%0Ajohn%40example.com%0Asarah%40example.com"
                       download="sample_invite.csv"
                       class="text-[11px] text-[#2D6A4F] hover:underline font-medium">
                        Download sample CSV
                    </a>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-4 pt-4 border-t border-gray-100 flex-shrink-0 flex items-center justify-between gap-3">
                <p class="text-[11px] text-gray-400 hidden sm:block">
                    Invalid or duplicate emails are filtered out automatically.
                </p>
                <button
                    type="submit"
                    class="w-full sm:w-auto px-6 py-2.5 text-white font-semibold rounded-xl text-sm transition-all duration-200 shadow-md hover:-translate-y-0.5 hover:shadow-lg hover:opacity-90 flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Invites
                </button>
            </div>

        </form>
    </div>

    {{-- Info Strip --}}
    <div class="mt-3 flex-shrink-0 bg-[#52b788]/10 border border-[#2D6A4F]/20 rounded-xl px-4 py-2.5">
        <div class="flex items-center gap-4 flex-wrap text-[11px] text-[#0B3D2E]">
            <span class="font-semibold text-[#2D6A4F]">How it works:</span>
            <span>📨 Sent in the background</span>
            <span>🔗 Unique link per recipient</span>
            <span>✅ Invalid emails filtered</span>
            <span>📬 Confirmation once done</span>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function switchMethod(method) {
        document.getElementById('invite_method').value = method;

        const tabManual = document.getElementById('tab-manual');
        const tabCsv = document.getElementById('tab-csv');

        if (method === 'manual') {
            tabManual.classList.add('border-[#2D6A4F]', 'text-[#2D6A4F]', 'bg-white');
            tabManual.classList.remove('border-transparent', 'text-gray-500');

            tabCsv.classList.add('border-transparent', 'text-gray-500');
            tabCsv.classList.remove('border-[#2D6A4F]', 'text-[#2D6A4F]', 'bg-white');

            document.getElementById('section-manual').classList.remove('hidden');
            document.getElementById('section-csv').classList.add('hidden');
        } else {
            tabCsv.classList.add('border-[#2D6A4F]', 'text-[#2D6A4F]', 'bg-white');
            tabCsv.classList.remove('border-transparent', 'text-gray-500');

            tabManual.classList.add('border-transparent', 'text-gray-500');
            tabManual.classList.remove('border-[#2D6A4F]', 'text-[#2D6A4F]', 'bg-white');

            document.getElementById('section-csv').classList.remove('hidden');
            document.getElementById('section-manual').classList.add('hidden');
        }
    }
    (function () {
    const box       = document.getElementById('email-tags-box');
    const input     = document.getElementById('email-tag-input');
    const hidden    = document.getElementById('manual_emails_hidden');
    let emails      = [];

    function isValidEmail(value) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
    }

    function syncHiddenField() {
        hidden.value = emails.join('\n');
    }

    function renderTags() {
        // Remove existing tag elements (keep the input)
        box.querySelectorAll('.email-tag').forEach(el => el.remove());

        emails.forEach((email, index) => {
            const tag = document.createElement('span');
            tag.className = 'email-tag inline-flex items-center gap-1.5 bg-[#2D6A4F]/10 text-[#2D6A4F] text-xs font-medium pl-3 pr-2 py-1.5 rounded-lg';
            tag.innerHTML = `
                <span>${email}</span>
                <button type="button" class="hover:text-red-500 transition" data-index="${index}">
                    &times;
                </button>
            `;
            box.insertBefore(tag, input);
        });

        syncHiddenField();
    }

   function addEmail(rawValue) {
    const value = rawValue.trim().replace(/[,;]$/, '');
    if (!value) return;

    if (emails.includes(value)) {
        input.value = '';
        return;
    }

    emails.push(value);
    input.value = '';
    renderTags();
}
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ',' || e.key === 'Tab') {
            if (input.value.trim() !== '') {
                e.preventDefault();
                addEmail(input.value);
            }
        }
        if (e.key === 'Backspace' && input.value === '' && emails.length > 0) {
            emails.pop();
            renderTags();
        }
    });

    // Support pasting multiple emails at once
    input.addEventListener('paste', function (e) {
        const pasted = (e.clipboardData || window.clipboardData).getData('text');
        if (pasted.includes(',') || pasted.includes('\n') || pasted.includes(';')) {
            e.preventDefault();
            pasted.split(/[\n,;]+/).forEach(addEmail);
        }
    });

    // Click anywhere in the box focuses the input
    box.addEventListener('click', () => input.focus());

    // Remove a tag when its × is clicked
    box.addEventListener('click', function (e) {
        const btn = e.target.closest('button[data-index]');
        if (!btn) return;
        const idx = parseInt(btn.dataset.index, 10);
        emails.splice(idx, 1);
        renderTags();
    });
})();

    function updateFileName(input) {
        const fileName = input.files[0]?.name;
        const fileNameDisplay = document.getElementById('file-name');

        if (fileName) {
            fileNameDisplay.textContent = '📄 ' + fileName;
            fileNameDisplay.classList.remove('hidden');
        } else {
            fileNameDisplay.classList.add('hidden');
        }
    }
</script>
@endpush