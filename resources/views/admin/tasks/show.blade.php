@extends('layouts.app')

@section('title', 'Task Details')
@section('page-title', 'Task Details')

@section('content')

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-6 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-2xl mx-auto space-y-4">

        {{-- Main card --}}
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">

            {{-- Card header --}}
            <div class="px-6 py-5 border-b border-gray-100 flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h2 class="text-lg font-bold text-gray-900 leading-snug">{{ $task->title }}</h2>
                    <p class="text-xs text-gray-400 mt-1">
                        Assigned by
                        <span class="font-medium text-gray-600">{{ $task->assignedBy?->name ?? '—' }}</span>
                        · {{ $task->created_at->format('M d, Y') }}
                    </p>
                </div>
                {{-- Status badge --}}
                @if($task->status === 'completed')
                    <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold
                                 px-3 py-1.5 rounded-full bg-[#52b788]/10 text-[#2D6A4F]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#52b788] inline-block"></span>
                        Completed
                    </span>
                @elseif($task->status === 'in_progress')
                    <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold
                                 px-3 py-1.5 rounded-full bg-[#2D6A4F]/10 text-[#0B3D2E]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#2D6A4F] inline-block"></span>
                        In Progress
                    </span>
                @else
                    <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-semibold
                                 px-3 py-1.5 rounded-full bg-[#40916c]/10 text-[#1a5c3a]">
                        <span class="w-1.5 h-1.5 rounded-full bg-[#40916c] inline-block"></span>
                        Pending
                    </span>
                @endif
            </div>

            {{-- Details grid --}}
            <div class="px-6 py-5 space-y-5">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- Assigned to --}}
                    <div>
                        <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">
                            Assigned To
                        </div>
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                        text-white text-xs font-bold shrink-0"
                                 style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                {{ strtoupper(substr($task->assignedTo?->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-gray-900">
                                    {{ $task->assignedTo?->name ?? '—' }}
                                </div>
                                <div class="text-xs text-gray-400">
                                    {{ $task->assignedTo?->email ?? '' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Due date --}}
                    <div>
                        <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">
                            Due Date
                        </div>
                        @if($task->due_date)
                            @php $due = \Carbon\Carbon::parse($task->due_date); @endphp
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-semibold
                                    {{ $due->isPast() && $task->status !== 'completed'
                                        ? 'text-red-600' : 'text-gray-900' }}">
                                    {{ $due->format('M d, Y') }}
                                </span>
                                @if($due->isPast() && $task->status !== 'completed')
                                    <span class="text-[9px] font-bold text-red-500 bg-red-50
                                                 px-2 py-0.5 rounded-full uppercase">
                                        Overdue
                                    </span>
                                @elseif($task->status !== 'completed')
                                    <span class="text-[10px] text-gray-400">
                                        {{ $due->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-sm text-gray-400">No due date set</span>
                        @endif
                    </div>
                </div>

                {{-- Description --}}
                @if($task->description)
                    <div>
                        <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">
                            Description
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-wrap bg-gray-50
                                  rounded-xl px-4 py-3 border border-gray-100">
                            {{ $task->description }}
                        </p>
                    </div>
                @endif

            </div>
        </div>

        {{-- Update status card --}}
        @if($task->status !== 'completed')
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm px-6 py-5">
            <div class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">
                Update Status
            </div>
            <form action="{{ route('admin.tasks.updateStatus', $task) }}" method="POST"
                  class="flex flex-col sm:flex-row gap-2">
                @csrf
                @method('PATCH')
                <select name="status"
                        class="flex-1 bg-gray-50 border border-gray-200 text-gray-800 text-sm
                               rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#2D6A4F]
                               focus:ring-2 focus:ring-[#2D6A4F]/10 transition">
                    <option value="pending"     {{ $task->status === 'pending'     ? 'selected' : '' }}>Pending</option>
                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed"   {{ $task->status === 'completed'   ? 'selected' : '' }}>Completed</option>
                </select>
                <button type="submit"
                        class="px-6 py-2.5 text-white text-sm font-semibold rounded-xl transition
                               hover:-translate-y-0.5 active:translate-y-0 shadow-sm hover:opacity-90"
                        style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                    Update
                </button>
            </form>
        </div>
        @endif

        {{-- Footer actions --}}
        <div class="flex items-center justify-between pt-1 pb-2">
            <a href="{{ route('admin.tasks.index') }}"
               class="text-xs font-semibold text-gray-400 hover:text-gray-700 transition">
                ← Back to tasks
            </a>
            <form action="{{ route('admin.tasks.destroy', $task) }}" method="POST"
                  onsubmit="return confirm('Delete this task? This cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit"
                        class="text-xs font-semibold text-red-400 hover:text-red-600 transition">
                    Delete task
                </button>
            </form>
        </div>

    </div>

@endsection