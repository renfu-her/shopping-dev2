<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Cart Summary</h5>
    </div>
    <div class="card-body">
        @php
            $cartItems = \App\Models\CartItem::where('session_id', session()->getId())->with('product')->get();
            $subtotal = $cartItems->sum(function($item) { return $item->price * $item->quantity; });
            $tax = $subtotal * 0.05; // 5% tax
            $shipping = $subtotal > 100 ? 0 : 10; // Free shipping over $100
            $total = $subtotal + $tax + $shipping;
        @endphp
        
        <div class="d-flex justify-content-between mb-2">
            <span>Subtotal ({{ $cartItems->count() }} items):</span>
            <span>${{ number_format($subtotal, 2) }}</span>
        </div>
        
        <div class="d-flex justify-content-between mb-2">
            <span>Tax (5%):</span>
            <span>${{ number_format($tax, 2) }}</span>
        </div>
        
        <div class="d-flex justify-content-between mb-3">
            <span>Shipping:</span>
            <span>{{ $shipping == 0 ? 'Free' : '$' . number_format($shipping, 2) }}</span>
        </div>
        
        <hr>
        
        <div class="d-flex justify-content-between mb-3">
            <strong>Total:</strong>
            <strong>${{ number_format($total, 2) }}</strong>
        </div>
        
        @if($cartItems->count() > 0)
            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">
                Proceed to Checkout
            </a>
        @else
            <button class="btn btn-primary w-100" disabled>
                Cart is Empty
            </button>
        @endif
    </div>
</div>
