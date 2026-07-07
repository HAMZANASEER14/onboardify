@extends('layouts.app')

@section('title', 'Add Client')

@section('content')

    {{-- Page-Specific Styles --}}
    <style>
        input:focus, select:focus {
            outline: none;
            border-color: #2D6A4F;
            box-shadow: 0 0 0 3px rgba(45,106,79,0.1);
        }
    </style>

    {{-- Page Header with Breadcrumb --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Client</h1>
            <p class="text-gray-500 text-sm mt-1">Fill in the details below to add a client to your account.</p>
        </div>
        <a href="{{ route('clients.index') }}"
           class="text-sm font-medium text-gray-600 hover:text-gray-900 px-4 py-2 rounded-xl hover:bg-gray-100 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Clients
        </a>
    </div>

    <div class="max-w-2xl">

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-2 rounded-xl mb-6">
                <div class="flex items-center gap-2 mb-1 font-semibold">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Please fix the following errors:
                </div>
                @foreach($errors->all() as $error)
                    <p class="ml-6 text-xs">• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Form Card --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

            {{-- Card Header --}}
            <div class="px-6 py-2 border-b border-gray-100 flex items-center gap-3"
                 style="background: linear-gradient(135deg, rgba(45,106,79,0.05), rgba(82,183,136,0.05))">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0"
                     style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <div class="font-bold text-gray-900 text-sm">Client Information</div>
                    <div class="text-gray-400 text-xs">Basic contact details for this client</div>
                </div>
            </div>

            {{-- Form Body --}}
            <form action="{{ route('clients.store') }}" method="POST" class="p-4 space-y-3">
                @csrf

                {{-- Full Name --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                      <input type="text" name="name"
       value="{{ old('name') }}"
       placeholder="John Doe"
       class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl transition">
                    </div>
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Email Address <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                      <input type="email" name="email"
       value="{{ old('email') }}"
       placeholder="john@example.com"
       class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl transition">
                </div>
</div>  
                {{-- Phone --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">
                        Phone <span class="text-gray-400 text-xs font-normal">(optional)</span>
                    </label>
                    <div class="relative">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <input type="text" name="phone"
                               value="{{ old('phone') }}"
                               placeholder="+1 234 567 8900"
                               class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl transition">
                    </div>
                </div>

               

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 flex items-center justify-center gap-2 text-white text-sm font-semibold py-2 rounded-xl transition shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0"
                            style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                        Add Client
                    </button>
                    <a href="{{ route('clients.index') }}"
                       class="px-5 py-2 rounded-xl text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition">
                        Cancel
                    </a>
                </div>

            </form>
        </div>

    </div>

@endsection