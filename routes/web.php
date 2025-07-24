<?php

use App\Livewire\Order\Cart;

use App\Http\Middleware\CheckIp;

use App\Livewire\Order\OrderView;

use App\Livewire\Order\TableOrder;
use App\Livewire\Order\CreateOrder;

use App\Livewire\Order\ManageOrders;

use App\Livewire\Products\AddProduct;
use App\Livewire\Sales\SalesOverview;
use Illuminate\Support\Facades\Route;
use App\Livewire\Employee\AddEmployee;
use App\Livewire\Finance\FinanceTable;

use App\Livewire\Attendance\Attendance;
use App\Livewire\Employee\EditEmployee;
use App\Livewire\Finance\FinanceStatus;

use App\Livewire\Logistic\SetLogistics;

use App\Livewire\Products\TableProduct;
use App\Livewire\Customer\TableCustomer;
use App\Livewire\Employee\TableEmployee;
use App\Livewire\Platform\OrderPlatform;
use App\Livewire\Platform\TablePlatform;
use App\Livewire\Products\UpdateProduct;
use App\Livewire\Dashboard\ViewDashboard;
use App\Livewire\Employee\AssignProducts;
use App\Livewire\Packaging\PackagingManager;

use App\Livewire\Finance\AffiliateIncentives;
use App\Livewire\Finance\ProductionIncentives;


Route::middleware(['auth'])->group(function () {
     // Dashboard Route
     Route::get('/', ViewDashboard::class)->name('view.dashboard');

    //Profile
    Route::view('/profile', 'profile')->name('profile');

    // Product Routes
    Route::get('/add-product', AddProduct::class)->name('add.product');
    Route::get('/update-product/{id}', UpdateProduct::class)->name('update.product');
    Route::get('/table-product', TableProduct::class)->name('table.product');

    // Order Routes
    Route::get('/table-order', TableOrder::class)->name('table.order');
    Route::get('/create-order', CreateOrder::class)->name('create.order');
    Route::get('/cart-order', Cart::class)->name('cart.order');
    Route::get('/view-order/{orderId}', OrderView::class)->name('view.order');
    Route::get('/manage-orders', ManageOrders::class)->name('manage.orders');

    // Customer Route
    Route::get('/table-customer', TableCustomer::class)->name('table.customer');

    // Employee Routes
    Route::get('/table-employee', TableEmployee::class)->name('table.employee');
    Route::get('/add-employee', AddEmployee::class)->name('add.employee');
    Route::get('/assign-products/{user}', AssignProducts::class)->name('assign.products');
    Route::get('/edit-employee/{user}', EditEmployee::class)->name('edit.employee');
    // Logistics Route
    Route::get('/set-logistics', SetLogistics::class)->name('set.logistics');

    // Packaging Route
    Route::get('/packaging', PackagingManager::class)->name('packaging.manager');

    //Platform Route
      Route::get('/platform', TablePlatform::class)->name('platform.table');
      Route::get('/platform/order', OrderPlatform::class)->name('create.orderPlatform');

    // Finance Route
    Route::get('/finance', FinanceTable::class)->name('finance.table');
    Route::get('/finance/status/{user}', FinanceStatus::class)->name('finance.status');
    Route::get('/finance/affiliate/{user}', AffiliateIncentives::class)->name('affiliate.incentives');
    Route::get('/finance/production/{user}', ProductionIncentives::class)->name('production.incentives');

    // Sales Route
    Route::get('/sales-overview', SalesOverview::class)->name('sales.overview');

    //Attendance Route
    // Route::get('/attendance', Attendance::class)->name('attendance.attendance');
    Route::get('/attendance', Attendance::class)->name('attendance.attendance')->middleware(CheckIp::class);
   

});


require __DIR__.'/auth.php';
