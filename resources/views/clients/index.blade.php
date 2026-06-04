<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clients – Onboardify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<div class="bg-white border-b border-gray-200 shadow-sm px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="text-2xl">📋</span>
        <span class="font-bold text-lg">Onboardify</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500 text-sm">Clients</span>
    </div>
    <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-900 text-sm">← Dashboard</a>
</div>

<div class="max-w-6xl mx-auto px-6 py-8">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">👥 Clients</h1>
            <p class="text-gray-500 text-sm mt-1">Manage your clients</p>
        </div>
        <a href="{{ route('clients.create') }}"
           class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-5 py-2.5 rounded-xl text-sm transition">
            + Add Client
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 text-sm px-4 py-3 rounded-xl mb-6">
            ✅ {{ session('success') }}
        </div>
    @endif

    @if($clients->isEmpty())
        <div class="bg-white border border-gray-200 rounded-2xl p-16 text-center shadow-sm">
            <div class="text-6xl mb-4">👤</div>
            <h2 class="text-gray-700 font-semibold text-lg mb-2">No clients yet</h2>
            <p class="text-gray-400 text-sm mb-6">Add your first client to get started</p>
            <a href="{{ route('clients.create') }}"
               class="bg-blue-600 hover:bg-blue-500 text-white font-semibold px-6 py-3 rounded-xl text-sm transition">
                + Add First Client
            </a>
        </div>
    @else
        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b border-gray-100 bg-gray-50">
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Name</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Email</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Phone</th>
                        <th class="text-left px-6 py-3 text-gray-500 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($clients as $client)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $client->name }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $client->email }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $client->phone ?? '—' }}</td>
                        <td class="px-6 py-4">
                            <form action="{{ route('clients.destroy', $client) }}"
                                  method="POST"
                                  onsubmit="return confirm('Delete this client?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="bg-red-50 hover:bg-red-100 border border-red-200 text-red-500 text-xs px-3 py-1.5 rounded-lg transition">
                                    🗑 Delete
                                </button>
                            </form>
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