<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gestionar Noticias
            </h2>
            <a href="{{ route('posts.create') }}" 
            class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Crear Noticia
            </a>
        </div>
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
                            Buscar por título
                        </label>
                        <input 
                            wire:model.live.debounce.300ms="search"
                            type="text" 
                            id="search"
                            placeholder="Escribe el título..."
                            class="w-full p-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                        >
                    </div>

                    {{-- Filtro por estado --}}
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
                            <option value="pending_review">Pendiente de Revisión</option>
                            <option value="published">Publicado</option>
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
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Título
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Autor
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th class="relative px-6 py-3">
                                    <span class="sr-only">Acciones</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($posts as $post)
                                <tr wire:key="post-{{ $post->id }}">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ Str::limit($post->title, 50) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $post->user->name }}
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <select 
                                            wire:change="updateStatus({{ $post->id }}, $event.target.value)"
                                            class="dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm border-gray-300 dark:border-gray-700 text-sm"
                                        >
                                            <option value="pending_review" {{ $post->status == 'pending_review' ? 'selected' : '' }}>
                                                Pendiente
                                            </option>
                                            <option value="published" {{ $post->status == 'published' ? 'selected' : '' }}>
                                                Publicado
                                            </option>
                                            <option value="hidden" {{ $post->status == 'hidden' ? 'selected' : '' }}>
                                                Oculto
                                            </option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                                        {{ $post->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                        <a href="{{ route('posts.edit', $post) }}" 
                                        class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            Editar
                                        </a>
                                        <button 
                                            wire:click="deletePost({{ $post->id }})"
                                            wire:confirm="¿Eliminar '{{ $post->title }}'? Esta acción no se puede deshacer."
                                            class="text-red-600 dark:text-red-400 hover:underline"
                                        >
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                        No se encontraron noticias con los filtros aplicados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="mt-4">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>