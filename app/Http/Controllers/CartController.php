<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('session_id', session()->getId())
            ->with('product')
            ->get();

        return view('pages.cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);
        
        if ($product->stock_quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available.');
        }

        $sessionId = session()->getId();
        $memberId = auth('member')->id();

        // Check if item already exists in cart
        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $request->product_id)
            ->when($memberId, fn($query) => $query->where('member_id', $memberId))
            ->first();

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $cartItem->quantity + $request->quantity,
                'price' => $product->final_price,
            ]);
        } else {
            CartItem::create([
                'session_id' => $sessionId,
                'member_id' => $memberId,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'price' => $product->final_price,
            ]);
        }

        return back()->with('success', 'Product added to cart successfully.');
    }

    public function update(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);
        
        if ($cartItem->product->stock_quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock available.'
            ]);
        }

        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json(['success' => true]);
    }

    public function remove(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
        ]);

        $cartItem = CartItem::findOrFail($request->cart_item_id);
        $cartItem->delete();

        return back()->with('success', 'Item removed from cart.');
    }
}
