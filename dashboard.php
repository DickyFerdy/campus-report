<?php
require_once __DIR__ . '/proses/proses_dashboard.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - CampusReport</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    
    <style>
        iconify-icon {
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }
        /* style jika laporan kosong */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: var(--bg-surface);
            border-radius: 16px;
            border: 1px dashed var(--border-color);
            color: var(--text-muted);
        }
    </style>
</head>
<body class="dashboard-page">

    <div class="dashboard-layout">
        
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon">
                    <iconify-icon icon="lucide:graduation-cap" width="24"></iconify-icon>
                </div>
                <div class="brand-text">
                    <h3>CampusReport</h3>
                    <p>Akun Mahasiswa</p>
                </div>
            </div>

            <ul class="nav-menu">
                <li class="nav-item active">
                    <a href="dashboard.php">
                        <iconify-icon icon="lucide:layout-dashboard" width="20"></iconify-icon> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="buat_laporan.php">
                        <iconify-icon icon="lucide:file-edit" width="20"></iconify-icon> Buat Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#">
                        <iconify-icon icon="lucide:activity" width="20"></iconify-icon> Status Laporan
                    </a>
                </li>
                <li class="nav-item">
                    <a href="riwayat.php">
                        <iconify-icon icon="lucide:history" width="20"></iconify-icon> Riwayat
                    </a>
                </li>
            </ul>

            <div class="sidebar-bottom">
                <ul class="nav-menu">
                    <li class="nav-item">
                        <a href="logout.php" class="logout-btn">
                            <iconify-icon icon="lucide:log-out" width="20"></iconify-icon> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </aside>

        <main class="main-content">
            
            <header class="topbar">
                <div class="search-bar">
                    <iconify-icon icon="lucide:search" width="18" style="color: #9ca3af;"></iconify-icon>
                    <input type="text" placeholder="Cari laporan...">
                </div>
                <div class="topbar-right">
                    <a href="#" style="color: var(--text-muted); display: flex; align-items: center;">
                        <iconify-icon icon="lucide:bell" width="22"></iconify-icon>
                    </a>
                    
                    <div class="user-profile" style="margin-left: 8px;">
                        <div class="avatar"><?= $inisial ?></div>
                        <div class="user-info">
                            <h4><?= htmlspecialchars($nama_user) ?></h4>
                            <p>MHS-2024</p>
                        </div>
                    </div>
                </div>
            </header>

            <div class="dashboard-header">
                <div>
                    <h1>Dashboard</h1>
                    <p>Halo, <strong><?= htmlspecialchars($nama_user) ?></strong>! Siap melaporkan atau memantau fasilitas hari ini.</p>
                </div>
                <a href="buat_laporan.php" class="btn-primary">
                    <iconify-icon icon="lucide:plus" width="20"></iconify-icon> Buat Laporan Baru
                </a>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon" style="color: #3b82f6; background-color: #eff6ff;">
                        <iconify-icon icon="lucide:file-text" width="24"></iconify-icon>
                    </div>
                    <p style="font-weight: 500;">Total Laporan</p>
                    <h3><?= sprintf("%02d", $stat_total) ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: #f59e0b; background-color: #fffbeb;">
                        <iconify-icon icon="tdesign:task-time-filled" width="24"></iconify-icon>
                    </div>
                    <p style="font-weight: 500;">Menunggu Verifikasi</p>
                    <h3><?= sprintf("%02d", $stat_menunggu) ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: #8b5cf6; background-color: #f5f3ff;">
                        <iconify-icon icon="uim:process" width="24"></iconify-icon>
                    </div>
                    <p style="font-weight: 500;">Diproses</p>
                    <h3><?= sprintf("%02d", $stat_diproses) ?></h3>
                </div>
                <div class="stat-card">
                    <div class="stat-icon" style="color: #10b981; background-color: #ecfdf5;">
                        <iconify-icon icon="prime:check-circle" width="28"></iconify-icon>
                    </div>
                    <p style="font-weight: 500;">Selesai</p>
                    <h3><?= sprintf("%02d", $stat_selesai) ?></h3>
                </div>
            </div>

            <div class="content-grid">
                
                <div class="recent-reports-section">
                    <div class="section-title">
                        <span style="font-weight: 700;">Laporan Terkini</span>
                        <a href="#">Lihat Semua</a>
                    </div>

                    <?php if (count($recent_reports) > 0): ?>
                        <?php foreach ($recent_reports as $report): ?>
                            <?php 
                                // Format warna badge status
                                $status_lower = strtolower($report['status']); 
                                $status_class = ($status_lower == 'menunggu') ? 'menunggu' : (($status_lower == 'diproses') ? 'diproses' : 'kategori');
                                
                                // Format tanggal dari database (Contoh: 24 Okt 2024)
                                $tanggal_format = date('d M Y', strtotime($report['created_at']));

                                // Path gambar
                                $img_src = !empty($report['foto_bukti']) ? 'assets/uploads/' . htmlspecialchars($report['foto_bukti']) : 'https://via.placeholder.com/150/e2e8f0/94a3b8?text=Foto';
                            ?>
                            <div class="report-card">
                                <img src="<?= $img_src ?>" alt="Foto Bukti" class="report-img">
                                <div class="report-details">
                                    <span class="badge kategori"><?= htmlspecialchars($report['kategori']) ?></span>
                                    <h4><?= htmlspecialchars($report['judul_laporan']) ?></h4>
                                    <div class="report-meta" style="display: flex; gap: 12px;">
                                        <span style="display: flex; align-items: center; gap: 4px;">
                                            <iconify-icon icon="lucide:map-pin" width="14"></iconify-icon> <?= htmlspecialchars($report['gedung']) ?>
                                        </span>
                                        <span style="display: flex; align-items: center; gap: 4px;">
                                            <iconify-icon icon="lucide:calendar" width="14"></iconify-icon> <?= $tanggal_format ?>
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="badge <?= $status_class ?>"><?= strtoupper($report['status']) ?></span>
                                </div>
                                <div class="report-action" style="padding-left: 10px;">
                                    <iconify-icon icon="lucide:chevron-right" width="20"></iconify-icon>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="empty-state">
                            <iconify-icon icon="lucide:inbox" width="48" style="color: #cbd5e1; margin-bottom: 12px;"></iconify-icon>
                            <p>Belum ada laporan yang Anda buat.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="sidebar-widgets">
                    <div class="action-widget">
                        <h3 style="font-weight: 700;">Butuh Bantuan?</h3>
                        <p>Laporkan masalah fasilitas atau keamanan kampus hanya dengan beberapa langkah mudah.</p>
                        <a href="buat_laporan.php" class="btn-white" style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                            <iconify-icon icon="lucide:camera" width="18"></iconify-icon> Ambil Foto & Lapor
                        </a>
                        <a href="#" class="btn-outline" style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                            <iconify-icon icon="lucide:phone-call" width="18"></iconify-icon> Layanan Darurat
                        </a>
                    </div>

                    <div class="stat-card">
                        <h4 style="margin: 0 0 16px 0; color: var(--text-main);">Statistik Bulan Ini</h4>
                        <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 8px;">
                            <span style="color: var(--text-muted); font-weight: 500;">Penyelesaian Laporan</span>
                            <span style="font-weight: 700; color: var(--primary-color);">85%</span>
                        </div>
                        <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden; margin-bottom: 24px;">
                            <div style="width: 85%; height: 100%; background: var(--primary-color);"></div>
                        </div>
                        
                        <div style="background: #f8fafc; padding: 16px; border-radius: 8px; border: 1px solid var(--border-color); display: flex; align-items: center; gap: 12px;">
                            <div style="color: #10b981; background: #d1fae5; width: 32px; height: 32px; border-radius: 50%; display: flex; justify-content: center; align-items: center;">
                                <iconify-icon icon="lucide:trending-up" width="18"></iconify-icon>
                            </div>
                            <div>
                                <p style="margin: 0 0 4px 0; font-size: 12px; color: var(--text-muted); font-weight: 500;">Laporan Selesai</p>
                                <h5 style="margin: 0; font-size: 14px; color: var(--text-main);">+12 dari bulan lalu</h5>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </main>
    </div>

    <a href="buat_laporan.php" style="color: inherit; text-decoration: none;">
        <div class="fab">
            <iconify-icon icon="lucide:plus" width="28"></iconify-icon>
        </div>
    </a>

</body>
</html>