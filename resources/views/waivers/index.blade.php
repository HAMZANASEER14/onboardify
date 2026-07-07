@extends('layouts.app')

@section('title', 'waivers')

@section('content')

    {{-- Page Title --}}
    <div class="mb-4 sm:mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900">My Waivers</h2>
        <p class="text-gray-500 text-xs sm:text-sm mt-1">Manage and send waivers to your clients</p>
    </div>

    @if($waivers->isEmpty())

        {{-- Empty State --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-10 md:p-16 text-center">
            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl flex items-center justify-center mx-auto mb-4 bg-[#2D6A4F]/10">
                <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">No waivers yet</h3>
            <p class="text-gray-400 text-xs sm:text-sm mb-6">Create your first waiver and send it to clients</p>
            <a href="{{ route('waivers.create') }}"
               class="inline-flex items-center gap-2 text-white font-semibold px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm transition shadow-sm"
               style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Create First Waiver
            </a>
        </div>

    @else

        {{-- Stats --}}
        @php
            $total   = $waivers->total();
        $sent    = \App\Models\WaiverSend::where('sent_by', auth()->id())->whereNotNull('client_id')->count();
$signed  = \App\Models\WaiverSend::where('sent_by', auth()->id())->whereNotNull('client_id')->where('status','signed')->count();
$pending = \App\Models\WaiverSend::where('sent_by', auth()->id())->whereNotNull('client_id')->where('status','pending')->count();
@endphp
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4 mb-6">
            {{-- Total Waivers --}}
            <div class="rounded-2xl p-4 text-white shadow-md relative overflow-hidden min-w-0" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wide">Total Waivers</p>
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $total }}</div>
                <div class="text-white/50 text-[11px] mt-1">All waivers</div>
            </div>

            {{-- Times Sent --}}
            <div class="rounded-2xl p-4 text-white shadow-md relative overflow-hidden min-w-0" style="background: linear-gradient(135deg, #1a5c3a, #40916c);">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wide">Times Sent</p>
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" style="transform:rotate(90deg)" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $sent }}</div>
                <div class="text-white/50 text-[11px] mt-1">Total dispatched</div>
            </div>

            {{-- Signed --}}
            <div class="rounded-2xl p-4 text-white shadow-md relative overflow-hidden min-w-0" style="background: linear-gradient(135deg, #2D6A4F, #52b788);">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wide">Signed</p>
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $signed }}</div>
                <div class="text-white/50 text-[11px] mt-1">Successfully signed</div>
            </div>

            {{-- Pending --}}
            <div class="rounded-2xl p-4 text-white shadow-md relative overflow-hidden min-w-0" style="background: linear-gradient(135deg, #0B3D2E, #40916c);">
                <div class="flex items-start justify-between mb-3">
                    <p class="text-[11px] font-semibold text-white/70 uppercase tracking-wide">Pending</p>
                    <div class="w-8 h-8 rounded-xl bg-white/20 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <div class="text-2xl sm:text-3xl font-extrabold text-white">{{ $pending }}</div>
                <div class="text-white/50 text-[11px] mt-1">Awaiting signature</div>
            </div>
        </div>

        {{-- Search & Filter --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-3 sm:p-4 border-b border-gray-100 flex flex-col md:flex-row gap-3">
                <div class="relative flex-1">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="search-input" placeholder="Search waivers..."
                        class="w-full pl-9 pr-4 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition">
                </div>
                <select id="status-filter"
                    class="w-full md:w-36 px-3 py-2 text-sm bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-[#2D6A4F] transition cursor-pointer">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="signed">Signed</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            {{-- Desktop Table --}}
            <div class="overflow-x-auto hidden lg:block">
                <table class="w-full text-sm min-w-[760px]" id="waivers-table">
                    <thead>
                        <tr class="border-b border-gray-100 bg-gray-50/80">
                            <th class="text-left px-4 lg:px-6 py-3 lg:py-3.5 text-gray-500 font-semibold text-[10px] sm:text-xs uppercase tracking-wide">Title</th>
                            <th class="text-left px-4 lg:px-6 py-3 lg:py-3.5 text-gray-500 font-semibold text-[10px] sm:text-xs uppercase tracking-wide">Fields</th>
                            <th class="text-left px-4 lg:px-6 py-3 lg:py-3.5 text-gray-500 font-semibold text-[10px] sm:text-xs uppercase tracking-wide">Last Sent</th>
                            <th class="text-left px-4 lg:px-6 py-3 lg:py-3.5 text-gray-500 font-semibold text-[10px] sm:text-xs uppercase tracking-wide">Status</th>
                            <th class="text-left px-4 lg:px-6 py-3 lg:py-3.5 text-gray-500 font-semibold text-[10px] sm:text-xs uppercase tracking-wide">Created</th>
                            <th class="text-right px-4 lg:px-6 py-3 lg:py-3.5 text-gray-500 font-semibold text-[10px] sm:text-xs uppercase tracking-wide">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50" id="waivers-tbody">
                        @foreach($waivers as $waiver)
                       @php
    $sendCount    = $waiver->sends_count;
    $signedCount  = $waiver->signed_count;
    $pendingCount = $waiver->pending_count;
    $lastSend     = $waiver->sends->first();
    $waiverStatus = $sendCount === 0 ? 'draft' : ($signedCount > 0 ? 'signed' : 'pending');
@endphp
                        <tr class="hover:bg-gray-50 transition waiver-row"
                            data-title="{{ strtolower($waiver->title) }}"
                            data-status="{{ $waiverStatus }}">

                            <td class="px-4 lg:px-6 py-3 lg:py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $waiver->title }}</div>
                                        <div class="text-gray-400 text-[11px] sm:text-xs mt-0.5">Created {{ $waiver->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-4 lg:px-6 py-3 lg:py-4">
                                <span class="text-[10px] sm:text-xs font-semibold px-2.5 py-1 rounded-full bg-[#2D6A4F]/10 text-[#2D6A4F]">
                                    {{ count($waiver->fields ?? []) }} fields
                                </span>
                            </td>

                            <td class="px-4 lg:px-6 py-3 lg:py-4">
                                @if($lastSend)
                                    <div class="text-gray-700 text-[11px] sm:text-xs font-medium">{{ $sendCount }}x sent</div>
                                    <div class="text-gray-400 text-[11px] sm:text-xs">{{ $lastSend->created_at->format('M d') }}</div>
                                @else
                                    <span class="text-gray-400 text-[11px] sm:text-xs">Not sent</span>
                                @endif
                            </td>

                            <td class="px-4 lg:px-6 py-3 lg:py-4">
                                @if($waiverStatus === 'draft')
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 text-[10px] sm:text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                                    </span>
                                @elseif($waiverStatus === 'pending')
                                    <span class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold px-2.5 py-1 rounded-full border bg-[#0B3D2E]/10 text-[#0B3D2E] border-[#0B3D2E]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#0B3D2E]"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs font-semibold px-2.5 py-1 rounded-full border bg-[#52b788]/10 text-[#52b788] border-[#52b788]/30">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#52b788]"></span> Signed
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 lg:px-6 py-3 lg:py-4 text-gray-500 text-[11px] sm:text-xs">
                                {{ $waiver->created_at->format('M d, Y') }}
                            </td>

                            <td class="px-4 lg:px-6 py-3 lg:py-4">
                                <div class="flex items-center justify-end gap-1 sm:gap-1.5">
                                    @if($waiverStatus !== 'signed')
                                        <a href="{{ route('waivers.sendForm', $waiver) }}"
                                           class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-lg text-white transition shadow-sm"
                                           style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                                           title="Send">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                            </svg>
                                        </a>
                                    @else
                                        <a href="{{ route('waivers.show', $waiver) }}"
                                           class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 rounded-lg transition border bg-[#52b788]/10 hover:bg-[#52b788]/20 border-[#52b788]/30 text-[#52b788]"
                                           title="View">
                                            <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    @endif
                                    <a href="{{ route('waivers.edit', $waiver) }}"
                                       class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-600 rounded-lg transition"
                                       title="Edit">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                    </a>
                                    <button onclick="confirmDelete(event, '{{ $waiver->title }}', '{{ route('waivers.destroy', $waiver) }}')"
                                            class="inline-flex items-center justify-center w-7 h-7 sm:w-8 sm:h-8 bg-red-50 hover:bg-red-100 border border-red-200 text-red-500 rounded-lg transition"
                                            title="Delete">
                                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Mobile/Tablet Cards --}}
            <div class="lg:hidden divide-y divide-gray-100" id="waivers-cards">
                @foreach($waivers as $waiver)
               @php
    $sendCount    = $waiver->sends_count;
    $signedCount  = $waiver->signed_count;
    $lastSend     = $waiver->sends->first();
    $waiverStatus = $sendCount === 0 ? 'draft' : ($signedCount > 0 ? 'signed' : 'pending');
@endphp
                <div class="p-3 sm:p-4 waiver-card" data-title="{{ strtolower($waiver->title) }}" data-status="{{ $waiverStatus }}">
                    <div class="flex items-start justify-between mb-3 gap-2">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <div class="font-semibold text-gray-900 text-sm truncate">{{ $waiver->title }}</div>
                                <div class="text-gray-400 text-[11px] sm:text-xs">{{ $waiver->created_at->format('M d, Y') }}</div>
                            </div>
                        </div>
                        @if($waiverStatus === 'draft')
                            <span class="bg-gray-100 text-gray-600 text-[9px] sm:text-[10px] font-semibold px-2 py-0.5 rounded-full shrink-0 whitespace-nowrap">Draft</span>
                        @elseif($waiverStatus === 'pending')
                            <span class="text-[9px] sm:text-[10px] font-semibold px-2 py-0.5 rounded-full border shrink-0 whitespace-nowrap bg-[#0B3D2E]/10 text-[#0B3D2E] border-[#0B3D2E]/30">Pending</span>
                        @else
                            <span class="text-[9px] sm:text-[10px] font-semibold px-2 py-0.5 rounded-full border shrink-0 whitespace-nowrap bg-[#52b788]/10 text-[#52b788] border-[#52b788]/30">Signed</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-[9px] sm:text-[10px] font-semibold px-2 py-0.5 rounded-full bg-[#2D6A4F]/10 text-[#2D6A4F]">{{ count($waiver->fields ?? []) }} fields</span>
                        @if($lastSend)
                            <span class="text-gray-400 text-[9px] sm:text-[10px]">{{ $sendCount }}x sent</span>
                        @endif
                    </div>
                    <div class="flex gap-1.5 sm:gap-2">
                        @if($waiverStatus !== 'signed')
                            <a href="{{ route('waivers.sendForm', $waiver) }}"
                               class="flex-1 text-center text-white text-[10px] sm:text-xs font-semibold py-1.5 sm:py-2 rounded-lg transition"
                               style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">Send</a>
                        @else
                            <a href="{{ route('waivers.show', $waiver) }}"
                               class="flex-1 text-center text-white text-[10px] sm:text-xs font-semibold py-1.5 sm:py-2 rounded-lg transition bg-[#52b788] hover:bg-[#2D6A4F]">View</a>
                        @endif
                        <a href="{{ route('waivers.edit', $waiver) }}"
                           class="flex-1 text-center bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-600 text-[10px] sm:text-xs font-semibold py-1.5 sm:py-2 rounded-lg transition">Edit</a>
                        <button onclick="confirmDelete(event, '{{ $waiver->title }}', '{{ route('waivers.destroy', $waiver) }}')"
                                class="flex-1 text-center bg-red-50 hover:bg-red-100 border border-red-200 text-red-500 text-[10px] sm:text-xs font-semibold py-1.5 sm:py-2 rounded-lg transition">Delete</button>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="px-3 sm:px-6 py-4 border-t border-gray-100">
                {{ $waivers->links() }}
            </div>
        </div>

    @endif

    {{-- Delete Modal (Page Specific) --}}
    <div id="delete-modal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-5 sm:p-6 max-w-sm w-full shadow-2xl">
            <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
            </div>
            <h3 class="text-center font-bold text-gray-900 text-base sm:text-lg mb-1">Delete Waiver</h3>
            <p class="text-center text-gray-500 text-xs sm:text-sm mb-6">Are you sure you want to delete <strong id="delete-title" class="text-gray-900"></strong>? This cannot be undone.</p>
            <div class="flex gap-3">
                <button onclick="closeDeleteModal()" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2.5 rounded-xl text-xs sm:text-sm transition">Cancel</button>
                <button id="confirm-delete-btn" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl text-xs sm:text-sm transition">Delete</button>
            </div>
        </div>
    </div>

@endsection

{{-- Page-Specific JavaScript --}}
@push('scripts')
<script>
    // Search & Filter Logic
    const searchInput = document.getElementById('search-input');
    const statusFilter = document.getElementById('status-filter');

    function filterWaivers() {
        if (!searchInput || !statusFilter) return;
        const query = searchInput.value.toLowerCase();
        const status = statusFilter.value;

        document.querySelectorAll('.waiver-row').forEach(row => {
            const match = row.dataset.title.includes(query) && (status === 'all' || row.dataset.status === status);
            row.style.display = match ? '' : 'none';
        });
        document.querySelectorAll('.waiver-card').forEach(card => {
            const match = card.dataset.title.includes(query) && (status === 'all' || card.dataset.status === status);
            card.style.display = match ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterWaivers);
    if (statusFilter) statusFilter.addEventListener('change', filterWaivers);

    // Delete Modal Logic
    let deleteUrl = null;

    function confirmDelete(e, title, url) {
        e.preventDefault();
        deleteUrl = url;
        document.getElementById('delete-title').textContent = title;
        document.getElementById('delete-modal').classList.remove('hidden');
        document.getElementById('delete-modal').classList.add('flex');
    }

    function closeDeleteModal() {
        deleteUrl = null;
        document.getElementById('delete-modal').classList.add('hidden');
        document.getElementById('delete-modal').classList.remove('flex');
    }

    const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function() {
            if (deleteUrl) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = deleteUrl;
                form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    const deleteModal = document.getElementById('delete-modal');
    if (deleteModal) {
        deleteModal.addEventListener('click', function(e) {
            if (e.target === this) closeDeleteModal();
        });
    }
</script>
@endpush