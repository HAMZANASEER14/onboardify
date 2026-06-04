<h1 class="text-xl font-bold mb-4 text-gray-800">My Sent Forms</h1>

@if($submissions->count() > 0)
    <div class="space-y-3">
        @foreach($submissions as $submission)
            <!-- Card Container with subtle shadow and hover effect -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-5 hover:shadow-md transition-shadow duration-200">
                
                <!-- Responsive Grid: 1 column on mobile, 2 on tablet, 4 on desktop -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Waiver Field -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Waiver</p>
                        <p class="text-sm font-medium text-gray-900">{{ $submission->waiver->title ?? 'N/A' }}</p>
                    </div>

                    <!-- Client Field -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Client</p>
                        <p class="text-sm font-medium text-gray-900">{{ $submission->client->name ?? 'N/A' }}</p>
                    </div>

                    <!-- Status Field (Styled as a clean badge) -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Status</p>
                        <span class="inline-block px-2.5 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/20">
                            {{ $submission->status }}
                        </span>
                    </div>

                    <!-- Date Field -->
                    <div>
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1">Date</p>
                        <p class="text-sm font-medium text-gray-900">{{ $submission->created_at }}</p>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@else
    <!-- Styled Empty State -->
    <div class="text-center py-8 bg-gray-50 rounded-lg border border-dashed border-gray-300">
        <p class="text-gray-500">No submissions found.</p>
    </div>
@endif