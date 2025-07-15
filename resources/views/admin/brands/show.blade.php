@extends('layouts.admin')

@section('title', 'Brand Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Brand Details</h2>
                <div>
                    <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit Brand
                    </a>
                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Brands
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Brand Information</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($brand->logo)
                                <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" 
                                     class="img-fluid mb-3" style="max-width: 200px;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center mb-3" 
                                     style="width: 200px; height: 200px; margin: 0 auto;">
                                    <i class="fas fa-tag fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            <h4>{{ $brand->name }}</h4>
                            
                            @if($brand->status)
                                <span class="badge bg-success mb-3">Active</span>
                            @else
                                <span class="badge bg-secondary mb-3">Inactive</span>
                            @endif

                            @if($brand->website)
                                <div class="mb-3">
                                    <a href="{{ $brand->website }}" target="_blank" class="btn btn-outline-primary">
                                        <i class="fas fa-external-link-alt"></i> Visit Website
                                    </a>
                                </div>
                            @endif

                            <div class="text-muted">
                                <small>Created: {{ $brand->created_at->format('M d, Y') }}</small><br>
                                <small>Updated: {{ $brand->updated_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    @if($brand->description)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Description</h5>
                        </div>
                        <div class="card-body">
                            <p>{{ $brand->description }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-header">
                            <h5>Products ({{ $brand->products->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @if($brand->products->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>SKU</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($brand->products as $product)
                                            <tr>
                                                <td>
                                                    <img src="{{ $product->featured_image }}" alt="{{ $product->name }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td><code>{{ $product->sku }}</code></td>
                                                <td>₹{{ number_format($product->price, 2) }}</td>
                                                <td>{{ $product->stock_quantity }}</td>
                                                <td>
                                                    @if($product->status)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.products.show', $product) }}" 
                                                       class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product) }}" 
                                                       class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center text-muted py-4">
                                    <i class="fas fa-box-open fa-3x mb-3"></i>
                                    <h5>No Products Found</h5>
                                    <p>This brand doesn't have any products yet.</p>
                                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Product
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
