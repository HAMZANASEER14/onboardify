<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Analytics - Onboardify</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%232563eb'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50 min-h-screen font-sans antialiased">

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    {{-- Page Header --}}
    <div class="mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Form Analytics</h1>
        <p class="text-gray-600">Track performance and submission metrics for your waivers</p>
    </div>

    {{-- Key Metrics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Sent --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    12%
                </span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalSent }}</div>
            <div class="text-sm text-gray-600">Total Forms Sent</div>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
                <div class="bg-blue-600 h-1.5 rounded-full" style="width: 100%"></div>
            </div>
        </div>

        {{-- Signed --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    8%
                </span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalSigned }}</div>
            <div class="text-sm text-gray-600">Forms Signed</div>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
                <div class="bg-green-600 h-1.5 rounded-full" style="width: {{ $signedPercentage }}%"></div>
            </div>
        </div>

        {{-- Pending --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded-full">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
                    </svg>
                    3%
                </span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $totalPending }}</div>
            <div class="text-sm text-gray-600">Awaiting Signature</div>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
                <div class="bg-yellow-600 h-1.5 rounded-full" style="width: {{ $pendingPercentage }}%"></div>
            </div>
        </div>

        {{-- Conversion Rate --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">
                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                    </svg>
                    5%
                </span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-1">{{ $conversionRate }}%</div>
            <div class="text-sm text-gray-600">Conversion Rate</div>
            <div class="mt-3 w-full bg-gray-200 rounded-full h-1.5">
                <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $conversionRate }}%"></div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Submissions Over Time --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Submissions Over Time</h3>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Last 30 Days</option>
                    <option>Last 7 Days</option>
                    <option>Last 3 Months</option>
                </select>
            </div>
            <div style="height: 250px;">
                <canvas id="submissionsChart"></canvas>
            </div>
        </div>

        {{-- Status Distribution --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Status Distribution</h3>
                <button class="text-blue-600 hover:text-blue-700 text-sm font-medium">View Details</button>
            </div>
            <div style="height: 250px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Top Performing Waivers --}}
    @if($topWaivers->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Top Performing Waivers</h3>
            <a href="{{ route('waivers.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Waiver Name</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Sent</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Signed</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Pending</th>
                        <th class="text-center py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Conversion</th>
                        <th class="text-right py-3 px-4 text-xs font-semibold text-gray-600 uppercase">Performance</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($topWaivers as $waiver)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-4 px-4">
                            <div class="font-semibold text-gray-900">{{ $waiver['name'] }}</div>
                            <div class="text-xs text-gray-500">Last sent {{ $waiver['last_sent'] }}</div>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $waiver['sent'] }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $waiver['signed'] }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                {{ $waiver['pending'] }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-center">
                            <div class="text-sm font-bold text-gray-900">{{ $waiver['conversion_rate'] }}%</div>
                        </td>
                        <td class="py-4 px-4">
                            <div class="flex items-center justify-end gap-2">
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-gradient-to-r from-blue-500 to-purple-500 h-2 rounded-full" style="width: {{ $waiver['conversion_rate'] }}%"></div>
                                </div>
                                <span class="text-xs font-medium text-gray-600">{{ $waiver['conversion_rate'] }}%</span>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- Recent Submissions Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <h3 class="text-lg font-bold text-gray-900">Recent Submissions</h3>
                <div class="flex items-center gap-3">
                    <div class="relative">
                        <svg class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" id="search-submissions" placeholder="Search submissions..." 
                               class="pl-9 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
                    </div>
                    <select id="filter-status" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Status</option>
                        <option value="signed">Signed</option>
                        <option value="pending">Pending</option>
                        <option value="viewed">Viewed</option>
                    </select>
                </div>
            </div>
        </div>
        
        @if($submissions->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full" id="submissions-table">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-600 uppercase">Waiver</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-600 uppercase">Client</th>
                        <th class="text-center py-3 px-6 text-xs font-semibold text-gray-600 uppercase">Status</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-gray-600 uppercase hidden sm:table-cell">Date</th>
                        <th class="text-right py-3 px-6 text-xs font-semibold text-gray-600 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($submissions as $submission)
                    <tr class="hover:bg-gray-50 transition submission-row" 
                        data-status="{{ $submission->status }}"
                        data-waiver="{{ strtolower($submission->waiver->title ?? '') }}"
                        data-client="{{ strtolower($submission->client->name ?? '') }}">
                        <td class="py-4 px-6">
                            <div class="font-semibold text-gray-900">{{ $submission->waiver->title ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500 sm:hidden">{{ $submission->created_at->format('M d, Y') }}</div>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-blue-400 to-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($submission->client->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-900">{{ $submission->client->name ?? 'N/A' }}</span>
                            </div>
                        </td>
                        <td class="py-4 px-6 text-center">
                            @if($submission->status === 'signed')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700 border border-green-200">
                                    <span class="w-1.5 h-1.5 bg-green-600 rounded-full"></span>
                                    Signed
                                </span>
                            @elseif($submission->status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700 border border-yellow-200">
                                    <span class="w-1.5 h-1.5 bg-yellow-600 rounded-full"></span>
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700 border border-gray-200">
                                    <span class="w-1.5 h-1.5 bg-gray-600 rounded-full"></span>
                                    {{ ucfirst($submission->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="py-4 px-6 text-sm text-gray-600 hidden sm:table-cell">
                            {{ $submission->created_at->format('M d, Y') }}
                        </td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('waivers.show', $submission->waiver_id) }}" 
                                   class="p-2 hover:bg-gray-100 rounded-lg transition" title="View Details">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <button class="p-2 hover:bg-gray-100 rounded-lg transition" title="Download">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </button>
                                @if($submission->status !== 'signed')
                                <button class="p-2 hover:bg-blue-50 rounded-lg transition" title="Send Reminder">
                                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $submissions->links() }}
        </div>
        @else
        <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-gray-500">No submissions found.</p>
        </div>
        @endif
    </div>

</div>

<script>
    // Initialize Charts
    document.addEventListener('DOMContentLoaded', function() {
        // Submissions Over Time Chart
        const submissionsCtx = document.getElementById('submissionsChart').getContext('2d');
        new Chart(submissionsCtx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Submissions',
                    data: @json($chartData),
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Signed', 'Pending', 'Viewed'],
                datasets: [{
                    data: [{{ $totalSigned }}, {{ $totalPending }}, {{ $totalViewed }}],
                    backgroundColor: [
                        '#10B981',
                        '#F59E0B',
                        '#6B7280'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        display: true,
                        position: 'bottom'
                    }
                }
            }
        });
    });

    // Search and Filter
    const searchInput = document.getElementById('search-submissions');
    const statusFilter = document.getElementById('filter-status');

    function filterSubmissions() {
        const query = searchInput.value.toLowerCase();
        const status = statusFilter.value;

        document.querySelectorAll('.submission-row').forEach(row => {
            const waiver = row.dataset.waiver;
            const client = row.dataset.client;
            const rowStatus = row.dataset.status;
            
            const matchesSearch = waiver.includes(query) || client.includes(query);
            const matchesStatus = status === 'all' || rowStatus === status;
            
            row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
        });
    }

    if (searchInput) searchInput.addEventListener('input', filterSubmissions);
    if (statusFilter) statusFilter.addEventListener('change', filterSubmissions);
</script>

</body>
</html>