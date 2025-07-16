@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Tambah Produk Keripik Pisang</h2>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Produk
                    </a>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
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
                                    <h5>Informasi Produk</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Produk *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" placeholder="Contoh: Keripik Pisang Original"
                                            required>
                                        <small class="text-muted">Beri nama yang menarik untuk produk keripik pisang
                                            Anda</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi Produk</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            placeholder="Keripik pisang renyah dengan cita rasa yang nikmat...">{{ old('description') }}</textarea>
                                        <small class="text-muted">Jelaskan keunikan dan kelebihan produk keripik pisang
                                            Anda</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Harga Normal (Rp) *</label>
                                                <input type="number" class="form-control" id="price" name="price"
                                                    step="100" value="{{ old('price') }}" placeholder="25000" required>
                                                <small class="text-muted">Harga jual regular produk</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sale_price" class="form-label">Harga Promo (Rp)</label>
                                                <input type="number" class="form-control" id="sale_price" name="sale_price"
                                                    step="100" value="{{ old('sale_price') }}" placeholder="20000">
                                                <small class="text-muted">Kosongkan jika tidak ada promo</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock_quantity" class="form-label">Jumlah Stok *</label>
                                                <input type="number" class="form-control" id="stock_quantity"
                                                    name="stock_quantity" value="{{ old('stock_quantity', 0) }}"
                                                    placeholder="100" required>
                                                <small class="text-muted">Jumlah produk yang tersedia</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="weight" class="form-label">Berat (gram)</label>
                                                <input type="number" class="form-control" id="weight" name="weight"
                                                    step="1" value="{{ old('weight') }}" placeholder="250">
                                                <small class="text-muted">Berat per kemasan produk</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Pengaturan Produk</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Kategori *</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @if (isset($categories) && $categories->count() > 0)
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="" disabled>Belum ada kategori</option>
                                            @endif
                                        </select>
                                        <small class="text-muted">Pilih kategori yang sesuai untuk produk</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status Produk</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active"
                                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                Tidak Aktif</option>
                                        </select>
                                        <small class="text-muted">Produk aktif akan tampil di toko online</small>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                            value="1" {{ old('featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featured">
                                            Produk Unggulan
                                        </label>
                                        <small class="d-block text-muted">Produk akan ditampilkan di halaman utama</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="featured_image" class="form-label">Foto Utama Produk</label>
                                        <input type="file" class="form-control" id="featured_image"
                                            name="featured_image" accept="image/*">
                                        <small class="text-muted">Upload foto terbaik produk keripik pisang</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="images" class="form-label">Foto Tambahan</label>
                                        <input type="file" class="form-control" id="images" name="images[]"
                                            accept="image/*" multiple>
                                        <small class="text-muted">Dapat memilih beberapa foto sekaligus</small>
                                    </div>
                                </div>
                            </div>

                            <div class="card mt-3">
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Produk
                                        </button>
                                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                            Batal
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
