<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Groups</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: sans-serif; background-color: #f9fafb; }
    </style>
</head>
<body>
<div style="max-width:480px;margin:40px auto;padding:0 16px">

    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px">
        <h2 style="font-size:18px;font-weight:700;color:#111827">💬 My Groups</h2>
        <a href="{{ route('groups.create') }}"
           style="padding:8px 16px;background:linear-gradient(135deg, #0B3D2E, #2D6A4F);color:#fff;border-radius:20px;
                  text-decoration:none;font-size:13px;font-weight:600;box-shadow: 0 2px 4px rgba(11, 61, 46, 0.2); transition: opacity 0.2s;"
           onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            + New Group
        </a>
    </div>

    @forelse($groups as $group)
    <a href="{{ route('groups.show', $group) }}"
       style="display:flex;align-items:center;gap:12px;
              padding:14px 16px;border:1px solid #e5e7eb;border-radius:12px;
              margin-bottom:10px;text-decoration:none;color:inherit; transition: all 0.2s; background: #fff;"
       onmouseover="this.style.borderColor='#2D6A4F'; this.style.boxShadow='0 4px 6px -1px rgba(45, 106, 79, 0.1)'; this.style.transform='translateY(-1px)'"
       onmouseout="this.style.borderColor='#e5e7eb'; this.style.boxShadow='none'; this.style.transform='none'">

        <div style="width:44px;height:44px;border-radius:50%;background:rgba(45,106,79,0.1);
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
                <span style="font-weight:600;font-size:15px;color:#111827">{{ $group->name }}</span>
                <span style="font-size:11px;color:#9ca3af">
                    {{ $group->latestMessage?->created_at?->diffForHumans() ?? '' }}
                </span>
            </div>
            <div style="color:#6b7280;font-size:13px;white-space:nowrap;overflow:hidden;
                        text-overflow:ellipsis;margin-top:2px">
                @if($group->latestMessage)
                    <strong style="color:#111827">{{ $group->latestMessage->user->name }}:</strong>
                    {{ $group->latestMessage->message }}
                @else
                    <em style="color:#9ca3af">No messages yet</em>
                @endif
            </div>
            <div style="font-size:11px;color:#2D6A4F;margin-top:4px;font-weight:500">
                👥 {{ $group->members_count }} members
            </div>
        </div>

    </a>
    @empty
        <div style="text-align:center;margin-top:60px">
            <div style="width:64px;height:64px;background:rgba(45,106,79,0.1);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;font-size:24px;">💬</div>
            <p style="color:#6b7280;font-size:14px;font-weight:500">
                You are not in any groups yet.
            </p>
            <a href="{{ route('groups.create') }}" style="display:inline-block;margin-top:12px;color:#2D6A4F;font-size:13px;font-weight:600;text-decoration:none;">
                Create your first group →
            </a>
        </div>
    @endforelse

</div>
</body>
</html>