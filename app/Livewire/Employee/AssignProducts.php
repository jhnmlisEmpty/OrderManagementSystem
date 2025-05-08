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
   
    public $search = '';
   
    public $price = [];

    public function mount(User $user)
    {
        $this->user = $user;
        
    }

    public function assignProduct($productId)
    {
        if ($this->user->products()->where('product_id', $productId)->exists()) {
            $this->alert('warning', 'Product is already assigned!');
            return;
        }

        // $this->user->products()->attach($productId);
        // $this->alert('success', 'Product assigned successfully!');

        $price = $this->price[$productId] ?? 0; // Default price if not provided
        $this->user->products()->attach($productId, ['price' => $price]);
    
        $this->alert('success', 'Product assigned successfully with price!');
    }

    public function unassignProduct($productId)
    {
        $this->user->products()->detach($productId);
        $this->alert('success', 'Product unassigned successfully!');
    }

    public function updatePrice($productId)
    {
        // $price = $this->price[$productId] ?? 0;
        // Product::where('id', $productId)->update(['price' => $price]);
        // $this->alert('success', 'Price updated successfully!');


        $price = $this->price[$productId] ?? 0;
        // Update the price in the pivot table
        $this->user->products()->updateExistingPivot($productId, ['price' => $price]);
    
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