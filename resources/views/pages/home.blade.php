@extends('layouts.app')

@section('title', 'Home - E-Commerce Store')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-primary text-white p-5 rounded">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h1 class="display-4 fw-bold">Welcome to Our Store</h1>
                        <p class="lead">Discover amazing products at great prices. Shop with confidence and enjoy fast delivery.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">Shop Now</a>
                    </div>
                    <div class="col-md-6 text-center">
                        <i class="fas fa-shopping-bag fa-8x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Categories -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Shop by Category</h2>
            <div class="row">
                @foreach(App\Models\Category::active()->root()->take(4)->get() as $category)
                    <div class="col-md-3 col-sm-6 mb-4">
                        <div class="card text-center h-100">
                            <div class="card-body">
                                <i class="fas fa-tag fa-3x text-primary mb-3"></i>
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <p class="card-text text-muted">{{ Str::limit($category->description, 100) }}</p>
                                <a href="{{ route('products.category', $category->slug) }}" class="btn btn-outline-primary">
                                    View Products
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Featured Products -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Featured Products</h2>
            <div class="row">
                @foreach(App\Models\Product::active()->featured()->with('primaryImage')->take(8)->get() as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        @include('components.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-4">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">View All Products</a>
            </div>
        </div>
    </div>

    <!-- Latest Products -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4">Latest Products</h2>
            <div class="row">
                @foreach(App\Models\Product::active()->with('primaryImage')->latest()->take(4)->get() as $product)
                    <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                        @include('components.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="text-center mb-5">Why Choose Us</h2>
            <div class="row">
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-shipping-fast fa-3x text-primary mb-3"></i>
                    <h5>Fast Delivery</h5>
                    <p class="text-muted">Get your orders delivered quickly and safely to your doorstep.</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-shield-alt fa-3x text-primary mb-3"></i>
                    <h5>Secure Payment</h5>
                    <p class="text-muted">Your payment information is protected with industry-standard security.</p>
                </div>
                <div class="col-md-4 text-center mb-4">
                    <i class="fas fa-headset fa-3x text-primary mb-3"></i>
                    <h5>24/7 Support</h5>
                    <p class="text-muted">Our customer support team is always ready to help you.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
