<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class OrderView extends Component
{
    use LivewireAlert;

    public $order;

    // Load the order details when the component is mounted
    public function mount($orderId)
    {
        $this->order = Order::findOrFail($orderId);
    }

    public function updateStatus($status)
    {
        $this->order->status = $status;
        $this->order->save();

        $this->alert('success', 'Update order status to '.$status, [
            'position' => 'top-right',
            'timer' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.order.order-view');
    }
}


