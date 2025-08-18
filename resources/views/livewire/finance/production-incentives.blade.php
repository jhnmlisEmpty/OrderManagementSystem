<div>
    <div class="mb-4 grid grid-cols-2 gap-4">
        <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" id="startDate" wire:model.live="startDate" @if(auth()->user()->role === 'production') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" id="endDate" wire:model.live="endDate" @if(auth()->user()->role === 'production') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 p-2">
        <div class="p-4 bg-gray-100 rounded-lg">
            <h3 class="text-sm font-lg">Number of Products Shipped</h3>
            <p class="text-lg font-semibold">{{ $totalProducts }}</p>
        </div>

        <div class="p-4 bg-gray-100 rounded-lg">
            <h3 class="text-sm font-lg">Number Of Parcels</h3>
            <p class="text-lg font-semibold">{{ $totalParcels ?? 0 }}</p>
        </div>

        <div class="p-4 bg-gray-100 rounded-lg">
            <h3 class="text-sm font-lg">Final Watches Out Incentives this week</h3>
            <p class="text-lg font-semibold">{{ $totalProducts ?? 0 }}</p>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-4 p-2">
        <div class="p-4 bg-gray-100 rounded-lg">
            <h3 class="text-sm font-lg">Number of Parcels</h3>
            <p class="text-lg font-semibold">{{ $attendedParcels ?? 0 }}</p>
        </div>

        <div class="p-4 bg-gray-100 rounded-lg">
            <h3 class="text-sm font-lg">Final Watches Out Incentives this week</h3>
            <p class="text-lg font-semibold">{{ $attendedProducts ?? 0 }}</p>
        </div>
    </div>


    @livewire('payslip.osr-payslip', ['userId' => $user->id])


    <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
        <div>  
            <h3 class="text-xl font-bold text-gray-900 mt-4">
                Data
            </h3>
        </div>
    </div>

    <div class="overflow-y-auto max-h-56 shadow-lg mt-4 rounded-lg">
        
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Agent
                    </th>
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
                        Page
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Payment Method
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
                        Status
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>
    
            <tbody>
                @foreach ($orders as $order)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                        <td class="w-4 p-4">
                            {{ $order->user->name }}
                        </td>
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
                            {{ $order->page ?? 'Not set' }}
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
                                        <td class="px-4 py-2">{{ $item->additionalService ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $item->servicePrice ? '₱' . number_format($item->servicePrice, 2) : 'N/A' }}</td>
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
                            {{ $order->status }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('view.order', ['orderId' => $order->id]) }}" target="_blank" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="overflow-y-auto max-h-56 shadow-lg mt-4 rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Platform Order ID
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Platform
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Page    
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Order Items
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Order Date
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Total Amount
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach ($platformOrders as $platformOrder)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $platformOrder->id }}
                        </th>
                        <td class="px-6 py-4">
                            {{ $platformOrder->platform }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $platformOrder->page }}
                        </td>
                        <td class="px-6 py-4 w-24">
                            <table class="w-full">
                                @foreach($platformOrder->platformOrderItems as $item)
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
                            {{ $platformOrder->created_at }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $platformOrder->full_amount }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
