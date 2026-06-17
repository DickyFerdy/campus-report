<aside class="admin-sidebar" id="adminSidebar" aria-label="Main navigation">
    <div class="sidebar-header">
        <a class="brand-mark" href="dashboard.php" aria-label="admin dashboard">
            <span class="brand-icon"><iconify-icon icon="lucide:user-check" width="24"></iconify-icon></span>
            <span class="brand-copy">
                <span class="brand-title">Admin / Pengelola</span>
                <span class="brand-subtitle">Admin Console</span>
            </span>
        </a>
    </div>

    <nav class="sidebar-nav">
        <a class="nav-link <?= ($current_page == 'dashboard') ? 'active' : '' ?>" href="dashboard.php" aria-current="page">
            <span class="nav-icon"><iconify-icon icon="lucide:layout-dashboard" width="20"></span>
            <span class="nav-text">Dashboard</span>
        </a>
        <a class="nav-link <?= ($current_page == 'laporan_masuk') ? 'active' : '' ?>" href="laporan_masuk.php" aria-current="page">
            <span class="nav-icon"><iconify-icon icon="lucide:inbox" width="20"></iconify-icon></span>
            <span class="nav-text">Laporan Masuk</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <a class="nav-link logout-btn" href="logout.php">
            <span class="nav-icon"><iconify-icon icon="lucide:log-out" width="20"></span>
            <span class="nav-text">Logout</span>
        </a>
    </div>
</aside>