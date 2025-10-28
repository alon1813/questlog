<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buscar Videojuegos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form action="{{ route('search.index') }}" method="GET" class="mb-6">
                        <div class="flex items-center">
                            <input type="text" name="query" placeholder="Buscar..." class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" value="{{ request('query') }}">
                            <button type="submit" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">Buscar</button>
                        </div>
                            <div class="mt-4 flex items-center space-x-2">
                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100 mr-2">Buscar por:</span>
                                <div class="flex rounded-md bg-gray-200 dark:bg-gray-700 p-1">
                                    
                                    <input type="radio" id="type_game" name="type" value="game" class="hidden peer" {{ request('type', 'game') === 'game' ? 'checked' : '' }}>
                                    <label for="type_game" class="px-4 py-1 text-sm font-semibold text-gray-600 dark:text-gray-300 rounded-md cursor-pointer peer-checked:bg-white dark:peer-checked:bg-gray-900 peer-checked:text-gray-900 dark:peer-checked:text-gray-100 peer-checked:shadow">
                                        Videojuegos
                                    </label>
                                    
                                    <input type="radio" id="type_anime" name="type" value="anime" class="hidden peer" {{ request('type') === 'anime' ? 'checked' : '' }}>
                                    <label for="type_anime" class="px-4 py-1 text-sm font-semibold text-gray-600 dark:text-gray-300 rounded-md cursor-pointer peer-checked:bg-white dark:peer-checked:bg-gray-900 peer-checked:text-gray-900 dark:peer-checked:text-gray-100 peer-checked:shadow">
                                        Anime
                                    </label>

                                </div>
                            </div>
                        
                    </form>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('info'))
                        <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 border border-blue-400 text-blue-700 dark:text-blue-200 rounded" role="alert">
                            {{ session('info') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @if (!empty($results))
                        @foreach ($results as $result)
                            <div class="bg-[var(--bg-secondary)] rounded-lg p-4 flex flex-col md:flex-row items-center border border-[var(--border-color)]">
                                <img src="{{ $result['cover_image_url'] }}" alt="{{ $result['title'] }}" class="w-24 h-32 object-cover rounded mr-4 mb-4 md:mb-0">
                                <div class="flex-grow">
                                    <h3 class="text-xl font-semibold text-[var(--text-primary)]">{{ $result['title'] }}</h3>
                                    <p class="text-[var(--text-secondary)] text-sm capitalize mb-2">{{ $result['type'] }}</p>

                                    @if ($result['in_collection'])
                                        {{-- Formulario para ELIMINAR --}}
                                        {{-- ¡¡¡AHORA SÍ!!! Pasar el user_list_item_id correcto --}}
                                        <form action="{{ route('user-list.destroy', $result['user_list_item_id']) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-lg text-sm flex items-center justify-center transition-colors duration-300">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path>
                                                </svg>
                                                Eliminar de Mi Colección
                                            </button>
                                        </form>
                                    @else
                                        {{-- Formulario para AÑADIR --}}
                                        <form action="{{ route('user-list.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="api_id" value="{{ $result['api_id'] }}">
                                            <input type="hidden" name="type" value="{{ $result['type'] }}">
                                            <input type="hidden" name="title" value="{{ $result['title'] }}">
                                            @if (isset($result['cover_image_url']))
                                                <input type="hidden" name="cover_image_url" value="{{ $result['cover_image_url'] }}">
                                            @endif
                                            @if ($result['type'] === 'anime' && isset($result['episodes']))
                                                <input type="hidden" name="episodes" value="{{ $result['episodes'] }}">
                                            @endif
                                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-3 rounded-lg text-sm flex items-center justify-center transition-colors duration-300">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                </svg>
                                                Añadir a Mi Colección
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        @elseif (request('query'))
                                <p class="text-gray-600 dark:text-gray-400 col-span-full">No se encontraron resultados para "{{ request('query') }}".</p>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>