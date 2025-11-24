<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gestionar: {{ $item->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:flex md:space-x-6">
                    <div class="md:w-1/3">
                        <img src="{{ $item->cover_image_url ?? asset('images/default-game-cover.png') }}" 
                        alt="Portada de {{ $item->title }}" 
                        class="rounded-lg w-full object-cover aspect-[3/4]">
                    </div>

                    <div class="md:w-2/3 mt-6 md:mt-0">
                        
                        <form action="{{ route('user-list.update', $userListItem) }}" method="POST"> 
                            @csrf
                            @method('PUT')

                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Estado</label>
                                <select name="status" id="status" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="Pendiente" @selected($userListItem->status == 'Pendiente')>Pendiente</option>
                                    <option value="Jugando" @selected($userListItem->status == 'Jugando')>
                                        {{ $item->type === 'anime' ? 'Viendo' : 'Jugando' }}
                                    </option>
                                    <option value="Completado" @selected($userListItem->status == 'Completado')>Completado</option>
                                    <option value="Abandonado" @selected($userListItem->status == 'Abandonado')>Abandonado</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="score" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Puntuación (sobre 10)</label>
                                <select name="score" id="score" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="">Sin puntuar</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" @selected($userListItem->score == $i)>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div class="mt-4">
                                <label for="review" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Reseña</label>
                                <textarea name="review" id="review" rows="5" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ $userListItem->review }}</textarea>
                            </div>

                            
                            @if ($item->type === 'anime' && $item->episodes > 0) 
                                <div class="mt-4">
                                    <label for="episodes_watched" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Progreso de Episodios</label>
                                    <div class="flex items-center space-x-2 mt-1">
                                        
                                        <input type="number" name="episodes_watched" id="episodes_watched" value="{{ $userListItem->episodes_watched ?? 0 }}" min="0" max="{{ $item->episodes }}" class="block w-24 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">/ {{ $item->episodes }} episodios</span>
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-center justify-end mt-6">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Guardar Cambios
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                        Información Adicional
                    </h3>
                    <div class="mt-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                            
                            @if ($item->type === 'anime' && $item->episodes) 
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Episodios</dt>
                                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $item->episodes }}</dd>
                                </div>
                            @endif

                            @if ($averageScore !== null)
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Puntuación Media</dt>
                                    <dd class="mt-1 text-lg font-bold text-yellow-400">⭐ {{ $averageScore }} / 10</dd>
                                    <dd class="text-xs text-gray-400">Basada en {{ $scoreCount }} voto(s)</dd>
                                </div>
                            @else
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Puntuación Media</dt>
                                    <dd class="mt-1 text-sm text-gray-400">Aún no hay suficientes votos.</dd>
                                </div>
                            @endif

                            {{-- En el futuro, podríamos añadir aquí detalles para videojuegos, como plataformas --}}
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Lanzamiento</dt>
                                {{-- Aquí podríamos mostrar la fecha de lanzamiento si la tuviéramos guardada --}}
                            </div>
                        </dl>
                    </div>
                    <div class="mt-12 border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-xl font-bold leading-6 text-gray-900 dark:text-gray-100 mb-6">
                            Reseñas de la Comunidad
                        </h3>
                        <div class="space-y-6">
                            @forelse ($publicReviews as $review)
                                <div class="flex space-x-4">
                                    
                                    <a href="{{ route('profiles.show', ['user' => $review->user->username]) }}" class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-full object-cover" 
                                        src="{{ $review->user->avatar ? asset('storage/' . $review->user->avatar) : asset('images/default-avatar.png') }}" 
                                        alt="Avatar">
                                    </a>
                                    <div class="flex-grow bg-[var(--bg-primary)] p-4 rounded-lg border border-[var(--border-color)]">
                                        <div class="flex justify-between items-start mb-2"> 
                                            <p>
                                                
                                                <a href="{{ route('profiles.show', ['user' => $review->user->username]) }}" class="font-bold hover:underline text-white">{{ $review->user->name }}</a>
                                                @if ($review->score)
                                                    <span class="ml-2 text-yellow-400 font-bold">⭐ {{ $review->score }} / 10</span>
                                                @endif
                                            </p>
                                            
                                            <livewire:helpful-vote-button :reviewPivot="$review" :key="'vote-'.$review->id" />
                                        </div>
                                        
                                        <p class="text-[var(--text-secondary)] text-sm">
                                            {!! nl2br(e($review->review)) !!}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-400 text-center">Todavía no hay reseñas de otros usuarios para este ítem.</p>
                            @endforelse
                        </div>
                        <div class="mt-8">
                            {{ $publicReviews->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>