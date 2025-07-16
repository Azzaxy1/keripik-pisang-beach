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
                            <table class="table table-striped table-hover" id="categories-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="8%">ID</th>
                                        <th width="15%">Gambar</th>
                                        <th width="25%">Nama Kategori</th>
                                        <th width="15%">Jumlah Produk</th>
                                        <th width="12%">Status</th>
                                        <th width="15%">Dibuat</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td class="align-middle">
                                                <span class="badge bg-light text-dark">{{ $category->id }}</span>
                                            </td>
                                            <td class="align-middle">
                                                @if ($category->image)
                                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                                        style="width: 60px; height: 60px; object-fit: cover;"
                                                        class="rounded shadow-sm">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                        style="width: 60px; height: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <strong>{{ $category->name }}</strong>
                                                    @if ($category->description)
                                                        <br><small
                                                            class="text-muted">{{ Str::limit($category->description, 60) }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="badge bg-primary fs-6">{{ $category->products_count ?? 0 }}</span>
                                                @if ($category->products_count > 0)
                                                    <br><small class="text-muted">produk terdaftar</small>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="badge bg-{{ $category->status == 'active' ? 'success' : 'danger' }} fs-6">
                                                    {{ $category->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    {{ $category->created_at->format('d M Y') }}
                                                    <br><small
                                                        class="text-muted">{{ $category->created_at->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.categories.show', $category) }}"
                                                        class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.categories.edit', $category) }}"
                                                        class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.categories.destroy', $category) }}"
                                                        method="POST" style="display: inline;"
                                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ $category->name }}\'?\n\nKategori yang memiliki produk tidak dapat dihapus.')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            title="Hapus"
                                                            {{ $category->products_count > 0 ? 'disabled' : '' }}>
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
