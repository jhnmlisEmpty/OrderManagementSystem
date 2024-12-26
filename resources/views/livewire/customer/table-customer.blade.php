<div class="container mx-auto mt-5"> 
    <div>
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Customers
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
                            placeholder="Search for customers" 
                            wire:model.live="search"
                        />
                    </div>                
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-y-auto max-h-full shadow-lg mt-4 rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Assign Agent
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Phone Number
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Landmark
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Street
                    </th>
                    <th scope="col" class="px-6 py-3">
                        City
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Province
                    </th>
                </tr>
            </thead>
    
            <tbody>
                <!-- Display No Customers Message if Empty -->
                @if($customers->isEmpty())
                    <tr>
                        <td colspan="6" class="p-4 text-center text-gray-500">
                            No customers found matching your search criteria.
                        </td>
                    </tr>
                @else
                    @foreach ($customers as $customer)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                            <td class="px-6 py-4">
                                {{ $customer->user->name }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $customer->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $customer->contact_number }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $customer->landmark }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $customer->street }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $customer->city }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $customer->province }}
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Pagination Links -->
    <div class="mt-4 px-4">
        {{ $customers->links('pagination::tailwind') }} 
    </div>
</div>
