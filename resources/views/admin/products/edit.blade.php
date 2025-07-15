@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Product</h2>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Products
                </a>
            </div>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Product Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $product->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU *</label>
                                    <input type="text" class="form-control" id="sku" name="sku" 
                                           value="{{ old('sku', $product->sku) }}" required>
                                    <small class="text-muted">Unique product identifier</small>
                                </div>

                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    <textarea class="form-control" id="short_description" name="short_description" 
                                              rows="3">{{ old('short_description', $product->short_description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Full Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="5">{{ old('description', $product->description) }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Regular Price *</label>
                                            <input type="number" class="form-control" id="price" name="price" 
                                                   step="0.01" value="{{ old('price', $product->price) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sale_price" class="form-label">Sale Price</label>
                                            <input type="number" class="form-control" id="sale_price" name="sale_price" 
                                                   step="0.01" value="{{ old('sale_price', $product->sale_price) }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                                   value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock_status" class="form-label">Stock Status *</label>
                                            <select class="form-control" id="stock_status" name="stock_status" required>
                                                <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                                <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                                <option value="on_backorder" {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" id="weight" name="weight" 
                                                   step="0.01" value="{{ old('weight', $product->weight) }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if($product->images && $product->images->count() > 0)
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Current Images</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @foreach($product->images as $image)
                                    <div class="col-md-3 mb-3">
                                        <div class="card">
                                            <img src="{{ $image->image_url }}" class="card-img-top" alt="Product Image" style="height: 150px; object-fit: cover;">
                                            <div class="card-body p-2">
                                                <button type="button" class="btn btn-danger btn-sm w-100 delete-image" 
                                                        data-image-id="{{ $image->id }}">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Add New Images</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="images" class="form-label">Product Images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" 
                                           accept="image/*" multiple>
                                    <small class="text-muted">You can select multiple images</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Product Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category *</label>
                                    <select class="form-select" id="category_id" name="category_id" required>
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-select" id="brand_id" name="brand_id">
                                        <option value="">Select Brand</option>
                                        @foreach($brands as $brand)
                                            <option value="{{ $brand->id }}" 
                                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                                {{ $brand->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" {{ old('status', $product->status ? 'active' : 'inactive') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $product->status ? 'active' : 'inactive') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured" 
                                           value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured">
                                        Featured Product
                                    </label>
                                </div>

                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">Featured Image</label>
                                    @if($product->featured_image)
                                        <div class="mb-2">
                                            <img src="{{ $product->featured_image }}" alt="Featured Image" 
                                                 class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current image</small>
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="manage_stock" name="manage_stock" 
                                               value="1" {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="manage_stock">
                                            Track Stock Quantity
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Product
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete image functionality
    document.querySelectorAll('.delete-image').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this image?')) {
                const imageId = this.dataset.imageId;
                
                fetch('{{ route("admin.products.image.delete") }}', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ image_id: imageId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('.col-md-3').remove();
                    } else {
                        alert('Failed to delete image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to delete image');
                });
            }
        });
    });
});
</script>
@endsection
