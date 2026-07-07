@extends('layouts.app')

@section('title', 'Templates')

@section('content')

    {{-- Page Specific Styles --}}
    <style>
        .template-card {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
            animation: fadeInUp 0.4s ease forwards;
        }

        @keyframes fadeInUp {
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .template-card:nth-child(1) { animation-delay: 0.05s; }
        .template-card:nth-child(2) { animation-delay: 0.1s; }
        .template-card:nth-child(3) { animation-delay: 0.15s; }
        .template-card:nth-child(4) { animation-delay: 0.2s; }
        .template-card:nth-child(5) { animation-delay: 0.25s; }
        .template-card:nth-child(6) { animation-delay: 0.3s; }
        .template-card:nth-child(7) { animation-delay: 0.35s; }
        .template-card:nth-child(8) { animation-delay: 0.4s; }
        .template-card:nth-child(9) { animation-delay: 0.45s; }
        .template-card:nth-child(10) { animation-delay: 0.5s; }
        .template-card:nth-child(11) { animation-delay: 0.55s; }
        .template-card:nth-child(12) { animation-delay: 0.6s; }
        .template-card:nth-child(13) { animation-delay: 0.65s; }

        /* ── 3D card shell: beveled border (light top/left, dark bottom/right) for a raised, embossed edge ── */
        .template-card {
            position: relative;
            background: linear-gradient(145deg, #ffffff, #f0fdf4);
            border-radius: 14px;
            border-top: 2px solid #74c69d;
            border-left: 2px solid #52b788;
            border-right: 2px solid #1a5c3a;
            border-bottom: 2px solid #0B3D2E;
            box-shadow:
                0 1px 2px rgba(45,106,79,0.06),
                0 8px 16px -8px rgba(45,106,79,0.25),
                inset 1px 1px 0 rgba(255,255,255,0.6),
                inset -2px -2px 4px rgba(11,61,46,0.25);
            transform-style: preserve-3d;
            transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        }
        /* subtle bottom-right "depth" sliver for extra plate curvature */
        .template-card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 12px;
            box-shadow: inset 0 -10px 14px -12px rgba(45,106,79,0.15);
            pointer-events: none;
        }
        html.dark .template-card {
            background: linear-gradient(145deg, #1c2b22, #121d16);
            border-top: 2px solid #40916c;
            border-left: 2px solid #2D6A4F;
            border-right: 2px solid #0B3D2E;
            border-bottom: 2px solid #051d16;
            box-shadow:
                0 1px 2px rgba(0,0,0,0.3),
                0 10px 18px -8px rgba(0,0,0,0.6),
                inset 1px 1px 0 rgba(255,255,255,0.08),
                inset -2px -2px 5px rgba(0,0,0,0.4);
        }
        html.dark .template-card::after {
            box-shadow: inset 0 -10px 14px -12px rgba(0,0,0,0.4);
        }

        .template-card:hover {
            transform: translateY(-5px) scale(1.025) perspective(600px) rotateX(1.5deg);
            box-shadow:
                0 2px 4px rgba(45,106,79,0.12),
                0 20px 30px -12px rgba(45,106,79,0.4),
                inset 1px 1px 0 rgba(255,255,255,0.7),
                inset -2px -2px 4px rgba(11,61,46,0.3);
            border-top-color: #95d5b2;
            border-left-color: #74c69d;
            border-right-color: #1a5c3a;
            border-bottom-color: #083326;
        }
        html.dark .template-card:hover {
            box-shadow:
                0 2px 4px rgba(45,106,79,0.2),
                0 20px 30px -12px rgba(45,106,79,0.5),
                inset 1px 1px 0 rgba(255,255,255,0.12),
                inset -2px -2px 5px rgba(0,0,0,0.45);
            border-top-color: #52b788;
            border-left-color: #40916c;
            border-right-color: #0B3D2E;
            border-bottom-color: #041a11;
        }

        .template-card:hover .use-btn {
            opacity: 1;
            transform: scale(1);
        }

        .use-btn {
            opacity: 0;
            transform: scale(0.9);
            transition: all 0.2s ease;
        }

        /* ── Icon swatch: dual-tone brand gradient ── */
        .tpl-icon {
            background: linear-gradient(135deg, #0B3D2E, #2D6A4F);
            box-shadow: 0 3px 8px -2px rgba(45,106,79,0.5), inset 0 1px 0 rgba(255,255,255,0.25);
        }

        /* ── Category badge: brand tint ── */
        .tpl-badge {
            background: rgba(45,106,79,0.1);
            color: #2D6A4F;
            border: 1px solid rgba(45,106,79,0.18);
        }
        html.dark .tpl-badge {
            background: rgba(82,183,136,0.18);
            color: #74c69d;
            border-color: rgba(82,183,136,0.2);
        }

        .filter-btn {
            transition: all 0.2s ease;
        }

        .filter-btn:hover {
            transform: scale(1.05);
        }

        /* ── Dark mode: text, footer, filter chips, empty state ── */
        html.dark h2.text-gray-900 { color: #fff; }
        html.dark .text-gray-500 { color: #9ca3af; }
        html.dark .text-gray-400 { color: #6b7280; }
        html.dark .template-card h3.text-gray-900 { color: #fff; }

        html.dark .tpl-footer {
            background: #16161f;
            border-color: #2a2a3a;
        }

        html.dark .filter-btn.bg-white {
            background: #1e1e2e;
            border-color: #2a2a3a;
            color: #9ca3af;
        }
        html.dark .filter-btn.bg-white:hover {
            border-color: #2D6A4F;
            color: #74c69d;
        }

        html.dark #no-results .bg-gray-100 { background: #1e1e2e; }
        html.dark #no-results .text-gray-400 { color: #6b7280; }
        html.dark #no-results .text-gray-500 { color: #9ca3af; }
    </style>

    {{-- Page Title --}}
    <div class="mb-4">
        <h2 class="text-xl font-bold text-gray-900">Prebuilt Templates</h2>
        <p class="text-gray-500 text-xs mt-0.5">Pick a template and start sending in minutes.</p>
    </div>

    {{-- Category Filter --}}
    <div class="flex items-center gap-2 mb-4 flex-wrap">
        <button onclick="filterCategory('all')" id="filter-all"
            class="filter-btn active-filter px-3 py-1 rounded-full text-xs font-medium transition"
            style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F); color: white;">
            All ({{ $templates->count() }})
        </button>
        @foreach($templates->pluck('category')->unique() as $cat)
        <button onclick="filterCategory('{{ $cat }}')"
            class="filter-btn px-3 py-1 rounded-full text-xs font-medium bg-white border border-gray-200 text-gray-600 hover:border-[#2D6A4F]/30 hover:text-[#2D6A4F] transition"
            data-category="{{ $cat }}">
            {{ $cat }} ({{ $templates->where('category', $cat)->count() }})
        </button>
        @endforeach
    </div>
@php
    $categoryIcons = [
        'Legal'     => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'Fitness'   => 'M20.5 8.5l-4-4-9 9-2 6 6-2 9-9zM3 21h18',
        'Medical'   => 'M9 12h6m-3-3v6m7 4H5a2 2 0 01-2-2V5a2 2 0 012-2h9.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'Events'    => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'Education' => 'M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.42A12.02 12.02 0 0121 12c0 2.21-.9 4.21-2.36 5.66M12 14l-6.16-3.42A12.02 12.02 0 003 12c0 2.21.9 4.21 2.36 5.66M12 14v7',
        'Business'  => 'M9 17V7a2 2 0 012-2h6a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2zm-4 0V9a2 2 0 012-2h1',
        'Nonprofit' => 'M12 21C12 21 4 14.5 4 9a4.5 4.5 0 018-2.7A4.5 4.5 0 0120 9c0 5.5-8 12-8 12z',
        'Beauty'    => 'M12 3v18m-6-9h12M7 6a5 5 0 0110 0M7 18a5 5 0 0010 0',
        'Sports'    => 'M12 21a9 9 0 100-18 9 9 0 000 18zM3 12h18M12 3a13 13 0 010 18M12 3a13 13 0 000 18',
    ];
@endphp
    {{-- Templates Grid - 5 columns on large screens, 4 on medium, 3 on small, 2 on mobile --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3" id="templates-grid">
        @foreach($templates as $template)
        <div class="template-card rounded-lg shadow-sm transition-all duration-300 overflow-hidden flex flex-col"
             data-category="{{ $template['category'] }}">

            {{-- Card Body --}}
            <div class="p-3 flex-1">
                <div class="flex items-start justify-between mb-2">
                    <div class="w-9 h-9 tpl-icon rounded-lg flex items-center justify-center shrink-0">
    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="{{ $categoryIcons[$template['category']] ?? $categoryIcons['Legal'] }}"/>
    </svg>
</div>
                    <span class="tpl-badge text-[9px] font-semibold px-1.5 py-0.5 rounded-full">
                        {{ $template['category'] }}
                    </span>
                </div>

                <h3 class="font-bold text-gray-900 text-xs mb-1 leading-tight">{{ $template['title'] }}</h3>
                <p class="text-gray-500 text-[10px] leading-relaxed mb-2 line-clamp-2">{{ $template['description'] }}</p>

                {{-- Field count --}}
                <div class="flex items-center gap-2 text-[9px] text-gray-400">
                    <span class="flex items-center gap-0.5">
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        {{ count($template['fields']) }} fields
                    </span>
                </div>
            </div>

            {{-- Card Footer --}}
            <div class="tpl-footer px-3 py-2 bg-gray-50 border-t border-gray-100 flex items-center justify-between">
                <span class="text-[9px] text-gray-400">Free</span>
                <form action="{{ route('templates.use', $template['id']) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="use-btn flex items-center gap-0.5 text-white text-[10px] font-semibold px-2 py-1 rounded transition shadow-sm"
                        style="background: linear-gradient(135deg, #0B3D2E, #2D6A4F)">
                        Use
                        <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Empty state if no templates match filter --}}
    <div id="no-results" class="hidden text-center py-16">
        <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <p class="text-gray-500 font-medium">No templates in this category</p>
    </div>

@endsection

{{-- Page-Specific JavaScript --}}
@push('scripts')
<script>
    // Category filter
    function filterCategory(category) {
        const cards = document.querySelectorAll('.template-card');
        const btns = document.querySelectorAll('.filter-btn');
        let visible = 0;

        cards.forEach((card, index) => {
            if (category === 'all' || card.dataset.category === category) {
                card.style.display = '';
                card.style.animation = 'none';
                setTimeout(() => {
                    card.style.animation = `fadeInUp 0.4s ease forwards ${index * 0.05}s`;
                }, 10);
                visible++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update button styles
        btns.forEach(btn => {
            btn.style.background = '';
            btn.style.color = '';
            btn.classList.remove('text-white');
            btn.classList.add('bg-white', 'text-gray-600');
        });

        const activeBtn = category === 'all'
            ? document.getElementById('filter-all')
            : document.querySelector(`[data-category="${category}"]`);

        if (activeBtn) {
            activeBtn.style.background = 'linear-gradient(135deg, #0B3D2E, #2D6A4F)';
            activeBtn.style.color = 'white';
        }

        document.getElementById('no-results').classList.toggle('hidden', visible > 0);
    }
</script>
@endpush