<?php

use App\Livewire\Order\Cart;

use App\Livewire\Order\OrderView;

use App\Livewire\Order\TableOrder;

use App\Livewire\Order\CreateOrder;
use App\Livewire\Products\AddProduct;

use Illuminate\Support\Facades\Route;

use App\Livewire\Employee\AddEmployee;
use App\Livewire\Logistic\SetLogistics;
use App\Livewire\Products\TableProduct;
use App\Livewire\Customer\TableCustomer;

use App\Livewire\Employee\TableEmployee;
use App\Livewire\Products\UpdateProduct;
use App\Livewire\Dashboard\ViewDashboard;

use App\Livewire\Production\ManageOrders;
use App\Livewire\Packaging\PackagingManager;


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

    // Customer Route
    Route::get('/table-customer', TableCustomer::class)->name('table.customer');

    // Employee Routes
    Route::get('/table-employee', TableEmployee::class)->name('table.employee');
    Route::get('/add-employee', AddEmployee::class)->name('add.employee');

    // Logistics Route
    Route::get('/set-logistics', SetLogistics::class)->name('set.logistics');

    // Packaging Route
    Route::get('/packaging', PackagingManager::class)->name('packaging.manager');
    
    Route::get('/manage-orders', ManageOrders::class)->name('manage.orders');
});


require __DIR__.'/auth.php';
