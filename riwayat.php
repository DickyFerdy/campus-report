<?php
require_once __DIR__ . '/proses/proses_riwayat.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Laporan - CampusReport</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>
        iconify-icon {
            display: inline-flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>

<body class="dashboard-page">

    <div class="dashboard-layout">

        <?php
        $current_page = 'riwayat';
        include 'includes/sidebar.php';
        ?>

        <main class="main-content">

            <?php include 'includes/topbar.php'; ?>

            <div class="history-header-wrapper">
                <div class="page-header" style="text-align: left; margin: 0;">
                    <h2>Riwayat Laporan</h2>
                    <p>Lihat dan tinjau semua laporan yang telah Anda kirim,<br>lengkap dengan status penanganannya.</p>
                </div>

                <div class="history-stats-group">
                    <div class="h-stat-card">
                        <p>Total Laporan</p>
                        <h3><?= sprintf("%02d", $stat_total) ?></h3>
                    </div>
                    <div class="h-stat-card blue-fill">
                        <p>Selesai</p>
                        <h3><?= sprintf("%02d", $stat_selesai) ?></h3>
                    </div>
                </div>
            </div>

            <?php if (isset($_SESSION['sukses_laporan'])): ?>
                <div style="background-color: #f6fbf5; border: 1px solid #c3e6cb; color: #1e7e34; padding: 12px 20px; border-radius: 8px; margin-bottom: 24px; display: flex; align-items: center; justify-content: center; font-size: 14px;">
                    <iconify-icon icon="lucide:check-circle-2" width="20" style="margin-right: 10px; color: #28a745;"></iconify-icon>
                    <?= htmlspecialchars($_SESSION['sukses_laporan']); ?>
                </div>
                <?php
                unset($_SESSION['sukses_laporan']);
                ?>
            <?php endif; ?>
            <div class="history-controls">
                <form action="riwayat.php" method="GET" class="search-form">
                    <iconify-icon icon="lucide:search" width="16" style="color: #9ca3af;"></iconify-icon>
                    <input type="text" name="search" placeholder="Cari berdasarkan judul atau lokasi..." value="<?= htmlspecialchars($search) ?>">
                    <input type="hidden" name="filter" value="<?= $filter ?>">
                    <input type="hidden" name="sort" value="<?= $sort ?>">
                </form>

                <div style="display: flex; gap: 24px; align-items: center; flex-wrap: wrap;">
                    <div class="filter-tabs" style="margin: 0; box-shadow: none; border: 1px solid var(--border-color);">
                        <a href="riwayat.php?search=<?= urlencode($search) ?>&filter=semua&sort=<?= $sort ?>" class="filter-tab <?= ($filter == 'semua') ? 'active' : '' ?>">Semua</a>
                        <a href="riwayat.php?search=<?= urlencode($search) ?>&filter=selesai&sort=<?= $sort ?>" class="filter-tab <?= ($filter == 'selesai') ? 'active' : '' ?>">Selesai</a>
                        <a href="riwayat.php?search=<?= urlencode($search) ?>&filter=diproses&sort=<?= $sort ?>" class="filter-tab <?= ($filter == 'diproses') ? 'active' : '' ?>">Diproses</a>
                    </div>

                    <div style="display: flex; align-items: center; gap: 8px; border: 1px solid var(--border-color); padding: 6px 12px; border-radius: 8px;">
                        <iconify-icon icon="lucide:list-filter" width="16" style="color: var(--text-muted);"></iconify-icon>
                        <select onchange="window.location.href=this.value" style="border:none; background:transparent; font-weight:600; color:var(--text-main); cursor:pointer; font-size:13px; outline:none;">
                            <option value="riwayat.php?search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=terbaru" <?= ($sort == 'terbaru') ? 'selected' : '' ?>>Terbaru</option>
                            <option value="riwayat.php?search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=terlama" <?= ($sort == 'terlama') ? 'selected' : '' ?>>Terlama</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="table-container">
                <table class="history-table">
                    <thead>
                        <tr>
                            <th>Judul Laporan</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($reports) > 0): ?>
                            <?php foreach ($reports as $report): ?>
                                <?php
                                $status_db = strtolower($report['status']);
                                $badge_class = 'kategori';
                                if ($status_db == 'menunggu') $badge_class = 'menunggu';
                                if ($status_db == 'diproses') $badge_class = 'diproses';
                                if ($status_db == 'selesai') $badge_class = 'success';
                                if ($status_db == 'ditolak') $badge_class = 'ditolak';
                                ?>
                                <tr>
                                    <td>
                                        <div class="td-title"><?= htmlspecialchars($report['judul_laporan']) ?></div>
                                        <div class="td-sub">ID: CR-<?= str_pad($report['id'], 4, "0", STR_PAD_LEFT) ?></div>
                                    </td>
                                    <td><span class="badge kategori"><?= htmlspecialchars($report['kategori']) ?></span></td>
                                    <td>
                                        <div style="display:flex; align-items:flex-start; gap:6px;">
                                            <iconify-icon icon="lucide:map-pin" width="14" style="color:var(--text-muted); margin-top:2px;"></iconify-icon>
                                            <div class="td-sub"><?= htmlspecialchars($report['gedung']) ?><br><?= htmlspecialchars($report['detail_lokasi']) ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="td-sub"><?= format_tgl_riwayat($report['created_at']) ?></div>
                                    </td>
                                    <td><span class="badge <?= $badge_class ?>"><?= strtoupper($report['status']) ?></span></td>
                                    <td>
                                        <a href="detail_laporan.php?id=<?= $report['id'] ?>" style="color: var(--primary-color); text-decoration: none; font-size: 13px; font-weight: 700;">Lihat Detail</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center; padding: 40px;">
                                    <iconify-icon icon="lucide:file-x-2" width="48" style="color: #cbd5e1; margin-bottom: 12px;"></iconify-icon>
                                    <p style="color: var(--text-muted); margin:0;">Tidak ada data laporan yang ditemukan.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <?php if ($total_pages > 1): ?>
                    <div class="pagination-wrapper">
                        <div class="pagination-info">
                            Menampilkan <?= count($reports) ?> dari <?= $total_rows ?> laporan
                        </div>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="riwayat.php?search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=<?= $sort ?>&page=<?= $page - 1 ?>" class="page-btn"><iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon></a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                <a href="riwayat.php?search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=<?= $sort ?>&page=<?= $i ?>" class="page-btn <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                            <?php endfor; ?>

                            <?php if ($page < $total_pages): ?>
                                <a href="riwayat.php?search=<?= urlencode($search) ?>&filter=<?= $filter ?>&sort=<?= $sort ?>&page=<?= $page + 1 ?>" class="page-btn"><iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon></a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="bottom-widgets-grid">
                <div class="action-widget" style="margin:0; position: relative; overflow: hidden;">
                    <iconify-icon icon="lucide:headset" width="180" style="position:absolute; right:-20px; bottom:-40px; opacity:0.1;"></iconify-icon>

                    <h3 style="font-weight: 700; font-size: 20px; margin-bottom: 12px;">Butuh Bantuan Mendesak?</h3>
                    <p style="font-size: 14px; margin-bottom: 24px; max-width: 80%;">Layanan darurat kampus tersedia 24/7 untuk menangani masalah keamanan kritis dan kecelakaan medis.</p>
                    <div style="display: flex; gap: 12px;">
                        <a href="#" class="btn-white" style="margin:0; display:inline-block; width:auto; padding: 10px 24px;">Hubungi Keamanan</a>
                        <a href="#" class="btn-outline" style="margin:0; display:inline-block; width:auto; padding: 10px 24px;">Daftar Kontak</a>
                    </div>
                </div>

                <div style="background: #f1f5f9; padding: 32px; border-radius: 16px; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center;">
                    <iconify-icon icon="lucide:message-square-heart" width="40" style="color: var(--primary-color); margin-bottom: 16px;"></iconify-icon>
                    <h4 style="margin: 0 0 12px 0; color: var(--text-main); font-size: 16px;">Punya Saran?</h4>
                    <p style="margin: 0 0 20px 0; font-size: 12px; color: var(--text-muted); line-height: 1.6;">Bantu kami meningkatkan kualitas fasilitas kampus melalui masukan Anda.</p>
                    <a href="#" style="color: var(--primary-color); font-weight: 700; font-size: 13px; text-decoration: none; display: flex; align-items: center; gap: 6px;">
                        Kirim Feedback <iconify-icon icon="lucide:arrow-right" width="14"></iconify-icon>
                    </a>
                </div>
            </div>

        </main>
    </div>

</body>

</html>