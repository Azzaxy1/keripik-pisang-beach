<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
            line-height: 1.6;
        }

        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border: 1px solid #ddd;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 40px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }

        .company-info h1 {
            color: #007bff;
            margin: 0;
            font-size: 28px;
            font-weight: bold;
        }

        .company-info p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            color: #333;
            margin: 0;
            font-size: 24px;
        }

        .invoice-title p {
            margin: 5px 0;
            color: #666;
        }

        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .invoice-details .section {
            flex: 1;
            margin-right: 20px;
        }

        .invoice-details .section:last-child {
            margin-right: 0;
        }

        .invoice-details h4 {
            color: #007bff;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .invoice-details p {
            margin: 5px 0;
            font-size: 14px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .items-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }

        .items-table .text-right {
            text-align: right;
        }

        .items-table .text-center {
            text-align: center;
        }

        .invoice-summary {
            margin-left: auto;
            width: 300px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }

        .summary-row.total {
            border-top: 2px solid #007bff;
            border-bottom: 2px solid #007bff;
            font-weight: bold;
            font-size: 18px;
            color: #007bff;
        }

        .invoice-footer {
            margin-top: 50px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-delivered {
            background-color: #d4edda;
            color: #155724;
        }

        .status-shipped {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-processing {
            background-color: #d1ecf1;
            color: #0c5460;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                border: none;
                box-shadow: none;
            }

            .no-print {
                display: none;
            }
        }

        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .print-button:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Invoice
    </button>

    <div class="invoice-container">
        <!-- Header -->
        <div class="invoice-header">
            <div class="company-info">
                <h1>Keripik Pisang Beach</h1>
                <p>Jl. Pantai Indah No. 123, Cinangka, Banten</p>
                <p>Telp: 0812-3456-7890</p>
                <p>Email: info@keripikpisangbeach.com</p>
            </div>
            <div class="invoice-title">
                <h2>INVOICE</h2>
                <p><strong>#{{ $order->order_number }}</strong></p>
                <p>{{ $order->created_at->format('d M Y') }}</p>
                <span class="status-badge status-{{ $order->status }}">
                    @php
                        $statusTranslations = [
                            'pending' => 'Menunggu',
                            'confirmed' => 'Dikonfirmasi',
                            'processing' => 'Diproses',
                            'shipped' => 'Dikirim',
                            'delivered' => 'Diterima',
                            'cancelled' => 'Dibatalkan',
                            'refunded' => 'Dikembalikan',
                        ];
                    @endphp
                    {{ $statusTranslations[$order->status] ?? ucfirst($order->status) }}
                </span>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="invoice-details">
            {{-- <div class="section">
                <h4>Tagihan Kepada:</h4>
                <p><strong>{{ $order->user->name }}</strong></p>
                <p>{{ $order->user->email }}</p>
                @if ($order->user->phone)
                    <p>{{ $order->user->phone }}</p>
                @endif
            </div> --}}

            <div class="section">
                <h4>Alamat Pengiriman:</h4>
                @if ($order->shipping_address)
                    @php $addr = $order->shipping_address; @endphp
                    <p><strong>{{ $addr['name'] ?? $order->user->name }}</strong></p>
                    <p>{{ $addr['address'] ?? 'Alamat tidak tersedia' }}</p>
                    @if (isset($addr['address_line_2']) && !empty($addr['address_line_2']))
                        <p>{{ $addr['address_line_2'] }}</p>
                    @endif
                    @if (isset($addr['phone']) && !empty($addr['phone']))
                        <p>{{ $addr['phone'] }}</p>
                    @endif
                @else
                    <p>Alamat tidak tersedia</p>
                @endif
            </div>

            <div class="section">
                <h4>Detail Pembayaran:</h4>
                <p><strong>Metode:</strong> Transfer Bank</p>
                <p><strong>Status:</strong>
                    @php
                        $paymentStatusTranslations = [
                            'pending' => 'Menunggu',
                            'paid' => 'Lunas',
                            'failed' => 'Gagal',
                            'refunded' => 'Dikembalikan',
                        ];
                    @endphp
                    {{ $paymentStatusTranslations[$order->payment_status] ?? ucfirst($order->payment_status) }}
                </p>
                <p><strong>Tanggal:</strong> {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        <!-- Items Table -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 50%">Produk</th>
                    <th class="text-center" style="width: 10%">Qty</th>
                    <th class="text-right" style="width: 20%">Harga Satuan</th>
                    <th class="text-right" style="width: 20%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product->name }}</strong><br>
                            <small style="color: #666;">SKU: {{ $item->product->sku }}</small>
                            @if ($item->product->description)
                                <br><small
                                    style="color: #666;">{{ Str::limit($item->product->description, 50) }}</small>
                            @endif
                        </td>
                        <td class="text-center">{{ $item->quantity }}</td>
                        <td class="text-right">Rp {{ number_format((float) $item->price, 0, ',', '.') }}</td>
                        <td class="text-right">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Invoice Summary -->
        <div class="invoice-summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}</span>
            </div>

            @if ($order->tax_amount > 0)
                <div class="summary-row">
                    <span>Pajak:</span>
                    <span>Rp {{ number_format((float) $order->tax_amount, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="summary-row">
                <span>Ongkos Kirim:</span>
                <span>Rp {{ number_format((float) $order->shipping_amount, 0, ',', '.') }}</span>
            </div>

            @if ($order->discount_amount > 0)
                <div class="summary-row" style="color: #28a745;">
                    <span>Diskon:</span>
                    <span>-Rp {{ number_format((float) $order->discount_amount, 0, ',', '.') }}</span>
                </div>
            @endif

            <div class="summary-row total">
                <span>TOTAL:</span>
                <span>Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Notes -->
        @if ($order->notes)
            <div style="margin-top: 30px;">
                <h4 style="color: #007bff;">Catatan:</h4>
                <p style="background: #f8f9fa; padding: 15px; border-left: 4px solid #007bff; margin: 0;">
                    {{ $order->notes }}
                </p>
            </div>
        @endif

        <!-- Footer -->
        <div class="invoice-footer">
            <p><strong>Terima kasih atas kepercayaan Anda!</strong></p>
            <p>Invoice ini digenerate otomatis oleh sistem pada {{ now()->format('d M Y, H:i') }}</p>
            <p>Untuk pertanyaan mengenai invoice ini, silakan hubungi customer service kami.</p>
            <p style="margin-top: 20px;">
                <strong>Keripik Pisang Beach</strong> - Keripik Pisang Terbaik di Bali<br>
                Website: www.keripikpisangbeach.com | Instagram: @keripikpisangbeach
            </p>
        </div>
    </div>

    <script>
        // Auto print when opened in new window/tab
        if (window.location.search.includes('print=1')) {
            window.onload = function() {
                window.print();
            }
        }
    </script>
</body>

</html>
