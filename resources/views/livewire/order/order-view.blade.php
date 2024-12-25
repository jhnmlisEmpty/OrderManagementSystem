<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-lg font-medium leading-6 text-gray-900">Order Details (ID: {{ $order->id }})</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Agent: {{ $order->user->name }}</p>
        </div>
        <div class="border-t border-gray-200">
            <dl>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-4 sm:px-6">
                   <div>
                        <dt class="text-sm font-medium text-gray-500">Customer Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->customer->name }}</dd>
                   </div>
                   <div>
                        <dt class="text-sm font-medium text-gray-500">Contact Number</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->customer->contact_number }}</dd>
                   </div>
                   <div>
                        <dt class="text-sm font-medium text-gray-500">Customer Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            <p>
                                {{ 
                                    ($order->customer->landmark ?? '') . 
                                    ($order->customer->street ? ', ' . $order->customer->street : '') . 
                                    ($order->customer->city ? ', ' . $order->customer->city : '') . 
                                    ($order->customer->province ? ', ' . $order->customer->province : '') 
                                }}
                            </p>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Platform</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->platform }}</dd>
                   </div>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-4 sm:px-6">
                    <div>
                         <dt class="text-sm font-medium text-gray-500">Tracking</dt>
                         <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{$order->tracking ?? 'Waiting to set by logistician'}}</dd>
                    </div>
                    <div>
                         <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                         <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->created_at }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Note</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->note }}</dd>
                   </div>
                   <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{$order->status}}</dd>
                   </div>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-4 sm:px-6">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Method</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{$order->paymentMethod->method}}</dd>
                   </div>
                    <!-- Amount From Items -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount From Items</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->total_from_items }}</dd>
                    </div>
                
                    <!-- Amount From Payment Method -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Amount From Payment Method</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->paymentMethod->amount }}</dd>
                    </div>
                
                    <!-- Shipping Price -->
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Shipping Price</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $order->shipping_price ?? 'Waiting to set by logistician' }}
                        </dd>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-4 sm:px-6">
                    <!-- Full Amount -->
                    <div>
                        <dt class="text-xl font-medium text-gray-500">Full Amount</dt>
                        <dd class="mt-1 text-xl text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->full_amount }}</dd>
                    </div>

                    <div>
                        <!-- Update Status Button -->
                        @if($order->status == 'pending')
                        <div >
                            <div>
                                <button wire:click="updateStatus('on_prod')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update Status to On Production
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'on_prod')
                        <div >
                            <div>
                                <button wire:click="updateStatus('created')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update Status to Created
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'created')
                        <div >
                            <div>
                                <button wire:click="updateStatus('shipped')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update Status to Shipped
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'shipped')
                        <div >
                            <div>
                                <button wire:click="updateStatus('delivered')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update Status to Delivered
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'delivered')
                        <div >
                            <div>
                                <button wire:click="updateStatus('canceled')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update Status to Canceled
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'canceled')
                        <div >
                            <div>
                                <button wire:click="updateStatus('returned')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update Status to Returned
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'returned')
                        <div >
                            <div>
                                <button wire:click="updateStatus('reprocess')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update Status to Reprocess
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($order->status == 'reprocess')
                        <div >
                            <div>
                                <button wire:click="updateStatus('pending')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                    Update Status to Pending
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>   
            </dl>
        </div>
    </div>

    <!-- Order Items -->
    <div class="mt-8">
        <h4 class="text-xl font-semibold text-gray-800">Order Items:</h4>
        <div class="overflow-y-auto max-h-96"> <!-- Add overflow and set max height -->
            <table class="min-w-full mt-4 text-sm text-left text-gray-500">
                <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="px-6 py-3">Product Name</th>
                        <th class="px-6 py-3">Quantity</th>
                        <th class="px-6 py-3">Price</th>
                        <th class="px-6 py-3">Total</th>
                        <th class="px-6 py-3">Layout</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                            <td class="px-6 py-4">{{ $item->product->name }}</td>
                            <td class="px-6 py-4">{{ $item->quantity }}</td>
                            <td class="px-6 py-4">${{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4">${{ number_format($item->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @if($item->layout)
                                    <div class="flex flex-wrap gap-2 justify-center"> <!-- Added flex-wrap and gap -->
                                        @foreach(json_decode($item->layout) as $layout)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($layout) }}" alt="Order Item Layout" class="w-24 h-24 object-cover rounded">
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-gray-500">No layout submitted</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    
</div>
