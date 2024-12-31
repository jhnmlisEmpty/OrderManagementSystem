<?php

namespace App\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class OrderView extends Component
{
    use LivewireAlert;

    public $order;
    public $tracking;

    // Load the order details when the component is mounted
    public function mount($orderId)
    {
        $this->order = Order::findOrFail($orderId);
    }

    public function acceptOrder()
    {
        $order = $this->order;
        foreach ($order->orderItems as $orderItem) {

            $product = $orderItem->product;
            if ($product->stocks < $orderItem->quantity) {
                $this->alert('error', 'Not enough stock for product: '.$product->name, [
                    'position' => 'top-right',
                    'timer' => 3000,
                ]);
                return;
            }

            $packaging = $orderItem->packaging;
            if ($packaging->quantity < $orderItem->quantity) {
                $this->alert('error', 'Not enough stock for product: '.$packaging->name, [
                    'position' => 'top-right',
                    'timer' => 3000,
                ]);
                return;
            }
        }

        foreach ($order->orderItems as $orderItem) {
            $product = $orderItem->product;
            $product->stocks -= $orderItem->quantity;
            $product->save();

            $packaging = $orderItem->packaging;
            $packaging->quantity -= $orderItem->quantity;
            $packaging->save();
        }

        $order->status = 'on_prod';
        $order->save();

        $this->alert('success', 'Update order status to '.$order->status, [
            'position' => 'top-right',
            'timer' => 3000,
        ]);
    }

    public function saveTracking()
    {
        $order = $this->order;
        $this->validate([
            'tracking' => 'required|string|max:255',
        ]);

        $order->tracking = $this->tracking;
        $order->status = 'shipped';
        $order->save();

        $this->alert('success', 'Order Tracking number is applied '.$order->tracking, [
            'position' => 'top-right',
            'timer' => 3000,
        ]);
       
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


