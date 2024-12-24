<!-- resources/views/livewire/employee/add-employee.blade.php -->

<div class="container mx-auto mt-4 p-4 bg-white rounded-lg shadow-lg">
    <h2 class="text-2xl font-semibold mb-4">Add New User</h2>

    <!-- Success Message -->
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-600 bg-green-100 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- User Form -->
    <form wire:submit.prevent="store">
        <!-- Name -->
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" wire:model="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            @error('name') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" wire:model="email" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            @error('email') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" wire:model="password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
            @error('password') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" id="password_confirmation" wire:model="password_confirmation" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
        </div>

        <!-- Role Dropdown -->
        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
            <select id="role" wire:model="role" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                <option value="">Select Role</option>
                @foreach($roles as $key => $value)
                    <option value="{{ $key }}">{{ $value }}</option>
                @endforeach
            </select>
            @error('role') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror
        </div>

        <!-- Profile Picture -->
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700">Profile Picture</label>
            <input type="file" id="image" wire:model="image" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            @error('image') <span class="text-red-600 text-xs">{{ $message }}</span> @enderror

            @if ($image)
                <!-- Live Preview -->
                <div class="mt-2">
                    <p class="text-sm text-gray-600">Preview:</p>
                    <img src="{{ $image->temporaryUrl() }}" alt="Profile Picture Preview" class="mt-2 rounded-lg shadow-md w-24 h-24">
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="mb-4">
            <button type="submit" class="w-full bg-blue-600 text-white font-medium py-2 px-4 rounded-lg hover:bg-blue-700">
                Add User
            </button>
        </div>
    </form>
</div>
