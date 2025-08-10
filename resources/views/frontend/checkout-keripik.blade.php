@extends('layouts.app')

@section('title', 'Checkout - Keripik Pisang Cinangka')

@section('content')
    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Keranjang</a></li>
                        <li class="breadcrumb-item active">Checkout</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-shipping-fast"></i> Informasi Pengiriman</h5>
                    </div>
                    <div class="card-body">
                        <form id="checkout-form" action="{{ route('checkout.process') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <!-- Info Customer -->
                            <div class="mb-4">
                                <h6>Data Pelanggan</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_name" class="form-label">Nama Lengkap *</label>
                                            <input type="text" class="form-control" id="customer_name"
                                                name="customer_name"
                                                value="{{ old('customer_name', auth()->user()->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="customer_phone" class="form-label">No. WhatsApp *</label>
                                            <input type="tel" class="form-control" id="customer_phone"
                                                name="customer_phone" value="{{ old('customer_phone') }}"
                                                placeholder="08xxxxxxxxxx" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_address" class="form-label">Alamat Lengkap *</label>
                                    <textarea class="form-control" id="customer_address" name="customer_address" rows="3"
                                        placeholder="Jl. Nama Jalan No. XX, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten" required>{{ old('customer_address') }}</textarea>
                                    <small class="text-muted">Masukkan alamat lengkap untuk pengiriman keripik
                                        pisang</small>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <h6><i class="fas fa-credit-card"></i> Metode Pembayaran</h6>
                                <div class="form-check">
                                    {{-- SIBAGUS MENGUBAH "value" INI --}}
                                    <input class="form-check-input" type="radio" name="payment_method" id="transfer"
                                        value="bank_transfer" checked>
                                    <label class="form-check-label" for="transfer">
                                        <strong>Transfer E-Wallet DANA</strong>
                                        <small class="d-block text-muted">Transfer ke rekening yang tersedia</small>
                                    </label>
                                </div>
                            </div>

                            <!-- Courier Selection -->
                            <div class="mb-4">
                                <h6><i class="fas fa-truck"></i> Pilih Kurir Pengiriman *</h6>
                                <select class="form-select" name="courier_service" id="courier_service" required>
                                    <option value="">-- Pilih Kurir --</option>
                                    <option value="jne">JNE</option>
                                    <option value="pos">Pos Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="jnt">J&T Express</option>
                                    <option value="sicepat">SiCepat</option>
                                    <option value="anteraja">AnterAja</option>
                                    <option value="gosend">GoSend</option>
                                    <option value="grab">GrabExpress</option>
                                </select>
                                <small class="text-muted">Pilih kurir yang Anda inginkan untuk pengiriman pesanan</small>
                            </div>

                            <!-- Bank Account Info -->
                            <div class="mb-4">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title"><i class="fas fa-university"></i> Informasi Rekening</h6>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Bank:</strong> {{ $bankAccount['bank_name'] }}</p>
                                                <p class="mb-1"><strong>No. Rekening:</strong>
                                                    {{ $bankAccount['account_number'] }}</p>
                                                <p class="mb-0"><strong>Nama:</strong> {{ $bankAccount['account_name'] }}
                                                </p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Total Transfer:</strong></p>
                                                <h4 class="text-primary">Rp {{ number_format((float) $total, 0, ',', '.') }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Payment Proof -->
                            <div class="mb-4">
                                <h6><i class="fas fa-upload"></i> Upload Bukti Pembayaran *</h6>
                                <div class="mb-3">
                                    <input type="file" class="form-control" id="payment_proof" name="payment_proof"
                                        accept="image/jpeg,image/png,image/jpg" required>
                                    <small class="text-muted">Upload foto bukti transfer (JPG, PNG, max 2MB)</small>
                                </div>
                                <div class="alert alert-info">
                                    <small>
                                        <strong>Petunjuk:</strong><br>
                                        1. Transfer sesuai nominal total di atas<br>
                                        2. Ambil screenshot/foto bukti transfer<br>
                                        3. Upload bukti transfer pada form ini<br>
                                        4. Pesanan akan diproses setelah pembayaran dikonfirmasi
                                    </small>
                                </div>
                            </div>

                            <!-- Order Notes -->
                            <div class="mb-4">
                                <label for="order_notes" class="form-label">Catatan Pesanan (Opsional)</label>
                                <textarea class="form-control" id="order_notes" name="order_notes" rows="3"
                                    placeholder="Tambahkan catatan khusus untuk pesanan Anda...">{{ old('order_notes') }}</textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5><i class="fas fa-shopping-cart"></i> Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body">
                        @foreach ($cartItems as $item)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div class="d-flex">
                                    <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/60x60' }}"
                                        alt="{{ $item->product->name }}" class="img-thumbnail me-3"
                                        style="width: 60px; height: 60px; object-fit: cover;">
                                    <div>
                                        <h6 class="mb-0">{{ $item->product->name }}</h6>
                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                    </div>
                                </div>
                                <div class="text-end">
                                    @if ($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                        <div class="small text-muted text-decoration-line-through">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </div>
                                        <span class="fw-bold text-success">Rp
                                            {{ number_format($item->product->current_price * $item->quantity, 0, ',', '.') }}</span>
                                    @else
                                        <span class="fw-bold">Rp
                                            {{ number_format($item->product->current_price * $item->quantity, 0, ',', '.') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        <div class="border-top pt-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>Rp {{ number_format((float) $subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Ongkos Kirim:</span>
                                <span id="shipping-cost">
                                    @if ($shipping == 0)
                                        <span class="text-success">GRATIS</span>
                                    @else
                                        Rp {{ number_format((float) $shipping, 0, ',', '.') }}
                                    @endif
                                </span>
                            </div>
                            @if ($subtotal >= 100000)
                                <small class="text-success">🎉 Selamat! Anda mendapat gratis ongkir</small>
                            @else
                                <small class="text-muted">💡 Belanja min. Rp 100.000 untuk gratis ongkir</small>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between h5">
                                <strong>Total:</strong>
                                <strong>Rp {{ number_format((float) $total, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <button type="submit" form="checkout-form" class="btn btn-success btn-lg w-100 mt-3">
                            <i class="fas fa-credit-card"></i> Buat Pesanan
                        </button>

                        <div class="text-center mt-3">
                            <small class="text-muted">
                                <i class="fas fa-shield-alt"></i> Pembayaran aman dan terpercaya
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Info Keripik -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="fas fa-info-circle"></i> Tentang Keripik Pisang Cinangka</h6>
                        <small class="text-muted">
                            Keripik pisang premium dari Cinangka, Banten. Dibuat dari pisang pilihan dengan proses
                            tradisional untuk menghasilkan keripik yang renyah dan gurih.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Preview image upload
            $('#payment_proof').change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // You can add image preview here if needed
                        console.log('Image uploaded:', file.name);
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Form validation
            $('#checkout-form').submit(function(e) {
                const paymentProof = $('#payment_proof')[0].files[0];
                if (!paymentProof) {
                    e.preventDefault();
                    alert('Harap upload bukti pembayaran terlebih dahulu!');
                    return false;
                }

                // Check file size (2MB max)
                if (paymentProof.size > 2 * 1024 * 1024) {
                    e.preventDefault();
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    return false;
                }

                return true;
            });
        });
    </script>
@endpush
