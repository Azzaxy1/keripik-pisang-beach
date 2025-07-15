@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2>Order #{{ $order->order_number }}</h2>
                    <p class="text-muted mb-0">Placed on {{ $order->created_at->format('M d, Y \a\t h:i A') }}</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }} fs-6">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            
            <div class="row">
                <div class="col-lg-8">
                    <!-- Order Items -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Items</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-borderless mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/60x60' }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="img-thumbnail me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                                        <div>
                                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                            @if($item->product_attributes)
                                                                <small class="text-muted">{{ $item->product_attributes }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="align-middle">₹{{ number_format($item->price, 2) }}</td>
                                                <td class="align-middle">{{ $item->quantity }}</td>
                                                <td class="align-middle text-end">₹{{ number_format($item->total_amount, 2) }}</td>
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
                            <h5 class="mb-0">Order Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline">
                                @php
                                    $statuses = [
                                        'pending' => 'Order Placed',
                                        'confirmed' => 'Order Confirmed',
                                        'processing' => 'Processing',
                                        'shipped' => 'Shipped',
                                        'delivered' => 'Delivered',
                                        'completed' => 'Completed'
                                    ];
                                    $currentStatusIndex = array_search($order->status, array_keys($statuses));
                                @endphp
                                
                                @foreach($statuses as $status => $label)
                                    @php
                                        $statusIndex = array_search($status, array_keys($statuses));
                                        $isCompleted = $order->status === 'cancelled' ? false : $statusIndex <= $currentStatusIndex;
                                        $isCurrent = $status === $order->status;
                                    @endphp
                                    
                                    <div class="timeline-item {{ $isCompleted ? 'completed' : '' }} {{ $isCurrent ? 'current' : '' }}">
                                        <div class="timeline-marker">
                                            @if($isCompleted)
                                                <i class="fas fa-check"></i>
                                            @else
                                                <i class="fas fa-circle"></i>
                                            @endif
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0">{{ $label }}</h6>
                                            @if($isCurrent)
                                                <small class="text-muted">Current Status</small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($order->status === 'cancelled')
                                    <div class="timeline-item cancelled">
                                        <div class="timeline-marker">
                                            <i class="fas fa-times"></i>
                                        </div>
                                        <div class="timeline-content">
                                            <h6 class="mb-0">Order Cancelled</h6>
                                            <small class="text-muted">{{ $order->notes }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <!-- Order Summary -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal:</span>
                                <span>₹{{ number_format($order->sub_total, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax:</span>
                                <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Shipping:</span>
                                <span>₹{{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                            @if($order->discount_amount > 0)
                                <div class="d-flex justify-content-between mb-2 text-success">
                                    <span>Discount:</span>
                                    <span>-₹{{ number_format($order->discount_amount, 2) }}</span>
                                </div>
                            @endif
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total:</span>
                                <span>₹{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Address -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            <address class="mb-0">
                                <strong>{{ $order->shipping_first_name }} {{ $order->shipping_last_name }}</strong><br>
                                {{ $order->shipping_address_line_1 }}<br>
                                @if($order->shipping_address_line_2)
                                    {{ $order->shipping_address_line_2 }}<br>
                                @endif
                                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal_code }}<br>
                                {{ $order->shipping_country }}<br>
                                <strong>Phone:</strong> {{ $order->shipping_phone }}
                            </address>
                        </div>
                    </div>
                    
                    <!-- Payment Information -->
                    @if($order->payment)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Payment Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Method:</span>
                                    <span class="text-capitalize">{{ $order->payment->payment_method }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Status:</span>
                                    <span class="badge bg-{{ $order->payment->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($order->payment->status) }}
                                    </span>
                                </div>
                                @if($order->payment->transaction_id)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Transaction ID:</span>
                                        <small class="text-muted">{{ $order->payment->transaction_id }}</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Actions -->
                    <div class="card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($order->status === 'completed')
                                    <a href="{{ route('orders.invoice', $order) }}" class="btn btn-success">
                                        <i class="fas fa-download me-2"></i>Download Invoice
                                    </a>
                                @endif
                                
                                @if(in_array($order->status, ['pending', 'confirmed']))
                                    <form action="{{ route('orders.cancel', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger w-100" 
                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                            <i class="fas fa-times me-2"></i>Cancel Order
                                        </button>
                                    </form>
                                @endif
                                
                                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Back to Orders
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
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

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background-color: #dee2e6;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 0;
    width: 20px;
    height: 20px;
    background-color: #dee2e6;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 10px;
    color: white;
}

.timeline-item.completed .timeline-marker {
    background-color: #28a745;
}

.timeline-item.current .timeline-marker {
    background-color: #007bff;
}

.timeline-item.cancelled .timeline-marker {
    background-color: #dc3545;
}

.timeline-content h6 {
    margin-bottom: 0;
}
</style>
@endpush
