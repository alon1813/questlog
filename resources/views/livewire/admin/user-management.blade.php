{{-- resources/views/livewire/admin/user-management.blade.php --}}
<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                @if (session()->has('success'))
                    <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 text-green-700 dark:text-green-200 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 text-red-700 dark:text-red-200 rounded" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                <input 
                    wire:model.live.debounce.300ms="search" 
                    type="text" 
                    placeholder="Buscar por nombre o email..."
                    class="w-full mb-4 p-2 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nombre</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rol</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avisos</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($users as $user)
                                <tr wire:key="user-{{ $user->id }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <select 
                                            wire:key="role-select-{{ $user->id }}"
                                            wire:change="changeRole({{ $user->id }}, $event.target.value)" 
                                            class="dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm border-gray-300 dark:border-gray-700">
                                            @foreach($roles as $role)
                                                <option value="{{ $role }}" {{ $user->role == $role ? 'selected' : '' }}>
                                                    {{ ucfirst($role) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <select 
                                            wire:key="status-select-{{ $user->id }}"
                                            wire:change="changeStatus({{ $user->id }}, $event.target.value)" 
                                            class="dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm border-gray-300 dark:border-gray-700">
                                            @foreach($statuses as $status)
                                                <option value="{{ $status }}" {{ $user->status == $status ? 'selected' : '' }}>
                                                    {{ ucfirst($status) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center space-x-2">
                                            {{-- Mostrar contador de avisos --}}
                                            @if($user->warnings_count > 0)
                                                <span class="px-2 py-1 text-xs font-bold rounded-full 
                                                    {{ $user->warnings_count >= 3 ? 'bg-red-600 text-white' : 'bg-yellow-500 text-black' }}">
                                                    ⚠️ {{ $user->warnings_count }}/3
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-xs">Sin avisos</span>
                                            @endif
                                            
                                            {{-- Botón para añadir aviso (solo si no está suspendido y tiene menos de 3 avisos) --}}
                                            @if($user->status !== 'suspended' && $user->warnings_count < 3)
                                                <button 
                                                    wire:click="addWarning({{ $user->id }})"
                                                    wire:confirm="¿Enviar advertencia a {{ $user->name }}?"
                                                    class="px-2 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs rounded-md transition-colors"
                                                    title="Añadir advertencia">
                                                    + Avisar
                                                </button>
                                            @endif
                                            
                                            {{-- Botón para resetear avisos (solo si tiene avisos) --}}
                                            @if($user->warnings_count > 0 && $user->status !== 'suspended')
                                                <button 
                                                    wire:click="resetWarnings({{ $user->id }})"
                                                    wire:confirm="¿Resetear todos los avisos de {{ $user->name }}?"
                                                    class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md transition-colors"
                                                    title="Resetear avisos">
                                                    🔄 Reset
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button 
                                            wire:key="delete-btn-{{ $user->id }}"
                                            wire:click="deleteUser({{ $user->id }})" 
                                            wire:confirm="¿Estás seguro de que quieres eliminar a {{ $user->name }}? Esta acción es irreversible."
                                            class="text-red-600 hover:text-red-900 dark:hover:text-red-400">
                                            Eliminar
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300 text-center">
                                        No se encontraron usuarios que coincidan con la búsqueda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>