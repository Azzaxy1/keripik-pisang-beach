@extends('layouts.admin')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>Detail Pesanan #{{ $order->order_number }}</h2>
                        <p class="text-muted mb-0">Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                        {{-- <a href="{{ route('admin.orders.print', $order) }}" target="_blank" class="btn btn-outline-primary">
                            <i class="fas fa-print"></i> Cetak
                        </a> --}}
                        <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank"
                            class="btn btn-outline-success">
                            <i class="fas fa-file-invoice"></i> Invoice
                        </a>
                    </div>
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="row">
                    <!-- Order Details -->
                    <div class="col-lg-8">
                        <!-- Customer Info -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Informasi Pelanggan</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center mb-3">
                                            @if ($order->user->avatar)
                                                <img src="{{ $order->user->avatar }}" alt="{{ $order->user->name }}"
                                                    class="rounded-circle me-3"
                                                    style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                                                    style="width: 50px; height: 50px;">
                                                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $order->user->name }}</h6>
                                                <small class="text-muted">{{ $order->user->email }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Total Pesanan:</strong>
                                            {{ $order->user->orders->count() }}</p>
                                        <p class="mb-1"><strong>Member Sejak:</strong>
                                            {{ $order->user->created_at->format('d M Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Item Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Produk</th>
                                                <th>SKU</th>
                                                <th>Harga</th>
                                                <th>Jumlah</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($order->items as $item)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/50x50' }}"
                                                                alt="{{ $item->product->name }}" class="img-thumbnail me-3"
                                                                style="width: 50px; height: 50px; object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                                <small
                                                                    class="text-muted">{{ $item->product->category->name ?? '' }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>{{ $item->product->sku }}</td>
                                                    <td>Rp {{ number_format((float) $item->price, 0, ',', '.') }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Alamat Pengiriman</h5>
                            </div>
                            <div class="card-body">
                                @if ($order->shipping_address)
                                    @php
                                        $shippingAddr = is_string($order->shipping_address)
                                            ? json_decode($order->shipping_address, true)
                                            : $order->shipping_address;
                                    @endphp
                                    @if ($shippingAddr)
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Nama:</strong> {{ $shippingAddr['name'] ?? '' }}
                                                </p>
                                                <p class="mb-1"><strong>Alamat:</strong>
                                                    {{ $shippingAddr['address'] ?? '' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                @if (isset($shippingAddr['phone']) && !empty($shippingAddr['phone']))
                                                    <p class="mb-1"><strong>Telepon:</strong>
                                                        {{ $shippingAddr['phone'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">Alamat tidak tersedia</p>
                                    @endif
                                @else
                                    <p class="text-muted">Alamat tidak tersedia</p>
                                @endif
                            </div>
                        </div>

                        <!-- Payment Proof -->
                        @if ($order->payment_proof)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Bukti Pembayaran</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <img src="{{ asset('storage/' . $order->payment_proof) }}"
                                                alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 300px;">
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                                                class="btn btn-outline-primary">
                                                <i class="fas fa-external-link-alt"></i> Lihat Full Size
                                            </a>
                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" download
                                                class="btn btn-outline-success ms-2">
                                                <i class="fas fa-download"></i> Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Order Summary & Actions -->
                    <div class="col-lg-4">
                        <!-- Order Summary -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Ringkasan Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}</span>
                                </div>
                                @if ($order->tax_amount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Pajak:</span>
                                        <span>Rp {{ number_format((float) $order->tax_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Ongkos Kirim:</span>
                                    <span>Rp {{ number_format((float) $order->shipping_amount, 0, ',', '.') }}</span>
                                </div>
                                @if ($order->discount_amount > 0)
                                    <div class="d-flex justify-content-between mb-2 text-success">
                                        <span>Diskon:</span>
                                        <span>-Rp {{ number_format((float) $order->discount_amount, 0, ',', '.') }}</span>
                                    </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between fw-bold h5">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Status Management -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Status Pesanan</h5>
                            </div>
                            <div class="card-body">
                                @if (Auth::user()->hasRole('admin'))
                                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST"
                                        class="mb-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mb-3">
                                            <label class="form-label">Status Pesanan</label>
                                            <select name="status" class="form-select" onchange="this.form.submit()">
                                                <option value="pending"
                                                    {{ $order->status == 'pending' ? 'selected' : '' }}>
                                                    Menunggu</option>
                                                <option value="processing"
                                                    {{ $order->status == 'processing' ? 'selected' : '' }}>Diproses
                                                </option>
                                                <option value="shipped"
                                                    {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                                    Dikirim</option>
                                                <option value="delivered"
                                                    {{ $order->status == 'delivered' ? 'selected' : '' }}>Diterima</option>
                                                <option value="cancelled"
                                                    {{ $order->status == 'cancelled' ? 'selected' : '' }}>Dibatalkan
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                @endif

                                <div class="mb-3">
                                    @php
                                        $statusBadges = [
                                            'pending' => ['warning', 'Menunggu'],
                                            'processing' => ['info', 'Diproses'],
                                            'shipped' => ['primary', 'Dikirim'],
                                            'delivered' => ['success', 'Diterima'],
                                            'cancelled' => ['danger', 'Dibatalkan'],
                                        ];
                                        $statusConfig = $statusBadges[$order->status] ?? ['secondary', 'Unknown'];
                                    @endphp
                                    <span class="badge bg-{{ $statusConfig[0] }} p-2">
                                        {{ $statusConfig[1] }}
                                    </span>
                                    @if (Auth::user()->hasRole('owner'))
                                        <small class="text-muted d-block mt-2">
                                            <i class="fas fa-info-circle"></i> Hanya admin yang dapat mengubah status
                                            pesanan
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Payment Status -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Status Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                @if (Auth::user()->hasRole('admin'))
                                    <form action="{{ route('admin.orders.updatePaymentStatus', $order) }}" method="POST"
                                        class="mb-3">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mb-3">
                                            <label class="form-label">Status Pembayaran</label>
                                            <select name="payment_status" class="form-select"
                                                onchange="this.form.submit()">
                                                <option value="pending"
                                                    {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Menunggu
                                                </option>
                                                <option value="paid"
                                                    {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Lunas</option>
                                                <option value="failed"
                                                    {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Gagal
                                                </option>
                                                <option value="refunded"
                                                    {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>
                                                    Dikembalikan
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                @endif

                                @php
                                    $paymentBadges = [
                                        'pending' => ['warning', 'Menunggu'],
                                        'paid' => ['success', 'Lunas'],
                                        'failed' => ['danger', 'Gagal'],
                                        'refunded' => ['info', 'Dikembalikan'],
                                    ];
                                    $paymentConfig = $paymentBadges[$order->payment_status] ?? ['secondary', 'Unknown'];
                                @endphp
                                <span class="badge bg-{{ $paymentConfig[0] }}">
                                    {{ $paymentConfig[1] }}
                                </span>
                                @if (Auth::user()->hasRole('owner'))
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i> Hanya admin yang dapat mengubah status
                                        pembayaran
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Catatan Pesanan</h5>
                            </div>
                            <div class="card-body">
                                @if ($order->notes)
                                    <div class="alert alert-info">
                                        {{ $order->notes }}
                                    </div>
                                @else
                                    <p class="text-muted">Belum ada catatan untuk pesanan ini.</p>
                                @endif

                                @if (Auth::user()->hasRole('admin'))
                                    <form action="{{ route('admin.orders.addNote', $order) }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <textarea name="notes" class="form-control" rows="3" placeholder="Tambahkan catatan untuk pesanan ini...">{{ $order->notes }}</textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-save"></i> Simpan Catatan
                                        </button>
                                    </form>
                                @else
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle"></i> Hanya admin yang dapat menambah/mengubah catatan
                                        pesanan
                                    </small>
                                @endif
                            </div>
                        </div>

                        <!-- Order Actions -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Aksi</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-grid gap-2">
                                    @if (Auth::user()->hasRole('admin'))
                                        @if ($order->status == 'cancelled')
                                            <form action="{{ route('admin.orders.destroy', $order) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger w-100">
                                                    <i class="fas fa-trash"></i> Hapus Pesanan
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Hanya admin yang dapat melakukan aksi pada
                                            pesanan
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .img-thumbnail {
            border: 1px solid #dee2e6;
        }

        .badge {
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Badge yang lebih besar untuk halaman detail */
        .badge.p-2 {}

        /* Warna badge yang konsisten */
        .badge.bg-warning {
            background-color: #ffc107 !important;
            color: #000 !important;
        }

        .badge.bg-info {
            background-color: #17a2b8 !important;
        }

        .badge.bg-primary {
            background-color: #007bff !important;
        }

        .badge.bg-success {
            background-color: #28a745 !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
        }

        .card-header h5 {
            color: #495057;
        }

        .table th {
            border-top: none;
            font-weight: 600;
            color: #6c757d;
            font-size: 0.875rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Auto-save notes on change
            $('textarea[name="notes"]').on('blur', function() {
                if ($(this).data('original') !== $(this).val()) {
                    $(this).closest('form').submit();
                }
            });

            // Store original value
            $('textarea[name="notes"]').each(function() {
                $(this).data('original', $(this).val());
            });
        });
    </script>
@endpush
