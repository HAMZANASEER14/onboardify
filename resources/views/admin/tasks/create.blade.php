@extends('layouts.app')

@section('title', 'Assign Task')
@section('page-title', 'Assign Task')

@section('content')

    <div class="max-w-xl mx-auto">

        {{-- Errors --}}
        @if($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-700 text-sm px-4 py-3 rounded-xl mb-5">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-6">

            <div class="mb-6">
                <h2 class="text-base font-bold text-gray-900">Assign a Task</h2>
                <p class="text-xs text-gray-400 mt-0.5">Fill in the details and assign to an employee.</p>
            </div>

            <form action="{{ route('admin.tasks.store') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Assign to --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Assign To <span class="text-red-500">*</span>
                    </label>
                    <select name="assigned_to" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl
                                   px-4 py-2.5 text-sm focus:outline-none focus:border-[#2D6A4F]
                                   focus:ring-2 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition">
                        <option value="">— Select employee —</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ old('assigned_to') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Title --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Task Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                           placeholder="e.g. Complete onboarding documentation"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl
                                  px-4 py-2.5 text-sm focus:outline-none focus:border-[#2D6A4F]
                                  focus:ring-2 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition">
                    @error('title')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Description <span class="text-gray-400 font-normal normal-case">(optional)</span>
                    </label>
                    <textarea name="description" rows="4"
                              placeholder="Add any relevant details or instructions…"
                              class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl
                                     px-4 py-2.5 text-sm focus:outline-none focus:border-[#2D6A4F]
                                     focus:ring-2 focus:ring-[#2D6A4F]/10 hover:border-gray-300
                                     transition resize-none">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Due date --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Due Date <span class="text-gray-400 font-normal normal-case">(optional)</span>
                    </label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           min="{{ now()->addDay()->format('Y-m-d') }}"
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl
                                  px-4 py-2.5 text-sm focus:outline-none focus:border-[#2D6A4F]
                                  focus:ring-2 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition">
                    @error('due_date')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 text-white font-semibold py-3 rounded-xl text-sm transition
                                   shadow-sm hover:-translate-y-0.5 active:translate-y-0 hover:opacity-90"
                            style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        Assign Task
                    </button>
                    <a href="{{ route('admin.tasks.index') }}"
                       class="px-5 py-3 rounded-xl border border-gray-200 text-sm font-medium
                              text-gray-600 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection