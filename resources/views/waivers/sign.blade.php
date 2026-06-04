<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Waiver</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<div class="bg-white border-b border-gray-200 shadow-sm px-6 py-4">
    <div class="flex items-center gap-3">
        <span class="text-2xl">📋</span>
        <span class="font-bold text-lg text-gray-900">Onboardify</span>
    </div>
</div>

<div class="max-w-2xl mx-auto px-6 py-8">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            {{ $waiver->title }}
        </h1>
        <p class="text-gray-500 text-sm mt-1">
           Hi <strong>{{ optional($send->client)->name }}</strong>
        </p>
    </div>

    <form action="{{ route('waiver.sign.save', $send->token) }}" method="POST">
        @csrf

        {{-- Render fields from JSON --}}
        @foreach($fields as $field)
            <div class="bg-white border border-gray-200 rounded-xl p-4 mb-4 shadow-sm">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    {{ $field['label'] }}
                    @if($field['required'])
                        <span class="text-red-500">*</span>
                    @endif
                </label>

                @switch($field['type'])
                    @case('fullname')
                    @case('text')
                    @case('email')
                    @case('phone')
                    @case('number')
                    @case('dob')
                        <input type="{{ in_array($field['type'], ['email','number']) ? $field['type'] : 'text' }}"
                               name="responses[{{ $field['id'] }}]"
                               placeholder="{{ $field['placeholder'] ?? '' }}"
                               {{ $field['required'] ? 'required' : '' }}
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
                        @break

                    @case('date')
                        <input type="date"
                               name="responses[{{ $field['id'] }}]"
                               {{ $field['required'] ? 'required' : '' }}
                               class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
                        @break

                    @case('textarea')
                    @case('address')
                        <textarea name="responses[{{ $field['id'] }}]"
                                  rows="3"
                                  placeholder="{{ $field['placeholder'] ?? '' }}"
                                  {{ $field['required'] ? 'required' : '' }}
                                  class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition resize-none"></textarea>
                        @break

                    @case('select')
                        <select name="responses[{{ $field['id'] }}]"
                                {{ $field['required'] ? 'required' : '' }}
                                class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
                            <option value="">-- Select --</option>
                            @foreach($field['options'] as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                        @break

                    @case('checkbox')
                    @case('yesno')
                        @foreach($field['options'] as $option)
                            <label class="flex items-center gap-2 mb-1 text-sm text-gray-700">
                                <input type="checkbox"
                                       name="responses[{{ $field['id'] }}][]"
                                       value="{{ $option }}"
                                       class="accent-blue-500">
                                {{ $option }}
                            </label>
                        @endforeach
                        @break

                    @case('radio')
                        @foreach($field['options'] as $option)
                            <label class="flex items-center gap-2 mb-1 text-sm text-gray-700">
                                <input type="radio"
                                       name="responses[{{ $field['id'] }}]"
                                       value="{{ $option }}"
                                       {{ $field['required'] ? 'required' : '' }}
                                       class="accent-blue-500">
                                {{ $option }}
                            </label>
                        @endforeach
                        @break

                    @case('signature')
                        <div class="border-2 border-dashed border-gray-300 rounded-xl overflow-hidden">
                            <canvas id="sig_{{ $field['id'] }}"
                                    class="w-full"
                                    style="height:150px; touch-action:none">
                            </canvas>
                        </div>
                        <input type="hidden"
                               name="responses[{{ $field['id'] }}]"
                               id="sigInput_{{ $field['id'] }}">
                        <button type="button"
                                onclick="clearSig('{{ $field['id'] }}')"
                                class="mt-2 text-xs text-gray-500 hover:text-red-500 transition">
                            🗑 Clear Signature
                        </button>
                        @break

                @endswitch
            </div>
        @endforeach

        {{-- Agree checkbox --}}
        <div class="bg-white border border-gray-200 rounded-xl p-4 mb-6 shadow-sm">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" required class="accent-blue-500 w-4 h-4">
                <span class="text-sm text-gray-700">
                    I agree to the terms of this waiver
                </span>
            </label>
        </div>

        <button type="submit"
                class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl text-sm transition">
            ✅ Submit & Sign Waiver
        </button>

    </form>
</div>

<script>
    var signaturePads = {};

    document.querySelectorAll('canvas[id^="sig_"]').forEach(function(canvas) {
        var id = canvas.id.replace('sig_', '');
        signaturePads[id] = new SignaturePad(canvas);
    });

    function clearSig(id) {
        if (signaturePads[id]) signaturePads[id].clear();
    }

    document.querySelector('form').addEventListener('submit', function(e) {
        Object.keys(signaturePads).forEach(function(id) {
            if (!signaturePads[id].isEmpty()) {
                document.getElementById('sigInput_' + id).value
                    = signaturePads[id].toDataURL();
            }
        });
    });
</script>

</body>
</html>