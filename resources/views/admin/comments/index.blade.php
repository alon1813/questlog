<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Moderar Comentarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-900 border border-green-600 text-green-200 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left ...">Comentario</th>
                            <th class="px-6 py-3 text-left ...">Autor</th>
                            <th class="px-6 py-3 text-left ...">Post</th>
                            <th class="px-6 py-3 text-left ...">Estado</th>
                            <th class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y ...">
                        @foreach ($comments as $comment)
                            <tr>
                                <td class="px-6 py-4 ...">{{ Str::limit($comment->body, 50) }}</td>
                                <td class="px-6 py-4 ...">{{ $comment->user->name ?? 'Usuario Eliminado' }}</td>
                                <td class="px-6 py-4 ..."><a href="{{ route('posts.show', $comment->post) }}" class="hover:underline" target="_blank">{{ Str::limit($comment->post->title, 30) }}</a></td>
                                <td class="px-6 py-4 ...">
                                    <span @class([
                                        'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                        'bg-green-100 text-green-800' => $comment->status === 'visible',
                                        'bg-red-100 text-red-800' => $comment->status === 'hidden',
                                    ])>
                                        {{ $comment->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right ... space-x-2">
                                    @if ($comment->status === 'visible')
                                        <form action="{{ route('admin.comments.updateStatus', $comment) }}" method="POST" class="inline"> @csrf @method('PATCH') <input type="hidden" name="status" value="hidden"><button type="submit" class="text-gray-400 hover:underline">Ocultar</button></form>
                                    @else
                                        <form action="{{ route('admin.comments.updateStatus', $comment) }}" method="POST" class="inline"> @csrf @method('PATCH') <input type="hidden" name="status" value="visible"><button type="submit" class="text-green-400 hover:underline">Mostrar</button></form>
                                    @endif
                                    <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" class="inline" onsubmit="return confirm('¿Eliminar este comentario permanentemente?');"> @csrf @method('DELETE') <button type="submit" class="text-red-400 hover:underline">Eliminar</button></form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</x-app-layout>