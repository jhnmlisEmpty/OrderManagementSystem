<div class="container mx-auto mt-5 max-w-full">
    <!-- Form Wrapper -->
    <form wire:submit.prevent="saveOrderWithItems">
        <!-- Order Items -->
        <h3 class="text-2xl text-center font-bold mb-2 mt-4">Order Items</h3>
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
                            <label for="price_{{ $index }}" class="block font-medium">Product Price</label>
                            <input type="number" id="price_{{ $index }}" wire:model.live="cartItems.{{ $index }}.price" class="border rounded w-full p-2">
                            @error("cartItems.{$index}.price") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                        
                        <div>
                            <label for="quantity_{{ $index }}" class="block font-medium">Quantity</label>
                            <input type="number" id="quantity_{{ $index }}" wire:model.live="cartItems.{{ $index }}.quantity" class="border rounded w-full p-2">
                            @error("cartItems.{$index}.quantity") <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            @error('cartItems.' . $index . '.product_id')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
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
                                <label for="packaging_quantity_{{ $index }}" class="block font-medium">Packaging Available Quantity</label>
                                <input type="text" id="packaging_quantity_{{ $index }}" value="{{ $packagings->firstWhere('id', $cartItems[$index]['packaging_id'])->quantity ?? 'N/A' }}" class="border rounded w-full p-2" readonly>
                            </div>
                        </div>
                    </div>
                </div>
               <div class="text-center mt-4">
                    <span class="text-lg font-bold">Total: {{ (float) $cartItems[$index]['price'] * (int) $cartItems[$index]['quantity'] }}</span>
               </div>
               <div class="flex justify-center items-center">
                    <button type="button" wire:click="removeItem({{ $index }})" 
                        class="mt-2 bg-red-500 text-white px-3 py-1 rounded">
                        Remove
                    </button>
                </div>
            </div>
        @endforeach

        

        <div class="mt-4 text-xl font-semibold underline text-center">
            <span>Order Item Total: </span>
            <span>{{ number_format(collect($cartItems)->sum(function($item) {
                return (float) $item['price'] * (int) $item['quantity'];
            }), 2) }}</span>
        </div>

        <!-- Shipping and other -->
        <h3 class="text-2xl mt-6 text-center font-bold mb-2"> Additional Information</h3>
        <div class="mb-8 grid grid-cols-2 gap-4 shadow-lg border rounded p-4">
            <div>
                <label for="platform" class="block font-medium">Platform</label>
                <select id="platform" wire:model="platform" class="border rounded w-full p-2">
                    <option value="">Select a platform</option>
                    <option value="Lazada">Lazada</option>
                    <option value="Shopee">Shopee</option>
                    <option value="Tiktok">Tiktok</option>
                    <option value="Website">Website</option>
                    <option value="Others">Others</option>
                </select>
                @error('platform') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="page" class="block font-medium">Page</label>
                <select id="page" wire:model="page" class="border rounded w-full p-2">
                    <option value="">Select a page</option>
                    <option value="F8watch">F8Watch</option>
                    <option value="Brotherhood">Brotherhood</option>
                    <option value="Alfacharlie">AlfaCharlie</option>
                    <option value="Others">Others</option>
                </select>
                @error('page') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="mt-4 text-2xl font-semibold text-center">
            <span>Total: </span>
            <span>
                {{ number_format(
                    collect($cartItems)->sum(function($item) {
                        return (float) $item['price'] * (int) $item['quantity'];
                    }), 2)
                }}
            </span>
            
        </div>
        <!-- Submit Button -->
        <div class="flex justify-center items-center mt-6">
            <button type="submit" class="bg-green-500 text-white px-16 py-2 rounded">Save Order</button>
        </div>
    </form>
</div>