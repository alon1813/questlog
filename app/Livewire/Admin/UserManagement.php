<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Notifications\AccountSuspendedNotification;
use App\Notifications\AccountWarningNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            Log::error('Error en changeRole', [
                'userId' => $userId,
                'newRole' => $newRole,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Error al cambiar el rol: ' . $e->getMessage());
        }
    }

    public function addWarning($userId)
    {
        DB::beginTransaction();
        
        try {
            $user = User::findOrFail($userId);
            
            if ($user->status === 'suspended') {
                session()->flash('error', 'No se pueden añadir avisos a usuarios suspendidos.');
                DB::rollBack();
                return;
            }

            $user->warnings_count = ($user->warnings_count ?? 0) + 1;

            if ($user->warnings_count >= 3) {
                $user->status = 'suspended';
                $user->save();
                DB::commit();
                
                try {
                    $user->notify(new AccountSuspendedNotification());
                } catch (\Exception $e) {
                    Log::error('Error enviando notificación de suspensión', [
                        'userId' => $userId,
                        'error' => $e->getMessage()
                    ]);
                }
                
                session()->flash('success', '⚠️ Usuario ' . $user->name . ' SUSPENDIDO tras alcanzar 3 avisos.');
                return;
            }

            $user->status = 'warned';
            $user->save();
            DB::commit();
            
            try {
                $user->notify(new AccountWarningNotification($user->warnings_count));
            } catch (\Exception $e) {
                Log::error('Error enviando notificación de advertencia', [
                    'userId' => $userId,
                    'error' => $e->getMessage()
                ]);
            }
            
            session()->flash('success', '⚠️ Advertencia #' . $user->warnings_count . ' enviada a ' . $user->name . '. (' . (3 - $user->warnings_count) . ' restantes antes de suspensión)');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en addWarning', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Error al añadir advertencia: ' . $e->getMessage());
        }
    }

    public function resetWarnings($userId)
    {
        try {
            $user = User::findOrFail($userId);
            
            $previousWarnings = $user->warnings_count;
            $user->warnings_count = 0;

            if ($user->status === 'warned') {
                $user->status = 'active';
            }
            
            $user->save();
            
            session()->flash('success', '✅ Avisos de ' . $user->name . ' reseteados (tenía ' . $previousWarnings . ' avisos).');
            
        } catch (\Exception $e) {
            Log::error('Error en resetWarnings', [
                'userId' => $userId,
                'error' => $e->getMessage()
            ]);
            session()->flash('error', 'Error al resetear avisos: ' . $e->getMessage());
        }
    }

    public function changeStatus($userId, $newStatus)
    {
        DB::beginTransaction();
        
        try {
            $user = User::findOrFail($userId);
            $previousStatus = $user->status;
            
            if (!in_array($newStatus, $this->statuses)) {
                session()->flash('error', 'Estado no válido.');
                DB::rollBack();
                return;
            }

            if ($newStatus === 'warned') {
                $this->addWarning($userId);
                return;
            }

            if ($newStatus === 'suspended' && $previousStatus !== 'suspended') {
                $user->status = 'suspended';
                $user->save();
                DB::commit();
                
                try {
                    $user->notify(new AccountSuspendedNotification());
                } catch (\Exception $e) {
                    Log::error('Error enviando notificación', ['error' => $e->getMessage()]);
                }
                
                session()->flash('success', '🔒 Usuario ' . $user->name . ' suspendido manualmente.');
                return;
            }

            if ($newStatus === 'active' && $previousStatus === 'suspended') {
                $user->status = 'active';
                $user->warnings_count = 0;
                $user->save();
                DB::commit();
                
                session()->flash('success', '✅ Usuario ' . $user->name . ' reactivado. Avisos reseteados.');
                return;
            }

            if ($newStatus === 'active' && $previousStatus === 'warned') {
                $user->status = 'active';
                $user->save();
                DB::commit();
                
                session()->flash('success', '✅ Usuario ' . $user->name . ' activado. Avisos mantenidos: ' . $user->warnings_count);
                return;
            }

            $user->status = $newStatus;
            $user->save();
            DB::commit();
            
            session()->flash('success', 'Estado de ' . $user->name . ' actualizado a ' . $newStatus . '.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en changeStatus', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error al cambiar el estado: ' . $e->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        try {
            $user = User::findOrFail($userId);
            $userName = $user->name;
            $user->delete();

            session()->flash('success', '🗑️ Usuario ' . $userName . ' eliminado correctamente.');
        } catch (\Exception $e) {
            Log::error('Error en deleteUser', ['error' => $e->getMessage()]);
            session()->flash('error', 'Error al eliminar usuario: ' . $e->getMessage());
        }
    }
}