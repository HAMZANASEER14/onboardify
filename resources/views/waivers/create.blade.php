<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Waiver – Onboardify</title>
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Light Theme Quill Overrides */
        .ql-toolbar { background: #ffffff; border-color: #e5e7eb !important; border-radius: 8px 8px 0 0; }
        .ql-toolbar .ql-stroke { stroke: #4b5563; }
        .ql-toolbar .ql-fill { fill: #4b5563; }
        .ql-toolbar .ql-picker { color: #4b5563; }
        .ql-container { background: #ffffff; border-color: #e5e7eb !important; border-radius: 0 0 8px 8px; color: #111827; }
        .ql-editor.ql-blank::before { color: #9ca3af; }
        
        .palette-item { transition: all 0.15s; }
        .palette-item:active { transform: scale(0.97); }
        .field-card { transition: border-color 0.15s; }
        .tab-btn.active { background: #3b82f6; color: white; border-color: #3b82f6; }
        .drag-over { border-color: #3b82f6 !important; background: rgba(59,130,246,0.05) !important; }
        #fieldCanvas .field-card:hover { border-color: #3b82f6; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen">

{{-- Top Bar --}}
<div class="bg-white border-b border-gray-200 shadow-sm px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="text-2xl">📋</span>
        <span class="font-bold text-lg text-gray-900">Onboardify</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500 text-sm">Create Waiver</span>
    </div>
    <a href="{{ route('waivers.index') }}"
        class="text-gray-500 hover:text-gray-900 text-sm transition">← Back to Waivers</a>
</div>

<div class="max-w-6xl mx-auto px-6 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">🧾 Create New Waiver</h1>
        <p class="text-gray-500 text-sm mt-1">Build your waiver form by dragging or clicking field types</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

       <form action="{{ route('waivers.store') }}" method="POST" id="waiverForm">
        @csrf
        
     {{-- Waiver Title --}}
<div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 mb-6">
    <h2 class="text-gray-900 font-semibold mb-4">📝 Waiver Details</h2>

    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Waiver Title <span class="text-red-500">*</span>
        </label>
        <input type="text"
               name="title"
               value="{{ old('title') }}"
               placeholder="e.g. Liability Waiver, Membership Agreement..."
               required
               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
        @error('title')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

        {{-- Form Builder --}}
        <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 mb-6">

      

            {{-- Header with field count --}}
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-gray-900 font-semibold">🔧 Form Fields</h2>
                    <p class="text-gray-500 text-xs mt-0.5">Click or drag field types to build your form</p>
                </div>
                <div id="fieldCount"
                    class="bg-blue-50 border border-blue-200 text-blue-600 text-xs px-3 py-1 rounded-full">
                    0 fields
                </div>
            </div>

            <div class="flex gap-5">

                {{-- Left: Field Palette --}}
                <div class="w-52 flex-shrink-0">

                    {{-- Basic Fields --}}
                    <div id="tab-basic" class="tab-content space-y-1.5">
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="fullname">
                            <span>👤</span> Full Name
                        </div>
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="email">
                            <span>📧</span> Email
                        </div>
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="phone">
                            <span>📞</span> Phone
                        </div>
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="date">
                            <span>📅</span> Date
                        </div>
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="dob">
                            <span>🎂</span> Date of Birth
                        </div>
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="text">
                            <span>📝</span> Text Input
                        </div>
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="textarea">
                            <span>📄</span> Textarea
                        </div>
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="number">
                            <span>🔢</span> Number
                        </div>
                        <div draggable="true" class="palette-item bg-white hover:bg-gray-50 border border-gray-200 hover:border-blue-300 rounded-xl px-3 py-2.5 cursor-pointer text-sm flex items-center gap-2 text-gray-700" data-type="address">
                            <span>🏠</span> Address
                        </div>
                    </div>

                </div>

                {{-- Right: Drop Canvas --}}
              {{-- Right: Drop Canvas --}}
<div class="flex-1 h-96 overflow-y-auto border-2 border-dashed border-gray-300 rounded-xl p-4 bg-gray-50 transition"
    id="fieldCanvas">
                    <div id="canvasEmpty" class="flex flex-col items-center justify-center h-80 text-center">
                        <div class="text-5xl mb-3">📋</div>
                        <p class="text-gray-500 text-sm font-medium">No fields yet</p>
                        <p class="text-gray-400 text-xs mt-1">Click a field type on the left to add it here</p>
                    </div>
                </div>

            </div>
        </div>

        {{-- Hidden JSON --}}
        <input type="hidden" name="fields" id="fieldsJson">

        {{-- Settings --}}
        <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 mb-6">
            <h2 class="text-gray-900 font-semibold mb-4">⚙️ Settings</h2>
            <div class="space-y-3">
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="require_signature" value="1" checked
                        class="accent-blue-500 w-4 h-4">
                    <div>
                        <div class="text-gray-900 text-sm font-medium">Require Client Signature</div>
                        <div class="text-gray-500 text-xs">Client must sign before submitting</div>
                    </div>
                </label>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked
                        class="accent-blue-500 w-4 h-4">
                    <div>
                        <div class="text-gray-900 text-sm font-medium">Active</div>
                        <div class="text-gray-500 text-xs">Waiver is visible and can be sent to clients</div>
                    </div>
                </label>
            </div>
        </div>

        {{-- JSON Preview --}}
        <div class="mb-6">
            <button type="button"
                onclick="togglePreview()"
                class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-600 text-xs px-3 py-2 rounded-xl transition">
                👁 Preview Field JSON
            </button>
            <div id="jsonPreview"
                class="hidden mt-3 bg-gray-50 border border-gray-200 rounded-xl p-4 text-xs font-mono text-gray-700 max-h-48 overflow-y-auto">
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-6 py-3 rounded-xl text-sm transition">
                💾 Save Waiver
            </button>
            <a href="{{ route('waivers.index') }}"
                class="bg-white hover:bg-gray-50 border border-gray-300 text-gray-700 font-semibold px-6 py-3 rounded-xl text-sm transition">
                Cancel
            </a>
        </div>

    </form>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
<script>
// ── State ─────────────────────────────────────────────────────────
var fields  = [];
var sortable = null;

var typeLabels = {
    fullname: 'Full Name',    email: 'Email',
    phone: 'Phone',           date: 'Date',
    dob: 'Date of Birth',     text: 'Text Input',
    textarea: 'Textarea',     number: 'Number',
    address: 'Address',       select: 'Dropdown',
    checkbox: 'Checkbox',     radio: 'Radio',
    yesno: 'Yes / No',        signature: 'Signature',
    file: 'File Upload',      rating: 'Rating',
    heading: 'Heading',       paragraph: 'Paragraph',
    divider: 'Divider'
};

var typeIcons = {
    fullname: '👤', email: '📧', phone: '📞',
    date: '📅', dob: '🎂', text: '📝',
    textarea: '📄', number: '🔢', address: '🏠',
    select: '🔽', checkbox: '☑️', radio: '🔘',
    yesno: '✅', signature: '✍️', file: '📎',
    rating: '⭐', heading: '🔤', paragraph: '📃',
    divider: '➖'
};

var typeColors = {
    fullname: 'blue', email: 'blue', phone: 'blue',
    date: 'blue', dob: 'blue', text: 'blue',
    textarea: 'blue', number: 'blue', address: 'blue',
    select: 'purple', checkbox: 'purple', radio: 'purple',
    yesno: 'purple', signature: 'purple', file: 'purple',
    rating: 'purple', heading: 'gray', paragraph: 'gray',
    divider: 'gray'
};

// ── Add Field ─────────────────────────────────────────────────────
function addField(type) {
    var newField = {
        id:       'f_' + Date.now() + '_' + Math.random().toString(36).substr(2, 5),
        type:     type,
        label:    typeLabels[type],
        required: false,
        options:  (['select', 'radio', 'yesno'].includes(type))
                  ? (type === 'yesno' ? ['Yes', 'No'] : ['Option 1', 'Option 2'])
                  : [],
        placeholder: ''
    };
    fields.push(newField);
    renderCanvas(newField.id); // Pass ID to auto-focus
    updateJson();
    updateFieldCount();
}

// ── Render Canvas ─────────────────────────────────────────────────
function renderCanvas(newFieldId) {
    var canvas = document.getElementById('fieldCanvas');
    var empty  = document.getElementById('canvasEmpty');

    if (fields.length === 0) {
        if (sortable) { sortable.destroy(); sortable = null; }
        Array.from(canvas.querySelectorAll('.field-card')).forEach(function(el) { el.remove(); });
        empty.style.display = 'flex';
        return;
    }

    empty.style.display = 'none';
    Array.from(canvas.querySelectorAll('.field-card')).forEach(function(el) { el.remove(); });

    fields.forEach(function(field) {
        var card = document.createElement('div');
        card.className = 'field-card bg-white border border-gray-200 shadow-sm rounded-xl p-3 mb-2.5 flex items-start gap-3 hover:border-blue-300 transition';
        card.setAttribute('data-id', field.id);

        var extraHtml = '';

        if (['select', 'radio'].includes(field.type)) {
            extraHtml = `
                <div class="mt-2">
                    <label class="text-xs text-gray-500">Options (comma separated)</label>
                    <input type="text"
                        value="${field.options.join(', ')}"
                        oninput="updateOptions('${field.id}', this.value)"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-blue-500"
                        placeholder="Option 1, Option 2, Option 3">
                </div>`;
        }

        if (field.type === 'yesno') {
            extraHtml = `<div class="mt-1.5 flex gap-2">
                <span class="bg-green-100 text-green-700 text-xs px-2 py-0.5 rounded-full">Yes</span>
                <span class="bg-red-100 text-red-700 text-xs px-2 py-0.5 rounded-full">No</span>
            </div>`;
        }

        if (field.type === 'rating') {
            extraHtml = `<div class="mt-1.5 text-yellow-400 text-sm">⭐⭐⭐⭐⭐</div>`;
        }

        if (field.type === 'divider') {
            extraHtml = `<div class="mt-1.5 border-t border-gray-200 w-full"></div>`;
        }

        if (field.type === 'signature') {
            extraHtml = `<div class="mt-1.5 bg-gray-50 border border-dashed border-gray-300 rounded-lg p-2 text-center text-xs text-gray-400">
                Signature box will appear here
            </div>`;
        }

        if (!['heading', 'paragraph', 'divider'].includes(field.type)) {
            extraHtml += `
                <div class="mt-2">
                    <label class="text-xs text-gray-500">Placeholder text</label>
                    <input type="text"
                        value="${field.placeholder || ''}"
                        oninput="updatePlaceholder('${field.id}', this.value)"
                        onkeydown="handleFieldEnter(event, '${field.id}')"
                        class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-xs rounded-lg px-3 py-1.5 mt-1 focus:outline-none focus:border-blue-500"
                        placeholder="Enter placeholder...">
                </div>`;
        }

        var badgeColor = typeColors[field.type] === 'purple'
            ? 'bg-purple-50 border-purple-200 text-purple-600'
            : typeColors[field.type] === 'gray'
            ? 'bg-gray-100 border-gray-200 text-gray-500'
            : 'bg-blue-50 border-blue-200 text-blue-600';

        card.innerHTML = `
            <div class="text-gray-400 cursor-grab text-lg pt-0.5 drag-handle select-none">⠿</div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-2 flex-wrap">
                    <span class="text-xs border px-2 py-0.5 rounded-full ${badgeColor}">
                        ${typeIcons[field.type]} ${typeLabels[field.type]}
                    </span>
                    <label class="flex items-center gap-1.5 text-xs text-gray-500 cursor-pointer">
                        <input type="checkbox"
                            ${field.required ? 'checked' : ''}
                            onchange="toggleRequired('${field.id}', this.checked)"
                            class="accent-blue-500">
                        Required
                    </label>
                </div>
                <input type="text"
                    class="w-full bg-transparent border-b border-gray-200 text-gray-900 text-sm py-1 focus:outline-none focus:border-blue-500 transition"
                    value="${field.label}"
                    placeholder="Field label..."
                    oninput="updateLabel('${field.id}', this.value)"
                    onkeydown="handleFieldEnter(event, '${field.id}')">
                ${extraHtml}
            </div>
            <div class="flex flex-col gap-1 mr-2">
                <button type="button" onclick="moveField('${field.id}', -1)" class="text-gray-400 hover:text-blue-500 text-xs transition" title="Move Up">⬆️</button>
                <button type="button" onclick="moveField('${field.id}', 1)" class="text-gray-400 hover:text-blue-500 text-xs transition" title="Move Down">⬇️</button>
            </div>
            <button type="button"
                onclick="deleteField('${field.id}')"
                class="text-gray-400 hover:text-red-500 transition text-lg leading-none mt-0.5">✕</button>
        `;

        canvas.appendChild(card);
    });

    // Auto-focus the newly added field's label input
    if (newFieldId) {
        setTimeout(function() {
            var newCard = document.querySelector(`.field-card[data-id="${newFieldId}"]`);
            if (newCard) {
                var labelInput = newCard.querySelector('input[oninput*="updateLabel"]');
                if (labelInput) {
                    labelInput.focus();
                    labelInput.select(); // Highlights the default text so it's easy to overwrite
                }
            }
        }, 50);
    }

    if (sortable) { sortable.destroy(); }
    sortable = Sortable.create(canvas, {
        animation:  150,
        handle:     '.drag-handle',
        onEnd: function(evt) {
            var moved = fields.splice(evt.oldIndex, 1)[0];
            fields.splice(evt.newIndex, 0, moved);
            updateJson();
        }
    });
}

// ── Field Actions ─────────────────────────────────────────────────
function updateLabel(id, value) {
    var f = fields.find(function(f) { return f.id === id; });
    if (f) { f.label = value; updateJson(); }
}

function toggleRequired(id, value) {
    var f = fields.find(function(f) { return f.id === id; });
    if (f) { f.required = value; updateJson(); }
}

function updateOptions(id, value) {
    var f = fields.find(function(f) { return f.id === id; });
    if (f) {
        f.options = value.split(',').map(function(o) { return o.trim(); });
        updateJson();
    }
}

function updatePlaceholder(id, value) {
    var f = fields.find(function(f) { return f.id === id; });
    if (f) { f.placeholder = value; updateJson(); }
}

function deleteField(id) {
    fields = fields.filter(function(f) { return f.id !== id; });
    renderCanvas();
    updateJson();
    updateFieldCount();
}

// ── New UX Functions ──────────────────────────────────────────────
// Automatically jump to the next field when pressing "Enter"
function handleFieldEnter(event, currentId) {
    if (event.key === 'Enter') {
        event.preventDefault(); // Prevent form submission
        var currentIndex = fields.findIndex(function(f) { return f.id === currentId; });
        if (currentIndex !== -1 && currentIndex < fields.length - 1) {
            var nextId = fields[currentIndex + 1].id;
            var nextCard = document.querySelector(`.field-card[data-id="${nextId}"]`);
            if (nextCard) {
                var nextInput = nextCard.querySelector('input[oninput*="updateLabel"]');
                if (nextInput) {
                    nextInput.focus();
                    nextInput.select();
                }
            }
        }
    }
}

// Move fields up or down without dragging
function moveField(id, direction) {
    var index = fields.findIndex(function(f) { return f.id === id; });
    var newIndex = index + direction;
    
    if (newIndex < 0 || newIndex >= fields.length) return;
    
    // Swap fields in the array
    var temp = fields[index];
    fields[index] = fields[newIndex];
    fields[newIndex] = temp;
    
    renderCanvas();
    updateJson();
}

// ── Field Count Badge ─────────────────────────────────────────────
function updateFieldCount() {
    var badge = document.getElementById('fieldCount');
    badge.textContent = fields.length + (fields.length === 1 ? ' field' : ' fields');
    badge.className = fields.length > 0
        ? 'bg-blue-50 border border-blue-200 text-blue-600 text-xs px-3 py-1 rounded-full'
        : 'bg-gray-100 border border-gray-200 text-gray-500 text-xs px-3 py-1 rounded-full';
}

// ── JSON ──────────────────────────────────────────────────────────
function updateJson() {
    document.getElementById('fieldsJson').value = JSON.stringify(fields);
    var preview = document.getElementById('jsonPreview');
    if (!preview.classList.contains('hidden')) {
        preview.textContent = JSON.stringify(fields, null, 2);
    }
}

function togglePreview() {
    var preview = document.getElementById('jsonPreview');
    preview.classList.toggle('hidden');
    if (!preview.classList.contains('hidden')) {
        preview.textContent = JSON.stringify(fields, null, 2);
    }
}

// ── Palette Events ────────────────────────────────────────────────
var dragType = null;

document.querySelectorAll('.palette-item').forEach(function(item) {
    item.addEventListener('click', function() {
        addField(this.getAttribute('data-type'));
    });
    item.addEventListener('dragstart', function() {
        dragType = this.getAttribute('data-type');
    });
    item.addEventListener('dragend', function() {
        dragType = null;
    });
});

var canvas = document.getElementById('fieldCanvas');
canvas.addEventListener('dragover',  function(e) { e.preventDefault(); canvas.classList.add('drag-over'); });
canvas.addEventListener('dragleave', function()  { canvas.classList.remove('drag-over'); });
canvas.addEventListener('drop',      function(e) {
    e.preventDefault();
    canvas.classList.remove('drag-over');
    if (dragType) { addField(dragType); dragType = null; }
});

// ── Submit ────────────────────────────────────────────────────────
document.getElementById('waiverForm').addEventListener('submit', function(e) {
    if (fields.length === 0) {
        e.preventDefault();
        alert('⚠️ Please add at least one field to your waiver.');
        return;
    }
    updateJson();
});
</script>

</body>
</html>