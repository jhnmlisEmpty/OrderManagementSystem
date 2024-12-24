<?php

// namespace App\Livewire\Logistic;
// use App\Models\Order;
// use Illuminate\Support\Facades\Auth;
// use Livewire\Component;

// class SetLogistics extends Component
// {
//     public $orders;
//     public $orderId, $status, $shipping_price, $tracking, $full_amount;
//     public $showModal = false;

//     public function mount()
//     {
//         $this->fetchOrders();
//     }

//     public function fetchOrders()
//     {
//         if (Auth::user()->role === 'admin') {
//             $this->orders = Order::all();
//         } else {
//             $this->orders = Order::where('user_id', Auth::id())->get();
//         }
//     }

//     public function editOrder($id)
//     {
//         $order = Order::find($id);

//         if ($order) {
//             $this->orderId = $order->id;
//             $this->status = $order->status;
//             $this->shipping_price = $order->shipping_price;
//             $this->tracking = $order->tracking;
//             $this->showModal = true;
//         }
//     }

//     public function updateOrder()
//     {
//         $this->validate([
//             'status' => 'required|in:pending,on_prod,created,shipped,delivered,canceled,returned,reprocess',
//             'shipping_price' => 'nullable|numeric',
//             'tracking' => 'nullable|string',
//         ]);

//         $order = Order::find($this->orderId);

//         if ($order) {
//             $order->status = $this->status;
//             $order->shipping_price = $this->shipping_price;
//             $order->tracking = $this->tracking;

//             if ($this->shipping_price) {
//                 $order->full_amount += $this->shipping_price;
//             }

//             if ($this->status === 'delivered') {
//                 $order->date_received = now();
//             }

//             $order->save();
//             session()->flash('message', 'Order updated successfully.');
//             $this->resetInputFields();
//             $this->fetchOrders();
//         }
//     }

//     private function resetInputFields()
//     {
//         $this->orderId = null;
//         $this->status = null;
//         $this->shipping_price = null;
//         $this->tracking = null;
//         $this->showModal = false;
//     }

//     public function render()
//     {
//         return view('livewire.logistic.set-logistics')->layout('layouts.main');
//     }
// }


namespace App\Livewire\Logistic;

use App\Models\Order;
use Livewire\WithPagination;

use Jantinnerezo\LivewireAlert\LivewireAlert;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SetLogistics extends Component
{
    use LivewireAlert, WithPagination;
    
    public $orders;
    public $orderId, $status, $shipping_price, $tracking, $full_amount;
    public $showModal = false;
    public $search = '';
    public $selectedOrders = []; 
    public $statusFilter = '';
    public $statusCounts = [];
    public $selectAll = false;
    public $statuses = ['pending', 'on_prod', 'created', 'shipped', 'delivered', 'canceled', 'returned', 'reprocess'];

    protected $listeners = [
        'confirmUpdate',
        'cancelUpdate'
    ];
    
    public function batchUpdateStatus($newStatus)
    {
        $this->alert('question',  'Are you sure you want to update these orders to ' . $newStatus . '?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'YES',
            'onConfirmed' => 'confirmUpdate',
            'showDenyButton' => true,
            'denyButtonText' => 'NO',
            'onDenied' => 'cancelUpdate',
            'data' => [
                    'newStatus' => $newStatus
                ]
        ]);  
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selectedOrders = $this->orders->pluck('id')->toArray();
        } else {
            $this->selectedOrders = [];
        }
    }

    public function mount()
    {
        $this->fetchOrders();
    }

    public function updatedSearch()
    {
        $this->fetchOrders();
    }

    public function updatedStatusFilter()
    {
        $this->fetchOrders();
    }

    public function fetchOrders()
    {
        $query = Order::query();

        if ($this->search) {
            $query->where(function ($query) {
                $query->whereHas('customer', function($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%');
                })
                ->orWhere('platform', 'like', '%' . $this->search . '%'); 
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        if (Auth::user()->role === 'admin') {
            $this->orders = $query->get();
        } else {
            $this->orders = $query->where('user_id', Auth::id())->get();
        }

        $this->statusCounts = Order::select('status', \DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }

    public function editOrder($id)
    {
        $order = Order::find($id);
        if ($order) {
            $this->orderId = $order->id;
            $this->status = $order->status;
            $this->shipping_price = $order->shipping_price;
            $this->tracking = $order->tracking;
            $this->showModal = true;
        }
    }

    public function updateOrder()
    {
        $this->validate([
            'status' => 'required|in:pending,on_prod,created,shipped,delivered,canceled,returned,reprocess',
            'shipping_price' => 'nullable|numeric',
            'tracking' => 'nullable|string',
        ]);

        $order = Order::find($this->orderId);
        if ($order) {
            $order->status = $this->status;
            $order->shipping_price = $this->shipping_price;
            $order->tracking = $this->tracking;

            if ($this->shipping_price) {
                $order->full_amount += $this->shipping_price;
            }

            if ($this->status === 'delivered') {
                $order->date_received = now();
            }

            $order->save();
            $this->resetInputFields();
            $this->fetchOrders();
        }
    }

    public function confirmUpdate($data)
    {
        $newStatus = $data['newStatus'];

        $this->validate([
            'selectedOrders' => 'required|array|min:1',
            'selectedOrders.*' => 'exists:orders,id',
        ]);

        foreach ($this->selectedOrders as $orderId) {
            $order = Order::find($orderId);
            if ($order) {
                $order->status = $newStatus;
                $order->save();
            }
        }

        $this->alert('success', 'Status update to '.$newStatus.' successfully', [
            'position' => 'top-right',
            'timer' => 5000,
        ]);
        $this->fetchOrders();
    }

    public function fetchAllOrders()
    {
        $this->statusFilter = '';
        $this->search = '';
        $this->fetchOrders();
    }

    private function resetInputFields()
    {
        $this->orderId = null;
        $this->status = null;
        $this->shipping_price = null;
        $this->tracking = null;
        $this->showModal = false;
    }

    public function render()
    {
        return view('livewire.logistic.set-logistics');
    }
}
