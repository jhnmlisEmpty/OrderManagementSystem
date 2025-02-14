<?php
namespace App\Livewire\Employee;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AssignProducts extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $user;
    public $selectedProducts = [];
    public $search = '';
    public $selectAll = false;
    public $price = [];

    public function mount(User $user)
    {
        $this->user = $user;
        $this->selectedProducts = $this->user->products->pluck('id')->toArray();
        $this->price = $this->user->products->pluck('pivot.price', 'id')->toArray();
    }

    public function unassignProduct($productId)
    {
        if (($key = array_search($productId, $this->selectedProducts)) !== false) {
            unset($this->selectedProducts[$key]);
        }
        $this->user->products()->sync($this->selectedProducts);
        $this->selectedProducts = $this->user->products->pluck('id')->toArray();
        $this->alert('info', 'Product unassigned successfully!');
    }

    public function assignProduct($productId)
    {
        if (!in_array($productId, $this->selectedProducts)) {
            $this->selectedProducts[] = $productId;
        }
        $this->user->products()->attach($productId);
        $this->selectedProducts = $this->user->products->pluck('id')->toArray(); // Update the selectedProducts array
        $this->alert('success', 'Product assigned successfully!');
    }

    public function updatePrice($productId)
    {
        $price = $this->price[$productId] ?? 0;
        Product::where('id', $productId)->update(['price' => $price]);
        $this->alert('success', 'Price updated successfully!');
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $assignedProductIds = $this->user->products->pluck('id')->toArray();
        $products = Product::whereNotIn('id', $assignedProductIds)
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->get();
            
        $assignedProducts = $this->user->products()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->get();

        return view('livewire.employee.assign-products', [
            'products' => $products,
            'assignedProducts' => $assignedProducts,
        ]);
    }
}