@extends('layouts.app')

@section('title', 'Order Confirmation')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <div class="success-icon mb-4">
                    <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
                </div>
                <h1 class="text-success">Pesanan Berhasil!</h1>
                <p class="lead">Terima kasih atas pesanan Anda. Pesanan Anda telah diterima dan sedang diproses.</p>
            </div>

            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class="fas fa-receipt"></i> Order Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Order Number:</strong><br>
                            <span class="h5 text-primary">#{{ $order->order_number }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Order Date:</strong><br>
                            {{ $order->created_at->format('M d, Y H:i') }}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <strong>Payment Method:</strong><br>
                            @if($order->payment_method === 'razorpay')
                                <i class="fas fa-credit-card"></i> Online Payment
                            @else
                                <i class="fas fa-money-bill-wave"></i> Cash on Delivery
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Status:</strong><br>
                            <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    @if($order->shippingAddress)
                    <div class="mb-4">
                        <strong>Shipping Address:</strong><br>
                        <div class="border rounded p-3 bg-light">
                            {{ $order->shippingAddress->full_name }}<br>
                            {{ $order->shippingAddress->address_line_1 }}<br>
                            @if($order->shippingAddress->address_line_2)
                                {{ $order->shippingAddress->address_line_2 }}<br>
                            @endif
                            {{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} - {{ $order->shippingAddress->postal_code }}<br>
                            {{ $order->shippingAddress->country }}<br>
                            Phone: {{ $order->shippingAddress->phone }}
                        </div>
                    </div>
                    @endif

                    <!-- Order Items -->
                    <div class="mb-4">
                        <strong>Ordered Items:</strong>
                        <div class="table-responsive mt-2">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $item->product->featured_image }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="rounded me-3" 
                                                     style="width: 60px; height: 60px; object-fit: cover;">
                                                <div>
                                                    <strong>{{ $item->product->name }}</strong><br>
                                                    @if($item->product->brand)
                                                        <small class="text-muted">{{ $item->product->brand->name }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>Rp {{ number_format((float)$item->price, 0, ",", ".") }}</td>
                                        <td>Rp {{ number_format((float)$item->total, 0, ",", ".") }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="row">
                        <div class="col-md-6 offset-md-6">
                            <div class="border rounded p-3 bg-light">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Subtotal:</span>
                                    <span>Rp {{ number_format((float)$order->subtotal, 0, ",", ".") }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Tax:</span>
                                    <span>Rp {{ number_format((float)$order->tax_amount, 0, ",", ".") }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Shipping:</span>
                                    <span>
                                        @if($order->shipping_amount > 0)
                                            Rp {{ number_format((float)$order->shipping_amount, 0, ",", ".") }}
                                        @else
                                            Free
                                        @endif
                                    </span>
                                </div>
                                @if($order->payment_method === 'cod')
                                <div class="d-flex justify-content-between mb-2">
                                    <span>COD Charges:</span>
                                    <span>Rp 5.000</span>
                                </div>
                                @endif
                                <hr>
                                <div class="d-flex justify-content-between h5">
                                    <strong>Total:</strong>
                                    <strong>Rp {{ number_format((float)$order->total_amount, 0, ",", ".") }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary me-3">
                    <i class="fas fa-eye"></i> View Order Details
                </a>
                <a href="{{ route('home') }}" class="btn btn-outline-primary">
                    <i class="fas fa-home"></i> Continue Shopping
                </a>
            </div>

            <!-- Order Status Timeline -->
            <div class="card mt-4">
                <div class="card-header">
                    <h6><i class="fas fa-clock"></i> Order Status</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item completed">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Order Placed</h6>
                                <p class="text-muted mb-0">{{ $order->created_at->format('M d, Y H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'completed' : '' }}">
                            <div class="timeline-marker {{ in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'bg-success' : 'bg-light' }}"></div>
                            <div class="timeline-content">
                                <h6>Order Confirmed</h6>
                                <p class="text-muted mb-0">
                                    @if(in_array($order->status, ['processing', 'shipped', 'delivered']))
                                        Confirmed
                                    @else
                                        Pending confirmation
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ in_array($order->status, ['shipped', 'delivered']) ? 'completed' : '' }}">
                            <div class="timeline-marker {{ in_array($order->status, ['shipped', 'delivered']) ? 'bg-success' : 'bg-light' }}"></div>
                            <div class="timeline-content">
                                <h6>Shipped</h6>
                                <p class="text-muted mb-0">
                                    @if(in_array($order->status, ['shipped', 'delivered']))
                                        Your order has been shipped
                                    @else
                                        Waiting to ship
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->status === 'delivered' ? 'completed' : '' }}">
                            <div class="timeline-marker {{ $order->status === 'delivered' ? 'bg-success' : 'bg-light' }}"></div>
                            <div class="timeline-content">
                                <h6>Delivered</h6>
                                <p class="text-muted mb-0">
                                    @if($order->status === 'delivered')
                                        Order delivered successfully
                                    @else
                                        Estimated delivery: 3-5 business days
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Important Notes -->
            <div class="alert alert-info mt-4">
                <h6><i class="fas fa-info-circle"></i> Important Notes:</h6>
                <ul class="mb-0">
                    <li>You will receive an email confirmation shortly with your order details.</li>
                    <li>You can track your order status by visiting "My Orders" in your account.</li>
                    @if($order->payment_method === 'cod')
                        <li>Please keep exact change ready for Cash on Delivery.</li>
                    @endif
                    <li>For any queries, contact our customer support at support@store.com</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -37px;
    top: 5px;
    width: 14px;
    height: 14px;
    border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 2px #ddd;
}

.timeline::before {
    content: '';
    position: absolute;
    left: -30px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #ddd;
}

.timeline-item.completed .timeline-marker {
    box-shadow: 0 0 0 2px #28a745;
}

.success-icon {
    animation: bounceIn 0.8s ease-out;
}

@keyframes bounceIn {
    0%, 20%, 40%, 60%, 80% {
        animation-timing-function: cubic-bezier(0.215, 0.610, 0.355, 1.000);
    }
    0% {
        opacity: 0;
        transform: scale3d(.3, .3, .3);
    }
    20% {
        transform: scale3d(1.1, 1.1, 1.1);
    }
    40% {
        transform: scale3d(.9, .9, .9);
    }
    60% {
        opacity: 1;
        transform: scale3d(1.03, 1.03, 1.03);
    }
    80% {
        transform: scale3d(.97, .97, .97);
    }
    100% {
        opacity: 1;
        transform: scale3d(1, 1, 1);
    }
}
</style>
@endpush
