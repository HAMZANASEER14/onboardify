@extends('layouts.app')

@section('title', 'Reports')

@section('content')

<style>
    .report-page { font-family: 'Inter', sans-serif; }

    /* ── KPI strip ── */
.kpi-card {
    border: none;
    background: linear-gradient(135deg, #0B3D2E, #2D6A4F);
    border-radius: 12px;
    padding: 24px;
    transition: box-shadow 0.15s, transform 0.15s;
}
/* Same gradient in dark mode — brand color stays constant, like your dashboard's KPI cards */
html.dark .kpi-card {
    background: linear-gradient(135deg, #0B3D2E, #2D6A4F);
}
@media (min-width: 640px) {
    .kpi-card { padding: 24px; }
}
.kpi-card:hover { box-shadow: 0 6px 20px rgba(11,61,46,0.35); transform: translateY(-2px); }
html.dark .kpi-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,0.4); }

.kpi-value  { font-size: 22px; font-weight: 700; color: #fff; letter-spacing: -0.5px; line-height: 1; }
html.dark .kpi-value { color: #fff; }
@media (min-width: 640px) {
    .kpi-value { font-size: 28px; }
}
.kpi-label  { font-size: 10px; font-weight: 600; color: rgba(255,255,255,0.7); text-transform: uppercase; letter-spacing: 0.06em; margin-top: 4px; }
html.dark .kpi-label { color: rgba(255,255,255,0.7); }
@media (min-width: 640px) {
    .kpi-label { font-size: 11px; }
}
.kpi-period { font-size: 9px; color: rgba(255,255,255,0.5); margin-top: 2px; }
html.dark .kpi-period { color: rgba(255,255,255,0.5); }
@media (min-width: 640px) {
    .kpi-period { font-size: 10px; }
}
.kpi-badge  { font-size: 9px; font-weight: 600; padding: 2px 6px; border-radius: 100px; background: rgba(255,255,255,0.2); color: #fff; }
html.dark .kpi-badge { background: rgba(255,255,255,0.2); color: #fff; }
@media (min-width: 640px) {
    .kpi-badge { font-size: 10px; padding: 2px 8px; }
}
.kpi-badge.orange { background: rgba(255,255,255,0.2); color: #fff; }
html.dark .kpi-badge.orange { background: rgba(255,255,255,0.2); color: #fff; }
.kpi-badge.blue   { background: rgba(255,255,255,0.2); color: #fff; }
html.dark .kpi-badge.blue { background: rgba(255,255,255,0.2); color: #fff; }
.kpi-badge.purple { background: rgba(255,255,255,0.2); color: #fff; }
html.dark .kpi-badge.purple { background: rgba(255,255,255,0.2); color: #fff; }

   /* ── Waiver cards ── */
.waiver-card {
    border: 1px solid rgba(45,106,79,0.15);
    background: #fff;
    border-radius: 12px;
    padding: 12px 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: box-shadow 0.15s, border-color 0.15s, background 0.15s;
}
html.dark .waiver-card {
    background: #1a1a1a;
    border-color: #2e2e2e;
}
@media (min-width: 640px) {
    .waiver-card { padding: 16px 20px; gap: 16px; }
}
.waiver-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.07); border-color: #d1d5db; }
html.dark .waiver-card:hover { background: #232323; border-color: #3a3a3a; }

.waiver-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    background: #4b5563;
    flex-shrink: 0;
}
html.dark .waiver-icon { background: #52525b; }
@media (min-width: 640px) {
    .waiver-icon { width: 40px; height: 40px; }
}

.waiver-progress-track { height: 4px; background: #f3f4f6; border-radius: 100px; overflow: hidden; flex: 1; }
html.dark .waiver-progress-track { background: #2e2e2e; }
.waiver-progress-fill  { height: 100%; border-radius: 100px; background: #4b5563; transition: width 0.6s ease; }
html.dark .waiver-progress-fill { background: #9ca3af; }

.pill         { font-size: 9px; font-weight: 600; padding: 2px 6px; border-radius: 100px; white-space: nowrap; }
@media (min-width: 640px) {
    .pill { font-size: 10px; padding: 2px 8px; }
}
.pill-teal    { background: rgba(45,106,79,0.1); color: #2D6A4F; }
html.dark .pill-teal { background: #2e2e2e; color: #d1d5db; }
.pill-blue    { background: rgba(11,61,46,0.1); color: #0B3D2E; }
html.dark .pill-blue { background: #2e2e2e; color: #d1d5db; }
.pill-orange  { background: #fff7ed; color: #c2410c; }
html.dark .pill-orange { background: rgba(249,115,22,0.18); color: #fdba74; }
.pill-gray    { background: #f9fafb; color: #6b7280; border: 1px solid #e5e7eb; }
html.dark .pill-gray { background: #2e2e2e; color: #9ca3af; border-color: #3a3a3a; }
    /* ── Table ── */
    .data-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    .data-table thead th {
        font-size: 9px; font-weight: 700; text-transform: uppercase;
        letter-spacing: 0.07em; color: #9ca3af;
        padding: 8px 12px; background: #f9fafb;
        border-bottom: 1px solid rgba(45,106,79,0.15);
    }
    @media (min-width: 640px) {
        .data-table thead th { font-size: 10px; padding: 10px 16px; }
    }
    .data-table thead th:first-child { border-radius: 8px 0 0 0; }
    .data-table thead th:last-child  { border-radius: 0 8px 0 0; }
    .data-table tbody tr { border-bottom: 1px solid #f3f4f6; transition: background 0.12s; }
    .data-table tbody tr:hover { background: #fafafa; }
    .data-table tbody td { padding: 10px 12px; font-size: 11px; color: #374151; vertical-align: middle; }
    @media (min-width: 640px) {
        .data-table tbody td { padding: 12px 16px; font-size: 12px; }
    }
    .data-table tbody tr:last-child td { border-bottom: none; }

    .status-dot { width: 6px; height: 6px; border-radius: 50%; display: inline-block; margin-right: 5px; }

    .icon-btn {
        width: 26px; height: 26px; border-radius: 6px; border: 1px solid #e5e7eb;
        display: inline-flex; align-items: center; justify-content: center;
        color: #9ca3af; background: #fff; transition: all 0.12s; cursor: pointer;
    }
    @media (min-width: 640px) {
        .icon-btn { width: 28px; height: 28px; }
    }
    .icon-btn:hover { background: rgba(45,106,79,0.06); color: #2D6A4F; border-color: #2D6A4F; }
    .icon-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    .section-header { font-size: 12px; font-weight: 700; color: #111827; letter-spacing: -0.1px; }
    @media (min-width: 640px) {
        .section-header { font-size: 13px; }
    }
    .section-sub    { font-size: 10px; color: #9ca3af; margin-top: 1px; }
    @media (min-width: 640px) {
        .section-sub { font-size: 11px; }
    }

    /* ── Date filter pills ── */
    .date-filter-btn {
        font-size: 10px; font-weight: 600; padding: 4px 8px; border-radius: 100px;
        border: 1px solid #e5e7eb; background: #fff; color: #6b7280;
        cursor: pointer; transition: all 0.12s; white-space: nowrap;
    }
    @media (min-width: 640px) {
        .date-filter-btn { font-size: 11px; padding: 4px 10px; }
    }
    .date-filter-btn.active,
    .date-filter-btn:hover { background: linear-gradient(135deg, #0B3D2E, #2D6A4F); color: #fff; border-color: transparent; }

    /* ── Toast ── */
    #toast {
        position: fixed; bottom: 16px; right: 16px; z-index: 100;
        background: #0B3D2E; color: #fff; font-size: 11px; font-weight: 500;
        padding: 8px 14px; border-radius: 10px;
        opacity: 0; transform: translateY(8px);
        transition: opacity 0.2s, transform 0.2s;
        pointer-events: none;
        max-width: calc(100vw - 32px);
    }
    @media (min-width: 640px) {
        #toast { bottom: 24px; right: 24px; font-size: 12px; padding: 10px 16px; }
    }
    #toast.show { opacity: 1; transform: translateY(0); }

    /* ── Modal ── */
    #data-modal { max-height: 80vh; }
    .modal-fade {
        position: sticky; bottom: 0; left: 0; right: 0;
        height: 40px;
        background: linear-gradient(to bottom, rgba(255,255,255,0), #fff);
        pointer-events: none;
    }
    html.dark .modal-fade { background: linear-gradient(to bottom, rgba(13,61,38,0), #0d3d26); }

    /* ── Empty filter state ── */
    #no-results-row { display: none; }
    #no-results-row td { text-align: center; padding: 32px 16px; color: #9ca3af; font-size: 12px; }

    /* ── Mobile card layout for table ── */
    @media (max-width: 639px) {
        .data-table thead { display: none; }
        .data-table tbody tr {
            display: block;
            padding: 12px;
            border: 1px solid rgba(45,106,79,0.15);
            border-radius: 10px;
            margin-bottom: 8px;
            background: #fff;
        }
        .data-table tbody tr:hover { background: #fff; }
        .data-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 6px 0;
            border-bottom: 1px solid #f3f4f6;
            font-size: 12px;
        }
        .data-table tbody td:last-child { border-bottom: none; }
        .data-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            flex-shrink: 0;
            margin-right: 12px;
        }
        .data-table tbody td:last-child {
            justify-content: flex-end;
            padding-top: 8px;
        }
        .data-table tbody td:last-child::before { display: none; }
    }

    /* ── Scrollable filter container ── */
    .filter-scroll {
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .filter-scroll::-webkit-scrollbar {
        display: none;
    }

    /* ── Dark mode overrides — now matches app's brand green (#0d3d26 / #0a2e1e) ── */
    html.dark .report-page,
    html.dark #data-modal {
        color-scheme: dark;
    }
    html.dark .report-page h1.text-gray-900,
    html.dark .report-page .text-gray-900,
    html.dark #data-modal .text-gray-900 { color: #fff; }
    html.dark .report-page .text-gray-800 { color: #e5e7eb; }
    html.dark .report-page .text-gray-700,
    html.dark .report-page .hover\:text-gray-700:hover { color: #d1d5db; }
    html.dark .report-page .text-gray-400 { color: #9ca3af; }

    html.dark .report-page .bg-white,
    html.dark #data-modal.bg-white { background: #0d3d26; }
    html.dark .report-page .bg-gray-50,
    html.dark .report-page .bg-gray-50\/40 { background: rgba(45,106,79,0.12); }
    html.dark .report-page .border-gray-200,
    html.dark .report-page .border-gray-100,
    html.dark #data-modal .border-gray-100 { border-color: rgba(45,106,79,0.35); }

    html.dark .data-table thead th { background: rgba(45,106,79,0.12); color: #9ca3af; border-color: rgba(45,106,79,0.25); }
    html.dark .data-table tbody tr { border-color: rgba(45,106,79,0.2); }
    html.dark .data-table tbody tr:hover { background: rgba(45,106,79,0.1); }
    html.dark .data-table tbody td { color: #cbd5e1; }

    html.dark .icon-btn { background: #0d3d26; border-color: rgba(45,106,79,0.35); color: #9ca3af; }
    html.dark .icon-btn:hover { background: rgba(45,106,79,0.2); border-color: #52b788; color: #74c69d; }

    html.dark .section-header { color: #fff; }
    html.dark .section-sub { color: #9ca3af; }

    html.dark .date-filter-btn { background: #0d3d26; border-color: rgba(45,106,79,0.35); color: #9ca3af; }

    html.dark #search-submissions,
    html.dark #filter-status { background: rgba(45,106,79,0.12); border-color: rgba(45,106,79,0.35); color: #e5e7eb; }
    html.dark #search-submissions::placeholder { color: #6b7280; }

    html.dark #data-modal-overlay { background: rgba(0,0,0,0.6); }
    html.dark #data-modal { background: #0d3d26; }

    html.dark #no-results-row td { color: #9ca3af; }
    @media (max-width: 639px) {
        html.dark .data-table tbody tr { background: #0d3d26; border-color: rgba(45,106,79,0.35); }
        html.dark .data-table tbody td { border-color: rgba(45,106,79,0.2); }
        html.dark .data-table tbody td::before { color: #9ca3af; }
    }
</style>

<div class="report-page">

    {{-- Page title + date preset filter --}}
    <div class="mb-4 sm:mb-6 flex flex-col sm:flex-row sm:items-end justify-between gap-3">
        <div>
            <h1 class="text-lg sm:text-xl font-bold text-gray-900 tracking-tight">Reports</h1>
            <p class="text-gray-400 text-[11px] sm:text-xs mt-0.5">Submission performance across all waiver templates</p>
        </div>
        {{-- UX FIX: date range presets give KPIs time context --}}
        <div class="flex items-center gap-1.5 overflow-x-auto filter-scroll pb-1 -mx-3 px-3 sm:mx-0 sm:px-0" id="date-presets">
            <button class="date-filter-btn active shrink-0" data-days="0">All time</button>
            <button class="date-filter-btn shrink-0" data-days="7">7 days</button>
            <button class="date-filter-btn shrink-0" data-days="30">30 days</button>
            <button class="date-filter-btn shrink-0" data-days="90">90 days</button>
        </div>
    </div>

    {{-- KPI Strip --}}
    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 sm:gap-3 mb-6 sm:mb-6">

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div>
                    <div class="kpi-value">{{ $totalSent }}</div>
                    <div class="kpi-label">Forms Sent</div>
                    <div class="kpi-period" id="kpi-period-label">All time</div>
                </div>
                <span class="kpi-badge blue">Total</span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div>
                    <div class="kpi-value">{{ $totalSigned }}</div>
                    <div class="kpi-label">Signed</div>
                    <div class="kpi-period" id="kpi-period-label-2">All time</div>
                </div>
                <span class="kpi-badge">{{ $signedPercentage }}%</span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div>
                    <div class="kpi-value">{{ $totalPending }}</div>
                    <div class="kpi-label">Pending</div>
                    <div class="kpi-period" id="kpi-period-label-3">All time</div>
                </div>
                <span class="kpi-badge orange">{{ $pendingPercentage }}%</span>
            </div>
        </div>

        <div class="kpi-card">
            <div class="flex items-start justify-between">
                <div>
                    <div class="kpi-value">{{ $conversionRate }}%</div>
                    <div class="kpi-label">Conversion</div>
                    <div class="kpi-period" id="kpi-period-label-4">All time</div>
                </div>
                <span class="kpi-badge purple">Rate</span>
            </div>
        </div>

    </div>

    {{-- Waiver Performance Cards --}}
    <div class="mb-4 sm:mb-6">
        <div class="flex items-center justify-between mb-2 sm:mb-3">
            <div>
                <div class="section-header">Waiver Performance</div>
                <div class="section-sub">{{ $topWaivers->count() }} templates</div>
            </div>
            <a href="{{ route('waivers.index') }}" class="text-[11px] sm:text-xs font-semibold text-gray-400 hover:text-gray-700 transition">View all →</a>
        </div>

        @if($topWaivers->count() > 0)
        <div class="space-y-2">
            @foreach($topWaivers as $waiver)
            <div class="waiver-card">
                <div class="waiver-icon">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-1 sm:mb-1.5">
                        <span class="text-xs sm:text-sm font-semibold text-gray-900 truncate">{{ $waiver['name'] }}</span>
                        <span class="text-[11px] sm:text-xs font-bold text-gray-700 ml-2 sm:ml-3 shrink-0">{{ $waiver['conversion_rate'] }}%</span>
                    </div>
                    <div class="waiver-progress-track">
                        <div class="waiver-progress-fill" style="width: {{ min($waiver['conversion_rate'], 100) }}%"></div>
                    </div>
                </div>

                <div class="hidden sm:flex items-center gap-1.5 sm:gap-2 shrink-0">
                    <span class="pill pill-blue">{{ $waiver['sent'] }} sent</span>
                    <span class="pill pill-teal">{{ $waiver['signed'] }} signed</span>
                    <span class="pill pill-orange">{{ $waiver['pending'] }} pending</span>
                </div>

                <div class="shrink-0 hidden md:block w-20 lg:w-24 text-right">
                    @if($waiver['conversion_rate'] >= 80)
                        <span class="pill pill-teal">Top performer</span>
                    @elseif($waiver['conversion_rate'] >= 50)
                        <span class="pill pill-blue">On track</span>
                    @elseif($waiver['conversion_rate'] < 30 && $waiver['sent'] > 0)
                        <span class="pill pill-orange">Needs review</span>
                    @else
                        <span class="pill pill-gray">—</span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="kpi-card flex flex-col items-center justify-center py-8 sm:py-10 text-center">
            <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-1">No waivers yet</p>
            <p class="text-[11px] sm:text-xs text-gray-400 mb-3">Create your first waiver to track performance here.</p>
            <a href="{{ route('waivers.create') }}" class="text-white text-[11px] sm:text-xs font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                Create waiver
            </a>
        </div>
        @endif
    </div>

    {{-- Submissions Table --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

        <div class="px-3 sm:px-5 py-3 sm:py-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-3">
            <div>
                <div class="section-header">Submissions</div>
                <div class="section-sub">Individual form statuses</div>
            </div>
            <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap">
                {{-- Search --}}
                <div class="relative flex-1 sm:flex-none">
                    <svg class="w-3.5 h-3.5 absolute left-2.5 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" id="search-submissions" placeholder="Search…"
                        class="pl-8 pr-3 py-1.5 border border-gray-200 text-gray-800 text-xs rounded-lg focus:outline-none focus:border-[#2D6A4F] focus:ring-2 focus:ring-[#2D6A4F]/10 transition w-full sm:w-36 lg:w-44 bg-gray-50"
                        aria-label="Search submissions">
                </div>
                {{-- Status filter --}}
                <select id="filter-status" class="px-2 sm:px-2.5 py-1.5 border border-gray-200 text-gray-700 text-xs rounded-lg focus:outline-none focus:border-[#2D6A4F] transition bg-gray-50" aria-label="Filter by status">
                    <option value="all">All</option>
                    <option value="signed">Signed</option>
                    <option value="pending">Pending</option>
                    <option value="viewed">Viewed</option>
                </select>
                {{-- UX FIX: CSV export --}}
                <a href="{{ route('submissions.export', ['status' => request('status','all'), 'days' => request('days',0)]) }}"
                   class="icon-btn shrink-0" title="Export CSV" aria-label="Export as CSV">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </a>
            </div>
        </div>

        @if($submissions->count() > 0)
        <div class="overflow-x-auto">
            <table class="data-table" id="submissions-table">
                <thead>
                    <tr>
                        <th class="text-left">Waiver</th>
                        <th class="text-left">Client</th>
                        <th class="text-left">Status</th>
                        <th class="text-left hidden sm:table-cell">Date</th>
                        <th class="text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                    <tr class="submission-row"
                        data-status="{{ $submission->status }}"
                        data-waiver="{{ strtolower($submission->waiver->title ?? '') }}"
                        data-client="{{ strtolower($submission->client?->name ?? '') }}">

                        <td data-label="Waiver">
                            <span class="font-semibold text-gray-900 text-xs">{{ $submission->waiver->title ?? '—' }}</span>
                        </td>

                        <td data-label="Client">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-white text-[10px] font-bold shrink-0"
                                     style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                                    {{ strtoupper(substr($submission->client?->name ?? '?', 0, 1)) }}
                                </div>
                                <span class="font-medium text-gray-800 text-xs">{{ $submission->client?->name ?? '—' }}</span>
                            </div>
                        </td>

                        <td data-label="Status">
                            @if($submission->status === 'signed')
                                <span class="inline-flex items-center text-[10px] font-semibold">
                                    <span class="status-dot" style="background:#2D6A4F"></span>
                                    <span style="color:#2D6A4F">Signed</span>
                                </span>
                            @elseif($submission->status === 'pending')
                                <span class="inline-flex items-center text-[10px] font-semibold">
                                    <span class="status-dot" style="background:#f97316"></span>
                                    <span class="text-orange-600">Pending</span>
                                </span>
                            @else
                                <span class="inline-flex items-center text-[10px] font-semibold text-gray-500">
                                    <span class="status-dot" style="background:#d1d5db"></span>
                                    {{ ucfirst($submission->status) }}
                                </span>
                            @endif
                        </td>

                        <td data-label="Date" class="hidden sm:table-cell text-gray-400 text-[11px]">
                            {{ $submission->created_at->format('M d, Y') }}
                        </td>

                        <td class="text-right">
                            <div class="flex items-center justify-end gap-1">

                                @if($submission->submission)
                                <button
                                    onclick="openDataModal({{ $submission->id }})"
                                    class="icon-btn" title="View responses" aria-label="View client responses">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </button>
                                @endif

                                <a href="{{ route('waivers.show', $submission->waiver_id) }}" class="icon-btn" title="View waiver" aria-label="View waiver" style="display:inline-flex;align-items:center;justify-content:center;">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>

                                @if($submission->status === 'signed')
                                <a href="{{ route('submissions.download', $submission->id) }}"
                                   class="icon-btn" title="Download PDF" aria-label="Download signed PDF">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                @endif

                                @if($submission->status !== 'signed')
                                <button
                                    onclick="sendReminder(this, {{ $submission->id }})"
                                    class="icon-btn" title="Send reminder" aria-label="Send reminder">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                </button>
                                @endif

                            </div>
                        </td>
                    </tr>
                    @endforeach

                    <tr id="no-results-row">
                        <td colspan="5">
                            <div class="flex flex-col items-center py-8">
                                <svg class="w-8 h-8 text-gray-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <p class="text-sm font-semibold text-gray-400">No results found</p>
                                <p class="text-xs text-gray-300 mt-0.5">Try a different search or filter.</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-3 sm:px-5 py-3 border-t border-gray-100 bg-gray-50/40 text-xs text-gray-400 overflow-x-auto">
            {{ $submissions->links() }}
        </div>

        @else
        <div class="flex flex-col items-center justify-center py-10 sm:py-14 text-center px-4">
            <p class="text-xs sm:text-sm font-semibold text-gray-500 mb-1">No submissions yet</p>
            <p class="text-[11px] sm:text-xs text-gray-400 mb-3">Send your first waiver to start collecting data.</p>
            <a href="{{ route('waivers.create') }}" class="text-white text-[11px] sm:text-xs font-semibold px-3 sm:px-4 py-1.5 sm:py-2 rounded-lg" style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                Create waiver
            </a>
        </div>
        @endif
    </div>

</div>

{{-- Toast notification --}}
<div id="toast" role="status" aria-live="polite"></div>

{{-- Response Modal --}}
<div id="data-modal-overlay" class="fixed inset-0 bg-black/40 z-50 hidden" onclick="closeDataModal()"></div>
<div id="data-modal" class="fixed hidden z-50 bg-white rounded-xl shadow-2xl overflow-hidden w-[calc(100%-24px)] sm:w-full max-w-md" style="top:50%;left:50%;transform:translate(-50%,-50%);max-height:85vh;" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="px-4 sm:px-5 py-3 sm:py-4 border-b border-gray-100 flex items-center justify-between">
        <div>
            <div id="modal-title" class="text-xs sm:text-sm font-bold text-gray-900">Client Responses</div>
            <div class="text-[10px] text-gray-400 mt-0.5">Submitted form data</div>
        </div>
        <button onclick="closeDataModal()" class="w-7 h-7 rounded-lg hover:bg-gray-100 flex items-center justify-center text-gray-400 transition" aria-label="Close modal">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <div id="data-modal-body" class="overflow-y-auto p-4 sm:p-5" style="max-height: calc(85vh - 70px);"></div>
    <div class="modal-fade"></div>
</div>

@endsection

@push('scripts')
<script>
    const CSRF      = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
    const BASE_URL  = '{{ route("submissions.index") }}';

    function showToast(msg, duration = 3000) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.classList.add('show');
        setTimeout(() => t.classList.remove('show'), duration);
    }

    document.querySelectorAll('[data-days]').forEach(btn => {
        btn.addEventListener('click', function () {
            document.querySelectorAll('[data-days]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const days = parseInt(this.dataset.days, 10);
            const label = days === 0 ? 'All time' : `Last ${days} days`;
            document.querySelectorAll('[id^="kpi-period-label"]').forEach(el => el.textContent = label);
        });
    });

    const searchInput  = document.getElementById('search-submissions');
    const statusFilter = document.getElementById('filter-status');
    const noResultsRow = document.getElementById('no-results-row');

    function filterSubmissions() {
        const q      = (searchInput?.value ?? '').toLowerCase().trim();
        const status = statusFilter?.value ?? 'all';
        let visibleCount = 0;

        document.querySelectorAll('.submission-row').forEach(row => {
            const matchSearch = row.dataset.waiver.includes(q) || row.dataset.client.includes(q);
            const matchStatus = status === 'all' || row.dataset.status === status;
            const visible = matchSearch && matchStatus;
            row.style.display = visible ? '' : 'none';
            if (visible) visibleCount++;
        });

        if (noResultsRow) {
            noResultsRow.style.display = visibleCount === 0 ? '' : 'none';
        }
    }

    searchInput?.addEventListener('input', filterSubmissions);
    statusFilter?.addEventListener('change', filterSubmissions);

    async function sendReminder(btn, submissionId) {
        btn.disabled = true;
        btn.style.opacity = '0.4';
        try {
            const res = await fetch(`${BASE_URL}/${submissionId}/remind`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            });
            if (!res.ok) throw new Error('Request failed');
            showToast('✓ Reminder sent successfully');
            btn.innerHTML = `<svg class="w-3.5 h-3.5" style="color:#2D6A4F" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>`;
        } catch {
            showToast('✗ Failed to send reminder. Please try again.');
            btn.disabled = false;
            btn.style.opacity = '';
        }
    }

    async function openDataModal(submissionId) {
        const body = document.getElementById('data-modal-body');
        body.innerHTML = '<div class="flex items-center justify-center py-10"><div class="w-5 h-5 border-2 rounded-full animate-spin" style="border-color:#e5e7eb;border-top-color:#2D6A4F"></div></div>';
        document.getElementById('data-modal-overlay').classList.remove('hidden');
        document.getElementById('data-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        try {
            const res  = await fetch(`${BASE_URL}/${submissionId}/responses`, {
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
            });
            if (!res.ok) throw new Error('Failed to load');
            const data = await res.json();
            renderModalBody(body, data);
        } catch {
            body.innerHTML = `<div class="flex flex-col items-center py-10 text-center">
                <p class="text-sm font-semibold text-gray-500 mb-1">Failed to load responses</p>
                <p class="text-xs text-gray-400">Please refresh and try again.</p>
            </div>`;
        }
    }

    function renderModalBody(body, { fields = [], responses = {}, signature_url = '' }) {
        const esc = s => String(s)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

        if (!Object.keys(responses).length) {
            body.innerHTML = `<div class="flex flex-col items-center py-10 text-center">
                <p class="text-sm font-semibold text-gray-500 mb-1">No data captured</p>
                <p class="text-xs text-gray-400">This submission was signed before data tracking was enabled.</p>
            </div>`;
            return;
        }

        let html = '<div class="space-y-4">';
        fields.forEach(field => {
            if (field.type === 'signature') return;
            const label = esc(field.label ?? 'Field');
            let value = responses[field.id] ?? '—';
            if (Array.isArray(value)) value = value.join(', ');
            value = esc(value);
            html += `<div class="pb-3 border-b border-gray-100 last:border-0 last:pb-0">
                <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-1">${label}</div>
                <div class="text-sm font-medium text-gray-900 break-words">${value}</div>
            </div>`;
        });
        html += '</div>';

        if (signature_url && signature_url.startsWith('https://')) {
            html += `<div class="mt-4 pt-4 border-t border-gray-100">
                <div class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest mb-2">Signature</div>
                <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 inline-block">
                    <img src="${esc(signature_url)}" alt="Client signature" class="h-14" loading="lazy">
                </div>
            </div>`;
        }

        body.innerHTML = html;
    }

    function closeDataModal() {
        document.getElementById('data-modal-overlay').classList.add('hidden');
        document.getElementById('data-modal').classList.add('hidden');
        document.getElementById('data-modal-body').innerHTML = '';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDataModal(); });
</script>
@endpush