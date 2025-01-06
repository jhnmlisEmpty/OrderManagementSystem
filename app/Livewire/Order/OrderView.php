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
    public $shippingFee;
    public $statusDate;

    // Load the order details when the component is mounted
    public function mount($orderId)
    {
        $this->order = Order::findOrFail($orderId);
    }

    public function updateStatus($status)
    {
        $order = $this->order;

        switch ($status) {
            case 'pending':
                $order->status = $status;
                break;

            case 'on_prod':
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
                        $this->alert('error', 'Not enough stock for packaging: '.$packaging->name, [
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

                $order->status = $status;
                break;

            case 'shipped':
                $this->validate([
                    'tracking' => 'required|string',
                    'shippingFee' => 'numeric|min:0',
                ]);

                $paidAmount = $order->paymentMethod->amount ?? 0;
            
                $order->tracking = $this->tracking;
                $order->status = $status;
                $order->shipping_price = $this->shippingFee; // Save the shipping fee
                $order->order_ship_date = now();
                $order->full_amount = $order->total_from_items + $this->shippingFee - $paidAmount;
                if ($order->full_amount < 0) {
                    $this->addError('shippingFee', 'Full amount cannot be negative. ('.'$order->full_amount'.')');
                    return;
                }
                $order->save(); // Save the changes to the database
                break;
                

            case 'delivered':
                $this->validate([
                    'statusDate' => 'required|date',
                ]);
            
                $order->status = $status;
                $order->date_received = $this->statusDate;
                break;

            case 'reprocess':
                $this->validate([
                    'statusDate' => 'required|date',
                ]);
                $order->status = $status;
                $order->order_reprocess_date = $this->statusDate;
                break;

            case 'returned':
                $this->validate([
                    'statusDate' => 'required|date',
                ]);
                $order->status = $status;
                $order->order_returned_date = $this->statusDate;
                break;

            default:
                $order->status = $status;
                break;
        }

        $order->save();

        $this->alert('success', 'Order status updated to '.$status, [
            'position' => 'top-right',
            'timer' => 3000,
        ]);
    }

    public function render()
    {
        return view('livewire.order.order-view');
    }
}


