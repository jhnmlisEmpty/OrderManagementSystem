<?php

namespace App\Livewire\Order;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Packaging;
use App\Models\PaymentMethod;
use Livewire\Component;
use App\Helpers\CartManagement;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class Cart extends Component
{
    use WithFileUploads;
    use LivewireAlert;
    
    public $customer_name;
    public $contact_number;
    public $landmark;
    public $street;
    public $city;
    public $province;
    public $company;

    public $payment_method;
    public $total_amount;
    public $payment_status;

    public $shipping_fee;
    public $platform;
    public $tracking;
    public $status;
    public $date_received;
    public $note;
    public $full_amount;
    public  $totalFromItems;

    public $cartItems = [];
    public $packagings = [];

    protected $rules = [
        'customer_name' => 'required|string|max:255',
        'contact_number' => 'required|numeric|digits_between:10,15',
        'street' => 'required|string|max:255',
        'landmark' => 'nullable|string|max:255',
        'city' => 'required|string|max:255',
        'province' => 'required|string|max:255',
        'company' => 'nullable|string|max:255',
        

        'payment_method' => 'required|string|max:255',
        'total_amount' => 'required|numeric|min:0',
        'payment_status' => 'required|image|max:1024',

        'platform' => 'required|string|max:255',
        'shipping_fee' => 'nullable|numeric|min:0',
        'note' => 'nullable|string|max:1000',

        'cartItems.*.layout' => 'required|array',
        'cartItems.*.layout.*' => 'image|max:2048',
        'cartItems.*.price' => 'required|numeric|min:0',
        'cartItems.*.quantity' => 'required|integer|min:1',
        'cartItems.*.packaging_id' => 'required|exists:packagings,id',
    ];

    protected $casts = [
        'cartItems.*.layout' => 'array', // Ensure layout is cast to an array
    ];
    
    protected $excludedProperties = ['cartItems.*.layout']; // Exclude files from serialization

    protected $listeners = [
        'confirmOrder',
        'cancelOrder'
    ];

    public function mount()
    {
        $this->packagings = Packaging::all();
        $this->cartItems = CartManagement::getCartItemsFromSession();

        foreach ($this->cartItems as &$cartItem) {
            $cartItem['layout'] = []; 
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
    
            if ($productQuantities[$productId] > (int)$product->stocks) {
                $this->addError('cartItems.' . $index . '.quantity', 'Insufficient stock for product: ' . $product->name);
                return;
            }

            if ($quantity > (int)$packaging->quantity) {
                $this->addError('cartItems.' . $index . '.quantity', 'Insufficient packaging quantity for packaging: ' . $packaging->name);
                return;
            }
    
            $totalFromItems += $price * $quantity;
        }

        if ($this->total_amount > $totalFromItems) {
            $this->addError('total_amount', 'Total amount cannot be greater than the total of items.');
            return;
        }
       
        DB::transaction(function () use ($totalFromItems) {

            $full_amount = $totalFromItems - $this->total_amount + $this->shipping_fee;

            $customer = Customer::create([
                'user_id' => auth()->id(),
                'name' => $this->customer_name,
                'contact_number' => $this->contact_number,
                'landmark' => $this->landmark,
                'street' => $this->street,
                'city' => $this->city,
                'province' => $this->province,
                'company' => $this->company,
            ]);

            if ($this->payment_status) {
                $path = $this->payment_status->store('payment_statuses', 'public');
            }
    
            $payment = PaymentMethod::create([
                'method' => $this->payment_method,
                'amount' => $this->total_amount,
                'image' => $path ?? null,
            ]);


            $order = Order::create([
                'user_id' => auth()->id(),
                'customer_id' => $customer->id,
                'payment_method_id' => $payment->id,
                'platform' => $this->platform,
                'note' => $this->note,
                'shipping_price' => $this->shipping_fee,
                'total_from_items' => $totalFromItems,
                'full_amount' => $full_amount,
            ]);

            foreach ($this->cartItems as $cartItem) {
                $price = (float) $cartItem['price'];
                $quantity = (int) $cartItem['quantity'];
                $totalAmount = $price * $quantity;
            
                $product = Product::find($cartItem['product_id']);
                $packaging = Packaging::find($cartItem['packaging_id']);
            
                // Process uploaded files and store them
                $storedLayouts = [];
                if (isset($cartItem['layout']) && is_array($cartItem['layout'])) {
                    foreach ($cartItem['layout'] as $file) {
                        $storedLayouts[] = $file->store('order-item-layouts', 'public');
                    }
                }
            
                $order->orderItems()->create([
                    'product_id' => $cartItem['product_id'],
                    'packaging_id' => $cartItem['packaging_id'],
                    'price' => $price,
                    'quantity' => $quantity,
                    'total_amount' => $totalAmount,
                    'layout' => json_encode($storedLayouts),
                ]);
            
                // if ($product) {
                //     $product->stocks -= $quantity;
                //     $product->save();
                // }

                // if ($packaging) {
                //     $packaging->quantity -= $quantity;
                //     $packaging->save();
                // }
            }

        });

        $this->reset([
            'customer_name', 'contact_number', 'landmark', 'street', 'city', 'province',
            'payment_method', 'total_amount', 'payment_status', 'shipping_fee', 'platform',
            'tracking', 'status', 'date_received', 'note', 'cartItems'
        ]);

        CartManagement::clearCartItems();

        return redirect()->route('manage.orders');
    }

    public function cancelOrder()
    {
        $this->alert('info', 'Order canceled.');
    }

    public function removeItem($index)
    {
        unset($this->cartItems[$index]);
        $this->cartItems = array_values($this->cartItems);

        $serializableCart = collect($this->cartItems)->map(function ($cartItem) {
            unset($cartItem['layout']);
            return $cartItem;
        });

        session()->put('cart_items', $serializableCart);
    }

    public function render()
    {
        
        $cartItemsWithProducts = collect($this->cartItems)->map(function ($cartItem) {
            $cartItem['product'] = Product::find($cartItem['product_id']); 
            return $cartItem;
        });

        return view('livewire.order.cart', [
            'cartItems' => $cartItemsWithProducts,
            'packagings' => $this->packagings, 
        ]);
    }

}
