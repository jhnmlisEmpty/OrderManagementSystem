<?php

namespace App\Livewire\Order;

use App\Helpers\CartManagement;
use App\Models\Product;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class CreateOrder extends Component
{
    use LivewireAlert;

    public $perPage = 10;  

    public $search = '';
    
    public $cartCount;

    public function boot()
    {
        $this->cartCount = count(CartManagement::getCartItemsFromSession());
    }

    public function addToCart($id)
    {
        $total_count = CartManagement::addItemToCart($id);

        $this->alert('success', 'Added to Cart Successfully!', [
            'position' => 'top-right',
            'timer' => 1000,
            'toast' => true,
        ]);

        $this->cartCount = $total_count;
    }

    public function render()
    {
        $products = Product::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('code', 'like', '%' . $this->search . '%');
        })
        ->where('stocks', '>', 0) 
        ->paginate($this->perPage);

        return view('livewire.order.create-order', [
            'products' => $products,
        ]);
    }
}
