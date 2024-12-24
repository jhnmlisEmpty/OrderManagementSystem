<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Component;

class OrderView extends Component
{

    public $order;

    // Load the order details when the component is mounted
    public function mount($orderId)
    {
        $this->order = Order::findOrFail($orderId);
    }

    public function render()
    {
        return view('livewire.order.order-view');
    }
}


