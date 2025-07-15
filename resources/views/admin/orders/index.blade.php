@extends('layouts.admin')

@section('title', 'Orders Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Orders Management</h2>
                <div class="btn-group">
                    <button class="btn btn-outline-primary" onclick="filterOrders('all')">All Orders</button>
                    <button class="btn btn-outline-warning" onclick="filterOrders('pending')">Pending</button>
                    <button class="btn btn-outline-info" onclick="filterOrders('processing')">Processing</button>
                    <button class="btn btn-outline-success" onclick="filterOrders('shipped')">Shipped</button>
                    <button class="btn btn-outline-dark" onclick="filterOrders('delivered')">Delivered</button>
                    <button class="btn btn-outline-danger" onclick="filterOrders('cancelled')">Cancelled</button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="orders-table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
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
                                <tr data-status="{{ $order->status }}">
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong>
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $order->user->name }}</strong><br>
                                            <small class="text-muted">{{ $order->user->email }}</small>
                                        </div>
                                    </td>
                                    <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                    <td>{{ $order->order_items_count ?? $order->items->count() }}</td>
                                    <td>Rp {{ number_format((float)$order->total_amount, 0, ",", ".") }}</td>
                                    <td>
                                        <span class="badge bg-{{ $order->payment_status == 'paid' ? 'success' : ($order->payment_status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <select class="form-select form-select-sm" onchange="updateOrderStatus({{ $order->id }}, this.value)">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button class="btn btn-sm btn-primary" onclick="printInvoice({{ $order->id }})">
                                                <i class="fas fa-print"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to update the order status?</p>
                <div id="status-details"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirm-status-update">Update Status</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#orders-table').DataTable({
        "pageLength": 25,
        "ordering": true,
        "searching": true,
        "order": [[2, "desc"]]
    });
});

function filterOrders(status) {
    var table = $('#orders-table').DataTable();
    if (status === 'all') {
        table.column(6).search('').draw();
    } else {
        table.column(6).search(status).draw();
    }
}

let pendingOrderId = null;
let pendingStatus = null;

function updateOrderStatus(orderId, newStatus) {
    pendingOrderId = orderId;
    pendingStatus = newStatus;
    
    $('#status-details').html(`
        <strong>Order ID:</strong> #${orderId}<br>
        <strong>New Status:</strong> ${newStatus.toUpperCase()}
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
                location.reload();
            },
            error: function(xhr) {
                alert('Error updating order status');
                location.reload();
            }
        });
    }
});

function printInvoice(orderId) {
    window.open(`/admin/orders/${orderId}/invoice`, '_blank');
}
</script>
@endpush
