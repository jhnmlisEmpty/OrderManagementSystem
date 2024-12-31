<div class="container mx-auto mt-5">
    <div class="mt-8">
        <h2 class="text-3xl font-bold text-black mb-4">Sales Overview</h2>
    </div>
    <div class="grid grid-cols-1 gap-6 pb-10 mt-6 md:grid-cols-2 lg:grid-cols-3">
         <!-- Daily Sales Card -->

        
         <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Daily Sales</h3>
            <p class="text-3xl font-bold text-black">{{ $salesStats['dailySales'] }}</p>
        </div>

        <!-- Weekly Sales Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Weekly Sales</h3>
            <p class="text-3xl font-bold text-black">{{ $salesStats['weeklySales'] }}</p>
        </div>

        <!-- Monthly Sales Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Monthly Sales</h3>
            <p class="text-3xl font-bold text-black">{{ $salesStats['monthlySales'] }}</p>
        </div>

        <!-- Daily Products Out Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Daily Watches Out</h3>
            <p class="text-3xl font-bold text-black">{{ $productStats['dailyProductsOut'] }}</p>
        </div>

        <!-- Weekly Products Out Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Weekly Watches Out</h3>
            <p class="text-3xl font-bold text-black">{{ $productStats['weeklyProductsOut'] }}</p>
        </div>

        <!-- Monthly Products Out Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Monthly Watches Out</h3>
            <p class="text-3xl font-bold text-black">{{ $productStats['monthlyProductsOut'] }}</p>
        </div>

        
    </div>
    
    <div class="mt-8">
        <h2 class="text-3xl font-bold text-black mb-4">Order Overview</h2>
    </div>

    <div class="grid grid-cols-1 gap-6 pb-10 mt-6 md:grid-cols-2 lg:grid-cols-3">
        <!-- New Orders Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">New Orders</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['newOrders'] }}</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">On Production</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['onProductionOrders'] }}</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Created Orders</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['createdOrders'] }}</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Ready to Ship Orders</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['readytoshipOrders'] }}</p>
        </div>

        <!-- Shipped Orders Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Shipped</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['shippedOrders'] }}</p>
        </div>

        <!-- Delivered Orders Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Delivered</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['deliveredOrders'] }}</p>
        </div>

        <!-- Cancelled Orders Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Cancelled</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['cancelledOrders'] }}</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Returned</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['returnedOrders'] }}</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Reprocess</h3>
            <p class="text-3xl font-bold text-black">{{ $orderStats['reprocessOrders'] }}</p>
        </div>

        <!-- Total Sales Card -->
        <div class="p-6 bg-white rounded-lg shadow-md">
            <h3 class="mb-2 text-lg text-gray-500">Total Sales</h3>
            <p class="text-3xl font-bold text-black">{{ number_format($orderStats['totalSales'], 2) }} PHP</p>
        </div>
    </div>
</div>
