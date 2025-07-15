@extends('layouts.admin')

@section('title', 'User Orders - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>User Orders - {{ $user->name }}</h2>
                <div>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye"></i> View User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>

            @if($orders->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Orders ({{ $orders->total() }})</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Items</th>
                                        <th>Total</th>
                                        <th>Payment</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none fw-bold">
                                                #{{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            {{ $order->created_at->format('M d, Y') }}
                                            <br><small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            {{ $order->items->count() }} items
                                            <br>
                                            <small class="text-muted">
                                                @foreach($order->items->take(2) as $item)
                                                    {{ $item->product->name }}@if(!$loop->last), @endif
                                                @endforeach
                                                @if($order->items->count() > 2)
                                                    +{{ $order->items->count() - 2 }} more
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <strong>₹{{ number_format($order->total_amount, 2) }}</strong>
                                            @if($order->discount_amount > 0)
                                                <br><small class="text-success">-₹{{ number_format($order->discount_amount, 2) }} discount</small>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'failed' ? 'danger' : 'warning') }}">
                                                {{ ucfirst($order->payment_status) }}
                                            </span>
                                            <br><small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-{{ 
                                                $order->status == 'delivered' ? 'success' : 
                                                ($order->status == 'cancelled' ? 'danger' : 
                                                ($order->status == 'shipped' ? 'info' : 'warning')) 
                                            }}">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                            @if($order->shipped_at)
                                                <br><small class="text-muted">Shipped: {{ $order->shipped_at->format('M d') }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary" title="View Order">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                                <button type="button" class="btn btn-outline-secondary dropdown-toggle" 
                                                        data-bs-toggle="dropdown" title="Change Status">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    @if($order->status == 'pending')
                                                        <li><a class="dropdown-item status-update" 
                                                               href="#" data-order="{{ $order->id }}" data-status="confirmed">Confirm</a></li>
                                                        <li><a class="dropdown-item status-update text-danger" 
                                                               href="#" data-order="{{ $order->id }}" data-status="cancelled">Cancel</a></li>
                                                    @elseif($order->status == 'confirmed')
                                                        <li><a class="dropdown-item status-update" 
                                                               href="#" data-order="{{ $order->id }}" data-status="shipped">Ship</a></li>
                                                        <li><a class="dropdown-item status-update text-danger" 
                                                               href="#" data-order="{{ $order->id }}" data-status="cancelled">Cancel</a></li>
                                                    @elseif($order->status == 'shipped')
                                                        <li><a class="dropdown-item status-update text-success" 
                                                               href="#" data-order="{{ $order->id }}" data-status="delivered">Mark Delivered</a></li>
                                                    @endif
                                                </ul>
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

                <!-- Pagination -->
                @if($orders->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
                @endif
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Orders Found</h5>
                        <p class="text-muted">This user hasn't placed any orders yet.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status update functionality
    document.querySelectorAll('.status-update').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const orderId = this.dataset.order;
            const newStatus = this.dataset.status;
            const statusText = this.textContent;
            
            if (confirm(`Are you sure you want to ${statusText.toLowerCase()} this order?`)) {
                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to update order status. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to update order status. Please try again.');
                });
            }
        });
    });
});
</script>
@endsection
