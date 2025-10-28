<x-app-layout>
    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8"> {{-- Un poco más ancho para el artículo --}}
            <article>
                {{-- ===== CABECERA CON IMAGEN DE FONDO (DISEÑO IMPACTANTE) ===== --}}
                <header
                    class="relative h-80 md:h-96 rounded-lg flex flex-col justify-end p-6 md:p-10 text-white bg-cover bg-center shadow-lg mb-12"
                    {{-- Usamos la imagen del post o la predeterminada, con gradiente --}}
                    style="background-image: linear-gradient(to top, rgba(0,0,0,0.85) 30%, transparent 90%), url('{{ $post->image_url ?? asset('images/default-post-image.png') }}');">

                    {{-- Título del Post --}}
                    <h1 class="text-3xl md:text-4xl font-black z-10 leading-tight">{{ $post->title }}</h1>

                    {{-- Información del Autor y Fecha --}}
                    <div class="flex items-center gap-3 z-10 mt-4">
                        <a href="{{ route('profiles.show', $post->user) }}">
                            <img class="h-10 w-10 rounded-full object-cover border-2 border-white/50"
                                src="{{ $post->user->avatar_path ? asset('storage/' . $post->user->avatar_path) : asset('images/default-avatar.png') }}"
                                alt="Avatar de {{ $post->user->name }}">
                        </a>
                        <div>
                            <a href="{{ route('profiles.show', $post->user) }}" class="font-semibold text-white hover:underline text-sm">
                                {{ $post->user->name }}
                            </a>
                            <p class="text-xs text-gray-300">{{ $post->created_at->format('d \d\e F, Y') }}</p>
                        </div>
                    </div>
                </header>
                {{-- ============================================================= --}}

                {{-- Contenido del Artículo (Ahora fuera del div de la imagen, y usando Tailwind Typography) --}}
                <div class="bg-[var(--bg-secondary)] dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 md:p-8">
                    <div class="prose prose-lg dark:prose-invert max-w-none text-[var(--text-secondary)]
                                prose-headings:text-[var(--text-primary)] prose-strong:text-white
                                prose-a:text-[var(--text-primary)] prose-blockquote:border-[var(--text-primary)]
                                prose-blockquote:text-gray-400">
                        {!! nl2br(e($post->body)) !!}
                    </div>
                </div>
            </article>

            {{-- Sección de Comentarios (Con el fondo adecuado y título) --}}
            <div class="mt-8 bg-[var(--bg-secondary)] dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <h2 class="text-2xl font-bold text-[var(--text-primary)] dark:text-gray-100">Comentarios ({{ $post->comments->count() }})</h2>

                    <div class="mt-6">
                        @auth
                            <form action="{{ route('comments.store', $post) }}" method="POST">
                                @csrf
                                <textarea name="body" rows="4" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" placeholder="Escribe tu comentario..." required></textarea>
                                <button type="submit" class="mt-4 inline-flex items-center px-4 py-2 bg-gray-800 border rounded-md font-semibold text-xs text-white uppercase hover:bg-gray-700">
                                    Publicar Comentario
                                </button>
                            </form>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">
                                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Inicia sesión</a> para dejar un comentario.
                            </p>
                        @endauth
                    </div>

                    <div class="mt-8 space-y-6">
                        @forelse ($post->comments as $comment)
                            @if ($comment->user)
                                <div class="flex space-x-4">
                                    <a href="{{ route('profiles.show', $comment->user) }}" class="flex-shrink-0">
                                        <img class="h-12 w-12 rounded-full object-cover"
                                            src="{{ $comment->user->avatar_path ? asset('storage/' . $comment->user->avatar_path) : asset('images/default-avatar.png') }}"
                                            alt="Avatar">
                                    </a>
                                    <div class="flex-grow bg-[var(--bg-primary)] p-4 rounded-lg">
                                        <p>
                                            <a href="{{ route('profiles.show', $comment->user) }}" class="font-bold hover:underline text-white">{{ $comment->user->name }}</a>
                                            <span class="text-xs text-gray-400 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                        </p>
                                        <p class="mt-2 text-[var(--text-secondary)]">
                                            {!! nl2br(e($comment->body)) !!}
                                        </p>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <p class="text-gray-400">Todavía no hay comentarios. ¡Sé el primero en comentar!</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>