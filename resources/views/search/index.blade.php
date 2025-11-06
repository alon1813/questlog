<x-app-layout> 
    <x-slot name="header"> 
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"> {{ __('Buscar Videojuegos y Animes') }} </h2> 
    </x-slot> 
        <div class="py-12"> <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6"> 

            <div id="react-search"> </div> 
            @viteReactRefresh 
            @vite(['resources/js/app.js', 'resources/js/components/SearchPage.jsx'])
            
            @if (session('success')) 
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900 dark:text-green-200" role="alert"> 
                    {{ session('success') }} 
                </div> 
            @endif 
                @if (session('info')) 
                    <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 border border-blue-400 text-blue-700 dark:text-blue-200 rounded" role="alert"> {{ session('info') }} </div> 
                @endif 
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6"> 
                    @if (!empty($results)) 
                    @foreach ($results as $result) 
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 flex flex-col md:flex-row items-center border border-gray-300 dark:border-gray-600 shadow-sm"> 
                        <img src="{{ $result['cover_image_url'] }}" alt="{{ $result['title'] }}" class="w-24 h-32 object-cover rounded mr-4 mb-4 md:mb-0 shadow-md"> 
                        <div class="flex-grow"> 
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $result['title'] }}</h3> 
                            <p class="text-gray-600 dark:text-gray-300 text-sm capitalize mb-2">{{ $result['type'] }}</p> 
                            @if ($result['in_collection']) 
                            <form action="{{ route('user-list.destroy', $result['user_list_item_id']) }}" method="POST"> 
                                @csrf @method('DELETE') 
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-lg text-sm flex items-center justify-center transition-colors duration-300 shadow-md"> 
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"> 
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path> 
                                    </svg> Eliminar de Mi Colección </button> 
                                </form> 
                                @else 
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
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-3 rounded-lg text-sm flex items-center justify-center transition-colors duration-300 shadow-md"> 
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"> 
                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path> 
                                        </svg> Añadir a Mi Colección </button> 
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
</x-app-layout>