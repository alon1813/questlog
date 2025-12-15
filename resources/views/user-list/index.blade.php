<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ view: 'grid' }">

            @if (session('success'))
                <div class="mb-4 p-3 sm:p-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 rounded text-sm sm:text-base" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('info'))
                <div class="mb-4 p-3 sm:p-4 bg-blue-100 dark:bg-blue-900 border border-blue-400 text-blue-700 dark:text-blue-200 rounded text-sm sm:text-base" role="alert">
                    {{ session('info') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 sm:p-4 bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 rounded text-sm sm:text-base" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <h1 class="text-2xl sm:text-3xl font-bold mb-4 sm:mb-6 text-[var(--text-primary)]">Mi Colección</h1>

            <!-- Controles: Stack en móvil, flex en desktop -->
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center bg-[var(--bg-secondary)] p-3 sm:p-4 rounded-lg mb-6 sm:mb-8 text-[var(--text-secondary)] gap-3 sm:gap-0">
                
                <!-- Filtros: scroll horizontal en móvil -->
                <div class="filters flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0">
                    <a href="{{ route('user-list.index') }}" 
                       class="px-3 py-1.5 text-xs sm:text-sm rounded-md whitespace-nowrap {{ request('status') === null ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">
                        Todos
                    </a>
                    <a href="{{ route('user-list.index', ['status' => 'Jugando']) }}" 
                       class="px-3 py-1.5 text-xs sm:text-sm rounded-md whitespace-nowrap {{ request('status') === 'Jugando' ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">
                        En Progreso
                    </a>
                    <a href="{{ route('user-list.index', ['status' => 'Completado']) }}" 
                       class="px-3 py-1.5 text-xs sm:text-sm rounded-md whitespace-nowrap {{ request('status') === 'Completado' ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">
                        Completados
                    </a>
                    <a href="{{ route('user-list.index', ['status' => 'Pendiente']) }}" 
                       class="px-3 py-1.5 text-xs sm:text-sm rounded-md whitespace-nowrap {{ request('status') === 'Pendiente' ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">
                        Pendientes
                    </a>
                    <a href="{{ route('user-list.index', ['status' => 'Abandonado']) }}" 
                       class="px-3 py-1.5 text-xs sm:text-sm rounded-md whitespace-nowrap {{ request('status') === 'Abandonado' ? 'bg-[var(--text-primary)] text-white' : 'bg-[var(--bg-tertiary)] hover:bg-[var(--bg-quaternary)]' }}">
                        Abandonados
                    </a>
                </div>
                
                <!-- Toggle de vista -->
                <div class="view-toggle flex items-center justify-center sm:justify-start gap-2">
                    <button @click="view = 'grid'" 
                            :class="{ 'bg-[var(--text-primary)] text-white': view === 'grid', 'bg-[var(--bg-tertiary)]': view !== 'grid' }" 
                            class="px-3 py-1.5 text-xs sm:text-sm rounded-md hover:bg-[var(--bg-quaternary)] transition-colors">
                        Cuadrícula
                    </button>
                    <button @click="view = 'list'" 
                            :class="{ 'bg-[var(--text-primary)] text-white': view === 'list', 'bg-[var(--bg-tertiary)]': view !== 'list' }" 
                            class="px-3 py-1.5 text-xs sm:text-sm rounded-md hover:bg-[var(--bg-quaternary)] transition-colors">
                        Lista
                    </button>
                </div>
            </div>

            <!-- Vista de Cuadrícula: responsive desde 2 hasta 7 columnas -->
            <div x-show="view === 'grid'" 
                 class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 2xl:grid-cols-7 gap-3 sm:gap-4">
                @forelse ($items as $item)
                    <div class="bg-[var(--bg-secondary)] rounded-lg overflow-hidden transform hover:scale-105 transition-transform flex flex-col relative group">
                        
                        <a href="{{ route('user-list.edit', $item->pivot->id) }}" class="block flex-grow">
                            <img src="{{ $item->cover_image_url ?? asset('images/default-game-cover.png') }}"
                                alt="Portada de {{ $item->title }}"
                                class="w-full aspect-[2/3] object-cover">
                        </a>
                        
                        <div class="p-2 flex flex-col items-center justify-between flex-grow">
                            <h3 class="text-xs sm:text-sm font-semibold text-[var(--text-primary)] text-center mb-1 leading-tight line-clamp-2">
                                {{ $item->title }}
                            </h3>
                            
                            <form action="{{ route('user-list.destroy', $item->pivot->id) }}" method="POST" class="w-full mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-1 px-2 rounded-lg text-xs flex items-center justify-center transition-colors duration-300">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="hidden sm:inline">Eliminar</span>
                                    <span class="sm:hidden">×</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-sm sm:text-base text-[var(--text-secondary)] py-8">
                        No tienes ítems en esta categoría.
                    </p>
                @endforelse
            </div>
            
            <!-- Vista de Lista: mejorada para móvil -->
            <div x-show="view === 'list'" class="space-y-3" style="display: none;">
                @forelse ($items as $item)
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 bg-[var(--bg-secondary)] p-3 rounded-lg hover:bg-[var(--bg-tertiary)] transition-colors">
                        
                        <a href="{{ route('user-list.edit', $item->pivot->id) }}" 
                           class="flex items-center gap-3 sm:gap-4 flex-grow min-w-0">
                            <img src="{{ $item->cover_image_url ?? asset('images/default-game-cover.png') }}"
                            alt="Portada de {{ $item->title }}"
                            class="w-12 h-16 sm:w-14 sm:h-20 object-cover rounded flex-shrink-0">
                            
                            <div class="flex-grow min-w-0">
                                <div class="font-semibold text-sm sm:text-base text-[var(--text-primary)] truncate">
                                    {{ $item->title }}
                                </div>
                                <div class="flex flex-wrap items-center gap-2 mt-1">
                                    @if ($item->pivot->score)
                                        <div class="font-bold text-sm sm:text-base text-yellow-400">
                                            ⭐ {{ $item->pivot->score }}
                                        </div>
                                    @endif
                                    <div class="text-xs sm:text-sm px-2 py-0.5 rounded-full bg-[var(--bg-tertiary)] text-[var(--text-secondary)]">
                                        @if ($item->pivot->status === 'Jugando')
                                            {{ $item->type === 'anime' ? 'Viendo' : 'Jugando' }}
                                        @else
                                            {{ $item->pivot->status }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <form action="{{ route('user-list.destroy', $item->pivot->id) }}" 
                              method="POST" 
                              class="flex-shrink-0 w-full sm:w-auto">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full sm:w-auto bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-3 rounded-lg text-xs sm:text-sm flex items-center justify-center transition-colors duration-300">
                                <svg class="w-4 h-4 sm:mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm6 0a1 1 0 012 0v6a1 1 0 11-2 0V8z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="hidden sm:inline">Eliminar</span>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-center text-sm sm:text-base text-[var(--text-secondary)] py-8">
                        No tienes ítems en esta categoría.
                    </p>
                @endforelse
            </div>
            
        </div>
    </div>
</x-app-layout>