@extends('layouts.app')

@section('title', 'My Tasks – Onboardify')
@section('page-title', 'My Tasks')

@section('header-actions')
    <span class="text-sm text-gray-600">
        {{ $tasks->count() }} task{{ $tasks->count() !== 1 ? 's' : '' }} assigned to you
    </span>
@endsection

@section('content')

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Stats Cards - Matching Employee Dashboard Theme --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 sm:gap-4 mb-4">
        
        {{-- Total Tasks --}}
        <div class="rounded-xl p-4 text-white shadow-md" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F);">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <span class="text-white/70 text-[10px] font-medium bg-white/10 px-1.5 py-0.5 rounded-full">All</span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold mb-0.5">{{ $tasks->count() }}</div>
            <div class="text-white/80 text-xs font-medium">Total Tasks</div>
        </div>

        {{-- Pending --}}
        <div class="rounded-xl p-4 text-white shadow-md" style="background: linear-gradient(135deg, #1a5c3a, #40916c);">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-white/70 text-[10px] font-medium bg-white/10 px-1.5 py-0.5 rounded-full">Active</span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold mb-0.5">{{ $tasks->where('status', 'pending')->count() }}</div>
            <div class="text-white/80 text-xs font-medium">Pending Tasks</div>
        </div>

        {{-- Completed --}}
        <div class="rounded-xl p-4 text-white shadow-md col-span-2 sm:col-span-1" style="background: linear-gradient(135deg, #2D6A4F, #52b788);">
            <div class="flex items-center justify-between mb-2">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-white/70 text-[10px] font-medium bg-white/10 px-1.5 py-0.5 rounded-full">Done</span>
            </div>
            <div class="text-2xl sm:text-3xl font-extrabold mb-0.5">{{ $tasks->where('status', 'completed')->count() }}</div>
            <div class="text-white/80 text-xs font-medium">Completed Tasks</div>
        </div>

    </div>

    {{-- Tasks List --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-gray-100">
            <h2 class="text-base sm:text-lg font-bold text-gray-900">All Tasks</h2>
            <p class="text-xs sm:text-sm text-gray-500 mt-0.5">Tasks assigned to you by your admin</p>
        </div>

        @if($tasks->isEmpty())
            <div class="p-10 sm:p-12 text-center">
                <div class="w-14 h-14 sm:w-16 sm:h-16 bg-[#52b788]/10 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 sm:w-8 sm:h-8 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <p class="text-gray-500 text-sm mb-1">No tasks assigned yet</p>
                <p class="text-gray-400 text-xs">Your admin will assign tasks to you here</p>
            </div>
        @else
            <div class="divide-y divide-gray-100">
                @foreach($tasks as $task)
                    @php
                        $isOverdue = $task->due_date && \Carbon\Carbon::parse($task->due_date)->isPast()
                                     && $task->status !== 'completed';
                    @endphp
                    <div class="p-4 sm:p-5 hover:bg-gray-50 transition">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3 sm:gap-4">
                            <div class="flex items-start gap-3 flex-1 min-w-0">
                                {{-- Task Icon with gradient matching dashboard --}}
                                <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0
                                    @if($isOverdue) bg-red-500
                                    @elseif($task->status === 'completed')
                                        style="background: linear-gradient(135deg, #2D6A4F, #52b788)"
                                    @elseif($task->status === 'in_progress')
                                        style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)"
                                    @else
                                        style="background: linear-gradient(135deg, #1a5c3a, #40916c)"
                                    @endif">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <h3 class="text-sm sm:text-base font-semibold text-gray-900 truncate">{{ $task->title }}</h3>
                                        <span class="px-2 py-0.5 text-[10px] sm:text-xs font-semibold rounded-full
                                            @if($task->status === 'completed') bg-[#52b788]/10 text-[#2D6A4F]
                                            @elseif($task->status === 'in_progress') bg-[#2D6A4F]/10 text-[#0B3D2E]
                                            @else bg-[#40916c]/10 text-[#1a5c3a]
                                            @endif">
                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                        </span>
                                    </div>
                                    
                                    @if($task->description)
                                        <p class="text-xs sm:text-sm text-gray-600 mb-2">{{ $task->description }}</p>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-3 sm:gap-4 text-[10px] sm:text-xs text-gray-500">
                                        @if($task->due_date)
                                            <div class="flex items-center gap-1.5 {{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                                <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <span>{{ $isOverdue ? 'Overdue · ' : 'Due: ' }}{{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                                            </div>
                                        @endif

                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                            <span>{{ $task->assignedBy->name }}</span>
                                        </div>

                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>{{ $task->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="shrink-0">
                                <form action="{{ route('employee.tasks.updateStatus', $task) }}" method="POST"
                                      class="flex items-center gap-1.5">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" 
                                        class="text-xs sm:text-sm border border-gray-200 rounded-lg px-2.5 sm:px-3 py-1.5 sm:py-2 focus:outline-none focus:ring-2 focus:ring-[#2D6A4F]/20 focus:border-[#2D6A4F] cursor-pointer bg-white transition">
                                        <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                    <button type="submit"
                                            class="text-xs sm:text-sm text-white px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg font-semibold transition hover:opacity-90"
                                            style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                        Save
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection