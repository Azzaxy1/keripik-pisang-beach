@extends('layouts.admin')

@section('title', 'User Addresses - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>User Addresses - {{ $user->name }}</h2>
                <div>
                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info me-2">
                        <i class="fas fa-eye"></i> View User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Users
                    </a>
                </div>
            </div>

            <div class="row">
                @if($user->addresses->count() > 0)
                    @foreach($user->addresses as $address)
                    <div class="col-md-6 mb-4">
                        <div class="card {{ $address->is_default ? 'border-primary' : '' }}">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    {{ $address->type ?? 'Address #' . $loop->iteration }}
                                    @if($address->is_default)
                                        <span class="badge bg-primary ms-1">Default</span>
                                    @endif
                                </h6>
                                <small class="text-muted">
                                    Added {{ $address->created_at->format('M d, Y') }}
                                </small>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>{{ $address->first_name }} {{ $address->last_name }}</strong>
                                    @if($address->company)
                                        <br><small class="text-muted">{{ $address->company }}</small>
                                    @endif
                                </div>
                                
                                <div class="mb-2">
                                    {{ $address->address_line_1 }}
                                    @if($address->address_line_2)
                                        <br>{{ $address->address_line_2 }}
                                    @endif
                                </div>
                                
                                <div class="mb-2">
                                    {{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}
                                    @if($address->country)
                                        <br>{{ $address->country }}
                                    @endif
                                </div>
                                
                                @if($address->phone)
                                <div class="mb-2">
                                    <i class="fas fa-phone text-muted me-1"></i>
                                    {{ $address->phone }}
                                </div>
                                @endif
                                
                                @if($address->notes)
                                <div class="mt-3 pt-3 border-top">
                                    <small class="text-muted">
                                        <strong>Notes:</strong> {{ $address->notes }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Addresses Found</h5>
                                <p class="text-muted">This user hasn't added any addresses yet.</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
