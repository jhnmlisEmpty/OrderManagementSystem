<?php

namespace App\Livewire\Employee;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;
use Livewire\Component;

class AddEmployee extends Component
{
    use WithFileUploads;

    public $name;
    public $email;
    public $password;
    public $role;
    public $image;
    public $password_confirmation;

    public $roles = [
        'admin' => 'Admin',
        'osr' => 'OSR',
        'logistics' => 'Logistics',
        'production' => 'Production',
        'affiliate' => 'Affiliate',
    ];

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string',
            'image' => 'required|image|max:2048',
        ]);

        $profilePicturePath = $this->image->store('profiles', 'public');

        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->password = bcrypt($this->password);
        $user->role = $this->role;
        $user->image = $profilePicturePath;
        $user->save();
        $this->reset();

        return redirect()->route('table.employee');
        
    }

    public function render()
    {
        return view('livewire.employee.add-employee');
    }
}
