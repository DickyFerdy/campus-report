<?php
$current_page = $current_page ?? 'dashboard'; 
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon"><iconify-icon icon="lucide:graduation-cap" width="24"></iconify-icon></div>
        <div class="brand-text"><h3>CampusReport</h3><p>Akun Mahasiswa</p></div>
    </div>
    
    <ul class="nav-menu">
        <li class="nav-item <?= ($current_page == 'dashboard') ? 'active' : '' ?>">
            <a href="dashboard.php"><iconify-icon icon="lucide:layout-dashboard" width="20"></iconify-icon> Dashboard</a>
        </li>
        <li class="nav-item <?= ($current_page == 'buat_laporan') ? 'active' : '' ?>">
            <a href="buat_laporan.php"><iconify-icon icon="lucide:file-edit" width="20"></iconify-icon> Buat Laporan</a>
        </li>
        <li class="nav-item <?= ($current_page == 'status_laporan') ? 'active' : '' ?>">
            <a href="status_laporan.php"><iconify-icon icon="lucide:activity" width="20"></iconify-icon> Status Laporan</a>
        </li>
        <li class="nav-item <?= ($current_page == 'riwayat') ? 'active' : '' ?>">
            <a href="riwayat.php"><iconify-icon icon="lucide:history" width="20"></iconify-icon> Riwayat</a>
        </li>
    </ul>
    
    <div class="sidebar-bottom">
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="logout.php" class="logout-btn"><iconify-icon icon="lucide:log-out" width="20"></iconify-icon> Keluar</a>
            </li>
        </ul>
    </div>
</aside>