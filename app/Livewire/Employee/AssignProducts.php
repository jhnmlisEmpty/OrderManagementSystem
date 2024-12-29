<?php

namespace App\Livewire\Employee;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class AssignProducts extends Component
{
    use WithPagination;

    public $user;
    public $selectedProducts = [];
    public $search = '';
    public $selectAll = false;

    public function mount(User $user)
    {
        $this->user = $user;
        $this->selectedProducts = $user->products->pluck('id')->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function toggleSelectAll()
    {
        $this->selectAll = !$this->selectAll;
        if ($this->selectAll) {
            $this->selectedProducts = Product::where('name', 'like', '%' . $this->search . '%')
                ->orWhere('code', 'like', '%' . $this->search . '%')
                ->pluck('id')
                ->toArray();
        } else {
            $this->selectedProducts = [];
        }
    }

    public function assignProducts()
    {
        $this->user->products()->sync($this->selectedProducts);
        session()->flash('message', 'Products assigned successfully.');
        return redirect()->route('table.employee');
    }

    public function render()
    {
        $products = Product::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('code', 'like', '%' . $this->search . '%')
            ->paginate(10);

        return view('livewire.employee.assign-products', compact('products'));
    }
    
}
