@extends('layouts.admin')

@section('title', 'Detail Kategori - ' . $category->name)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Detail Kategori</h2>
                    <div>
                        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Kategori
                        </a>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali ke Kategori
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Informasi Kategori</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Nama:</strong>
                                        <p>{{ $category->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Slug:</strong>
                                        <p>{{ $category->slug }}</p>
                                    </div>
                                </div>

                                @if ($category->description)
                                    <div class="row">
                                        <div class="col-12">
                                            <strong>Deskripsi:</strong>
                                            <p>{{ $category->description }}</p>
                                        </div>
                                    </div>
                                @endif

                                @if ($category->meta_title || $category->meta_description)
                                    <div class="row">
                                        @if ($category->meta_title)
                                            <div class="col-md-6">
                                                <strong>Judul Meta:</strong>
                                                <p>{{ $category->meta_title }}</p>
                                            </div>
                                        @endif
                                        @if ($category->meta_description)
                                            <div class="col-md-6">
                                                <strong>Deskripsi Meta:</strong>
                                                <p>{{ $category->meta_description }}</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Status:</strong>
                                        <p>
                                            <span
                                                class="badge bg-{{ $category->status == 'active' ? 'success' : 'danger' }}">
                                                {{ $category->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Jumlah Produk:</strong>
                                        <p>{{ $category->products_count ?? 0 }} produk</p>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Dibuat:</strong>
                                        <p>{{ $category->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Terakhir Diperbarui:</strong>
                                        <p>{{ $category->updated_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($category->products && $category->products->count() > 0)
                            <div class="card mt-4">
                                <div class="card-header">
                                    <h5>Produk dalam Kategori Ini</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Gambar</th>
                                                    <th>Nama</th>
                                                    <th>Harga</th>
                                                    <th>Stok</th>
                                                    <th>Status</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($category->products->take(10) as $product)
                                                    <tr>
                                                        <td>
                                                            <img src="{{ $product->featured_image ?: 'https://via.placeholder.com/50x50?text=No+Image' }}"
                                                                alt="{{ $product->name }}"
                                                                style="width: 50px; height: 50px; object-fit: cover;"
                                                                class="rounded">
                                                        </td>
                                                        <td>{{ Str::limit($product->name, 30) }}</td>
                                                        <td>Rp {{ number_format((float) $product->price, 0, ',', '.') }}
                                                        </td>
                                                        <td>{{ $product->stock_quantity }}</td>
                                                        <td>
                                                            <span
                                                                class="badge bg-{{ $product->status == 'active' ? 'success' : 'danger' }}">
                                                                {{ $product->status == 'active' ? 'Aktif' : 'Tidak Aktif' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.products.show', $product) }}"
                                                                class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @if ($category->products->count() > 10)
                                        <div class="text-center">
                                            <a href="{{ route('admin.products.index', ['category' => $category->id]) }}"
                                                class="btn" style="background-color: #f3b841; color: white;">
                                                Lihat Semua Produk
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
                                <h5>Gambar Kategori</h5>
                            </div>
                            <div class="card-body text-center">
                                @if ($category->image)
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-width: 300px; max-height: 300px; object-fit: cover;">
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x mb-3"></i>
                                        <p>Belum ada gambar diupload</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Statistik Singkat</h5>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h4 class="text-primary">{{ $category->products_count ?? 0 }}</h4>
                                            <small class="text-muted">Produk</small>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="stat-item">
                                            <h4 class="text-success">
                                                {{ $category->products->where('status', 'active')->count() }}</h4>
                                            <small class="text-muted">Aktif</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-4">
                            <div class="card-header">
                                <h5>Aksi</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Kategori
                                    </a>
                                    <a href="{{ route('admin.products.create', ['category' => $category->id]) }}"
                                        class="btn btn-success">
                                        <i class="fas fa-plus"></i> Tambah Produk
                                    </a>
                                    <a href="{{ route('category.products', $category->slug) }}" class="btn btn-info"
                                        target="_blank">
                                        <i class="fas fa-external-link-alt"></i> Lihat di Toko
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
