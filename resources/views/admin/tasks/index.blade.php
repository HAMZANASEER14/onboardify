@extends('layouts.app')

@section('title', 'Tasks')
@section('page-title', 'Tasks')

@section('header-actions')
    <a href="{{ route('admin.tasks.create') }}"
       class="flex items-center gap-1.5 text-white text-sm font-semibold px-4 py-2 rounded-lg transition shadow-sm hover:opacity-90"
       style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Assign Task
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
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="rounded-xl px-5 py-6 shadow-sm" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
            <div class="text-2xl font-bold text-white">{{ $tasks->count() }}</div>
            <div class="text-xs font-semibold text-white/70 uppercase tracking-wide mt-1">Total</div>
        </div>
        <div class="rounded-xl px-5 py-6 shadow-sm" style="background: linear-gradient(135deg, #1a5c3a, #40916c);">
            <div class="text-2xl font-bold text-white">{{ $tasks->where('status', 'pending')->count() }}</div>
            <div class="text-xs font-semibold text-white/70 uppercase tracking-wide mt-1">Pending</div>
        </div>
        <div class="rounded-xl px-5 py-6 shadow-sm" style="background: linear-gradient(135deg, #2D6A4F, #40916c);">
            <div class="text-2xl font-bold text-white">{{ $tasks->where('status', 'in_progress')->count() }}</div>
            <div class="text-xs font-semibold text-white/70 uppercase tracking-wide mt-1">In Progress</div>
        </div>
        <div class="rounded-xl px-5 py-6 shadow-sm" style="background: linear-gradient(135deg, #2D6A4F, #52b788);">
            <div class="text-2xl font-bold text-white">{{ $tasks->where('status', 'completed')->count() }}</div>
            <div class="text-xs font-semibold text-white/70 uppercase tracking-wide mt-1">Completed</div>
        </div>
    </div>

    {{-- Table card --}}
    <div class="bg-white dark:bg-[#0d3d26] border border-gray-200 dark:border-[rgba(45,106,79,0.35)] rounded-xl overflow-hidden">

        {{-- Header bar --}}
        <div class="px-5 py-4 border-b border-gray-100 dark:border-[rgba(45,106,79,0.25)] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <div class="text-sm font-bold text-gray-900 dark:text-white">All Tasks</div>
                <div class="text-xs text-gray-400 dark:text-gray-500 mt-0.5">Tasks assigned to your team employees</div>
            </div>
            <div class="flex items-center gap-2">
                {{-- Search --}}
                <div class="relative">
                    <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 dark:text-gray-500 pointer-events-none"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="task-search" placeholder="Search tasks…"
                           class="pl-8 pr-3 py-1.5 border border-gray-200 dark:border-[rgba(45,106,79,0.35)] text-gray-800 dark:text-white dark:placeholder-gray-500 text-xs rounded-lg
                                  focus:outline-none focus:border-[#2D6A4F] dark:focus:border-[#52b788] focus:ring-2 focus:ring-[#2D6A4F]/10 dark:focus:ring-[#52b788]/20
                                  transition w-40 sm:w-48 bg-gray-50 dark:bg-[rgba(45,106,79,0.12)]"
                           aria-label="Search tasks">
                </div>
                {{-- Status filter --}}
                <select id="status-filter"
                        class="px-2.5 py-1.5 border border-gray-200 dark:border-[rgba(45,106,79,0.35)] text-gray-700 dark:text-white text-xs rounded-lg
                               focus:outline-none focus:border-[#2D6A4F] dark:focus:border-[#52b788] transition bg-gray-50 dark:bg-[rgba(45,106,79,0.12)]"
                        aria-label="Filter by status">
                    <option value="all">All</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>

        @if($tasks->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 bg-[#52b788]/10 dark:bg-[rgba(45,106,79,0.2)] rounded-2xl flex items-center justify-center mb-3">
                    <svg class="w-7 h-7 text-[#2D6A4F] dark:text-[#74c69d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2
                                 M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2
                                 m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <p class="text-sm font-semibold text-gray-500 dark:text-gray-400 mb-1">No tasks yet</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mb-4">Assign your first task to a team member.</p>
                <a href="{{ route('admin.tasks.create') }}"
                   class="text-white text-xs font-semibold px-4 py-2 rounded-lg hover:opacity-90 transition"
                   style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    Assign Task
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full border-collapse" id="tasks-table">
                    <thead>
                        <tr class="bg-gray-50 dark:bg-[rgba(45,106,79,0.12)] border-b border-gray-100 dark:border-[rgba(45,106,79,0.25)]">
                            <th class="text-left px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest">Task</th>
                            <th class="text-left px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest">Assigned To</th>
                            <th class="text-left px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest hidden sm:table-cell">Due Date</th>
                            <th class="text-left px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest">Status</th>
                            <th class="text-right px-5 py-3 text-[10px] font-bold text-gray-400 dark:text-gray-400 uppercase tracking-widest">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr class="task-row border-b border-gray-50 dark:border-[rgba(45,106,79,0.15)] hover:bg-gray-50/70 dark:hover:bg-[rgba(45,106,79,0.1)] transition"
                            data-title="{{ strtolower($task->title) }}"
                            data-assignee="{{ strtolower($task->assignedTo?->name ?? '') }}"
                            data-status="{{ $task->status }}">

                            {{-- Task title + description --}}
                            <td class="px-5 py-3 max-w-xs">
                                <div class="text-xs font-semibold text-gray-900 dark:text-white truncate">{{ $task->title }}</div>
                                @if($task->description)
                                    <div class="text-[10px] text-gray-400 dark:text-gray-500 mt-0.5 truncate">{{ Str::limit($task->description, 60) }}</div>
                                @endif
                            </td>

                            {{-- Assigned to --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center
                                                text-white text-[10px] font-bold shrink-0"
                                         style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                        {{ strtoupper(substr($task->assignedTo?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <span class="text-xs text-gray-700 dark:text-gray-300 font-medium">
                                        {{ $task->assignedTo?->name ?? '—' }}
                                    </span>
                                </div>
                            </td>

                            {{-- Due date --}}
                            <td class="px-5 py-3 hidden sm:table-cell">
                                @if($task->due_date)
                                    @php $due = \Carbon\Carbon::parse($task->due_date); @endphp
                                    <span class="text-xs {{ $due->isPast() && $task->status !== 'completed' ? 'text-red-500 dark:text-red-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                                        {{ $due->format('M d, Y') }}
                                    </span>
                                    @if($due->isPast() && $task->status !== 'completed')
                                        <span class="ml-1 text-[9px] font-bold text-red-500 dark:text-red-400 uppercase">Overdue</span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-300 dark:text-gray-600">—</span>
                                @endif
                            </td>

                            {{-- Status badge --}}
                            <td class="px-5 py-3">
                                @if($task->status === 'completed')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold
                                                 px-2.5 py-1 rounded-full bg-[#52b788]/10 dark:bg-[rgba(82,183,136,0.18)] text-[#2D6A4F] dark:text-[#74c69d]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#52b788] inline-block"></span>
                                        Completed
                                    </span>
                                @elseif($task->status === 'in_progress')
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold
                                                 px-2.5 py-1 rounded-full bg-[#2D6A4F]/10 dark:bg-[rgba(45,106,79,0.25)] text-[#0B3D2E] dark:text-[#95d5b2]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#2D6A4F] inline-block"></span>
                                        In Progress
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold
                                                 px-2.5 py-1 rounded-full bg-[#40916c]/10 dark:bg-[rgba(64,145,108,0.2)] text-[#1a5c3a] dark:text-[#95d5b2]">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#40916c] inline-block"></span>
                                        Pending
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-end gap-1.5">

                                    {{-- View --}}
                                    <a href="{{ route('admin.tasks.show', $task) }}"
                                       class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                              border border-gray-200 dark:border-[rgba(45,106,79,0.35)] bg-white dark:bg-[#0d3d26] text-gray-500 dark:text-gray-400
                                              hover:bg-[#2D6A4F]/10 dark:hover:bg-[rgba(45,106,79,0.2)] hover:text-[#2D6A4F] dark:hover:text-[#74c69d] hover:border-[#2D6A4F]/30 dark:hover:border-[#52b788] transition"
                                       title="View details" aria-label="View task details">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943
                                                     9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST"
                                          onsubmit="return confirm('Delete this task? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center justify-center w-7 h-7 rounded-lg
                                                       border border-gray-200 dark:border-[rgba(45,106,79,0.35)] bg-white dark:bg-[#0d3d26] text-gray-500 dark:text-gray-400
                                                       hover:bg-red-50 dark:hover:bg-red-500/10 hover:text-red-600 dark:hover:text-red-400 hover:border-red-200 dark:hover:border-red-500/30 transition">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858
                                                         L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach

                        {{-- Empty search state --}}
                        <tr id="no-results" style="display:none">
                            <td colspan="5" class="px-5 py-10 text-center text-sm text-gray-400 dark:text-gray-500">
                                No matching tasks found.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-5 py-3 border-t border-gray-100 dark:border-[rgba(45,106,79,0.25)] bg-gray-50/40 dark:bg-[rgba(45,106,79,0.08)] text-xs text-gray-400 dark:text-gray-500">
                {{ $tasks->count() }} task(s) total
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    const search   = document.getElementById('task-search');
    const filter   = document.getElementById('status-filter');
    const noResult = document.getElementById('no-results');

    function filterTasks() {
        const q      = (search?.value ?? '').toLowerCase().trim();
        const status = filter?.value ?? 'all';
        let visible  = 0;

        document.querySelectorAll('.task-row').forEach(row => {
            const matchSearch = row.dataset.title.includes(q) || row.dataset.assignee.includes(q);
            const matchStatus = status === 'all' || row.dataset.status === status;
            const show = matchSearch && matchStatus;
            row.style.display = show ? '' : 'none';
            if (show) visible++;
        });

        if (noResult) noResult.style.display = visible === 0 ? '' : 'none';
    }

    search?.addEventListener('input', filterTasks);
    filter?.addEventListener('change', filterTasks);
</script>
@endpush