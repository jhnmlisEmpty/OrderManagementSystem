<?php

namespace App\Livewire\Production;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class ManageOrders extends Component
{ 
    use WithPagination;
    use LivewireAlert;

    public $currentTab = 'pending';
    public $search = '';
    public $selectedOrder = null;
    public $pendingCount;
    public $onProdCount;
    public $createdCount;
    public $shippedCount;
    public $deliveredCount;
    public $canceledCount;
    public $returnedCount;
    public $reprocessCount;

    protected $queryString = ['search'];


    public function mount()
    {
        $this->loadCounts();
    }

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

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function switchTab($tab)
    {
        $this->currentTab = $tab;
        $this->resetPage();
    }

    public function selectOrder($orderId)
    {
        $this->selectedOrder = Order::with('orderItems')->find($orderId);
    }

    public function updateStatus($status)
    {
        if ($this->selectedOrder) {
            $this->selectedOrder->status = $status;
            $this->selectedOrder->save();
            $this->selectedOrder = null;
            $this->loadCounts();
        }
    }

    
    public function loadCounts()
    {
        $user = auth()->user();
    
        $pendingQuery = Order::where('status', 'pending');
        $onProdQuery = Order::where('status', 'on_prod');
        $createdQuery = Order::where('status', 'created');
        $shippedQuery = Order::where('status', 'shipped');
        $deliveredQuery = Order::where('status', 'delivered');
        $canceledQuery = Order::where('status', 'canceled');
        $returnedQuery = Order::where('status', 'returned');
        $reprocessQuery = Order::where('status', 'reprocess');
    
        if ($user->role === 'user') {
            $pendingQuery->where('user_id', $user->id);
            $onProdQuery->where('user_id', $user->id);
            $createdQuery->where('user_id', $user->id);
            $shippedQuery->where('user_id', $user->id);
            $deliveredQuery->where('user_id', $user->id);
            $canceledQuery->where('user_id', $user->id);
            $returnedQuery->where('user_id', $user->id);
            $reprocessQuery->where('user_id', $user->id);
        }
    
        $this->pendingCount = $pendingQuery->count();
        $this->onProdCount = $onProdQuery->count();
        $this->createdCount = $createdQuery->count();
        $this->shippedCount = $shippedQuery->count();
        $this->deliveredCount = $deliveredQuery->count();
        $this->canceledCount = $canceledQuery->count();
        $this->returnedCount = $returnedQuery->count();
        $this->reprocessCount = $reprocessQuery->count();
    }

       public function render()
    {
        $user = auth()->user();
        
        $ordersQuery = Order::with('orderItems')
            ->where('status', $this->currentTab)
            ->where(function($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('orderItems', function($query) {
                          $query->where('id', 'like', '%' . $this->search . '%');
                      });
            });
    
        if ($user->role === 'user') {
            $ordersQuery->where('user_id', $user->id);
        }
    
        $orders = $ordersQuery->paginate(10);
    
        return view('livewire.production.manage-orders', [
            'orders' => $orders,
        ]);
    }
    
}
