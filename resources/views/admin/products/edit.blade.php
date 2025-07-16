@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Edit Produk Keripik Pisang</h2>
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

                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                            value="{{ old('name', $product->name) }}"
                                            placeholder="Contoh: Keripik Pisang Original" required>
                                        <small class="text-muted">Beri nama yang menarik untuk produk keripik pisang
                                            Anda</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi Produk</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            placeholder="Keripik pisang renyah dengan cita rasa yang nikmat...">{{ old('description', $product->description) }}</textarea>
                                        <small class="text-muted">Jelaskan keunikan dan kelebihan produk keripik pisang
                                            Anda</small>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="price" class="form-label">Harga Normal (Rp) *</label>
                                                <input type="number" class="form-control" id="price" name="price"
                                                    step="100" value="{{ old('price', $product->price) }}"
                                                    placeholder="25000" required>
                                                <small class="text-muted">Harga jual regular produk</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="sale_price" class="form-label">Harga Promo (Rp)</label>
                                                <input type="number" class="form-control" id="sale_price" name="sale_price"
                                                    step="100" value="{{ old('sale_price', $product->sale_price) }}"
                                                    placeholder="20000">
                                                <small class="text-muted">Kosongkan jika tidak ada promo</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="stock_quantity" class="form-label">Jumlah Stok *</label>
                                                <input type="number" class="form-control" id="stock_quantity"
                                                    name="stock_quantity"
                                                    value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                                    placeholder="100" required>
                                                <small class="text-muted">Jumlah produk yang tersedia</small>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="weight" class="form-label">Berat (gram)</label>
                                                <input type="number" class="form-control" id="weight" name="weight"
                                                    step="1" value="{{ old('weight', $product->weight) }}"
                                                    placeholder="250">
                                                <small class="text-muted">Berat per kemasan produk</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if ($product->images && $product->images->count() > 0)
                                <div class="card mt-4">
                                    <div class="card-header">
                                        <h5>Current Images</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            @foreach ($product->images as $image)
                                                <div class="col-md-3 mb-3">
                                                    <div class="card">
                                                        <img src="{{ $image->image_url }}" class="card-img-top"
                                                            alt="Product Image" style="height: 150px; object-fit: cover;">
                                                        <div class="card-body p-2">
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm w-100 delete-image"
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
                                    <h5>Pengaturan Produk</h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Kategori *</label>
                                        <select class="form-select" id="category_id" name="category_id" required>
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="text-muted">Pilih kategori yang sesuai untuk produk</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status Produk</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active"
                                                {{ old('status', $product->status ? 'active' : 'inactive') == 'active' ? 'selected' : '' }}>
                                                Aktif</option>
                                            <option value="inactive"
                                                {{ old('status', $product->status ? 'active' : 'inactive') == 'inactive' ? 'selected' : '' }}>
                                                Tidak Aktif</option>
                                        </select>
                                        <small class="text-muted">Produk aktif akan tampil di toko online</small>
                                    </div>

                                    <div class="form-check mb-3">
                                        <input class="form-check-input" type="checkbox" id="featured" name="featured"
                                            value="1" {{ old('featured', $product->featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featured">
                                            Produk Unggulan
                                        </label>
                                        <small class="d-block text-muted">Produk akan ditampilkan di halaman utama</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="featured_image" class="form-label">Foto Utama Produk</label>
                                        @if ($product->featured_image)
                                            <div class="mb-2">
                                                <img src="{{ $product->featured_image }}" alt="Foto Utama"
                                                    class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                        @endif
                                        <input type="file" class="form-control" id="featured_image"
                                            name="featured_image" accept="image/*">
                                        <small class="text-muted">Kosongkan jika tidak ingin mengubah foto</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary me-2">Batal</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Produk
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

                        fetch('{{ route('admin.products.image.delete') }}', {
                                method: 'DELETE',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]').content
                                },
                                body: JSON.stringify({
                                    image_id: imageId
                                })
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
