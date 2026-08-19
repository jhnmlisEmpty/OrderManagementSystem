<?php

namespace App\Livewire\Products;

use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\WithPagination;

class TableProduct extends Component
{
    use LivewireAlert;
    use WithPagination;
    
    public $search = '';  
    public $product;
    public $assignedProductIds;
    public $user;

    protected $listeners = [
        'confirmDelete',
        'cancelDelete',
    ];

    public function mount()
    {
        $this->user = auth()->user();
    }
    
    public function deleteProduct($id)
    {
        $this->alert('question', 'Are you sure you want to delete this product?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'YES',
            'onConfirmed' => 'confirmDelete',
            'showDenyButton' => true,
            'denyButtonText' => 'NO',
            'onDenied' => 'cancelDelete',
            'data' => [
                    'id' => $id
                ]
        ]);
    }

    public function confirmDelete($data)
    { 
        $id = $data['id'];

        $product = Product::findOrFail($id);

        if ($product->image && \Storage::disk('public')->exists($product->image)) {
            \Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        $this->alert('success', 'Product deleted successfully!');

        $this->resetPage();
    }

    public function cancelDelete()
    {
        $this->alert('info', 'Product deletion canceled.');
    }

    public function render()
    {
         $user = Auth::user();
        if ($this->user->role === 'affiliate') {
                $products = $user->products()
                    ->where(function ($query) {
                        $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%');
                    })
                    ->orderBy('stocks', 'desc')
                    ->get();
        } else {
            $products = Product::where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('stocks', 'asc')
            ->get();
        }

        return view('livewire.products.table-product', [
            'products' => $products,
        ]);
    }
}