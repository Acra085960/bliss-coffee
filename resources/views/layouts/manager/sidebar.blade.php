{{-- filepath: /home/acra/bliss/resources/views/layouts/manager/sidebar.blade.php --}}
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('manajer.dashboard') }}" class="nav-link {{ request()->routeIs('manajer.dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard Operasional</p>
                    </a>
                </li>
                <!-- Analisis Penjualan -->
                <li class="nav-item">
                    <a href="{{ route('manajer.sales.analysis') }}" class="nav-link {{ request()->is('manajer/sales*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Analisis Penjualan</p>
                    </a>
                </li>
                <!-- Stok & Sisa Bahan -->
                <li class="nav-item">
                    <a href="{{ route('manajer.stocks.index') }}" class="nav-link {{ request()->is('manajer/stocks*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Stok & Sisa Bahan</p>
                    </a>
                </li>
                <!-- Monitoring Kinerja Penjual -->
                <li class="nav-item">
                    <a href="{{ route('manajer.sellers.performance') }}" class="nav-link {{ request()->is('manajer/sellers*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Kinerja Penjual</p>
                    </a>
                </li>
                <!-- Laporan Menu Terlaris -->
                <li class="nav-item">
                    <a href="{{ route('manajer.topmenus') }}" class="nav-link {{ request()->is('manajer/topmenus*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Menu Terlaris</p>
                    </a>
                </li>
                <!-- Filter & Ekspor Data Penjualan -->
                <li class="nav-item">
                    <a href="{{ route('manajer.sales.export') }}" class="nav-link {{ request()->is('manajer/sales/export*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-export"></i>
                        <p>Ekspor Data Penjualan</p>
                    </a>
                </li>
            </ul>
            <hr style="margin: 1.5rem 0; border-top: 1px solid #444;">
            <form method="POST" action="{{ route('logout') }}" style="padding: 0 1rem;">
                @csrf
                <button type="submit" class="btn btn-danger w-100" style="margin-top: 10px;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </div>
</aside>