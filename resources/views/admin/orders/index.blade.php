@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Header dengan filter dan stats -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Orders Management</h2>
                    {{-- <div class="d-flex gap-2">
                        <div class="btn-group">
                            <button class="btn btn-outline-primary btn-sm" onclick="filterOrders('all')">Semua</button>
                            <button class="btn btn-outline-warning btn-sm" onclick="filterOrders('pending')">Menunggu</button>
                            <button class="btn btn-outline-info btn-sm"
                                onclick="filterOrders('processing')">Diproses</button>
                            <button class="btn btn-outline-primary btn-sm"
                                onclick="filterOrders('shipped')">Dikirim</button>
                            <button class="btn btn-outline-success btn-sm"
                                onclick="filterOrders('delivered')">Diterima</button>
                            <button class="btn btn-outline-danger btn-sm"
                                onclick="filterOrders('cancelled')">Dibatalkan</button>
                        </div>
                    </div> --}}
                </div>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Statistik dan Tombol Cetak -->
                <div class="row mb-4">
                    <div class="col-lg-8">
                        <div class="row">
                            <!-- Total Pendapatan -->
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-money-bill-wave text-success"></i> Total Pendapatan</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <h4 class="text-success mb-1">Rp {{ number_format($totalKeseluruhan, 0, ',', '.') }}</h4>
                                                <small class="text-muted">Keseluruhan (Lunas)</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-primary mb-1">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</h4>
                                                <small class="text-muted">{{ now()->format('M Y') }} (Lunas)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Pesanan -->
                            <div class="col-md-6 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-shopping-cart text-info"></i> Total Pesanan</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <h4 class="text-success mb-1">{{ number_format($totalPesananKeseluruhan) }}</h4>
                                                <small class="text-muted">Keseluruhan (Aktif)</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-primary mb-1">{{ number_format($totalPesananBulanIni) }}</h4>
                                                <small class="text-muted">{{ now()->format('M Y') }} (Aktif)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Produk Terjual -->
                            <div class="col-md-12 mb-3">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-box text-warning"></i> Total Produk Terjual</h6>
                                        <div class="row">
                                            <div class="col-6">
                                                <h4 class="text-success mb-1">{{ number_format($totalProdukTerjualKeseluruhan) }} pcs</h4>
                                                <small class="text-muted">Keseluruhan (Lunas & Aktif)</small>
                                            </div>
                                            <div class="col-6">
                                                <h4 class="text-primary mb-1">{{ number_format($totalProdukTerjualBulanIni) }} pcs</h4>
                                                <small class="text-muted">{{ now()->format('F Y') }} (Lunas & Aktif)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-body text-center">
                                <h5 class="card-title"><i class="fas fa-file-pdf"></i> Laporan PDF</h5>
                                <form method="GET" action="{{ route('admin.orders.monthly-report') }}" class="mb-3">
                                    <div class="row">
                                        <div class="col-6">
                                            <select name="month" class="form-select form-select-sm" required>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>
                                                        {{ \Carbon\Carbon::create()->month($i)->format('F') }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <select name="year" class="form-select form-select-sm" required>
                                                @for($year = now()->year; $year >= 2020; $year--)
                                                    <option value="{{ $year }}">{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-danger mt-2">
                                        <i class="fas fa-download"></i> Download Laporan
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover" id="orders-table">
                                <thead>
                                    <tr>
                                        <th width="12%">No. Pesanan</th>
                                        <th width="15%">Pelanggan</th>
                                        <th width="10%">Tanggal</th>
                                        <th width="8%">Item</th>
                                        <th width="12%">Total</th>
                                        <th width="10%">Pembayaran</th>
                                        <th width="12%">Status</th>
                                        <th width="8%">Bukti</th>
                                        <th width="13%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr data-status="{{ $order->status }}" class="order-row">
                                            <td>
                                                <div>
                                                    <strong class="text-primary">#{{ $order->order_number }}</strong>
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($order->user->avatar)
                                                        <img src="{{ $order->user->avatar }}" alt="{{ $order->user->name }}"
                                                            class="rounded-circle me-2"
                                                            style="width: 30px; height: 30px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                                            style="width: 30px; height: 30px; font-size: 12px;">
                                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ Str::limit($order->user->name, 15) }}</div>
                                                        <small
                                                            class="text-muted">{{ Str::limit($order->user->email, 20) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    {{ $order->created_at->format('d M Y') }}
                                                    <br>
                                                    <small
                                                        class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-light text-dark">
                                                    {{ $order->items->sum('quantity') }} item
                                                </span>
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
                                                        'completed' => ['dark', 'Selesai'],
                                                        'cancelled' => ['danger', 'Dibatalkan'],
                                                    ];
                                                    $status = $statusBadges[$order->status] ?? ['secondary', 'Unknown'];
                                                @endphp
                                                <span class="badge bg-{{ $status[0] }}">
                                                    {{ $status[1] }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @if ($order->payment_proof)
                                                    <button class="btn btn-sm btn-outline-info"
                                                        onclick="showPaymentProof('{{ asset('storage/' . $order->payment_proof) }}')"
                                                        title="Lihat Bukti Pembayaran">
                                                        <i class="fas fa-image"></i>
                                                    </button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.orders.show', $order) }}"
                                                        class="btn btn-sm btn-outline-primary" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if (Auth::user()->hasRole('admin'))
                                                        @if ($order->payment_proof && $order->payment_status == 'pending')
                                                            <button class="btn btn-sm btn-outline-success"
                                                                onclick="approvePayment({{ $order->id }})"
                                                                title="Terima Pembayaran">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        @endif
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                data-bs-toggle="dropdown" title="Lainnya">
                                                                <i class="fas fa-ellipsis-v"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                {{-- <li><a class="dropdown-item"
                                                                        href="{{ route('admin.orders.print', $order) }}"
                                                                        target="_blank">
                                                                        <i class="fas fa-print me-2"></i>Cetak
                                                                    </a></li> --}}
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('admin.orders.invoice', $order) }}"
                                                                        target="_blank">
                                                                        <i class="fas fa-file-invoice me-2"></i>Invoice
                                                                    </a></li>
                                                                @if ($order->status == 'cancelled')
                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>
                                                                    <li><a class="dropdown-item text-danger" href="#"
                                                                            onclick="deleteOrder({{ $order->id }})">
                                                                            <i class="fas fa-trash me-2"></i>Hapus
                                                                        </a></li>
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    @else
                                                        {{-- Owner hanya bisa print dan invoice --}}
                                                        <div class="btn-group">
                                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                                data-bs-toggle="dropdown" title="Cetak">
                                                                <i class="fas fa-print"></i>
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('admin.orders.print', $order) }}"
                                                                        target="_blank">
                                                                        <i class="fas fa-print me-2"></i>Cetak
                                                                    </a></li>
                                                                <li><a class="dropdown-item"
                                                                        href="{{ route('admin.orders.invoice', $order) }}"
                                                                        target="_blank">
                                                                        <i class="fas fa-file-invoice me-2"></i>Invoice
                                                                    </a></li>
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Proof Modal -->
    <div class="modal fade" id="paymentProofModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="payment-proof-image" src="" alt="Bukti Pembayaran" class="img-fluid">
                </div>
                <div class="modal-footer">
                    <a id="download-proof" href="" download class="btn btn-success">
                        <i class="fas fa-download"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Confirmation Modal -->
    <div class="modal fade" id="statusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Update Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin mengubah status pesanan?</p>
                    <div id="status-details"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="confirm-status-update">Update Status</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .order-row:hover {
            background-color: #f8f9fa;
        }

        .status-select {
            font-size: 0.875rem;
        }

        .btn-group .btn {
            border-radius: 0.25rem;
        }

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

        /* Highlight untuk status aktif di filter */
        .btn-group .btn.active {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#orders-table').DataTable({
                "pageLength": 25,
                "ordering": true,
                "searching": true,
                "order": [
                    [2, "desc"]
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [7, 8]
                }]
            });
        });

        function filterOrders(status) {
            var table = $('#orders-table').DataTable();
            if (status === 'all') {
                table.column(6).search('').draw();
            } else {
                table.column(6).search(status).draw();
            }

            // Update button states - reset semua button terlebih dahulu
            $('.btn-group button').each(function() {
                var $btn = $(this);
                var originalClass = $btn.data('original-class') || 'btn-outline-primary';
                $btn.removeClass('btn-primary btn-secondary btn-success btn-warning btn-info btn-danger active')
                    .addClass(originalClass);
            });

            // Set button yang aktif
            $(event.target).removeClass(
                    'btn-outline-primary btn-outline-secondary btn-outline-success btn-outline-warning btn-outline-info btn-outline-danger'
                )
                .addClass('btn-primary active');
        }

        function showPaymentProof(imageSrc) {
            $('#payment-proof-image').attr('src', imageSrc);
            $('#download-proof').attr('href', imageSrc);
            $('#paymentProofModal').modal('show');
        }

        let pendingOrderId = null;
        let pendingStatus = null;

        function updateOrderStatus(orderId, newStatus) {
            pendingOrderId = orderId;
            pendingStatus = newStatus;

            const statusNames = {
                'pending': 'Menunggu',
                'confirmed': 'Dikonfirmasi',
                'processing': 'Diproses',
                'shipped': 'Dikirim',
                'delivered': 'Diterima',
                'cancelled': 'Dibatalkan'
            };

            $('#status-details').html(`
        <strong>No. Pesanan:</strong> #${orderId}<br>
        <strong>Status Baru:</strong> ${statusNames[newStatus]}
    `);

            $('#statusModal').modal('show');
        }

        $('#confirm-status-update').click(function() {
            if (pendingOrderId && pendingStatus) {
                $.ajax({
                    url: `/admin/orders/${pendingOrderId}/status`,
                    method: 'PATCH',
                    data: {
                        status: pendingStatus,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        $('#statusModal').modal('hide');
                        toastr.success('Status pesanan berhasil diperbarui');
                        setTimeout(() => location.reload(), 1000);
                    },
                    error: function(xhr) {
                        toastr.error('Error updating order status');
                        location.reload();
                    }
                });
            }
        });

        function approvePayment(orderId) {
            if (confirm('Apakah Anda yakin ingin menyetujui pembayaran ini?')) {
                $.ajax({
                    url: `/admin/orders/${orderId}/payment-status`,
                    method: 'PATCH',
                    data: {
                        payment_status: 'paid',
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success('Pembayaran telah disetujui');
                        location.reload();
                    },
                    error: function(xhr) {
                        toastr.error('Error approving payment');
                    }
                });
            }
        }

        function deleteOrder(orderId) {
            if (confirm('Apakah Anda yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.')) {
                $.ajax({
                    url: `/admin/orders/${orderId}`,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success('Pesanan berhasil dihapus');
                        location.reload();
                    },
                    error: function(xhr) {
                        toastr.error('Error deleting order');
                    }
                });
            }
        }
    </script>
@endpush
