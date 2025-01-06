<!-- filepath: /C:/Users/Hi/Documents/School Files/OrderManagementSystem/resources/views/livewire/packaging/packaging-manager.blade.php -->
<div class="container mx-auto mt-5  max-w-full">
    <div>
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Manage Packaging
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
                            placeholder="Search for packaging" 
                            wire:model.live="search"
                        />
                    </div>  
                </div>
            </div>
        </div>
    </div>

    <!-- Form for Adding/Updating Packaging -->
    <div class="relative overflow-x-auto shadow-lg sm:rounded-lg p-4 mb-4">
        <form wire:submit.prevent="saveOrderWithItems" class="flex flex-wrap space-x-4 justify-center">
            <div class="mb-4 flex-1">
                <label for="name" class="block font-medium">Name</label>
                <input type="text" id="name" wire:model="name" class="border rounded w-full p-2">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4 flex-1">
                <label for="quantity" class="block font-medium">Quantity</label>
                <input type="number" id="quantity" wire:model="quantity" class="border rounded w-full p-2">
                @error('quantity') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4 mt-2 flex items-end justify-center w-full">
                <button type="submit" class="w-64 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    {{ $packagingId ? 'Update' : 'Save' }}
                </button>
                <button type="button" wire:click="resetInputFields" class="w-64 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-700 focus:outline-none dark:focus:ring-gray-800">
                    Reset
                </button>
            </div>
            
        </form>
    </div>

    <!-- Table -->
    <div class="shadow-lg mt-4 rounded-lg overflow-y-auto overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 overflow-x-auto">
            <thead class="text-xs text-center text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 sticky top-0 z-10">
                <tr>
                    <th scope="col" class="px-6 py-3">
                        Name
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Quantity
                    </th>
                    <th scope="col" class="px-6 py-3">
                        Actions
                    </th>
                </tr>
            </thead>

            <tbody>
                @if($packagings->isEmpty())
                    <tr>
                        <td colspan="3" class="p-4 text-center text-gray-500">
                            No packaging found matching your search criteria.
                        </td>
                    </tr>
                @else
                    @foreach ($packagings as $packaging)
                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 text-center">
                            <td class="w-1/3 p-4">
                                {{ $packaging->name }}
                            </td>
                            <td class="w-1/3 p-4">
                                {{ $packaging->quantity }}
                            </td>
                            <td class="px-6 py-4">
                                <button wire:click="edit({{ $packaging->id }})" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                    Edit
                                </button>
                                <button wire:click="delete({{ $packaging->id }})" class="font-medium text-red-600 dark:text-red-500 hover:underline">
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
        {{ $packagings->links() }} 
    </div>
</div>