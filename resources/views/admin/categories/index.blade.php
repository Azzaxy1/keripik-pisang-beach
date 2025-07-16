@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manajemen Kategori</h2>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Kategori Baru
                    </a>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped" id="categories-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Gambar</th>
                                        <th>Nama</th>
                                        <th>Slug</th>
                                        <th>Jumlah Produk</th>
                                        <th>Status</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>
                                                @if ($category->image)
                                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                                        style="width: 50px; height: 50px; object-fit: cover;"
                                                        class="rounded">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                        style="width: 50px; height: 50px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->slug }}</td>
                                            <td>{{ $category->products_count ?? 0 }}</td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $category->status == 'active' ? 'success' : 'danger' }}">
                                                    {{ $category->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td>{{ $category->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.categories.show', $category) }}"
                                                        class="btn btn-sm btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                                        class="btn btn-sm btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.categories.destroy', $category) }}"
                                                        method="POST" style="display: inline;"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#categories-table').DataTable({
                "pageLength": 25,
                "ordering": true,
                "searching": true
            });
        });
    </script>
@endpush
