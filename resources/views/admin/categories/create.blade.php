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
                                            value="{{ old('name') }}" required>
                                        <small class="text-muted">Slug akan dibuat otomatis dari nama</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="image" class="form-label">Gambar Kategori</label>
                                        <input type="file" class="form-control" id="image" name="image"
                                            accept="image/*">
                                        <small class="text-muted">Ukuran disarankan: 300x300 piksel</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Judul Meta</label>
                                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                                            value="{{ old('meta_title') }}">
                                        <small class="text-muted">Untuk keperluan SEO</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Deskripsi Meta</label>
                                        <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                                        <small class="text-muted">Untuk keperluan SEO</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="active"
                                                {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                Tidak Aktif</option>
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-end">
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
                                <h5>Tips Cepat</h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success"></i> Gunakan nama yang jelas dan deskriptif
                                    </li>
                                    <li><i class="fas fa-check text-success"></i> Tambahkan deskripsi relevan untuk SEO</li>
                                    <li><i class="fas fa-check text-success"></i> Upload gambar berkualitas tinggi</li>
                                    <li><i class="fas fa-check text-success"></i> Batasi deskripsi meta di bawah 160
                                        karakter</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
