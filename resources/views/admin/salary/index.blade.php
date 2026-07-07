@extends('layouts.app')

@section('title', 'Salary Slips')
@section('page-title', 'Salary Slips')

@section('header-actions')
    <a href="{{ route('admin.salary.create') }}"
       class="flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm hover:opacity-90"
       style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Upload Slip
    </a>
@endsection

@section('content')

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/30 text-emerald-800 dark:text-emerald-400 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats strip --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 mb-6">
        <div class="rounded-xl px-5 py-6 shadow-sm" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
            <div class="text-2xl font-bold text-white">{{ $salarySlips->count() }}</div>
            <div class="text-xs font-semibold text-white/70 uppercase tracking-wide mt-1">Total Slips</div>
        </div>
        <div class="rounded-xl px-5 py-6 shadow-sm" style="background: linear-gradient(135deg, #1a5c3a, #40916c);">
            <div class="text-2xl font-bold text-white">{{ $salarySlips->unique('user_id')->count() }}</div>
            <div class="text-xs font-semibold text-white/70 uppercase tracking-wide mt-1">Employees</div>
        </div>
        <div class="rounded-xl px-5 py-6 shadow-sm" style="background: linear-gradient(135deg, #2D6A4F, #52b788);">
            <div class="text-2xl font-bold text-white">
                {{ $salarySlips->where('created_at', '>=', now()->startOfMonth())->count() }}
            </div>
            <div class="text-xs font-semibold text-white/70 uppercase tracking-wide mt-1">This Month</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white dark:bg-[#0d3d26] border border-gray-200 dark:border-[rgba(45,106,79,0.35)] rounded-xl overflow-hidden">

        {{-- Table header bar --}}
        <div class="px-3 py-3 border-b border-gray-100 dark:border-[rgba(45,106,79,0.25)] flex flex-col sm:flex-row sm:items-center justify-between gap-1">
            <div>
                <div class="text-sm font-bold text-gray-900 dark:text-white">All Salary Slips</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">PDFs uploaded for your team employees</div>
            </div>
            {{-- Search --}}
            <div class="relative">
                <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="slip-search" placeholder="Search employee or month…"
                       class="pl-8 pr-3 py-1.5 border border-gray-200 dark:border-[rgba(45,106,79,0.35)] text-gray-800 dark:text-white dark:placeholder-gray-500 text-xs rounded-lg
                              focus:outline-none focus:border-[#2D6A4F] dark:focus:border-[#52b788] focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-[#52b788]/20
                              transition w-48 sm:w-56 bg-gray-50 dark:bg-[rgba(45,106,79,0.12)]"
                       aria-label="Search salary slips">
            </div>
        </div>

        @if($salarySlips->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 bg-[#52b788]/10 dark:bg-[rgba(45,106,79,0.2)] rounded-2xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-[#2D6A4F] dark:text-[#74c69d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293
                                 l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">No salary slips yet</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Upload your first salary slip for an employee.</p>
                <a href="{{ route('admin.salary.create') }}"
                   class="text-white text-xs font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition"
                   style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    Upload Slip
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="salary-table">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-[rgba(45,106,79,0.12)] border-b border-gray-100 dark:border-[rgba(45,106,79,0.25)]">
                            <th class="text-left px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest">Employee</th>
                            <th class="text-left px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest">Month</th>
                            <th class="text-left px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest hidden sm:table-cell">Uploaded</th>
                            <th class="text-right px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($salarySlips as $slip)
                        <tr class="slip-row border-b border-gray-50 dark:border-[rgba(45,106,79,0.15)] hover:bg-gray-50/70 dark:hover:bg-[rgba(45,106,79,0.1)] transition"
                            data-employee="{{ strtolower($slip->user?->name ?? '') }}"
                            data-month="{{ strtolower($slip->month ?? '') }}">

                            {{-- Employee --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-full flex items-center justify-center
                                                text-white text-[10px] font-bold shrink-0"
                                         style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                        {{ strtoupper(substr($slip->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-900 dark:text-white">
                                            {{ $slip->user?->name ?? '—' }}
                                        </div>
                                        <div class="text-[10px] text-gray-400 dark:text-gray-500">
                                            {{ $slip->user?->email ?? '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            {{-- Month --}}
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1
                                             rounded-full bg-[#2D6A4F]/10 dark:bg-[rgba(45,106,79,0.25)] text-[#2D6A4F] dark:text-[#95d5b2]">
                                    {{ $slip->month ?? '—' }}
                                </span>
                            </td>

                            {{-- Uploaded date --}}
                            <td class="px-5 py-3 text-[11px] text-gray-400 dark:text-gray-500 hidden sm:table-cell">
                                {{ $slip->created_at->format('M d, Y') }}
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1.5">
                                    {{-- Download --}}
                                    <a href="{{ route('admin.salary.download', $slip) }}"
                                       class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                              border border-gray-200 dark:border-[rgba(45,106,79,0.35)] bg-white dark:bg-[#0d3d26] text-gray-500 dark:text-gray-400
                                              hover:bg-[#2D6A4F]/10 dark:hover:bg-[rgba(45,106,79,0.2)] hover:text-[#2D6A4F] dark:hover:text-[#74c69d] hover:border-[#2D6A4F]/30 dark:hover:border-[#52b788] transition"
                                       title="Download PDF" aria-label="Download salary slip PDF">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.salary.destroy', $slip) }}" method="POST"
                                          onsubmit="return confirm('Delete this salary slip? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                                       border border-gray-200 dark:border-[rgba(45,106,79,0.35)] bg-white dark:bg-[#0d3d26] text-gray-500 dark:text-gray-400
                                                       hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-500/30 transition"
                                                title="Delete" aria-label="Delete salary slip">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Empty search state --}}
                        <tr id="no-results" style="display:none">
                            <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                No matching salary slips found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3 border-t border-gray-100 dark:border-[rgba(45,106,79,0.25)] bg-gray-50/40 dark:bg-[rgba(45,106,79,0.08)] text-xs text-gray-400 dark:text-gray-500">
                {{ $salarySlips->count() }} slip(s) total
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    const search = document.getElementById('slip-search');
    const noResults = document.getElementById('no-results');

    search?.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        let visible = 0;

        document.querySelectorAll('.slip-row').forEach(row => {
            const match = row.dataset.employee.includes(q) || row.dataset.month.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        if (noResults) noResults.style.display = visible === 0 ? '' : 'none';
    });
</script>
@endpush