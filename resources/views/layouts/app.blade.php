<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" {{-- Modo oscuro sigue en <html> --}}
    x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))"
    x-bind:class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'QuestLog') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&family=Fira+Code&display=swap" rel="stylesheet">
    <head>
    <link rel="icon" type="image/png" href="{{ asset('favicons/source/favicon-96x96.png') }}" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicons/source/favicon.svg') }}" />
    <link rel="shortcut icon" href="{{ asset('favicons/source/favicon.ico') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/source/apple-touch-icon.png') }}" />
    <link rel="manifest" href="{{ asset('favicons/source/site.webmanifest') }}" />
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    </head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased" style="background-color: var(--bg-primary);">

    <div x-data="{ showNotification: false, notificationMessage: '' }"
        @product-added.window="
            console.log('Evento product-added recibido vía @:', $event.detail); // Nuevo log
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
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform translate-y-2"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform translate-y-2"
            class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-lg shadow-md z-50"
            style="display: none;">
            <p x-text="notificationMessage"></p>
        </div>

    </div> 

    @livewireScripts
    @stack('scripts')
</body>
</html>