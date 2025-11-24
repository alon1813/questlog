<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ view: 'grid' }">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="mb-4 p-4 bg-blue-100 dark:bg-blue-900 border border-blue-400 text-blue-700 dark:text-blue-200 rounded" role="alert">
                    {{ session('info') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 rounded" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <h1 class="text-3xl font-bold mb-6 text-[var(--text-primary)]">Mi Colección</h1>

            <div class="flex flex-wrap justify-between items-center bg-[var(--bg-secondary)] p-4 rounded-lg mb-8 text-[var(--text-secondary)]">
                <div class="filters flex items-center space-x-2">
                    
                    <a href="{{ route('user-list.index') }}" class="px-3 py-1 text-sm rounded-md {{ request('status') === null ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">Todos</a>
                    <a href="{{ route('user-list.index', ['status' => 'Jugando']) }}" class="px-3 py-1 text-sm rounded-md {{ request('status') === 'Jugando' ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">En Progreso</a>
                    <a href="{{ route('user-list.index', ['status' => 'Completado']) }}" class="px-3 py-1 text-sm rounded-md {{ request('status') === 'Completado' ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">Completados</a>
                    <a href="{{ route('user-list.index', ['status' => 'Pendiente']) }}" class="px-3 py-1 text-sm rounded-md {{ request('status') === 'Pendiente' ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">Pendientes</a>
                    <a href="{{ route('user-list.index', ['status' => 'Abandonado']) }}" class="px-3 py-1 text-sm rounded-md {{ request('status') === 'Abandonado' ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">Abandonados</a>
                </div>
                <div class="view-toggle flex items-center space-x-2 mt-4 sm:mt-0">
                    
                    <button @click="view = 'grid'" :class="{ 'bg-[var(--text-primary)] text-white': view === 'grid', 'bg-[var(--bg-tertiary)]': view !== 'grid' }" class="px-3 py-1 text-sm rounded-md hover:bg-[var(--bg-quaternary)] transition-colors">Cuadrícula</button>
                    <button @click="view = 'list'" :class="{ 'bg-[var(--text-primary)] text-white': view === 'list', 'bg-[var(--bg-tertiary)]': view !== 'list' }" class="px-3 py-1 text-sm rounded-md hover:bg-[var(--bg-quaternary)] transition-colors">Lista</button>
                </div>
            </div>
            <div x-show="view === 'grid'" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 xl:grid-cols-7 gap-4">
                @forelse ($items as $item)
                    <div class="bg-[var(--bg-secondary)] rounded-lg overflow-hidden transform hover:scale-105 transition-transform flex flex-col relative group">
                        
                        <a href="{{ route('user-list.edit', $item->pivot->id) }}" class="block flex-grow">
                            <img src="{{ $item->cover_image_url ?? asset('images/default-game-cover.png') }}"
                                alt="Portada de {{ $item->title }}"
                                class="w-full aspect-[2/3] object-cover">
                        </a>
                        
                        <div class="p-2 flex flex-col items-center justify-between flex-grow">
                            <h3 class="text-sm font-semibold text-[var(--text-primary)] text-center mb-1 leading-tight">{{ $item->title }}</h3>
                            
                            <form action="{{ route('user-list.destroy', $item->pivot->id) }}" method="POST" class="w-full mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg text-xs flex items-center justify-center transition-colors duration-300">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path>
                                    </svg>
                                    Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-[var(--text-secondary)]">No tienes ítems en esta categoría.</p>
                @endforelse
            </div>
            
            <div x-show="view === 'list'" class="space-y-3" style="display: none;">
                @forelse ($items as $item)
                    <div class="flex items-center gap-4 bg-[var(--bg-secondary)] p-3 rounded-lg hover:bg-[var(--bg-tertiary)] transition-colors">
                        
                        <a href="{{ route('user-list.edit', $item->pivot->id) }}" class="flex items-center gap-4 flex-grow">
                            <img src="{{ $item->cover_image_url ?? asset('images/default-game-cover.png') }}"
                            alt="Portada de {{ $item->title }}"
                            class="w-12 h-16 object-cover rounded">
                            <div class="flex-grow font-semibold text-[var(--text-primary)]">{{ $item->title }}</div>
                            @if ($item->pivot->score)
                                <div class="font-bold text-lg text-yellow-400">⭐ {{ $item->pivot->score }}</div>
                            @endif
                            <div class="text-sm px-3 py-1 rounded-full bg-[var(--bg-tertiary)] text-[var(--text-secondary)]">
                                @if ($item->pivot->status === 'Jugando')
                                    {{ $item->type === 'anime' ? 'Viendo' : 'Jugando' }}
                                @else
                                    {{ $item->pivot->status }}
                                @endif
                            </div>
                        </a>
                        
                        <form action="{{ route('user-list.destroy', $item->pivot->id) }}" method="POST" class="ml-4 flex-shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-lg text-sm flex items-center justify-center transition-colors duration-300">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path>
                                </svg>
                                Eliminar
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-center text-[var(--text-secondary)]">No tienes ítems en esta categoría.</p>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>