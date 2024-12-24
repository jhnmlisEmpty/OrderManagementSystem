<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

use App\Models\Order;

class ViewDashboard extends Component
{
    public $orderStats = [];

    public function mount()
    {
        $user = auth()->user();

        $ordersQuery = Order::query();

        if ($user->role !== 'admin') {
            $ordersQuery->where('user_id', $user->id);
        }

        $this->orderStats = [
            'newOrders' => (clone $ordersQuery)->where('status', 'pending')->count(),
            'onProductionOrders' => (clone $ordersQuery)->where('status', 'on_prod')->count(),
            'createdOrders' => (clone $ordersQuery)->where('status', 'created')->count(),
            'shippedOrders' => (clone $ordersQuery)->where('status', 'shipped')->count(),
            'deliveredOrders' => (clone $ordersQuery)->where('status', 'delivered')->count(),
            'cancelledOrders' => (clone $ordersQuery)->where('status', 'canceled')->count(),
            'returnedOrders' => (clone $ordersQuery)->where('status', 'returned')->count(),
            'reprocessOrders' => (clone $ordersQuery)->where('status', 'reprocess')->count(),
            'totalSales' => (clone $ordersQuery)->where('status', 'delivered')->sum('full_amount'),
        ];
    }


    public function render()
    {
        return view('livewire.dashboard.view-dashboard', [
            'orderStats' => $this->orderStats,
        ]);
    }
}
