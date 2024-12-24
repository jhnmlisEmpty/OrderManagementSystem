<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TableOrder extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $perPage = 10;  
    public $search = '';  
    public $isModalOpen = false;
    public $order;

    protected $listeners = [
        'confirmDelete',
        'cancelDelete'
    ];
    

    public function deleteOrder($id)
    {
        $this->alert('question', 'Are you sure you want to delete this order?', [
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

        $order = Order::findOrFail($id);
        $order->delete();

        $this->alert('success', 'Order deleted successfully!');
        $this->resetPage();
    }

    public function cancelDelete()
    {
        $this->alert('info', 'Order deletion canceled.');
    }

    public function openModal($orderId)
    {
        $this->product = Order::find($orderId);
        $this->isModalOpen[$orderId] = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->order = null;
    }


    public function render()
    {
        $user = auth()->user();
    
        $ordersQuery = Order::query();
    
        if ($user->role !== 'admin') {
            $ordersQuery->where('user_id', $user->id);
        }
    
        $ordersQuery->where(function ($query) {
            $query->whereHas('customer', function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('user', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orWhere('platform', 'like', '%' . $this->search . '%'); 
        });
    
        $orders = $ordersQuery->paginate($this->perPage);
    
        return view('livewire.order.table-order', [
            'orders' => $orders,
        ]);
    }
    
    
}

