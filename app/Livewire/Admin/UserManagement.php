<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User; // Asegúrate de que el namespace y el nombre del modelo User son correctos
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Layout; // <<--- ¡Importa esto!

// <<--- AÑADE ESTA LÍNEA AQUÍ.
// Le dice a Livewire que use la vista 'resources/views/layouts/app.blade.php' como su layout.
#[Layout('layouts.app')] 
class UserManagement extends Component
{
    use WithPagination; // Habilita la paginación para Livewire

    public $search = ''; // Propiedad para el campo de búsqueda
    public $roles = ['admin', 'user']; // Roles permitidos para cambiar
    public $statuses = ['active', 'warned', 'suspended']; // Estados permitidos para cambiar

    protected $queryString = ['search']; // Sincroniza la búsqueda con la URL

    /**
     * Renderiza la vista del componente.
     * El layout se aplica automáticamente gracias a #[Layout].
     */
    public function render()
    {
        // Obtener todos los usuarios, excluyendo al usuario autenticado (para evitar gestionarse a sí mismo)
        $users = User::where('id', '!=', Auth::id())
                    ->where(function($query) {
                        // Aplica el filtro de búsqueda por nombre o email si $search no está vacío
                        $query->where('name', 'like', '%'.$this->search.'%')
                              ->orWhere('email', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('name') // Ordena los usuarios por nombre
                    ->paginate(15); // Pagina los resultados, 15 usuarios por página

        return view('livewire.admin.user-management', [
            'users' => $users // Pasa los usuarios paginados a la vista
        ]);
        
    }

    /**
     * Cambia el rol de un usuario.
     * @param User $user El modelo de usuario a actualizar.
     * @param string $newRole El nuevo rol a asignar ('admin' o 'user').
     */
    public function changeRole(User $user, $newRole)
    {
        // Verifica que el nuevo rol sea uno de los roles permitidos
        if (in_array($newRole, $this->roles)) {
            $user->role = $newRole; // Asigna el nuevo rol
            $user->save(); // Guarda los cambios en la base de datos
            session()->flash('success', 'Rol del usuario ' . $user->name . ' actualizado a ' . $newRole . '.');
        } else {
            session()->flash('error', 'Rol no válido.');
        }
    }

    /**
     * Cambia el estado de un usuario (activo, advertido, suspendido).
     * @param User $user El modelo de usuario a actualizar.
     * @param string $newStatus El nuevo estado a asignar.
     */
    public function changeStatus(User $user, $newStatus)
    {
        // Verifica que el nuevo estado sea uno de los estados permitidos
        if (in_array($newStatus, $this->statuses)) {
            $user->status = $newStatus; // Asigna el nuevo estado
            $user->save(); // Guarda los cambios
            session()->flash('success', 'Estado del usuario ' . $user->name . ' actualizado a ' . $newStatus . '.');
        } else {
            session()->flash('error', 'Estado no válido.');
        }
    }

    /**
     * Elimina un usuario de la base de datos.
     * @param User $user El modelo de usuario a eliminar.
     */
    public function deleteUser(User $user)
    {
        // Guarda el nombre antes de eliminar para el mensaje de confirmación
        $userName = $user->name;
        $user->delete(); // Elimina el usuario

        session()->flash('success', 'Usuario ' . $userName . ' eliminado.');
    }
}