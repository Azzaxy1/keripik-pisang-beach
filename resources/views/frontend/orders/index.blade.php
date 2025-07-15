@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">My Orders</h2>
            
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            
            @if($orders->count() > 0)
                <div class="row">
                    @foreach($orders as $order)
                        <div class="col-12 mb-4">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-0">Order #{{ $order->order_number }}</h6>
                                        <small class="text-muted">Placed on {{ $order->created_at->format('M d, Y') }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                        <div class="mt-1">
                                            <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6>Items ({{ $order->items->count() }})</h6>
                                            <div class="row">
                                                @foreach($order->items->take(3) as $item)
                                                    <div class="col-auto">
                                                        <img src="{{ $item->product->featured_image ?? 'https://via.placeholder.com/60x60' }}" 
                                                             alt="{{ $item->product->name }}" 
                                                             class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                                                    </div>
                                                @endforeach
                                                @if($order->items->count() > 3)
                                                    <div class="col-auto d-flex align-items-center">
                                                        <span class="text-muted">+{{ $order->items->count() - 3 }} more</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                                    View Details
                                                </a>
                                                @if($order->status === 'completed')
                                                    <a href="{{ route('orders.invoice', $order) }}" class="btn btn-outline-success btn-sm">
                                                        <i class="fas fa-download me-1"></i>Invoice
                                                    </a>
                                                @endif
                                                @if(in_array($order->status, ['pending', 'confirmed']))
                                                    <form action="{{ route('orders.cancel', $order) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                                onclick="return confirm('Are you sure you want to cancel this order?')">
                                                            Cancel
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
                    <h4>No orders found</h4>
                    <p class="text-muted mb-4">You haven't placed any orders yet.</p>
                    <a href="{{ route('shop') }}" class="btn btn-primary">Start Shopping</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
