{{-- filepath: c:\Users\Hi\Documents\School Files\OrderManagementSystem\resources\views\livewire\attendance\attendance.blade.php --}}
<div class="container mx-auto mt-5 max-w-full shadow-md rounded-lg p-6">
    <div class="mb-10 flex flex-column sm:flex-row flex-wrap space-y-2 sm:space-y-0 items-center justify-between pl-4">
        <div>
            <h3 class="text-3xl font-bold text-gray-900 mt-2">
                Attendance Records
            </h3>
        </div>

        <div class="relative overflow-x-auto sm:rounded-lg p-4">
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

                @if($isAdmin)
                    <div class="mt-10 ml-5">
                        <button 
                            wire:click.debounce.500ms="deleteAttendance" 
                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800">
                            Delete Attendance
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Filter Attendance Records --}}
    @if($isAdmin)
        <div class="mb-4 grid grid-cols-2 gap-4">
            <div>
                <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" id="startDate" wire:model.live="startDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" id="endDate" wire:model.live="endDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
        </div>
    @endif

    @if(!$isAdmin)
        <div class="mb-10 mt-10 grid grid-cols-2 gap-4">
            <div class="flex justify-center">
                <button 
                    wire:click.debounce.500ms="timeIn" 
                    class="px-24 py-5 bg-blue-500 text-white rounded-md disabled:opacity-50" 
                    @if (!$canTimeIn) disabled @endif>
                    Time In
                </button>
            </div>
            <div class="flex justify-center">
                <button 
                    wire:click.debounce.500ms="timeOut({{ $attendanceRecords->last()->id ?? 'null' }})" 
                    class="px-24 py-5 bg-red-500 text-white rounded-md disabled:opacity-50" 
                    @if (!$canTimeOut) disabled @endif>
                    Time Out
                </button>
            </div>
        </div>
    @endif
    {{-- Attendance Table --}}
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @if ($isAdmin)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Stamp</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($attendanceRecords as $record)
                    <tr>
                        @if ($isAdmin)
                            <td class="px-6 py-4 whitespace-nowrap">{{ $record->user->name ?? 'N/A' }}</td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">{{ $record->date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($record->date)->format('l') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($isAdmin)
                                <input type="time" wire:change="updateAttendance({{ $record->id }}, 'time_in', $event.target.value)" value="{{ $record->time_in }}">
                            @else
                                {{ \Carbon\Carbon::parse($record->time_in)->format('h:i A') ?? 'N/A' }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($isAdmin)
                                <input type="time" wire:change="updateAttendance({{ $record->id }}, 'time_out', $event.target.value)" value="{{ $record->time_out }}">
                            @else
                                {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('h:i A') : 'N/A' }}
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if ($record->seconds_elapsed !== null)
                                @php
                                    $hours = floor($record->seconds_elapsed / 3600);
                                    $minutes = floor(($record->seconds_elapsed % 3600) / 60);
                                    $seconds = $record->seconds_elapsed % 60;
                                @endphp
                                {{ $hours }} hours {{ $minutes }} minutes {{ $seconds }} seconds
                            @else
                                N/A
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>