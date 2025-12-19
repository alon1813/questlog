<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Notifications\AccountSuspendedNotification;
use App\Notifications\AccountWarningNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class UserManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $roles = ['admin', 'user'];
    public $statuses = ['active', 'warned', 'suspended'];

    protected $queryString = ['search'];

    public function render()
    {
        $users = User::where('id', '!=', Auth::id())
                    ->where(function($query) {
                        $query->where('name', 'like', '%'.$this->search.'%')
                            ->orWhere('email', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('name')
                    ->paginate(15);

        return view('livewire.admin.user-management', [
            'users' => $users
        ]);
    }

    public function changeRole($userId, $newRole)
    {
        try {
            $user = User::findOrFail($userId);
            
            if (!in_array($newRole, $this->roles)) {
                session()->flash('error', 'Rol no válido.');
                return;
            }

            $user->role = $newRole;
            $user->save();
            
            session()->flash('success', 'Rol del usuario ' . $user->name . ' actualizado a ' . $newRole . '.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el rol: ' . $e->getMessage());
        }
    }

    public function changeStatus($userId, $newStatus)
    {
        try {
            $user = User::findOrFail($userId);
            $previousStatus = $user->status;
            
            if (!in_array($newStatus, $this->statuses)) {
                session()->flash('error', 'Estado no válido.');
                return;
            }

            // Si se selecciona "warned" manualmente, incrementar avisos
            if ($newStatus === 'warned') {
                $user->warnings_count++;
                
                // Si llega a 3 avisos, suspender automáticamente
                if ($user->warnings_count >= 3) {
                    $user->status = 'suspended';
                    $user->notify(new AccountSuspendedNotification());
                    session()->flash('success', 'Usuario ' . $user->name . ' suspendido automáticamente tras 3 avisos.');
                } else {
                    $user->status = 'warned';
                    $user->notify(new AccountWarningNotification($user->warnings_count));
                    session()->flash('success', 'Advertencia #' . $user->warnings_count . ' enviada a ' . $user->name . '.');
                }
            } 
            // Si se suspende directamente
            elseif ($newStatus === 'suspended' && $previousStatus !== 'suspended') {
                $user->status = 'suspended';
                $user->notify(new AccountSuspendedNotification());
                session()->flash('success', 'Usuario ' . $user->name . ' suspendido y notificado.');
            } 
            // Si se reactiva de suspendido
            elseif ($newStatus === 'active' && $previousStatus === 'suspended') {
                $user->status = 'active';
                $user->warnings_count = 0; // Reset avisos al reactivar
                session()->flash('success', 'Usuario ' . $user->name . ' reactivado. Avisos reseteados.');
            }
            // Si se pasa de warned a active (sin resetear avisos)
            elseif ($newStatus === 'active' && $previousStatus === 'warned') {
                $user->status = 'active';
                // NO resetear warnings_count aquí para mantener historial
                session()->flash('success', 'Usuario ' . $user->name . ' activado. Avisos mantenidos: ' . $user->warnings_count);
            }
            // Cualquier otro cambio directo
            else {
                $user->status = $newStatus;
                session()->flash('success', 'Estado de ' . $user->name . ' actualizado a ' . $newStatus . '.');
            }

            $user->save();
            
        } catch (\Exception $e) {
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $userName = $user->name;
            $user->delete();

            session()->flash('success', 'Usuario ' . $userName . ' eliminado correctamente.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }
}