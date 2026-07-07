{{--
    Theme initialization. Include this once inside head, before any
    other script, on every page (login, register, dashboard, layouts.app, etc).

    Usage:
    <head>
        ...
        @include('partials.theme-init')
        ...
    </head>
--}}

<script>
    (function () {
        const saved = localStorage.getItem('onboardify-theme');
        document.documentElement.classList.toggle('dark', saved === 'dark');
    })();
</script>