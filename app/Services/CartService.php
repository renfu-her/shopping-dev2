<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Str;

class CartService
{
    public function addToCart($productId, $quantity = 1, $memberId = null)
    {
        $sessionId = session()->getId();
        $product = Product::findOrFail($productId);

        // Check if item already exists in cart
        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->when($memberId, fn($query) => $query->where('member_id', $memberId))
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $quantity,
                'price' => $product->final_price,
            ]);
        } else {
            CartItem::create([
                'session_id' => $sessionId,
                'member_id' => $memberId,
                'product_id' => $productId,
                'quantity' => $quantity,
                'price' => $product->final_price,
            ]);
        }

        return $this->getCart($memberId);
    }

    public function getCart($memberId = null)
    {
        $sessionId = session()->getId();
        
        return CartItem::with('product')
            ->where('session_id', $sessionId)
            ->when($memberId, fn($query) => $query->where('member_id', $memberId))
            ->get();
    }

    public function updateQuantity($cartItemId, $quantity)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->update(['quantity' => $quantity]);
        
        return $this->getCart($cartItem->member_id);
    }

    public function removeFromCart($cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $memberId = $cartItem->member_id;
        $cartItem->delete();
        
        return $this->getCart($memberId);
    }

    public function getCartTotal($memberId = null)
    {
        $cartItems = $this->getCart($memberId);
        return $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }

    public function clearCart($memberId = null)
    {
        $sessionId = session()->getId();
        
        CartItem::where('session_id', $sessionId)
            ->when($memberId, fn($query) => $query->where('member_id', $memberId))
            ->delete();
    }
}
