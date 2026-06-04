<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat with {{ $user->name }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; }
        #chat-wrap {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 640px;
            margin: 0 auto;
            border-left: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }
        #chat-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e5e7eb;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        #chat-header a {
            font-size: 13px;
            color: #3b82f6;
            text-decoration: none;
        }
        #messages {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .message {
            display: flex;
            flex-direction: column;
            max-width: 70%;
        }
        .message.mine {
            align-self: flex-end;
            align-items: flex-end;
        }
        .message.theirs {
            align-self: flex-start;
            align-items: flex-start;
        }
        .bubble {
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.5;
            word-break: break-word;
        }
        .mine .bubble {
            background: #3b82f6;
            color: #fff;
            border-bottom-right-radius: 4px;
        }
        .theirs .bubble {
            background: #f3f4f6;
            color: #111;
            border-bottom-left-radius: 4px;
        }
        .time {
            font-size: 11px;
            color: #9ca3af;
            margin-top: 4px;
        }
        #input-area {
            display: flex;
            gap: 10px;
            padding: 14px 16px;
            border-top: 1px solid #e5e7eb;
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
        #msg-input:focus {
            border-color: #3b82f6;
        }
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
        #send-btn:hover { background: #2563eb; }
    </style>
</head>
<body>

<div id="chat-wrap">

    <div id="chat-header">
        <a href="{{ route('chat.index') }}">← Back</a>
        {{ $user->name }}
    </div>

    <div id="messages">
        @foreach ($messages as $msg)
            <div class="message {{ $msg->user_id === auth()->id() ? 'mine' : 'theirs' }}">
                <div class="bubble">{{ $msg->content }}</div>
                <span class="time">{{ $msg->created_at->format('h:i A') }}</span>
            </div>
        @endforeach
    </div>

    <div id="input-area">
        <textarea
            id="msg-input"
            rows="1"
            placeholder="Type a message..."></textarea>
        <button id="send-btn">Send</button>
    </div>

</div>

{{-- Pass data to JavaScript --}}
<script>
    window.CONVERSATION_ID = {{ $conversation->id }};
    window.AUTH_USER_ID    = {{ auth()->id() }};
</script>

@vite('resources/js/chat.js')

</body>
</html>