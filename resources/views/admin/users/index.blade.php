@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>User Management</h2>
                <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New User
                </a>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title">Total Users</h6>
                                    <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title">Active Users</h6>
                                    <h3 class="mb-0">{{ $stats['active'] ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-user-check fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title">Customers</h6>
                                    <h3 class="mb-0">{{ $stats['customers'] ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-grow-1">
                                    <h6 class="card-title">Admins</h6>
                                    <h3 class="mb-0">{{ $stats['admins'] ?? 0 }}</h3>
                                </div>
                                <i class="fas fa-user-shield fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped" id="users-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Orders</th>
                                    <th>Total Spent</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        @if($user->avatar)
                                            <img src="{{ $user->avatar }}" alt="{{ $user->name }}" 
                                                 class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 40px; height: 40px;">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <strong>{{ $user->name }}</strong>
                                            @if($user->email_verified_at)
                                                <i class="fas fa-check-circle text-success" title="Verified"></i>
                                            @else
                                                <i class="fas fa-exclamation-circle text-warning" title="Unverified"></i>
                                            @endif
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-{{ $role->name == 'admin' ? 'danger' : 'primary' }}">
                                                {{ ucfirst($role->name) }}
                                            </span>
                                        @endforeach
                                        @if($user->roles->isEmpty())
                                            <span class="badge bg-secondary">Customer</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" 
                                                   {{ $user->is_active ? 'checked' : '' }}
                                                   onchange="toggleUserStatus({{ $user->id }})">
                                        </div>
                                    </td>
                                    <td>{{ $user->orders_count ?? 0 }}</td>
                                    <td>₹{{ number_format($user->total_spent ?? 0, 2) }}</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->id !== auth()->id())
                                            <button class="btn btn-sm btn-danger" onclick="deleteUser({{ $user->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#users-table').DataTable({
        "pageLength": 25,
        "ordering": true,
        "searching": true,
        "order": [[0, "desc"]]
    });
});

function toggleUserStatus(userId) {
    $.ajax({
        url: `/admin/users/${userId}/toggle-status`,
        method: 'PATCH',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            toastr.success('User status updated successfully');
        },
        error: function(xhr) {
            toastr.error('Error updating user status');
            location.reload();
        }
    });
}

function deleteUser(userId) {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        $.ajax({
            url: `/admin/users/${userId}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                toastr.success('User deleted successfully');
                location.reload();
            },
            error: function(xhr) {
                toastr.error('Error deleting user');
            }
        });
    }
}
</script>
@endpush
