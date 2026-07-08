@extends('layouts.app')

@section('title', 'My Documents – Onboardify')
@section('page-title', 'My Documents')

@section('content')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">My Documents</h1>
        <p class="text-gray-500 text-sm mt-1">All waivers and forms sent to you — pending and signed.</p>
    </div>

    {{-- Main Content --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Documents Waiting for Signature --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Documents to Sign</h3>
                    <p class="text-gray-400 text-xs mt-0.5">Waivers and forms waiting for your signature</p>
                </div>
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #1a5c3a, #40916c)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </div>
            </div>

            @forelse($pendingWaivers as $send)
                <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4 {{ !$loop->last ? 'mb-3' : '' }} hover:border-[#2D6A4F]/30 hover:bg-[#2D6A4F]/5 transition">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-[#52b788]/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $send->waiver->title ?? 'Untitled Waiver' }}</p>
                            <p class="text-gray-400 text-xs">Sent {{ $send->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <a href="{{ url('/sign/' . $send->token) }}"
                       class="text-white text-xs font-semibold px-4 py-2 rounded-lg shrink-0 ml-3 transition hover:opacity-90"
                       style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        Sign Now
                    </a>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-[#52b788]/10 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-gray-600 font-medium mb-1">All caught up!</p>
                    <p class="text-gray-400 text-sm">No documents waiting for your signature</p>
                </div>
            @endforelse
        </div>

        {{-- Signed Documents History --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="font-bold text-gray-900 text-lg">Signed Documents</h3>
                    <p class="text-gray-400 text-xs mt-0.5">Your completed signatures and submissions</p>
                </div>
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #2D6A4F, #52b788)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
            </div>

            @forelse($signedWaivers as $send)
                <div class="flex items-center justify-between border border-gray-100 rounded-xl p-4 {{ !$loop->last ? 'mb-3' : '' }}">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-10 h-10 rounded-lg bg-[#2D6A4F]/10 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $send->waiver->title ?? 'Untitled Waiver' }}</p>
                            <p class="text-gray-400 text-xs">
                                Signed {{ $send->updated_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <span class="text-[#2D6A4F] bg-[#2D6A4F]/10 text-xs font-semibold px-3 py-1.5 rounded-lg shrink-0 ml-3">
                        Signed
                    </span>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <div class="w-16 h-16 bg-[#2D6A4F]/10 rounded-2xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <p class="text-gray-500 text-sm">No signed documents yet</p>
                </div>
            @endforelse
        </div>

    </div>

@endsection