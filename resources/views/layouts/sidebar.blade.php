<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">AdminLTE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard Penjual</p>
                    </a>
                </li>

                <!-- Manage Menu -->
                <li class="nav-item">
                    <a href="{{ route('penjual.menu.index') }}" class="nav-link {{ request()->is('menu*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-coffee"></i>
                        <p>Kelola Menu</p>
                    </a>
                </li>

                <!-- Orders -->
                <li class="nav-item">
                    <a href="{{ route('penjual.orders.index') }}" class="nav-link {{ request()->is('orders*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>Pesanan</p>
                    </a>
                </li>

                <!-- Stock Management -->
                <li class="nav-item">
                    <a href="{{ route('penjual.stock.index') }}" class="nav-link {{ request()->is('stock*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-cogs"></i>
                        <p>Kelola Stok</p>
                    </a>
                </li>

                <!-- Feedback -->
                <li class="nav-item">
                    <a href="{{ route('penjual.feedback.index') }}" class="nav-link {{ request()->is('feedback*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-comments"></i>
                        <p>Feedback Pembeli</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
