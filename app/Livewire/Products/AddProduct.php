<?php

namespace App\Livewire\Products;

use Jantinnerezo\LivewireAlert\LivewireAlert;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddProduct extends Component
{

    use WithFileUploads;
    use LivewireAlert;

    public $name;
    public $code;
    public $image;
    public $stocks;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:255|unique:products,code',
        'image' => 'required|image|max:1024',
        'stocks' => 'required|integer|min:0',
    ];

    public function addProduct()
    {
        
        $this->validate();

        $imagePath = $this->image->storeAs('products', 'public');

        Product::create([
            'name' => $this->name,
            'code' => $this->code,
            'image' => $imagePath,
            'stocks' => $this->stocks,
        ]);

        $this->reset();

        $this->alert('success', 'New Product Added Successfully', [
            'position' => 'top-right',
            'timer' => 3000,
        ]);
        
        return redirect()->route('table.product');
    }

    public function render()
    {
        return view('livewire.products.add-product');
    }
}
