<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class CartManagement
{
    public static function addItemToCart($product_id)
    {
        $cart_items = self::getCartItemsFromSession();

        $product = Product::where('id', $product_id)->first(['id', 'name', 'image', 'stocks', 'code']);

        if ($product) {
            $cart_items[] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'image' => $product->image,
            ];
        }
        self::addCartItemsToSession($cart_items);
        return count($cart_items);    
    }

    public static function removeCartItem($product_id)
    {
        $cart_items = self::getCartItemsFromSession();
        foreach ($cart_items as $key => $item) {
            if (isset($item['product_id']) && $item['product_id'] == $product_id) {
                unset($cart_items[$key]);
            }
        }
        self::addCartItemsToSession($cart_items);

        return $cart_items;
    }

    public static function addCartItemsToSession($cart_items)
    {
        Session::put('cart_items', $cart_items);
    }

    public static function clearCartItems()
    {
        Session::forget('cart_items');
    }

    public static function getCartItemsFromSession()
    {
        return Session::get('cart_items', []); // Default to empty array if no session value
    }
}
