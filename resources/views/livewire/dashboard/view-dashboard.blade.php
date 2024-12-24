<div>
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
