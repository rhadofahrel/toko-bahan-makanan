<?php

namespace App\Models;

use Illuminate\Support\Facades\Session;

class Transaction
{
    private static $cartKey = 'shopping_cart';
    
    // Get cart from session
    public static function getCart()
    {
        return Session::get(self::$cartKey, []);
    }
    
    // Add item to cart
    public static function addItem($product, $quantity)
    {
        $cart = self::getCart();
        
        $cart[] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => (int) $quantity,
            'subtotal' => $product->price * (int) $quantity
        ];
        
        Session::put(self::$cartKey, $cart);
        return true;
    }
    
    // Update item quantity
    public static function updateItem($index, $quantity)
    {
        $cart = self::getCart();
        
        if (!isset($cart[$index])) {
            return false;
        }
        
        if ($quantity < 1) {
            return self::removeItem($index);
        }
        
        $cart[$index]['quantity'] = (int) $quantity;
        $cart[$index]['subtotal'] = $cart[$index]['price'] * (int) $quantity;
        
        Session::put(self::$cartKey, $cart);
        return true;
    }
    
    // Remove item from cart
    public static function removeItem($index)
    {
        $cart = self::getCart();
        
        if (isset($cart[$index])) {
            unset($cart[$index]);
            Session::put(self::$cartKey, array_values($cart));
            return true;
        }
        
        return false;
    }
    
    // Calculate total
    public static function getTotal()
    {
        $cart = self::getCart();
        $total = 0;
        
        foreach ($cart as $item) {
            $total += $item['subtotal'];
        }
        
        return $total;
    }
    
    // Get receipt
    public static function getReceipt()
    {
        $cart = self::getCart();
        
        if (empty($cart)) {
            return null;
        }
        
        return [
            'items' => $cart,
            'total' => self::getTotal(),
            'date' => now()->format('d/m/Y H:i:s')
        ];
    }
    
    // Reset transaction
    public static function reset()
    {
        Session::forget(self::$cartKey);
    }
}