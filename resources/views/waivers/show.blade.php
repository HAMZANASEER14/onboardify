<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $waiver->title }} – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-gradient { background: linear-gradient(160deg, #0f4c75 0%, #1b6ca8 50%, #0a9396 100%); }
        .nav-active { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-item { color: rgba(255,255,255,0.65); transition: all 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex font-sans antialiased">

<div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

<aside id="sidebar" class="sidebar-gradient fixed inset-y-0 left-0 z-50 w-60 transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex flex-col shadow-2xl">
    <div class="px-5 py-5 flex items-center justify-between">
        <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="font-bold text-white text-base tracking-tight">Onboardify</span>
        </div>
        <button id="close-sidebar" class="md:hidden text-white/60 hover:text-white">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <div class="mx-4 mb-4 bg-white/10 backdrop-blur rounded-2xl p-4 border border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-full bg-white/20 border-2 border-white/30 flex items-center justify-center text-white font-bold text-base shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <div class="text-white font-semibold text-sm truncate">{{ optional(auth()->user()->profile)->first_name ?? auth()->user()->name }}</div>
                <div class="text-white/50 text-[11px] truncate">WELCOME TO DASHBOARD</div>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-3 space-y-0.5 overflow-y-auto">
        <a href="{{ route('dashboard') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <a href="{{ route('waivers.index') }}" class="nav-active flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Documents
        </a>
        <a href="{{ route('clients.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Clients
        </a>
        <a href="{{ route('templates.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
            Templates
        </a>
        <a href="{{ route('submissions.index') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            Signatures
        </a>
        <div class="border-t border-white/10 my-3"></div>
        <a href="{{ route('chats') }}" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
            Messages
        </a>
        <a href="/profile" class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Settings
        </a>
    </nav>

    <div class="p-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium nav-item">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<main class="flex-1 md:ml-60 min-h-screen flex flex-col">

    <header class="bg-white border-b border-gray-200 px-4 md:px-8 h-14 flex items-center justify-between sticky top-0 z-30 shadow-sm">
        <button id="mobile-menu-btn" class="md:hidden text-gray-500 hover:text-gray-900 p-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="text-sm font-semibold text-gray-500 hidden md:block">
            Home /
            <a href="{{ route('waivers.index') }}" class="hover:text-blue-600 transition">Documents</a> /
            <span class="text-gray-900">{{ $waiver->title }}</span>
        </h1>
        <div class="flex items-center gap-3 ml-auto">
            <a href="{{ route('waivers.sendForm', $waiver) }}"
               class="flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm"
               style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                Send Waiver
            </a>
            <a href="{{ route('waivers.index') }}" class="text-sm font-medium text-gray-500 hover:text-gray-800 px-3 py-2 rounded-lg hover:bg-gray-100 transition">
                ← Back
            </a>
        </div>
    </header>

    <div class="flex-1 p-4 md:p-8">

        @if(session('success'))
            <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @php
            $sends      = $waiver->sends;
            $signedSends  = $sends->where('status', 'signed');
            $pendingSends = $sends->where('status', 'pending');
            $totalSent  = $sends->count();
            $totalSigned = $signedSends->count();
            $totalPending = $pendingSends->count();
        @endphp

        {{-- Stats --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="text-3xl font-extrabold text-gray-900 mb-1">{{ $totalSent }}</div>
                <div class="text-xs text-gray-500 font-medium">Total Sent</div>
                <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                    <div class="h-1.5 rounded-full" style="width:100%; background: linear-gradient(135deg, #1b6ca8, #0a9396)"></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="text-3xl font-extrabold text-teal-600 mb-1">{{ $totalSigned }}</div>
                <div class="text-xs text-gray-500 font-medium">Signed</div>
                <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-teal-500 h-1.5 rounded-full" style="width:{{ $totalSent > 0 ? ($totalSigned/$totalSent)*100 : 0 }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="text-3xl font-extrabold text-orange-500 mb-1">{{ $totalPending }}</div>
                <div class="text-xs text-gray-500 font-medium">Pending</div>
                <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-orange-400 h-1.5 rounded-full" style="width:{{ $totalSent > 0 ? ($totalPending/$totalSent)*100 : 0 }}%"></div>
                </div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
                <div class="text-3xl font-extrabold text-blue-600 mb-1">{{ count($waiver->fields ?? []) }}</div>
                <div class="text-xs text-gray-500 font-medium">Form Fields</div>
                <div class="mt-3 w-full bg-gray-100 rounded-full h-1.5">
                    <div class="bg-blue-400 h-1.5 rounded-full" style="width:100%"></div>
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
                                <span class="inline-flex items-center gap-1.5 bg-teal-50 text-teal-700 text-xs font-semibold px-3 py-1 rounded-full border border-teal-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span> Signed
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 bg-orange-50 text-orange-700 text-xs font-semibold px-3 py-1 rounded-full border border-orange-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span> Pending
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
                           class="w-full flex items-center justify-center gap-2 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm"
                           style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
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
                                <div class="w-7 h-7 rounded-lg flex items-center justify-center shrink-0" style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
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
                        <span class="bg-teal-50 text-teal-700 text-xs font-semibold px-3 py-1 rounded-full border border-teal-200">
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
                                         style="background: linear-gradient(135deg, #0a9396, #1b6ca8)">
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
                                    <span class="inline-flex items-center gap-1 bg-teal-50 text-teal-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-teal-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span> Signed
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
                        <span class="bg-orange-50 text-orange-700 text-xs font-semibold px-3 py-1 rounded-full border border-orange-200">
                            {{ $totalPending }} pending
                        </span>
                    </div>
                    <div class="space-y-2">
                        @foreach($pendingSends as $send)
                        <div class="flex items-center justify-between p-3.5 bg-orange-50/50 rounded-xl border border-orange-100">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0 bg-orange-400">
                                    {{ strtoupper(substr($send->client_name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-sm font-semibold text-gray-800">{{ $send->client_name ?? 'Unknown' }}</div>
                                    <div class="text-xs text-gray-400">{{ $send->client_email ?? '' }}</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0 ml-3">
                                <span class="text-xs text-gray-400">Sent {{ $send->created_at->format('M d') }}</span>
                                <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span> Pending
                                </span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const closeSidebarBtn = document.getElementById('close-sidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
        if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);
    });
</script>

</body>
</html>