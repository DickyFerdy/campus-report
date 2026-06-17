<?php
require_once __DIR__ . '/proses/proses_dashboard.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - CampusReport</title>

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>
        body {
            padding: 0;
        }

        iconify-icon {
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body>
    <div class="admin-shell">
        <div class="sidebar-backdrop" data-sidebar-close></div>

        <?php
        $current_page = 'dashboard';
        include 'includes/sidebar.php';
        ?>

        <div class="admin-main">
            <?php include 'includes/topbar.php'; ?>

            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4">
                    <div class="page-header" style="text-align: left;">
                        <h2>Dashboard Admin</h2>
                        <p>Selamat datang kembali. Berikut ringkasan laporan hari ini.</p>
                    </div>

                    <div class="admin-stats-grid">
                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon icon-total"><iconify-icon icon="lucide:bar-chart-2"></iconify-icon></div>
                                <span class="admin-stat-title">TOTAL</span>
                            </div>
                            <h3 class="admin-stat-value"><?= sprintf("%02d", $stat_total) ?></h3>
                            <p class="admin-stat-desc">Laporan Masuk</p>
                        </div>

                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon icon-menunggu"><iconify-icon icon="lucide:clipboard-list"></iconify-icon></div>
                                <span class="admin-stat-title">MENUNGGU</span>
                            </div>
                            <h3 class="admin-stat-value"><?= sprintf("%02d", $stat_menunggu) ?></h3>
                            <p class="admin-stat-desc">Verifikasi</p>
                        </div>

                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon icon-proses"><iconify-icon icon="lucide:refresh-cw"></iconify-icon></div>
                                <span class="admin-stat-title">PROSES</span>
                            </div>
                            <h3 class="admin-stat-value"><?= sprintf("%02d", $stat_diproses) ?></h3>
                            <p class="admin-stat-desc">Sedang Diproses</p>
                        </div>

                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon icon-selesai"><iconify-icon icon="lucide:check-circle-2"></iconify-icon></div>
                                <span class="admin-stat-title">SELESAI</span>
                            </div>
                            <h3 class="admin-stat-value"><?= sprintf("%02d", $stat_selesai) ?></h3>
                            <p class="admin-stat-desc">Teratasi</p>
                        </div>

                        <div class="admin-stat-card">
                            <div class="admin-stat-header">
                                <div class="admin-stat-icon icon-ditolak"><iconify-icon icon="lucide:x-circle"></iconify-icon></div>
                                <span class="admin-stat-title">DITOLAK</span>
                            </div>
                            <h3 class="admin-stat-value"><?= sprintf("%02d", $stat_ditolak) ?></h3>
                            <p class="admin-stat-desc">Laporan Ditolak</p>
                        </div>
                    </div>

                    <div class="table-container">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 24px; border-bottom: 1px solid var(--border-color);">
                            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: var(--text-main);">Laporan Terbaru</h3>
                            <a href="laporan_masuk.php" style="font-size: 13px; font-weight: 700; color: var(--primary-color); text-decoration: none;">Lihat Semua</a>
                        </div>

                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Judul Laporan</th>
                                    <th>Lokasi</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (count($recent_reports) > 0): ?>
                                    <?php foreach ($recent_reports as $report): ?>
                                        <?php
                                        $status_db = strtolower($report['status']);
                                        $badge_class = 'kategori'; // Default fallback
                                        if ($status_db == 'menunggu') $badge_class = 'menunggu';
                                        if ($status_db == 'diproses') $badge_class = 'diproses';
                                        if ($status_db == 'selesai') $badge_class = 'success';
                                        if ($status_db == 'ditolak') $badge_class = 'ditolak';
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="td-title"><?= htmlspecialchars($report['judul_laporan']) ?></div>
                                                <div class="td-sub"><span style="color: #64748b;"><?= htmlspecialchars($report['kategori']) ?></span></div>
                                            </td>
                                            <td>
                                                <div class="td-sub"><?= htmlspecialchars($report['gedung']) ?><br><?= htmlspecialchars($report['detail_lokasi']) ?></div>
                                            </td>
                                            <td>
                                                <div class="td-sub"><?= format_tgl_admin($report['created_at']) ?></div>
                                            </td>
                                            <td><span class="badge <?= $badge_class ?>"><?= ucfirst(htmlspecialchars($report['status'])) ?></span></td>
                                            <td>
                                                <a href="detail_laporan_admin.php?id=<?= (int)$report['id'] ?>" class="btn-action-light">Lihat Detail</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" style="text-align: center; padding: 40px;">
                                            <iconify-icon icon="lucide:inbox" width="48" style="color: #cbd5e1; margin-bottom: 12px;"></iconify-icon>
                                            <p style="color: var(--text-muted); margin:0;">Belum ada laporan yang masuk ke sistem.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>