@extends('layouts.app')

@section('title', 'Shopping Cart - E-Commerce Store')

@section('content')
<div class="container">
    <h1 class="mb-4">Shopping Cart</h1>

    @php
        $cartItems = \App\Models\CartItem::where('session_id', session()->getId())->with('product')->get();
    @endphp

    @if($cartItems->count() > 0)
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Cart Items ({{ $cartItems->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                            <div class="cart-item">
                                <div class="row align-items-center">
                                    <div class="col-md-2">
                                        @if($item->product->primaryImage)
                                            <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                                 class="img-fluid rounded" 
                                                 alt="{{ $item->product->name }}">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-md-4">
                                        <h6 class="mb-1">
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="text-decoration-none">
                                                {{ $item->product->name }}
                                            </a>
                                        </h6>
                                        <small class="text-muted">SKU: {{ $item->product->sku ?? 'N/A' }}</small>
                                    </div>
                                    <div class="col-md-2">
                                        <span class="fw-bold">${{ number_format($item->price, 2) }}</span>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="input-group input-group-sm">
                                            <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity({{ $item->id }}, -1)">-</button>
                                            <input type="number" class="form-control text-center quantity-input" 
                                                   value="{{ $item->quantity }}" min="1" max="{{ $item->product->stock_quantity }}"
                                                   data-cart-item-id="{{ $item->id }}">
                                            <button class="btn btn-outline-secondary" type="button" onclick="updateQuantity({{ $item->id }}, 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="fw-bold">${{ number_format($item->price * $item->quantity, 2) }}</span>
                                    </div>
                                    <div class="col-md-1">
                                        <form action="{{ route('cart.remove') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure you want to remove this item?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Cart Summary -->
            <div class="col-lg-4">
                @include('components.cart-summary')
            </div>
        </div>

        <!-- Continue Shopping -->
        <div class="row mt-4">
            <div class="col-12">
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
            <h3>Your cart is empty</h3>
            <p class="text-muted">Looks like you haven't added any products to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-shopping-bag me-2"></i>Start Shopping
            </a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function updateQuantity(cartItemId, change) {
    const input = document.querySelector(`input[data-cart-item-id="${cartItemId}"]`);
    const newQuantity = parseInt(input.value) + change;
    
    if (newQuantity >= 1) {
        input.value = newQuantity;
        
        // Send AJAX request to update cart
        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                cart_item_id: cartItemId,
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating cart: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating cart');
        });
    }
}

// Handle quantity input change
document.addEventListener('DOMContentLoaded', function() {
    const quantityInputs = document.querySelectorAll('.quantity-input');
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const cartItemId = this.dataset.cartItemId;
            const quantity = parseInt(this.value);
            
            if (quantity >= 1) {
                fetch('/cart/update', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        cart_item_id: cartItemId,
                        quantity: quantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating cart: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error updating cart');
                });
            }
        });
    });
});
</script>
@endpush
