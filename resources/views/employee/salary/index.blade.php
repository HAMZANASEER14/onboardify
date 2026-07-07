@extends('layouts.app')

@section('title', 'My Salary – Onboardify')
@section('page-title', 'My Salary Slips')

@section('header-actions')
    <span class="text-sm text-gray-600">
        {{ $salarySlips->count() }} slip{{ $salarySlips->count() !== 1 ? 's' : '' }} available
    </span>
@endsection

@section('content')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Info Banner --}}
    <div class="bg-[#52b788]/10 border border-[#52b788]/20 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-lg bg-[#52b788]/20 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <h3 class="font-semibold text-[#0B3D2E] text-sm">About Salary Slips</h3>
                <p class="text-[#2D6A4F] text-xs mt-1">Your admin uploads salary slips as PDF files. You can view and download them here.</p>
            </div>
        </div>
    </div>

    {{-- Salary Slips Grid --}}
    @if($salarySlips->isEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <div class="w-20 h-20 bg-[#52b788]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
            </div>
            <h3 class="text-gray-900 font-semibold mb-2">No Salary Slips Yet</h3>
            <p class="text-gray-500 text-sm mb-4">Your admin hasn't uploaded any salary slips for you yet.</p>
            <p class="text-gray-400 text-xs">Contact your admin if you have questions about your salary.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($salarySlips as $slip)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    {{-- Header --}}
                    <div class="p-4 text-white" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
                        <div class="flex items-center justify-between mb-2">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <span class="text-xs font-semibold bg-white/20 px-2 py-1 rounded-full">
                                PDF
                            </span>
                        </div>
                        <h3 class="text-lg font-bold">{{ $slip->month }}</h3>
                        <p class="text-white/70 text-xs mt-1">Salary Slip</p>
                    </div>

                    {{-- Body --}}
                    <div class="p-4">
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Uploaded</span>
                                <span class="text-xs font-medium text-gray-700">
                                    {{ $slip->created_at->format('M d, Y') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Time</span>
                                <span class="text-xs font-medium text-gray-700">
                                    {{ $slip->created_at->format('h:i A') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-xs text-gray-500">Status</span>
                                <span class="inline-flex items-center gap-1 text-xs font-medium text-[#2D6A4F]">
                                    <span class="w-2 h-2 bg-[#2D6A4F] rounded-full"></span>
                                    Available
                                </span>
                            </div>
                        </div>

                        {{-- Download Button --}}
                        <a href="{{ route('employee.salary.download', $slip) }}" 
                           class="mt-4 w-full flex items-center justify-center gap-2 text-white font-semibold py-2.5 rounded-lg transition text-sm hover:opacity-90"
                           style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download PDF
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection