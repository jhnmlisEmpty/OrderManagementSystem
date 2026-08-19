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
    public $editingTracking = false;
    public $trackingInput;

    // Load the order details when the component is mounted
    public function mount($orderId)
    {
        $this->order = Order::findOrFail($orderId);
        $this->shippingFee = $this->order->shipping_price;
        $this->trackingInput = $this->order->tracking;
    }

    public function enableTrackingEdit()
    {
        $this->editingTracking = true;
    }

    public function updateTracking()
    {
        $this->order->tracking = $this->trackingInput;
        $this->order->save();
        $this->editingTracking = false;
        session()->flash('message', 'Tracking number updated!');
    }

    public function updateStatus($status)
    {
        $order = $this->order;

        switch ($status) {
            case 'pending':
                $order->status = $status;
                break;
                
            case 'created':
                $order->status = $status;
                $order->created_at = now('Asia/Manila')->toDateString();
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
                    $product = $orderItem->product()->first();
                    $product->stocks -= $orderItem->quantity;
                    $product->save();

                    $packaging = $orderItem->packaging()->first();
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

                // Access the related paid amount from the PaymentMethod relationship
                $paidAmount = (float) ($order->paymentMethod->amount ?? 0);

                // Ensure total_from_items is a number (it might be a string in the database)
                $totalFromItems = (float) $order->total_from_items;

                // Ensure shipping fee is a number (it should be a numeric value)
                $shippingFee = (float) $this->shippingFee;
            
                $order->tracking = $this->tracking;
                $order->status = $status;
                $order->shipping_price = $this->shippingFee; // Save the shipping fee
                $order->order_ship_date = now();
                $order->full_amount = $totalFromItems + $shippingFee - $paidAmount;
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


