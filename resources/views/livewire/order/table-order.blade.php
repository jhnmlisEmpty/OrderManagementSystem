
<div class="container mx-auto mt-5"> 
    <div>
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4 ">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Orders
                </h3>
            </div>
        </div>
    
        <div class="relative overflow-x-auto shadow-lg sm:rounded-lg p-4">
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between py-4">
                <div>
                    <label for="table-search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 rtl:inset-r-0 rtl:right-0 flex items-center ps-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <input 
                            type="text" 
                            id="search" 
                            wire:key="search-input"
                            class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search for orders" 
                            wire:model.live="search"
                        />
                    </div>  
                    
                    
                </div>

                <div>
                    <a href="{{ route('create.order') }}" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Create New Order
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class=" shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-x-auto">
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
                        Payment
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
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>
                @if($orders->isEmpty())
                    <tr>
                        <td colspan="11" class="p-4 text-center text-gray-500">
                            No orders found matching your search criteria.
                        </td>
                    </tr>
                @else
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
                                {{ $order->paymentMethod->method }}
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format($order->total_from_items, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format($order->paymentMethod->amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($order->shipping_price)
                                    ${{ number_format($order->shipping_price, 2) }}
                                @else
                                    Not set
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                ${{ number_format($order->full_amount, 2) }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order->status }}
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="deleteOrder({{ $order->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                    Delete
                                </button>
                                <a href="{{ route('view.order', ['orderId' => $order->id]) }}" class="font-medium text-blue-600 dark:text-blue-600 hover:underline">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4 px-4">
        {{ $orders->links() }} 
    </div>

</div>
