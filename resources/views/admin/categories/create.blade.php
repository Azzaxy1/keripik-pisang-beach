@extends('layouts.admin')

@section('title', 'Tambah Kategori Baru')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Tambah Kategori Baru</h2>
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
                                <form action="{{ route('admin.categories.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nama Kategori *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ old('name') }}" required
                                            placeholder="Contoh: Keripik Pisang Original">
                                        <small class="text-muted">Nama kategori keripik pisang (slug akan dibuat
                                            otomatis)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="3"
                                            placeholder="Deskripsi singkat tentang kategori keripik pisang ini...">{{ old('description') }}</textarea>
                                        <small class="text-muted">Deskripsi kategori untuk membantu pelanggan memahami jenis
                                            keripik</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Gambar Kategori</label>
                                        <input type="file" class="form-control" id="image" name="image"
                                            accept="image/jpeg,image/png,image/jpg">
                                        <small class="text-muted">Upload gambar kategori (format: JPG, PNG | maksimal
                                            2MB)</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active"
                                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                Tidak Aktif</option>
                                        </select>
                                        <small class="text-muted">Status kategori - hanya kategori aktif yang ditampilkan di
                                            toko</small>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary me-2">
                                            <i class="fas fa-times"></i> Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Simpan Kategori
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5>Tips Kategori Keripik Pisang</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> <strong>Nama Jelas:</strong> Gunakan nama
                                        yang mudah dipahami pembeli</li>
                                    <li><i class="fas fa-check text-success"></i> <strong>Deskripsi Menarik:</strong>
                                        Jelaskan keunikan rasa atau tekstur</li>
                                    <li><i class="fas fa-check text-success"></i> <strong>Gambar Berkualitas:</strong>
                                        Upload foto yang menggugah selera</li>
                                    <li><i class="fas fa-check text-success"></i> <strong>Status Aktif:</strong> Pastikan
                                        kategori aktif agar muncul di toko</li>
                                </ul>

                                <div class="mt-3 p-3 bg-light rounded">
                                    <h6 class="text-primary">Contoh Kategori:</h6>
                                    <small class="text-muted">
                                        • Keripik Pisang Original<br>
                                        • Keripik Pisang Balado<br>
                                        • Keripik Pisang Manis<br>
                                        • Keripik Pisang Pedas
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
