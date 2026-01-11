@php
    // Fungsi untuk cek apakah menu aktif
    function isMenuActive($url, $output = 'active') {
        return request()->is($url) || request()->is($url . '/*') ? $output : '';
    }

    // Fungsi untuk cek apakah dropdown harus terbuka
    function isMenuOpen($prefix) {
        return request()->is($prefix . '*') ? 'open' : '';
    }
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor QC Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary-blue: #015255ff;
            --light-blue: #e0e7ff;
            --bg-gray: #f5f7fa;
            --card-shadow: 0 2px 8px rgba(99, 102, 241, 0.08);
            --hover-shadow: 0 4px 16px rgba(99, 102, 241, 0.12);
        }

        /* ===== TAMBAHAN UNTUK RESPONSIVE STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.blue::before { background: linear-gradient(90deg, #6366f1, #8b5cf6); }
        .stat-card.green::before { background: linear-gradient(90deg, #10b981, #059669); }
        .stat-card.red::before { background: linear-gradient(90deg, #ef4444, #dc2626); }
        .stat-card.orange::before { background: linear-gradient(90deg, #f59e0b, #d97706); }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .stat-card.blue .stat-icon { background: var(--light-blue); color: var(--primary-blue); }
        .stat-card.green .stat-icon { background: #d1fae5; color: #10b981; }
        .stat-card.red .stat-icon { background: #fee2e2; color: #ef4444; }
        .stat-card.orange .stat-icon { background: #fef3c7; color: #f59e0b; }

        .stat-label {
            color: #6b7280;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #015255ff;
            margin-bottom: 0.5rem;
        }

        .stat-change {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-change.positive { color: #10b981; }
        .stat-change.negative { color: #ef4444; }

        /* Chart Cards */
        .chart-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .chart-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
        }

        .chart-card.full-width {
            grid-column: 1 / -1;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #015255ff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .chart-canvas {
            position: relative;
            height: 300px;
        }

        /* Table */
        .table-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.5rem;
        }

        .table-responsive {
            max-height: 500px;
            overflow-y: auto;
            border-radius: 10px;
        }

        .modern-table {
            width: 100%;
            font-size: 0.9rem;
            border-collapse: collapse;
        }

        .modern-table thead {
            background: var(--bg-gray);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .modern-table th {
            padding: 1rem;
            font-weight: 600;
            color: #4b5563;
            border: none;
            text-align: left;
        }

        .modern-table td {
            padding: 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #015255ff;
        }

        .modern-table tbody tr {
            transition: all 0.2s;
        }

        .modern-table tbody tr:hover {
            background: var(--bg-gray);
        }

        .badge {
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
        }

        .badge-blue { background: var(--light-blue); color: var(--primary-blue); }
        .badge-green { background: #d1fae5; color: #059669; }
        .badge-red { background: #fee2e2; color: #dc2626; }
        .badge-orange { background: #fef3c7; color: #d97706; }
        .badge-gray { background: #f3f4f6; color: #6b7280; }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: var(--bg-gray);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #015255ff 0%, #015255ff 100%);
            color: white;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 12px rgba(0,0,0,0.05);
            transition: transform 0.3s ease;
            position: relative;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 1.8rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-header h4 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-header .logo {
            width: 32px;
            height: 32px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-blue);
            font-weight: bold;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .sidebar-nav a {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            padding: 0.85rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
            font-size: 0.95rem;
            border-left: 3px solid transparent;
        }

        .sidebar-nav a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .sidebar-nav a.active {
            background-color: rgba(255, 255, 255, 0.15);
            color: white;
            border-left-color: white;
        }

        .sidebar-nav a i {
            font-size: 1.1rem;
            width: 20px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 3px rgba(0,0,0,0.03);
        }

        /* Hamburger Menu */
        .hamburger-menu {
            display: none;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
            padding: 8px;
            background: var(--bg-gray);
            border-radius: 8px;
            transition: all 0.2s;
        }

        .hamburger-menu:hover {
            background: var(--light-blue);
        }

        .hamburger-menu span {
            width: 24px;
            height: 3px;
            background: var(--primary-blue);
            border-radius: 2px;
            transition: all 0.3s;
        }

        .hamburger-menu.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .hamburger-menu.active span:nth-child(2) {
            opacity: 0;
        }

        .hamburger-menu.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        .search-box {
            position: relative;
            width: 400px;
        }

        .search-box input {
            width: 100%;
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .search-box input:focus {
            outline: none;
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px var(--light-blue);
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .notification-icon {
            position: relative;
            width: 40px;
            height: 40px;
            background: var(--bg-gray);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-icon:hover {
            background: var(--light-blue);
        }

        .notification-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: #ef4444;
            border-radius: 50%;
        }

        .user-avatar {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            cursor: pointer;
        }

        .user-avatar .avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Content Area */
        .content-area {
            padding: 2rem;
            flex: 1;
        }

        .page-header {
            margin-bottom: 2rem;
        }

        .page-header h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #015255ff;
            margin-bottom: 0.25rem;
        }

        .page-header .date {
            color: #6b7280;
            font-size: 0.9rem;
        }

        /* Overlay untuk mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .sidebar-overlay.active {
            display: block;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            /* Sidebar menjadi offcanvas di mobile */
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                transform: translateX(-100%);
                z-index: 1001;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            /* Show hamburger menu */
            .hamburger-menu {
                display: flex;
            }

            /* Navbar adjustments */
            .top-navbar {
                padding: 0.75rem 1rem;
                gap: 0.5rem;
            }

            .search-box {
                display: none; /* Hide search on mobile by default */
            }

            /* Adjust user info */
            .user-info {
                gap: 0.5rem;
                margin-left: auto;
            }

            .user-avatar span {
                display: none; /* Hide username text on mobile */
            }

            .user-info form {
                margin-left: 0 !important;
            }

            .user-info .btn {
                padding: 0.4rem 0.6rem;
                font-size: 0.85rem;
            }

            /* Content area */
            .content-area {
                padding: 1rem;
            }

            .page-header h2 {
                font-size: 1.4rem;
            }

            /* Notification icon smaller */
            .notification-icon {
                width: 36px;
                height: 36px;
            }

            .user-avatar .avatar-placeholder {
                width: 36px;
                height: 36px;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .top-navbar {
                padding: 0.5rem 0.75rem;
            }

            .content-area {
                padding: 0.75rem;
            }

            .page-header h2 {
                font-size: 1.25rem;
            }

            .notification-icon {
                width: 32px;
                height: 32px;
            }

            .user-avatar .avatar-placeholder {
                width: 32px;
                height: 32px;
                font-size: 0.8rem;
            }

            .user-info .btn {
                padding: 0.35rem 0.5rem;
                font-size: 0.8rem;
            }

            .user-info .btn i {
                font-size: 0.9rem;
            }

            .user-info .btn span {
                display: none; /* Hide logout text, only show icon */
            }
        }

        /* Demo Content Styles */
        .demo-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--card-shadow);
        }

        .demo-card h5 {
            color: var(--primary-blue);
            margin-bottom: 0.5rem;
        }

        .menu-group .menu-toggle {
            cursor: pointer;
        }

        .submenu {
            display: none;
            flex-direction: column;
            background: rgba(255,255,255,0.05);
        }

        .submenu a {
            padding-left: 3.5rem;
            font-size: 0.9rem;
        }

        .menu-group.open .submenu {
            display: flex;
        }

        .menu-group.open .menu-toggle .bi-chevron-down {
            transform: rotate(180deg);
        }

        .menu-toggle .bi-chevron-down {
            transition: transform 0.3s ease;
        }

        .menu-group .menu-toggle {
        cursor: pointer;
        position: relative;
    }

    .submenu {
        display: none;
        flex-direction: column;
        background: rgba(255,255,255,0.05);
        overflow: hidden;
        max-height: 0;
        transition: max-height 0.3s ease;
    }

    .submenu a {
        padding-left: 3.5rem;
        font-size: 0.9rem;
    }

    .menu-group.open .submenu {
        display: flex;
        max-height: 500px; /* Sesuaikan dengan jumlah item submenu */
    }

    .menu-group.open .menu-toggle .bi-chevron-down {
        transform: rotate(180deg);
    }

    .menu-toggle .bi-chevron-down {
        transition: transform 0.3s ease;
        margin-left: auto;
    }

    .menu-toggle {
        display: flex;
        align-items: center;
        width: 100%;
    }

    </style>
</head>
<script>
    // Inisialisasi Dropdown Menu
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggles = document.querySelectorAll('.menu-toggle');

        menuToggles.forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.preventDefault();

                const parent = this.closest('.menu-group');

                // Tutup dropdown lain (opsional, untuk single accordion)
                document.querySelectorAll('.menu-group').forEach(group => {
                    if (group !== parent) {
                        group.classList.remove('open');
                    }
                });

                // Toggle dropdown yang diklik
                parent.classList.toggle('open');
            });
        });
    });
</script>

<body>
    <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h4>
            <span class="logo">E</span>
            <span>E-Monitor QC</span>
        </h4>
    </div>
    <nav class="sidebar-nav">

        <!-- DASHBOARD -->
        <a href="/spv/dashboard" class="{{ isMenuActive('spv/dashboard', true) }}">
            <i class="bi bi-house-door-fill"></i>
            <span>Dashboard</span>
        </a>

        <!-- DATA PRODUKSI (DROPDOWN) -->
        <div class="menu-group {{ isMenuOpen('spv/produksi') }}">
            <a href="javascript:void(0)" class="menu-toggle {{ isMenuActive('spv/produksi') }}">
                <i class="bi bi-clipboard-data-fill"></i>
                <span>Data Produksi</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="submenu">
                <a href="/spv/produksi" class="{{ isMenuActive('spv/produksi', true) }}">
                    <i class="bi bi-table"></i>
                    <span>Lihat Tabel</span>
                </a>
                <a href="/spv/produksi/excel" class="{{ isMenuActive('spv/produksi/excel', true) }}">
                    <i class="bi bi-file-earmark-excel-fill"></i>
                    <span>Download Excel</span>
                </a>
            </div>
        </div>

        <!-- DATA DEFECT (DROPDOWN) -->
        <div class="menu-group {{ isMenuOpen('spv/defect') }}">
            <a href="javascript:void(0)" class="menu-toggle {{ isMenuActive('spv/defect') }}">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>Data Defect</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="submenu">
                <a href="/spv/defect" class="{{ isMenuActive('spv/defect', true) }}">
                    <i class="bi bi-table"></i>
                    <span>Lihat Tabel</span>
                </a>
                <a href="/spv/defect/realtime" class="{{ isMenuActive('spv/defect/realtime', true) }}">
                    <i class="bi bi-activity"></i>
                    <span>Monitoring Real-Time</span>
                </a>
                <a href="/spv/defect/validasi" class="{{ isMenuActive('spv/defect/validasi', true) }}">
                    <i class="bi bi-check-circle-fill"></i>
                    <span>Validasi Data</span>
                </a>
            </div>
        </div>

        <div class="menu-group {{ isMenuOpen('spv/laporan') }}">
            <a href="javascript:void(0)" class="menu-toggle {{ isMenuActive('spv/laporan') }}">
                <i class="bi bi-file-text-fill"></i>
                <span>Laporan</span>
                <i class="bi bi-chevron-down ms-auto"></i>
            </a>
            <div class="submenu">
                <a href="/report/spv" class="{{ isMenuActive('/report/spv', true) }}">
                    <i class="bi bi-file-earmark-excel-fill"></i>
                    <span>Download Excel</span>
                </a>
            </div>
        </div>
        <a href="/approval/list" class="{{ isMenuActive('/approval/list', true) }}">
            <i class="bi bi-clipboard-check-fill"></i>
            <span>Approval</span>
        </a>

    </nav>
</div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Navbar -->
        <nav class="top-navbar">
            <!-- Hamburger Menu (Mobile Only) -->
            <div class="hamburger-menu" id="hamburgerMenu">
                <span></span>
                <span></span>
                <span></span>
            </div>

            <!-- Search Box -->
            <div class="search-box">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search...">
            </div>

            <!-- User Info -->
            <div class="user-info">
                <a href="{{ route('notifikasi') }}" class="notification-icon" title="Lihat Notifikasi">
                    <i class="bi bi-bell-fill"></i>
                    <span class="notification-badge"></span>
                </a>
                <div class="user-avatar">
                    <div class="avatar-placeholder">SPV</div>
                    <span style="font-weight: 500; color: #015255ff;">SPV QC</span>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin-left: 15px;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </nav>

<div class="content-area">
    @yield('content')
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Mobile Menu Toggle
        const hamburgerMenu = document.getElementById('hamburgerMenu');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            hamburgerMenu.classList.toggle('active');
        }

        hamburgerMenu.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);

        // Close sidebar when clicking on a link (mobile)
        const sidebarLinks = document.querySelectorAll('.sidebar-nav a');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth <= 768) {
                    toggleSidebar();
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', () => {
            if (window.innerWidth > 768) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                hamburgerMenu.classList.remove('active');
            }
        });
    </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@stack('scripts')

</body>
</html>