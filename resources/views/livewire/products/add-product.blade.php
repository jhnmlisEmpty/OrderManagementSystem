<div class="container mx-auto mt-5">
    <div class="flex flex-column sm:flex-row flex-wrap space-y-10 sm:space-y-0 items-center justify-between">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 mt-4">
                Add Products
            </h3>
        </div>
    </div>

   <div  class="relative overflow-x-auto shadow-lg sm:rounded-lg p-4">
        <form wire:submit.prevent="addProduct">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Product Name</label>
                <input type="text" id="name" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="grid gap-6 mb-6 md:grid-cols-2">
                <div class="mb-4">
                    <label for="stocks" class="block text-sm font-medium text-gray-700">Stocks</label>
                    <input type="number" id="stocks" wire:model="stocks" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('stocks') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
        
                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">Product Code</label>
                    <input type="text" id="code" wire:model="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @error('code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Product Image</label>
                <input type="file" id="image" wire:model="image" class="mt-1 block w-full">
                @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                {{-- @if ($image)
                    <div class="mt-2">
                        <img src="{{ $image->temporaryUrl() }}" class="h-24 w-24 object-cover">
                    </div>
                @endif --}}
            </div>
            <div class="mb-4">
                <label for="preview" class="block text-sm font-medium text-gray-700">Image Preview</label>
                <img id="imagePreview" class="h-24 w-24 object-cover">
            </div>

            <script>
                document.getElementById('image').addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('imagePreview').src = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            </script>

            

            <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">
                Add Products
            </button>
        </form>
   </div>
</div>
