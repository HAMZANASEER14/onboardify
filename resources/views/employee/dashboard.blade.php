@extends('layouts.app')

@section('title', 'Employee Dashboard – Onboardify')
@section('page-title', 'Employee Dashboard')

@section('content')

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @php
        $user = auth()->user()->load('team');
        $team = $user->team;
        $joinedDate   = $user->joined_at ? \Carbon\Carbon::parse($user->joined_at) : null;
        $duration = $joinedDate ? $joinedDate->diffForHumans() : null;

        $pendingTasks   = \App\Models\Task::where('assigned_to', $user->id)
                            ->where('status', '!=', 'completed')
                            ->orderBy('due_date')
                            ->get();

        $taskCounts = \App\Models\Task::where('assigned_to', $user->id)
                        ->selectRaw("
                            COUNT(*) as total,
                            SUM(status = 'completed') as completed,
                            SUM(status != 'completed') as pending
                        ")
                        ->first();

        $completedCount = (int) ($taskCounts->completed ?? 0);
        $pendingCount   = (int) ($taskCounts->pending   ?? 0);

        $mySalaries      = \App\Models\SalarySlip::where('user_id', $user->id)->latest()->take(3)->get();
        $totalSalarySlips = \App\Models\SalarySlip::where('user_id', $user->id)->count();
    @endphp

    {{-- Stats Row - Responsive Grid --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-4">

        {{-- Welcome Card --}}
        <div class="col-span-2 sm:col-span-1 rounded-xl p-4 text-white shadow-md flex items-center gap-3"
             style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
            <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center text-white font-bold text-base shrink-0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <div class="font-bold text-sm truncate">
                    {{ optional($user->profile)->first_name ?? $user->name }}
                </div>
                <div class="text-white/60 text-[10px] uppercase tracking-wide">Employee</div>
                <div class="text-white/60 text-[10px] truncate">{{ $team?->name ?? 'Company' }}</div>
            </div>
        </div>

        {{-- Stat: Employment Duration --}}
        <div class="rounded-xl p-4 text-white shadow-md" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($joinedDate)
                    <span class="text-white/70 text-[10px] font-medium bg-white/10 px-1.5 py-0.5 rounded-full hidden sm:inline">
                        Since {{ $joinedDate->format('M Y') }}
                    </span>
                @endif
            </div>
            <div class="text-xl sm:text-2xl font-extrabold mb-0.5 leading-tight truncate">
                {{ $duration ?? '—' }}
            </div>
            <div class="text-white/80 text-xs font-medium">Employment Duration</div>
        </div>

        {{-- Stat: Pending Tasks --}}
        <div class="rounded-xl p-4 text-white shadow-md" style="background: linear-gradient(135deg, #1a5c3a, #40916c);">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-white/70 text-[10px] font-medium bg-white/10 px-1.5 py-0.5 rounded-full">Active</span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold mb-0.5">{{ $pendingCount }}</div>
            <div class="text-white/80 text-xs font-medium">Pending Tasks</div>
        </div>

        {{-- Stat: Completed Tasks --}}
        <div class="rounded-xl p-4 text-white shadow-md" style="background: linear-gradient(135deg, #2D6A4F, #52b788);">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-white/70 text-[10px] font-medium bg-white/10 px-1.5 py-0.5 rounded-full">Done</span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold mb-0.5">{{ $completedCount }}</div>
            <div class="text-white/80 text-xs font-medium">Completed Tasks</div>
        </div>

    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Pending Tasks --}}
        <div class="lg:col-span-2 bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm sm:text-base">My Pending Tasks</h3>
                    <p class="text-gray-400 text-xs mt-0.5">Tasks assigned to you</p>
                </div>
                @if($completedCount > 0)
                    <a href="{{ route('employee.tasks.index', ['status' => 'completed']) }}"
                       class="text-xs text-[#2D6A4F] hover:text-[#0B3D2E] font-medium transition">
                        {{ $completedCount }} done →
                    </a>
                @endif
            </div>

            @if($pendingTasks->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-14 h-14 bg-[#52b788]/10 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-600 font-medium text-sm mb-1">All caught up!</p>
                    <p class="text-gray-400 text-xs">You have no pending tasks</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($pendingTasks as $task)
                        @php
                            $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast()
                                         && $task->status !== 'completed';
                        @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-3 sm:p-4 rounded-xl transition gap-3
                            {{ $isOverdue ? 'bg-red-50 border border-red-100' : 'bg-gray-50 hover:bg-gray-100' }}">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-lg flex items-center justify-center shrink-0
                                    {{ $isOverdue ? 'bg-red-500' : '' }}"
                                    {{ $isOverdue ? '' : 'style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"' }}>
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="font-semibold text-gray-900 text-xs sm:text-sm truncate">{{ $task->title }}</h4>
                                    @if($task->description)
                                        <p class="text-[11px] sm:text-xs text-gray-500 mt-0.5 truncate">{{ $task->description }}</p>
                                    @endif
                                    <div class="flex items-center gap-2 sm:gap-3 mt-1.5 flex-wrap">
                                        @if($task->due_date)
                                            <span class="text-[10px] sm:text-xs flex items-center gap-1
                                                {{ $isOverdue ? 'text-red-600 font-semibold' : 'text-gray-500' }}">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                {{ $isOverdue ? 'Overdue · ' : 'Due ' }}{{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}
                                            </span>
                                        @endif
                                        <span class="text-[10px] sm:text-xs font-semibold px-2 py-0.5 rounded-full
                                            @if($task->status === 'pending') bg-yellow-100 text-yellow-700
                                            @elseif($task->status === 'in_progress') bg-blue-100 text-blue-700
                                            @else bg-[#52b788]/10 text-[#2D6A4F]
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('employee.tasks.updateStatus', $task) }}" method="POST"
                                  class="sm:ml-3 shrink-0 flex items-center gap-1.5">
                                @csrf
                                @method('PATCH')
                                <select name="status"
                                        class="text-[11px] sm:text-xs border border-gray-200 rounded-lg px-2 py-1.5 focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] bg-white transition">
                                    <option value="pending"     {{ $task->status === 'pending'     ? 'selected' : '' }}>Pending</option>
                                    <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="completed"   {{ $task->status === 'completed'   ? 'selected' : '' }}>Completed</option>
                                </select>
                                <button type="submit"
                                        class="text-[10px] sm:text-xs text-white px-2 sm:px-2.5 py-1.5 rounded-lg font-semibold transition hover:opacity-90"
                                        style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                    Save
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Salary Slips --}}
        <div class="bg-white rounded-xl p-4 sm:p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm sm:text-base">Salary Slips</h3>
                    <p class="text-gray-400 text-xs mt-0.5">Recent payslips</p>
                </div>
            </div>

            @if($mySalaries->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 text-center">
                    <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-xs">No salary slips yet</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($mySalaries as $slip)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-9 h-9 rounded-lg flex items-center justify-center shrink-0"
                                     style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-semibold text-gray-900 text-xs sm:text-sm truncate">{{ $slip->month }}</h4>
                                    <p class="text-[10px] sm:text-xs text-gray-500">{{ $slip->created_at->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('employee.salary.download', $slip) }}"
                               class="text-white text-[10px] sm:text-xs font-semibold px-2.5 sm:px-3 py-1.5 rounded-lg shrink-0 hover:opacity-90 transition"
                               style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                Download
                            </a>
                        </div>
                    @endforeach
                </div>

                @if($totalSalarySlips > 3)
                    <div class="mt-3 pt-3 border-t border-gray-100 text-center">
                        <a href="{{ route('employee.salary.index') }}"
                           class="text-xs text-[#2D6A4F] font-semibold hover:underline hover:text-[#0B3D2E] transition">
                            View all {{ $totalSalarySlips }} slips →
                        </a>
                    </div>
                @endif
            @endif
        </div>

    </div>

@endsection