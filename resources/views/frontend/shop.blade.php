@extends('layouts.app')

@section('title', 'Shop All Products')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h2>Shop All Products</h2>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                    <li class="breadcrumb-item active">Shop</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Sidebar Filters -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Filters</h6>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('shop') }}" id="filter-form">
                        <!-- Search -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search products...">
                        </div>

                        <!-- Categories -->
                        @if($categories->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Categories</label>
                            <select class="form-select" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->slug }}" {{ request('category') == $category->slug ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Brands -->
                        @if($brands->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">Brands</label>
                            <select class="form-select" name="brand">
                                <option value="">All Brands</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->slug }}" {{ request('brand') == $brand->slug ? 'selected' : '' }}>
                                        {{ $brand->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="row">
                                <div class="col-6">
                                    <input type="number" class="form-control" name="min_price" placeholder="Min" value="{{ request('min_price') }}">
                                </div>
                                <div class="col-6">
                                    <input type="number" class="form-control" name="max_price" placeholder="Max" value="{{ request('max_price') }}">
                                </div>
                            </div>
                            @if($priceRange)
                            <small class="text-muted">Range: ₹{{ number_format($priceRange->min_price) }} - ₹{{ number_format($priceRange->max_price) }}</small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100 mt-2">Clear Filters</a>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-lg-9 col-md-8">
            <!-- Sort and Results Count -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <small class="text-muted">Showing {{ $products->firstItem() }}-{{ $products->lastItem() }} of {{ $products->total() }} results</small>
                </div>
                <div>
                    <select class="form-select form-select-sm" name="sort" onchange="document.getElementById('filter-form').submit();">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                        <option value="popularity" {{ request('sort') == 'popularity' ? 'selected' : '' }}>Popularity</option>
                    </select>
                </div>
            </div>

            <!-- Products Grid -->
            @if($products->count() > 0)
                <div class="row">
                    @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card product-card h-100">
                            @if($product->discount_percentage > 0)
                                <div class="discount-badge">-{{ $product->discount_percentage }}%</div>
                            @endif
                            <img src="{{ $product->featured_image }}" class="card-img-top product-image" alt="{{ $product->name }}">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                                <p class="card-text text-muted small">{{ Str::limit($product->short_description, 80) }}</p>
                                
                                <!-- Rating -->
                                @if($product->average_rating > 0)
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="text-warning">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $product->average_rating)
                                                    <i class="fas fa-star"></i>
                                                @elseif($i - 0.5 <= $product->average_rating)
                                                    <i class="fas fa-star-half-alt"></i>
                                                @else
                                                    <i class="far fa-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                        <small class="text-muted ms-2">({{ $product->total_reviews }})</small>
                                    </div>
                                </div>
                                @endif

                                <div class="mt-auto">
                                    <div class="price-section mb-3">
                                        @if($product->sale_price && $product->sale_price < $product->price)
                                            <span class="price-sale h6">₹{{ number_format($product->sale_price, 2) }}</span>
                                            <span class="price-original small ms-2">₹{{ number_format($product->price, 2) }}</span>
                                        @else
                                            <span class="h6">₹{{ number_format($product->price, 2) }}</span>
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-cart flex-fill" onclick="addToCart({{ $product->id }})">
                                            <i class="fas fa-cart-plus"></i> Add to Cart
                                        </button>
                                        <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $products->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-5x text-muted mb-4"></i>
                    <h3>No products found</h3>
                    <p class="text-muted">Try adjusting your search or filter criteria.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">View All Products</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form when sort changes
$('select[name="sort"]').change(function() {
    $('#filter-form').append('<input type="hidden" name="sort" value="' + $(this).val() + '">');
    $('#filter-form').submit();
});
</script>
@endpush
