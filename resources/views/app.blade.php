<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="cerberus"
    @if (($appearance ?? 'system') === 'dark') data-mode="dark"
    @elseif (($appearance ?? 'system') === 'light') data-mode="light"
    @endif
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.ts'])
        @inertiaHead

        <script>
            (function() {
                const appearance = '{{ $appearance ?? "system" }}';

                function applyTheme(mode) {
                    if (mode === 'dark' || (mode === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                        document.documentElement.setAttribute('data-mode', 'dark');
                    } else {
                        document.documentElement.removeAttribute('data-mode');
                    }
                }

                applyTheme(appearance);

                if (appearance === 'system') {
                    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function() {
                        applyTheme('system');
                    });
                }
            })();
        </script>
    </head>
    <body class="bg-surface-50-950 font-sans antialiased">
        @inertia
    </body>
</html>
