@extends('layouts.app')

@section('title', 'Clients')

@section('content')

    {{-- Page Header with Actions --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Clients</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Manage and track all your clients in one place.</p>
        </div>
        <a href="{{ route('clients.create') }}"
           class="flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0"
           style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Add Client
        </a>
    </div>

    {{-- Stats Row --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-3 mb-4 sm:mb-6">

    <div class="rounded-xl p-2.5 shadow-sm flex items-center gap-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
         style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-white/20">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <div class="text-lg font-extrabold text-white">{{ $clients->total() }}</div>
            <div class="text-[11px] text-white/70 font-medium">Total Clients</div>
        </div>
    </div>

    <div class="rounded-xl p-2.5 shadow-sm flex items-center gap-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
         style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-white/20">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
<div class="text-lg font-extrabold text-white">{{ $activeCount }}</div>
            <div class="text-[11px] text-white/70 font-medium">Active</div>
        </div>
    </div>

    <div class="rounded-xl p-6 shadow-sm flex items-center gap-3 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200"
         style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0 bg-white/20">
            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
<div class="text-lg font-extrabold text-white">{{ $addedThisMonth }}</div>
            <div class="text-[11px] text-white/70 font-medium">Added This Month</div>
        </div>
    </div>
</div>
    @if($clients->isEmpty())
        {{-- Empty State --}}
        <div class="bg-white dark:bg-[#0d3d26] border border-gray-100 dark:border-[rgba(45,106,79,0.35)] rounded-2xl shadow-sm p-16 text-center">
            <div class="w-16 h-16 bg-gray-100 dark:bg-[rgba(45,106,79,0.15)] rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400 dark:text-[rgba(82,183,136,0.6)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h3 class="text-gray-800 dark:text-white font-bold text-base mb-1">No clients yet</h3>
            <p class="text-gray-400 dark:text-gray-500 text-sm mb-6">Add your first client to start sending documents.</p>
            <a href="{{ route('clients.create') }}"
               class="inline-flex items-center gap-2 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition shadow-sm hover:shadow-md"
               style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                </svg>
                Add First Client
            </a>
        </div>
    @else
        {{-- Search Bar --}}
        <div class="mb-4 flex items-center gap-3">
            <div class="relative flex-1 max-w-sm">
                <svg class="w-4 h-4 text-gray-400 dark:text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input id="client-search" type="text" placeholder="Search clients..."
                       class="w-full pl-9 pr-4 py-2.5 text-sm bg-white dark:bg-[#0d3d26] text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 border border-gray-200 dark:border-[rgba(45,106,79,0.35)] rounded-xl focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-[#52b788]/20 focus:border-[#2D6A4F] dark:focus:border-[#52b788] transition">
            </div>
            <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">{{ $clients->total() }} clients</span>
        </div>

        {{-- Clients Table --}}
        <div class="bg-white dark:bg-[#0d3d26] rounded-2xl border border-gray-100 dark:border-[rgba(45,106,79,0.35)] shadow-sm overflow-hidden">
            <table class="w-full text-sm" id="clients-table">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-[rgba(45,106,79,0.25)] bg-gray-50/70 dark:bg-[rgba(45,106,79,0.12)]">
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Client</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide hidden sm:table-cell">Phone</th>
                        <th class="text-left px-6 py-3.5 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide hidden md:table-cell">Added</th>
                        <th class="px-6 py-3.5"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50 dark:divide-[rgba(45,106,79,0.15)]">
                    @foreach($clients as $client)
                    <tr class="client-row hover:bg-gray-50/70 dark:hover:bg-[rgba(45,106,79,0.1)] transition group">
                        {{-- Avatar + Name --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0"
                                     style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                    {{ strtoupper(substr($client->name, 0, 1)) }}
                                </div>
                                <span class="font-semibold text-gray-900 dark:text-white text-sm">{{ $client->name }}</span>
                            </div>
                        </td>
                        {{-- Email --}}
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm">{{ $client->email }}</td>
                        {{-- Phone --}}
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400 text-sm hidden sm:table-cell">
                            {{ $client->phone ?? '—' }}
                        </td>
                        {{-- Date --}}
                        <td class="px-6 py-4 text-gray-400 dark:text-gray-500 text-xs hidden md:table-cell">
                            {{ $client->created_at->format('M d, Y') }}
                        </td>
                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="row-actions flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        
                                {{-- Delete --}}
                                <form action="{{ route('clients.destroy', $client) }}" method="POST"
                                      onsubmit="return confirm('Delete {{ $client->name }}? This cannot be undone.')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                            class="flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-50 dark:bg-red-500/10 hover:bg-red-100 dark:hover:bg-red-500/20 border border-red-100 dark:border-red-500/30 text-red-500 dark:text-red-400 transition">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="px-6 py-4 border-t border-gray-100 dark:border-[rgba(45,106,79,0.25)]">
    {{ $clients->links() }}
</div>
        </div>
    @endif

@endsection

@push('scripts')
<script>
    // Live search
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('client-search');
        if (searchInput) {
            searchInput.addEventListener('input', function () {
                const query = this.value.toLowerCase();
                document.querySelectorAll('#clients-table tbody tr').forEach(row => {
                    const name  = row.querySelector('td:first-child')?.textContent.toLowerCase() ?? '';
                    const email = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() ?? '';
                    row.style.display = (name.includes(query) || email.includes(query)) ? '' : 'none';
                });
            });
        }
    });
</script>
@endpush