@extends('layouts.app')

@section('title', 'Edit Waiver')

@section('content')

    {{-- Page-Specific Styles --}}
    <style>
        .palette-item { transition: all 0.15s; }
        .palette-item:active { transform: scale(0.97); }
        .field-card { transition: border-color 0.15s; }
        .drag-over { border-color: #2D6A4F !important; background: rgba(45,106,79,0.05) !important; }
        #fieldCanvas .field-card:hover { border-color: #2D6A4F; }
        .tab-btn { transition: all 0.15s; }
        .tab-btn.active { background: linear-gradient(135deg, #0B3D2E, #2D6A4F); color: white; }
        .palette-item {
            background: white; border: 1px solid #e5e7eb; border-radius: 10px;
            padding: 8px 12px; cursor: pointer; font-size: 12px; color: #374151;
            display: flex; align-items: center; gap: 6px; transition: all 0.15s;
        }
        .palette-item:hover { background: rgba(45,106,79,0.05); border-color: #2D6A4F; color: #2D6A4F; }
    </style>

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
                <a href="{{ route('waivers.index') }}" class="hover:text-[#2D6A4F] transition">Documents</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Edit</span>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Edit Waiver</h1>
            <p class="text-gray-500 text-sm mt-1">Update your waiver fields and settings</p>
        </div>
        <a href="{{ route('waivers.index') }}" 
           class="text-sm font-medium text-gray-600 hover:text-gray-900 px-4 py-2.5 rounded-xl hover:bg-gray-100 transition flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Back
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6">
            @foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach
        </div>
    @endif

    <form action="{{ route('waivers.update', $waiver) }}" method="POST" id="waiverForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Waiver Details --}}
        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-6">
            <h2 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                Waiver Details
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Waiver Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title', $waiver->title) }}"
    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:ring-2 focus:ring-[#2D6A4F]/10 transition">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Replace PDF Document
                        @if($waiver->pdf_document)
                            <span class="text-green-600 text-xs ml-1">(PDF attached)</span>
                        @endif
                    </label>
                    <input type="file" name="pdf_document" accept=".pdf"
                        class="w-full bg-gray-50 border border-gray-200 text-sm rounded-xl px-4 py-2 focus:outline-none focus:border-[#2D6A4F] transition
                               file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-[#2D6A4F] hover:file:bg-green-100">
                    @if($waiver->pdf_document)
                        <p class="text-xs text-gray-400 mt-1">
                            Current: <a href="{{ Storage::url($waiver->pdf_document) }}" target="_blank" class="text-[#2D6A4F] hover:underline">View PDF</a>
                            — Upload a new file to replace it
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Form Builder --}}
        <div class="bg-white border border-gray-100 shadow-sm rounded-2xl p-6 mb-6">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="font-semibold text-gray-900 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Form Fields
                    </h2>
                    <p class="text-gray-500 text-xs mt-0.5">Click or drag field types to build your form</p>
                </div>
                <div id="fieldCount" class="bg-green-50 border border-green-200 text-[#2D6A4F] text-xs px-3 py-1 rounded-full font-medium">0 fields</div>
            </div>

            <div class="flex flex-col lg:flex-row gap-4 lg:gap-5 min-h-[34rem]">
                {{-- Left: Field Palette with Tabs --}}
                <div class="w-full lg:w-56 flex-shrink-0 lg:h-[34rem] overflow-y-auto border border-gray-100 rounded-xl bg-white p-3">
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
                <div class="flex-1 min-h-[24rem] lg:h-[34rem] overflow-y-auto border-2 border-dashed border-gray-200 rounded-xl p-3 sm:p-4 bg-gray-50/50 transition" id="fieldCanvas">
                    <div id="canvasEmpty" class="flex flex-col items-center justify-center h-full text-center">
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
                <svg class="w-4 h-4 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </h2>
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="require_signature" value="1"
                        {{ $waiver->require_signature ? 'checked' : '' }}
                        class="accent-[#2D6A4F] w-4 h-4">
                    <div>
                        <div class="text-gray-900 text-sm font-medium">Require Client Signature</div>
                        <div class="text-gray-500 text-xs">Client must sign before submitting</div>
                    </div>
                </label>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <button type="submit"
                class="text-white font-semibold px-6 py-3 rounded-xl text-sm transition shadow-sm hover:shadow-md hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2"
                style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Update Waiver
            </button>
            <a href="{{ route('waivers.index') }}"
                class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 font-semibold px-6 py-3 rounded-xl text-sm transition">
                Cancel
            </a>
        </div>

    </form>

@endsection

{{-- Pass existing fields to JS --}}
<script>
    var existingFields = {!! json_encode($waiver->fields ?? []) !!};
</script>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
var fields  = [];
var sortable = null;
var dragType = null;

var typeLabels = {
    fullname:'Full Name', text:'Text Input', textarea:'Textarea',
    email:'Email', password:'Password', number:'Number',
    phone:'Phone Number', url:'URL/Website',
    date:'Date Picker', time:'Time Picker', datetime:'Date & Time', dob:'Date of Birth',
    select:'Dropdown', radio:'Radio Buttons', checkbox:'Checkboxes', yesno:'Yes / No',
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
    blue:   'bg-green-50 border-green-200 text-[#2D6A4F]',
    green:  'bg-green-50 border-green-200 text-green-700',
    purple: 'bg-purple-50 border-purple-200 text-purple-700',
    orange: 'bg-orange-50 border-orange-200 text-orange-700',
    pink:   'bg-pink-50 border-pink-200 text-pink-700',
    yellow: 'bg-yellow-50 border-yellow-200 text-yellow-700',
    teal:   'bg-green-50 border-green-200 text-[#2D6A4F]',
    gray:   'bg-gray-100 border-gray-200 text-gray-600'
};

// ── Load existing fields ──
function loadExistingFields() {
    if (existingFields && existingFields.length > 0) {
        existingFields.forEach(function(f) {
            fields.push({
                id:          f.id || ('f_' + Date.now() + '_' + Math.random().toString(36).substr(2,5)),
                type:        f.type,
                label:       f.label,
                required:    f.required || false,
                options:     f.options || [],
                placeholder: f.placeholder || '',
                min:         f.min || '',
                max:         f.max || '',
                step:        f.step || ''
            });
        });
        renderCanvas();
        updateJson();
        updateFieldCount();
    }
}

// ── Add Field ──
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
        min: '', max: '', step: ''
    };
    fields.push(newField);
    renderCanvas(newField.id);
    updateJson();
    updateFieldCount();
}

// ── Render Canvas ──
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
            extra += '<div class="mt-2"><label class="text-xs text-gray-500 font-medium">Options (comma separated)</label><input type="text" value="' + field.options.join(', ') + '" oninput="updateOptions(\'' + field.id + '\', this.value)" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-[#2D6A4F] transition" placeholder="Option 1, Option 2, Option 3"></div>';
        }
        if (field.type === 'yesno') {
            extra += '<div class="mt-2 flex gap-2"><span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-medium">Yes</span><span class="bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-medium">No</span></div>';
        }
        if (field.type === 'rating') {
            extra += '<div class="mt-2"><label class="text-xs text-gray-500 font-medium">Max Stars</label><input type="number" value="' + (field.max || 5) + '" min="1" max="10" oninput="updateMax(\'' + field.id + '\', this.value)" class="w-20 bg-gray-50 border border-gray-200 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-[#2D6A4F] transition"><div class="text-yellow-400 text-sm mt-1">⭐⭐⭐⭐⭐</div></div>';
        }
        if (field.type === 'signature') {
            extra += '<div class="mt-2 bg-gray-50 border-2 border-dashed border-gray-200 rounded-xl p-3 text-center text-xs text-gray-400">✍️ Signature box will appear here</div>';
        }
        if (field.type === 'pdf_upload') {
            extra += '<div class="mt-2 bg-orange-50 border border-orange-200 rounded-xl p-3 text-xs text-orange-600 flex items-center gap-2"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>Client can upload a PDF document here</div>';
        }
        if (field.type === 'divider') {
            extra += '<div class="mt-2 border-t-2 border-gray-200 w-full"></div>';
        }
        if (!['heading','paragraph','divider','yesno','rating','signature','pdf_upload','image','file','color'].includes(field.type)) {
            extra += '<div class="mt-2"><label class="text-xs text-gray-500 font-medium">Placeholder</label><input type="text" value="' + (field.placeholder || '') + '" oninput="updatePlaceholder(\'' + field.id + '\', this.value)" onkeydown="handleFieldEnter(event,\'' + field.id + '\')" class="w-full bg-gray-50 border border-gray-200 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-[#2D6A4F] transition" placeholder="Enter placeholder text..."></div>';
        }

        card.innerHTML =
            '<div class="text-gray-300 cursor-grab text-base pt-0.5 drag-handle select-none leading-none">⠿</div>' +
            '<div class="flex-1 min-w-0">' +
                '<div class="flex items-center gap-2 mb-2 flex-wrap">' +
                    '<span class="text-xs border px-2 py-0.5 rounded-full font-medium ' + badge + '">' + typeIcons[field.type] + ' ' + typeLabels[field.type] + '</span>' +
                    '<label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">' +
                        '<input type="checkbox" ' + (field.required ? 'checked' : '') + ' onchange="toggleRequired(\'' + field.id + '\', this.checked)" class="accent-[#2D6A4F] w-3.5 h-3.5"> Required' +
                    '</label>' +
                '</div>' +
                (field.type !== 'divider' ? '<input type="text" class="w-full bg-transparent border-b border-gray-200 text-gray-900 text-sm py-1 focus:outline-none focus:border-[#2D6A4F] transition font-medium" value="' + field.label.replace(/"/g, '&quot;') + '" placeholder="Field label..." oninput="updateLabel(\'' + field.id + '\', this.value)" onkeydown="handleFieldEnter(event,\'' + field.id + '\')">' : '') +
                extra +
            '</div>' +
            '<div class="flex flex-col gap-1">' +
                '<button type="button" onclick="moveField(\'' + field.id + '\',-1)" class="text-gray-300 hover:text-[#2D6A4F] transition text-xs" title="Move Up">▲</button>' +
                '<button type="button" onclick="moveField(\'' + field.id + '\',1)" class="text-gray-300 hover:text-[#2D6A4F] transition text-xs" title="Move Down">▼</button>' +
            '</div>' +
            '<button type="button" onclick="deleteField(\'' + field.id + '\')" class="text-gray-300 hover:text-red-500 transition text-lg leading-none">✕</button>';

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
function updateMax(id, v)         { var f=fields.find(f=>f.id===id); if(f){f.max=v; updateJson();} }
function updateOptions(id, v) {
    var f=fields.find(f=>f.id===id);
    if(f){ f.options=v.split(',').map(o=>o.trim()); updateJson(); }
}
function deleteField(id) {
    fields = fields.filter(f=>f.id!==id);
    renderCanvas(); updateJson(); updateFieldCount();
}
function moveField(id, dir) {
    var i = fields.findIndex(f=>f.id===id);
    var ni = i + dir;
    if (ni < 0 || ni >= fields.length) return;
    var tmp = fields[i]; fields[i] = fields[ni]; fields[ni] = tmp;
    renderCanvas(); updateJson();
}
function handleFieldEnter(e, currentId) {
    if (e.key !== 'Enter') return;
    e.preventDefault();
    var i = fields.findIndex(f=>f.id===currentId);
    if (i !== -1 && i < fields.length-1) {
        var nextCard = document.querySelector('.field-card[data-id="'+fields[i+1].id+'"]');
        if (nextCard) {
            var ni = nextCard.querySelector('input[oninput*="updateLabel"]');
            if (ni) { ni.focus(); ni.select(); }
        }
    }
}
function updateFieldCount() {
    var b = document.getElementById('fieldCount');
    b.textContent = fields.length + (fields.length===1?' field':' fields');
    b.className = fields.length > 0
        ? 'bg-green-50 border border-green-200 text-[#2D6A4F] text-xs px-3 py-1 rounded-full'
        : 'bg-gray-100 border border-gray-200 text-gray-500 text-xs px-3 py-1 rounded-full';
}
function updateJson() {
    document.getElementById('fieldsJson').value = JSON.stringify(fields);
}

// ── Tabs ──
document.querySelectorAll('.tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        document.querySelectorAll('.tab-btn').forEach(function(b) {
            b.classList.remove('active'); b.classList.add('text-gray-600');
        });
        this.classList.add('active'); this.classList.remove('text-gray-600');
        document.querySelectorAll('.tab-content').forEach(function(c){ c.classList.add('hidden'); });
        document.getElementById('tab-' + this.dataset.tab).classList.remove('hidden');
    });
});

// ── Palette Events ──
document.querySelectorAll('.palette-item').forEach(function(item) {
    item.addEventListener('click', function() { addField(this.getAttribute('data-type')); });
    item.addEventListener('dragstart', function() { dragType = this.getAttribute('data-type'); });
    item.addEventListener('dragend',   function() { dragType = null; });
});

var canvas = document.getElementById('fieldCanvas');
canvas.addEventListener('dragover',  function(e){ e.preventDefault(); canvas.classList.add('drag-over'); });
canvas.addEventListener('dragleave', function()  { canvas.classList.remove('drag-over'); });
canvas.addEventListener('drop',      function(e){
    e.preventDefault(); canvas.classList.remove('drag-over');
    if (dragType) { addField(dragType); dragType = null; }
});

// ── Submit ──
document.getElementById('waiverForm').addEventListener('submit', function(e) {
    updateJson(); // still required — builds the hidden 'fields' input before submit
});

// ── Load existing fields on page load ──
loadExistingFields();
</script>
@endpush