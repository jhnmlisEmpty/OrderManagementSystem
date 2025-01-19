<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Livewire\Component;
use App\Models\OrderItem;

class ViewDashboard extends Component
{
    public $orderStats = [];
    public $assignedProductIds;

    public function mount()
    {
        $user = auth()->user();

        $ordersQuery = Order::query();

        if ($user->role === 'osr' || $user->role === 'affiliate') {
            $ordersQuery->where('user_id', $user->id);
        }

        $this->orderStats = [
            'newOrders' => (clone $ordersQuery)->where('status', 'pending')->count(),
            'onProductionOrders' => (clone $ordersQuery)->where('status', 'on_prod')->count(),
            'createdOrders' => (clone $ordersQuery)->where('status', 'created')->count(),
            'readytoshipOrders' => (clone $ordersQuery)->where('status', 'ready_to_ship')->count(),
            'shippedOrders' => (clone $ordersQuery)->where('status', 'shipped')->count(),
            'deliveredOrders' => (clone $ordersQuery)->where('status', 'delivered')->count(),
            'cancelledOrders' => (clone $ordersQuery)->where('status', 'canceled')->count(),
            'returnedOrders' => (clone $ordersQuery)->where('status', 'returned')->count(),
            'reprocessOrders' => (clone $ordersQuery)->where('status', 'reprocess')->count(),
            'totalSales' => (clone $ordersQuery)->where('status', 'delivered')->sum('total_from_items'),
        ];

        $this->salesStats = [
            'dailySales' => (clone $ordersQuery)->where('status', 'delivered')->whereDate('date_received', Carbon::today())->sum('total_from_items'),
            'weeklySales' => (clone $ordersQuery)->where('status', 'delivered')->whereBetween('date_received', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('total_from_items'),
            'monthlySales' => (clone $ordersQuery)->where('status', 'delivered')->whereMonth('date_received', Carbon::now()->month)->sum('total_from_items'),
        ];

        $orderItemsQuery = OrderItem::query();

        if ($user->role === 'osr' || $user->role === 'affiliate') {
            $orderItemsQuery->whereHas('order', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            });
        }

        $this->productStats = [
            'dailyProductsOut' => (clone $orderItemsQuery)->whereHas('order', function ($query) {
                $query->whereIn('status', ['shipped', 'delivered']) // Check for either shipped or delivered status
                      ->whereDate('order_ship_date', Carbon::today()); // Apply date_received filter on orders table
            })->sum('quantity'),
            'weeklyProductsOut' => (clone $orderItemsQuery)->whereHas('order', function ($query) {
                $query->whereIn('status', ['shipped', 'delivered']) // Check for either shipped or delivered status
                      ->whereBetween('order_ship_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); // Apply date_received filter on orders table
            })->sum('quantity'),
            'monthlyProductsOut' => (clone $orderItemsQuery)->whereHas('order', function ($query) {
                $query->whereIn('status', ['shipped', 'delivered']) // Check for either shipped or delivered status
                      ->whereMonth('order_ship_date', Carbon::now()->month); // Apply date_received filter on orders table
            })->sum('quantity'),
        ];
        
        $this->dailyStats = [
            'dailyOrders' => (clone $ordersQuery)
                ->whereDate('created_at', Carbon::today())
                ->count(),
        
            'dailyOrderQuantity' => (clone $orderItemsQuery)
                ->whereHas('order', function ($query) {
                    $query->whereDate('created_at', Carbon::today());
                })
                ->sum('quantity'),
        
            'totalOrderAmount' => (clone $ordersQuery)
                ->whereDate('created_at', Carbon::today())
                ->sum('total_from_items'),
        ];
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        
        $this->rankedUsers = User::whereIn('role', ['osr', 'affiliate'])
            ->withSum([
                // Calculate the sum of delivered items
                'orderItems as delivered_quantity' => function ($query) use ($currentMonth, $currentYear) {
                    $query->where('status', 'delivered')
                        ->whereMonth('date_received', $currentMonth)
                        ->whereYear('date_received', $currentYear);
                },
                // Calculate the sum of shipped items
                'orderItems as shipped_quantity' => function ($query) use ($currentMonth, $currentYear) {
                    $query->where('status', 'shipped')
                        ->whereMonth('order_ship_date', $currentMonth)
                        ->whereYear('order_ship_date', $currentYear);
                }
            ], 'quantity')
            ->orderByDesc('delivered_quantity')
            ->get();

        $this->pieChart = Order::select('orders.page') // Specify the table for `page`
            ->selectRaw('SUM(order_items.quantity) as order_items_sum_quantity')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereNotNull('orders.page') // Exclude null values for page
            ->where('orders.status', 'delivered') // Add condition for delivered status
            ->whereMonth('orders.created_at', Carbon::now()->month) // Specify `orders.created_at`
            ->whereYear('orders.created_at', Carbon::now()->year) // Specify `orders.created_at`
            ->groupBy('orders.page') // Specify `orders.page`
            ->get();

        $chartData = $this->pieChart->map(function ($item) {
            $label = $item->page;
            if ($label === 'G7 Global Corporate Giveaways') {
            $label = 'G7Global Corporate Giveaway Supplier (James)';
            } elseif ($label === 'Customized Watch Co.') {
            $label = 'Customized Watch Co. (Armella & Dhenelyn)';
            } elseif ($label === 'Customized Watch Premium') {
            $label = 'Customized Watch Premium (Geo)';
            } elseif ($label === 'Premium Corporate Wristwatch Supplier') {
            $label = 'Premium Corporate Wristwatch Supplier (Sheana & Eula)';
            } elseif ($label === 'Personalized Gift Wristwatch') {
            $label = 'Personalized Gift Wristwatch Supplier (Jerico)';
            } elseif ($label === 'Personalized Watch Supplier') {
            $label = 'Personalized Watch Supplier (Jeremy & Annalyn)';
            } elseif ($label === 'WatchBoss') {
            $label = 'WatchBoss (Admin)';
            } elseif ($label === 'F8Watch') {
            $label = 'F8watch (Admin)';
            }

            // Add more conditions as needed
            return [
            'label' => $label,
            'value' => $item->order_items_sum_quantity,
            ];
        });
        $this->chartData = $chartData; 

        // $this->barChart = Order::select('page') // Select the `page` column from the `orders` table
        //     ->selectRaw('SUM(total_from_items) as total_froms_items_sum') // Calculate the sum of `total_froms_items`
        //     ->whereNotNull('page') // Exclude rows where `page` is null
        //     ->whereMonth('created_at', Carbon::now()->month) // Filter by the current month
        //     ->whereYear('created_at', Carbon::now()->year) // Filter by the current year
        //     ->groupBy('page') // Group the results by the `page` column
        //     ->get();

        $this->barChart = Order::select('orders.page') // Specify the table for `page`
            ->selectRaw('SUM(order_items.quantity) as total_froms_items_sum')
            ->leftJoin('order_items', 'orders.id', '=', 'order_items.order_id')
            ->whereNotNull('orders.page') // Exclude null values for page
            ->where('orders.status', 'delivered') // Add condition for delivered status
            ->whereMonth('orders.created_at', Carbon::now()->month) // Specify `orders.created_at`
            ->whereYear('orders.created_at', Carbon::now()->year) // Specify `orders.created_at`
            ->groupBy('orders.page') // Specify `orders.page`
            ->get();


        $barChartData = $this->barChart->map(function ($item) {
            return [
                'label' => $item->page,
                'value' => $item->total_froms_items_sum,
            ];
        });

        $this->barChartData = $barChartData;


        if ($user->role === 'affiliate') {
            $assignedProductIds = $user->products->pluck('id')->toArray();
            $this->lowStockProducts = Product::whereIn('id', $assignedProductIds)
                ->where('stocks', '<', 30)
                ->orderBy('stocks', 'asc')
                ->get();
        } else {
            $this->lowStockProducts = Product::where('stocks', '<', 30)
                ->orderBy('stocks', 'asc')
                ->get();
        }
    }

    public function render()
    {
        return view('livewire.dashboard.view-dashboard', [
            'chartData' => $this->chartData,
            'barChartData' => $this->barChartData,
            'barChart' => $this->barChart,
            'dailyStats' => $this->dailyStats,
            'orderStats' => $this->orderStats,
            'salesStats' => $this->salesStats,
            'productStats' => $this->productStats,
            'rankedUsers' => $this->rankedUsers,
            'pieChart' => $this->pieChart,
            'lowStockProducts' => $this->lowStockProducts,
        ]);
    }
}
