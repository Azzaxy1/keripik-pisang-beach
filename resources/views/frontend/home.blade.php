@extends('layouts.app')

@section('title', 'Welcome to Our Store')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <p class="lead mb-4">Temukan produk menakjubkan dengan harga yang tak tertandingi. Belanja dari ribuan produk dengan pengiriman cepat dan pengembalian yang mudah..</p>
                <a href="{{ route('shop') }}" class="btn btn-light btn-lg">Shop Now</a>
            </div>
            <div class="col-lg-6">
                <img src="https://via.placeholder.com/600x400/007bff/ffffff?text=E-Commerce+Store" alt="Hero Image" class="img-fluid rounded shadow">
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
@if($topCategories->count() > 0)
<section class="categories-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Shop by Category</h2>
                <p class="text-muted">Explore our wide range of categories</p>
            </div>
        </div>
        <div class="row">
            @foreach($topCategories as $category)
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <a href="{{ route('category.products', $category->slug) }}" class="text-decoration-none">
                    <div class="card text-center h-100 category-card">
                        <div class="card-body d-flex flex-column">
                            <img src="{{ $category->image_url }}" alt="{{ $category->name }}" class="mb-3 mx-auto" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%;">
                            <h6 class="card-title mb-2">{{ $category->name }}</h6>
                            <small class="text-muted">{{ $category->products_count }} products</small>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Featured Products -->
@if($featuredProducts->count() > 0)
<section class="featured-products py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Featured Products</h2>
                <p class="text-muted">Handpicked products just for you</p>
            </div>
        </div>
        <div class="row">
            @foreach($featuredProducts as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    @if($product->discount_percentage > 0)
                        <div class="discount-badge">-{{ $product->discount_percentage }}%</div>
                    @endif
                    <img src="{{ $product->featured_image }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($product->short_description, 80) }}</p>
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
        <div class="text-center">
            <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">View All Products</a>
        </div>
    </div>
</section>
@endif

<!-- Best Selling Products -->
@if($bestSellingProducts->count() > 0)
<section class="best-selling py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Best Selling Products</h2>
                <p class="text-muted">Most popular products among our customers</p>
            </div>
        </div>
        <div class="row">
            @foreach($bestSellingProducts as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    @if($product->discount_percentage > 0)
                        <div class="discount-badge">-{{ $product->discount_percentage }}%</div>
                    @endif
                    <img src="{{ $product->featured_image }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($product->short_description, 80) }}</p>
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
    </div>
</section>
@endif

<!-- Latest Products -->
@if($latestProducts->count() > 0)
<section class="latest-products py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="fw-bold">Latest Products</h2>
                <p class="text-muted">Check out our newest arrivals</p>
            </div>
        </div>
        <div class="row">
            @foreach($latestProducts->take(8) as $product)
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="card product-card h-100">
                    @if($product->discount_percentage > 0)
                        <div class="discount-badge">-{{ $product->discount_percentage }}%</div>
                    @endif
                    <img src="{{ $product->featured_image }}" class="card-img-top product-image" alt="{{ $product->name }}">
                    <div class="card-body d-flex flex-column">
                        <h6 class="card-title">{{ Str::limit($product->name, 50) }}</h6>
                        <p class="card-text text-muted small">{{ Str::limit($product->short_description, 80) }}</p>
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
    </div>
</section>
@endif

<!-- Features Section -->
<section class="features-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shipping-fast fa-3x text-primary"></i>
                    </div>
                    <h5>Fast Delivery</h5>
                    <p class="text-muted">Free shipping on orders above ₹500</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-undo-alt fa-3x text-primary"></i>
                    </div>
                    <h5>Easy Returns</h5>
                    <p class="text-muted">30-day hassle-free returns</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-shield-alt fa-3x text-primary"></i>
                    </div>
                    <h5>Secure Payment</h5>
                    <p class="text-muted">100% secure payment gateway</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div class="text-center">
                    <div class="feature-icon mb-3">
                        <i class="fas fa-headset fa-3x text-primary"></i>
                    </div>
                    <h5>24/7 Support</h5>
                    <p class="text-muted">Round-the-clock customer support</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.category-card {
    transition: transform 0.3s ease;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.feature-icon {
    transition: transform 0.3s ease;
}

.feature-icon:hover {
    transform: scale(1.1);
}
</style>
@endpush
