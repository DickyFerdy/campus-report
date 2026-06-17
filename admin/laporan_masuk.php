<?php
require_once __DIR__ . '/proses/proses_laporan.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Laporan Masuk - CampusReport</title>

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
        $current_page = 'laporan_masuk';
        include 'includes/sidebar.php';
        ?>

        <div class="admin-main">
            <?php include 'includes/topbar.php'; ?>

            <main class="dashboard-content">
                <div class="container-fluid px-3 px-lg-4">
                    <div class="page-header" style="text-align: left;">
                        <h2>Daftar Laporan Masuk</h2>
                        <p>Kelola dan tinjau semua laporan keamanan serta infrastruktur kampus secara real-time.</p>
                    </div>

                    <div class="table-container" style="padding: 24px;">

                        <form action="laporan_masuk.php" method="GET" class="admin-filter-row">
                            <div class="filter-group">
                                <label>Kategori</label>
                                <select name="kategori" class="filter-select">
                                    <option value="semua" <?= ($kategori == 'semua') ? 'selected' : '' ?>>Semua Kategori</option>
                                    <option value="Kelistrikan & Lampu" <?= ($kategori == 'Kelistrikan & Lampu') ? 'selected' : '' ?>>Kelistrikan & Lampu</option>
                                    <option value="AC & Ventilasi" <?= ($kategori == 'AC & Ventilasi') ? 'selected' : '' ?>>AC & Ventilasi</option>
                                    <option value="Furnitur Kelas" <?= ($kategori == 'Furnitur Kelas') ? 'selected' : '' ?>>Furnitur Kelas</option>
                                    <option value="Proyektor & IT" <?= ($kategori == 'Proyektor & IT') ? 'selected' : '' ?>>Proyektor & IT</option>
                                    <option value="Infrastruktur & Bangunan" <?= ($kategori == 'Infrastruktur & Bangunan') ? 'selected' : '' ?>>Infrastruktur & Bangunan</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Status Laporan</label>
                                <select name="status" class="filter-select">
                                    <option value="semua" <?= ($status == 'semua') ? 'selected' : '' ?>>Semua Status</option>
                                    <option value="menunggu" <?= ($status == 'menunggu') ? 'selected' : '' ?>>Menunggu</option>
                                    <option value="diproses" <?= ($status == 'diproses') ? 'selected' : '' ?>>Diproses</option>
                                    <option value="selesai" <?= ($status == 'selesai') ? 'selected' : '' ?>>Selesai</option>
                                    <option value="ditolak" <?= ($status == 'ditolak') ? 'selected' : '' ?>>Ditolak</option>
                                </select>
                            </div>

                            <div class="filter-group">
                                <label>Urutkan Tanggal</label>
                                <select name="sort" class="filter-select">
                                    <option value="terbaru" <?= ($sort == 'terbaru') ? 'selected' : '' ?>>Terbaru</option>
                                    <option value="terlama" <?= ($sort == 'terlama') ? 'selected' : '' ?>>Terlama</option>
                                </select>
                            </div>

                            <button type="submit" class="btn-filter">
                                <iconify-icon icon="lucide:filter"></iconify-icon> Terapkan Filter
                            </button>
                        </form>

                        <table class="history-table" style="margin-top: 16px;">
                            <thead>
                                <tr>
                                    <th>Judul Laporan</th>
                                    <th>Pelapor</th>
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
                                        $st = strtolower($report['status']);
                                        $badge_class = 'kategori';
                                        if ($st == 'menunggu') $badge_class = 'menunggu';
                                        if ($st == 'diproses') $badge_class = 'diproses';
                                        if ($st == 'selesai') $badge_class = 'success';
                                        if ($st == 'ditolak') $badge_class = 'ditolak';

                                        // Nonaktifkan tombol ACC/Tolak jika status sudah final
                                        $disabled = ($st == 'selesai' || $st == 'ditolak') ? 'disabled' : '';
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="td-title"><?= htmlspecialchars($report['judul_laporan']) ?></div>
                                                <div class="td-sub">#RP-<?= str_pad($report['id'], 5, "0", STR_PAD_LEFT) ?></div>
                                            </td>
                                            <td>
                                                <?php
                                                // Memisahkan nama depan dan belakang agar rapi bertumpuk seperti desain
                                                $nm = explode(" ", htmlspecialchars($report['pelapor']));
                                                echo "<span style='color:var(--text-main); font-size:13px; font-weight: 500;'>" . $nm[0] .  "</span><br>";
                                                echo "<span style='color:var(--text-main); font-size:13px; font-weight: 500;' class='td-sub'>" . (isset($nm[1]) ? $nm[1] : '') . "</span>";
                                                ?>
                                            </td>
                                            <td>
                                                <div class="td-sub"><?= htmlspecialchars($report['gedung']) ?><br><?= htmlspecialchars($report['detail_lokasi']) ?></div>
                                            </td>
                                            <td>
                                                <div class="td-sub"><?= format_tgl_admin($report['created_at']) ?></div>
                                            </td>
                                            <td><span class="badge <?= $badge_class ?>"><?= strtoupper($report['status']) ?></span></td>
                                            <td>
                                                <div class="aksi-icons">
                                                    <a href="detail_laporan_admin.php?id=<?= $report['id'] ?>" class="icon-btn icon-view" title="Lihat Detail"><iconify-icon icon="lucide:eye"></iconify-icon></a>

                                                    <a href="#" class="icon-btn icon-approve <?= $disabled ?>" title="Proses Laporan"><iconify-icon icon="lucide:check-circle"></iconify-icon></a>

                                                    <a href="#" class="icon-btn icon-reject <?= $disabled ?>" title="Tolak Laporan"><iconify-icon icon="lucide:x-circle"></iconify-icon></a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align: center; padding: 40px; color: var(--text-muted);">Tidak ada laporan yang sesuai dengan filter.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>

                        <?php if ($total_pages > 1): ?>
                            <div class="pagination-wrapper" style="border-top: none; padding: 24px 0 0 0;">
                                <div class="pagination-info">Menampilkan <?= count($reports) ?> dari <?= $total_rows ?> laporan</div>
                                <div class="pagination">
                                    <?php if ($page > 1): ?>
                                        <a href="?kategori=<?= urlencode($kategori) ?>&status=<?= $status ?>&sort=<?= $sort ?>&page=<?= $page - 1 ?>" class="page-btn"><iconify-icon icon="lucide:chevron-left" width="16"></iconify-icon></a>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                        <a href="?kategori=<?= urlencode($kategori) ?>&status=<?= $status ?>&sort=<?= $sort ?>&page=<?= $i ?>" class="page-btn <?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
                                    <?php endfor; ?>

                                    <?php if ($page < $total_pages): ?>
                                        <a href="?kategori=<?= urlencode($kategori) ?>&status=<?= $status ?>&sort=<?= $sort ?>&page=<?= $page + 1 ?>" class="page-btn"><iconify-icon icon="lucide:chevron-right" width="16"></iconify-icon></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="admin-bottom-stats">
                        <div class="stat-card-b">
                            <h4>LAPORAN HARI INI</h4>
                            <h2><?= sprintf("%02d", $stat_hari_ini) ?></h2>
                            <p style="color: #10b981;"><iconify-icon icon="lucide:trending-up" width="14"></iconify-icon> Sistem berjalan lancar</p>
                        </div>

                        <div class="stat-card-b">
                            <h4>RATA-RATA RESPON</h4>
                            <h2><?= $stat_respon ?></h2>
                            <p>Durasi verifikasi tim admin</p>
                        </div>

                        <div class="stat-card-b blue-theme">
                            <h4>LAPORAN URGENT</h4>
                            <h2><?= sprintf("%02d", $stat_urgent) ?></h2>
                            <p><span style="width:6px; height:6px; background:white; border-radius:50%; display:inline-block;"></span> Perlu perhatian segera</p>
                        </div>
                    </div>
                </div>
            </main>

        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>