<?php

namespace App\Livewire\Customer;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TableCustomer extends Component
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

    public function deleteCustomer($id)
    {
        $this->alert('question', 'Are you sure you want to delete this customer?', [
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

        $customer = Customer::findOrFail($id);
        $customer->delete();

        $this->alert('success', 'Customer deleted successfully!');
        $this->resetPage();
    }

    public function cancelDelete()
    {
        $this->alert('info', 'Customer deletion canceled.');
    }

    public function render()
    {
        $user = auth()->user();

        $customersQuery = Customer::query();
    
        if ($user->role === 'user') {
            $customersQuery->where('user_id', $user->id);
        }
    
        $customersQuery->where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('contact_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('user', function ($query) {
                      $query->where('name', 'like', '%' . $this->search . '%');
                  });
        });
    
        $customers = $customersQuery->paginate($this->perPage);
    
        return view('livewire.customer.table-customer', [
            'customers' => $customers,
        ]);
    }
}
