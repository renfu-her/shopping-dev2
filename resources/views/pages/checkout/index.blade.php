@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Checkout</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf
                        
                        <!-- Billing Information -->
                        <div class="mb-4">
                            <h5>Billing Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="billing_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="billing_name" name="billing_name" value="{{ $member->name ?? '' }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="billing_email" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="billing_email" name="billing_email" value="{{ $member->email ?? '' }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="billing_address" class="form-label">Address</label>
                                <textarea class="form-control" id="billing_address" name="billing_address" rows="3" required>{{ $member->address ?? '' }}</textarea>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div class="mb-4">
                            <h5>Shipping Information</h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="same_as_billing" checked>
                                <label class="form-check-label" for="same_as_billing">
                                    Same as billing address
                                </label>
                            </div>
                            <div id="shipping-fields" style="display: none;">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_name" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="shipping_name" name="shipping_name" value="{{ $member->name ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_email" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="shipping_email" name="shipping_email" value="{{ $member->email ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="shipping_address" class="form-label">Address</label>
                                    <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3">{{ $member->address ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-4">
                            <h5>Payment Method</h5>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="credit_card" value="credit_card" checked>
                                <label class="form-check-label" for="credit_card">
                                    <i class="fas fa-credit-card me-2"></i>Credit Card
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer" value="bank_transfer">
                                <label class="form-check-label" for="bank_transfer">
                                    <i class="fas fa-university me-2"></i>Bank Transfer
                                </label>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="mb-4">
                            <label for="notes" class="form-label">Order Notes (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Any special instructions for your order..."></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">Place Order</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Summary</h5>
                </div>
                <div class="card-body">

                    <!-- Cart Items -->
                    <div class="mb-3">
                        @foreach($cartItems as $item)
                            <div class="d-flex align-items-center mb-3">
                                <div class="flex-shrink-0 me-3">
                                    @if($item->product->primaryImage)
                                        <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                             class="img-fluid rounded" 
                                             alt="{{ $item->product->name }}"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ $item->product->name }}</h6>
                                    <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                </div>
                                <div class="text-end">
                                    <strong>${{ number_format($item->price * $item->quantity, 2) }}</strong>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr>

                    <!-- Totals -->
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax (5%):</span>
                        <span>${{ number_format($tax, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Shipping:</span>
                        <span>${{ number_format($shipping, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total:</strong>
                        <strong class="text-primary">${{ number_format($total, 2) }}</strong>
                    </div>

                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle me-1"></i>
                            All prices include tax. Free shipping on all orders.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Shipping address toggle
    const sameAsBillingCheckbox = document.getElementById('same_as_billing');
    const shippingFields = document.getElementById('shipping-fields');
    const shippingInputs = shippingFields.querySelectorAll('input, textarea');

    sameAsBillingCheckbox.addEventListener('change', function() {
        if (this.checked) {
            shippingFields.style.display = 'none';
            shippingInputs.forEach(input => input.removeAttribute('required'));
        } else {
            shippingFields.style.display = 'block';
            shippingInputs.forEach(input => input.setAttribute('required', 'required'));
            
            // Copy billing information to shipping fields
            document.getElementById('shipping_name').value = document.getElementById('billing_name').value;
            document.getElementById('shipping_email').value = document.getElementById('billing_email').value;
            document.getElementById('shipping_address').value = document.getElementById('billing_address').value;
        }
    });

    // Payment method toggle
    const creditCardRadio = document.getElementById('credit_card');
    const bankTransferRadio = document.getElementById('bank_transfer');
    const creditCardFields = document.getElementById('credit-card-fields');
    const bankTransferFields = document.getElementById('bank-transfer-fields');
    const creditCardInputs = creditCardFields.querySelectorAll('input');
    const bankTransferInputs = bankTransferFields.querySelectorAll('input');

    function togglePaymentFields() {
        if (creditCardRadio.checked) {
            creditCardFields.style.display = 'block';
            bankTransferFields.style.display = 'none';
        } else {
            creditCardFields.style.display = 'none';
            bankTransferFields.style.display = 'block';
        }
    }

    creditCardRadio.addEventListener('change', togglePaymentFields);
    bankTransferRadio.addEventListener('change', togglePaymentFields);

    // Initialize payment fields
    togglePaymentFields();
});
</script>
@endpush
@endsection
