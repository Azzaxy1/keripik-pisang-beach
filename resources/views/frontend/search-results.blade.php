@extends('layouts.app')

@section('title', 'Search Results for "' . $query . '"')

@section('content')
<div class="container my-4">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
            <li class="breadcrumb-item active" aria-current="page">Search Results</li>
        </ol>
    </nav>

    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <h1 class="mb-1">Search Results for "{{ $query }}"</h1>
                    <small class="text-muted">{{ $products->total() }} products found</small>
                </div>
                <div>
                    <form action="{{ route('search') }}" method="GET" class="d-flex">
                        <input type="text" name="q" class="form-control me-2" placeholder="Search products..." value="{{ $query }}" style="min-width: 250px;">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        @if($products->count() > 0)
            @foreach($products as $product)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card product-card h-100">
                        <div class="position-relative">
                            <a href="{{ route('product.detail', $product->slug) }}">
                                <img src="{{ $product->featured_image }}" 
                                     class="card-img-top" 
                                     alt="{{ $product->name }}"
                                     style="height: 250px; object-fit: cover;">
                            </a>
                            @if($product->featured)
                                <span class="badge bg-warning position-absolute top-0 end-0 m-2">Featured</span>
                            @endif
                            @if($product->compare_price && $product->compare_price > $product->price)
                                @php
                                    $discount = round((($product->compare_price - $product->price) / $product->compare_price) * 100);
                                @endphp
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">-{{ $discount }}%</span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <small class="text-muted">{{ $product->category->name }}</small>
                                @if($product->brand)
                                    <small class="text-muted"> • {{ $product->brand->name }}</small>
                                @endif
                            </div>
                            <h6 class="card-title">
                                <a href="{{ route('product.detail', $product->slug) }}" class="text-decoration-none text-dark">
                                    {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', $product->name) !!}
                                </a>
                            </h6>
                            @if($product->short_description)
                                <p class="card-text text-muted small">
                                    {!! preg_replace('/(' . preg_quote($query, '/') . ')/i', '<mark>$1</mark>', Str::limit($product->short_description, 80)) !!}
                                </p>
                            @endif
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="price">
                                        <span class="fw-bold text-primary">₹{{ number_format($product->price, 2) }}</span>
                                        @if($product->compare_price && $product->compare_price > $product->price)
                                            <small class="text-muted text-decoration-line-through ms-1">₹{{ number_format($product->compare_price, 2) }}</small>
                                        @endif
                                    </div>
                                    @if($product->reviews_count > 0)
                                        <div class="rating">
                                            <small class="text-warning">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $product->average_rating)
                                                        ★
                                                    @else
                                                        ☆
                                                    @endif
                                                @endfor
                                            </small>
                                            <small class="text-muted">({{ $product->reviews_count }})</small>
                                        </div>
                                    @endif
                                </div>
                                @if($product->track_quantity && $product->quantity <= 0)
                                    <button class="btn btn-outline-secondary w-100" disabled>Out of Stock</button>
                                @else
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary btn-sm add-to-cart" 
                                                data-product-id="{{ $product->id }}"
                                                data-product-name="{{ $product->name }}"
                                                data-product-price="{{ $product->price }}"
                                                data-product-image="{{ $product->featured_image }}">
                                            Add to Cart
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="text-center py-5">
                    <img src="{{ asset('images/no-search.svg') }}" alt="No results" class="mb-4" style="width: 200px; opacity: 0.5;">
                    <h4 class="text-muted">No products found for "{{ $query }}"</h4>
                    <p class="text-muted">Try adjusting your search terms or browse our categories.</p>
                    <div class="mt-4">
                        <a href="{{ route('shop') }}" class="btn btn-primary me-2">Browse All Products</a>
                        <button type="button" class="btn btn-outline-primary" onclick="document.querySelector('input[name=q]').focus()">
                            Try Another Search
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $products->appends(['q' => $query])->links() }}
                </div>
            </div>
        </div>
    @endif

    <!-- Search Suggestions -->
    @if($products->count() == 0)
    <div class="row mt-5">
        <div class="col-12">
            <div class="card bg-light">
                <div class="card-body">
                    <h6 class="card-title">Search Tips:</h6>
                    <ul class="mb-0">
                        <li>Check your spelling</li>
                        <li>Try more general keywords</li>
                        <li>Try different keywords</li>
                        <li>Browse our categories for inspiration</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .product-card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        border: 1px solid #e0e0e0;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .rating {
        font-size: 14px;
    }
    
    .price .fw-bold {
        font-size: 1.1rem;
    }
    
    mark {
        background-color: yellow;
        padding: 2px 4px;
        border-radius: 3px;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add to cart functionality
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const productName = this.dataset.productName;
            const productPrice = this.dataset.productPrice;
            const productImage = this.dataset.productImage;
            
            // Show loading state
            const originalText = this.innerHTML;
            this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Adding...';
            this.disabled = true;
            
            // Add to cart via AJAX
            fetch('{{ route("cart.add") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    this.innerHTML = '<i class="fas fa-check me-1"></i>Added!';
                    this.classList.remove('btn-primary');
                    this.classList.add('btn-success');
                    
                    // Update cart count in header if exists
                    const cartCount = document.querySelector('.cart-count');
                    if (cartCount) {
                        cartCount.textContent = data.cart_count;
                    }
                    
                    // Reset button after 2 seconds
                    setTimeout(() => {
                        this.innerHTML = originalText;
                        this.classList.remove('btn-success');
                        this.classList.add('btn-primary');
                        this.disabled = false;
                    }, 2000);
                    
                    // Show toast notification
                    showToast('Product added to cart successfully!', 'success');
                } else {
                    throw new Error(data.message || 'Failed to add product to cart');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.innerHTML = originalText;
                this.disabled = false;
                showToast('Failed to add product to cart. Please try again.', 'error');
            });
        });
    });
});

function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-auto" aria-label="Close"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        toast.remove();
    }, 3000);
    
    // Close button functionality
    toast.querySelector('.btn-close').addEventListener('click', () => {
        toast.remove();
    });
}
</script>
@endsection
