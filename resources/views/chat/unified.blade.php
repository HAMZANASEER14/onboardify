<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chats</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background: #f9fafb; }

        #app {
            display: flex;
            height: 100vh;
            max-width: 1100px;
            margin: 0 auto;
            background: #fff;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }

        /* ── Sidebar ── */
        #sidebar {
            width: 320px;
            border-right: 1px solid #e5e7eb;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }

        #sidebar-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        #sidebar-header h2 { font-size: 18px; font-weight: 700; }
        #sidebar-header a {
            font-size: 13px; color: #3b82f6;
            text-decoration: none; font-weight: 500;
        }

        #search-box {
            padding: 10px 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        #search-box input {
            width: 100%;
            padding: 8px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            font-size: 13px;
            outline: none;
            background: #f9fafb;
        }
        #search-box input:focus { border-color: #3b82f6; background: #fff; }

        #chat-list { flex: 1; overflow-y: auto; }

        .chat-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            text-decoration: none;
            color: inherit;
            transition: background 0.15s;
        }
        .chat-item:hover { background: #f9fafb; }
        .chat-item.active { background: #eff6ff; border-left: 3px solid #3b82f6; }

        .chat-avatar {
            width: 46px; height: 46px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; font-weight: 700; flex-shrink: 0;
            overflow: hidden;
        }
        .chat-avatar.personal { background: #dbeafe; color: #1d4ed8; }
        .chat-avatar.group    { background: #fef3c7; color: #92400e; }
        .chat-avatar img { width: 100%; height: 100%; object-fit: cover; }

        .chat-info { flex: 1; min-width: 0; }
        .chat-info-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 3px;
        }
        .chat-name { font-weight: 600; font-size: 14px; }
        .chat-time { font-size: 11px; color: #9ca3af; flex-shrink: 0; }
        .chat-last {
            font-size: 13px; color: #6b7280;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .chat-type-badge {
            font-size: 10px; padding: 2px 6px; border-radius: 10px;
            font-weight: 600; margin-left: 6px;
        }
        .badge-personal { background: #dbeafe; color: #1d4ed8; }
        .badge-group    { background: #fef3c7; color: #92400e; }

        /* ── Main Panel ── */
        #main-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            color: #9ca3af;
        }
        #main-panel .empty-icon { font-size: 64px; margin-bottom: 16px; }
        #main-panel p { font-size: 15px; }

        #chat-frame {
            flex: 1;
            border: none;
            width: 100%;
            height: 100%;
            display: none;
        }
    </style>
</head>
<body>

<div id="app">

    {{-- ── Sidebar ── --}}
    <div id="sidebar">

        <div id="sidebar-header">
            <h2>💬 Chats</h2>
            <a href="{{ route('groups.create') }}">✏️ New Group</a>
        </div>

        <div id="search-box">
            <input type="text" id="search-input" placeholder="🔍  Search chats...">
        </div>

        <div id="chat-list">
            @forelse($chats as $chat)
            <a href="{{ $chat['route'] }}"
               class="chat-item"
               data-name="{{ strtolower($chat['name']) }}"
               onclick="openChat(event, '{{ $chat['route'] }}')">

                <div class="chat-avatar {{ $chat['type'] }}">
                    @if($chat['type'] === 'group' && $chat['avatar'])
                        <img src="{{ asset('storage/' . $chat['avatar']) }}">
                    @else
                        {{ $chat['initial'] }}
                    @endif
                </div>

                <div class="chat-info">
                    <div class="chat-info-top">
                        <span class="chat-name">
                            {{ $chat['name'] }}
                            <span class="chat-type-badge {{ $chat['type'] === 'group' ? 'badge-group' : 'badge-personal' }}">
                                {{ $chat['type'] === 'group' ? '👥' : '👤' }}
                            </span>
                        </span>
                        <span class="chat-time">
                            {{ $chat['last_time'] ? $chat['last_time']->diffForHumans(null, true) : '' }}
                        </span>
                    </div>
                    <div class="chat-last">{{ $chat['last_msg'] }}</div>
                </div>

            </a>
            @empty
            <div style="text-align:center;padding:40px 20px;color:#9ca3af">
                <div style="font-size:40px;margin-bottom:12px">💬</div>
                <p style="font-size:14px">No chats yet</p>
                <a href="{{ route('chat.index') }}"
                   style="display:inline-block;margin-top:12px;font-size:13px;color:#3b82f6">
                    Start a personal chat →
                </a>
            </div>
            @endforelse
        </div>

    </div>

    {{-- ── Main Panel ── --}}
    <div id="main-panel">
        <div class="empty-icon">💬</div>
        <p>Select a chat to start messaging</p>
    </div>

    {{-- Chat loads here in iframe --}}
    <iframe id="chat-frame" src="" frameborder="0"></iframe>

</div>

<script>
    // Open chat in iframe
    function openChat(e, url) {
        e.preventDefault();

        // Set active state
        document.querySelectorAll('.chat-item').forEach(el => el.classList.remove('active'));
        e.currentTarget.classList.add('active');

        // Show iframe, hide empty state
        document.getElementById('main-panel').style.display = 'none';
        const frame = document.getElementById('chat-frame');
        frame.style.display = 'block';
        frame.src = url;
    }

    // Search filter
    document.getElementById('search-input').addEventListener('input', function () {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.chat-item').forEach(item => {
            const name = item.dataset.name;
            item.style.display = name.includes(query) ? 'flex' : 'none';
        });
    });
</script>

</body>
</html>