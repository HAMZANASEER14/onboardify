@extends('layouts.app')

@section('title', $waiver->title)

@section('content')

    @php
        $sends      = $waiver->sends;
        $signedSends  = $sends->where('status', 'signed');
        $pendingSends = $sends->where('status', 'pending');
        $totalSent  = $sends->count();
        $totalSigned = $signedSends->count();
        $totalPending = $pendingSends->count();
    @endphp

    {{-- Page Header with Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('waivers.index') }}" class="hover:text-[#2D6A4F] transition">waivers</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ $waiver->title }}</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $waiver->title }}</h1>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('waivers.sendForm', $waiver) }}"
               class="flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm hover:shadow-md"
               style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Send Waiver
            </a>
            <a href="{{ route('waivers.index') }}" class="text-sm font-medium text-gray-600 hover:text-gray-900 px-3 py-2.5 rounded-xl hover:bg-gray-100 transition">
                ← Back
            </a>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="text-3xl font-extrabold text-gray-900 mb-1">{{ $totalSent }}</div>
            <div class="text-xs text-gray-500 font-medium">Total Sent</div>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                <div class="h-1.5 rounded-full" style="width:100%; background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="text-3xl font-extrabold text-[#2D6A4F] mb-1">{{ $totalSigned }}</div>
            <div class="text-xs text-gray-500 font-medium">Signed</div>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                <div class="bg-[#52b788] h-1.5 rounded-full" style="width:{{ $totalSent > 0 ? ($totalSigned/$totalSent)*100 : 0 }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="text-3xl font-extrabold text-[#0B3D2E] mb-1">{{ $totalPending }}</div>
            <div class="text-xs text-gray-500 font-medium">Pending</div>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                <div class="bg-[#0B3D2E] h-1.5 rounded-full" style="width:{{ $totalSent > 0 ? ($totalPending/$totalSent)*100 : 0 }}%"></div>
            </div>
        </div>
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="text-3xl font-extrabold text-[#40916c] mb-1">{{ count($waiver->fields ?? []) }}</div>
            <div class="text-xs text-gray-500 font-medium">Form Fields</div>
            <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                <div class="bg-[#40916c] h-1.5 rounded-full" style="width:100%"></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Waiver Info --}}
        <div class="space-y-5">

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Waiver Details</h2>
                <div class="space-y-4">
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Title</div>
                        <div class="font-bold text-gray-900 text-base">{{ $waiver->title }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Status</div>
                        @if($totalSent === 0)
                            <span class="inline-flex items-center gap-1.5 bg-gray-100 text-gray-600 text-xs font-semibold px-3 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Draft
                            </span>
                        @elseif($totalSigned > 0)
                            <span class="inline-flex items-center gap-1.5 bg-[#52b788]/10 text-[#52b788] text-xs font-semibold px-3 py-1 rounded-full border border-[#52b788]/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#52b788]"></span> Signed
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 bg-[#0B3D2E]/10 text-[#0B3D2E] text-xs font-semibold px-3 py-1 rounded-full border border-[#0B3D2E]/30">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#0B3D2E]"></span> Pending
                            </span>
                        @endif
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Requires Signature</div>
                        <div class="text-sm text-gray-700">{{ $waiver->require_signature ? 'Yes' : 'No' }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Created</div>
                        <div class="text-sm text-gray-700">{{ $waiver->created_at->format('d M Y, h:i A') }}</div>
                    </div>
                    <div>
                        <div class="text-xs text-gray-400 mb-1">Last Updated</div>
                        <div class="text-sm text-gray-700">{{ $waiver->updated_at->diffForHumans() }}</div>
                    </div>
                </div>

                <div class="mt-5 pt-5 border-t border-gray-100 space-y-2">
                    <a href="{{ route('waivers.sendForm', $waiver) }}"
                       class="w-full flex items-center justify-center gap-2 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm hover:shadow-md"
                       style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Send to Client
                    </a>
                    <a href="{{ route('waivers.edit', $waiver) }}"
                       class="w-full flex items-center justify-center gap-2 text-gray-700 text-sm font-semibold px-4 py-2.5 rounded-xl border border-gray-200 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        Edit Waiver
                    </a>
                </div>
            </div>

            {{-- Form Fields --}}
            @if($waiver->fields && count($waiver->fields) > 0)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <h2 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-4">Form Fields ({{ count($waiver->fields) }})</h2>
                <div class="space-y-2">
                    @foreach($waiver->fields as $field)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/></svg>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-gray-800">{{ $field['label'] ?? 'Field' }}</div>
                                <div class="text-[10px] text-gray-400">{{ $field['type'] ?? 'text' }}</div>
                            </div>
                        </div>
                        @if(!empty($field['required']))
                            <span class="text-[10px] bg-red-50 text-red-500 font-semibold px-2 py-0.5 rounded-full border border-red-100">Required</span>
                        @else
                            <span class="text-[10px] bg-gray-100 text-gray-400 font-semibold px-2 py-0.5 rounded-full">Optional</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

        {{-- Right: Submissions --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Signed --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-bold text-gray-900">Signed Submissions</h2>
                        <p class="text-gray-400 text-xs mt-0.5">Clients who completed & signed this waiver</p>
                    </div>
                    <span class="bg-[#52b788]/10 text-[#52b788] text-xs font-semibold px-3 py-1 rounded-full border border-[#52b788]/30">
                        {{ $totalSigned }} signed
                    </span>
                </div>

                @if($signedSends->isEmpty())
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                        </div>
                        <p class="text-gray-400 text-sm">No signed submissions yet</p>
                    </div>
                @else
                    <div class="space-y-2">
                        @foreach($signedSends as $send)
                        <div class="flex items-center justify-between p-3.5 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                     style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                    {{ strtoupper(substr($send->client_name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-gray-800">{{ $send->client_name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-400">{{ $send->client_email ?? '' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0 ml-3">
                                @if($send->signed_at)
                                    <span class="text-xs text-gray-400 hidden sm:block">
                                        Signed {{ \Carbon\Carbon::parse($send->signed_at)->format('M d, Y') }}
                                    </span>
                                @endif
                                <span class="inline-flex items-center gap-1 bg-[#52b788]/10 text-[#52b788] text-xs font-semibold px-2.5 py-1 rounded-full border border-[#52b788]/30">
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#52b788]"></span> Signed
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Pending --}}
            @if($pendingSends->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <div>
                        <h2 class="font-bold text-gray-900">Awaiting Signature</h2>
                        <p class="text-gray-400 text-xs mt-0.5">Clients who haven't signed yet</p>
                    </div>
                    <span class="bg-[#0B3D2E]/10 text-[#0B3D2E] text-xs font-semibold px-3 py-1 rounded-full border border-[#0B3D2E]/30">
                        {{ $totalPending }} pending
                    </span>
                </div>
                <div class="space-y-2">
                    @foreach($pendingSends as $send)
                    <div class="flex items-center justify-between p-3.5 bg-[#0B3D2E]/5 rounded-xl border border-[#0B3D2E]/20">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0 bg-[#0B3D2E]">
                                {{ strtoupper(substr($send->client_name ?? '?', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-semibold text-gray-800">{{ $send->client_name ?? 'Unknown' }}</div>
                                <div class="text-xs text-gray-400">{{ $send->client_email ?? '' }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 ml-3">
                            <span class="text-xs text-gray-400">Sent {{ $send->created_at->format('M d') }}</span>
                            <span class="inline-flex items-center gap-1 bg-[#0B3D2E]/10 text-[#0B3D2E] text-xs font-semibold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-[#0B3D2E]"></span> Pending
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </div>

@endsection