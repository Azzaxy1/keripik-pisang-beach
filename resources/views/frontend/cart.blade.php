@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Shopping Cart</h2>
        </div>
    </div>

    @if($cartItems->count() > 0)
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Cart Items ({{ $cartItems->count() }})</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                        <div class="row align-items-center cart-item mb-3" data-item-id="{{ $item->id }}">
                            <div class="col-md-2">
                                <img src="{{ $item->product->featured_image }}" alt="{{ $item->product->name }}" class="img-fluid rounded">
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1">{{ $item->product->name }}</h6>
                                <small class="text-muted">SKU: {{ $item->product->sku }}</small>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})">-</button>
                                    <input type="number" class="form-control form-control-sm text-center" value="{{ $item->quantity }}" min="1" onchange="updateQuantity({{ $item->id }}, this.value)">
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})">+</button>
                                </div>
                            </div>
                            <div class="col-md-2 text-end">
                                <strong>₹{{ number_format($item->price, 2) }}</strong>
                            </div>
                            <div class="col-md-2 text-end">
                                <strong class="item-subtotal">₹{{ number_format($item->subtotal, 2) }}</strong>
                                <br>
                                <button class="btn btn-sm btn-outline-danger mt-1" onclick="removeItem({{ $item->id }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <hr>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Order Summary</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span id="cart-subtotal">₹{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Shipping:</span>
                            <span id="shipping-cost">{{ $total > 500 ? 'Free' : '₹50.00' }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax (18%):</span>
                            <span id="tax-amount">₹{{ number_format($total * 0.18, 2) }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <strong>Total:</strong>
                            <strong id="cart-total">₹{{ number_format($total + ($total > 500 ? 0 : 50) + ($total * 0.18), 2) }}</strong>
                        </div>
                        
                        @auth
                            <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 mb-2">Proceed to Checkout</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">Login to Checkout</a>
                        @endauth
                        
                        <a href="{{ route('shop') }}" class="btn btn-outline-secondary w-100">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-12 text-center">
                <div class="py-5">
                    <i class="fas fa-shopping-cart fa-5x text-muted mb-4"></i>
                    <h3>Your cart is empty</h3>
                    <p class="text-muted mb-4">Looks like you haven't added anything to your cart yet.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary btn-lg">Start Shopping</a>
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

    $.post('{{ route("cart.update") }}', {
        cart_item_id: itemId,
        quantity: quantity
    }, function(response) {
        if (response.success) {
            // Update the item subtotal
            $('[data-item-id="' + itemId + '"] .item-subtotal').text('₹' + parseFloat(response.subtotal).toFixed(2));
            
            // Update cart totals
            updateCartTotals(response.total);
            updateCartCount();
            showToast('success', response.message);
        } else {
            showToast('error', response.message);
        }
    }).fail(function() {
        showToast('error', 'Something went wrong. Please try again.');
    });
}

function removeItem(itemId) {
    if (!confirm('Are you sure you want to remove this item from your cart?')) {
        return;
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.post('{{ route("cart.remove") }}', {
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
            showToast('success', response.message);
        } else {
            showToast('error', response.message);
        }
    }).fail(function() {
        showToast('error', 'Something went wrong. Please try again.');
    });
}

function updateCartTotals(subtotal) {
    const shipping = subtotal > 500 ? 0 : 50;
    const tax = subtotal * 0.18;
    const total = subtotal + shipping + tax;
    
    $('#cart-subtotal').text('₹' + parseFloat(subtotal).toFixed(2));
    $('#shipping-cost').text(shipping === 0 ? 'Free' : '₹' + shipping.toFixed(2));
    $('#tax-amount').text('₹' + tax.toFixed(2));
    $('#cart-total').text('₹' + total.toFixed(2));
}
</script>
@endpush
