@extends('layouts.app')

@section('title', 'Bulk Invite – Onboardify')
@section('page-title', 'Bulk Invite')

@section('content')

<div class="max-w-3xl mx-auto">

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Invite Team Members</h1>
        <p class="text-gray-500 text-sm mt-1">Send invite emails to new employees or clients.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-4 flex items-start gap-3">
            <svg class="w-5 h-5 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 text-sm px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- Invalid Emails Warning --}}
    @if(session('invalid_emails'))
        <div class="bg-amber-50 border border-amber-200 text-amber-800 text-sm px-4 py-3 rounded-xl mb-4">
            <p class="font-semibold mb-2">⚠️ Invalid emails skipped:</p>
            <ul class="space-y-1 text-xs">
                @foreach(session('invalid_emails') as $invalid)
                    <li>• <strong>{{ $invalid['email'] }}</strong> — {{ $invalid['reason'] }}</li>
                @endforeach
            </ul>
        </div>
    @endif
@if(session('duplicate_emails'))
    <div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm px-4 py-3 rounded-xl mb-4">
        <p class="font-semibold mb-2">🔄 Duplicate emails skipped:</p>
        <ul class="space-y-1 text-xs">
            @foreach(session('duplicate_emails') as $dup)
                <li>• <strong>{{ $dup['email'] }}</strong> (row {{ $dup['row'] }})</li>
            @endforeach
        </ul>
    </div>
@endif
    {{-- Main Card --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Method Tabs --}}
        <div class="border-b border-gray-100 bg-gray-50">
            <div class="flex">
                <button type="button" onclick="switchMethod('manual')" id="tab-manual"
                    class="flex-1 px-6 py-4 text-sm font-semibold text-center transition-all border-b-2 border-[#2D6A4F] text-[#2D6A4F] bg-white">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Quick Invite (1-2 emails)
                </button>
                <button type="button" onclick="switchMethod('csv')" id="tab-csv"
                    class="flex-1 px-6 py-4 text-sm font-semibold text-center transition-all border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                    <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Bulk Upload (CSV)
                </button>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('admin.bulk-invite.process') }}" enctype="multipart/form-data" class="p-6 sm:p-8">
            @csrf

            {{-- Hidden field to track method --}}
            <input type="hidden" name="invite_method" id="invite_method" value="manual">
  <div class="mb-6">
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-2">
                    Invite Role <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-3">
                    Select the role for the people you are inviting.
                </p>
                <select name="role" id="role" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all bg-white">
                    <option value="employee">Employee (Team Member)</option>
                    <option value="client">Client (Customer/Partner)</option>
                </select>
            </div>
            {{-- ═══════════════════════════════════════════
                 OPTION 1: Manual Email Input
            ═══════════════════════════════════════════ --}}
            <div id="section-manual">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Email Addresses <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-3">
                    Enter one email per line, or separate with commas/semicolons.
                </p>
                <textarea
                    name="manual_emails"
                    rows="6"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm placeholder-gray-400 focus:outline-none focus:border-[#2D6A4F] focus:ring-4 focus:ring-[#2D6A4F]/10 transition-all resize-none font-mono"
                    placeholder="john@example.com&#10;sarah@example.com&#10;mike@example.com"
                ></textarea>
                <p class="text-xs text-gray-400 mt-2">
                    💡 Tip: You can paste multiple emails at once. We'll handle the rest!
                </p>
            </div>

            {{-- ═══════════════════════════════════════════
                 OPTION 2: CSV Upload
            ═══════════════════════════════════════════ --}}
            <div id="section-csv" class="hidden">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Upload CSV File <span class="text-red-500">*</span>
                </label>
                <p class="text-xs text-gray-500 mb-3">
                    Upload a CSV file with email addresses (one per row). First row can be a header.
                </p>
                
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-[#52b788] transition-colors">
                    <input
                        type="file"
                        name="csv_file"
                        accept=".csv,.txt"
                        class="hidden"
                        id="csv-input"
                        onchange="updateFileName(this)"
                    >
                    <label for="csv-input" class="cursor-pointer">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm font-medium text-gray-700 mb-1">Click to upload CSV</p>
                        <p class="text-xs text-gray-400">CSV or TXT files only (max 2MB)</p>
                        <p id="file-name" class="text-xs text-[#2D6A4F] font-semibold mt-2 hidden"></p>
                    </label>
                </div>

                {{-- Download Sample CSV --}}
                <div class="mt-4 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <a href="data:text/csv;charset=utf-8,email%0Ajohn%40example.com%0Asarah%40example.com" 
                       download="sample_invite.csv"
                       class="text-xs text-[#2D6A4F] hover:underline font-medium">
                        Download sample CSV
                    </a>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="mt-6 pt-6 border-t border-gray-100">
                <button
                    type="submit"
                    class="w-full sm:w-auto px-8 py-3 text-white font-semibold rounded-xl text-sm transition-all duration-200 shadow-lg hover:-translate-y-0.5 hover:shadow-xl hover:opacity-90"
                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                >
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Send Invites
                </button>
            </div>

        </form>
    </div>

    {{-- Info Card --}}
    <div class="mt-6 bg-[#52b788]/10 border border-[#2D6A4F]/20 rounded-xl p-4">
        <div class="flex gap-3">
            <svg class="w-5 h-5 text-[#2D6A4F] flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-xs text-[#0B3D2E]">
                <p class="font-semibold mb-1">How it works:</p>
                <ul class="space-y-1 text-[#2D6A4F]">
                    <li>• Invites are sent in the background (you won't have to wait)</li>
                    <li>• Each recipient gets a unique link to join your team</li>
                    <li>• Invalid emails are automatically filtered out</li>
                    <li>• You'll receive a confirmation once all invites are processed</li>
                </ul>
            </div>
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
    function switchMethod(method) {
        // Update hidden input
        document.getElementById('invite_method').value = method;
        
        // Update tabs
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