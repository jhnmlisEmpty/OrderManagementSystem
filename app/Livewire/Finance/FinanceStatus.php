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
    public $pageIncentivePercentage;
    public $marketIncentivePercentage;
    public $deliveredOrders;
    public $shippedOrders;
    public $shippedIncentive;
    public $deliveredIncentive;
    public $returnedOrders;
    public $returnedCount;
    public $deductedAmount;
    public $shippedCount;
    public $shippedAmount;
    public $deliveredCount;
    public $deliveredAmount;
    public $totalIncentive;
    public $finalTotalIncentive;
    public $Percent;

    public function mount($user)
    {
        $this->user = User::findOrFail($user);
        $this->calculate();
        $this->loadIncentives();
    }

    public function loadIncentives()
    {

        $incentive = $this->user->incentive;

        if ($incentive) {
            $this->pageIncentivePercentage = $incentive->page_incentive_percentage;
            $this->marketIncentivePercentage = $incentive->market_incentive_percentage;
            $this->startDate = $incentive->startDate;
            $this->endDate = $incentive->endDate;
            $this->calculate();
        } else {
            $this->pageIncentivePercentage = 0;
            $this->marketIncentivePercentage = 0;
            $this->startDate = now()->subMonth()->format('Y-m-d'); // Default start date to one month ago
            $this->endDate = now()->format('Y-m-d');
        }
    }


    public function calculate()
    {
        $shippedOrders = Order::where('user_id', $this->user->id)
            ->whereNotNull('date_received')
            ->whereBetween('date_received', [$this->startDate, $this->endDate])
            ->where('status', 'delivered')
            ->where('platform', '!=', 'Facebook Page')
            ->get();

        $deliveredOrders = Order::where('user_id', $this->user->id)
            ->whereNotNull('date_received')
            ->whereBetween('date_received', [$this->startDate, $this->endDate])
            ->where('status', 'delivered')
            ->where('platform','Facebook Page')
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

        // Calculate total incentive for shipped and delivered orders separately
        $this->shippedIncentive = $this->shippedAmount * ((float) $this->pageIncentivePercentage / 100);
        $this->deliveredIncentive = $this->deliveredAmount * ((float) $this->marketIncentivePercentage / 100);

        // Calculate total incentive
        $this->totalIncentive =  $this->deliveredIncentive;

        

        $percentage = 0;
        $totalCount = $this->deliveredCount + $this->shippedCount;

        switch (true) {
            case $totalCount >= 150:
                $percentage = 1.00;
                $this->Percent=100;
                break;
            case $totalCount >= 100:
                $percentage = 0.75;
                $this->Percent=75;
                break;
            case $totalCount >= 50:
                $percentage = 0.50;
                $this->Percent=50;
                break;
            case $totalCount >= 30:
                $percentage = 0.15;
                $this->Percent=15;
                break;
            case $totalCount >= 1:
                $percentage = 0.05;
                $this->Percent=5;
                break;
        }

        $this->finalTotalIncentive = $this->totalIncentive * $percentage;
        
        $this->finalTotalIncentive += $this->shippedIncentive;
        $this->finalTotalIncentive -= $this->deductedAmount;

        $this->deliveredOrders = $deliveredOrders;
        $this->shippedOrders = $shippedOrders;
        $this->returnedOrders = $returnedOrders;
    }


    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate', 'pageIncentivePercentage', 'marketIncentivePercentage'])) {
            $this->calculate();
            $this->saveIncentives();
        }
    }

    public function saveIncentives()
    {
        // Ensure the values are set to 0 if they are empty
        $this->pageIncentivePercentage = $this->pageIncentivePercentage !== '' ? $this->pageIncentivePercentage : 0;
        $this->marketIncentivePercentage = $this->marketIncentivePercentage !== '' ? $this->marketIncentivePercentage : 0;

        $incentive = $this->user->incentive;

        if ($incentive) {
            $incentive->update([
                'page_incentive_percentage' => $this->pageIncentivePercentage,
                'market_incentive_percentage' => $this->marketIncentivePercentage,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
            ]);
        } else {
            $this->user->incentive()->create([
                'page_incentive_percentage' => $this->pageIncentivePercentage,
                'market_incentive_percentage' => $this->marketIncentivePercentage,
                'startDate' => $this->startDate,
                'endDate' => $this->endDate,
            ]);
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
            'shippedIncentive' => $this->shippedIncentive,
            'deliveredIncentive' => $this->deliveredIncentive,
            'shippedOrders' =>$this->shippedOrders,
            'returnedOrders' => $this->returnedOrders,
            'totalCount' => $this->deliveredCount + $this->shippedCount,
            'finalTotalIncentive' => $this->finalTotalIncentive,
            'Percent' => $this->Percent,
        ]);
    }
}
