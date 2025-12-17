<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
use App\Notifications\AccountSuspendedNotification;
use App\Notifications\AccountWarningNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
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
            session()->flash('error', 'Error al cambiar el rol: ' . $e->getMessage());
        }
    }

    /**
     * Envía notificación de forma segura (no falla si el email no funciona)
     */
    private function sendNotificationSafely($user, $notification)
    {
        try {
            // Intentar enviar la notificación
            Notification::send($user, $notification);
            return true;
        } catch (\Exception $e) {
            // Si falla, registrar en logs pero NO romper el flujo
            Log::warning('Email notification failed', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'notification' => get_class($notification),
                'error' => $e->getMessage()
            ]);
            return false;
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

            $emailSent = false;

            // Si se selecciona "warned" manualmente, incrementar avisos
            if ($newStatus === 'warned') {
                $user->warnings_count++;
                
                // Si llega a 3 avisos, suspender automáticamente
                if ($user->warnings_count >= 3) {
                    $user->status = 'suspended';
                    $emailSent = $this->sendNotificationSafely($user, new AccountSuspendedNotification());
                    
                    $message = 'Usuario ' . $user->name . ' suspendido automáticamente tras 3 avisos.';
                    $message .= $emailSent ? ' Email enviado.' : ' (Email no enviado - revisar configuración)';
                    session()->flash('success', $message);
                } else {
                    $user->status = 'warned';
                    $emailSent = $this->sendNotificationSafely($user, new AccountWarningNotification($user->warnings_count));
                    
                    $message = 'Advertencia #' . $user->warnings_count . ' registrada para ' . $user->name . '.';
                    $message .= $emailSent ? ' Email enviado.' : ' (Email no enviado - revisar configuración)';
                    session()->flash('success', $message);
                }
            } 
            // Si se suspende directamente
            elseif ($newStatus === 'suspended' && $previousStatus !== 'suspended') {
                $user->status = 'suspended';
                $emailSent = $this->sendNotificationSafely($user, new AccountSuspendedNotification());
                
                $message = 'Usuario ' . $user->name . ' suspendido.';
                $message .= $emailSent ? ' Email enviado.' : ' (Email no enviado - revisar configuración)';
                session()->flash('success', $message);
            } 
            // Si se reactiva de suspendido
            elseif ($newStatus === 'active' && $previousStatus === 'suspended') {
                $user->status = 'active';
                $user->warnings_count = 0;
                session()->flash('success', 'Usuario ' . $user->name . ' reactivado. Avisos reseteados.');
            }
            // Si se pasa de warned a active (sin resetear avisos)
            elseif ($newStatus === 'active' && $previousStatus === 'warned') {
                $user->status = 'active';
                session()->flash('success', 'Usuario ' . $user->name . ' activado. Avisos mantenidos: ' . $user->warnings_count);
            }
            // Cualquier otro cambio directo
            else {
                $user->status = $newStatus;
                session()->flash('success', 'Estado de ' . $user->name . ' actualizado a ' . $newStatus . '.');
            }

            // IMPORTANTE: Guardar DESPUÉS de enviar notificaciones
            $user->save();
            
        } catch (\Exception $e) {
            Log::error('Error changing user status', [
                'user_id' => $userId,
                'new_status' => $newStatus,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error al cambiar el estado. Por favor, revisa la configuración de correo.');
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