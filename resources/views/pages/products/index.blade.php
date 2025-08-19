@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' - E-Commerce Store' : 'All Products - E-Commerce Store')

@section('content')
<div class="container">
    @if(isset($category))
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
            </ol>
        </nav>
    @endif
    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filters</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ isset($category) ? route('products.category', $category->slug) : route('products.index') }}">
                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                @foreach(App\Models\Category::active()->root()->get() as $cat)
                                    <option value="{{ $cat->id }}" 
                                        @if(!empty(request('category'))){{  (request('category') == $cat->id) ? 'selected' : '' }} @endif>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" name="min_price" class="form-control" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" name="max_price" class="form-control" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Sort By -->
                        <div class="mb-3">
                            <label class="form-label">Sort By</label>
                            <select name="sort" class="form-select">
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                            </select>
                        </div>

                        <!-- Featured Only -->
                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" name="featured" class="form-check-input" value="1" {{ request('featured') ? 'checked' : '' }}>
                                <label class="form-check-label">Featured Only</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <!-- Search and Results Info -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>{{ isset($category) ? $category->name : 'All Products' }}</h2>
                    <p class="text-muted mb-0">{{ $products->total() }} products found</p>
                </div>
                <div class="d-flex align-items-center">
                    <span class="me-2">View:</span>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary active" onclick="setView('grid')">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" onclick="setView('list')">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row" id="products-grid">
                @forelse($products as $product)
                    <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
                        @include('components.product-card', ['product' => $product])
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="fas fa-search fa-3x text-muted mb-3"></i>
                            <h4>No products found</h4>
                            <p class="text-muted">Try adjusting your filters or search terms.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary">Clear Filters</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
function setView(view) {
    const grid = document.getElementById('products-grid');
    const buttons = document.querySelectorAll('.btn-group .btn');
    
    buttons.forEach(btn => btn.classList.remove('active'));
    event.target.classList.add('active');
    
    if (view === 'list') {
        grid.classList.add('list-view');
        grid.querySelectorAll('.col-lg-4').forEach(col => {
            col.classList.remove('col-lg-4', 'col-md-6', 'col-sm-6');
            col.classList.add('col-12');
        });
    } else {
        grid.classList.remove('list-view');
        grid.querySelectorAll('.col-12').forEach(col => {
            col.classList.remove('col-12');
            col.classList.add('col-lg-4', 'col-md-6', 'col-sm-6');
        });
    }
}
</script>
@endpush

@push('styles')
<style>
.list-view .product-card {
    flex-direction: row;
}

.list-view .product-card .card-img-top {
    width: 200px;
    height: 150px;
    flex-shrink: 0;
}

.list-view .product-card .card-body {
    flex: 1;
}
</style>
@endpush
@endsection
