<div class="card h-100 product-card">
    <div class="position-relative">
        @if($product->primaryImage)
            <img src="{{ asset('storage/' . $product->primaryImage->image_path) }}" 
                 class="card-img-top" 
                 alt="{{ $product->primaryImage->alt_text ?? $product->name }}"
                 style="height: 200px; object-fit: cover;">
        @else
            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                 style="height: 200px;">
                <i class="fas fa-image fa-3x text-muted"></i>
            </div>
        @endif
        
        @if($product->discount_percentage > 0)
            <span class="badge bg-danger position-absolute top-0 end-0 m-2">
                -{{ $product->discount_percentage }}%
            </span>
        @endif
        
        @if($product->is_featured)
            <span class="badge bg-warning position-absolute top-0 start-0 m-2">
                Featured
            </span>
        @endif
    </div>
    
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">{{ $product->name }}</h5>
        <p class="card-text text-muted small">{{ Str::limit($product->short_description, 100) }}</p>
        
        <div class="mt-auto">
            <div class="d-flex justify-content-between align-items-center mb-2">
                @if($product->sale_price)
                    <span class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</span>
                    <span class="h5 text-danger mb-0">${{ number_format($product->sale_price, 2) }}</span>
                @else
                    <span class="h5 text-primary mb-0">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>
            
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="fas fa-box me-1"></i>
                    Stock: {{ $product->stock_quantity }}
                </small>
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-primary btn-sm">
                    View Details
                </a>
            </div>
        </div>
    </div>
    
    <div class="card-footer bg-transparent">
        <form action="{{ route('cart.add') }}" method="POST" class="d-grid">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit" class="btn btn-primary" {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                <i class="fas fa-cart-plus me-1"></i>
                {{ $product->stock_quantity <= 0 ? 'Out of Stock' : 'Add to Cart' }}
            </button>
        </form>
    </div>
</div>
