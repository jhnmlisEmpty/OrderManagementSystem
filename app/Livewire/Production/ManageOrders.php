<?php

namespace App\Livewire\Production;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class ManageOrders extends Component
{ 
    use WithPagination;

    public $currentTab = 'pending';
    public $search = '';
    public $selectedOrder = null;
    public $pendingCount;
    public $onProdCount;
    public $createdCount;

    protected $queryString = ['search'];

    public function mount()
    {
        $this->loadCounts();
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
        $this->pendingCount = Order::where('status', 'pending')->count();
        $this->onProdCount = Order::where('status', 'on_prod')->count();
        $this->createdCount = Order::where('status', 'created')->count();
    }

    public function render()
    {
        $orders = Order::with('orderItems')
            ->where('status', $this->currentTab)
            ->where(function($query) {
                $query->where('id', 'like', '%' . $this->search . '%')
                      ->orWhereHas('orderItems', function($query) {
                          $query->where('id', 'like', '%' . $this->search . '%');
                      });
            })
            ->paginate(10);

        return view('livewire.production.manage-orders', [
            'orders' => $orders,
        ]);
    }
    
}
