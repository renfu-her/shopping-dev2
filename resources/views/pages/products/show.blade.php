@extends('layouts.app')

@section('title', $product->name . ' - E-Commerce Store')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6 mb-4">
            <div class="product-images">
                @if($product->images->count() > 0)
                    <div class="main-image mb-3">
                        <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                             class="img-fluid rounded" 
                             id="main-image"
                             alt="{{ $product->images->first()->alt_text ?? $product->name }}">
                    </div>
                    
                    @if($product->images->count() > 1)
                        <div class="thumbnail-images">
                            <div class="row">
                                @foreach($product->images as $image)
                                    <div class="col-3 mb-2">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                                             class="img-thumbnail thumbnail-image" 
                                             alt="{{ $image->alt_text ?? $product->name }}"
                                             style="cursor: pointer; height: 80px; object-fit: cover;">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                @else
                    <div class="main-image mb-3">
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                            <i class="fas fa-image fa-4x text-muted"></i>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1 class="mb-3">{{ $product->name }}</h1>
            
            @if($product->is_featured)
                <span class="badge bg-warning mb-2">Featured</span>
            @endif
            
            <div class="mb-3">
                @if($product->sale_price)
                    <span class="text-decoration-line-through text-muted h4">${{ number_format($product->price, 2) }}</span>
                    <span class="h2 text-danger ms-2">${{ number_format($product->sale_price, 2) }}</span>
                    <span class="badge bg-danger ms-2">-{{ $product->discount_percentage }}%</span>
                @else
                    <span class="h2 text-primary">${{ number_format($product->price, 2) }}</span>
                @endif
            </div>

            <div class="mb-3">
                <p class="text-muted">{{ $product->short_description }}</p>
            </div>

            <div class="mb-3">
                <strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}<br>
                <strong>Category:</strong> <a href="{{ route('products.category', $product->category->slug) }}">{{ $product->category->name }}</a><br>
                <strong>Stock:</strong> 
                @if($product->stock_quantity > 0)
                    <span class="text-success">{{ $product->stock_quantity }} available</span>
                @else
                    <span class="text-danger">Out of Stock</span>
                @endif
            </div>

            @if($product->stock_quantity > 0)
                <form action="{{ route('cart.add') }}" method="POST" class="mb-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="row">
                        <div class="col-md-4">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="1" min="1" max="{{ $product->stock_quantity }}">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary btn-lg w-100">
                                <i class="fas fa-cart-plus me-2"></i>Add to Cart
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <button class="btn btn-secondary btn-lg w-100" disabled>
                    <i class="fas fa-times me-2"></i>Out of Stock
                </button>
            @endif

            <div class="mt-4">
                <h5>Product Details</h5>
                <div class="row">
                    @if($product->weight)
                        <div class="col-6">
                            <strong>Weight:</strong> {{ $product->weight }} kg
                        </div>
                    @endif
                    @if($product->dimensions)
                        <div class="col-6">
                            <strong>Dimensions:</strong> {{ $product->dimensions }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description -->
    @if($product->description)
        <div class="row mt-5">
            <div class="col-12">
                <h3>Description</h3>
                <div class="card">
                    <div class="card-body">
                        {!! $product->description !!}
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Related Products -->
    <div class="row mt-5">
        <div class="col-12">
            <h3>Related Products</h3>
            <div class="row">
                @foreach($product->category->products()->where('id', '!=', $product->id)->active()->with('primaryImage')->take(4)->get() as $relatedProduct)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        @include('components.product-card', ['product' => $relatedProduct])
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
