<?php
require_once __DIR__ . '/proses/proses_detail.php';

// Inisialisasi Session dan CSRF Token
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan Admin - CampusReport</title>

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
                <div class="container-fluid px-3 px-lg-4 py-3">
                    <a href="laporan_masuk.php" style="display: inline-flex; align-items: center; gap: 6px; color: var(--text-muted); text-decoration: none; font-size: 13px; font-weight: 600; margin-bottom: 16px;">
                        <iconify-icon icon="lucide:arrow-left"></iconify-icon> Kembali ke Daftar Laporan
                    </a>

                    <?= $pesan ?>

                    <form action="" method="POST" class="admin-detail-grid">

                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

                        <div class="left-col">
                            <div class="admin-detail-header" style="margin-bottom: 24px;">
                                <div class="admin-detail-meta" style="color: var(--primary-color); font-weight: 600;">
                                    <iconify-icon icon="lucide:calendar"></iconify-icon> <?= $tanggal_dibuat ?>
                                </div>
                                <h2><?= htmlspecialchars($detail['judul_laporan']) ?></h2>
                                <div class="admin-detail-meta">
                                    <iconify-icon icon="lucide:map-pin"></iconify-icon> <?= htmlspecialchars($detail['gedung']) ?>, <?= htmlspecialchars($detail['detail_lokasi']) ?>
                                </div>
                            </div>

                            <?php if (!empty($detail['foto_bukti'])): ?>
                                <img src="../assets/uploads/<?= htmlspecialchars($detail['foto_bukti']) ?>" alt="Foto Bukti" class="admin-foto-bukti">
                            <?php else: ?>
                                <div class="admin-foto-bukti" style="display:flex; justify-content:center; align-items:center; color:#94a3b8; flex-direction:column; gap:8px;">
                                    <iconify-icon icon="lucide:image-off" width="32"></iconify-icon>
                                    <span>Tidak ada foto bukti terlampir</span>
                                </div>
                            <?php endif; ?>

                            <div class="admin-card-box">
                                <h4 style="margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px; color: var(--text-main);">
                                    <iconify-icon icon="lucide:file-text" style="color: var(--primary-color);"></iconify-icon> Deskripsi Laporan
                                </h4>
                                <p style="color: #475569; font-size: 14px; line-height: 1.6; margin: 0;">
                                    <?= nl2br(htmlspecialchars($detail['deskripsi'])) ?>
                                </p>

                                <div class="admin-info-grid">
                                    <div class="info-item">
                                        <h5>Pelapor</h5>
                                        <p><?= htmlspecialchars($detail['pelapor']) ?></p>
                                    </div>
                                    <div class="info-item">
                                        <h5>Kategori</h5>
                                        <p><?= htmlspecialchars($detail['kategori']) ?></p>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-item">
                                            <h5>Prioritas</h5>
                                            <?php
                                            $prio = strtolower($detail['prioritas']);
                                            $prio_class = ($prio == 'tinggi') ? 'tinggi' : (($prio == 'sedang') ? 'sedang' : 'rendah');
                                            ?>
                                            <span class="badge-prioritas <?= $prio_class ?>">
                                                <?= strtoupper($detail['prioritas']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="admin-card-box">
                                <h4 style="margin: 0 0 16px 0; display: flex; align-items: center; gap: 8px; color: var(--text-main);">
                                    <iconify-icon icon="lucide:message-square" style="color: var(--primary-color);"></iconify-icon> Berikan Tanggapan
                                </h4>
                                <textarea name="respon_admin" class="form-control" rows="4" placeholder="Tuliskan instruksi, balasan, atau alasan penolakan di sini..."><?= htmlspecialchars($detail['respon_admin'] ?? '') ?></textarea>

                                <button type="submit" name="action" value="tanggapan_saja" class="btn-blue" style="padding: 10px 20px; border-radius: 8px; border: none; cursor: pointer; font-weight: 600; margin-top: 12px; display: inline-flex; align-items: center; gap: 8px;">
                                    <iconify-icon icon="lucide:send"></iconify-icon> Simpan Tanggapan
                                </button>
                            </div>
                        </div>

                        <div class="right-col">
                            <div class="admin-card-box">

                                <?php
                                $st = strtolower($detail['status']);
                                $icon = 'lucide:hourglass';
                                if ($st == 'diproses') $icon = 'lucide:refresh-cw';
                                if ($st == 'selesai') $icon = 'lucide:check-circle-2';
                                if ($st == 'ditolak') $icon = 'lucide:x-circle';
                                ?>
                                <div class="status-current-box current-<?= $st ?>">
                                    <h4>Status Saat Ini</h4>
                                    <div class="status-badge-lg">
                                        <iconify-icon icon="<?= $icon ?>"></iconify-icon>
                                        <?= strtoupper($detail['status']) ?>
                                    </div>
                                </div>

                                <div class="tracker-vertical">
                                    <div class="v-step done">
                                        <div class="v-step-dot"></div>
                                        <h5>Laporan Dibuat</h5>
                                        <p><?= date('d M, H:i', strtotime($detail['created_at'])) ?></p>
                                    </div>

                                    <div class="v-step <?= ($st == 'diproses' || $st == 'selesai') ? 'done' : (($st == 'menunggu') ? 'active' : '') ?>">
                                        <div class="v-step-dot"></div>
                                        <h5>Verifikasi Admin</h5>
                                        <p><?= ($st == 'menunggu') ? 'Pending' : 'Selesai' ?></p>
                                    </div>

                                    <?php if ($st != 'ditolak'): ?>
                                        <div class="v-step <?= ($st == 'selesai') ? 'done' : (($st == 'diproses') ? 'active' : '') ?>">
                                            <div class="v-step-dot"></div>
                                            <h5>Proses Perbaikan</h5>
                                            <p><?= ($st == 'selesai') ? 'Selesai' : (($st == 'diproses') ? 'Sedang Ditangani' : 'Pending') ?></p>
                                        </div>

                                        <div class="v-step <?= ($st == 'selesai') ? 'active' : '' ?>">
                                            <div class="v-step-dot"></div>
                                            <h5>Selesai</h5>
                                            <p><?= ($st == 'selesai') ? date('d M, H:i', strtotime($detail['updated_at'])) : 'Pending' ?></p>
                                        </div>
                                    <?php else: ?>
                                        <div class="v-step active" style="margin-top: -10px;">
                                            <div class="v-step-dot" style="background:#ef4444; border-color:var(--bg-surface);"></div>
                                            <h5 style="color:#ef4444;">Laporan Ditolak</h5>
                                            <p><?= date('d M, H:i', strtotime($detail['updated_at'])) ?></p>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <div class="btn-action-group" style="border-top: 1px solid var(--border-color); padding-top: 24px;">

                                    <?php if ($st == 'menunggu'): ?>
                                        <button type="submit" name="action" value="proses" class="btn-block btn-blue">
                                            <iconify-icon icon="lucide:settings"></iconify-icon> Proses Laporan
                                        </button>
                                        <button type="submit" name="action" value="tolak" class="btn-block btn-red">
                                            <iconify-icon icon="lucide:x-circle"></iconify-icon> Tolak & Arsipkan
                                        </button>
                                    <?php elseif ($st == 'diproses'): ?>
                                        <button type="submit" name="action" value="selesai" class="btn-block btn-green">
                                            <iconify-icon icon="lucide:check-circle"></iconify-icon> Tandai Selesai
                                        </button>
                                    <?php else: ?>
                                        <div style="text-align: center; color: var(--text-muted); font-size: 13px; font-weight: 600; padding: 12px; background: #f8fafc; border-radius: 12px;">
                                            Laporan telah ditutup.
                                        </div>
                                    <?php endif; ?>

                                </div>

                            </div>
                        </div>

                    </form>
                </div>
            </main>

        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>