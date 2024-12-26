<div class="container mx-auto mt-5"> 
    <div>
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Products
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
                            placeholder="Search for items" 
                            wire:model.live="search"
                        />
                    </div>                
                </div>
    
                <div>
                    <a href="{{ route('add.product') }}" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Add New Product
                    </a>
                </div>
            </div>
        </div>
    </div>

        <!-- Table -->
        <div class=" shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                    <tr>
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
                            Stock
                        </th>              
                        <th scope="col" class="px-6 py-3">
                            Action
                        </th>
                    </tr>
                </thead>
    
                <tbody>
                    <!-- Display No Products Message if Empty -->
                    @if($products->isEmpty())
                        <tr>
                            <td colspan="5" class="p-4 text-center text-gray-500">
                                No products found matching your search criteria.
                            </td>
                        </tr>
                    @else
                        @foreach ($products as $product)
                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                                <td class="w-4 p-4">
                                    <img src="{{ asset('storage/' . $product->image) }}" class="h-12 w-12 object-cover justify-center">
                                </td>
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $product->name }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $product->code }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $product->stocks }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('update.product', ['id' => $product->id]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                        Edit
                                    </a> | 
                                    <button wire:click="openModal({{ $product->id }})" class="text-green-600 hover:underline">
                                        View
                                    </button> |
                                    <button wire:click="deleteProduct({{ $product->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                        Delete
                                    </button>
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


        <div>
            <!-- Modal Background -->
            <div 
                x-data="{ isOpen: @entangle('isModalOpen') }" 
                x-show="isOpen" 
                class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50"
                x-cloak
            >
                <!-- Modal Content -->
                <div class="bg-white rounded-lg shadow-lg max-w-lg w-full p-6">
                    <div class="flex justify-between items-center border-b pb-3">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Product Details
                        </h3>
                        <button @click="isOpen = false" wire:click="closeModal" class="text-2xl text-gray-500 hover:text-gray-700">
                            &times;
                        </button>
                    </div>
        
                    <!-- Modal Body -->
                    <div class="mt-4">
                        @if($product)
                            <img 
                                src="{{ asset('storage/' . $product->image) }}" 
                                alt="Product Image" 
                                class="w-48 h-48 object-cover mx-auto mb-4"
                            >
                            <p><strong>Name:</strong> {{ $product->name }}</p>
                            <p><strong>Code:</strong> {{ $product->code }}</p>
                            <p><strong>Stock:</strong> {{ $product->stocks }}</p>
                        @else
                            <p class="text-gray-500">No product details available.</p>
                        @endif
                    </div>
        
                    <!-- Modal Footer -->
                    <div class="flex justify-end mt-4">
                        <button 
                            @click="isOpen = false" 
                            wire:click="closeModal" 
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600"
                        >
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
</div>
