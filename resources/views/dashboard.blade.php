<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @isset($popularItems) 
                <x-carousel :slides="$popularItems" title="Juegos y Animes Populares" />
            @endisset

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">
                <main class="lg:col-span-2">
                    <h2 class="text-2xl font-bold mb-6 text-[var(--text-primary)]">Feed de Actividad</h2> 
                    <div class="space-y-4">
                        @forelse ($activities as $activity)
                            <div class="flex gap-4 bg-[var(--bg-secondary)] p-4 rounded-lg border-l-4 border-[var(--border-color)] shadow-md"> 
                                <a href="{{ route('profiles.show', $activity->user) }}">
                                    <img class="h-12 w-12 rounded-full object-cover" 
                                    src="{{ $activity->user->avatar_path ? asset('storage/' . $activity->user->avatar_path) : asset('images/default-avatar.png') }}" 
                                    alt="Avatar de {{ $activity->user->name ?? 'Usuario Anónimo' }}">
                                </a>
                                <div class="w-full">
                                    
                                    @if(view()->exists("dashboard.activities._{$activity->type}"))
                                        @include("dashboard.activities._{$activity->type}", ['activity' => $activity])
                                    @else
                                        <p class="text-[var(--text-secondary)]">
                                            <span class="font-semibold text-[var(--text-primary)]">{{ $activity->user->name ?? 'Usuario Anónimo' }}</span> 
                                            ha realizado una actividad de tipo {{ $activity->type }}.
                                        </p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $activity->created_at->diffForHumans() }}</p>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="bg-[var(--bg-secondary)] p-6 rounded-lg text-center shadow-lg"> 
                                <p class="text-[var(--text-secondary)]">Todavía no hay actividad en la plataforma. ¡Sé el primero!</p>
                            </div>
                        @endforelse
                    </div>
                </main>

                <aside class="space-y-6">
                    <div class="bg-[var(--bg-secondary)] p-6 rounded-lg text-center shadow-lg border border-[var(--border-color)]">
                        <img class="h-20 w-20 rounded-full mx-auto mb-2 border-2 border-[var(--text-primary)] object-cover" 
                            src="{{ Auth::user()->avatar_path ? asset('storage/' . Auth::user()->avatar_path) : asset('images/default-avatar.png') }}" 
                            alt="Avatar de {{ Auth::user()->name }}">
                        <h4 class="font-bold text-lg text-[var(--text-primary)] mb-2">{{ Auth::user()->name }}</h4> 
                        <a href="{{ route('profile.edit') }}" class="text-sm text-[var(--text-secondary)] hover:underline">Ver Perfil Completo</a> 
                    </div>
                    <div class="bg-[var(--bg-secondary)] p-6 rounded-lg shadow-lg border border-[var(--border-color)]">
                        <h4 class="font-bold text-[var(--text-primary)] mb-4">Mi Progreso</h4> 
                        <ul class="space-y-2 text-sm text-[var(--text-secondary)]"> 
                            <li class="flex justify-between"><span>Jugando actualmente:</span> <strong class="text-white">{{ $stats['playing'] }}</strong></li>
                            <li class="flex justify-between"><span>Viendo esta temporada:</span> <strong class="text-white">{{ $stats['watching'] }}</strong></li>
                            <li class="flex justify-between"><span>Completados (Total):</span> <strong class="text-white">{{ $stats['completed'] }}</strong></li>
                        </ul>
                    </div>

                    <div class="bg-[var(--bg-secondary)] p-6 rounded-lg shadow-lg border border-[var(--border-color)]">
                        <h4 class="font-bold text-[var(--text-primary)] mb-4">Tendencias de la Semana</h4> 
                        <ul class="space-y-3">
                            @forelse ($trendingItems as $item)
                                <li class="flex items-center gap-3">
                                    <img src="{{ $item->cover_image_url ?? asset('images/default-game-cover.png') }}" 
                                            alt="{{ $item->title }}" 
                                            class="w-10 h-14 object-cover rounded flex-shrink-0"> 
                                    <div class="text-sm text-[var(--text-secondary)]">{{ $item->title }} ({{ $item->additions_count }} adiciones)</div> 
                                </li>
                            @empty
                                <p class="text-sm text-gray-400">No hay suficientes datos para mostrar tendencias.</p>
                            @endforelse
                        </ul>
                    </div>
                    {{-- Widget "Últimas Noticias" (Puedes añadir esto si tienes un controlador y rutas para noticias en el dashboard) --}}
                    <div class="bg-[var(--bg-secondary)] p-6 rounded-lg shadow-lg border border-[var(--border-color)]">
                        <h4 class="text-xl font-bold text-[var(--text-primary)] mb-4">Últimas Noticias</h4>
                        <ul class="space-y-2 text-[var(--text-secondary)]">
                            <li><a href="#" class="hover:text-[var(--text-primary)] hover:underline text-sm">Los 10 RPGs que no te puedes perder</a></li>
                            <li><a href="#" class="hover:text-[var(--text-primary)] hover:underline text-sm">Análisis de la temporada de anime</a></li>
                            {{-- Aquí irían las noticias reales si las tuvieras en $latestNews --}}
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>