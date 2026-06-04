<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $group->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; }

        #chat-wrap {
            display: flex;
            height: 100vh;
            max-width: 900px;
            margin: 0 auto;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            position: relative;
        }

        #chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        #chat-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        #chat-header a { font-size:13px; color:#3b82f6; text-decoration:none }
        #chat-header .avatar {
            width:38px; height:38px; border-radius:50%;
            background:#dbeafe; display:flex; align-items:center;
            justify-content:center; font-size:16px; overflow:hidden; flex-shrink:0;
        }
        #chat-header .avatar img { width:100%; height:100%; object-fit:cover }

        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: #f9fafb;
        }

        .message { display:flex; flex-direction:column; max-width:70% }
        .message.mine  { align-self:flex-end;  align-items:flex-end }
        .message.theirs{ align-self:flex-start; align-items:flex-start }

        .sender-name { font-size:11px; color:#6b7280; margin-bottom:3px }

        .bubble {
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.5;
            word-break: break-word;
        }
        .mine   .bubble { background:#3b82f6; color:#fff; border-bottom-right-radius:4px }
        .theirs .bubble { background:#fff; color:#111; border-bottom-left-radius:4px;
                          border:1px solid #e5e7eb }

        .time { font-size:11px; color:#9ca3af; margin-top:4px }

        #input-area {
            display: flex;
            gap: 10px;
            padding: 14px 16px;
            border-top: 1px solid #e5e7eb;
            background: #fff;
        }
        #msg-input {
            flex: 1;
            padding: 10px 16px;
            border: 1px solid #d1d5db;
            border-radius: 22px;
            font-size: 14px;
            resize: none;
            outline: none;
            font-family: sans-serif;
        }
        #msg-input:focus { border-color:#3b82f6 }
        #send-btn {
            padding: 10px 22px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 22px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }
        #send-btn:hover { background:#2563eb }

        .member-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 6px;
            border-radius: 8px;
        }
        .member-row:hover { background:#f9fafb }
        .member-avatar {
            width:32px; height:32px; border-radius:50%;
            background:#dbeafe; display:flex; align-items:center;
            justify-content:center; font-size:13px; font-weight:700;
            color:#1d4ed8; flex-shrink:0;
        }
        .member-name { font-size:13px; font-weight:500 }
        .member-role { font-size:11px; color:#f59e0b; font-weight:600 }
        .remove-btn {
            background:none; border:none; color:#d1d5db;
            cursor:pointer; font-size:14px; padding:2px 4px;
        }
        .remove-btn:hover { color:#ef4444 }
        .btn-add {
            width:100%; padding:8px; background:#3b82f6; color:#fff;
            border:none; border-radius:8px; font-size:13px;
            font-weight:600; cursor:pointer; margin-bottom:8px;
        }
        .btn-add:hover { background:#2563eb }
        .btn-delete {
            width:100%; padding:8px; background:#fff; color:#ef4444;
            border:1px solid #ef4444; border-radius:8px; font-size:13px;
            font-weight:600; cursor:pointer;
        }
        .btn-delete:hover { background:#fef2f2 }
    </style>
</head>
<body>

<div id="chat-wrap">

    {{-- ── Chat Main ── --}}
    <div id="chat-main">

        <div id="chat-header">
            <a href="{{ route('groups.index') }}">← Back</a>
            <div class="avatar">
                @if($group->avatar)
                    <img src="{{ asset('storage/'.$group->avatar) }}">
                @else
                    👥
                @endif
            </div>
            <div style="flex:1">
                <div style="font-weight:600;font-size:15px">{{ $group->name }}</div>
                <div style="font-size:11px;color:#9ca3af" id="online-count">
                    {{ $members->count() }} members
                </div>
            </div>

            {{-- Members toggle button inside header --}}
            <button onclick="toggleSidebar()"
                    style="margin-left:auto;background:#3b82f6;color:#fff;border:none;
                           border-radius:20px;padding:7px 14px;font-size:13px;
                           font-weight:500;cursor:pointer;flex-shrink:0">
                👥 Members
            </button>
        </div>

        <div id="messages">
            @foreach($messages as $msg)
            <div class="message {{ $msg->user_id === auth()->id() ? 'mine' : 'theirs' }}">
                @if($msg->user_id !== auth()->id())
                    <span class="sender-name">{{ $msg->user->name }}</span>
                @endif
                <div class="bubble">{{ $msg->message }}</div>
                <span class="time">{{ $msg->created_at->format('h:i A') }}</span>
            </div>
            @endforeach
        </div>

        <div id="input-area">
            <textarea id="msg-input" rows="1"
                      placeholder="Type a message..."></textarea>
            <button id="send-btn">Send</button>
        </div>

    </div> {{-- closes #chat-main --}}

    {{-- ── Members Sidebar Drawer ── --}}
    <div id="sidebar"
         style="display:none;position:absolute;top:0;right:0;
                width:260px;height:100%;background:#fff;
                border-left:1px solid #e5e7eb;
                flex-direction:column;overflow:hidden;
                box-shadow:-4px 0 12px rgba(0,0,0,0.08);z-index:99">

        <div style="padding:14px 16px;font-weight:600;font-size:13px;
                    color:#374151;border-bottom:1px solid #e5e7eb;
                    display:flex;justify-content:space-between;align-items:center">
            👥 Members
            <button onclick="toggleSidebar()"
                    style="background:none;border:none;cursor:pointer;
                           font-size:16px;color:#9ca3af">✕</button>
        </div>

        <div id="member-list" style="flex:1;overflow-y:auto;padding:10px">
            @foreach($members as $member)
            <div class="member-row">
                <div style="display:flex;align-items:center;gap:8px">
                    <div class="member-avatar">
                        {{ strtoupper(substr($member->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="member-name">{{ $member->name }}</div>
                        @if($member->pivot->role === 'admin')
                            <div class="member-role">Admin</div>
                        @endif
                    </div>
                </div>

                @if($isAdmin && $member->id !== auth()->id())
                <form action="{{ route('groups.members.remove', [$group, $member]) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" class="remove-btn" title="Remove">✕</button>
                </form>
                @endif
            </div>
            @endforeach
        </div>

        @if($isAdmin)
        <div style="padding:12px;border-top:1px solid #e5e7eb">
            <form action="{{ route('groups.members.add', $group) }}" method="POST">
                @csrf
                <select name="user_id"
                        style="width:100%;padding:8px 10px;border:1px solid #d1d5db;
                               border-radius:8px;font-size:13px;margin-bottom:8px;outline:none">
                    @foreach(\App\Models\User::whereNotIn('id', $members->pluck('id'))->get() as $u)
                        <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn-add">+ Add Member</button>
            </form>

            <form action="{{ route('groups.destroy', $group) }}" method="POST"
                  onsubmit="return confirm('Delete this group permanently?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-delete">🗑 Delete Group</button>
            </form>
        </div>
        @endif

    </div> {{-- closes #sidebar --}}

</div> {{-- closes #chat-wrap --}}

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const isOpen  = sidebar.style.display === 'flex';
        sidebar.style.display = isOpen ? 'none' : 'flex';
    }

    window.GROUP_ID  = {{ $group->id }};
    window.AUTH_ID   = {{ auth()->id() }};
    window.AUTH_NAME = @json(auth()->user()->name);
    window.STORE_URL = "{{ route('groups.messages.store', $group) }}";
</script>

@vite('resources/js/group-chat.js')

</body>
</html>