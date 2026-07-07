<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign – {{ $waiver->title }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white border-b border-gray-200 shadow-sm px-6 py-4">
        <div class="max-w-2xl mx-auto flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <span class="font-bold text-lg text-gray-900 tracking-tight">Onboardify</span>
        </div>
    </header>

    <main class="flex-1 max-w-2xl mx-auto w-full px-4 sm:px-6 py-8">

        {{-- Title --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ $waiver->title }}</h1>
            <p class="text-gray-500 text-sm mt-1">
                Hi <strong class="text-gray-700">{{ optional($send->client)->name ?? 'there' }}</strong>, please review the document and sign below.
            </p>
        </div>

        {{-- ── PDF VIEWER (shown only if waiver has a PDF) ── --}}
        @if($waiver->pdf_document)
        <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm mb-6">
            <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span class="text-sm font-semibold text-gray-700">Document to Sign</span>
                </div>
                <a href="{{ asset('storage/' . $waiver->pdf_document) }}" target="_blank"
                   class="text-xs font-medium text-[#2D6A4F] hover:underline flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Open in new tab
                </a>
            </div>
            {{-- Embedded PDF viewer --}}
            <iframe src="{{ asset('storage/' . $waiver->pdf_document) }}#toolbar=0"
                    class="w-full"
                    style="height: 500px; border: none;">
            </iframe>
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('waivers.submit-sign', $send->token) }}" method="POST" id="sign-form" enctype="multipart/form-data">
            @csrf

            @foreach($fields as $field)
                <div class="bg-white border border-gray-200 rounded-2xl p-5 mb-4 shadow-sm">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
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
       class="..."
                                   class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition">
                            @break

                        @case('date')
                            <input type="date"
       name="responses[{{ $field['id'] }}]"
                                   class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition">
                            @break

                        @case('textarea')
                        @case('address')
                          <textarea name="responses[{{ $field['id'] }}]"
          rows="3"
          placeholder="{{ $field['placeholder'] ?? '' }}"
                                      class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition resize-none"></textarea>
                            @break

                        @case('select')
                           <select name="responses[{{ $field['id'] }}]"
                                    class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-[#2D6A4F] focus:bg-white transition">
                                <option value="">-- Select --</option>
                                @foreach($field['options'] as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            @break

                        @case('checkbox')
                        @case('yesno')
                            <div class="space-y-2">
                                @foreach($field['options'] as $option)
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="checkbox"
                                               name="responses[{{ $field['id'] }}][]"
                                               value="{{ $option }}"
                                               class="w-4 h-4 rounded border-gray-300 text-[#2D6A4F] focus:ring-[#2D6A4F]">
                                        <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                        @case('radio')
                            <div class="space-y-2">
                                @foreach($field['options'] as $option)
                                    <label class="flex items-center gap-2.5 cursor-pointer group">
                                        <input type="radio"
       name="responses[{{ $field['id'] }}]"
       value="{{ $option }}"
                                               class="w-4 h-4 border-gray-300 text-[#2D6A4F] focus:ring-[#2D6A4F]">
                                        <span class="text-sm text-gray-700 group-hover:text-gray-900">{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                            @break

                        @case('signature')
                            <p class="text-xs text-gray-400 mb-2">Draw your signature below</p>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl overflow-hidden bg-white">
                                <canvas id="sig_{{ $field['id'] }}"
                                        class="w-full"
                                        style="height:160px; touch-action:none; display:block;">
                                </canvas>
                            </div>
                            <input type="hidden"
                                   name="responses[{{ $field['id'] }}]"
                                   id="sigInput_{{ $field['id'] }}">
                            {{-- Also save as top-level signature field --}}
                            <input type="hidden" name="signature" id="mainSig_{{ $field['id'] }}">
                            <button type="button"
                                    onclick="clearSig('{{ $field['id'] }}')"
                                    class="mt-2 flex items-center gap-1.5 text-xs font-medium text-gray-400 hover:text-red-500 transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Clear
                            </button>
                            @break
                    @endswitch
                </div>
            @endforeach

            {{-- Agree checkbox --}}
            <div class="bg-green-50 border border-green-200 rounded-2xl p-5 mb-6">
                <label class="flex items-start gap-3 cursor-pointer">
                    <input type="checkbox" required class="w-4 h-4 mt-0.5 rounded border-gray-300 text-[#2D6A4F] focus:ring-[#2D6A4F]">
                    <span class="text-sm text-gray-700 leading-relaxed">
                        I have read and agree to the terms of this document. I confirm the information provided is accurate and my signature above is legally binding.
                    </span>
                </label>
            </div>

            <button type="submit"
                    class="w-full text-white font-semibold py-3.5 rounded-xl text-sm transition shadow-lg hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2"
                    style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
                Submit & Sign
            </button>
        </form>

        <p class="text-center text-xs text-gray-400 mt-8">
            Powered by <span class="font-semibold text-gray-500">Onboardify</span>
        </p>

    </main>

    <script>
        var signaturePads = {};

        document.querySelectorAll('canvas[id^="sig_"]').forEach(function(canvas) {
            var fieldId = canvas.id.replace('sig_', '');
            var pad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255,255,255)',
                penColor: 'rgb(0,0,0)'
            });
            signaturePads[fieldId] = pad;

            // Resize canvas properly
            function resizeCanvas() {
                var ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width  = canvas.offsetWidth  * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext('2d').scale(ratio, ratio);
                pad.clear();
            }
            resizeCanvas();
            window.addEventListener('resize', resizeCanvas);
        });

        function clearSig(id) {
            if (signaturePads[id]) signaturePads[id].clear();
        }
document.getElementById('sign-form').addEventListener('submit', function(e) {
    let hasSignature = false;

    Object.keys(signaturePads).forEach(function(id) {
        if (!signaturePads[id].isEmpty()) {
            hasSignature = true;
            var dataUrl = signaturePads[id].toDataURL('image/png');
            var respInput = document.getElementById('sigInput_' + id);
            if (respInput) respInput.value = dataUrl;
            var mainInput = document.getElementById('mainSig_' + id);
            if (mainInput) mainInput.value = dataUrl;
        }
    });

    if (Object.keys(signaturePads).length > 0 && !hasSignature) {
        e.preventDefault();
        alert('Please draw your signature before submitting.');
    }
});
    </script>

</body>
</html>