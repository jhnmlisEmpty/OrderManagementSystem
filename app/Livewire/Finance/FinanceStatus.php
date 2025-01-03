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
    public $returnedOrders;
    public $returnedCount;
    public $deductedAmount;
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
        $shippedOrders = Order::where('user_id', $this->user->id)
            ->whereNotNull('order_ship_date')
            ->whereBetween('order_ship_date', [$this->startDate, $this->endDate])
            ->where('status', 'shipped') // Filter by shipped status
            ->get();

        $deliveredOrders = Order::where('user_id', $this->user->id)
            ->whereNotNull('date_received')
            ->whereBetween('date_received', [$this->startDate, $this->endDate])
            ->where('status', 'delivered')
            ->get();

        $returnedOrders = Order::where('user_id', $this->user->id)
            ->whereNotNull('order_returned_date')
            ->whereBetween('order_returned_date', [$this->startDate, $this->endDate])
            ->where('status', 'returned')
            ->get();

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

        $this->returnedCount = (float) $returnedOrders->sum(function ($order) {
            return $order->orderItems->sum('quantity');
        });

       // Calculate deducted amount based on returnedCount
        $this->deductedAmount = $this->returnedCount * 10;

        // Calculate total incentive
        $this->totalIncentive = $this->deliveredAmount * ((float) $this->incentivePercentage / 100);

        // Subtract deducted amount from total incentive
        $this->totalIncentive -= $this->deductedAmount;

        $this->deliveredOrders = $deliveredOrders;
        $this->returnedOrders = $returnedOrders;
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
        ->whereNotNull('date_received')
        ->whereBetween('date_received', [$this->startDate, $this->endDate])
        ->paginate(10); 

        return view('livewire.finance.finance-status',[
            'user' => $this->user,
            'returnedCount' => $this->returnedCount,
            'deductedAmount' => $this->deductedAmount,
            'shippedCount' => $this->shippedCount,
            'shippedAmount' => $this->shippedAmount,
            'deliveredCount' => $this->deliveredCount,
            'deliveredAmount' => $this->deliveredAmount,
            'totalIncentive' => $this->totalIncentive,
            'deliveredOrders' => $deliveredOrders,
            'returnedOrders' => $this->returnedOrders,
        ]);
    }
}
