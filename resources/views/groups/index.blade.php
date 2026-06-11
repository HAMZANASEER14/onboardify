<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Groups</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
<div style="max-width:480px;margin:40px auto;padding:0 16px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
        <h2 style="font-size:18px;font-weight:700">💬 My Groups</h2>
        <a href="{{ route('groups.create') }}"
           style="padding:8px 16px;background:#3b82f6;color:#fff;border-radius:20px;
                  text-decoration:none;font-size:13px;font-weight:500">
            + New Group
        </a>
    </div>

    @forelse($groups as $group)
    <a href="{{ route('groups.show', $group) }}"
       style="display:flex;align-items:center;gap:12px;
              padding:14px 16px;border:1px solid #e5e7eb;border-radius:10px;
              margin-bottom:10px;text-decoration:none;color:inherit">

        <div style="width:44px;height:44px;border-radius:50%;background:#dbeafe;
                    display:flex;align-items:center;justify-content:center;
                    font-size:18px;flex-shrink:0;overflow:hidden">
            @if($group->avatar)
                <img src="{{ asset('storage/'.$group->avatar) }}"
                     style="width:100%;height:100%;object-fit:cover">
            @else
                👥
            @endif
        </div>

        <div style="flex:1;min-width:0">
            <div style="display:flex;justify-content:space-between;align-items:center">
                <span style="font-weight:600;font-size:15px">{{ $group->name }}</span>
                <span style="font-size:11px;color:#9ca3af">
                    {{ $group->latestMessage?->created_at?->diffForHumans() ?? '' }}
                </span>
            </div>
            <div style="color:#999;font-size:13px;white-space:nowrap;overflow:hidden;
                        text-overflow:ellipsis;margin-top:2px">
                @if($group->latestMessage)
                    <strong>{{ $group->latestMessage->user->name }}:</strong>
                    {{ $group->latestMessage->message }}
                @else
                    <em>No messages yet</em>
                @endif
            </div>
            <div style="font-size:11px;color:#9ca3af;margin-top:2px">
                👥 {{ $group->members_count }} members
            </div>
        </div>

    </a>
    @empty
        <p style="text-align:center;color:#9ca3af;margin-top:60px">
            You are not in any groups yet.
        </p>
    @endforelse

</div>
</body>
</html>