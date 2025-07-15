@extends('layouts.app')

@section('title', $product->meta_title ?: $product->name)

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('category.products', $product->category->slug) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <div class="row">
        <!-- Product Images -->
        <div class="col-lg-6">
            <div class="product-images">
                <div class="main-image mb-3">
                    <img src="{{ $product->featured_image ?: 'https://via.placeholder.com/600x400?text=No+Image' }}" 
                         alt="{{ $product->name }}" 
                         class="img-fluid rounded shadow-sm" 
                         id="mainImage">
                </div>
                
                @if($product->images && $product->images->count() > 0)
                <div class="thumbnail-images">
                    <div class="row">
                        <div class="col-3">
                            <img src="{{ $product->featured_image ?: 'https://via.placeholder.com/150x150?text=No+Image' }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded thumb-image active"
                                 onclick="changeMainImage(this.src)">
                        </div>
                        @foreach($product->images->take(3) as $image)
                        <div class="col-3">
                            <img src="{{ $image->image_path ?: 'https://via.placeholder.com/150x150?text=No+Image' }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded thumb-image"
                                 onclick="changeMainImage(this.src)">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-lg-6">
            <div class="product-details">
                <h1 class="product-title">{{ $product->name }}</h1>
                
                @if($product->brand)
                    <p class="text-muted mb-2">Brand: <strong>{{ $product->brand->name }}</strong></p>
                @endif

                <div class="product-rating mb-3">
                    @php $rating = $product->average_rating ?? 0; @endphp
                    @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $rating ? 'text-warning' : 'text-muted' }}"></i>
                    @endfor
                    <span class="ms-2">({{ $product->reviews_count ?? 0 }} reviews)</span>
                </div>

                <div class="product-price mb-4">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="current-price h3 text-danger">₹{{ number_format($product->sale_price, 2) }}</span>
                        <span class="original-price h5 text-muted text-decoration-line-through ms-2">₹{{ number_format($product->price, 2) }}</span>
                        <span class="discount-badge bg-danger text-white px-2 py-1 rounded ms-2">
                            {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                        </span>
                    @else
                        <span class="current-price h3 text-primary">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                @if($product->short_description)
                    <div class="product-short-description mb-4">
                        <p>{{ $product->short_description }}</p>
                    </div>
                @endif

                <div class="product-stock mb-4">
                    @if($product->stock_quantity > 0)
                        <span class="badge bg-success">In Stock ({{ $product->stock_quantity }} available)</span>
                    @else
                        <span class="badge bg-danger">Out of Stock</span>
                    @endif
                </div>

                <!-- Add to Cart Form -->
                <form class="add-to-cart-form mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   value="1" min="1" max="{{ $product->stock_quantity }}">
                        </div>
                        <div class="col-md-9">
                            <button type="button" class="btn btn-primary btn-lg me-2" 
                                    onclick="addToCart({{ $product->id }})"
                                    {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-cart-plus"></i> Add to Cart
                            </button>
                            <button type="button" class="btn btn-outline-danger btn-lg" 
                                    onclick="addToWishlist({{ $product->id }})">
                                <i class="far fa-heart"></i> Wishlist
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Product Features -->
                <div class="product-features">
                    <ul class="list-unstyled">
                        <li><i class="fas fa-truck text-primary"></i> Free shipping on orders above ₹500</li>
                        <li><i class="fas fa-undo text-primary"></i> 30 days return policy</li>
                        <li><i class="fas fa-shield-alt text-primary"></i> 1 year warranty</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Description & Reviews -->
    <div class="row mt-5">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab" 
                            data-bs-target="#description" type="button">Description</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" 
                            data-bs-target="#reviews" type="button">Reviews ({{ $product->reviews_count ?? 0 }})</button>
                </li>
            </ul>
            
            <div class="tab-content" id="productTabsContent">
                <div class="tab-pane fade show active" id="description" role="tabpanel">
                    <div class="p-4">
                        @if($product->description)
                            {!! nl2br(e($product->description)) !!}
                        @else
                            <p>No description available for this product.</p>
                        @endif
                    </div>
                </div>
                
                <div class="tab-pane fade" id="reviews" role="tabpanel">
                    <div class="p-4">
                        @if($product->reviews && $product->reviews->count() > 0)
                            @foreach($product->reviews as $review)
                            <div class="review-item border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $review->user->name }}</strong>
                                        <div class="rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                                <p class="mt-2">{{ $review->comment }}</p>
                            </div>
                            @endforeach
                        @else
                            <p>No reviews yet. Be the first to review this product!</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.thumb-image {
    cursor: pointer;
    border: 2px solid transparent;
    transition: border-color 0.3s;
}

.thumb-image:hover,
.thumb-image.active {
    border-color: #007bff;
}

.product-price .current-price {
    font-weight: bold;
}

.product-features li {
    margin-bottom: 0.5rem;
}

.product-features i {
    width: 20px;
    margin-right: 10px;
}
</style>
@endpush

@push('scripts')
<script>
function changeMainImage(src) {
    document.getElementById('mainImage').src = src;
    
    // Update active thumbnail
    document.querySelectorAll('.thumb-image').forEach(img => {
        img.classList.remove('active');
    });
    event.target.classList.add('active');
}

function addToCart(productId) {
    const quantity = document.getElementById('quantity').value;
    
    fetch('{{ route("cart.add") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: quantity
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added to cart successfully!');
            // Update cart count if you have one
        } else {
            alert('Error adding product to cart');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding product to cart');
    });
}

function addToWishlist(productId) {
    // Implement wishlist functionality
    alert('Wishlist functionality coming soon!');
}
</script>
@endpush
