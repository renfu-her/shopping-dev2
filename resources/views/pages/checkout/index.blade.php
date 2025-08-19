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
                            
                            <!-- Credit Card Fields -->
                            <div id="credit-card-fields">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="card_number" class="form-label">Card Number</label>
                                            <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="card_expiry" class="form-label">Expiry Date</label>
                                            <input type="text" class="form-control" id="card_expiry" name="card_expiry" placeholder="MM/YY" maxlength="5">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="card_cvv" class="form-label">CVV</label>
                                            <input type="text" class="form-control" id="card_cvv" name="card_cvv" placeholder="123" maxlength="4">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label for="card_holder" class="form-label">Cardholder Name</label>
                                            <input type="text" class="form-control" id="card_holder" name="card_holder" placeholder="Name on card">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bank Transfer Fields -->
                            <div id="bank-transfer-fields" style="display: none;">
                                <div class="alert alert-info">
                                    <h6>Bank Transfer Details</h6>
                                    <p class="mb-1"><strong>Bank:</strong> Example Bank</p>
                                    <p class="mb-1"><strong>Account Number:</strong> 1234-5678-9012-3456</p>
                                    <p class="mb-1"><strong>Account Name:</strong> Your Store Name</p>
                                    <p class="mb-0"><strong>Reference:</strong> Please use your order number as reference</p>
                                </div>
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
                    @include('components.cart-summary')
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
            creditCardInputs.forEach(input => input.setAttribute('required', 'required'));
            bankTransferInputs.forEach(input => input.removeAttribute('required'));
        } else {
            creditCardFields.style.display = 'none';
            bankTransferFields.style.display = 'block';
            creditCardInputs.forEach(input => input.removeAttribute('required'));
            bankTransferInputs.forEach(input => input.removeAttribute('required'));
        }
    }

    creditCardRadio.addEventListener('change', togglePaymentFields);
    bankTransferRadio.addEventListener('change', togglePaymentFields);

    // Card number formatting
    const cardNumberInput = document.getElementById('card_number');
    cardNumberInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    });

    // Expiry date formatting
    const cardExpiryInput = document.getElementById('card_expiry');
    cardExpiryInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        if (value.length >= 2) {
            value = value.substring(0, 2) + '/' + value.substring(2, 4);
        }
        e.target.value = value;
    });

    // CVV validation
    const cardCvvInput = document.getElementById('card_cvv');
    cardCvvInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        e.target.value = value;
    });

    // Initialize payment fields
    togglePaymentFields();
});
</script>
@endpush
@endsection
