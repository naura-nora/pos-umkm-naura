<aside class="main-sidebar sidebar-dark-primary elevation-4 fixed-top">
    <a href="{{ route('dashboard') }}" class="brand-link">
        <span class="brand-text font-weight-light">POS UMKM</span>
    </a>

    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="info">
                <span class="d-block text-white">
                    {{ auth()->user()->name }}
                </span>
                <span class="d-block text-muted text-sm">
                    {{ auth()->user()->role_name }}
                </span>
            </div>
        </div>

        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview">
                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="{{ route('profile.show') }}" class="nav-link">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>

                <!-- Produk -->
                <li class="nav-item">
                    <a href="{{ route('produk.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-boxes"></i>
                        <p>Produk</p>
                    </a>
                </li>

                @if(auth()->user()->hasRole('admin'))
                    <!-- Admin Menu -->
                    <li class="nav-item">
                        <a href="{{ route('kategori.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-tags"></i>
                            <p>Kategori</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('financial-reports.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>Laporan Keuangan</p>
                        </a>
                    </li>

                    <!-- Manajemen User Dropdown Menu -->
                    <li class="nav-item has-treeview">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>
                                Manajemen User
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('user-management.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>User & Role</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('aktivitas.index') }}" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Aktivitas</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                @endif

                @if(auth()->user()->hasAnyRole(['admin', 'kasir']))
                    <!-- Transaksi Menu -->
                    <li class="nav-item">
                        <a href="{{ route('transaksi.index') }}" class="nav-link">
                            <i class="nav-icon fas fa-receipt"></i>
                            <p>Transaksi</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>