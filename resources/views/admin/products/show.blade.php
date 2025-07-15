@extends('layouts.admin')

@section('title', 'Product Details - ' . $product->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Product Details</h2>
                <div>
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Product
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Products
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Product Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Name:</strong>
                                    <p>{{ $product->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>SKU:</strong>
                                    <p>{{ $product->sku }}</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Category:</strong>
                                    <p>{{ $product->category->name ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Brand:</strong>
                                    <p>{{ $product->brand->name ?? 'N/A' }}</p>
                                </div>
                            </div>

                            @if($product->short_description)
                            <div class="row">
                                <div class="col-12">
                                    <strong>Short Description:</strong>
                                    <p>{{ $product->short_description }}</p>
                                </div>
                            </div>
                            @endif

                            @if($product->description)
                            <div class="row">
                                <div class="col-12">
                                    <strong>Description:</strong>
                                    <div class="border p-3 rounded bg-light">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <strong>Regular Price:</strong>
                                    <p class="h5 text-primary">₹{{ number_format($product->price, 2) }}</p>
                                </div>
                                @if($product->sale_price)
                                <div class="col-md-3">
                                    <strong>Sale Price:</strong>
                                    <p class="h5 text-success">₹{{ number_format($product->sale_price, 2) }}</p>
                                </div>
                                @endif
                                <div class="col-md-3">
                                    <strong>Stock:</strong>
                                    <p class="h5 {{ $product->stock_quantity > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $product->stock_quantity }}
                                    </p>
                                </div>
                                @if($product->weight)
                                <div class="col-md-3">
                                    <strong>Weight:</strong>
                                    <p>{{ $product->weight }} kg</p>
                                </div>
                                @endif
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <p>
                                        <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($product->status) }}
                                        </span>
                                        @if($product->featured)
                                            <span class="badge bg-warning">Featured</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Created:</strong>
                                    <p>{{ $product->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($product->images && $product->images->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Product Images</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach($product->images as $image)
                                <div class="col-md-3 mb-3">
                                    <img src="{{ $image->image_path ?: 'https://via.placeholder.com/200x200?text=No+Image' }}" 
                                         alt="Product Image" 
                                         class="img-fluid rounded shadow-sm"
                                         style="width: 100%; height: 200px; object-fit: cover;">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    @if($product->reviews && $product->reviews->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Customer Reviews ({{ $product->reviews->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @foreach($product->reviews->take(5) as $review)
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
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Featured Image</h5>
                        </div>
                        <div class="card-body text-center">
                            <img src="{{ $product->featured_image }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-fluid rounded shadow-sm"
                                 style="max-width: 300px; max-height: 300px; object-fit: cover;">
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Quick Stats</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h4 class="text-primary">{{ $product->reviews->count() }}</h4>
                                        <small class="text-muted">Reviews</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h4 class="text-warning">
                                            {{ number_format($product->reviews->avg('rating') ?? 0, 1) }}
                                        </h4>
                                        <small class="text-muted">Avg Rating</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Product
                                </a>
                                <a href="{{ route('product.detail', $product->slug) }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> View on Store
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" 
                                      onsubmit="return confirm('Are you sure you want to delete this product?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-trash"></i> Delete Product
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
