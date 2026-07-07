<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Group</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; background-color: #f9fafb; }
        label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px }
        input[type=text], textarea, select {
            width:100%; padding:10px 14px; border:1px solid #d1d5db;
            border-radius:8px; font-size:14px; outline:none; font-family:sans-serif;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        input:focus, textarea:focus { border-color:#2D6A4F; box-shadow: 0 0 0 3px rgba(45, 106, 79, 0.1); }
        .field { margin-bottom:18px }
        .error { color:#ef4444; font-size:12px; margin-top:4px }
        input[type="checkbox"] { accent-color: #2D6A4F; }
    </style>
</head>
<body>
<div style="max-width:480px;margin:40px auto;padding:24px;background:#fff;border-radius:16px;box-shadow:0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); border: 1px solid #f3f4f6;">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('groups.index') }}"
           style="font-size:13px;color:#2D6A4F;text-decoration:none;font-weight:500">← Back</a>
        <h2 style="font-size:18px;font-weight:700;color:#111827">Create Group</h2>
    </div>

    <form action="{{ route('groups.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="field">
            <label>Group Name</label>
            <input type="text" name="name" value="{{ old('name') }}"
                   placeholder="e.g. Drivers Team" required>
            @error('name') <p class="error">{{ $message }}</p> @enderror
        </div>

        <div class="field">
            <label>Description <span style="font-weight:400;color:#9ca3af">(optional)</span></label>
            <textarea name="description" rows="2"
                      placeholder="What is this group about?">{{ old('description') }}</textarea>
        </div>

        <div class="field">
            <label>Group Avatar <span style="font-weight:400;color:#9ca3af">(optional)</span></label>
            <input type="file" name="avatar" accept="image/*"
                   style="padding:6px 14px;cursor:pointer">
        </div>

        <div class="field">
            <label>Add Members</label>
            @error('members') <p class="error">{{ $message }}</p> @enderror
            <div style="border:1px solid #e5e7eb;border-radius:8px;
                        max-height:200px;overflow-y:auto;padding:8px; background:#f9fafb;">
                @foreach($users as $user)
                <label style="display:flex;align-items:center;gap:10px;
                               padding:8px;border-radius:6px;cursor:pointer;
                               font-weight:400;color:inherit;margin-bottom:0; transition: background 0.15s;"
                       onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">
                    <input type="checkbox" name="members[]" value="{{ $user->id }}"
                           style="width:16px;height:16px">
                    <div>
                        <div style="font-weight:600;font-size:14px; color:#111827">{{ $user->name }}</div>
                        <div style="font-size:12px;color:#6b7280">{{ $user->email }}</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit"
                style="width:100%;padding:12px;background:linear-gradient(135deg, #0B3D2E, #2D6A4F);color:#fff;
                       border:none;border-radius:10px;font-size:15px;
                       font-weight:600;cursor:pointer; margin-top:8px; box-shadow: 0 4px 6px -1px rgba(11, 61, 46, 0.2); transition: opacity 0.2s;"
                onmouseover="this.style.opacity='0.9'" onmouseout="this.style.opacity='1'">
            Create Group
        </button>
    </form>

</div>
</body>
</html>