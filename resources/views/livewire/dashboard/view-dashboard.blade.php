<div class="container mx-auto mt-5">
    <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 mt-4">
                Dashboard
            </h3>
        </div>
    </div>
    
    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' || auth()->user()->role === 'affiliate' || auth()->user()->role === 'osr')
        <div class="mt-8">
            <h2 class="text-xl font-bold text-black mb-4">Top Rank Agent</h2>
        </div>

        <div class=" shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Rank
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Watches Sold
                        </th>     
                        <th scope="col" class="px-6 py-3">
                            Watches Delivered
                        </th>              
                    </tr>
                </thead>

                <tbody>
                    @foreach ($rankedUsers as $index => $user)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                            <td class="px-6 py-4">
                                {{ $index + 1 }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                {{ $user->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->shipped_quantity }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $user->delivered_quantity }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
        <div class="mt-8">
            <h2 class="text-xl font-bold text-black mb-4">Sales Overview</h2>
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

            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="mb-2 text-lg text-gray-500">Total Sales</h3>
                <p class="text-3xl font-bold text-black">{{ number_format($orderStats['totalSales'], 2) }} PHP</p>
            </div>
        </div>
    @endif
    
    <div class="mt-8">
        <h2 class="text-xl font-bold text-black mb-4">Order Overview</h2>
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
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold text-black mb-4">Low Stock Products</h2>
    </div>

    <div class=" shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Image
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Code
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Quantity
                    </th>               
                </tr>
            </thead>

            <tbody>
                @foreach ($lowStockProducts as $product)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                        <td class="w-44 p-4">
                            <img src="{{ asset('storage/' . $product->image) }}" class="h-16 w-16 rounded-lg object-cover justify-center">
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->name }}
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                            {{ $product->code }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $product->stocks }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
