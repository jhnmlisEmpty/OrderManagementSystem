<?php

namespace App\Livewire\Platform;

use App\Models\Product;
use Livewire\Component;
use App\Models\Packaging;
use App\Models\PlatformOrder;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\DB;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class OrderPlatform extends Component
{
    use LivewireAlert;
    
    public $platform;
    public $page;
    public $cartItems = [];
    public $packagings = [];

    protected $rules = [
        'platform' => 'required|string|max:255',
        'page' => 'required|string|max:255',
        'cartItems.*.price' => 'required|numeric|min:0',
        'cartItems.*.quantity' => 'required|integer|min:1',
        'cartItems.*.packaging_id' => 'required|exists:packagings,id',
    ];
    
    protected $listeners = [
        'confirmOrder',
        'cancelOrder'
    ];

    public function mount()
    {

        $this->packagings = Packaging::all();
        $this->cartItems = CartManagement::getCartItemsFromSession();

        foreach ($this->cartItems as &$cartItem) {
            $cartItem['price'] = $cartItem['price'] ?? 0; 
            $cartItem['quantity'] = $cartItem['quantity'] ?? 1; 
            $cartItem['total_amount'] = $cartItem['total_amount'] ?? 0;
            $cartItem['packaging_id'] = $cartItem['packaging_id'] ?? null;

            $product = Product::find($cartItem['product_id']);
            $packaging = Packaging::find($cartItem['packaging_id']);

            if ($product) {
                $cartItem['product_name'] = $product->name;
                $cartItem['product_image'] = $product->image;
                $cartItem['product_stock'] = $product->stocks;
            } else {
                $cartItem['product_name'] = 'Product not found';
                $cartItem['product_image'] = null;
                $cartItem['product_stock'] = 0;
            }

            if ($packaging) {
                $cartItem['packaging_name'] = $packaging->name;
            } else {
                $cartItem['packaging_name'] = 'Packaging not found';
            }
        }
    }
    
    public function saveOrderWithItems()
    {
        $this->validate();
        
        $this->alert('question', 'Are you sure you want to process this order?', [
            'showConfirmButton' => true,
            'confirmButtonText' => 'YES',
            'onConfirmed' => 'confirmOrder',
            'showDenyButton' => true,
            'denyButtonText' => 'NO',
            'onDenied' => 'cancelOrder',
        ]);
       
    }


    public function confirmOrder()
    {
        $this->validate();

        $totalFromItems = 0;
        $full_amount = 0;
        $productQuantities = [];

        foreach ($this->cartItems as $index => $cartItem) {
            $price = (float) $cartItem['price'];
            $quantity = (int) $cartItem['quantity'];
            $productId = $cartItem['product_id'];
            $packagingId = $cartItem['packaging_id'];
        
            if (!isset($productQuantities[$productId])) {
            $productQuantities[$productId] = 0;
            }
            $productQuantities[$productId] += $quantity;
        
            $product = Product::find($productId);
            $packaging = Packaging::find($packagingId);
        
            if (!$product) {
            $this->addError('cartItems.' . $index . '.product_id', 'Product not found.');
            return;
            }

            if (!$packaging) {
            $this->addError('cartItems.' . $index . '.packaging_id', 'Packaging not found.');
            return;
            }

            if ($product->stocks < $quantity) {
            $this->addError('cartItems.' . $index . '.quantity', 'Not enough stock for product: ' . $product->name);
            return;
            }

            if ($packaging->quantity < $quantity) {
            $this->addError('cartItems.' . $index . '.quantity', 'Not enough stock for packaging: ' . $packaging->name);
            return;
            }
        
            $totalFromItems += $price * $quantity;
        }

       
        DB::transaction(function () use ($totalFromItems) {

            $full_amount = $totalFromItems;

            $platformOrder = PlatformOrder::create([
            'platform' => $this->platform,
            'page' => $this->page,
            'full_amount' => $full_amount,
            ]);

            foreach ($this->cartItems as $cartItem) {
            $price = (float) $cartItem['price'];
            $quantity = (int) $cartItem['quantity'];
            $totalAmount = $price * $quantity;
            
            $product = Product::find($cartItem['product_id']);
            $packaging = Packaging::find($cartItem['packaging_id']);
            
            $platformOrder->platformOrderItems()->create([
                'platform_order_id' => $platformOrder->id,
                'product_id' => $cartItem['product_id'],
                'packaging_id' => $cartItem['packaging_id'],
                'price' => $price,
                'quantity' => $quantity,
                'total_amount' => $totalAmount,
            ]);

            // Reduce product stocks
            $product->stocks -= $quantity;
            $product->save();

            // Reduce packaging quantity
            $packaging->quantity -= $quantity;
            $packaging->save();
            }

        });

        $this->reset([
            'platform',
            'page'
        ]);

        CartManagement::clearCartItems();

        return redirect()->route('platform.table');
    }

    public function cancelOrder()
    {
        $this->alert('info', 'Order canceled.');
    }

    public function removeItem($index)
    {
        unset($this->cartItems[$index]);

        $this->cartItems = array_values($this->cartItems);

        $this->alert('info', 'Order Item Removed.');

        if (count($this->cartItems) === 0) {
            session()->forget('cart_items'); // Clear cart session if empty
            return redirect()->route('create.order'); // Redirect to the desired route
        }
        $serializableCart = $this->cartItems;
        session()->put('cart_items', $serializableCart);

    }
    
    public function render()
    {
        return view('livewire.platform.order-platform');
    }
}
