<div class="container mx-auto mt-5">
    <!-- Form Wrapper -->
    <form wire:submit.prevent="saveOrderWithItems">
        <!-- Customer Details -->
        <h3 class="text-2xl text-center font-bold mb-2">Customer Information</h3>
        <div class="mb-8 grid grid-cols-2 gap-4 shadow-lg border rounded p-4">
            <div>
                <label for="customer_name" class="block font-medium">Name</label>
                <input type="text" id="customer_name" wire:model="customer_name" class="border rounded w-full p-2">
                @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="contact_number" class="block font-medium">Contact Number</label>
                <input type="text" id="contact_number" wire:model="contact_number" class="border rounded w-full p-2">
                @error('contact_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="street" class="block font-medium">Street</label>
                <input type="text" id="street" wire:model="street" class="border rounded w-full p-2">
                @error('street') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="landmark" class="block font-medium">Landmark</label>
                <input type="text" id="landmark" wire:model="landmark" class="border rounded w-full p-2">
                @error('landmark') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="city" class="block font-medium">City</label>
                <input type="text" id="city" wire:model="city" class="border rounded w-full p-2">
                @error('city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="province" class="block font-medium">Province</label>
                <input type="text" id="province" wire:model="province" class="border rounded w-full p-2">
                @error('province') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Payment Details -->
        <h3 class="text-2xl text-center font-bold mb-2">Payment Information</h3>
        <div class="mb-8 grid grid-cols-2 gap-4 shadow-lg border rounded p-4">
            <div>
                <label for="payment_method" class="block font-medium">Payment Method</label>
                <select id="payment_method" wire:model="payment_method" class="border rounded w-full p-2">
                    <option value="">Select Payment Method</option>
                    <option value="Bank">Bank</option>
                    <option value="Gcash">GCash</option>
                    <option value="Cod">COD</option>
                </select>
                @error('payment_method') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>

            <div>
                <label for="total_amount" class="block font-medium">Amount Paid</label>
                <input type="number" id="total_amount" wire:model.live="total_amount" class="border rounded w-full p-2">
                @error('total_amount') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="col-span-2">
                <label for="payment_status" class="block font-medium">Payment Proof</label>
                <input type="file" id="payment_status" wire:model="payment_status" class="border rounded w-full p-2" accept="image/*">
                
                @if ($payment_status)
                    <div class="mt-2">
                        <img src="{{ $payment_status->temporaryUrl() }}" alt="Payment Status Preview" class="w-32 h-32 object-cover border rounded">
                    </div>
                @endif
                
                @error('payment_status') 
                    <span class="text-red-500 text-sm">{{ $message }}</span> 
                @enderror
            </div>
        </div>

        <!-- Order Items -->
        <h3 class="text-2xl text-center font-bold mb-2">Order Items</h3>
        @foreach($cartItems as $index => $item)
            <div class="mb-8 shadow-lg border rounded p-4">
                <h3 class="text-sm font-bold mb-2">Order Item #{{ $index + 1 }}</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <div class="mt-4 max-w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex">
                            @if($item['product_image'])
                                <img src="{{ asset('storage/' . $item['product_image']) }}" alt="Product Image" class="w-20 h-20 object-cover border rounded">
                            @else
                                <span>No image available</span>
                            @endif
                            <div class="ml-6">
                                <a href="#">
                                    <h5 class="mb-2 text-md font-bold tracking-tight text-gray-900 dark:text-white">{{ $item['product_name'] ?? 'N/A' }}</h5>
                                </a>
                                <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Stocks: {{ $item['product_stock'] ?? 'N/A' }}</p>                 
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="mb-2">
                            <label for="price_{{ $index }}" class="block font-medium">Price</label>
                            <input type="number" id="price_{{ $index }}" wire:model.live="cartItems.{{ $index }}.price" class="border rounded w-full p-2">
                            @error("cartItems.{$index}.price") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        <div class="mb-2 flex space-x-4">
                            <div class="flex-1">
                                <label for="packaging_{{ $index }}" class="block font-medium">Packaging</label>
                                <select id="packaging_{{ $index }}" wire:model.live="cartItems.{{ $index }}.packaging_id" class="border rounded w-full p-2">
                                    <option value="">Select Packaging</option>
                                    @foreach($packagings as $packaging)
                                        <option value="{{ $packaging->id }}">{{ $packaging->name }}</option>
                                    @endforeach
                                </select>
                                @error("cartItems.{$index}.packaging_id") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                        
                            <div class="flex-1">
                                <label for="packaging_quantity_{{ $index }}" class="block font-medium">Packaging Quantity</label>
                                <input type="text" id="packaging_quantity_{{ $index }}" value="{{ $packagings->firstWhere('id', $cartItems[$index]['packaging_id'])->quantity ?? 'N/A' }}" class="border rounded w-full p-2" readonly>
                            </div>
                        </div>
                        <div>
                            <label for="quantity_{{ $index }}" class="block font-medium">Quantity</label>
                            <input type="number" id="quantity_{{ $index }}" wire:model.live="cartItems.{{ $index }}.quantity" class="border rounded w-full p-2">
                            @error("cartItems.{$index}.quantity") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @error('cartItems.' . $index . '.product_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-span-2 mt-4">
                    <label for="layout_{{ $index }}" class="block font-medium">Layout</label>
                    <input type="file" id="layout_{{ $index }}" wire:model="cartItems.{{ $index }}.layout" class="border rounded w-full p-2" multiple accept="image/*">
                    
                    @error("cartItems.{$index}.layout") 
                        <span class="text-red-500 text-sm">{{ $message }}</span> 
                    @enderror

                    @if (isset($cartItems[$index]['layout']) && is_array($cartItems[$index]['layout']))
                        <div class="mt-2 flex space-x-2 overflow-x-auto">
                            @foreach($cartItems[$index]['layout'] as $file)
                                <img src="{{ $file->temporaryUrl() }}" alt="Preview" class="w-20 h-20 object-cover">
                            @endforeach
                        </div>
                    @endif
                </div>
               <div class="text-center mt-4">
                    <span class="text-lg font-bold">Total: {{ (float) $cartItems[$index]['price'] * (int) $cartItems[$index]['quantity'] }}</span>
               </div>
               <div class="flex justify-center items-center">
                    <button type="button" wire:click="removeItem({{ $index }})" 
                        class="mt-2 bg-red-500 text-white px-3 py-1 rounded 
                        {{ count($cartItems) == 1 ? 'opacity-50 cursor-not-allowed' : '' }}" 
                        {{ count($cartItems) == 1 ? 'disabled' : '' }}>
                        Remove
                    </button>
                </div>
            </div>
        @endforeach

        <!-- Shipping and other -->
        <h3 class="text-2xl text-center font-bold mb-2"> Additional Information</h3>
        <div class="mb-8 grid grid-cols-2 gap-4 shadow-lg border rounded p-4">
            <div>
                <label for="platform" class="block font-medium">Platform</label>
                <select id="platform" wire:model="platform" class="border rounded w-full p-2">
                    <option value="">Select a platform</option>
                    <option value="Lazada">Lazada</option>
                    <option value="Shopee">Shopee</option>
                    <option value="Marketplace">Marketplace</option>
                    <option value="Facebook Page">Page</option>
                    <option value="Tiktok">TikTok</option>
                </select>
                @error('platform') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="note" class="block font-medium">Note</label>
                <textarea id="note" wire:model="note" class="border rounded w-full p-2"></textarea>
                @error('note') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-4 text-2xl font-semibold text-center">
            <span>Order Item Total: </span>
            <span>{{ number_format(collect($cartItems)->sum(function($item) {
                return (float) $item['price'] * (int) $item['quantity'];
            }), 2) }}</span>
        </div>

        <div class="mt-4 text-2xl font-semibold text-center">
            <span>Total: </span>
            <span>
                {{-- {{ number_format(
                    collect($cartItems)->sum(function($item) {
                        return (float) $item['price'] * (int) $item['quantity'];
                    }) - ($total_amount), 2)
                }} --}}
                {{ number_format(
                    collect($cartItems)->sum(function($item) {
                        return (float) $item['price'] * (int) $item['quantity'];
                    }) - (float) $total_amount, 2)
                }}
            </span>

        </div>

        <!-- Submit Button -->
        <div class="flex justify-center items-center mt-6">
            <button type="submit" class="bg-green-500 text-white px-16 py-2 rounded">Save Order</button>
        </div>
    </form>
</div>