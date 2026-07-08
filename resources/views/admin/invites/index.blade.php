@extends('layouts.app')

@section('content')
<div class="w-full pt-2">
        <div class="flex items-center justify-between mb-2">
        <h1 class="text-2xl font-bold text-gray-800">
            Invite History
        </h1>

        <a href="{{ route('admin.bulk-invite') }}"
   class="text-white px-4 py-1.5 rounded-lg text-sm shadow-sm hover:scale-105 transition"
   style="background:linear-gradient(135deg,#0B3D2E,#2D6A4F)">
    + New Invite
</a>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-100 border border-green-300 text-green-700 p-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow overflow-hidden">

        <table class="min-w-full">

            <thead class="bg-gray-100">

                <tr>

                    <th class="px-6 py-2 text-left">Email</th>

                    <th class="px-6 py-2 text-left">Status</th>

                    <th class="px-6 py-2 text-left">Source</th>

                    <th class="px-6 py-2 text-left">Failure Reason</th>

                    <th class="px-6 py-2 text-left">Created</th>

                </tr>

            </thead>

            <tbody>

                @forelse($invites as $invite)

                <tr class="border-b">

                    <td class="px-4 py-1.5">
                        {{ $invite->email }}
                    </td>

                    <td class="px-4 py-1.5">

                        @if($invite->status=='sent')

                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                Sent
                            </span>

                        @elseif($invite->status=='failed')

                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                                Failed
                            </span>

                        @elseif($invite->status=='joined')

                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-sm font-semibold">
                                Joined
                            </span>

                        @else

                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                                Pending
                            </span>

                        @endif

                    </td>

                    <td class="px-4 py-1.5">
                        {{ ucfirst($invite->source) }}
                    </td>

                    <td class="px-4 py-1.5">
                        {{ $invite->failure_reason ?? '-' }}
                    </td>

                    <td class="px-4 py-1.5">
                        {{ $invite->created_at->format('d M Y h:i A') }}
                    </td>

                </tr>

                @empty

                <tr>

                    <td colspan="5" class="text-center py-10 text-gray-500">

                        No invitations found.

                    </td>

                </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="mt-6">
        {{ $invites->links() }}
    </div>

</div>

@endsection