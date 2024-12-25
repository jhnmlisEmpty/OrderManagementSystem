<?php

namespace App\Livewire\Employee;

use App\Models\User;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

use Livewire\Component;

class TableEmployee extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $perPage = 10;  

    public $search = '';  

    protected $paginationTheme = 'bootstrap';

    protected $listeners = [
        'confirmDelete',
        'cancelDelete'
    ];

    // Function to trigger delete confirmation
    public function deleteUser($id)
    {
        $this->alert('question', 'Are you sure you want to delete this user?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'YES',
            'onConfirmed' => 'confirmDelete',
            'showDenyButton' => true,
            'denyButtonText' => 'NO',
            'onDenied' => 'cancelDelete',
            'data' => [
                    'id' => $id
                ]
        ]);
    }

    public function confirmDelete($data)
    { 
        $id = $data['id'];

        $user = User::findOrFail($id);
        $user->delete();

        $this->alert('success', 'User deleted successfully!');
        $this->resetPage();
    }

    public function cancelDelete()
    {
        $this->alert('info', 'User deletion canceled.');
    }

    public function render()
    {
        $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
        })
        ->where('role', '!=', 'super_admin')
        ->paginate($this->perPage);

        return view('livewire.employee.table-employee', [
            'users' => $users,
        ]);
    }

}
