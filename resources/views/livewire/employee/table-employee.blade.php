<div class="container mx-auto mt-5  max-w-full"> 
    <div>
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Users
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
                            placeholder="Search for users" 
                            wire:model.live="search"
                        />
                    </div>                
                </div>
    
                <div>
                    <a href="{{ route('add.employee') }}" 
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Add New User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-8 shadow-md p-2 m-2 rounded-lg overflow-y-auto">
        <div class="mt-2">
            <h2 class="text-xl font-bold text-black mb-4">OSR Users</h2>
        </div>
        @if ($osrUsers->isEmpty())
            <p class="text-gray-500 text-center text-sm mt-4">No OSR users found.</p>
        @else
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-center text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($osrUsers as $user)
                    <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-500 uppercase dark:text-gray-400">{{ $user->role }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'affiliate')
                                <a href="{{ route('assign.products', $user->id) }}" class="text-blue-500 underline">Assign Products</a>
                            @endif
                            <a href="{{ route('edit.employee', $user->id) }}" class="text-green-500 underline ml-2">Edit</a>
                            <button wire:click="deleteUser({{ $user->id }})" class="text-red-500 underline ml-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
    <!-- Affiliate Users Section -->
    <div class="mt-8 shadow-md p-2 m-2 rounded-lg overflow-y-auto">
        <div class="mt-2">
            <h2 class="text-xl font-bold text-black mb-4">Affiliate Users</h2>
        </div>
        @if ($affiliateUsers->isEmpty())
            <p class="text-gray-500 text-center text-sm mt-4">No Affiliate users found.</p>
        @else
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-center text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($affiliateUsers as $user)
                    <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-500 uppercase dark:text-gray-400">{{ $user->role }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'affiliate')
                                <a href="{{ route('assign.products', $user->id) }}" class="text-blue-500 underline">Assign Products</a>
                            @endif
                            <a href="{{ route('edit.employee', $user->id) }}" class="text-green-500 underline ml-2">Edit</a>
                            <button wire:click="deleteUser({{ $user->id }})" class="text-red-500 underline ml-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    
    <!-- Other Users Section -->
    <div class="mt-8 shadow-md p-2 m-2 rounded-lg overflow-y-auto">
        <div class="mt-2">
            <h2 class="text-xl font-bold text-black mb-4">Other Users</h2>
        </div>
        @if ($otherUsers->isEmpty())
            <p class="text-gray-500 text-center text-sm mt-4">No Other users found.</p>
        @else
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-center text-gray-700 uppercase bg-blue-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Name</th>
                        <th scope="col" class="px-6 py-3">Email</th>
                        <th scope="col" class="px-6 py-3">Role</th>
                        <th scope="col" class="px-6 py-3">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($otherUsers as $user)
                    <tr class="text-center bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $user->name }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $user->email }}</td>
                        <td class="px-6 py-4 text-gray-500 uppercase dark:text-gray-400">{{ $user->role }}</td>
                        <td class="px-6 py-4">
                            @if($user->role === 'affiliate')
                                <a href="{{ route('assign.products', $user->id) }}" class="text-blue-500 underline">Assign Products</a>
                            @endif
                            <a href="{{ route('edit.employee', $user->id) }}" class="text-green-500 underline ml-2">Edit</a>
                            <button wire:click="deleteUser({{ $user->id }})" class="text-red-500 underline ml-2">
                                Delete
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
