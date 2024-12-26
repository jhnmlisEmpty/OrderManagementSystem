<div class="container mx-auto mt-5">
    <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 mt-4">
                Logistics
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
        </div>
    </div>

    <div class="space-y-4 mt-6">
        <!-- Status Filter Buttons -->
        <div class="flex space-x-4">
            @foreach ($statuses as $statusOption)
                <button 
                    wire:click="$set('statusFilter', '{{ $statusOption }}')" 
                    wire:loading.attr="disabled"
                    wire:key="status-{{ $statusOption }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-md focus:outline-none"
                >
                    {{ ucfirst($statusOption) }} 
                    <span class="ml-2 bg-white text-gray-600 px-2 rounded-full text-xs">
                        {{ $statusCounts[$statusOption] ?? 0 }}
                    </span>
                </button>
            @endforeach

            <div class="">
                <button wire:click="fetchAllOrders" class="px-4 py-2 bg-gray-500 text-white rounded-md">Fetch All Orders</button>
            </div>
        </div>
    
        <!-- Fetch All Orders Button -->
        
    
        <!-- Batch Update Status Buttons -->
        <div class="space-y-4 mt-6">
            <!-- Batch Update Status Buttons -->
            <div class="flex flex-wrap gap-4">
                @foreach ($statuses as $status)
                    <div class="flex-shrink-0 w-1/4">
                        <button 
                            wire:click="batchUpdateStatus('{{ $status }}')" 
                            class="px-4 py-2 bg-green-500 text-white rounded-md" 
                            wire:loading.attr="disabled" 
                            wire:target="batchUpdateStatus"
                        >
                            Update Selected Orders to {{ ucfirst($status) }}
                        </button>
                    </div>
                @endforeach
            </div>
            
        </div>                  
    </div>
    

    <!-- Orders Table -->
    <div class="overflow-y-auto max-h-full shadow-lg mt-4 rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-x-auto">
            <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        <input type="checkbox" wire:model.live="selectAll" class="form-checkbox">
                    </th>
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
                            <td class="px-6 py-4">
                                <input type="checkbox" wire:model.live="selectedOrders" value="{{ $order->id }}" class="form-checkbox">
                            </td>
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
                                <button wire:click="editOrder({{ $order->id }})" class="px-4 py-2 bg-blue-600 text-white rounded">
                                    Set Logistics
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modal for editing an order -->
    @if ($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="w-full max-w-md p-6 bg-white rounded-lg shadow-lg">
                <h3 class="text-lg font-semibold mb-4">Edit Order #{{ $orderId }}</h3>
                <form wire:submit.prevent="updateOrder">
                    <div class="mb-4">
                        <label for="status" class="block text-gray-700">Status</label>
                        <select wire:model="status" id="status" class="w-full border rounded p-2">
                            <option value="pending">Pending</option>
                            <option value="on_prod">On Production</option>
                            <option value="created">Created</option>
                            <option value="shipped">Shipped</option>
                            <option value="delivered">Delivered</option>
                            <option value="canceled">Canceled</option>
                            <option value="returned">Returned</option>
                            <option value="reprocess">Reprocess</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="shipping_price" class="block text-gray-700">Shipping Price</label>
                        <input wire:model="shipping_price" type="number" id="shipping_price" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4">
                        <label for="tracking" class="block text-gray-700">Tracking</label>
                        <input wire:model="tracking" type="text" id="tracking" class="w-full border rounded p-2">
                    </div>

                    <div class="mb-4 flex justify-end">
                        <button type="button" wire:click="$set('showModal', false)" class="bg-gray-400 text-white px-4 py-2 rounded">
                            Cancel
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded ml-2">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
