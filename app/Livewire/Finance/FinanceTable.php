<?php

namespace App\Livewire\Finance;

use App\Models\User;
use Livewire\Component;

class FinanceTable extends Component
{
    public $osrUsers;
    public $affiliateUsers;
    public $search = '';

    public function mount()
    {
        $this->updateUsers();
    }

    public function updatedSearch()
    {
        $this->updateUsers();
    }

    private function updateUsers()
    {
        $searchTerm = '%' . $this->search . '%';
        $this->osrUsers = User::where('role', 'osr')
            ->where('name', 'like', $searchTerm)
            ->get();

        $this->affiliateUsers = User::where('role', 'affiliate')
            ->where('name', 'like', $searchTerm)
            ->get();
    }

    public function render()
    {
        return view('livewire.finance.finance-table');
    }
}
