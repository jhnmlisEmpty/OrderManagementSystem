<!-- filepath: /C:/Users/Hi/Documents/School Files/OrderManagementSystem/resources/views/livewire/employee/assign-products.blade.php -->

<div class="container mx-auto mt-5  max-w-full">
    <div>
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Assign Products to {{ $user->name }}
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
                            wire:model.live="search"
                            class="block p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg w-80 bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Search for products" 
                        />
                    </div>                
                </div>
                <div>
                    <button wire:click="toggleSelectAll" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        {{ $selectAll ? 'Deselect All' : 'Select All' }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="assignProducts">
        <div class="shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                    <tr>
                        <th scope="col" class="px-6 py-3">
                            Select
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Image
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Name
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Code
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Stocks
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if($products->isEmpty())
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                No products found matching your search criteria.
                            </td>
                        </tr>
                    @else
                        @foreach($products as $product)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                                <td class="px-6 py-4">
                                    <input type="checkbox" wire:model="selectedProducts" value="{{ $product->id }}" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out"
                                        {{ in_array($product->id, $selectedProducts) ? 'checked' : '' }}>
                                </td>
                                <td class="px-6 py-4">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-12 w-12 object-cover">
                                </td>
                                <td class="px-6 py-4">
                                    {{ $product->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $product->code }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $product->stocks }}
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-4 px-4">
            {{ $products->links() }} 
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md mt-4">Assign Products</button>
    </form>
</div>