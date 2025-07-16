@extends('layouts.app')

@section('title', 'Keranjang Belanja - Keripik Pisang Cinangka')

@push('styles')
    <style>
        .cart-item {
            transition: all 0.3s ease;
        }

        .cart-item:hover {
            background-color: #f8f9fa;
            border-radius: 8px;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .cart-summary-card {
            position: sticky;
            top: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Keranjang</li>
                    </ol>
                </nav>
                <h2 class="mb-4"><i class="fas fa-shopping-cart"></i> Keranjang Belanja</h2>
            </div>
        </div>

        @if ($cartItems->count() > 0)
            <div class="row">
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-list"></i> Item Keranjang
                                ({{ $cartItems->count() }})</h5>
                        </div>
                        <div class="card-body">
                            @foreach ($cartItems as $item)
                                <div class="d-flex align-items-center cart-item mb-3 pb-3 border-bottom"
                                    data-item-id="{{ $item->id }}">
                                    <div class="me-3">
                                        <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/80x80' }}"
                                            alt="{{ $item->product->name }}" class="rounded"
                                            style="width: 80px; height: 80px; object-fit: cover;">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product->name }}</h6>
                                        <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                                        <br>
                                        @if ($item->product->sale_price && $item->product->sale_price < $item->product->price)
                                            <small class="text-muted text-decoration-line-through">Harga: Rp
                                                {{ number_format((float) $item->product->price, 0, ',', '.') }}</small>
                                            <br>
                                            <small class="text-success"><strong>Sale: Rp
                                                    {{ number_format((float) $item->product->current_price, 0, ',', '.') }}</strong></small>
                                            <span class="badge bg-danger ms-1">{{ $item->product->discount_percentage }}%
                                                OFF</span>
                                        @else
                                            <small class="text-success">Harga: Rp
                                                {{ number_format((float) $item->product->current_price, 0, ',', '.') }}</small>
                                        @endif
                                    </div>
                                    <div class="mx-3">
                                        <label class="form-label small">Jumlah:</label>
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" class="form-control form-control-sm text-center"
                                                value="{{ $item->quantity }}" min="1"
                                                onchange="updateQuantity({{ $item->id }}, this.value)">
                                            <button class="btn btn-outline-secondary btn-sm" type="button"
                                                onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="mb-2">
                                            <strong class="item-subtotal">Rp
                                                {{ number_format((float) $item->subtotal, 0, ',', '.') }}</strong>
                                        </div>
                                        <button class="btn btn-sm btn-outline-danger"
                                            onclick="removeItem({{ $item->id }})" title="Hapus item">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach

                            <div class="text-center mt-3">
                                <button class="btn btn-outline-danger" onclick="clearCart()">
                                    <i class="fas fa-trash-alt"></i> Kosongkan Keranjang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card cart-summary-card">
                        <div class="card-header">
                            <h5 class="card-title mb-0"><i class="fas fa-calculator"></i> Ringkasan Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span id="cart-subtotal">Rp {{ number_format((float) $total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Ongkos Kirim:</span>
                                <span id="shipping-cost">
                                    @if ($total >= 100000)
                                        <span class="text-success">Gratis</span>
                                    @else
                                        <span>Rp {{ number_format(5000, 0, ',', '.') }}</span>
                                    @endif
                                </span>
                            </div>
                            @if ($total < 100000)
                                <small class="text-muted d-block mb-2">
                                    💡 Belanja min. Rp {{ number_format(100000, 0, ',', '.') }} untuk gratis ongkir
                                </small>
                            @else
                                <small class="text-success d-block mb-2">
                                    🎉 Selamat! Anda mendapat gratis ongkir
                                </small>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between mb-3">
                                <strong>Total:</strong>
                                <strong id="cart-total">
                                    Rp {{ number_format($total + ($total >= 100000 ? 0 : 5000), 0, ',', '.') }}
                                </strong>
                            </div>

                            @auth
                                <a href="{{ route('checkout.index') }}" class="btn btn-success w-100 mb-2">
                                    <i class="fas fa-credit-card"></i> Lanjut ke Checkout
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="btn w-100 mb-2"
                                    style="background-color: #f3b841; color: white; border: none;">
                                    <i class="fas fa-sign-in-alt"></i> Login untuk Checkout
                                </a>
                            @endauth

                            <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100">
                                <i class="fas fa-arrow-left"></i> Lanjut Belanja
                            </a>
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
                            <hr>
                            <small class="text-muted">
                                <i class="fas fa-shipping-fast"></i> Gratis ongkir untuk pembelian minimal Rp 100.000<br>
                                <i class="fas fa-phone"></i> Hubungi kami: 0812-3456-7890
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center">
                    <div class="py-5">
                        <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                        <h3>Keranjang Anda Kosong</h3>
                        <p class="text-muted mb-4">Sepertinya Anda belum menambahkan keripik pisang ke keranjang.</p>
                        <a href="{{ route('shop') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-shopping-bag"></i> Mulai Belanja Keripik
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        function updateQuantity(itemId, quantity) {
            if (quantity < 1) {
                removeItem(itemId);
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{ route('cart.update') }}', {
                cart_item_id: itemId,
                quantity: quantity
            }, function(response) {
                if (response.success) {
                    // Update the item subtotal
                    $('[data-item-id="' + itemId + '"] .item-subtotal').text('Rp ' + parseInt(response.subtotal)
                        .toLocaleString('id-ID'));

                    // Update cart totals
                    updateCartTotals(response.total);
                    updateCartCount();

                    // Show success message
                    if (typeof showToast === 'function') {
                        showToast('success', response.message);
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast('error', response.message);
                    } else {
                        alert(response.message);
                    }
                }
            }).fail(function() {
                if (typeof showToast === 'function') {
                    showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }

        function removeItem(itemId) {
            if (!confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{ route('cart.remove') }}', {
                cart_item_id: itemId
            }, function(response) {
                if (response.success) {
                    // Remove the item row
                    $('[data-item-id="' + itemId + '"]').fadeOut(300, function() {
                        $(this).remove();

                        // Check if cart is empty
                        if ($('.cart-item').length === 0) {
                            location.reload();
                        }
                    });

                    // Update cart totals
                    updateCartTotals(response.total);
                    updateCartCount();

                    if (typeof showToast === 'function') {
                        showToast('success', response.message);
                    }
                } else {
                    if (typeof showToast === 'function') {
                        showToast('error', response.message);
                    } else {
                        alert(response.message);
                    }
                }
            }).fail(function() {
                if (typeof showToast === 'function') {
                    showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }

        function clearCart() {
            if (!confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang?')) {
                return;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{ route('cart.clear') }}', function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    if (typeof showToast === 'function') {
                        showToast('error', response.message);
                    } else {
                        alert(response.message);
                    }
                }
            }).fail(function() {
                if (typeof showToast === 'function') {
                    showToast('error', 'Terjadi kesalahan. Silakan coba lagi.');
                } else {
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                }
            });
        }

        function updateCartTotals(subtotal) {
            // Shipping cost logic: free shipping for orders >= 100,000
            const shippingCost = subtotal >= 100000 ? 0 : 5000;
            const total = subtotal + shippingCost;

            // Update subtotal
            $('#cart-subtotal').text('Rp ' + parseInt(subtotal).toLocaleString('id-ID'));

            // Update shipping cost
            if (shippingCost === 0) {
                $('#shipping-cost').html('<span class="text-success">Gratis</span>');
            } else {
                $('#shipping-cost').text('Rp ' + shippingCost.toLocaleString('id-ID'));
            }

            // Update total
            $('#cart-total').text('Rp ' + total.toLocaleString('id-ID'));
        }

        // Update cart count in navbar
        function updateCartCount() {
            $.get('{{ route('cart.count') }}', function(response) {
                if (response.count !== undefined) {
                    $('.cart-count').text(response.count);
                    if (response.count > 0) {
                        $('.cart-count').show();
                    } else {
                        $('.cart-count').hide();
                    }
                }
            });
        }
    </script>
@endpush
