@extends('layouts.admin')

@section('title', 'Edit Category')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Category</h2>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Categories
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

            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Category Information</h5>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.categories.update', $category) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="mb-3">
                                    <label for="name" class="form-label">Category Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $category->name) }}" required>
                                    <small class="text-muted">The slug will be automatically generated from the name</small>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="4">{{ old('description', $category->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <small class="text-muted">Recommended size: 300x300 pixels. Leave empty to keep current image.</small>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_title" class="form-label">Meta Title</label>
                                    <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                           value="{{ old('meta_title', $category->meta_title) }}">
                                    <small class="text-muted">For SEO purposes</small>
                                </div>

                                <div class="mb-3">
                                    <label for="meta_description" class="form-label">Meta Description</label>
                                    <textarea class="form-control" id="meta_description" name="meta_description" 
                                              rows="3">{{ old('meta_description', $category->meta_description) }}</textarea>
                                    <small class="text-muted">For SEO purposes</small>
                                </div>

                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Category
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Current Image</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($category->image)
                                <img src="{{ $category->image_url }}" 
                                     alt="{{ $category->name }}" 
                                     class="img-fluid rounded shadow-sm"
                                     style="max-width: 300px; max-height: 300px; object-fit: cover;">
                                <p class="mt-2 text-muted">Current category image</p>
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
                            <h5>Category Stats</h5>
                        </div>
                        <div class="card-body">
                            <div class="row text-center">
                                <div class="col-12">
                                    <div class="stat-item">
                                        <h4 class="text-primary">{{ $category->products->count() }}</h4>
                                        <small class="text-muted">Total Products</small>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="d-grid">
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> View Details
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
