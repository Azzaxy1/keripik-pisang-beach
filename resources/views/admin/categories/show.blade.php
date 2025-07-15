@extends('layouts.admin')

@section('title', 'Category Details - ' . $category->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Category Details</h2>
                <div>
                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Category
                    </a>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Categories
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Category Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Name:</strong>
                                    <p>{{ $category->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Slug:</strong>
                                    <p>{{ $category->slug }}</p>
                                </div>
                            </div>

                            @if($category->description)
                            <div class="row">
                                <div class="col-12">
                                    <strong>Description:</strong>
                                    <p>{{ $category->description }}</p>
                                </div>
                            </div>
                            @endif

                            @if($category->meta_title || $category->meta_description)
                            <div class="row">
                                @if($category->meta_title)
                                <div class="col-md-6">
                                    <strong>Meta Title:</strong>
                                    <p>{{ $category->meta_title }}</p>
                                </div>
                                @endif
                                @if($category->meta_description)
                                <div class="col-md-6">
                                    <strong>Meta Description:</strong>
                                    <p>{{ $category->meta_description }}</p>
                                </div>
                                @endif
                            </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <p>
                                        <span class="badge bg-{{ $category->status == 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($category->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Products Count:</strong>
                                    <p>{{ $category->products_count ?? 0 }} products</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Created:</strong>
                                    <p>{{ $category->created_at->format('M d, Y H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Last Updated:</strong>
                                    <p>{{ $category->updated_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($category->products && $category->products->count() > 0)
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5>Products in this Category</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($category->products->take(10) as $product)
                                        <tr>
                                            <td>
                                                <img src="{{ $product->featured_image ?: 'https://via.placeholder.com/50x50?text=No+Image' }}" 
                                                     alt="{{ $product->name }}" 
                                                     style="width: 50px; height: 50px; object-fit: cover;" 
                                                     class="rounded">
                                            </td>
                                            <td>{{ Str::limit($product->name, 30) }}</td>
                                            <td>₹{{ number_format($product->price, 2) }}</td>
                                            <td>{{ $product->stock_quantity }}</td>
                                            <td>
                                                <span class="badge bg-{{ $product->status == 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($product->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            @if($category->products->count() > 10)
                                <div class="text-center">
                                    <a href="{{ route('admin.products.index', ['category' => $category->id]) }}" class="btn btn-primary">
                                        View All Products
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Category Image</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($category->image)
                                <img src="{{ $category->image_url }}" 
                                     alt="{{ $category->name }}" 
                                     class="img-fluid rounded shadow-sm"
                                     style="max-width: 300px; max-height: 300px; object-fit: cover;">
                            @else
                                <div class="text-muted">
                                    <i class="fas fa-image fa-3x mb-3"></i>
                                    <p>No image uploaded</p>
                                </div>
                            @endif
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
                                        <h4 class="text-primary">{{ $category->products_count ?? 0 }}</h4>
                                        <small class="text-muted">Products</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-item">
                                        <h4 class="text-success">{{ $category->products->where('status', 'active')->count() }}</h4>
                                        <small class="text-muted">Active</small>
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
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Category
                                </a>
                                <a href="{{ route('admin.products.create', ['category' => $category->id]) }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add Product
                                </a>
                                <a href="{{ route('category.products', $category->slug) }}" class="btn btn-info" target="_blank">
                                    <i class="fas fa-external-link-alt"></i> View on Store
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
