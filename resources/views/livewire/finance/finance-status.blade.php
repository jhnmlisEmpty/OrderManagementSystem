<div class="container mx-auto mt-5  max-w-full" >
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Finance Details
                </h3>
            </div>
        </div>
        <div class="px-4 py-5 sm:px-6">
            <p class="mt-1 max-w-2xl text-lg text-gray-500">Agent: {{ $user->name }}</p>
            <p class="uppercase mt-1 max-w-2xl text-sm text-gray-500">Role: {{ $user->role }}</p>
        </div>
    
        <div class="grid grid-cols-2 gap-4 mb-6 p-2">
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="startDate" wire:model.live="startDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
    
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="endDate" wire:model.live="endDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
    
            <div>
                <label for="incentivePercentage" class="block text-sm font-medium text-gray-700">Incentive Percentage (%)</label>
                <input type="number" id="incentivePercentage" wire:model.live="incentivePercentage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    
        <div class="grid grid-cols-2 gap-4 p-2">
            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm font-medium">Shipped Watches</h3>
                <p class="text-lg font-semibold">{{ $shippedCount }}</p>
                <h3 class="text-sm font-medium">Total Amount</h3>
                <p class="text-lg font-semibold">₱{{ number_format($shippedAmount, 2) }}</p>
            </div>
    
            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm font-medium">Delivered Watches</h3>
                <p class="text-lg font-semibold">{{ $deliveredCount }}</p>
                <h3 class="text-sm font-medium">Total Amount</h3>
                <p class="text-lg font-semibold">₱{{ number_format($deliveredAmount, 2) }}</p>
            </div>

            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm font-medium">Returned Watches</h3>
                <p class="text-lg font-semibold">{{ $returnedCount }}</p>
                <h3 class="text-sm font-medium">Total Deducted Amount</h3>
                <p class="text-lg font-semibold">₱{{ number_format($deductedAmount, 2) }}</p>
            </div>
    
            <div class="p-4 bg-gray-100 rounded-lg col-span-2">
                <h3 class="text-sm font-medium">Total Incentive</h3>
                <p class="text-lg font-semibold text-green-500">₱{{ number_format($totalIncentive, 2) }}</p>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold text-black mb-4">Delivered Orders</h2>
        </div>

        <div class="shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-x-auto">
                <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Order ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tracking
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Customer Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Platform
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Payment
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Order Items
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Order Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Payment
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Shipping Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Delivered Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>
    
                <tbody>
                    @if($deliveredOrders->isEmpty())
                        <tr>
                            <td colspan="12" class="p-4 text-center text-gray-500">
                                No orders found matching your search criteria.
                            </td>
                        </tr>
                    @else
                        @foreach ($deliveredOrders as $order)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                                <td class="w-4 p-4">
                                    {{ $order->id }}
                                </td>
                                <td>
                                    @if($order->tracking)
                                        <a href="https://www.trackingmore.com/track/en/{{ $order->tracking }}?express=jtexpress-ph" 
                                        class="text-blue-500 underline" 
                                        target="_blank">
                                            {{ $order->tracking }}
                                        </a>
                                    @else
                                        Not set
                                    @endif
                                </td>
                                <td class="w-4 p-4">
                                    {{ $order->customer->name }}
                                </td>
                                <td class="w-4 p-4">
                                    {{ $order->platform }}
                                </td>
                                <td class="w-4 p-4">
                                    {{ $order->paymentMethod->method }}
                                </td>
                                <td class="px-6 py-4 w-24">
                                    <table class="w-full">
                                        @foreach($order->orderItems as $item)
                                            <tr>
                                                <td class="px-4 py-2">{{ $item->product->name }}</td>
                                                <td class="px-4 py-2">₱{{ number_format($item->price, 2) }}</td>
                                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                                <td class="px-4 py-2">{{ $item->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ number_format($order->total_from_items, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ number_format($order->paymentMethod->amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->shipping_price)
                                        ₱{{ number_format($order->shipping_price, 2) }}
                                    @else
                                        Not set
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ number_format($order->full_amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->date_received }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $order->status }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('view.order', ['orderId' => $order->id]) }}" target="_blank" class="font-medium text-blue-600 dark:blue-red-500 hover:underline">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold text-black mb-4">Returned Orders</h2>
        </div>

        <div class="shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-x-auto">
                <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Order ID
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Tracking
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Customer Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Platform
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Payment
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Order Items
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Order Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Payment
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Shipping Price
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Total
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Returned Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Actions
                        </th>
                    </tr>
                </thead>
    
                <tbody>
                    @if($deliveredOrders->isEmpty())
                        <tr>
                            <td colspan="12" class="p-4 text-center text-gray-500">
                                No orders found matching your search criteria.
                            </td>
                        </tr>
                    @else
                        @foreach ($returnedOrders as $returnedOrder)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                                <td class="w-4 p-4">
                                    {{ $returnedOrder->id }}
                                </td>
                                <td>
                                    @if($returnedOrder->tracking)
                                        <a href="https://www.trackingmore.com/track/en/{{ $returnedOrder->tracking }}?express=jtexpress-ph" 
                                        class="text-blue-500 underline" 
                                        target="_blank">
                                            {{ $returnedOrder->tracking }}
                                        </a>
                                    @else
                                        Not set
                                    @endif
                                </td>
                                <td class="w-4 p-4">
                                    {{ $returnedOrder->customer->name }}
                                </td>
                                <td class="w-4 p-4">
                                    {{ $returnedOrder->platform }}
                                </td>
                                <td class="w-4 p-4">
                                    {{ $returnedOrder->paymentMethod->method }}
                                </td>
                                <td class="px-6 py-4 w-24">
                                    <table class="w-full">
                                        @foreach($returnedOrder->orderItems as $item)
                                            <tr>
                                                <td class="px-4 py-2">{{ $item->product->name }}</td>
                                                <td class="px-4 py-2">₱{{ number_format($item->price, 2) }}</td>
                                                <td class="px-4 py-2">{{ $item->quantity }}</td>
                                                <td class="px-4 py-2">{{ $item->total_amount }}</td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ number_format($returnedOrder->total_from_items, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ number_format($returnedOrder->paymentMethod->amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($returnedOrder->shipping_price)
                                        ₱{{ number_format($returnedOrder->shipping_price, 2) }}
                                    @else
                                        Not set
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    ₱{{ number_format($returnedOrder->full_amount, 2) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $returnedOrder->order_returned_date }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $returnedOrder->status }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('view.order', ['orderId' => $returnedOrder->id]) }}" target="_blank" class="font-medium text-blue-600 dark:blue-red-500 hover:underline">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        {{-- <div class="mt-4 px-4">
            {{ $deliveredOrders->links() }} 
        </div> --}}
    </div>
</div>
