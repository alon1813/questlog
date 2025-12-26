<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') !== 'false'
    }"
    x-init="
        $watch('darkMode', val => {
            localStorage.setItem('darkMode', val);
            if (val) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light-mode');
            } else {
                document.documentElement.classList.remove('dark');
                document.documentElement.classList.add('light-mode');
            }
        });
        // Aplicar estado inicial inmediatamente
        if (darkMode) {
            document.documentElement.classList.add('dark');
            document.documentElement.classList.remove('light-mode');
        } else {
            document.documentElement.classList.remove('dark');
            document.documentElement.classList.add('light-mode');
        }
    "
    :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'QuestLog') }}</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Fira+Code&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <link rel="icon" type="image/png" href="{{ asset('favicons/source/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicons/source/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicons/source/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/source/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('favicons/source/site.webmanifest') }}" />
    @viteReactRefresh
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/main.jsx'])
    @livewireStyles
    @stack('styles')

    <script>
        (function() {
            const darkMode = localStorage.getItem('darkMode') !== 'false';
            if (darkMode) {
                document.documentElement.classList.add('dark');
                document.documentElement.classList.remove('light-mode');
            } else {
                document.documentElement.classList.add('light-mode');
                document.documentElement.classList.remove('dark');
            }
        })();
        async function getSanctumCsrfToken() {
            try {
                const response = await fetch('/sanctum/csrf-cookie', {
                    method: 'GET',
                    headers: { 'Accept': 'application/json' }
                });
            } catch (error) {
                console.error('Error al obtener la cookie CSRF de Sanctum:', error);
            }
        }
        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelector('meta[name="csrf-token"]')) {
                getSanctumCsrfToken();
            }
        });
    </script>
</head>
<body class="font-sans antialiased" style="background-color: var(--bg-primary);">

    <div x-data="{ showNotification: false, notificationMessage: '' }"
        @product-added.window="
            console.log('Evento product-added recibido vía @:', $event.detail);
            notificationMessage = '¡' + $event.detail.productName + ' añadido al carrito!';
            showNotification = true;
            setTimeout(() => showNotification = false, 3000);
        ">

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <div x-show="showNotification"
            x-transition
            class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-lg shadow-md z-50"
            style="display: none;">
            <p x-text="notificationMessage"></p>
        </div>
    </div>
    <livewire:likes-modal />
    @livewireScripts
    @stack('scripts')
</body>
</html>