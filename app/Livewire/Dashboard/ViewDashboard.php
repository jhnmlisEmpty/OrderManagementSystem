<?php

namespace App\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
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
                      ->whereDate('date_received', Carbon::today()); // Apply date_received filter on orders table
            })->sum('quantity'),
            'weeklyProductsOut' => (clone $orderItemsQuery)->whereHas('order', function ($query) {
                $query->whereIn('status', ['shipped', 'delivered']) // Check for either shipped or delivered status
                      ->whereBetween('date_received', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]); // Apply date_received filter on orders table
            })->sum('quantity'),
            'monthlyProductsOut' => (clone $orderItemsQuery)->whereHas('order', function ($query) {
                $query->whereIn('status', ['shipped', 'delivered']) // Check for either shipped or delivered status
                      ->whereMonth('date_received', Carbon::now()->month); // Apply date_received filter on orders table
            })->sum('quantity'),
        ];        
        

        $this->rankedUsers = User::whereIn('role', ['osr', 'affiliate']) // Filter by role
            ->with(['orders' => function ($query) {
                $query->whereIn('status', ['shipped', 'delivered']) // Only shipped and delivered orders
                    ->whereMonth('date_received', Carbon::now()->month); // Orders from the current month
            }])
            ->withCount([
                'orders as total_quantity' => function ($query) {
                    $query->where('status', 'delivered') // Filter delivered orders
                        ->whereMonth('date_received', Carbon::now()->month) // Filter by current month
                        ->withSum('orderItems', 'quantity'); // Sum order items quantity for delivered orders
                },
                'orders as shipped_quantity' => function ($query) {
                    $query->where('status', 'shipped') // Filter shipped orders
                        ->whereMonth('date_received', Carbon::now()->month) // Filter by current month
                        ->withSum('orderItems', 'quantity'); // Sum order items quantity for shipped orders
                }
            ])
            ->orderByDesc('total_quantity') // Rank by total quantity of delivered orders in descending order
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard.view-dashboard', [
            'orderStats' => $this->orderStats,
            'salesStats' => $this->salesStats,
            'productStats' => $this->productStats,
            'rankedUsers' => $this->rankedUsers,
        ]);
    }
}
