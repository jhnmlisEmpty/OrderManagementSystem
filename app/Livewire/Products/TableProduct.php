<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class TableProduct extends Component
{
    use WithPagination;
    use LivewireAlert;
    

    public $perPage = 10;  
    public $search = '';  
    public $isModalOpen = false;
    public $product;

    protected $listeners = [
        'confirmDelete',
        'cancelDelete'
    ];
    
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

    public function openModal($productId)
    {
        $this->product = Product::find($productId);
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->product = null;
    }

    public function render()
    {
        $products = Product::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
        })
        ->orderBy('stocks','asc')
        ->paginate($this->perPage);

        return view('livewire.products.table-product', [
            'products' => $products,
        ]);
    }

}
