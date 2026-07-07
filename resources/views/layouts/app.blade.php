<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Onboardify')</title>
            <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect width='24' height='24' rx='6' fill='%230B3D2E'/><path fill='none' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' d='M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'/></svg>">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- ✅ Theme init: Tailwind CDN + dark-mode config + anti-flash script.
         Must come before any other style/script that depends on Tailwind classes. --}}
    @include('partials.theme-init')

    {{-- ✅ bellNotifications() MUST be defined BEFORE Alpine loads --}}
    <script>
    function bellNotifications() {
    return {
        open: false,
        count: 0,
        notifications: [],
        loading: false,

        init() {
            this.fetchNotifications();
            setInterval(() => this.fetchNotifications(), 30000);
        },

        toggle() {
            this.open = !this.open;
            if (this.open) this.fetchNotifications();
        },

        async fetchNotifications() {
            this.loading = true;
            try {
                const res  = await fetch('/notifications/fetch', {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await res.json();
                this.count         = data.count;
                this.notifications = data.notifications;
            } catch (e) {
                console.error('Notification error:', e);
            } finally {
                this.loading = false;
            }
        },

        // ✅ NEW: Clear all button handler
        async clearAll() {
            const keys = this.notifications.map(n => n.key);
            if (keys.length === 0) return;

            // Optimistically clear the UI immediately
            this.notifications = [];
            this.count = 0;

            try {
                await fetch('/notifications/dismiss-all', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ keys })
                });
            } catch (e) {
                console.error('Dismiss error:', e);
            }
        }
    }
}
    </script>

    {{-- ✅ Alpine loads AFTER the function is defined above --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        body { font-family: 'Inter', sans-serif; }

        .sidebar-gradient { background: linear-gradient(135deg, #0B3D2E, #374e44); }
        .nav-active { background: rgba(3, 71, 55, 0.808); color: #fff; }
        .nav-item { color: rgba(255,255,255,0.65); transition: all 0.2s; }
        .nav-item:hover { background: rgba(255,255,255,0.1); color: #fff; }

        .card-green { background: linear-gradient(135deg, #0B3D2E, #2D6A4F); }

        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-thumb { background: #e5ebe8; border-radius: 4px; }
        .dark ::-webkit-scrollbar-thumb { background: #374151; }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-[#0a2e1e] text-gray-900 dark:text-gray-100 min-h-screen">    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    @include('partials.sidebar')

 <div class="flex-1 {{ auth()->check() ? 'md:ml-60' : '' }} min-h-screen flex flex-col">
    @include('partials.header')
    <div class="flex-1 px-4 md:px-8 pt-2 pb-6 flex flex-col min-h-0">
        @yield('content')
    </div>
    @include('partials.footer')
</div>
   @stack('scripts')
</body>
</html>