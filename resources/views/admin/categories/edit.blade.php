@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Edit Kategori</h2>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali ke Kategori
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

                <div class="row">
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5>Informasi Kategori</h5>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.categories.update', $category) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Kategori *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name', $category->name) }}" required
                                            placeholder="Contoh: Keripik Pisang Original">
                                        <small class="text-muted">Nama kategori keripik pisang (slug akan dibuat
                                            otomatis)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            placeholder="Deskripsi singkat tentang kategori keripik pisang ini...">{{ old('description', $category->description) }}</textarea>
                                        <small class="text-muted">Deskripsi kategori untuk membantu pelanggan memahami jenis
                                            keripik</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Gambar Kategori</label>
                                        <input type="file" class="form-control" id="image" name="image"
                                            accept="image/jpeg,image/png,image/jpg">
                                        <small class="text-muted">Upload gambar baru (kosongkan jika tidak ingin mengubah
                                            gambar) | Format: JPG, PNG | maksimal 2MB</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active"
                                                {{ old('status', $category->status) == 'active' ? 'selected' : '' }}>Aktif
                                            </option>
                                            <option value="inactive"
                                                {{ old('status', $category->status) == 'inactive' ? 'selected' : '' }}>Tidak
                                                Aktif</option>
                                        </select>
                                        <small class="text-muted">Status kategori - hanya kategori aktif yang ditampilkan di
                                            toko</small>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">
                                            <i class="fas fa-times"></i> Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Perbarui Kategori
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Gambar Saat Ini</h5>
                            </div>
                            <div class="card-body text-center">
                                @if ($category->image)
                                    <img src="{{ $category->image_url }}" alt="{{ $category->name }}"
                                        class="img-fluid rounded shadow-sm"
                                        style="max-width: 200px; max-height: 200px; object-fit: cover;">
                                    <p class="mt-2 text-muted small">Gambar kategori saat ini</p>
                                @else
                                    <div class="text-muted">
                                        <i class="fas fa-image fa-3x mb-3"></i>
                                        <p>Belum ada gambar</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Statistik Kategori</h5>
                            </div>
                            <div class="card-body text-center">
                                <div class="row">
                                    <div class="col-6">
                                        <h4 class="text-primary mb-0">{{ $category->products->count() }}</h4>
                                        <small class="text-muted">Total Produk</small>
                                    </div>
                                    <div class="col-6">
                                        <h4 class="text-success mb-0">
                                            {{ $category->products->where('status', 'active')->count() }}</h4>
                                        <small class="text-muted">Produk Aktif</small>
                                    </div>
                                </div>
                                <hr class="my-3">
                                <div class="d-grid">
                                    <a href="{{ route('admin.categories.show', $category) }}"
                                        class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i> Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="card mt-3">
                            <div class="card-header">
                                <h5>Tips Edit Kategori</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled small">
                                    <li><i class="fas fa-lightbulb text-warning"></i> <strong>Hati-hati mengubah
                                            nama</strong> - akan mempengaruhi URL</li>
                                    <li><i class="fas fa-info-circle text-info"></i> <strong>Gambar baru</strong> akan
                                        mengganti gambar lama</li>
                                    <li><i class="fas fa-exclamation-triangle text-danger"></i> <strong>Status tidak
                                            aktif</strong> akan menyembunyikan kategori dari toko</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
