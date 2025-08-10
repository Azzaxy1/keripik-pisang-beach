@extends('layouts.app')

@section('title', 'Detail Pesanan - ' . $order->order_number)

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2>Pesanan #{{ $order->order_number }}</h2>
                        <p class="text-muted mb-0">Dipesan pada {{ $order->created_at->format('d M Y') }} pukul
                            {{ $order->created_at->format('H:i') }}</p>
                    </div>
                    {{-- buat teks di tengah --}}
                    <div style="width: 350px;" class="alert mb-0 py-2 px-3 d-flex justify-content-center align-items-center
                        @php
$badgeColors = [
                                'pending' => 'alert-warning',
                                'confirmed' => 'alert-info',
                                'processing' => 'alert-primary',
                                'shipped' => 'alert-info',
                                'delivered' => 'alert-success',
                                'completed' => 'alert-dark',
                                'cancelled' => 'alert-danger',
                                'refunded' => 'alert-secondary'
                            ];
                            echo $badgeColors[$order->status] ?? 'alert-secondary'; @endphp">
                        @php
                            $statusTranslations = [
                                'pending' => 'Menunggu',
                                'confirmed' => 'Dikonfirmasi',
                                'processing' => 'Diproses',
                                'shipped' => 'Dikirim',
                                'delivered' => 'Diterima',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                'refunded' => 'Dikembalikan',
                            ];
                            $statusIcons = [
                                'pending' => 'fas fa-clock',
                                'confirmed' => 'fas fa-check',
                                'processing' => 'fas fa-cogs',
                                'shipped' => 'fas fa-shipping-fast',
                                'delivered' => 'fas fa-check-circle',
                                'completed' => 'fas fa-check-double',
                                'cancelled' => 'fas fa-times-circle',
                                'refunded' => 'fas fa-undo',
                            ];
                        @endphp
                        <i class="{{ $statusIcons[$order->status] ?? 'fas fa-info-circle' }} me-2"></i>
                        <strong>{{ $statusTranslations[$order->status] ?? ucfirst($order->status) }}</strong>
                    </div>
                </div>

                <div class="row">
                    <!-- Order Items -->
                    <div class="col-lg-8">
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
                                                            <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/60x60' }}"
                                                                alt="{{ $item->product->name }}" class="img-thumbnail me-3"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                            <div>
                                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                                <small class="text-muted">SKU:
                                                                    {{ $item->product->sku }}</small>
                                                            </div>
                                                        </div>
                                                    </td>
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

                        <!-- Order Status Timeline -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Status Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <div class="timeline">
                                    <div
                                        class="timeline-item {{ in_array($order->status, ['pending', 'confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div class="timeline-marker bg-success"></div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                <i class="fas fa-receipt me-1"></i>Pesanan Diterima
                                            </h6>
                                            <p class="timeline-description">
                                                @if ($order->status === 'pending')
                                                    <div class="alert alert-warning mb-0 py-2">
                                                        <i class="fas fa-clock me-2"></i>
                                                        <strong>Status Saat Ini: Menunggu Konfirmasi</strong>
                                                    </div>
                                                @else
                                                    Pesanan Anda telah berhasil diterima
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="timeline-item {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                <i class="fas fa-check me-1"></i>Pesanan Dikonfirmasi
                                            </h6>
                                            <p class="timeline-description">
                                                @if ($order->status === 'confirmed')
                                                    <div class="alert alert-info mb-0 py-2">
                                                        <i class="fas fa-check me-2"></i>
                                                        <strong>Status Saat Ini: Pesanan Dikonfirmasi</strong>
                                                    </div>
                                                @else
                                                    Pesanan Anda telah dikonfirmasi
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                <i class="fas fa-cogs me-1"></i>Sedang Diproses
                                            </h6>
                                            <p class="timeline-description">
                                                @if ($order->status === 'processing')
                                                    <div class="alert alert-primary mb-0 py-2">
                                                        <i class="fas fa-cogs me-2"></i>
                                                        <strong>Status Saat Ini: Sedang Diproses</strong>
                                                    </div>
                                                @else
                                                    Pesanan Anda sedang disiapkan
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div
                                        class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                <i class="fas fa-shipping-fast me-1"></i>Dikirim
                                            </h6>
                                            <p class="timeline-description">
                                                @if ($order->status === 'shipped')
                                                    <div class="alert alert-info mb-0 py-2">
                                                        <i class="fas fa-shipping-fast me-2"></i>
                                                        <strong>Status Saat Ini: Pesanan Sedang Dikirim</strong>
                                                    </div>
                                                @else
                                                    Pesanan Anda sedang dalam perjalanan
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    <div class="timeline-item {{ $order->status === 'delivered' ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ $order->status === 'delivered' ? 'bg-success' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                <i class="fas fa-check-circle me-1"></i>Diterima
                                            </h6>
                                            <p class="timeline-description">
                                                @if ($order->status === 'delivered')
                                                    <div class="alert alert-success mb-0 py-2">
                                                        <i class="fas fa-check-circle me-2"></i>
                                                        <strong>Status Saat Ini: Pesanan Telah Diterima</strong>
                                                    </div>
                                                @else
                                                    Pesanan telah diterima oleh pelanggan
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="timeline-item {{ $order->status === 'completed' ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ $order->status === 'completed' ? 'bg-success' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">
                                                <i class="fas fa-check-circle me-1"></i>Selesai
                                            </h6>
                                            <p class="timeline-description">
                                                @if ($order->status === 'completed')
                                                    <div class="alert alert-success mb-0 py-2">
                                                        <i class="fas fa-check-double me-2"></i>
                                                        <strong>Status Saat Ini: Pesanan Telah Selesai</strong>
                                                    </div>
                                                @else
                                                    Pesanan telah selesai dan ditutup
                                                @endif
                                            </p>
                                        </div>
                                    </div>

                                    @if ($order->status === 'cancelled')
                                        <div class="timeline-item completed">
                                            <div class="timeline-marker bg-danger"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">
                                                    <i class="fas fa-times-circle me-1"></i>Dibatalkan
                                                </h6>
                                                <p class="timeline-description">
                                                    <div class="alert alert-danger mb-0 py-2">
                                                        <i class="fas fa-times-circle me-2"></i>
                                                        <strong>Status Saat Ini: Pesanan Dibatalkan</strong>
                                                    </div>
                                                </p>
                                            </div>
                                        </div>
                                    @endif

                                    @if ($order->status === 'refunded')
                                        <div class="timeline-item completed">
                                            <div class="timeline-marker bg-secondary"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">
                                                    <i class="fas fa-undo me-1"></i>Dikembalikan
                                                </h6>
                                                <p class="timeline-description">
                                                    <div class="alert alert-secondary mb-0 py-2">
                                                        <i class="fas fa-undo me-2"></i>
                                                        <strong>Status Saat Ini: Pesanan Dikembalikan</strong>
                                                    </div>
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
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
                                <div class="d-flex justify-content-between fw-bold">
                                    <span>Total:</span>
                                    <span>Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</span>
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
                                        $shippingAddr = $order->shipping_address;
                                    @endphp
                                    @if ($shippingAddr)
                                        <div class="address-details">
                                            <p class="mb-2"><strong>Nama Pelanggan:</strong>
                                                {{ $shippingAddr['name'] ?? '' }}</p>
                                            <p class="mb-1"><strong>Alamat: </strong>{{ $shippingAddr['address'] ?? '' }}
                                            </p>
                                            @if (isset($shippingAddr['address_line_2']) && !empty($shippingAddr['address_line_2']))
                                                <p class="mb-1">{{ $shippingAddr['address_line_2'] }}</p>
                                            @endif
                                            @if (isset($shippingAddr['phone']) && !empty($shippingAddr['phone']))
                                                <p class="mb-0">
                                                    <strong>Telepon:</strong> {{ $shippingAddr['phone'] }}
                                                </p>
                                            @endif
                                        </div>
                                    @else
                                        <p class="text-muted">Alamat tidak tersedia</p>
                                    @endif
                                @else
                                    <p class="text-muted">Alamat tidak tersedia</p>
                                @endif
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        @if ($order->courier_service || $order->tracking_number)
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Informasi Pengiriman</h5>
                                </div>
                                <div class="card-body">
                                    @if ($order->courier_service)
                                        <div class="mb-2">
                                            <strong>Kurir:</strong>
                                            @php
                                                $courierNames = [
                                                    'jne' => 'JNE',
                                                    'pos' => 'Pos Indonesia',
                                                    'tiki' => 'TIKI',
                                                    'jnt' => 'J&T Express',
                                                    'sicepat' => 'SiCepat',
                                                    'anteraja' => 'AnterAja',
                                                    'gosend' => 'GoSend',
                                                    'grab' => 'GrabExpress',
                                                ];
                                            @endphp
                                            <span
                                                class="badge bg-info">{{ $courierNames[$order->courier_service] ?? $order->courier_service }}</span>
                                        </div>
                                    @endif
                                    @if ($order->tracking_number)
                                        <div class="mb-2">
                                            <strong>No. Resi:</strong>
                                            <code>{{ $order->tracking_number }}</code>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <!-- Payment Information -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Informasi Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Status Pembayaran:</strong>
                                    @php
                                        $paymentStatusTranslations = [
                                            'pending' => 'Menunggu',
                                            'paid' => 'Diterima',
                                            'failed' => 'Gagal',
                                            'refunded' => 'Dikembalikan',
                                        ];
                                        $paymentStatusColors = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'failed' => 'danger',
                                            'refunded' => 'info',
                                        ];
                                    @endphp
                                    <span
                                        class="badge bg-{{ $paymentStatusColors[$order->payment_status] ?? 'secondary' }}">
                                        {{ $paymentStatusTranslations[$order->payment_status] ?? ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                <div class="mb-2">
                                    <strong>Bukti Pembayaran:</strong>
                                    @if ($order->payment_proof)
                                        <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank"
                                            class="text-decoration-none">
                                            <i class="fas fa-file-download me-1"></i> Unduh Bukti Pembayaran
                                        </a>
                                    @else
                                        <span class="text-muted">Bukti pembayaran belum diunggah</span>
                                    @endif
                                </div>
                                @if ($order->notes)
                                    <div class="mt-3">
                                        <strong>Catatan Pesanan:</strong>
                                        <p class="text-muted mb-0">{{ $order->notes }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Pesanan
                            </a>

                            @if ($order->getAttribute('status') === 'delivered' && !$order->getAttribute('completed_at'))
                                <form action="{{ route('orders.markAsCompleted', $order) }}" method="POST"
                                    onsubmit="return confirm('Apakah Anda yakin telah menerima pesanan ini dengan baik?')">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check-circle me-1"></i> Pesanan Selesai
                                    </button>
                                </form>
                            @endif

                            @if ($order->getAttribute('completed_at'))
                                <div class="alert alert-success">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Pesanan ditandai selesai pada
                                    {{ $order->getAttribute('completed_at')->format('d M Y H:i') }}
                                </div>
                            @endif

                            @if (in_array($order->status, ['delivered', 'shipped', 'completed']))
                                <a href="{{ route('orders.invoice', $order) }}" class="btn btn-outline-success">
                                    <i class="fas fa-download me-1"></i> Unduh Invoice
                                </a>
                            @endif

                            @if (in_array($order->status, ['pending', 'confirmed']))
                                <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger w-100"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                        <i class="fas fa-times me-1"></i> Batalkan Pesanan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .timeline {
                position: relative;
                padding-left: 30px;
            }

            .timeline::before {
                content: '';
                position: absolute;
                left: 15px;
                top: 0;
                bottom: 0;
                width: 2px;
                background-color: #e9ecef;
            }

            .timeline-item {
                position: relative;
                margin-bottom: 30px;
            }

            .timeline-marker {
                position: absolute;
                left: -22px;
                top: 5px;
                width: 12px;
                height: 12px;
                border-radius: 50%;
                border: 2px solid #fff;
                box-shadow: 0 0 0 2px #e9ecef;
            }

            .timeline-item.completed .timeline-marker {
                box-shadow: 0 0 0 2px #28a745;
            }

            .timeline-title {
                font-size: 0.9rem;
                font-weight: 600;
                margin-bottom: 5px;
            }

            .timeline-description {
                font-size: 0.8rem;
                color: #6c757d;
                margin-bottom: 0;
            }

            .address-details p {
                margin-bottom: 0.25rem;
                line-height: 1.4;
            }
        </style>
    @endpush
@endsection
