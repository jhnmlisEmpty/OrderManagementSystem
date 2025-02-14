<div class="container mx-auto mt-5 max-w-full">
    <div>
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Platform Orders
                </h3>
            </div>
        </div>

        <div class="mt-8">
            <h2 class="text-xl font-bold text-black mb-4">Running Monthly Sales</h2>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div class="col-span-2 md:col-span-1 lg:col-span-1">
                <input type="date" wire:model.live="startDate" hidden class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
            <div class="col-span-2 md:col-span-1 lg:col-span-1">
                <input type="date" wire:model.live="endDate" hidden class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>
        </div>
    
        @forelse($salesData as $platform => $pages)
            <div class="mt-8">
            <h2 class="text-xl text-black mb-4">{{ $platform }}</h2>
            </div>
            <div class="grid grid-cols-1 gap-6 pb-10 mt-6 md:grid-cols-2 lg:grid-cols-3">
            @foreach($pages as $page)
                <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="mb-2 text-lg text-gray-500">{{ $page->page }}</h3>
                <p class="text-3xl font-bold text-black">{{ number_format($page->total_sales, 2) }} PHP</p>
                </div>
            @endforeach
            </div>
        @empty
            <div class="mt-8">
            <h2 class="text-center text-xl text-black mb-4">No Data Found</h2>
            </div>
        @endforelse

        <div class="relative overflow-x-auto shadow-lg sm:rounded-lg p-4">
            <div class="flex flex-column sm:flex-row flex-wrap space-y-4 sm:space-y-0 items-center justify-between py-4">
                <div>
                    
                </div>
                <div>
                    <a href="{{ route('create.order') }}" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Create New Order For Other Platfom
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
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
                    <th scope="col" class="px-6 py-3">
                        Action
                    </th>
                </tr>
            </thead>

            <tbody>
                <!-- Display No Products Message if Empty -->
                @if($platformOrders->isEmpty())
                    <tr>
                        <td colspan="7" class="p-4 text-center text-gray-500">
                            No products found matching your search criteria.
                        </td>
                    </tr>
                @else
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
                            <td class="px-6 py-4">
                                <button wire:click="deletePlatformOrder({{ $platformOrder->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>