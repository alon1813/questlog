{{-- resources/views/items/show.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalles de: {{ $item->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 flex flex-col sm:flex-row gap-8">
                    <div class="flex-shrink-0">
                        <img src="{{ $item->cover_image_url }}" alt="Portada de {{ $item->title }}" class="rounded-lg max-w-xs object-cover">
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold mb-2">{{ $item->title }}</h1>
                        <p class="text-gray-400 mb-4">{{ $item->type === 'game' ? 'Juego' : 'Anime' }}</p>
                        
                        @if ($item->description)
                            <p class="mb-4">{{ $item->description }}</p>
                        @endif

                        @if ($item->release_date)
                            <p class="text-sm text-gray-500">Fecha de Lanzamiento: {{ $item->release_date->format('d/m/Y') }}</p>
                        @endif

                        @if ($item->episodes > 0 && $item->type === 'anime')
                            <p class="text-sm text-gray-500">Episodios: {{ $item->episodes }}</p>
                        @endif

                        {{-- Puedes añadir más detalles aquí --}}

                        <a href="{{ url()->previous() }}" class="mt-6 inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Volver
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>