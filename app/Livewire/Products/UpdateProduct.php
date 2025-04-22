<?php

namespace App\Livewire\Products;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class UpdateProduct extends Component
{

    use WithFileUploads;
    use LivewireAlert;

    public $productId;
    public $name;
    public $code;
    public $image;
    public $newImage;
    public $stocks;
    public $category;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255|unique:products,code,{id}', // Placeholder for dynamic validation
        'newImage' => 'nullable|image|max:1024',
        'stocks' => 'required|integer|min:0',
        'category' => 'required',
    ];

    protected $listeners = [
        'confirmUpdate',
        'cancelUpdate'
    ];

    public function mount($id)
    {
        $product = Product::findOrFail($id);

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->code = $product->code;
        $this->image = $product->image;
        $this->stocks = $product->stocks;
        $this->category = $product->category;
    }

    public function updateProduct()
    {
        $this->alert('question', 'Are you sure you want to update this product?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'YES',
            'onConfirmed' => 'confirmUpdate',
            'showDenyButton' => true,
            'denyButtonText' => 'NO',
            'onDenied' => 'cancelUpdate',
            'timer' => 5000,
        ]);
       
    }

    public function confirmUpdate()
    {
        $this->validate([
            'code' => 'required|string|max:255|unique:products,code,' . $this->productId,
        ]);

        $product = Product::find($this->productId);

        if ($this->newImage) {
            $imagePath = $this->newImage->store('products', 'public');
            $product->update(['image' => $imagePath]);
        }

        $product->update([
            'name' => $this->name,
            'code' => $this->code,
            'stocks' => $this->stocks,
            'image' => $product->image,
            'category' => $this->category,
        ]);

        $this->alert('success', 'New Product Added Successfully', [
            'position' => 'top-right',
            'timer' => 5000,
        ]);

        return redirect()->route('table.product');
    }

    public function cancelUpdate()
    {
        $this->alert('info', 'Product update canceled.');
    }

    public function render()
    {
        return view('livewire.products.update-product');
    }
}
