<?php
// app/Livewire/Admin/UserManagement.php

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
        $user = User::findOrFail($userId);
        
        if (in_array($newRole, $this->roles)) {
            $user->role = $newRole;
            $user->save();
            session()->flash('success', 'Rol del usuario ' . $user->name . ' actualizado a ' . $newRole . '.');
        } else {
            session()->flash('error', 'Rol no válido.');
        }
    }

    public function changeStatus($userId, $newStatus)
    {
        $user = User::findOrFail($userId);
        $previousStatus = $user->status;
        
        if (!in_array($newStatus, $this->statuses)) {
            session()->flash('error', 'Estado no válido.');
            return;
        }

        if ($newStatus === 'warned') {
            $user->warnings_count++;
            
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
        elseif ($newStatus === 'suspended') {
            $user->status = 'suspended';
            $user->notify(new AccountSuspendedNotification());
            session()->flash('success', 'Usuario ' . $user->name . ' suspendido y notificado.');
        } 
        elseif ($newStatus === 'active' && $previousStatus === 'suspended') {
            $user->status = 'active';
            $user->warnings_count = 0; 
            session()->flash('success', 'Usuario ' . $user->name . ' reactivado. Avisos reseteados.');
        } 
        else {
            $user->status = $newStatus;
            session()->flash('success', 'Estado de ' . $user->name . ' actualizado.');
        }

        $user->save();
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $userName = $user->name;
        $user->delete();

        session()->flash('success', 'Usuario ' . $userName . ' eliminado.');
    }
}