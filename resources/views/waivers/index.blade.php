<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Waivers – Onboardify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

{{-- Top Bar --}}
<div class="bg-white border-b border-gray-200 shadow-sm px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="text-2xl">📋</span>
        <span class="font-bold text-lg text-gray-900">Onboardify</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500 text-sm">Waivers</span>
    </div>
    <a href="{{ route('dashboard') }}"
       class="text-gray-500 hover:text-gray-900 text-sm transition">← Back to Dashboard</a>
</div>

<div class="max-w-6xl mx-auto px-6 py-8">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">🧾 My Waivers</h1>
            <p class="text-gray-500 text-sm mt-1">Manage and send waivers to your clients</p>
        </div>
        <a href="{{ route('waivers.create') }}"
           class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition">
            + Create Waiver
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif

    {{-- Empty State --}}
    @if($waivers->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="text-6xl mb-4">📄</div>
            <h2 class="text-gray-700 font-semibold text-lg mb-2">No waivers yet</h2>
            <p class="text-gray-400 text-sm mb-6">Create your first waiver and send it to clients</p>
            <a href="{{ route('waivers.create') }}"
               class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-6 py-3 rounded-xl text-sm transition">
                + Create First Waiver
            </a>
        </div>

    @else
        {{-- Waivers Table --}}
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Title</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Fields</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Status</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Created</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($waivers as $waiver)
                    <tr class="hover:bg-gray-50 transition">

                        {{-- Title --}}
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900">{{ $waiver->title }}</div>
                            <div class="text-gray-400 text-xs mt-0.5">{{ $waiver->slug }}</div>
                        </td>

                        {{-- Field Count --}}
                        <td class="px-6 py-4">
                            <span class="bg-blue-50 border border-blue-200 text-blue-600 text-xs px-2.5 py-1 rounded-full">
                                {{ count($waiver->fields ?? []) }} fields
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            @if($waiver->status === 'draft')
                                <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full">
                                    📝 Draft
                                </span>
                            @elseif($waiver->status === 'sent')
                                <span class="bg-yellow-50 border border-yellow-200 text-yellow-600 text-xs px-2.5 py-1 rounded-full">
                                    📤 Sent
                                </span>
                            @elseif($waiver->status === 'signed')
                                <span class="bg-green-50 border border-green-200 text-green-600 text-xs px-2.5 py-1 rounded-full">
                                    ✅ Signed
                                </span>
                            @endif
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4 text-gray-500 text-xs">
                            {{ $waiver->created_at->format('M d, Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">

                                {{-- Send --}}
                                <a href="{{ route('waivers.sendForm', $waiver) }}"
                                   class="bg-blue-50 hover:bg-blue-100 border border-blue-200 text-blue-600 text-xs px-3 py-1.5 rounded-lg transition">
                                    📤 Send
                                </a>

                                {{-- Edit --}}
                                <a href="{{ route('waivers.edit', $waiver) }}"
                                   class="bg-gray-50 hover:bg-gray-100 border border-gray-200 text-gray-600 text-xs px-3 py-1.5 rounded-lg transition">
                                    ✏️ Edit
                                </a>

                                {{-- Delete --}}
                                <form action="{{ route('waivers.destroy', $waiver) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this waiver?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-50 hover:bg-red-100 border border-red-200 text-red-500 text-xs px-3 py-1.5 rounded-lg transition">
                                        🗑 Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

</div>
</body>
</html>