@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>User Details - {{ $user->name }}</h2>
                <div>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning me-2">
                        <i class="fas fa-edit"></i> Edit User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- User Information Card -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">User Information</h5>
                            <div>
                                @if($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                                @foreach($user->roles as $role)
                                    <span class="badge bg-primary ms-1">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-muted">Full Name</label>
                                        <p class="mb-0">{{ $user->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-muted">Email Address</label>
                                        <p class="mb-0">
                                            {{ $user->email }}
                                            @if($user->email_verified_at)
                                                <i class="fas fa-check-circle text-success ms-1" title="Email Verified"></i>
                                            @else
                                                <i class="fas fa-exclamation-circle text-warning ms-1" title="Email Not Verified"></i>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-muted">Phone Number</label>
                                        <p class="mb-0">{{ $user->phone ?: 'Not provided' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-muted">Date of Birth</label>
                                        <p class="mb-0">
                                            @if($user->date_of_birth)
                                                {{ $user->date_of_birth->format('M d, Y') }}
                                                ({{ $user->date_of_birth->age }} years old)
                                            @else
                                                Not provided
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-muted">Gender</label>
                                        <p class="mb-0">{{ $user->gender ? ucfirst($user->gender) : 'Not specified' }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-muted">Account Created</label>
                                        <p class="mb-0">{{ $user->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-muted">Last Login</label>
                                        <p class="mb-0">
                                            @if($user->last_login_at)
                                                {{ $user->last_login_at->format('M d, Y h:i A') }}
                                                <small class="text-muted">({{ $user->last_login_at->diffForHumans() }})</small>
                                            @else
                                                Never logged in
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="fw-bold text-muted">Last Updated</label>
                                        <p class="mb-0">{{ $user->updated_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders -->
                    @if($user->orders->count() > 0)
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Orders</h5>
                            <a href="{{ route('admin.users.orders', $user) }}" class="btn btn-sm btn-outline-primary">
                                View All Orders
                            </a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Order #</th>
                                            <th>Date</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($user->orders->take(5) as $order)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-decoration-none">
                                                    #{{ $order->order_number }}
                                                </a>
                                            </td>
                                            <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            <td>{{ $order->items->count() }}</td>
                                            <td>₹{{ number_format($order->total_amount, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $order->status == 'delivered' ? 'success' : ($order->status == 'cancelled' ? 'danger' : 'warning') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Stats Sidebar -->
                <div class="col-md-4">
                    <!-- Order Statistics -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Order Statistics</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Orders:</span>
                                <strong>{{ $stats['total_orders'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Spent:</span>
                                <strong>₹{{ number_format($stats['total_spent'], 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Pending Orders:</span>
                                <strong>{{ $stats['pending_orders'] }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Completed Orders:</span>
                                <strong>{{ $stats['completed_orders'] }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0">Quick Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit User
                                </a>
                                <button type="button" class="btn btn-{{ $user->is_active ? 'danger' : 'success' }} btn-sm toggle-status" 
                                        data-user-id="{{ $user->id }}">
                                    <i class="fas fa-{{ $user->is_active ? 'ban' : 'check' }}"></i>
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }} User
                                </button>
                                @if($user->orders->count() > 0)
                                <a href="{{ route('admin.users.orders', $user) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-shopping-cart"></i> View Orders
                                </a>
                                @endif
                                @if($user->addresses->count() > 0)
                                <a href="{{ route('admin.users.addresses', $user) }}" class="btn btn-secondary btn-sm">
                                    <i class="fas fa-map-marker-alt"></i> View Addresses
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Addresses -->
                    @if($user->addresses->count() > 0)
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Addresses</h6>
                            <a href="{{ route('admin.users.addresses', $user) }}" class="btn btn-sm btn-outline-secondary">
                                View All
                            </a>
                        </div>
                        <div class="card-body">
                            @foreach($user->addresses->take(2) as $address)
                            <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong>{{ $address->type ?? 'Address' }}</strong>
                                        @if($address->is_default)
                                            <span class="badge bg-primary badge-sm ms-1">Default</span>
                                        @endif
                                    </div>
                                </div>
                                <small class="text-muted">
                                    {{ $address->address_line_1 }}<br>
                                    {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                </small>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle user status
    document.querySelector('.toggle-status')?.addEventListener('click', function() {
        const userId = this.dataset.userId;
        const button = this;
        
        if (confirm('Are you sure you want to change this user\'s status?')) {
            fetch(`/admin/users/${userId}/toggle-status`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to update user status. Please try again.');
            });
        }
    });
});
</script>
@endsection
