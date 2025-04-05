<div class="container mx-auto mt-5  max-w-full">
    <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 mt-4">
                Update Products
            </h3>
        </div>
    </div>

    <div class="relative overflow-x-auto shadow-lg sm:rounded-lg p-4">
        <form wire:submit.prevent="updateProduct">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" id="name" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
    
           <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Product Code</label>
                    <input type="text" id="code" wire:model="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
    
                <div class="mb-4">
                    <label for="stocks" class="block text-sm font-medium text-gray-700">Stocks</label>
                    <input type="number" id="stocks" wire:model="stocks" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('stocks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
           </div>
    
            {{-- <div class="mb-4">
                <label for="newImage" class="block text-sm font-medium text-gray-700">Product Image</label>
                <input type="file" id="newImage" wire:model="newImage" class="mt-1 block w-full">
                @error('newImage') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    
                @if ($newImage)
                    
                @else
                    <div class="mt-2">
                        <img src="{{ asset('storage/' . $image) }}" class="h-24 w-24 object-cover">
                    </div> 
                @endif
            </div>     --}}

            <div class="grid grid-cols-1 md:grid-cols-2 mb-4">
                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                    <input type="file" id="image" wire:model="image" class="mt-1 block w-full">
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                    @if ($newImage)
                        {{-- <div class="mt-2">
                            <img src="{{ $newImage->temporaryUrl() }}" class="h-24 w-24 object-cover">
                        </div> --}}
                    @else
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $image) }}" class="h-24 w-24 object-cover">
                        </div> 
                    @endif
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                    <select id="category" wire:model="category" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">Select a category</option>
                        <option value="Watch & Apparel"> Watch & Apparel</option>
                        <option value="Health & Beauty Product"> Health & Beauty Product</option>
                    </select>
                    @error('category') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
    
            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                Update Product
            </button>
        </form>
    </div>
</div>
