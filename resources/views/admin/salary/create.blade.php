@extends('layouts.app')

@section('title', 'Upload Salary Slip')
@section('page-title', 'Upload Salary Slip')

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
                <h2 class="text-base font-bold text-gray-900">Upload Salary Slip</h2>
                <p class="text-xs text-gray-400 mt-0.5">Select an employee, enter the month, and upload a PDF.</p>
            </div>

            <form action="{{ route('admin.salary.store') }}" method="POST"
                  enctype="multipart/form-data" class="space-y-5">
                @csrf

                {{-- Employee --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Employee <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" required
                            class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl
                                   px-4 py-2.5 text-sm focus:outline-none focus:border-[#2D6A4F]
                                   focus:ring-2 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition">
                        <option value="">— Select employee —</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                {{ old('user_id') == $employee->id ? 'selected' : '' }}>
                                {{ $employee->name }} ({{ $employee->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Month --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                        Month <span class="text-red-500">*</span>
                    </label>
                    <input type="month" name="month" value="{{ old('month') }}" required
                           class="w-full bg-gray-50 border border-gray-200 text-gray-900 rounded-xl
                                  px-4 py-2.5 text-sm focus:outline-none focus:border-[#2D6A4F]
                                  focus:ring-2 focus:ring-[#2D6A4F]/10 hover:border-gray-300 transition">
                    @error('month')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- PDF Upload --}}
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5 uppercase tracking-wide">
                        PDF File <span class="text-red-500">*</span>
                    </label>

                    {{-- Drop zone --}}
                    <label for="file-upload"
                           class="flex flex-col items-center justify-center w-full border-2 border-dashed
                                  border-gray-200 rounded-xl px-4 py-8 cursor-pointer
                                  hover:border-[#52b788] hover:bg-[#52b788]/5 transition group"
                           id="drop-zone">
                        <svg class="w-8 h-8 text-gray-300 group-hover:text-[#52b788] transition mb-2"
                             fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-500 group-hover:text-[#2D6A4F] transition"
                              id="file-label">Click to upload or drag & drop</span>
                        <span class="text-xs text-gray-400 mt-1">PDF only · Max 10MB</span>
                        <input type="file" name="file" id="file-upload" accept=".pdf"
                               class="sr-only" required>
                    </label>

                    @error('file')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Actions --}}
                <div class="flex items-center gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 text-white font-semibold py-3 rounded-xl text-sm transition shadow-sm
                                   hover:-translate-y-0.5 active:translate-y-0 hover:opacity-90"
                            style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        Upload Salary Slip
                    </button>
                    <a href="{{ route('admin.salary.index') }}"
                       class="px-5 py-3 rounded-xl border border-gray-200 text-sm font-medium
                              text-gray-600 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    const input     = document.getElementById('file-upload');
    const fileLabel = document.getElementById('file-label');
    const dropZone  = document.getElementById('drop-zone');

    input?.addEventListener('change', function () {
        if (this.files.length) {
            fileLabel.textContent = this.files[0].name;
            dropZone.classList.add('border-[#52b788]', 'bg-[#52b788]/5');
        }
    });

    // Drag & drop support
    dropZone?.addEventListener('dragover', e => {
        e.preventDefault();
        dropZone.classList.add('border-[#52b788]', 'bg-[#52b788]/5');
    });

    dropZone?.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-[#52b788]', 'bg-[#52b788]/5');
    });

    dropZone?.addEventListener('drop', e => {
        e.preventDefault();
        const file = e.dataTransfer.files[0];
        if (file && file.type === 'application/pdf') {
            input.files = e.dataTransfer.files;
            fileLabel.textContent = file.name;
            dropZone.classList.add('border-[#52b788]', 'bg-[#52b788]/5');
        } else {
            alert('Please drop a PDF file only.');
        }
    });
</script>
@endpush