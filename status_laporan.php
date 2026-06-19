<?php
require_once __DIR__ . '/proses/proses_status.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Laporan - CampusReport</title>
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
        $current_page = 'status_laporan';
        include 'includes/sidebar.php';
        ?>

        <main class="main-content">

            <?php include 'includes/topbar.php'; ?>

            <div class="page-header" style="text-align: left;">
                <h2>Status Laporan</h2>
                <p>Pantau perkembangan laporan fasilitas Anda secara real-time.</p>
            </div>

            <div class="filter-bar">
                <div class="filter-tabs">
                    <a href="status_laporan.php?filter=semua&sort=<?= $sort ?>" class="filter-tab <?= ($filter == 'semua') ? 'active' : '' ?>">Semua Laporan</a>
                    <a href="status_laporan.php?filter=menunggu&sort=<?= $sort ?>" class="filter-tab <?= ($filter == 'menunggu') ? 'active' : '' ?>">Menunggu</a>
                    <a href="status_laporan.php?filter=diproses&sort=<?= $sort ?>" class="filter-tab <?= ($filter == 'diproses') ? 'active' : '' ?>">Diproses</a>
                    <a href="status_laporan.php?filter=selesai&sort=<?= $sort ?>" class="filter-tab <?= ($filter == 'selesai') ? 'active' : '' ?>">Selesai</a>
                    <a href="status_laporan.php?filter=ditolak&sort=<?= $sort ?>" class="filter-tab <?= ($filter == 'ditolak') ? 'active' : '' ?>">Ditolak</a>
                </div>

                <div style="font-size: 13px; color: var(--text-muted); position: relative; display: flex; align-items: center;">
                    Urutkan:
                    <select onchange="window.location.href=this.value" style="border:none; background:transparent; font-weight:700; color:var(--text-main); cursor:pointer; font-family:inherit; font-size:13px; appearance:none; padding-left:4px; padding-right:16px; outline:none;">
                        <option value="status_laporan.php?filter=<?= $filter ?>&sort=terbaru" <?= ($sort == 'terbaru') ? 'selected' : '' ?>>Terbaru</option>
                        <option value="status_laporan.php?filter=<?= $filter ?>&sort=terlama" <?= ($sort == 'terlama') ? 'selected' : '' ?>>Terlama</option>
                    </select>
                    <iconify-icon icon="lucide:chevron-down" width="14" style="position:absolute; right:0; pointer-events:none; color:var(--text-main);"></iconify-icon>
                </div>
            </div>

            <?php if (count($reports) > 0): ?>
                <?php foreach ($reports as $report): ?>
                    <?php
                    $status_db = strtolower($report['status']);
                    $format_id = "ID: CR-" . str_pad($report['id'], 4, "0", STR_PAD_LEFT);

                    $step1 = "";
                    $step2 = "";
                    $step3 = "";
                    $step4 = "";
                    $icon1 = "lucide:check";
                    $icon2 = "lucide:check";
                    $icon3 = "lucide:check";
                    $icon4 = "lucide:check";

                    if ($status_db == 'menunggu') {
                        $step1 = "waiting-step";
                        $icon1 = "lucide:hourglass";
                    } elseif ($status_db == 'diproses') {
                        $step1 = "completed";
                        $step2 = "completed";
                        $step3 = "active-step";
                        $icon3 = "lucide:loader-2";
                    } elseif ($status_db == 'selesai') {
                        $step1 = "completed";
                        $step2 = "completed";
                        $step3 = "completed";
                        $step4 = "completed";
                    } elseif ($status_db == 'ditolak') {
                        $step1 = "completed";
                        $step2 = "rejected-step";
                        $icon2 = "lucide:x";
                    }
                    ?>

                    <div class="status-card-split">

                        <div class="status-left-panel">
                            <div class="status-header-meta">
                                <span class="status-id-badge"><?= $format_id ?></span>
                                <span class="status-time">
                                    <iconify-icon icon="lucide:clock" width="12"></iconify-icon> <?= time_ago($report['created_at']) ?>
                                </span>
                            </div>
                            <h3><?= htmlspecialchars($report['judul_laporan']) ?></h3>
                            <p><iconify-icon icon="lucide:map-pin" width="14"></iconify-icon> <?= htmlspecialchars($report['gedung']) ?>, <?= htmlspecialchars($report['detail_lokasi']) ?></p>
                            <p><iconify-icon icon="lucide:calendar" width="14"></iconify-icon> <?= format_tgl($report['created_at']) ?></p>

                            <a href="detail_laporan.php?id=<?= $report['id'] ?>" class="btn-secondary" style="margin-top: 16px; display: inline-block; text-decoration: none; padding: 10px 20px; font-size: 13px;">
                                Lihat Detail
                            </a>
                        </div>

                        <div class="status-right-panel">
                            <div class="tracker-header">
                                <?= ($status_db == 'ditolak') ? '<span style="color:#ef4444;">LAPORAN DITOLAK</span>' : 'PROGRESS TRACKER' ?>
                            </div>

                            <div class="stepper-wrapper">
                                <div class="stepper-item <?= $step1 ?>">
                                    <div class="step-counter"><iconify-icon icon="<?= $icon1 ?>" width="16"></iconify-icon></div>
                                    <div class="step-name">Menunggu<br>Verifikasi</div>
                                </div>

                                <div class="stepper-item <?= $step2 ?>">
                                    <div class="step-counter"><iconify-icon icon="<?= $icon2 ?>" width="16"></iconify-icon></div>
                                    <div class="step-name"><?= ($status_db == 'ditolak') ? 'Ditolak' : 'Disetujui' ?></div>
                                </div>

                                <div class="stepper-item <?= $step3 ?>">
                                    <div class="step-counter"><iconify-icon icon="<?= $icon3 ?>" width="16"></iconify-icon></div>
                                    <div class="step-name">Diproses</div>
                                </div>

                                <div class="stepper-item <?= $step4 ?>">
                                    <div class="step-counter"><iconify-icon icon="<?= $icon4 ?>" width="16"></iconify-icon></div>
                                    <div class="step-name">Selesai</div>
                                </div>
                            </div>

                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state" style="margin-top: 40px; padding: 60px 20px;">
                    <iconify-icon icon="lucide:folder-search" width="64" style="color: #cbd5e1; margin-bottom: 16px;"></iconify-icon>
                    <h3 style="color: var(--text-main);">Tidak ada laporan</h3>
                    <p style="color: var(--text-muted);">Anda belum memiliki laporan dengan status ini.</p>
                </div>
            <?php endif; ?>

        </main>
    </div>

</body>

</html>