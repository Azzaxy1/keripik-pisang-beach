@extends('layouts.admin')

@section('title', 'Brands Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Brands Management</h2>
                <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Brand
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="brands-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Logo</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Products Count</th>
                                    <th>Website</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($brands as $brand)
                                <tr>
                                    <td>{{ $brand->id }}</td>
                                    <td>
                                        @if($brand->logo)
                                            <img src="{{ $brand->logo_url }}" alt="{{ $brand->name }}" 
                                                 style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-tag text-muted"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $brand->name }}</strong>
                                        @if($brand->description)
                                            <br><small class="text-muted">{{ Str::limit($brand->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td><code>{{ $brand->slug }}</code></td>
                                    <td>
                                        <span class="badge bg-info">{{ $brand->products_count ?? $brand->products()->count() }}</span>
                                    </td>
                                    <td>
                                        @if($brand->website)
                                            <a href="{{ $brand->website }}" target="_blank" class="text-primary">
                                                <i class="fas fa-external-link-alt"></i> Visit
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($brand->status)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $brand->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.brands.show', $brand) }}" 
                                               class="btn btn-sm btn-info" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.brands.edit', $brand) }}" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deleteBrand({{ $brand->id }})" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center">
                        {{ $brands->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="delete-form" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('scripts')
<script>
function deleteBrand(brandId) {
    if (confirm('Are you sure you want to delete this brand? This action cannot be undone.')) {
        const form = document.getElementById('delete-form');
        form.action = '/admin/brands/' + brandId;
        form.submit();
    }
}

$(document).ready(function() {
    $('#brands-table').DataTable({
        "pageLength": 25,
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            { "orderable": false, "targets": [1, 8] }
        ]
    });
});
</script>
@endsection
