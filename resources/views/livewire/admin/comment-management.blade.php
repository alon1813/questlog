<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Moderar Comentarios
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session()->has('success'))
                <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 rounded" role="alert">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Buscar en comentarios o por autor
                        </label>
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            id="search"
                            placeholder="Escribe para buscar..."
                            class="w-full p-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                        >
                    </div>

                    <div>
                        <label for="statusFilter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Filtrar por estado
                        </label>
                        <select 
                            wire:model.live="statusFilter"
                            id="statusFilter"
                            class="w-full p-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                        >
                            <option value="">Todos los estados</option>
                            <option value="visible">Visible</option>
                            <option value="hidden">Oculto</option>
                        </select>
                    </div>
                </div>

                @if($search || $statusFilter)
                    <button 
                        wire:click="$set('search', ''); $set('statusFilter', '')"
                        class="mt-4 text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
                    >
                        ✕ Limpiar filtros
                    </button>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Comentario</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Autor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Post</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Estado</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Acciones</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($comments as $comment)
                                <tr wire:key="comment-{{ $comment->id }}">
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                        {{ Str::limit($comment->body, 50) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $comment->user->name ?? 'Usuario Eliminado' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <a href="{{ route('posts.show', $comment->post) }}" 
                                        class="text-indigo-600 dark:text-indigo-400 hover:underline" 
                                        target="_blank">
                                            {{ Str::limit($comment->post->title, 30) }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <select 
                                            wire:change="updateStatus({{ $comment->id }}, $event.target.value)"
                                            class="dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm border-gray-300 dark:border-gray-700 text-sm"
                                        >
                                            <option value="visible" {{ $comment->status == 'visible' ? 'selected' : '' }}>
                                                Visible
                                            </option>
                                            <option value="hidden" {{ $comment->status == 'hidden' ? 'selected' : '' }}>
                                                Oculto
                                            </option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm">
                                        <button 
                                            wire:click="deleteComment({{ $comment->id }})"
                                            wire:confirm="¿Eliminar este comentario permanentemente?"
                                            class="text-red-600 dark:text-red-400 hover:underline"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                        No se encontraron comentarios.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</div>