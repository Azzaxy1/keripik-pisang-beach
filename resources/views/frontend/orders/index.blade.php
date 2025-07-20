@extends('layouts.app')

@section('title', 'Pesanan Saya')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Pesanan Saya</h2>

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                @if ($orders->count() > 0)
                    <div class="row">
                        @foreach ($orders as $order)
                            <div class="col-12 mb-4">
                                <div class="card">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Pesanan #{{ $order->order_number }}</h6> <small
                                                class="text-muted">Dipesan pada
                                                {{ $order->created_at->format('d M Y') }} pukul
                                                {{ $order->created_at->format('H:i') }}</small>
                                        </div>
                                        <div class="text-end">
                                            @php
                                                $statusTranslations = [
                                                    'pending' => 'Menunggu',
                                                    'confirmed' => 'Dikonfirmasi',
                                                    'processing' => 'Diproses',
                                                    'shipped' => 'Dikirim',
                                                    'delivered' => 'Diterima',
                                                    'cancelled' => 'Dibatalkan',
                                                    'completed' => 'Selesai',
                                                    'refunded' => 'Dikembalikan',
                                                ];

                                                $badgeColors = [
                                                    'pending' => 'warning',
                                                    'confirmed' => 'info',
                                                    'processing' => 'primary',
                                                    'shipped' => 'info',
                                                    'delivered' => 'success',
                                                    'cancelled' => 'danger',
                                                    'completed' => 'dark',
                                                    'refunded' => 'secondary',
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $badgeColors[$order->status] ?? 'secondary' }} p-2">
                                                {{ $statusTranslations[$order->status] ?? ucfirst($order->status) }}
                                            </span>
                                            <div class="mt-1">
                                                <strong>Rp
                                                    {{ number_format((float) $order->total_amount, 0, ',', '.') }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-8">
                                                <h6>Item Pesanan ({{ $order->items->count() }})</h6>
                                                <div class="row">
                                                    @foreach ($order->items->take(3) as $item)
                                                        <div class="col-auto">
                                                            <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/60x60' }}"
                                                                alt="{{ $item->product->name }}" class="img-thumbnail"
                                                                style="width: 60px; height: 60px; object-fit: cover;">
                                                        </div>
                                                    @endforeach
                                                    @if ($order->items->count() > 3)
                                                        <div class="col-auto d-flex align-items-center">
                                                            <span class="text-muted">+{{ $order->items->count() - 3 }}
                                                                lainnya</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <a href="{{ route('orders.show', $order) }}"
                                                        class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye me-1"></i>Lihat Detail
                                                    </a>
                                                    @if (in_array($order->status, ['delivered', 'shipped']))
                                                        <a href="{{ route('orders.invoice', $order) }}"
                                                            class="btn btn-outline-success btn-sm">
                                                            <i class="fas fa-download me-1"></i>Invoice
                                                        </a>
                                                    @endif
                                                    @if (in_array($order->status, ['pending', 'confirmed']))
                                                        <form action="{{ route('orders.cancel', $order) }}" method="POST"
                                                            class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                                                <i class="fas fa-times me-1"></i>Batalkan
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $orders->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-bag fa-4x text-muted mb-4"></i>
                        <h4>Belum Ada Pesanan</h4>
                        <p class="text-muted mb-4">Anda belum pernah melakukan pemesanan.</p>
                        <a href="{{ route('shop') }}" class="btn btn-primary">Mulai Berbelanja</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
