<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\User;
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
        
        if (in_array($newStatus, $this->statuses)) {
            $user->status = $newStatus;
            $user->save();
            session()->flash('success', 'Estado del usuario ' . $user->name . ' actualizado a ' . $newStatus . '.');
        } else {
            session()->flash('error', 'Estado no válido.');
        }
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        $userName = $user->name;
        $user->delete();

        session()->flash('success', 'Usuario ' . $userName . ' eliminado.');
    }
}