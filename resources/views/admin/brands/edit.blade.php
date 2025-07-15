@extends('layouts.admin')

@section('title', 'Edit Brand')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Brand</h2>
                <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Brands
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

            <form action="{{ route('admin.brands.update', $brand) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Brand Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Brand Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" 
                                           value="{{ old('name', $brand->name) }}" required>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" 
                                              rows="5">{{ old('description', $brand->description) }}</textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="website" class="form-label">Website URL</label>
                                    <input type="url" class="form-control" id="website" name="website" 
                                           value="{{ old('website', $brand->website) }}" placeholder="https://example.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Brand Settings</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="active" {{ old('status', $brand->status ? 'active' : 'inactive') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $brand->status ? 'active' : 'inactive') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="logo" class="form-label">Brand Logo</label>
                                    @if($brand->logo)
                                        <div class="mb-2">
                                            <img src="{{ $brand->logo_url }}" alt="Current Logo" 
                                                 class="img-thumbnail" style="max-width: 200px;">
                                        </div>
                                    @endif
                                    <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                                    <small class="text-muted">Leave empty to keep current logo. Recommended size: 200x200px</small>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save"></i> Update Brand
                                    </button>
                                    <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">
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
