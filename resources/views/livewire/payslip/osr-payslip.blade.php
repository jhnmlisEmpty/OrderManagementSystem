<div>
    <div class="mt-8">
        <h2 class="text-3xl font-bold text-black mb-4">OSR PAYSLIP</h2>
    </div>

    <div class="mb-4 grid grid-cols-2 gap-4">
        <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" id="startDate" wire:model.live="startDate" @if(auth()->user()->role === 'osr') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <label for="endDate" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" id="endDate" wire:model.live="endDate" @if(auth()->user()->role === 'osr') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Day</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Stamp</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($attendanceRecords as $record)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $record->user->name ?? 'N/A' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ $record->date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($record->date)->format('l') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $record->time_in ? \Carbon\Carbon::parse($record->time_in)->format('h:i A') : 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $record->time_out ? \Carbon\Carbon::parse($record->time_out)->format('h:i A') : 'N/A' }}
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
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No attendance records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-2 shadow-md bg-slate-200 p-4 rounded-lg">
        <div class="col-span-4 md:col-span-1">
            <label for="salaryRate" class="block text-sm font-medium text-gray-700">Salary Rate</label>
            <input type="number" id="salaryRate" wire:model.change="dailySalaryRate" @if(auth()->user()->role === 'osr') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter salary rate">
        </div>

        <div class=" col-span-4 md:col-span-1">
            <p class="block text-sm font-medium text-gray-700">Number of Time Present</p>
            <p class="text-xl font-bold text-blue-500">
                {{ $totalHours }} hrs {{ $totalMinutes }} mins {{ $totalSeconds }} secs
            </p>
        </div>

        <div class="col-span-4 md:col-span-1">
            <p class="block text-sm font-medium text-gray-700">Number of Days Present</p>
            <p class="text-xl font-bold text-blue-500">
                {{$presentDays}}
            </p>
        </div>
       
        <div class="col-span-4 md:col-span-1">
            <p class="block text-xl font-bold text-gray-700">Based Salary</p>
            <p class="text-3xl font-bold text-red-500">
               {{$totalSalary}}
            </p>
        </div>

        <div class="mt-8 col-span-4">
            <h2 class="text-3xl font-bold text-black mb-4">Deductions</h2>
        </div>

        <div class="col-span-4 md:col-span-1">
            <label for="sss" class="block text-sm font-medium text-gray-700">SSS</label>
            <input type="number" id="sss" wire:model.change="sss" @if(auth()->user()->role === 'osr') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter salary rate">
        </div>

        <div class="col-span-4 md:col-span-1">
            <label for="philhealth" class="block text-sm font-medium text-gray-700">Phil Health</label>
            <input type="number" id="philhealth" wire:model.change="philhealth" @if(auth()->user()->role === 'osr') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter salary rate">
        </div>

        <div class="col-span-4 md:col-span-1">
            <label for="pagibig" class="block text-sm font-medium text-gray-700">Pag-Ibig</label>
            <input type="number" id="pagibig" wire:model.change="pagibig" @if(auth()->user()->role === 'osr') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter salary rate">
        </div>

        <div class="col-span-4 md:col-span-1">
            <label for="otherDeduction" class="block text-sm font-medium text-gray-700">Other Deduction</label>
            <input type="number" id="otherDeduction" wire:model.change="otherDeduction"  @if(auth()->user()->role === 'osr') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter salary rate">
        </div>

        <div class="col-span-2 md:col-span-1">
            <label for="otherDeductionNotes" class="block text-sm font-medium text-gray-700">Notes in other deduction</label>
            <textarea id="otherDeductionNotes" wire:model.change="otherDeductionNotes"  @if(auth()->user()->role === 'osr') disabled @endif class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter notes for other deductions"></textarea>
        </div>

        <div class="col-span-4 md:col-span-1">
            <div class="">
                <h2 class="text-2xl font-bold text-black mb-4">Incentives</h2>
            </div>
    
            <div>
                
                <input type="number" id="incentives" wire:model.change="incentives" @if(auth()->user()->role === 'osr') disabled @endif  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter Incentives">
            </div>
        </div>

        <div class="mt-8 col-span-4">
            <h2 class="text-3xl font-bold text-black">PAYOUT DETAILS</h2>
        </div>

        <div class="shadow-md bg-slate-400 p-4 rounded-lg col-span-4 md:col-span-1">
            <p class="block text-xl font-bold text-gray-700">Net Payout</p>
            <p class="text-3xl font-bold text-yellow-500">
               {{ round($netPay, 2) }}
            </p>
        </div>

        <div class="mt-8 col-span-4">
           <div class="grid grid-cols-1 md:grid-cols-4 gap-2 ">
            <div>
                <label for="paymentMethod" class="block text-sm font-medium text-gray-700">Payment Method</label>
                <input type="text" id="paymentMethod" wire:model.change="paymentMethod" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter Payment Method" @if(auth()->user()->role === 'osr') disabled @endif>
            </div>

            <div>
                <label for="accountName" class="block text-sm font-medium text-gray-700">Account Name</label>
                <input type="text" id="accountName" wire:model.change="accountName" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter Account Name" @if(auth()->user()->role === 'osr') disabled @endif>
            </div>

            <div>
                <label for="accountNumber" class="block text-sm font-medium text-gray-700">Account Number</label>
                <input type="text" id="accountNumber" wire:model.change="accountNumber" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter Account Number" @if(auth()->user()->role === 'osr') disabled @endif>
            </div>

            <div>
                <label for="releaseDate" class="block text-sm font-medium text-gray-700">Release Date</label>
                <input type="date" id="releaseDate" wire:model.change="releaseDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Enter release date" @if(auth()->user()->role === 'osr') disabled @endif>
            </div>

            <div class="mt-2">
                <label for="qrCode" class="block text-sm font-medium text-gray-700">QR Code</label>
                <input type="file" id="qrCode" wire:model.change="qrCode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" @if(auth()->user()->role === 'osr') disabled @endif>
                @error('qrCode')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                <div class="mt-2">
                @if (is_string($qrCode))
                    <p class="text-sm text-gray-500">Preview:</p>
                    <img src="{{ asset('storage/' . $qrCode) }}" alt="QR Code Preview" class="w-32 h-32 object-cover rounded-md">
                @endif
                </div>
            </div>

            <div class="mt-2">
                <label for="paymentProof" class="block text-sm font-medium text-gray-700">Payment Proof</label>
                <input type="file" id="paymentProof" wire:model.change="paymentProof" class="mt-1 block w-full bg-blue border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" @if(auth()->user()->role === 'osr') disabled @endif>
                @error('paymentProof')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
                <div class="mt-2">
                @if (is_string($paymentProof))
                    <p class="text-sm text-gray-500">Preview:</p>
                    <img src="{{ asset('storage/' . $paymentProof) }}" alt="Payment Proof Preview" class="w-32 h-32 object-cover rounded-md">
                @endif
                </div>
            </div>

            <div class="col-span-2">
                <div class=" flex items-center space-x-4 mt-5">
                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'super_admin')
                        <button wire:click="markAsReleased" 
                            class="px-4 py-2 bg-green-500 text-white rounded-md shadow hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400"
                            wire:loading.attr="disabled" wire:target="qrCode, paymentProof" @if(auth()->user()->role === 'osr') disabled @endif>
                            Released
                        </button>
                        <button wire:click="markAsNotReleased" 
                            class="px-4 py-2 bg-red-500 text-white rounded-md shadow hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-400"
                            wire:loading.attr="disabled" wire:target="qrCode, paymentProof" @if(auth()->user()->role === 'osr') disabled @endif>
                            Not Released
                        </button>
                        <span wire:loading wire:target="qrCode, paymentProof" class="text-sm text-gray-500">
                            Uploading files, please wait...
                        </span>
                    @endif
                    <p class="text-sm text-gray-500">Status: 
                        <span class="text-bold text-3xl font-bold">
                            {{ $status === 'released' ? 'RELEASED' : 'NOT RELEASED' }}
                        </span>
                    </p>
                </div>

                @if (session()->has('message'))
                <div class="mt-2 text-green-500 font-bold">
                    {{ session('message') }}
                </div>
                @endif
            </div>

               
           </div>

        </div>


    </div>
    
</div>
