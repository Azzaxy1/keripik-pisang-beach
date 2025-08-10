<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pemesanan Bulanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f3b841;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #333;
            margin: 0;
        }
        .header p {
            color: #666;
            margin: 5px 0 0 0;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .summary-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .summary-item h3 {
            margin: 0 0 5px 0;
            color: #495057;
        }
        .summary-item .value {
            font-size: 24px;
            font-weight: bold;
            color: #f3b841;
        }
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .orders-table th,
        .orders-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: left;
            font-size: 12px;
        }
        .orders-table th {
            background-color: #f3b841;
            color: white;
            font-weight: bold;
        }
        .orders-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .status-badge {
            padding: 3px 8px;
            border-radius: 4px;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }
        .status-pending { background-color: #ffc107; }
        .status-processing { background-color: #17a2b8; }
        .status-shipped { background-color: #007bff; }
        .status-delivered { background-color: #28a745; }
        .status-completed { background-color: #6c757d; }
        .status-cancelled { background-color: #dc3545; }
        .footer {
            margin-top: 30px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }
        .total-row {
            background-color: #f3b841 !important;
            color: white;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Keripik Pisang Beach</h1>
        <p>Laporan Pemesanan Bulanan - {{ $monthName }} {{ $year }}</p>
        <p><small>(Hanya pesanan yang sudah dibayar dan tidak dibatalkan)</small></p>
    </div>
    {{-- <div class="header">
        <h1>Keripik Pisang Beach</h1>
        <p>Laporan Pemesanan Bulanan - {{ $monthName }} {{ $year }}</p>
        <p><small>(Hanya pesanan yang sudah dibayar)</small></p>
    </div> --}}

    <div class="summary">
        <div class="summary-item">
            <h3>Total Pesanan</h3>
            <div class="value">{{ $totalOrders }}</div>
        </div>
        <div class="summary-item">
            <h3>Total Pendapatan</h3>
            <div class="value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <h3>Produk Terjual</h3>
            <div class="value">{{ number_format($totalProductsSold) }} pcs</div>
        </div>
    </div>

    {{-- <div class="summary">
        <div class="summary-item">
            <h3>Rata-rata per Pesanan</h3>
            <div class="value">Rp {{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : '0' }}</div>
        </div>
        <div class="summary-item">
            <h3>Rata-rata Produk per Pesanan</h3>
            <div class="value">{{ $totalOrders > 0 ? number_format($totalProductsSold / $totalOrders, 1) : '0' }} pcs</div>
        </div>
        <div class="summary-item">
            <h3>Pendapatan per Produk</h3>
            <div class="value">Rp {{ $totalProductsSold > 0 ? number_format($totalRevenue / $totalProductsSold, 0, ',', '.') : '0' }}</div>
        </div>
    </div> --}}

    <h2>Detail Pemesanan</h2>
    <table class="orders-table">
        <thead>
            <tr>
                <th>No</th>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Status</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    {{ $order->user->name ?? 'N/A' }}<br>
                    <small>{{ $order->user->email ?? 'N/A' }}</small>
                </td>
                <td>{{ $order->items->sum('quantity') }} pcs</td>
                <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                <td>
                    @php
                        $statusLabels = [
                            'pending' => 'Menunggu',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'delivered' => 'Diterima',
                            'completed' => 'Selesai',
                            'cancelled' => 'Dibatalkan',
                        ];
                    @endphp
                    <span class="status-badge status-{{ $order->status }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </td>
                <td>
                    @php
                        $paymentLabels = [
                            'pending' => 'Menunggu',
                            'paid' => 'Lunas',
                            'failed' => 'Gagal',
                            'refunded' => 'Dikembalikan',
                        ];
                    @endphp
                    {{ $paymentLabels[$order->payment_status] ?? $order->payment_status }}
                </td>
            </tr>
            @endforeach
            
            @if($orders->count() > 0)
            <tr class="total-row">
                <td colspan="4"><strong>TOTAL</strong></td>
                <td><strong>{{ number_format($totalProductsSold) }} pcs</strong></td>
                <td><strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong></td>
                <td colspan="2"></td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan digenerate pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>© {{ date('Y') }} Keripik Pisang Beach - Semua hak dilindungi</p>
    </div>
</body>
</html>
