<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-col sm:flex-row items-center gap-8 mb-8">
                <img class="h-40 w-40 rounded-full object-cover border-4 border-[var(--text-primary)]" src="{{ $user->avatar_path ? asset('storage/' . $user->avatar_path) : asset('images/default-avatar.png') }}" alt="Avatar de {{ $user->name }}">
                <div class="text-center sm:text-left">
                    <h2 class="text-4xl font-black text-white">{{ $user->name }}</h2>
                    <p class="text-xl text-gray-400 mb-2">@ {{ $user->username }}</p>

                    <div class="flex justify-center sm:justify-start space-x-6 text-white mb-4">
                        <div><span class="font-bold">{{ $user->followers_count }}</span> <span class="text-gray-400">Seguidores</span></div>
                        <div><span class="font-bold">{{ $user->following_count }}</span> <span class="text-gray-400">Siguiendo</span></div>
                    </div>

                    @auth
                        @if (auth()->user()->id !== $user->id)
                            <livewire:follow-button :user="$user" />
                        @else
                            
                            <a href="{{ route('profile.edit') }}" class="px-6 py-2 bg-[var(--text-primary)] text-white font-bold rounded-lg hover:opacity-80">
                                Editar Perfil
                            </a>
                        @endif
                    @endauth
                </div>
            </div>

            <div class="bg-[var(--bg-secondary)] p-6 rounded-lg shadow-lg mb-8">
                <h3 class="text-xl font-bold text-[var(--text-primary)] mb-4 flex items-center gap-2">
                    <i class="fas fa-heart"></i>
                    Estadísticas de Likes
                </h3>
                
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-[var(--bg-primary)] rounded-lg">
                        <div class="text-3xl font-bold text-red-400">{{ $likeStats['posts'] }}</div>
                        <div class="text-sm text-gray-400 mt-1">En Posts</div>
                    </div>
                    
                    <div class="text-center p-4 bg-[var(--bg-primary)] rounded-lg">
                        <div class="text-3xl font-bold text-pink-400">{{ $likeStats['comments'] }}</div>
                        <div class="text-sm text-gray-400 mt-1">En Comentarios</div>
                    </div>
                    
                    <div class="text-center p-4 bg-[var(--bg-primary)] rounded-lg">
                        <div class="text-3xl font-bold text-purple-400">{{ $likeStats['items'] }}</div>
                        <div class="text-sm text-gray-400 mt-1">En Colección</div>
                    </div>
                    
                    <div class="text-center p-4 bg-[var(--bg-primary)] rounded-lg">
                        <div class="text-3xl font-bold text-blue-400">{{ $likeStats['given'] }}</div>
                        <div class="text-sm text-gray-400 mt-1">Dados</div>
                    </div>
                </div>
            </div>
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-6">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Colección de {{ $user->name }}
                    </h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Aquí están los ítems que {{ $user->name }} ha añadido a su colección.
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">

                    @if($user->items->isNotEmpty())
                        @foreach ($user->items as $item)
                            @php
                                $itemUser = $item->pivot; 
                                $currentUser = auth()->user(); 

                                $isOwnProfile = $currentUser && ($user->id === $currentUser->id);

                                $canInteractWithLike = $currentUser && !$isOwnProfile;

                                $isLikedByCurrentUser = $canInteractWithLike && $itemUser->likes->isNotEmpty();

                                $statusColor = match ($item->pivot->status) {
                                    'Jugando' => 'bg-blue-500',
                                    'Completado' => 'bg-green-500',
                                    'Abandonado' => 'bg-red-500',
                                    default => 'bg-gray-500',
                                };
                            @endphp

                            <div class="relative group">
                                
                                @if ($isOwnProfile)
                                    
                                    <a href="{{ route('user-list.edit', $item->pivot->id) }}" class="block">
                                @else
                                    
                                    <a href="{{ route('items.show', $item->id) }}" class="block"> 
                                @endif

                                    <div class="absolute top-2 right-2 z-10 px-2 py-1 text-xs font-semibold text-white rounded {{ $statusColor }}">
                                        @if ($item->pivot->status === 'Jugando')
                                            {{ $item->type === 'anime' ? 'Viendo' : 'Jugando' }}
                                        @else
                                            {{ $item->pivot->status }}
                                        @endif
                                    </div>

                                    <img src="{{ $item->cover_image_url }}" alt="Portada de {{ $item->title }}" class="rounded-lg w-full h-full object-cover aspect-[3/4] group-hover:opacity-75 transition-opacity">

                                    <div class="absolute inset-0 bg-black bg-opacity-70 rounded-lg flex flex-col justify-end p-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <h4 class="text-white font-bold text-sm">{{ $item->title }}</h4>
                                        @if ($item->pivot->score)
                                            <p class="text-yellow-400 font-bold mt-1">⭐ {{ $item->pivot->score }} / 10</p>
                                        @endif
                                        @if ($item->type === 'anime' && $item->episodes > 0)
                                            <div class="mt-1 text-xs text-gray-300">
                                                <p>Vistos: {{ $item->pivot->episodes_watched }} / {{ $item->episodes }}</p>
                                                <div class="w-full bg-gray-600 rounded-full h-1.5 mt-1">
                                                    <div class="bg-blue-500 h-1.5 rounded-full" style="width: '{{ ($item->pivot->episodes_watched / $item->episodes) * 100 }}%'"></div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </a>

                                @if ($canInteractWithLike)
                                    <livewire:like-button :itemUser="$itemUser" :key="'like-btn-'.$itemUser->id" />
                                @endif
                            </div>
                        @endforeach
                    @else
                        <p class="text-gray-600 dark:text-gray-400 col-span-full">
                            {{ $user->name }} aún no ha añadido ningún ítem a su lista.
                        </p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>