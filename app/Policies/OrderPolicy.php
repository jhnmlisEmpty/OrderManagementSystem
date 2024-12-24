<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Order;

class OrderPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

     /**
     * Determine whether the user can view the order.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->role === 'admin' || $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can view any orders.
     */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }
}
