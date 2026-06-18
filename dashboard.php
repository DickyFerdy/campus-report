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

        <?php
        $current_page = 'dashboard';
        include 'includes/sidebar.php';
        ?>

        <main class="main-content">

            <?php include 'includes/topbar.php'; ?>
            
            <?php if (isset($_SESSION['sukses_laporan'])): ?>
                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 14px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                    <iconify-icon icon="lucide:check-circle" width="18"></iconify-icon>
                    <span><?= $_SESSION['sukses_laporan']; ?></span>
                </div>
                <?php unset($_SESSION['sukses_laporan']); ?>
            <?php endif; ?>            

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
                        <a href="riwayat.php">Lihat Semua</a>
                    </div>

                    <?php if (count($recent_reports) > 0): ?>
                        <?php foreach ($recent_reports as $report): ?>
                            <?php
                            // Format warna badge status
                            $status_lower = strtolower($report['status']);
                            if ($status_lower == 'menunggu') {
                                $status_class = 'menunggu';
                            } elseif ($status_lower == 'diproses') {
                                $status_class = 'diproses';
                            } elseif ($status_lower == 'selesai') {
                                $status_class = 'success';
                            } elseif ($status_lower == 'ditolak') {
                                $status_class = 'ditolak';
                            } else {
                                $status_class = 'kategori';
                            }

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
                                <a href="detail_laporan.php?id=<?= $report['id'] ?>" class="report-action" style="padding-left: 10px; color: inherit;">
                                    <iconify-icon icon="lucide:chevron-right" width="20"></iconify-icon>
                                </a>
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