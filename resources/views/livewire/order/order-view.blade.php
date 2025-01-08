<div class="container mx-auto mt-5  max-w-full">
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="px-4 py-5 sm:px-6">
            <h3 class="text-2xl font-medium leading-6 text-gray-900">Order Details (ID: {{ $order->id }})</h3>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Agent: {{ $order->user->name }}</p>
        </div>
        <div class="overflow-y-auto border-t border-gray-200">
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
                        <dt class="text-sm font-medium text-gray-500">Platform</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->platform }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Page</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->page ?? 'Not set' }}</dd>
                   </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Landmark</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->customer->landmark ?? '' }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Street</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->customer->street ?? '' }}</dd>
                   </div>
                   <div>
                        <dt class="text-sm font-medium text-gray-500">City</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->customer->city ?? '' }}</dd>
                   </div>
                   <div>
                        <dt class="text-sm font-medium text-gray-500">Province</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->customer->province ?? '' }}</dd>
                   </div>
                </div>
                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-4 sm:px-6">
                    <div>
                         <dt class="text-sm font-medium text-gray-500">Tracking</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if($order->tracking)
                                <a href="https://www.trackingmore.com/track/en/{{ $order->tracking }}?express=jtexpress-ph" 
                                   class="text-blue-500 underline" 
                                   target="_blank">
                                    {{ $order->tracking }}
                                </a>
                            @else
                                Waiting to set by logistician
                            @endif
                        </dd>
                    </div>
                    <div>
                         <dt class="text-sm font-medium text-gray-500">Order Date</dt>
                         <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->created_at }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Note</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->note ?? 'No Note' }}</dd>
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

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Payment Proof</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            @if ($order->paymentMethod->image)
                                <div class="flex flex-wrap gap-2">
                                    @if (is_array(json_decode($order->paymentMethod->image)))
                                        @foreach (json_decode($order->paymentMethod->image) as $image)
                                            <img src="{{ asset('storage/' . $image) }}" alt="Payment Proof" class="w-20 h-20 rounded-md shadow-md">
                                        @endforeach
                                    @else
                                        {{-- Accept string as image --}}
                                        @php
                                            $imagePath = json_decode($order->paymentMethod->image, true);
                                        @endphp
                                        <img src="{{ asset('storage/' . $imagePath) }}" alt="Payment Proof" class="w-20 h-20 rounded-md shadow-md">
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500">No payment proof uploaded.</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Shipped Date</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                            {{ $order->order_ship_date ?? 'Waiting to ship' }}
                        </dd>
                    </div>

                    @if(in_array($order->status, ['delivered', 'returned']))
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                @if($order->status === 'delivered')
                                    Delivered Date
                                @elseif($order->status === 'returned')
                                    Returned Date
                                @else
                                    N/A
                                @endif
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                @if($order->status === 'delivered')
                                    {{ $order->date_received ?? 'Not yet delivered' }}
                                @elseif($order->status === 'returned')
                                    {{ $order->order_returned_date ?? 'Not yet returned' }}
                                @else
                                    N/A
                                @endif
                            </dd>
                        </div>
                    @endif
                    <div>
                        @if(!empty($order->order_reprocess_date))
                            <dt class="text-sm font-medium text-gray-500">
                                Reprocessed Date
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
                                {{$order->order_reprocess_date}}
                            </dd>
                            
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-4 sm:px-6">
                    <!-- Full Amount -->
                    <div>
                        <dt class="text-xl font-medium text-gray-500">Full Amount</dt>
                        <dd class="mt-1 text-xl text-gray-900 sm:col-span-2 sm:mt-0">{{ $order->full_amount }}</dd>
                    </div>

                    <div class="flex space-x-4 col-span-2">
                     
                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' || auth()->user()->role === 'production')
                            @if($order->status == 'pending')
                            <div >
                                <div>
                                    <button wire:click="updateStatus('on_prod')"  class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        Accept Order
                                    </button>
                                </div>
                            </div>
                            @endif
                            @if($order->status == 'on_prod')
                            <div >
                                <div>
                                    <button wire:click="updateStatus('created')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        Order Created
                                    </button>
                                </div>
                            </div>
                            @endif
                        @endif                   
                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' || auth()->user()->role === 'logistics' || auth()->user()->role === 'osr' || auth()->user()->role === 'affiliate')
                            @if($order->status == 'pending')
                                <div>
                                    <button wire:click="updateStatus('canceled')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        Cancel Order
                                    </button>
                                </div>
                            @endif
                            @if($order->status == 'created')
                            <div class="col-span-">
                                <div class="flex items-center space-x-4">
                                    <button wire:click="updateStatus('ready_to_ship')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                        Ready to Ship
                                    </button>   
                                </div>
                            </div>
                            @endif
                        @endif
                        @if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin' || auth()->user()->role === 'logistics')
                            @if ($order->status == 'ready_to_ship')
                                <div class="col-span-2">
                                    <div class="flex items-center space-x-2">
                                        <input type="text" wire:model="tracking" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Tracking Number">
                                        <input type="number" step="0.01" wire:model="shippingFee" class="block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Shipping Fee">
                                        <button wire:click="updateStatus('shipped')" class="w-3/4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Ship Order</button>
                                    </div>
                                    @error('tracking') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    @error('shippingFee') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            @endif
                            @if($order->status == 'shipped')
                                <div class="flex space-x-4">

                                    <div>
                                        <input type="date" id="statusDate" wire:model="statusDate" class=" block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        @error('statusDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                        
                                       
                                    </div>
                                    <div>
                                        <button wire:click="updateStatus('delivered')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                            Delivered
                                        </button>
                                    </div>
                                    <div>
                                        <button wire:click="updateStatus('returned')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                            Returned
                                        </button>
                                    </div>
                                    <div>
                                        <button wire:click="updateStatus('reprocess')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                            Reprocess
                                        </button>
                                    </div>
                                </div>
                            @endif
                            @if($order->status == 'reprocess')
                                <div class="flex space-x-4">
                                    <div>
                                        <button wire:click="updateStatus('pending')" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                                            Order Pending
                                        </button>
                                    </div>
                                </div>
                            @endif
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
                            <td class="px-6 py-4">₱{{ number_format($item->price, 2) }}</td>
                            <td class="px-6 py-4">₱{{ number_format($item->total_amount, 2) }}</td>
                            <td class="px-6 py-4">
                                @if($item->layout)
                                    <div class="flex flex-wrap gap-2 justify-center"> <!-- Added flex-wrap and gap -->
                                        @foreach(json_decode($item->layout) as $layout)
                                            <div class="mb-2">
                                                <img src="{{ Storage::url($layout) }}" alt="Order Item Layout" class="w-12 h-12 object-cover rounded">
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
