<div class="container mx-auto mt-5  max-w-full">
    <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 mt-4">
                Create Orders
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
                @if ($cartCount > 0)
                    <a href="{{ auth()->user()->role === 'production' ? route('create.orderPlatform') : route('cart.order') }}" 
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Go to Cart
                        <span class="ml-2 bg-red-600 text-white font-bold rounded-full text-xs px-2 py-1">
                            {{ $cartCount }}
                        </span>
                    </a>
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                        <a href="{{ route('create.orderPlatform') }}" 
                        class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                            Create Order for Platform
                            <span class="ml-2 bg-red-600 text-white font-bold rounded-full text-xs px-2 py-1">
                                {{ $cartCount }}
                            </span>
                        </a>
                    @endif
                @else
                    <button class="text-white bg-blue-700 cursor-not-allowed opacity-50 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800" disabled>
                        Go to Cart
                        <span class="ml-2 bg-red-600 text-white font-bold rounded-full text-xs px-2 py-1">
                            {{ $cartCount }}
                        </span>
                    </button>
                    @if (auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                        <a href="{{ route('create.orderPlatform') }}" 
                        class="text-white bg-green-700 cursor-not-allowed opacity-50 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800" disabled>
                            Create Order for Platform
                            <span class="ml-2 bg-red-600 text-white font-bold rounded-full text-xs px-2 py-1">
                                {{ $cartCount }}
                            </span>
                        </a>
                    @endif
                @endif
            </div>

        </div>
    </div>
    @if($products->isEmpty())
        <p colspan="5" class="p-4 mt-6 text-center text-gray-500">
            No products found matching your search criteria.
        </p>
    @else
        <div class="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
            @foreach ($products as $product)
                <div class="overflow-y-hidden mt-4 max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700 flex">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="Image" class="w-24 h-24 object-cover rounded-lg mr-4">
                    <div>
                        <a href="#">
                            <h5 class="mb-2 text-md font-bold tracking-tight text-gray-900 dark:text-white">{{ $product->name }}</h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Stocks: {{ $product->stocks }}</p>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Code: {{ $product->code }}</p>
                        <a wire:click.prevent='addToCart({{$product->id}})' href="#" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                            Add to Cart
                            <svg class="rtl:rotate-180 w-3.5 h-3.5 ms-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 5h12m0 0L9 1m4 4L9 9"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>