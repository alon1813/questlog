<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🔥 Posts Más Populares
            </h2>
            <a href="{{ route('posts.index') }}" class="text-sm text-indigo-600 hover:underline">
                ← Ver todas las noticias
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($posts->isEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-lg p-12 text-center">
                    <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">
                        Aún no hay posts con likes. ¡Sé el primero en dar like!
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($posts as $post)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="{{ $post->image_url ?? asset('images/default-post-image.png') }}" 
                                    alt="Imagen del post" 
                                    class="w-full h-48 object-cover">
                                
                                {{-- 🆕 Badge de popularidad --}}
                                @if($post->likes_count > 10)
                                    <div class="absolute top-2 right-2 bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold flex items-center gap-1">
                                        <i class="fas fa-fire"></i>
                                        <span>{{ $post->likes_count }}</span>
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-6 flex flex-col flex-grow">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">
                                    {{ $post->title }}
                                </h3>
                                
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                    Por {{ $post->user->name }} - {{ $post->created_at->format('d M, Y') }}
                                </p>
                                
                                <p class="mt-4 text-gray-800 dark:text-gray-200 text-sm flex-grow">
                                    {{ Str::limit($post->body, 150) }}
                                </p>
                                
                                {{-- 🆕 Estadísticas mejoradas --}}
                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-heart text-red-500"></i>
                                            {{ $post->likes_count }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-comment"></i>
                                            {{ $post->comments->count() }}
                                        </span>
                                    </div>
                                    
                                    <a href="{{ route('posts.show', $post) }}" 
                                       class="text-indigo-600 dark:text-indigo-400 hover:underline text-sm font-medium">
                                        Leer más →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>