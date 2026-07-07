<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat with {{ $user->name }}</title>

    @vite('resources/css/app.css')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; background: #f9fafb; margin: 0; padding: 0; height: 100vh; overflow: hidden; }
        #messages { scroll-behavior: smooth; }
        #messages::-webkit-scrollbar { width: 4px; }
        #messages::-webkit-scrollbar-thumb { background: #e5e7eb; border-radius: 4px; }
        .bubble-mine { border-radius: 18px 18px 4px 18px; background: linear-gradient(135deg, #0B3D2E, #2D6A4F); }
        .bubble-theirs { border-radius: 18px 18px 18px 4px; }
        #msg-input::-webkit-scrollbar { display: none; }
        .message-enter { animation: fadeUp 0.2s ease; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>

<body class="flex flex-col">

    <div class="flex flex-col h-screen bg-white">

        {{-- Chat Header --}}
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between bg-white shrink-0 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <div class="w-11 h-11 rounded-full text-white flex items-center justify-center font-semibold text-sm" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    {{-- Online dot — starts gray, Pusher presence sets it green if online --}}
                    <span
                        id="header-online-dot"
                        class="absolute bottom-0 right-0 w-3 h-3 bg-gray-300 border-2 border-white rounded-full transition-colors duration-300">
                    </span>
                </div>
                <div>
                    <div class="font-bold text-gray-900">{{ $user->name }}</div>
                    {{-- Status text updates with online state --}}
                    <div id="header-status-text" class="text-gray-400 text-xs">Offline</div>
                </div>
            </div>
        </div>

        {{-- Messages Area --}}
        <div id="messages" class="flex-1 overflow-y-auto px-6 py-5 space-y-4 bg-gray-50/50">

            @php $lastDate = null; @endphp

            @foreach ($messages as $msg)
                @php
                    $msgDate   = $msg->created_at->format('Y-m-d');
                    $isMine    = $msg->user_id === auth()->id();
                    $today     = now()->format('Y-m-d');
                    $yesterday = now()->subDay()->format('Y-m-d');
                @endphp

                {{-- Date Separator --}}
                @if($lastDate !== $msgDate)
                    <div class="flex items-center gap-3 my-4">
                        <div class="flex-1 h-px bg-gray-200"></div>
                        <span class="text-gray-400 text-xs font-medium px-2">
                            @if($msgDate === $today) Today
                            @elseif($msgDate === $yesterday) Yesterday
                            @else {{ $msg->created_at->format('M d, Y') }}
                            @endif
                        </span>
                        <div class="flex-1 h-px bg-gray-200"></div>
                    </div>
                    @php $lastDate = $msgDate; @endphp
                @endif

                {{-- Message Bubble --}}
                <div class="flex items-end gap-2 {{ $isMine ? 'flex-row-reverse' : '' }} message-enter">
                    @if(!$isMine)
                        <div class="w-8 h-8 rounded-full text-white flex items-center justify-center font-semibold text-xs shrink-0 mb-1"
                             style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif

                    <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} max-w-xs lg:max-w-md">
                        @if(!$isMine)
                            <span class="text-xs font-medium text-gray-600 mb-1 ml-1">
                                {{ $user->name }}
                                <span class="text-gray-400 font-normal ml-1">{{ $msg->created_at->format('h:i A') }}</span>
                            </span>
                        @endif

                        @if($msg->content)
                            <div class="px-4 py-2.5 text-sm leading-relaxed break-words
                                {{ $isMine ? 'text-white bubble-mine' : 'bg-white text-gray-800 bubble-theirs border border-gray-100 shadow-sm' }}">
                                {{ $msg->content }}
                            </div>
                        @endif

                        @if($msg->file_path)
                            @php $isImage = str_starts_with($msg->file_type ?? '', 'image/'); @endphp
                            @if($isImage)
                                <img src="{{ asset('storage/' . $msg->file_path) }}"
                                     class="mt-1 rounded-xl max-w-xs max-h-48 object-cover cursor-pointer"
                                     onclick="window.open('{{ asset('storage/' . $msg->file_path) }}')">
                            @else
                                <a href="{{ asset('storage/' . $msg->file_path) }}" target="_blank"
                                   class="mt-1 flex items-center gap-2 px-3 py-2 rounded-xl text-xs transition
                                       {{ $isMine ? 'bg-white/20 hover:bg-white/30 text-white' : 'bg-white border border-gray-100 text-gray-700 hover:bg-gray-50' }}">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $msg->file_name }}
                                </a>
                            @endif
                        @endif

                        @if($isMine)
                            <span class="text-[10px] text-gray-400 mt-1 mr-1">{{ $msg->created_at->format('h:i A') }}</span>
                        @endif
                    </div>
                </div>
            @endforeach

            @if($messages->isEmpty())
                <div class="flex flex-col items-center justify-center h-full py-20 text-center">
                    <div class="w-16 h-16 rounded-3xl flex items-center justify-center mb-4"
                         style="background: linear-gradient(135deg, rgba(11,61,46,0.1), rgba(45,106,79,0.1))">
                        <svg class="w-8 h-8" style="color: #2D6A4F" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                    </div>
                    <p class="text-gray-500 text-sm font-medium">No messages yet</p>
                    <p class="text-gray-400 text-xs mt-1">Say hi to {{ $user->name }}!</p>
                </div>
            @endif

        </div>

        {{-- Input Area --}}
        <div class="px-4 py-3 border-t border-gray-100 bg-white shrink-0">
           <div id="file-preview" class="hidden mb-2 px-1"></div>
            <div class="flex items-end gap-2 relative">
                {{-- Hidden file inputs per type --}}
<input type="file" id="file-input-image"    accept="image/*"                                    class="hidden" onchange="handleFileSelect(this)">
<input type="file" id="file-input-video"    accept="video/*"                                    class="hidden" onchange="handleFileSelect(this)">
<input type="file" id="file-input-document" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt" class="hidden" onchange="handleFileSelect(this)">
<input type="file" id="file-input-audio"    accept="audio/*"                                    class="hidden" onchange="handleFileSelect(this)">

{{-- File picker popup --}}
<div id="file-picker-menu" class="hidden absolute bottom-16 left-4 bg-white rounded-2xl shadow-xl border border-gray-100 p-2 z-50 w-52">
    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wider mb-1.5 px-2">Attach</p>

    <button type="button" onclick="triggerFileInput('file-input-image')"
        class="flex items-center gap-3 w-full px-3 py-2 rounded-xl hover:bg-purple-50 transition group">
        <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition shrink-0">
            <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Image / Gallery</span>
    </button>

    <button type="button" onclick="triggerFileInput('file-input-video')"
        class="flex items-center gap-3 w-full px-3 py-2 rounded-xl hover:bg-pink-50 transition group">
        <div class="w-8 h-8 rounded-full bg-pink-100 flex items-center justify-center group-hover:bg-pink-200 transition shrink-0">
            <svg class="w-4 h-4 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.277A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M3 8a2 2 0 012-2h8a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Video</span>
    </button>

    <button type="button" onclick="triggerFileInput('file-input-document')"
        class="flex items-center gap-3 w-full px-3 py-2 rounded-xl hover:bg-blue-50 transition group">
        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition shrink-0">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Document</span>
    </button>

    <button type="button" onclick="triggerFileInput('file-input-audio')"
        class="flex items-center gap-3 w-full px-3 py-2 rounded-xl hover:bg-green-50 transition group">
        <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center group-hover:bg-green-200 transition shrink-0">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"/>
            </svg>
        </div>
        <span class="text-sm font-medium text-gray-700">Audio</span>
    </button>
</div>

{{-- Attach button --}}
<button type="button" id="attach-btn"
    onclick="toggleFilePicker(event)"
    class="w-9 h-9 rounded-xl hover:bg-gray-100 flex items-center justify-center text-gray-400 hover:text-gray-600 transition shrink-0 mb-0.5">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
    </svg>
</button>
                <textarea id="msg-input" rows="1" placeholder="Type a message or attach a file..."
                    class="flex-1 px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-2xl text-sm focus:outline-none focus:border-[#2D6A4F] focus:bg-white focus:ring-2 focus:ring-[#2D6A4F]/10 transition resize-none"
                    style="min-height:42px;max-height:120px;"
                    onInput="this.style.height='42px';this.style.height=Math.min(this.scrollHeight,120)+'px'"></textarea>

                <button id="send-btn"
                    class="px-4 py-2.5 bg-[#2D6A4F] hover:bg-[#0B3D2E] text-white text-sm font-semibold rounded-2xl transition shrink-0">
                    Send
                </button>
            </div>
        </div>

    </div>

 <script>
    window.AUTH_USER_ID      = {{ auth()->id() }};
    window.CHAT_WITH_USER_ID = {{ $user->id }};

    window.CONVERSATION_ID   = {{ $conversation->id }}; 
</script>

    @vite('resources/js/app.js')
    @vite('resources/js/chat.js')

    <script>
        // ── Presence channel to track if this specific user is online ──
        // Joins the same 'presence-online' channel your index page uses
        if (window.Echo) {
            const dot        = document.getElementById('header-online-dot');
            const statusText = document.getElementById('header-status-text');
            const chatUserId = window.CHAT_WITH_USER_ID;

            window.Echo.join('presence-online')
                .here((users) => {
                    // Check if the person we're chatting with is already online
                    const isOnline = users.some(u => u.id === chatUserId);
                    setHeaderOnline(isOnline);
                })
                .joining((user) => {
                    if (user.id === chatUserId) setHeaderOnline(true);
                })
                .leaving((user) => {
                    if (user.id === chatUserId) setHeaderOnline(false);
                });

            function setHeaderOnline(isOnline) {
                dot.classList.toggle('bg-green-400', isOnline);
                dot.classList.toggle('bg-gray-300', !isOnline);
                statusText.textContent = isOnline ? 'Online' : 'Offline';
                statusText.classList.toggle('text-green-500', isOnline);
                statusText.classList.toggle('text-gray-400', !isOnline);
            }
        }
        function toggleFilePicker(e) {
    e.stopPropagation();
    document.getElementById('file-picker-menu').classList.toggle('hidden');
}

function triggerFileInput(inputId) {
    document.getElementById('file-picker-menu').classList.add('hidden');
    document.getElementById(inputId).click();
}

// Close picker when clicking outside
document.addEventListener('click', function (e) {
    const menu = document.getElementById('file-picker-menu');
    const btn  = document.getElementById('attach-btn');
    if (menu && !menu.contains(e.target) && e.target !== btn) {
        menu.classList.add('hidden');
    }
});
    </script>

</body>
</html>