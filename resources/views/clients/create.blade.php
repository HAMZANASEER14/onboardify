<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Client – Onboardify</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen">

<div class="bg-white border-b border-gray-200 shadow-sm px-6 py-4 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <span class="text-2xl">📋</span>
        <span class="font-bold text-lg">Onboardify</span>
        <span class="text-gray-400">/</span>
        <span class="text-gray-500 text-sm">Add Client</span>
    </div>
    <a href="{{ route('clients.index') }}" class="text-gray-500 hover:text-gray-900 text-sm">← Back</a>
</div>

<div class="max-w-xl mx-auto px-6 py-8">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">👤 Add New Client</h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 text-sm px-4 py-3 rounded-xl mb-6">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6">
        <form action="{{ route('clients.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Full Name <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="John Doe"
                       required
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="john@example.com"
                       required
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Phone <span class="text-gray-400 text-xs">(optional)</span>
                </label>
                <input type="text" name="phone"
                       value="{{ old('phone') }}"
                       placeholder="+1 234 567 8900"
                       class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-2.5 focus:outline-none focus:border-blue-500 transition">
            </div>

            <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-500 text-white font-semibold py-3 rounded-xl text-sm transition">
                ✅ Add Client
            </button>
        </form>
    </div>
</div>

</body>
</html>