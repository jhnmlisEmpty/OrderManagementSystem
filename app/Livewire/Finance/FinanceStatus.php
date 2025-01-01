<?php

namespace App\Livewire\Finance;

use App\Models\User;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class FinanceStatus extends Component
{
    use WithPagination;
    
    public $user;
    public $startDate;
    public $endDate;
    public $incentivePercentage;
    public $deliveredOrders;
    public $shippedCount;
    public $shippedAmount;
    public $deliveredCount;
    public $deliveredAmount;
    public $totalIncentive;

    public function mount($user)
    {
        $this->user = User::findOrFail($user);
        $this->startDate = now()->subMonth()->format('Y-m-d'); // Default start date to one month ago
        $this->endDate = now()->format('Y-m-d'); // Default end date to today
        $this->incentivePercentage = 0;
        $this->calculate();
    }

    public function calculate()
    {
        $orders = Order::where('user_id', $this->user->id)
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

        $shippedOrders = $orders->where('status', 'shipped');
        $deliveredOrders = $orders->where('status', 'delivered');

        $this->shippedCount = (float) $shippedOrders->sum(function ($order) {
            return $order->orderItems->sum('quantity');
        });
        $this->shippedAmount = (float) $shippedOrders->sum(function ($order) {
            return $order->orderItems->sum('total_amount');
        });

        $this->deliveredCount = (float) $deliveredOrders->sum(function ($order) {
            return $order->orderItems->sum('quantity');
        });
        $this->deliveredAmount = (float) $deliveredOrders->sum(function ($order) {
            return $order->orderItems->sum('total_amount');
        });

        $this->totalIncentive = $this->deliveredAmount * ((float) $this->incentivePercentage / 100);
        $this->deliveredOrders = $deliveredOrders;
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate', 'incentivePercentage'])) {
            $this->calculate();
        }
    }

    public function render()
    {
        $deliveredOrders = Order::where('user_id', $this->user->id)
        ->where('status', 'delivered')
        ->whereBetween('created_at', [$this->startDate, $this->endDate])
        ->paginate(10); 

        return view('livewire.finance.finance-status',[
            'user' => $this->user,
            'shippedCount' => $this->shippedCount,
            'shippedAmount' => $this->shippedAmount,
            'deliveredCount' => $this->deliveredCount,
            'deliveredAmount' => $this->deliveredAmount,
            'totalIncentive' => $this->totalIncentive,
             'deliveredOrders' => $deliveredOrders,
        ]);
    }
}
