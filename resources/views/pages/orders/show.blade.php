@extends('layouts.app')

@section('title', 'Order #' . $order->order_number)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <!-- Back Button -->
            <div class="mb-4">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>

            <!-- Order Header -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h3 class="mb-0">Order #{{ $order->order_number }}</h3>
                            <p class="text-muted mb-0">Placed on {{ $order->created_at->format('M j, Y g:i A') }}</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <span class="badge bg-{{ $order->status === 'pending' ? 'warning' : ($order->status === 'processing' ? 'info' : ($order->status === 'shipped' ? 'primary' : ($order->status === 'delivered' ? 'success' : 'danger'))) }} fs-6">
                                {{ ucfirst($order->status) }}
                            </span>
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }} fs-6 ms-2">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Order Items -->
                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Items</h5>
                        </div>
                        <div class="card-body">
                            @foreach($order->items as $item)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 me-3">
                                        @if($item->product && $item->product->primaryImage)
                                            <img src="{{ asset('storage/' . $item->product->primaryImage->image_path) }}" 
                                                 class="img-fluid rounded" 
                                                 alt="{{ $item->product_name }}"
                                                 style="width: 80px; height: 80px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        <p class="text-muted mb-1">SKU: {{ $item->product_sku ?? 'N/A' }}</p>
                                        <p class="text-muted mb-0">Quantity: {{ $item->quantity }}</p>
                                    </div>
                                    <div class="text-end">
                                        <p class="mb-1"><strong>${{ number_format($item->price, 2) }}</strong> each</p>
                                        <p class="mb-0 text-primary"><strong>${{ number_format($item->total_price, 2) }}</strong></p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>${{ number_format($order->subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax (5%):</span>
                                <span>${{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>${{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong class="text-primary">${{ number_format($order->total_amount, 2) }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Details</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-2"><strong>Payment Method:</strong><br>
                                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
                            </p>
                            
                            @if($order->payment_status === 'paid' && $order->ecpay_payment_date)
                                <p class="mb-2"><strong>Payment Date:</strong><br>
                                    {{ \Carbon\Carbon::parse($order->ecpay_payment_date)->format('M j, Y g:i A') }}
                                </p>
                            @endif

                            @if($order->notes)
                                <p class="mb-2"><strong>Order Notes:</strong><br>
                                    {{ $order->notes }}
                                </p>
                            @endif

                            @if($order->payment_status === 'pending' && $order->payment_method === 'bank_transfer')
                                <div class="alert alert-info">
                                    <h6>Bank Transfer Required</h6>
                                    <p class="mb-1">Please complete your bank transfer to:</p>
                                    <p class="mb-1"><strong>Bank:</strong> Example Bank</p>
                                    <p class="mb-1"><strong>Account:</strong> 1234-5678-9012-3456</p>
                                    <p class="mb-0"><strong>Reference:</strong> {{ $order->order_number }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Shipping Information</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong>Shipping Address:</strong></p>
                            <p class="text-muted">{{ $order->shipping_address }}</p>
                            
                            <p class="mb-1"><strong>Billing Address:</strong></p>
                            <p class="text-muted">{{ $order->billing_address }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                @if($order->payment_status === 'pending' && $order->payment_method === 'bank_transfer')
                    <button class="btn btn-primary" onclick="showBankDetails()">
                        <i class="fas fa-university me-2"></i>View Bank Transfer Details
                    </button>
                @endif
                
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary ms-2">
                    <i class="fas fa-shopping-bag me-2"></i>Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Bank Transfer Details Modal -->
<div class="modal fade" id="bankDetailsModal" tabindex="-1" aria-labelledby="bankDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bankDetailsModalLabel">Bank Transfer Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <h6>Bank Transfer Information</h6>
                    <p class="mb-1"><strong>Bank:</strong> Example Bank</p>
                    <p class="mb-1"><strong>Account Number:</strong> 1234-5678-9012-3456</p>
                    <p class="mb-1"><strong>Account Name:</strong> Your Store Name</p>
                    <p class="mb-0"><strong>Reference:</strong> {{ $order->order_number }}</p>
                </div>
                <p class="text-muted small">
                    <i class="fas fa-info-circle me-1"></i>
                    Please complete the bank transfer within 24 hours to avoid order cancellation.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showBankDetails() {
    var modal = new bootstrap.Modal(document.getElementById('bankDetailsModal'));
    modal.show();
}
</script>
@endpush
