<?php

namespace App\Livewire\Sales;

use App\Models\Order;
use Livewire\Component;
use App\Models\OrderItem;
use App\Models\PlatformOrder;
use App\Models\PlatformOrderItems;

class SalesOverview extends Component
{
    public $startDate;
    public $endDate;

    //Data
    public $platformOrders = [];
    public $orders = [];

    //Overall sales
    public $totalFromItems;
    public $totalPlatformOrder;
    public $totalShipping;
    public $totalPlatformOrderCount;
    public $totalOrdersCount;
    public $totalPlatformOrderItemCount;
    public $totalOrderItemsCount;
    public $grossSales;
    public $netSales;
    public $totalParcels;
    public $totalProducts;

    //Page sales
    public $pageSales = [];

    public $searchPlatformOrders = '';
    public $searchOrders = '';

    public function mount()
    {
        $this->startDate = now()->subYear()->format('Y-m-d'); // One year ago
        $this->endDate = now()->format('Y-m-d'); // Today

        $this->calculateTotalFullAmount();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['startDate', 'endDate'])) {
            $this->render();
        }
        $this->calculateTotalFullAmount();
    }

    public function calculateTotalFullAmount()
    {
        if ($this->startDate && $this->endDate) {

            $this->platformOrders = PlatformOrder::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->when($this->searchPlatformOrders, function ($query) {
                    $query->where('page', 'like', '%' . $this->searchPlatformOrders . '%') // Adjust column name as needed
                        ->orWhere('platform', 'like', '%' . $this->searchPlatformOrders . '%');
                })
                ->get();

            $this->orders = $ordersQuery = Order::with('customer', 'user')
                ->whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('status', 'delivered')
                ->where(function($query) {
                    $query->where('id', 'like', '%' . $this->searchOrders . '%')
                        ->orWhereHas('customer', function($query) {
                            $query->where('name', 'like', '%' . $this->searchOrders . '%');
                        })
                        ->orWhereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . $this->searchOrders . '%');
                        });
                })->get();


            //Computation for overall sales
            $this->totalFromItems = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('status', 'delivered')
                ->sum('total_from_items');
            
            $this->totalPlatformOrder = PlatformOrder::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->sum('full_amount');
            
            $this->totalShipping = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('status', 'delivered')
                ->sum('shipping_price');
            
            
            $this->totalPlatformOrderCount = PlatformOrder::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->count();

            $this->totalOrdersCount = Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->where('status', 'delivered')
                ->count();
            
            $this->totalPlatformOrderItemCount = PlatformOrderItems::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->sum('quantity');

            $this->totalOrderItemsCount = OrderItem::whereHas('order', function ($query) {
                    $query->whereBetween('created_at', [$this->startDate, $this->endDate])
                          ->where('status', 'delivered');
                })
                ->sum('quantity');
            
            $this->grossSales = $this->totalFromItems + $this->totalPlatformOrder + $this->totalShipping;
            $this->netSales = $this->grossSales - $this->totalShipping;
            $this->totalParcels = $this->totalPlatformOrderCount + $this->totalOrdersCount;
            $this->totalProducts = $this->totalPlatformOrderItemCount + $this->totalOrderItemsCount;

            //Computation for page sales
            // List of pages to calculate sales for
            $pages = [
                'Customized Watch Premium',
                'Customized Watch Co.',
                'Premium Corporate Wristwatch Supplier',
                'Personalized Gift Wristwatch',
                'G7 Global Corporate Giveaways',
                'Personalized Watch Supplier',
                'Brotherhood',
                'WatchBoss',
                'AlfaCharlie',
                'BYF8',
                'MusangX',
                'Club1979',
                'F8Watch',
                'PixWatch',
                'Detoxime',
            ];

            $this->pageSales = [];

            foreach ($pages as $page) {
                $this->pageSales[$page] = [
                    'totalFromItems' => Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                        ->where('status', 'delivered')
                        ->where('page', $page)
                        ->sum('total_from_items'),
    
                    'totalPlatformOrder' => PlatformOrder::whereBetween('created_at', [$this->startDate, $this->endDate])
                        ->where('page', $page)
                        ->sum('full_amount'),
    
                    'totalShipping' => Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                        ->where('status', 'delivered')
                        ->where('page', $page)
                        ->sum('shipping_price'),
    
                    'grossSales' => 0, // Placeholder, calculated below
                    'netSales' => 0,   // Placeholder, calculated below
                    'totalParcels' => PlatformOrder::whereBetween('created_at', [$this->startDate, $this->endDate])
                        ->where('page', $page)
                        ->count() + Order::whereBetween('created_at', [$this->startDate, $this->endDate])
                        ->where('status', 'delivered')
                        ->where('page', $page)
                        ->count(),
    
                    'totalProducts' => PlatformOrderItems::whereHas('platformOrder', function ($query) use ($page) {
                            $query->whereBetween('created_at', [$this->startDate, $this->endDate])
                                  ->where('page', $page);
                        })
                        ->sum('quantity') + OrderItem::whereHas('order', function ($query) use ($page) {
                            $query->whereBetween('created_at', [$this->startDate, $this->endDate])
                                  ->where('status', 'delivered')
                                  ->where('page', $page);
                        })
                        ->sum('quantity'),
                ];

                // Calculate gross and net sales for the page
                $this->pageSales[$page]['grossSales'] = $this->pageSales[$page]['totalFromItems']
                    + $this->pageSales[$page]['totalPlatformOrder']
                    + $this->pageSales[$page]['totalShipping'];

                $this->pageSales[$page]['netSales'] = $this->pageSales[$page]['grossSales']
                    - $this->pageSales[$page]['totalShipping'];
            }

        } else {
            $this->resetSalesData();
        }
    }

    private function resetSalesData()
    {
        $this->grossSales = 0;
        $this->netSales = 0;
        $this->totalParcels = 0;
        $this->totalProducts = 0;
        $this->totalShipping = 0;
        $this->totalPlatformOrderCount = 0;
        $this->totalOrdersCount = 0;
        $this->totalPlatformOrderItemCount = 0;
        $this->totalOrderItemsCount = 0;
        $this->totalFromItems = 0;
        $this->totalPlatformOrder = 0;

        $this->pageSales = [];

        $this->platformOrders = [];
        $this->orders = [];
    }
    
    public function render()
    {
        return view('livewire.sales.sales-overview');
    }
}
