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
                    <span
                        class="badge p-2
                        @php
$badgeColors = [
                                'pending' => 'bg-warning',
                                'confirmed' => 'bg-info',
                                'processing' => 'bg-primary',
                                'shipped' => 'bg-info',
                                'delivered' => 'bg-success',
                                'completed' => 'bg-dark',
                                'cancelled' => 'bg-danger',
                                'refunded' => 'bg-secondary'
                            ];
                            echo $badgeColors[$order->status] ?? 'bg-secondary'; @endphp">
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
                        @endphp
                        {{ $statusTranslations[$order->status] ?? ucfirst($order->status) }}
                    </span>
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
                                            <h6 class="timeline-title">Pesanan Diterima</h6>
                                            <p class="timeline-description">Pesanan Anda telah berhasil diterima</p>
                                        </div>
                                    </div>

                                    <div
                                        class="timeline-item {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ in_array($order->status, ['confirmed', 'processing', 'shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Pesanan Dikonfirmasi</h6>
                                            <p class="timeline-description">Pesanan Anda telah dikonfirmasi</p>
                                        </div>
                                    </div>

                                    <div
                                        class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Sedang Diproses</h6>
                                            <p class="timeline-description">Pesanan Anda sedang disiapkan</p>
                                        </div>
                                    </div>

                                    <div
                                        class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-success' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Dikirim</h6>
                                            <p class="timeline-description">Pesanan Anda sedang dalam perjalanan</p>
                                        </div>
                                    </div>

                                    <div class="timeline-item {{ $order->status === 'delivered' ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ $order->status === 'delivered' ? 'bg-primary' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Diterima</h6>
                                            <p class="timeline-description">
                                                @if ($order->status === 'delivered')
                                                    <span class="text-primary fw-bold">Status Saat Ini</span>
                                                @else
                                                    Pesanan telah diterima oleh pelanggan
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    <div class="timeline-item {{ $order->status === 'completed' ? 'completed' : '' }}">
                                        <div
                                            class="timeline-marker {{ $order->status === 'completed' ? 'bg-dark' : 'bg-secondary' }}">
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="timeline-title">Selesai</h6>
                                            <p class="timeline-description">
                                                @if ($order->status === 'completed')
                                                    <span class="text-dark fw-bold">Status Saat Ini</span>
                                                @else
                                                    Pesanan telah selesai dan ditutup
                                                @endif
                                            </p>
                                        </div>
                                    </div>
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
