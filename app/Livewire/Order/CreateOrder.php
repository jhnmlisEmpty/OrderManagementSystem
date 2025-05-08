<?php

namespace App\Livewire\Order;

use App\Models\User;
use App\Models\Product;
use Livewire\Component;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\Auth;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class CreateOrder extends Component
{
    use LivewireAlert;

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
        $user = Auth::user();

        if ($user->role === 'affiliate') {
            // Retrieve products assigned to the user with the pivot price
            $products = $user->products()
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('code', 'like', '%' . $this->search . '%');
                })
                ->orderBy('stocks', 'desc')
                ->get();
        } else {
            // Retrieve all products for admin or other roles
            $products = Product::where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->orderBy('stocks', 'desc')
            ->get();
        }

        return view('livewire.order.create-order', [
            'products' => $products,
        ]);
    }
}
