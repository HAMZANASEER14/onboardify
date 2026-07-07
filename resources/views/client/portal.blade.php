@extends('layouts.app')

@section('title', 'Client Portal – Onboardify')
@section('page-title', 'Client Portal')

@section('content')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            {{ session('success') }}
        </div>
    @endif

    @php
        $user = auth()->user();
        $team = $user->team;
    @endphp

    {{-- Welcome Card --}}
    <div class="rounded-2xl p-6 text-white shadow-lg mb-6" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-1">Welcome, {{ optional($user->profile)->first_name ?? $user->name }}!</h2>
                <p class="text-white/60 text-sm">Client Portal • {{ $team->name ?? 'Your Company' }}</p>
            </div>
            <div class="w-14 h-14 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-xl">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
        </div>
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

            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-[#52b788]/10 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <p class="text-gray-600 font-medium mb-1">All caught up!</p>
                <p class="text-gray-400 text-sm">No documents waiting for your signature</p>
            </div>
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

            <div class="flex flex-col items-center justify-center py-12 text-center">
                <div class="w-16 h-16 bg-[#2D6A4F]/10 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-gray-500 text-sm">No signed documents yet</p>
            </div>
        </div>

    </div>

    {{-- Quick Info Card --}}
    <div class="mt-6 bg-[#52b788]/10 border border-[#2D6A4F]/20 rounded-2xl p-6">
        <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 mb-1">Need Help?</h3>
                <p class="text-gray-600 text-sm">If you have questions about any documents or need assistance, use the chat feature in the sidebar to message your admin directly.</p>
            </div>
        </div>
    </div>

@endsection