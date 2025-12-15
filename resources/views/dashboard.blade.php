<x-app-layout>
    <div class="py-6 sm:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @isset($popularItems) 
                <x-carousel :slides="$popularItems" title="Juegos y Animes Populares" />
            @endisset

            <!-- Grid adaptativo: 1 columna en móvil, 3 columnas en desktop -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8 mt-6 sm:mt-8">
                
                <!-- Feed principal: ocupa todo el ancho en móvil -->
                <main class="lg:col-span-2 order-2 lg:order-1">
                    <h2 class="text-xl sm:text-2xl font-bold mb-4 sm:mb-6 text-[var(--text-primary)]">
                        Feed de Actividad
                    </h2> 
                    
                    <div class="space-y-3 sm:space-y-4">
                        @forelse ($activities as $activity)
                            <div class="flex gap-3 sm:gap-4 bg-[var(--bg-secondary)] p-3 sm:p-4 rounded-lg border-l-4 border-[var(--border-color)] shadow-md">
                                <a href="{{ route('profiles.show', $activity->user) }}" class="flex-shrink-0">
                                    <img class="h-10 w-10 sm:h-12 sm:w-12 rounded-full object-cover" 
                                    src="{{ $activity->user->avatar_path ? asset('storage/' . $activity->user->avatar_path) : asset('images/default-avatar.png') }}" 
                                    alt="Avatar de {{ $activity->user->name ?? 'Usuario Anónimo' }}">
                                </a>
                                <div class="w-full min-w-0">
                                    @if(view()->exists("dashboard.activities._{$activity->type}"))
                                        @include("dashboard.activities._{$activity->type}", ['activity' => $activity])
                                    @else
                                        <p class="text-sm sm:text-base text-[var(--text-secondary)] break-words">
                                            <span class="font-semibold text-[var(--text-primary)]">{{ $activity->user->name ?? 'Usuario Anónimo' }}</span> 
                                            ha realizado una actividad de tipo {{ $activity->type }}.
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-[var(--bg-secondary)] p-6 rounded-lg text-center shadow-lg">
                                <p class="text-sm sm:text-base text-[var(--text-secondary)]">
                                    Todavía no hay actividad en la plataforma. ¡Sé el primero!
                                </p>
                            </div>
                        @endforelse
                    </div>
                </main>

                <!-- Sidebar: aparece primero en móvil, a la derecha en desktop -->
                <aside class="space-y-4 sm:space-y-6 order-1 lg:order-2">
                    
                    <!-- Perfil de usuario -->
                    <div class="bg-[var(--bg-secondary)] p-4 sm:p-6 rounded-lg text-center shadow-lg border border-[var(--border-color)]">
                        <img class="h-16 w-16 sm:h-20 sm:w-20 rounded-full mx-auto mb-2 border-2 border-[var(--text-primary)] object-cover" 
                            src="{{ Auth::user()->avatar_path ? asset('storage/' . Auth::user()->avatar_path) : asset('images/default-avatar.png') }}" 
                            alt="Avatar de {{ Auth::user()->name }}">
                        <h4 class="font-bold text-base sm:text-lg text-[var(--text-primary)] mb-2">
                            {{ Auth::user()->name }}
                        </h4>
                        <a href="{{ route('profile.edit') }}" 
                           class="text-xs sm:text-sm text-[var(--text-secondary)] hover:underline">
                            Ver Perfil Completo
                        </a>
                    </div>

                    <!-- Mi Progreso -->
                    <div class="bg-[var(--bg-secondary)] p-4 sm:p-6 rounded-lg shadow-lg border border-[var(--border-color)]">
                        <h4 class="font-bold text-base sm:text-lg text-[var(--text-primary)] mb-3 sm:mb-4">
                            Mi Progreso
                        </h4>
                        <ul class="space-y-2 text-xs sm:text-sm text-[var(--text-secondary)]">
                            <li class="flex justify-between items-center">
                                <span>Jugando actualmente:</span> 
                                <strong class="text-white text-sm sm:text-base">{{ $stats['playing'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center">
                                <span>Viendo esta temporada:</span> 
                                <strong class="text-white text-sm sm:text-base">{{ $stats['watching'] }}</strong>
                            </li>
                            <li class="flex justify-between items-center">
                                <span>Completados (Total):</span> 
                                <strong class="text-white text-sm sm:text-base">{{ $stats['completed'] }}</strong>
                            </li>
                        </ul>
                    </div>

                    <!-- Tendencias -->
                    <div class="bg-[var(--bg-secondary)] p-4 sm:p-6 rounded-lg shadow-lg border border-[var(--border-color)]">
                        <h4 class="font-bold text-base sm:text-lg text-[var(--text-primary)] mb-3 sm:mb-4">
                            Tendencias de la Semana
                        </h4>
                        <ul class="space-y-3">
                            @forelse ($trendingItems as $item)
                                <li class="flex items-center gap-2 sm:gap-3">
                                    <img src="{{ $item->cover_image_url ?? asset('images/default-game-cover.png') }}" 
                                         alt="{{ $item->title }}" 
                                         class="w-8 h-10 sm:w-10 sm:h-14 object-cover rounded flex-shrink-0">
                                    <div class="text-xs sm:text-sm text-[var(--text-secondary)] break-words min-w-0">
                                        <span class="line-clamp-2">{{ $item->title }}</span>
                                        <span class="text-xs text-gray-400">({{ $item->additions_count }} adiciones)</span>
                                    </div>
                                </li>
                            @empty
                                <p class="text-xs sm:text-sm text-gray-400">
                                    No hay suficientes datos para mostrar tendencias.
                                </p>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Últimas Noticias -->
                    <div class="bg-[var(--bg-secondary)] p-4 sm:p-6 rounded-lg shadow-lg border border-[var(--border-color)]">
                        <h4 class="text-base sm:text-xl font-bold text-[var(--text-primary)] mb-3 sm:mb-4">
                            Últimas Noticias
                        </h4>
                        <ul class="space-y-2 text-[var(--text-secondary)]">
                            <li>
                                <a href="#" class="hover:text-[var(--text-primary)] hover:underline text-xs sm:text-sm block">
                                    Los 10 RPGs que no te puedes perder
                                </a>
                            </li>
                            <li>
                                <a href="#" class="hover:text-[var(--text-primary)] hover:underline text-xs sm:text-sm block">
                                    Análisis de la temporada de anime
                                </a>
                            </li>
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>