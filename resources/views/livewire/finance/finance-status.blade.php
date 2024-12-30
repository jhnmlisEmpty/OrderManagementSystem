<div class="container mx-auto mt-5" >
    <div class="bg-white shadow overflow-hidden sm:rounded-lg">
        <div class="flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
            <div>
                <h3 class="text-3xl font-bold text-gray-900 mt-4">
                    Finance Details
                </h3>
            </div>
        </div>
        <div class="px-4 py-5 sm:px-6">
            <p class="mt-1 max-w-2xl text-lg text-gray-500">Agent: {{ $user->name }}</p>
            <p class="mt-1 max-w-2xl text-sm text-gray-500">Role: {{ $user->role }}</p>
        </div>
    
        <div class="grid grid-cols-2 gap-4 mb-6 p-2">
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="startDate" wire:model.live="startDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
    
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="endDate" wire:model.live="endDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
    
            <div>
                <label for="incentivePercentage" class="block text-sm font-medium text-gray-700">Incentive Percentage (%)</label>
                <input type="number" id="incentivePercentage" wire:model.live="incentivePercentage" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    
        <div class="grid grid-cols-2 gap-4 p-2">
            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm font-medium">Shipped Orders</h3>
                <p class="text-lg font-semibold">{{ $shippedCount }}</p>
                <h3 class="text-sm font-medium">Total Amount</h3>
                <p class="text-lg font-semibold">&#36;{{ number_format($shippedAmount, 2) }}</p>
            </div>
    
            <div class="p-4 bg-gray-100 rounded-lg">
                <h3 class="text-sm font-medium">Delivered Orders</h3>
                <p class="text-lg font-semibold">{{ $deliveredCount }}</p>
                <h3 class="text-sm font-medium">Total Amount</h3>
                <p class="text-lg font-semibold">&#36;{{ number_format($deliveredAmount, 2) }}</p>
            </div>
    
            <div class="p-4 bg-gray-100 rounded-lg col-span-2">
                <h3 class="text-sm font-medium">Total Incentive</h3>
                <p class="text-lg font-semibold text-green-500">&#36;{{ number_format($totalIncentive, 2) }}</p>
            </div>
        </div>
    </div>
</div>
