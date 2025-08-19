<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        $member = auth()->guard('member')->user();
        
        return view('pages.checkout.index', compact('member'));
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'billing_name' => 'required|string|max:255',
            'billing_email' => 'required|email',
            'billing_address' => 'required|string',
            'payment_method' => 'required|in:credit_card,bank_transfer',
            'card_number' => 'required_if:payment_method,credit_card',
            'card_expiry' => 'required_if:payment_method,credit_card',
            'card_cvv' => 'required_if:payment_method,credit_card',
            'card_holder' => 'required_if:payment_method,credit_card',
            'notes' => 'nullable|string',
        ]);

        try {
            // Get cart items
            $cartItems = app(\App\Services\CartService::class)->getCart();
            
            if ($cartItems->isEmpty()) {
                return redirect()->back()->with('error', 'Your cart is empty.');
            }

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->price * $item->quantity;
            });
            $tax = $subtotal * 0.05; // 5% tax
            $shipping = 0; // Free shipping for now
            $total = $subtotal + $tax + $shipping;

            // Create order
            $order = \App\Models\Order::create([
                'order_number' => 'ORD-' . time(),
                'member_id' => auth()->guard('member')->id(),
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => $request->payment_method,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'total_amount' => $total,
                'shipping_address' => $request->billing_address, // For now, use billing address
                'billing_address' => $request->billing_address,
                'notes' => $request->notes,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                    'total_price' => $cartItem->price * $cartItem->quantity,
                ]);
            }

            // Process payment based on method
            if ($request->payment_method === 'credit_card') {
                // Here you would integrate with a real payment gateway
                // For now, we'll simulate a successful payment
                $paymentResult = $this->processCreditCardPayment($request, $total);
                
                if ($paymentResult['success']) {
                    $order->update([
                        'payment_status' => 'paid',
                        'ecpay_payment_date' => now(),
                    ]);
                    
                    // Clear cart
                    app(\App\Services\CartService::class)->clearCart();
                    
                    return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! Payment processed.');
                } else {
                    $order->update(['payment_status' => 'failed']);
                    return redirect()->back()->with('error', 'Payment failed: ' . $paymentResult['message']);
                }
            } else {
                // Bank transfer - mark as pending
                $order->update(['payment_status' => 'pending']);
                
                // Clear cart
                app(\App\Services\CartService::class)->clearCart();
                
                return redirect()->route('orders.show', $order)->with('success', 'Order placed successfully! Please complete bank transfer.');
            }

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while processing your order. Please try again.');
        }
    }

    private function processCreditCardPayment(Request $request, $amount)
    {
        // This is a placeholder for payment processing
        // In a real application, you would integrate with Stripe, PayPal, or another payment gateway
        
        // Simulate payment processing
        $cardNumber = $request->card_number;
        $lastFour = substr(str_replace(' ', '', $cardNumber), -4);
        
        // Simple validation
        if (strlen(str_replace(' ', '', $cardNumber)) !== 16) {
            return ['success' => false, 'message' => 'Invalid card number'];
        }
        
        if (strlen($request->card_cvv) < 3) {
            return ['success' => false, 'message' => 'Invalid CVV'];
        }
        
        // Simulate successful payment
        return ['success' => true, 'message' => 'Payment processed successfully'];
    }

    public function paymentResult(Request $request)
    {
        // Handle payment result callback
        return response()->json(['status' => 'success']);
    }

    public function paymentNotify(Request $request)
    {
        // Handle payment notification
        return response()->json(['status' => 'success']);
    }
}
