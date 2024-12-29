<?php

namespace App\Livewire\Employee;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Hash;

class EditEmployee extends Component
{
    use WithFileUploads;

    public $user;
    public $name;
    public $email;
    public $password;
    public $password_confirmation;
    public $role;
    public $image;
    public $currentImage;
    public $roles;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->currentImage = $user->image; // Assuming 'image' is the field name for the profile picture
        $this->roles = ['admin' => 'Admin', 'osr' => 'OSR', 'affiliate' => 'Affiliate', 'logistics' => 'Logistics', 'production' => 'Production'];
    }

    public function update()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string',
            'image' => 'nullable|image|max:1024', // 1MB Max
        ]);

        $this->user->name = $this->name;
        $this->user->email = $this->email;
        if ($this->password) {
            $this->user->password = Hash::make($this->password);
        }
        $this->user->role = $this->role;

        if ($this->image) {
            $this->user->image = $this->image->store('profile-pictures', 'public');
        }

        $this->user->save();

        session()->flash('message', 'User updated successfully.');

        return redirect()->route('table.employee'); // Adjust the route as needed
    }

    public function render()
    {
        return view('livewire.employee.edit-employee');
    }
}
