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
            'totalSales' => (clone $ordersQuery)->where('status', 'delivered')->sum('full_amount'),
        ];

        $this->salesStats = [
            'dailySales' => (clone $ordersQuery)->where('status', 'delivered')->whereDate('date_received', Carbon::today())->sum('full_amount'),
            'weeklySales' => (clone $ordersQuery)->where('status', 'delivered')->whereBetween('date_received', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->sum('full_amount'),
            'monthlySales' => (clone $ordersQuery)->where('status', 'delivered')->whereMonth('date_received', Carbon::now()->month)->sum('full_amount'),
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

        $this->lowStockProducts = Product::where('stocks', '<', 30)
            ->orderBy('stocks', 'asc')
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.view-dashboard', [
            'orderStats' => $this->orderStats,
            'salesStats' => $this->salesStats,
            'productStats' => $this->productStats,
            'rankedUsers' => $this->rankedUsers,
            'lowStockProducts' => $this->lowStockProducts,
        ]);
    }
}
