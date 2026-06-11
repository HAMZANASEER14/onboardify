<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Waiver – Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }

        /* Dashboard Sidebar Styles */
        .sidebar-gradient { background: linear-gradient(160deg, #0f4c75 0%, #1b6ca8 50%, #0a9396 100%); }
        .nav-active { background: rgba(255,255,255,0.15); color: #fff; }
        .nav-item { color: rgba(255,255,255,0.65); transition: all 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }

        /* Form Builder Styles */
        .palette-item { transition: all 0.15s; }
        .palette-item:active { transform: scale(0.97); }
        .field-card { transition: border-color 0.15s; }
        .drag-over { border-color: #0a9396 !important; background: rgba(10,147,150,0.05) !important; }
        #fieldCanvas .field-card:hover { border-color: #1b6ca8; }
        .tab-btn { transition: all 0.15s; }
        .tab-btn.active { background: linear-gradient(135deg, #1b6ca8, #0a9396); color: white; border-color: transparent; }
        
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100 text-gray-900 min-h-screen flex font-sans antialiased">

    {{-- Mobile Overlay --}}
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    {{-- ── Sidebar (Exact match from Dashboard) ── --}}
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
                    <div class="text-white/50 text-[11px] truncate">BUILDING FORM</div>
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

    {{-- ── Main Content ── --}}
    <main class="flex-1 md:ml-60 min-h-screen flex flex-col">

        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-200 px-4 md:px-8 h-14 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <button id="mobile-menu-btn" class="md:hidden text-gray-500 hover:text-gray-900 p-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <h1 class="text-sm font-semibold text-gray-500 hidden md:block">Documents / <span class="text-gray-900">Create Waiver</span></h1>
            <div class="flex items-center gap-3 ml-auto">
                <a href="{{ route('waivers.index') }}" class="text-gray-500 hover:text-gray-900 text-sm transition flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </a>
            </div>
        </header>

        <div class="flex-1 p-4 md:p-8">
            <div class="max-w-6xl mx-auto">

                <div class="mb-6">
                    <h1 class="text-2xl font-bold text-gray-900">Create New Waiver</h1>
                    <p class="text-gray-500 text-sm mt-1">Build your waiver form by clicking or dragging field types</p>
                </div>

                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6">
                        @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
                    </div>
                @endif

                <form action="{{ route('waivers.store') }}" method="POST" id="waiverForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Waiver Details --}}
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-6">
                        <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#1b6ca8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            Waiver Details
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Waiver Title <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}" placeholder="e.g. Liability Waiver, Membership Agreement..." required
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#0a9396] focus:ring-2 focus:ring-[#0a9396]/10 transition">
                                @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Attach PDF Document (optional)</label>
                                <div class="relative">
                                    <input type="file" name="pdf_document" id="pdf_document" accept=".pdf"
                                        class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:border-[#0a9396] transition
                                               file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-[#0a9396] hover:file:bg-teal-100">
                                    <p class="text-gray-400 text-xs mt-1">Admin PDF — shown to client when signing</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Form Builder --}}
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-5">
                            <div>
                                <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                                    <svg class="w-4 h-4 text-[#1b6ca8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    Form Fields
                                </h2>
                                <p class="text-gray-500 text-xs mt-0.5">Click or drag field types to build your form</p>
                            </div>
                            <div id="fieldCount" class="bg-teal-50 border border-teal-200 text-[#0a9396] text-xs px-3 py-1 rounded-full font-medium">0 fields</div>
                        </div>

                        <div class="flex gap-5">
                            {{-- Left: Field Palette with Tabs --}}
                            <div class="w-48 flex-shrink-0">
                                <div class="flex flex-wrap gap-1 mb-3">
                                    <button type="button" class="tab-btn active text-xs px-2.5 py-1 rounded-lg border border-gray-200 font-medium" data-tab="basic">Basic</button>
                                    <button type="button" class="tab-btn text-xs px-2.5 py-1 rounded-lg border border-gray-200 font-medium text-gray-600" data-tab="datetime">Date</button>
                                    <button type="button" class="tab-btn text-xs px-2.5 py-1 rounded-lg border border-gray-200 font-medium text-gray-600" data-tab="selection">Select</button>
                                    <button type="button" class="tab-btn text-xs px-2.5 py-1 rounded-lg border border-gray-200 font-medium text-gray-600" data-tab="files">Files</button>
                                    <button type="button" class="tab-btn text-xs px-2.5 py-1 rounded-lg border border-gray-200 font-medium text-gray-600" data-tab="advanced">Advanced</button>
                                    <button type="button" class="tab-btn text-xs px-2.5 py-1 rounded-lg border border-gray-200 font-medium text-gray-600" data-tab="location">Location</button>
                                </div>

                                <div id="tab-basic" class="tab-content space-y-1">
                                    <div draggable="true" class="palette-item" data-type="fullname">👤 Full Name</div>
                                    <div draggable="true" class="palette-item" data-type="text">📝 Text Input</div>
                                    <div draggable="true" class="palette-item" data-type="textarea">📄 Textarea</div>
                                    <div draggable="true" class="palette-item" data-type="email">📧 Email</div>
                                    <div draggable="true" class="palette-item" data-type="password">🔒 Password</div>
                                    <div draggable="true" class="palette-item" data-type="number">🔢 Number</div>
                                    <div draggable="true" class="palette-item" data-type="phone">📞 Phone Number</div>
                                    <div draggable="true" class="palette-item" data-type="url">🔗 URL/Website</div>
                                </div>
                                <div id="tab-datetime" class="tab-content space-y-1 hidden">
                                    <div draggable="true" class="palette-item" data-type="date">📅 Date Picker</div>
                                    <div draggable="true" class="palette-item" data-type="time">⏰ Time Picker</div>
                                    <div draggable="true" class="palette-item" data-type="datetime">📆 Date & Time</div>
                                    <div draggable="true" class="palette-item" data-type="dob">🎂 Date of Birth</div>
                                </div>
                                <div id="tab-selection" class="tab-content space-y-1 hidden">
                                    <div draggable="true" class="palette-item" data-type="select">🔽 Dropdown</div>
                                    <div draggable="true" class="palette-item" data-type="radio">🔘 Radio Buttons</div>
                                    <div draggable="true" class="palette-item" data-type="checkbox">☑️ Checkboxes</div>
                                    <div draggable="true" class="palette-item" data-type="yesno">✅ Yes / No Toggle</div>
                                </div>
                                <div id="tab-files" class="tab-content space-y-1 hidden">
                                    <div draggable="true" class="palette-item" data-type="file">📎 File Upload</div>
                                    <div draggable="true" class="palette-item" data-type="image">🖼️ Image Upload</div>
                                    <div draggable="true" class="palette-item" data-type="pdf_upload">📄 PDF Upload</div>
                                </div>
                                <div id="tab-advanced" class="tab-content space-y-1 hidden">
                                    <div draggable="true" class="palette-item" data-type="signature">✍️ Signature</div>
                                    <div draggable="true" class="palette-item" data-type="rating">⭐ Rating</div>
                                    <div draggable="true" class="palette-item" data-type="range">🎚️ Range Slider</div>
                                    <div draggable="true" class="palette-item" data-type="color">🎨 Color Picker</div>
                                    <div draggable="true" class="palette-item" data-type="hidden">👁️ Hidden Field</div>
                                    <div draggable="true" class="palette-item" data-type="heading">🔤 Heading</div>
                                    <div draggable="true" class="palette-item" data-type="paragraph">📃 Paragraph</div>
                                    <div draggable="true" class="palette-item" data-type="divider">➖ Divider</div>
                                </div>
                                <div id="tab-location" class="tab-content space-y-1 hidden">
                                    <div draggable="true" class="palette-item" data-type="address">🏠 Full Address</div>
                                    <div draggable="true" class="palette-item" data-type="country">🌍 Country</div>
                                    <div draggable="true" class="palette-item" data-type="state">📍 State/Province</div>
                                    <div draggable="true" class="palette-item" data-type="city">🏙️ City</div>
                                    <div draggable="true" class="palette-item" data-type="zipcode">📮 ZIP/Postal Code</div>
                                </div>
                            </div>

                            {{-- Right: Drop Canvas --}}
                            <div class="flex-1 min-h-96 overflow-y-auto border-2 border-dashed border-gray-200 rounded-xl p-4 bg-gray-50/50 transition" id="fieldCanvas">
                                <div id="canvasEmpty" class="flex flex-col items-center justify-center h-80 text-center">
                                    <div class="w-14 h-14 bg-gray-100 rounded-2xl flex items-center justify-center mb-3">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    </div>
                                    <p class="text-gray-500 text-sm font-medium">No fields yet</p>
                                    <p class="text-gray-400 text-xs mt-1">Click a field type on the left to add it</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="fields" id="fieldsJson">

                    {{-- Settings --}}
                    <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-6">
                        <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#1b6ca8]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Settings
                        </h2>
                        <div class="space-y-3">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="require_signature" value="1" checked class="accent-[#0a9396] w-4 h-4">
                                <div>
                                    <div class="text-gray-900 text-sm font-medium">Require Client Signature</div>
                                    <div class="text-gray-500 text-xs">Client must sign before submitting</div>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" checked class="accent-[#0a9396] w-4 h-4">
                                <div>
                                    <div class="text-gray-900 text-sm font-medium">Active</div>
                                    <div class="text-gray-500 text-xs">Waiver is visible and can be sent to clients</div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="flex gap-3">
                        <button type="submit"
                            class="hover:opacity-90 text-white font-semibold px-6 py-3 rounded-xl text-sm transition shadow-sm flex items-center gap-2"
                            style="background: linear-gradient(135deg, #1b6ca8, #0a9396)">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            Save Waiver
                        </button>
                        <a href="{{ route('waivers.index') }}"
                            class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl text-sm transition">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <style>
    .palette-item {
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 8px 12px;
        cursor: pointer;
        font-size: 12px;
        color: #374151;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.15s;
    }
    .palette-item:hover {
        background: rgba(10,147,150,0.05);
        border-color: #0a9396;
        color: #0a9396;
    }
    </style>

    <script>
    // Sidebar Toggle Logic
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

    // ── Form Builder Logic (100% Untouched) ──
    var fields  = [];
    var sortable = null;
    var dragType = null;

    var typeLabels = {
        fullname:'Full Name', text:'Text Input', textarea:'Textarea',
        email:'Email', password:'Password', number:'Number',
        phone:'Phone Number', url:'URL/Website',
        date:'Date Picker', time:'Time Picker', datetime:'Date & Time', dob:'Date of Birth',
        select:'Dropdown', radio:'Radio Buttons', checkbox:'Checkboxes', yesno:'Yes / No',
        file:'File Upload', image:'Image Upload', pdf_upload:'PDF Upload',
        signature:'Signature', rating:'Rating', range:'Range Slider',
        color:'Color Picker', hidden:'Hidden Field',
        heading:'Heading', paragraph:'Paragraph', divider:'Divider',
        address:'Full Address', country:'Country', state:'State/Province',
        city:'City', zipcode:'ZIP/Postal Code'
    };

    var typeIcons = {
        fullname:'👤', text:'📝', textarea:'📄', email:'📧', password:'🔒',
        number:'🔢', phone:'📞', url:'🔗',
        date:'📅', time:'⏰', datetime:'📆', dob:'🎂',
        select:'🔽', radio:'🔘', checkbox:'☑️', yesno:'✅',
        file:'📎', image:'🖼️', pdf_upload:'📄',
        signature:'✍️', rating:'⭐', range:'🎚️', color:'🎨',
        hidden:'👁️', heading:'🔤', paragraph:'📃', divider:'➖',
        address:'🏠', country:'🌍', state:'📍', city:'🏙️', zipcode:'📮'
    };

    var typeColors = {
        fullname:'teal', text:'teal', textarea:'teal', email:'teal', password:'teal',
        number:'teal', phone:'teal', url:'teal',
        date:'green', time:'green', datetime:'green', dob:'green',
        select:'purple', radio:'purple', checkbox:'purple', yesno:'purple',
        file:'orange', image:'orange', pdf_upload:'orange',
        signature:'pink', rating:'yellow', range:'yellow', color:'pink',
        hidden:'gray', heading:'gray', paragraph:'gray', divider:'gray',
        address:'blue', country:'blue', state:'blue', city:'blue', zipcode:'blue'
    };

    var colorMap = {
        blue:   'bg-blue-50 border-blue-200 text-[#1b6ca8]',
        green:  'bg-green-50 border-green-200 text-green-700',
        purple: 'bg-purple-50 border-purple-200 text-purple-700',
        orange: 'bg-orange-50 border-orange-200 text-orange-700',
        pink:   'bg-pink-50 border-pink-200 text-pink-700',
        yellow: 'bg-yellow-50 border-yellow-200 text-yellow-700',
        teal:   'bg-teal-50 border-teal-200 text-[#0a9396]',
        gray:   'bg-gray-100 border-gray-200 text-gray-600'
    };

    function addField(type) {
        var newField = {
            id:          'f_' + Date.now() + '_' + Math.random().toString(36).substr(2,5),
            type:        type,
            label:       typeLabels[type],
            required:    false,
            options:     (['select','radio','yesno','checkbox'].includes(type))
                         ? (type === 'yesno' ? ['Yes','No'] : ['Option 1','Option 2'])
                         : [],
            placeholder: '',
            min:         '',
            max:         '',
            step:        ''
        };
        fields.push(newField);
        renderCanvas(newField.id);
        updateJson();
        updateFieldCount();
    }

    function renderCanvas(newFieldId) {
        var canvas = document.getElementById('fieldCanvas');
        var empty  = document.getElementById('canvasEmpty');

        if (fields.length === 0) {
            if (sortable) { sortable.destroy(); sortable = null; }
            Array.from(canvas.querySelectorAll('.field-card')).forEach(function(el){ el.remove(); });
            empty.style.display = 'flex';
            return;
        }

        empty.style.display = 'none';
        Array.from(canvas.querySelectorAll('.field-card')).forEach(function(el){ el.remove(); });

        fields.forEach(function(field) {
            var card = document.createElement('div');
            card.className = 'field-card bg-white border border-gray-200 shadow-sm rounded-xl p-3 mb-2.5 flex items-start gap-3';
            card.setAttribute('data-id', field.id);

            var badge = colorMap[typeColors[field.type]] || colorMap.gray;
            var extra = '';

            if (['select','radio','checkbox'].includes(field.type)) {
                extra += `<div class="mt-2">
                    <label class="text-xs text-gray-500 font-medium">Options (comma separated)</label>
                    <input type="text" value="${field.options.join(', ')}"
                        oninput="updateOptions('${field.id}', this.value)"
                        class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-[#0a9396] transition"
                        placeholder="Option 1, Option 2, Option 3">
                </div>`;
            }
            if (field.type === 'yesno') {
                extra += `<div class="mt-2 flex gap-2">
                    <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-medium">Yes</span>
                    <span class="bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-medium">No</span>
                </div>`;
            }
            if (field.type === 'rating') {
                extra += `<div class="mt-2">
                    <label class="text-xs text-gray-500 font-medium">Max Stars</label>
                    <input type="number" value="${field.max || 5}" min="1" max="10"
                        oninput="updateMax('${field.id}', this.value)"
                        class="w-20 bg-gray-50 border border-gray-200 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-[#0a9396] transition">
                    <div class="text-yellow-400 text-sm mt-1">⭐⭐⭐⭐⭐</div>
                </div>`;
            }
            if (field.type === 'range') {
                extra += `<div class="mt-2 grid grid-cols-3 gap-2">
                    <div><label class="text-xs text-gray-500">Min</label><input type="number" value="${field.min || 0}" oninput="updateMin('${field.id}', this.value)" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg px-2 py-1.5 mt-1 focus:outline-none focus:border-[#0a9396]"></div>
                    <div><label class="text-xs text-gray-500">Max</label><input type="number" value="${field.max || 100}" oninput="updateMax('${field.id}', this.value)" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg px-2 py-1.5 mt-1 focus:outline-none focus:border-[#0a9396]"></div>
                    <div><label class="text-xs text-gray-500">Step</label><input type="number" value="${field.step || 1}" oninput="updateStep('${field.id}', this.value)" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg px-2 py-1.5 mt-1 focus:outline-none focus:border-[#0a9396]"></div>
                </div>
                <div class="mt-1"><input type="range" min="${field.min||0}" max="${field.max||100}" step="${field.step||1}" class="w-full accent-[#0a9396]" disabled></div>`;
            }
            if (field.type === 'color') {
                extra += `<div class="mt-2 flex items-center gap-2"><input type="color" value="#0a9396" disabled class="w-8 h-8 rounded border border-gray-200 cursor-not-allowed"><span class="text-xs text-gray-400">Color picker preview</span></div>`;
            }
            if (field.type === 'signature') {
                extra += `<div class="mt-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-3 text-center text-xs text-gray-400">✍️ Signature box will appear here</div>`;
            }
            if (field.type === 'pdf_upload') {
                extra += `<div class="mt-2 bg-orange-50 border border-orange-200 rounded-xl p-3 text-xs text-orange-600 flex items-center gap-2"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Client can upload a PDF document here</div>`;
            }
            if (field.type === 'image') {
                extra += `<div class="mt-2 bg-blue-50 border border-blue-200 rounded-xl p-3 text-xs text-[#1b6ca8] flex items-center gap-2"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>Client can upload an image here</div>`;
            }
            if (field.type === 'divider') {
                extra += `<div class="mt-2 border-t-2 border-gray-200 w-full"></div>`;
            }
            if (field.type === 'hidden') {
                extra += `<div class="mt-2"><label class="text-xs text-gray-500 font-medium">Hidden Value</label><input type="text" value="${field.placeholder || ''}" oninput="updatePlaceholder('${field.id}', this.value)" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-[#0a9396] transition" placeholder="Value stored but not shown to user"></div>`;
            }
            if (!['heading','paragraph','divider','yesno','rating','range','color','signature','pdf_upload','image','hidden','file'].includes(field.type)) {
                extra += `<div class="mt-2"><label class="text-xs text-gray-500 font-medium">Placeholder text</label><input type="text" value="${field.placeholder || ''}" oninput="updatePlaceholder('${field.id}', this.value)" onkeydown="handleFieldEnter(event,'${field.id}')" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-[#0a9396] transition" placeholder="Enter placeholder text..."></div>`;
            }

            card.innerHTML = `
                <div class="text-gray-300 cursor-grab text-base pt-0.5 drag-handle select-none leading-none">⠿</div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                        <span class="text-xs border px-2 py-0.5 rounded-full font-medium ${badge}">${typeIcons[field.type]} ${typeLabels[field.type]}</span>
                        <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">
                            <input type="checkbox" ${field.required ? 'checked' : ''} onchange="toggleRequired('${field.id}', this.checked)" class="accent-[#0a9396] w-3.5 h-3.5"> Required
                        </label>
                    </div>
                    ${!['divider'].includes(field.type) ? `<input type="text" class="w-full bg-transparent border-b border-gray-200 text-gray-900 text-sm py-1 focus:outline-none focus:border-[#0a9396] transition font-medium" value="${field.label}" placeholder="Field label..." oninput="updateLabel('${field.id}', this.value)" onkeydown="handleFieldEnter(event,'${field.id}')">` : ''}
                    ${extra}
                </div>
                <div class="flex flex-col gap-1">
                    <button type="button" onclick="moveField('${field.id}',-1)" class="text-gray-300 hover:text-[#1b6ca8] transition text-xs" title="Move Up">▲</button>
                    <button type="button" onclick="moveField('${field.id}',1)" class="text-gray-300 hover:text-[#1b6ca8] transition text-xs" title="Move Down">▼</button>
                </div>
                <button type="button" onclick="deleteField('${field.id}')" class="text-gray-300 hover:text-red-500 transition text-lg leading-none">✕</button>
            `;
            canvas.appendChild(card);
        });

        if (newFieldId) {
            setTimeout(function() {
                var newCard = document.querySelector('.field-card[data-id="' + newFieldId + '"]');
                if (newCard) {
                    var labelInput = newCard.querySelector('input[oninput*="updateLabel"]');
                    if (labelInput) { labelInput.focus(); labelInput.select(); }
                    newCard.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 50);
        }

        if (sortable) sortable.destroy();
        sortable = Sortable.create(canvas, {
            animation: 150,
            handle: '.drag-handle',
            onEnd: function(evt) {
                var moved = fields.splice(evt.oldIndex, 1)[0];
                fields.splice(evt.newIndex, 0, moved);
                updateJson();
            }
        });
    }

    function updateLabel(id, v)       { var f=fields.find(f=>f.id===id); if(f){f.label=v; updateJson();} }
    function toggleRequired(id, v)    { var f=fields.find(f=>f.id===id); if(f){f.required=v; updateJson();} }
    function updatePlaceholder(id, v) { var f=fields.find(f=>f.id===id); if(f){f.placeholder=v; updateJson();} }
    function updateMin(id, v)         { var f=fields.find(f=>f.id===id); if(f){f.min=v; updateJson();} }
    function updateMax(id, v)         { var f=fields.find(f=>f.id===id); if(f){f.max=v; updateJson();} }
    function updateStep(id, v)        { var f=fields.find(f=>f.id===id); if(f){f.step=v; updateJson();} }
    function updateOptions(id, v) { var f=fields.find(f=>f.id===id); if(f){ f.options=v.split(',').map(o=>o.trim()); updateJson(); } }
    function deleteField(id) { fields = fields.filter(f=>f.id!==id); renderCanvas(); updateJson(); updateFieldCount(); }
    function moveField(id, dir) {
        var i = fields.findIndex(f=>f.id===id); var ni = i + dir;
        if (ni < 0 || ni >= fields.length) return;
        var tmp = fields[i]; fields[i] = fields[ni]; fields[ni] = tmp;
        renderCanvas(); updateJson();
    }
    function handleFieldEnter(e, currentId) {
        if (e.key !== 'Enter') return; e.preventDefault();
        var i = fields.findIndex(f=>f.id===currentId);
        if (i !== -1 && i < fields.length-1) {
            var nextCard = document.querySelector('.field-card[data-id="'+fields[i+1].id+'"]');
            if (nextCard) { var ni = nextCard.querySelector('input[oninput*="updateLabel"]'); if (ni) { ni.focus(); ni.select(); } }
        }
    }

    function updateFieldCount() {
        var b = document.getElementById('fieldCount');
        b.textContent = fields.length + (fields.length===1?' field':' fields');
        b.className = fields.length > 0
            ? 'bg-teal-50 border border-teal-200 text-[#0a9396] text-xs px-3 py-1 rounded-full font-medium'
            : 'bg-gray-100 border border-gray-200 text-gray-500 text-xs px-3 py-1 rounded-full';
    }

    function updateJson() { document.getElementById('fieldsJson').value = JSON.stringify(fields); }

    document.querySelectorAll('.tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.tab-btn').forEach(function(b) { b.classList.remove('active'); b.classList.add('text-gray-600'); });
            this.classList.add('active'); this.classList.remove('text-gray-600');
            document.querySelectorAll('.tab-content').forEach(function(c){ c.classList.add('hidden'); });
            document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
        });
    });

    document.querySelectorAll('.palette-item').forEach(function(item) {
        item.addEventListener('click', function() { addField(this.getAttribute('data-type')); });
        item.addEventListener('dragstart', function() { dragType = this.getAttribute('data-type'); });
        item.addEventListener('dragend',   function() { dragType = null; });
    });

    var canvas = document.getElementById('fieldCanvas');
    canvas.addEventListener('dragover',  function(e){ e.preventDefault(); canvas.classList.add('drag-over'); });
    canvas.addEventListener('dragleave', function()  { canvas.classList.remove('drag-over'); });
    canvas.addEventListener('drop',      function(e){ e.preventDefault(); canvas.classList.remove('drag-over'); if (dragType) { addField(dragType); dragType = null; } });

    document.getElementById('waiverForm').addEventListener('submit', function(e) {
        if (fields.length === 0) { e.preventDefault(); alert('Please add at least one field to your waiver.'); return; }
        updateJson();
    });
    </script>

</body>
</html>