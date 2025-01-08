<div class="container mx-auto mt-5 max-w-full">
    <div>
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
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
                        <div class="absolute inset-y-0 left-0 flex items-center ps-3 pointer-events-none">
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
                    @if (auth()->user()->role === 'osr'|| auth()->user()->role === 'affiliate'|| auth()->user()->role === 'admin'|| auth()->user()->role === 'super_admin')
                        <a href="{{ route('create.order') }}" 
                            class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                            Create New Order
                        </a>          
                    @endif
                </div>
            </div>
        </div>
    </div>

    <ul class="overflow-y-hidden overflow-x-hidden flex border-b mt-4">
        <li class="-mb-px mr-1">
            <a wire:click.prevent="switchTab('pending')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'pending' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                Pending ({{ $pendingCount }})
            </a>
        </li>
        <li class="mr-1">
            <a wire:click.prevent="switchTab('on_prod')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'on_prod' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                On Production ({{ $onProdCount }})
            </a>
        </li>
        <li class="mr-1">
            <a wire:click.prevent="switchTab('created')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'created' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                Created ({{ $createdCount }})
            </a>
        </li>
        <li class="mr-1">
            <a wire:click.prevent="switchTab('ready_to_ship')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'ready_to_ship' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                Ready to Ship ({{ $readytoshipCount }})
            </a>
        </li>
        <li class="mr-1">
            <a wire:click.prevent="switchTab('shipped')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'shipped' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                Shipped ({{ $shippedCount }})
            </a>
        </li>
        <li class="mr-1">
            <a wire:click.prevent="switchTab('delivered')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'delivered' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                Delivered ({{ $deliveredCount }})
            </a>
        </li>
        <li class="mr-1">
            <a wire:click.prevent="switchTab('canceled')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'canceled' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                Canceled ({{ $canceledCount }})
            </a>
        </li>
        <li class="mr-1">
            <a wire:click.prevent="switchTab('returned')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'returned' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                Returned ({{ $returnedCount }})
            </a>
        </li>
        <li class="mr-1">
            <a wire:click.prevent="switchTab('reprocess')" class="cursor-pointer bg-white inline-block py-2 px-4 {{ $currentTab === 'reprocess' ? 'text-blue-700 border-l border-t border-r rounded-t' : 'text-blue-500 hover:text-blue-800' }} font-semibold">
                Reprocess ({{ $reprocessCount }})
            </a>
        </li>
    </ul>

    <!-- Table -->
    <div class="shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
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
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>
                @if($orders->isEmpty())
                    <tr>
                        <td colspan="12" class="p-4 text-center text-gray-500">
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
                                @if (auth()->user()->role === 'admin'|| auth()->user()->role === 'super_admin')
                                    <button wire:click="deleteOrder({{ $order->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                        Delete
                                    </button>
                                @endif
                                
                                <a href="{{ route('view.order', ['orderId' => $order->id]) }}" target="_blank" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>