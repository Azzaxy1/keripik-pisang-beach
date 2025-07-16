@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Manajemen Produk Keripik Pisang</h2>
                    @if (Auth::user()->hasRole('admin'))
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Tambah Produk Baru
                        </a>
                    @endif
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
                            <table class="table table-striped table-hover" id="products-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="8%">ID</th>
                                        <th width="12%">Foto</th>
                                        <th width="25%">Nama Produk</th>
                                        <th width="15%">Kategori</th>
                                        <th width="12%">Harga</th>
                                        <th width="12%">Harga Promo</th>
                                        <th width="8%">Stok</th>
                                        <th width="8%">Status</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td class="align-middle">
                                                <span class="badge bg-light text-dark">{{ $product->id }}</span>
                                            </td>
                                            <td class="align-middle">
                                                <img src="{{ $product->featured_image }}" alt="{{ $product->name }}"
                                                    style="width: 60px; height: 60px; object-fit: cover;"
                                                    class="rounded shadow-sm">
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <strong>{{ Str::limit($product->name, 30) }}</strong>
                                                    @if ($product->featured)
                                                        <br><span class="badge bg-warning text-dark">Unggulan</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="align-middle">{{ $product->category->name ?? 'Belum ada kategori' }}
                                            </td>
                                            <td class="align-middle">
                                                <span class="fw-bold">Rp
                                                    {{ number_format((float) $product->price, 0, ',', '.') }}</span>
                                            </td>
                                            <td class="align-middle">
                                                @if ($product->sale_price)
                                                    <span class="text-success fw-bold">Rp
                                                        {{ number_format((float) $product->sale_price, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td class="align-middle text-center">
                                                <span
                                                    class="badge bg-{{ $product->stock_quantity > 0 ? 'success' : 'danger' }} fs-6">
                                                    {{ $product->stock_quantity }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <span
                                                    class="badge bg-{{ $product->status == 'active' ? 'success' : 'danger' }} fs-6">
                                                    {{ $product->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                                </span>
                                            </td>
                                            <td class="align-middle">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.products.show', $product) }}"
                                                        class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if (Auth::user()->hasRole('admin'))
                                                        <a href="{{ route('admin.products.edit', $product) }}"
                                                            class="btn btn-sm btn-outline-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                                            method="POST" style="display: inline;"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk \'{{ $product->name }}\'?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                                title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#products-table').DataTable({
                "pageLength": 25,
                "ordering": true,
                "searching": true
            });
        });
    </script>
@endpush
