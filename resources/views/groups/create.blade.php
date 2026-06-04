<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Create Group</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: sans-serif; }
        label { display:block; font-size:13px; font-weight:600; color:#374151; margin-bottom:6px }
        input[type=text], textarea, select {
            width:100%; padding:10px 14px; border:1px solid #d1d5db;
            border-radius:8px; font-size:14px; outline:none; font-family:sans-serif;
        }
        input:focus, textarea:focus { border-color:#3b82f6; }
        .field { margin-bottom:18px }
        .error { color:#ef4444; font-size:12px; margin-top:4px }
    </style>
</head>
<body>
<div style="max-width:480px;margin:40px auto;padding:0 16px">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px">
        <a href="{{ route('groups.index') }}"
           style="font-size:13px;color:#3b82f6;text-decoration:none">← Back</a>
        <h2 style="font-size:18px;font-weight:700">Create Group</h2>
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
            <div style="border:1px solid #d1d5db;border-radius:8px;
                        max-height:200px;overflow-y:auto;padding:8px">
                @foreach($users as $user)
                <label style="display:flex;align-items:center;gap:10px;
                               padding:8px;border-radius:6px;cursor:pointer;
                               font-weight:400;color:inherit;margin-bottom:0">
                    <input type="checkbox" name="members[]" value="{{ $user->id }}"
                           style="width:16px;height:16px">
                    <div>
                        <div style="font-weight:600;font-size:14px">{{ $user->name }}</div>
                        <div style="font-size:12px;color:#9ca3af">{{ $user->email }}</div>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <button type="submit"
                style="width:100%;padding:12px;background:#3b82f6;color:#fff;
                       border:none;border-radius:22px;font-size:15px;
                       font-weight:600;cursor:pointer">
            Create Group
        </button>
    </form>

</div>
</body>
</html>