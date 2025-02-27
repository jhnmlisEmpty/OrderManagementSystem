<?php

namespace App\Livewire\Finance;

use App\Models\User;
use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AffiliateIncentives extends Component
{
    use WithPagination;
    use WithFileUploads;
    use LivewireAlert;
    
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
    public $shippingAmount;
    public $wholesaleAmount;
    public $payoutStatus;
    public $releaseDate;
    public $payoutProof;
    public $fullAmount;

    public function mount($user)
    {
        $this->user = User::findOrFail($user);
        $this->calculate();
        $this->loadIncentives();
        $incentive = $this->user->incentive;
        $this->payoutStatus = $incentive->status ?? '';
        $this->releaseDate = $incentive->release_date ?? '';
        $this->payoutProof = $incentive->image ?? '';
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
        $deliveredOrders = Order::where('user_id', $this->user->id)
            ->where('status', 'delivered')
            ->whereNotNull('date_received')
            ->whereBetween('date_received', [$this->startDate, $this->endDate])
            ->get();


        $returnedOrders = Order::where('user_id', $this->user->id)
            ->whereNotNull('order_returned_date')
            ->whereBetween('order_returned_date', [$this->startDate, $this->endDate])
            ->where('status', 'returned')
            ->get();

        $this->deliveredCount = (float) $deliveredOrders->sum(function ($order) {
            return $order->orderItems->sum('quantity');
        });

        $this->deliveredAmount = (float) $deliveredOrders->sum(function ($order) {
            return $order->orderItems->sum('total_amount');
        });

        

        $this->wholesaleAmount = (float) $deliveredOrders->sum(function ($order) {
            return $order->orderItems->sum(function ($orderItem) {
                return $orderItem->product->price * $orderItem->quantity;
            });
        });


        $this->shippingAmount = (float) $deliveredOrders->sum('shipping_price');

        $this->fullAmount = (float) $deliveredOrders->sum('total_from_items');

        $this->returnedCount = (float) $returnedOrders->sum(function ($order) {
            return $order->orderItems->sum('quantity');
        });

        // Calculate deducted amount based on returnedCount
        $this->deductedAmount = $this->returnedCount * 15;

        $this->deliveredIncentive = $this->deliveredAmount;

        // Calculate total incentive
        $this->totalIncentive =  $this->deliveredIncentive;
        $this->totalIncentive -= $this->shippingAmount;
        
        $this->totalIncentive -= $this->deductedAmount;
        $this->totalIncentive -= $this->wholesaleAmount;

        $this->deliveredOrders = $deliveredOrders;
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

    public function save()
    {
        $this->validate([
            'releaseDate' => 'required|date',
            'payoutProof' => 'required|image|max:1024', // 1MB Max
        ]);

        $incentive = $this->user->incentive()->updateOrCreate([], [
            'status' => 'Released',
            'release_date' => $this->releaseDate,
            'image' => $this->payoutProof ? $this->payoutProof->store('payout_proofs', 'public') : $this->payoutProof,
        ]);

        $this->alert('success', 'Incentive details updated successfully!');
    }

    public function clearFields()
    {
        $this->user->incentive()->update([
            'status' => 'Not Released',
            'release_date' => null,
            'image' => null,
        ]);

        // Clear the local properties
        $this->payoutStatus = '';
        $this->releaseDate = '';
        $this->payoutProof = '';

        $this->alert('success', 'Incentive fields cleared successfully!');
    }

    public function render()
    {
        $deliveredOrders = Order::where('user_id', $this->user->id)
            ->where('status', 'delivered')
            ->whereNotNull('date_received')
            ->whereBetween('date_received', [$this->startDate, $this->endDate])
            ->get();

        return view('livewire.finance.affiliate-incentives',[
            'user' => $this->user,
            'returnedCount' => $this->returnedCount,
            'deductedAmount' => $this->deductedAmount,
            'deliveredCount' => $this->deliveredCount,
            'deliveredAmount' => $this->deliveredAmount,
            'totalIncentive' => $this->totalIncentive,
            'deliveredOrders' => $deliveredOrders,
            'deliveredIncentive' => $this->deliveredIncentive,
            'returnedOrders' => $this->returnedOrders,
            'shippingAmount' => $this->shippingAmount,
            'wholesaleAmount' => $this->wholesaleAmount,
            'fullAmount' => $this->fullAmount,

        ]);
    }
}
