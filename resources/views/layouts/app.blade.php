<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/x-icon">

    <title>@yield('title') - {{ config('app.name', 'E-Commerce Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        body {
            font-family: 'Figtree', sans-serif;
        }

        .navbar-brand {
            font-weight: 600;
            font-size: 1.5rem;
        }

        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        }

        .product-image {
            height: 250px;
            object-fit: cover;
        }

        .btn-cart {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-cart:hover {
            background-color: #0056b3;
            border-color: #0056b3;
            transform: translateY(-2px);
        }

        .cart-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background-color: var(--danger-color);
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 0.75rem;
            min-width: 20px;
            text-align: center;
        }

        .footer {
            background-color: var(--dark-color);
            color: white;
            padding: 3rem 0 1rem 0;
        }

        .price-original {
            text-decoration: line-through;
            color: var(--secondary-color);
        }

        .price-sale {
            color: var(--danger-color);
            font-weight: 600;
        }

        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background-color: var(--danger-color);
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .loading {
            opacity: 0.6;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .product-image {
                height: 200px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm "
        style="position: sticky; top: 0; z-index: 1000;">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="images/logo.png" alt="Logo" style="width: 50px;">
                {{ config('app.name', 'E-Commerce') }}
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('shop') }}">Shop</a>
                    </li>
                </ul>

                <!-- Search Form -->
                <form class="d-flex me-3" action="{{ route('search') }}" method="GET">
                    <input class="form-control" type="search" name="q" placeholder="Search products..."
                        value="{{ request('q') }}">
                    <button class="btn btn-outline-primary ms-2" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </form>

                <ul class="navbar-nav">
                    <!-- Cart -->
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span class="cart-badge" id="cart-count">0</span>
                        </a>
                    </li>

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">My Orders</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}">Profile</a></li>
                                @if (Auth::user()->hasRole(['admin', 'owner']))
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show m-0" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show m-0" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row align-items-center mb-4">
                <div class="col-lg-4 col-md-6 mb-4">
                    <img src="{{ asset('images/logo.png') }}" alt="Keripik Pisang Beach" style="width: 60px;">
                    <h5 class="mt-2 mb-1" style="font-weight: 700;">Keripik Pisang Beach</h5>
                    <p class="mb-2">Rasakan renyahnya keripik pisang khas pantai, dibuat dari bahan alami dan tanpa
                        pengawet. Nikmati camilan sehat dan lezat setiap hari!</p>
                    <div class="social-links mt-3">
                        <a href="#" class="text-light me-3"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold">Navigasi</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-light">Beranda</a></li>
                        <li><a href="{{ route('shop') }}" class="text-light">Produk</a></li>
                        <li><a href="#" class="text-light">Tentang Kami</a></li>
                        <li><a href="#" class="text-light">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold">Bantuan</h6>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-light">FAQ</a></li>
                        <li><a href="#" class="text-light">Cara Pemesanan</a></li>
                        <li><a href="#" class="text-light">Pengiriman</a></li>
                        <li><a href="#" class="text-light">Konfirmasi Pembayaran</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="fw-bold">Kontak Kami</h6>
                    <ul class="list-unstyled">
                        <li><i class="fas fa-phone"></i> 0812-3456-7890</li>
                        <li><i class="fas fa-envelope"></i> info@keripikpisangbeach.com</li>
                        <li><i class="fas fa-map-marker-alt"></i> Jl. Pantai Indah No. 123, Cinangka, Banten</li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="row align-items-center">
                <div class="col-md-7">
                    <p class="mb-0">&copy; {{ date('Y') }} Keripik Pisang Beach. All rights reserved.</p>
                </div>
                <div class="col-md-5 text-end">
                    <p class="mb-0">Cinta lokal, rasa nasional <i class="fas fa-heart text-danger"></i></p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Global JavaScript -->
    <script>
        // Update cart count on page load
        $(document).ready(function() {
            updateCartCount();
        });

        function updateCartCount() {
            $.get('{{ route('cart.count') }}', function(data) {
                $('#cart-count').text(data.count);
            });
        }

        // Add to cart function
        function addToCart(productId, quantity = 1) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.post('{{ route('cart.add') }}', {
                product_id: productId,
                quantity: quantity
            }, function(response) {
                if (response.success) {
                    updateCartCount();
                    showToast('success', response.message);
                } else {
                    showToast('error', response.message);
                }
            }).fail(function() {
                showToast('error', 'Something went wrong. Please try again.');
            });
        }

        // Show toast notification
        function showToast(type, message) {
            const toast = `
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">${message}</div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;

            if (!$('#toast-container').length) {
                $('body').append(
                    '<div id="toast-container" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1051;"></div>'
                );
            }

            $('#toast-container').append(toast);
            $('.toast').last().toast('show');
        }
    </script>

    @stack('scripts')
</body>

</html>
