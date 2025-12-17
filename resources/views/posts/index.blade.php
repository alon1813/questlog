<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Últimas Noticias
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($posts as $post)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg flex flex-col">
                        <img src="{{ $post->image_url ?? asset('images/default-post-image.png') }}" 
                            alt="Imagen del post" 
                            class="w-full h-48 object-cover"
                            loading="lazy"
                            decoding="async">
                        <div class="p-6 flex flex-col flex-grow">
                            <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $post->title }}</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                Por {{ $post->user->name }} - {{ $post->created_at->format('d M, Y') }}
                            </p>
                            <p class="mt-4 text-gray-800 dark:text-gray-200 text-sm flex-grow">
                                {{ Str::limit($post->body, 150) }}
                            </p>
                            <a href="{{ route('posts.show', $post) }}" class="mt-4 self-end text-indigo-600 dark:text-indigo-400 hover:underline">
                                Leer más &rarr;
                            </a>
                        </div>
                    </div>
                @endforeach

            </div>

            <div class="mt-8">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>