<?php

namespace App\Livewire\Finance;

use App\Models\Order;
use Livewire\Component;
use App\Models\OrderItem;
use App\Models\GlobalFilter;
use App\Models\PlatformOrder;
use App\Models\PlatformOrderItems;
use Illuminate\Foundation\Auth\User;

class ProductionIncentives extends Component
{
    public $user;
    public $startDate;
    public $endDate;

    public $platformOrders = [];
    public $orders = [];

    public $totalPlatformOrderCount;
    public $totalOrdersCount;

    public $totalPlatformOrderItemCount;
    public $totalOrderItemsCount;

    public $totalParcels;
    public $totalProducts;

    public $attendedParcels;
    public $attendedProducts;

    public function mount($user)
    {
        $this->user = User::findOrFail($user);
        $filter = GlobalFilter::where('filter_name', 'production_incentives_date_range')->first();

        $this->startDate = $filter->start_date ?? now()->subYear()->format('Y-m-d'); // Default: one year ago
        $this->endDate = $filter->end_date ?? now()->format('Y-m-d'); // Default: today

        $this->calculateIncentives();
        $this->calculateAttendedIncentives();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate'])) {
            // Update or create the global filter
            GlobalFilter::updateOrCreate(
                ['filter_name' => 'production_incentives_date_range'],
                [
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                ]
            );
    
            $this->calculateIncentives();
            $this->calculateAttendedIncentives();
        }
    }

    public function calculateAttendedIncentives()
    {
        // Get all attendance dates for the user in the range
        $attendanceDates = \App\Models\UserAttendance::where('user_id', $this->user->id)
            ->whereBetween('date', [$this->startDate, $this->endDate])
            ->pluck('date')
            ->toArray();

        if (empty($attendanceDates)) {
            $this->attendedOrderCount = 0;
            $this->attendedOrderItemsCount = 0;
            $this->attendedPlatformOrderCount = 0;
            $this->attendedPlatformOrderItemCount = 0;
            $this->attendedParcels = 0;
            $this->attendedProducts = 0;
            return;
        }

        // Orders shipped or delivered on attended dates
        $this->attendedOrderCount = Order::whereIn('order_ship_date', $attendanceDates)
            ->whereIn('status', ['delivered', 'shipped'])
            ->count();

        // Order items for those orders
        $this->attendedOrderItemsCount = OrderItem::whereHas('order', function ($query) use ($attendanceDates) {
            $query->whereIn('order_ship_date', $attendanceDates)
            ->whereIn('status', ['delivered', 'shipped']);
        })->sum('quantity');

        // Platform orders created on attended dates
        $this->attendedPlatformOrderCount = PlatformOrder::whereDate('created_at', $attendanceDates)
            ->count();

        // Platform order items created on attended dates
        $this->attendedPlatformOrderItemCount = PlatformOrderItems::whereDate('created_at', $attendanceDates)
            ->sum('quantity');

        $this->attendedParcels = $this->attendedOrderCount + $this->attendedPlatformOrderCount;
        $this->attendedProducts = $this->attendedOrderItemsCount + $this->attendedPlatformOrderItemCount;

    }

    public function calculateIncentives()
    {
        // Calculate the total incentives based on the date range
        $this->totalPlatformOrderCount = PlatformOrder::whereBetween('created_at', [$this->startDate, $this->endDate])->count();
        $this->totalPlatformOrderItemCount = PlatformOrderItems::whereBetween('created_at', [$this->startDate, $this->endDate])->sum('quantity');

        $this->totalOrdersCount = Order::whereBetween('order_ship_date', [$this->startDate, $this->endDate])
            ->whereIn('status', ['delivered', 'shipped']) // Filter by status
            ->count();

        // Fetch order items related to orders with status 'delivered' or 'shipped'
        $this->totalOrderItemsCount = OrderItem::whereHas('order', function ($query) {
            $query->whereBetween('order_ship_date', [$this->startDate, $this->endDate])
            ->whereIn('status', ['delivered', 'shipped']); // Filter by status
        })->sum('quantity');

        $this->totalParcels = $this->totalPlatformOrderCount + $this->totalOrdersCount;
        $this->totalProducts = $this->totalPlatformOrderItemCount + $this->totalOrderItemsCount;


        $this->platformOrders = PlatformOrder::whereBetween('created_at', [$this->startDate, $this->endDate])
            ->get();

        $this->orders = Order::with('customer', 'user')
            ->whereBetween('order_ship_date', [$this->startDate, $this->endDate])
            ->whereIn('status', ['shipped', 'delivered']) // Filter by status
            ->get();
    }

    public function render()
    {
        return view('livewire.finance.production-incentives');
    }
}
