<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Bliss Coffee') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #003200 0%, #004d26 100%);
        }
        .sidebar .nav-link {
            color: #ffffff;
            border-radius: 8px;
            margin: 5px 0;
            padding: 10px 15px;
        }
        .sidebar .nav-link:hover {
            background-color: rgba(255,255,255,0.1);
            color: #ffffff;
        }
        .sidebar .nav-link.active {
            background-color: #A9744F;
            color: #ffffff;
        }
        .content-area {
            padding: 20px;
        }
        .user-info {
            background-color: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            color: #ffffff;
        }
        .logout-btn {
            background-color: #dc3545;
            border: none;
            color: white;
            width: 100%;
            margin-top: 10px;
        }
        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 col-lg-2 sidebar">
                    <div class="p-3">
                        <!-- Logo -->
                        <div class="text-center mb-4">
                            <img src="{{ asset('images/bliss_logo.png') }}" alt="Logo" style="height: 50px;" class="me-2">
                            <h5 class="text-white mb-0">Bliss Coffee</h5>
                        </div>

                        <!-- User Info -->
                        @auth
                        <div class="user-info text-center">
                            <i class="fas fa-user-circle fa-2x mb-2"></i>
                            <div>{{ Auth::user()->name }}</div>
                            <small class="text-muted">{{ ucfirst(Auth::user()->role) }}</small>
                        </div>
                        @endauth

                        <!-- Navigation Menu -->
                        <nav class="nav flex-column">
                            @auth
                                @if(Auth::user()->role === 'pembeli')
                                    <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}" href="{{ route('customer.dashboard') }}">
                                        <i class="fas fa-home me-2"></i> Dashboard
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('customer.menu') ? 'active' : '' }}" href="{{ route('customer.menu') }}">
                                        <i class="fas fa-coffee me-2"></i> Menu
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('customer.cart') ? 'active' : '' }}" href="{{ route('customer.cart') }}">
                                        <i class="fas fa-shopping-cart me-2"></i> Keranjang
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}" href="{{ route('customer.orders') }}">
                                        <i class="fas fa-history me-2"></i> Riwayat Pesanan
                                    </a>
                                @elseif(Auth::user()->role === 'penjual')
                                    <a class="nav-link {{ request()->routeIs('penjual.dashboard') ? 'active' : '' }}" href="{{ route('penjual.dashboard') }}">
                                        <i class="fas fa-home me-2"></i> Dashboard
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('penjual.orders.*') ? 'active' : '' }}" href="{{ route('penjual.orders.index') }}">
                                        <i class="fas fa-clipboard-list me-2"></i> Pesanan
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('penjual.menu.*') ? 'active' : '' }}" href="{{ route('penjual.menu.index') }}">
                                        <i class="fas fa-utensils me-2"></i> Menu
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('penjual.stock.*') ? 'active' : '' }}" href="{{ route('penjual.stock.index') }}">
                                        <i class="fas fa-boxes me-2"></i> Stok
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('penjual.feedback.*') ? 'active' : '' }}" href="{{ route('penjual.feedback.index') }}">
                                        <i class="fas fa-comments me-2"></i> Feedback
                                    </a>
                                @elseif(Auth::user()->role === 'manajer')
                                    <a class="nav-link {{ request()->routeIs('manajer.dashboard') ? 'active' : '' }}" href="{{ route('manajer.dashboard') }}">
                                        <i class="fas fa-home me-2"></i> Dashboard
                                    </a>
                                @elseif(Auth::user()->role === 'owner')
                                    <a class="nav-link {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}" href="{{ route('owner.dashboard') }}">
                                        <i class="fas fa-home me-2"></i> Dashboard
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('owner.employees') ? 'active' : '' }}" href="{{ route('owner.employees') }}">
                                        <i class="fas fa-users me-2"></i> Karyawan
                                    </a>
                                    <a class="nav-link {{ request()->routeIs('owner.reports') ? 'active' : '' }}" href="{{ route('owner.reports') }}">
                                        <i class="fas fa-chart-bar me-2"></i> Laporan
                                    </a>
                                @endif

                                <!-- Profile -->
                                <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user-cog me-2"></i> Profile
                                </a>

                                <!-- Logout Button -->
                                <form method="POST" action="{{ route('logout') }}" class="mt-3">
                                    @csrf
                                    <button type="submit" class="btn logout-btn">
                                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                                    </button>
                                </form>
                            @endauth
                        </nav>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-md-9 col-lg-10 content-area">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
