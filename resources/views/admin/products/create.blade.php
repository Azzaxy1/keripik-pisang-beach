@extends('layouts.admin')

@section('title', 'Add New Product')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Add New Product</h2>
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

            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
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
                                           value="{{ old('name') }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="sku" name="sku" 
                                           value="{{ old('sku') }}" placeholder="Leave empty to auto-generate">
                                    <small class="text-muted">Unique product identifier</small>
                                </div>

                                <div class="mb-3">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    <textarea class="form-control" id="short_description" name="short_description" 
                                              rows="3">{{ old('short_description') }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Full Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="5">{{ old('description') }}</textarea>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Regular Price *</label>
                                            <input type="number" class="form-control" id="price" name="price" 
                                                   step="0.01" value="{{ old('price') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sale_price" class="form-label">Sale Price</label>
                                            <input type="number" class="form-control" id="sale_price" name="sale_price" 
                                                   step="0.01" value="{{ old('sale_price') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock_quantity" class="form-label">Stock Quantity *</label>
                                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" 
                                                   value="{{ old('stock_quantity', 0) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="stock_status" class="form-label">Stock Status *</label>
                                            <select class="form-control" id="stock_status" name="stock_status" required>
                                                <option value="in_stock" {{ old('stock_status', 'in_stock') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                                <option value="out_of_stock" {{ old('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                                <option value="on_backorder" {{ old('stock_status') == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="weight" class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" id="weight" name="weight" 
                                                   step="0.01" value="{{ old('weight') }}">
                                        </div>
                                    </div>
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
                                        @if(isset($categories) && $categories->count() > 0)
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No categories available</option>
                                        @endif
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="brand_id" class="form-label">Brand</label>
                                    <select class="form-select" id="brand_id" name="brand_id">
                                        <option value="">Select Brand</option>
                                        @if(isset($brands) && $brands->count() > 0)
                                            @foreach($brands as $brand)
                                                <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                                    {{ $brand->name }}
                                                </option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>No brands available</option>
                                        @endif
                                    </select>
                                    <small class="text-muted">
                                        <a href="{{ route('admin.brands.create') }}" target="_blank" class="text-primary">
                                            <i class="fas fa-plus"></i> Add New Brand
                                        </a>
                                    </small>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="featured" name="featured" 
                                           value="1" {{ old('featured') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="featured">
                                        Featured Product
                                    </label>
                                </div>

                                <div class="mb-3">
                                    <label for="featured_image" class="form-label">Featured Image</label>
                                    <input type="file" class="form-control" id="featured_image" name="featured_image" accept="image/*">
                                    <small class="text-muted">Recommended size: 800x600px</small>
                                </div>

                                <div class="mb-3">
                                    <label for="images" class="form-label">Additional Images</label>
                                    <input type="file" class="form-control" id="images" name="images[]" 
                                           accept="image/*" multiple>
                                    <small class="text-muted">You can select multiple images</small>
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="manage_stock" name="manage_stock" 
                                           value="1" {{ old('manage_stock', 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="manage_stock">
                                        Track Stock Quantity
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Save Product
                                    </button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
