<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div style="max-width:480px;margin:40px auto;padding:0 16px">

    <h2 style="margin-bottom:20px">Select a user to chat with</h2>

    @foreach ($users as $u)
        <a href="{{ route('chat.show', $u) }}"
           style="display:flex;align-items:center;justify-content:space-between;
                  padding:14px 16px;border:1px solid #e5e7eb;border-radius:10px;
                  margin-bottom:10px;text-decoration:none;color:inherit">
            <div>
                <div style="font-weight:600;font-size:15px">{{ $u->name }}</div>
                <div style="color:#999;font-size:13px">{{ $u->email }}</div>
            </div>
            <span style="font-size:13px;color:#3b82f6">Chat →</span>
        </a>
    @endforeach

</div>
</body>
</html>