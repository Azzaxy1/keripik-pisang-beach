@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h3 mb-0">Dashboard</h1>
                    <small class="text-muted">Selamat datang kembali, {{ Auth::user()->name }}!</small>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-primary border-4 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Produk</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-success border-4 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Pesanan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalOrders }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-info border-4 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pengguna</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-start border-warning border-4 shadow">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Pendapatan
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">Rp
                                    {{ number_format($totalRevenue, 0, ',', '.') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Ringkasan Penjualan Bulanan</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="salesChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            @if (Auth::user()->hasRole('owner'))
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-info">
                                    <i class="fas fa-list"></i> Lihat Semua Pesanan
                                </a>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-warning">
                                    <i class="fas fa-box"></i> Lihat Semua Produk
                                </a>
                            @elseif(Auth::user()->hasRole('admin'))
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Produk Baru
                                </a>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Tambah Kategori Baru
                                </a>
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-info">
                                    <i class="fas fa-list"></i> Lihat Semua Pesanan
                                </a>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-warning">
                                    <i class="fas fa-box"></i> Kelola Produk
                                </a>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-users"></i> Manajemen Pengguna
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Recent Orders -->
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Pesanan Terbaru</h6>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        @if ($recentOrders->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped table-hover" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>No. Pesanan</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th>Pembayaran</th>
                                            <th>Status</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentOrders as $order)
                                            <tr>
                                                <td>
                                                    <div>
                                                        <strong class="text-primary">
                                                            <a href="{{ route('admin.orders.show', $order) }}"
                                                                class="text-decoration-none">
                                                                #{{ $order->order_number }}
                                                            </a>
                                                        </strong>
                                                        <br>
                                                        <small
                                                            class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if ($order->user->avatar)
                                                            <img src="{{ $order->user->avatar }}"
                                                                alt="{{ $order->user->name }}" class="rounded-circle me-2"
                                                                style="width: 30px; height: 30px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                                style="width: 30px; height: 30px; font-size: 12px;">
                                                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="fw-bold">{{ Str::limit($order->user->name, 15) }}
                                                            </div>
                                                            <small
                                                                class="text-muted">{{ Str::limit($order->user->email, 20) }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>Rp
                                                            {{ number_format((float) $order->total_amount, 0, ',', '.') }}</strong>
                                                        @if ($order->discount_amount > 0)
                                                            <br>
                                                            <small class="text-success">-Rp
                                                                {{ number_format((float) $order->discount_amount, 0, ',', '.') }}</small>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @php
                                                        $paymentBadges = [
                                                            'pending' => ['warning', 'Menunggu'],
                                                            'paid' => ['success', 'Lunas'],
                                                            'failed' => ['danger', 'Gagal'],
                                                            'refunded' => ['info', 'Dikembalikan'],
                                                        ];
                                                        $payment = $paymentBadges[$order->payment_status] ?? [
                                                            'secondary',
                                                            'Unknown',
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $payment[0] }}">
                                                        {{ $payment[1] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @php
                                                        $statusBadges = [
                                                            'pending' => ['warning', 'Menunggu'],
                                                            'processing' => ['info', 'Diproses'],
                                                            'shipped' => ['primary', 'Dikirim'],
                                                            'delivered' => ['success', 'Diterima'],
                                                            'cancelled' => ['danger', 'Dibatalkan'],
                                                        ];
                                                        $status = $statusBadges[$order->status] ?? [
                                                            'secondary',
                                                            'Unknown',
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $status[0] }}">
                                                        {{ $status[1] }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $order->created_at->format('d M Y') }}
                                                        <br>
                                                        <small
                                                            class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-center text-muted">Tidak ada pesanan terbaru ditemukan.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Top Products -->
            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Produk Terlaris</h6>
                    </div>
                    <div class="card-body">
                        @if ($topProducts->count() > 0)
                            @foreach ($topProducts->take(5) as $product)
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $product->featured_image }}" alt="{{ $product->name }}"
                                        class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ Str::limit($product->name, 30) }}</h6>
                                        <small class="text-muted">{{ $product->order_items_count }} terjual</small>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-center text-muted">Tidak ada data penjualan tersedia.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sales Chart
        const ctx = document.getElementById('salesChart').getContext('2d');
        const monthlySales = @json($monthlySales);

        const monthNames = [
            'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
            'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
        ];

        const salesData = Array(12).fill(0);
        monthlySales.forEach(sale => {
            salesData[sale.month - 1] = sale.total;
        });

        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: monthNames,
                datasets: [{
                    label: 'Penjualan (Rp)',
                    data: salesData,
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endpush

@push('styles')
    <style>
        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #dee2e6;
        }

        .badge {
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 0.5em 0.75em;
        }

        /* Warna badge status yang konsisten */
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

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>
@endpush
