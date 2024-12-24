<?php

namespace App\Providers;

use App\Models\Order;
use App\Policies\OrderPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     */
    protected $policies = [
        Order::class => OrderPolicy::class,
    ];

    /**
     * Register any authentication/authorization services.
     */
    public function boot()
    {
        $this->registerPolicies();
    }
}
