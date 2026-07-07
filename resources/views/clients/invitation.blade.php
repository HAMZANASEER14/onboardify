<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation – {{ $client->name }}</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }
        
        /* Smart Print Styles: Only prints the QR and link, hides everything else */
        @media print {
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; box-shadow: none; border: none; }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 md:p-6">

<div id="print-area" class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 md:p-8 max-w-md w-full text-center">

    <!-- Success Icon -->
    <div class="w-16 h-16 rounded-2xl flex items-center justify-center mx-auto mb-5" style="background: linear-gradient(135deg, rgba(45, 106, 79, 0.1), rgba(82, 183, 136, 0.1))">
        <svg class="w-8 h-8 text-[#2D6A4F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
    </div>

    <h2 class="text-2xl font-bold text-gray-900 mb-2">Client Added Successfully!</h2>
    <p class="text-gray-500 text-sm mb-6">
        Share this link or QR code with <strong class="text-gray-700">{{ $client->name }}</strong> to start the onboarding process.
    </p>

    <!-- QR Code -->
    <div class="bg-gray-50 border border-gray-100 rounded-2xl p-5 mb-5 flex justify-center">
        {!! QrCode::size(180)->generate($link) !!}
    </div>

    <!-- Link -->
    <div class="flex items-center gap-2 bg-gray-50 border border-gray-200 rounded-xl px-4 py-3 mb-6">
        <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
        <span class="text-xs text-gray-600 truncate flex-1 text-left">{{ $link }}</span>
        <button id="copy-btn" onclick="copyLink()"
            class="text-xs font-bold text-[#2D6A4F] hover:text-[#0B3D2E] shrink-0 transition">
            Copy
        </button>
    </div>

    <!-- Actions -->
    <div class="flex gap-3">
        <a href="{{ route('clients.index') }}"
           class="flex-1 py-3 rounded-xl border border-gray-200 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
            Back to Clients
        </a>
        <button onclick="window.print()"
           class="flex-1 py-3 rounded-xl text-white text-sm font-semibold transition shadow-sm hover:shadow-md flex items-center justify-center gap-2" 
           style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Print QR
        </button>
    </div>

</div>

<script>
    function copyLink() {
        const link = "{{ $link }}";
        navigator.clipboard.writeText(link).then(() => {
            const btn = document.getElementById('copy-btn');
            btn.innerText = 'Copied!';
            btn.classList.remove('text-[#2D6A4F]');
            btn.classList.add('text-[#52b788]');
            
            // Revert back to "Copy" after 2 seconds
            setTimeout(() => {
                btn.innerText = 'Copy';
                btn.classList.remove('text-[#52b788]');
                btn.classList.add('text-[#2D6A4F]');
            }, 2000);
        });
    }
</script>

</body>
</html>