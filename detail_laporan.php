<?php
require_once __DIR__ . '/proses/proses_detail.php';

// Menyiapkan Badge Status & Logika Timeline
$status = strtolower($detail['status']);

if ($status == 'menunggu') {
    $badge_class = 'menunggu';
} elseif ($status == 'diproses') {
    $badge_class = 'diproses';
} elseif ($status == 'selesai') {
    $badge_class = 'success';
} elseif ($status == 'ditolak') {
    $badge_class = 'ditolak';
} else {
    $badge_class = 'kategori';
}

// Format ID Laporan
$format_id = "#CR-" . str_pad($detail['id'], 4, "0", STR_PAD_LEFT);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan <?= $format_id ?> - CampusReport</title>
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

            <a href="dashboard.php" class="back-link">
                <iconify-icon icon="lucide:arrow-left" width="16"></iconify-icon> Daftar Laporan
            </a>

            <div class="detail-header-container">
                <div class="detail-title-group">
                    <h2>Detail Laporan</h2>
                    <div class="detail-id-status">
                        <span><?= $format_id ?></span>
                        <span class="badge <?= $badge_class ?>"><?= strtoupper($detail['status']) ?></span>
                    </div>
                </div>
                <div class="detail-actions">
                    <a href="dashboard.php" class="btn-secondary" style="text-decoration:none; display:flex; align-items:center;">Kembali</a>
                    <?php if ($status == 'menunggu'): ?>
                        <a href="edit_laporan.php?id=<?= $report_id ?>" class="btn-primary">
                            <iconify-icon icon="lucide:edit-2" width="16"></iconify-icon> Edit Laporan
                        </a>
                    
                        <a href="proses/hapus_laporan.php?id=<?= $report_id ?>"
                            style="background-color: #ef4444; color: white; padding: 10px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; display: flex; align-items: center; gap: 8px; text-decoration: none; transition: 0.2s;"
                            onclick="return confirm('Yakin ingin membatalkan dan menghapus laporan ini? Data yang dihapus tidak dapat dikembalikan.');">
                            <iconify-icon icon="lucide:trash-2" width="16"></iconify-icon> Hapus Laporan
                        </a>
                    
                    <?php endif; ?>
                </div>
            </div>

            <div class="content-grid">

                <div class="left-col">
                    <div class="detail-card">
                        <div class="detail-card-title">
                            <iconify-icon icon="lucide:info" width="20" style="color: var(--primary-color);"></iconify-icon> Informasi Laporan
                        </div>

                        <div class="info-grid-2">
                            <div class="info-item">
                                <span class="info-label">Judul Laporan</span>
                                <span class="info-value"><?= htmlspecialchars($detail['judul_laporan']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Kategori</span>
                                <span class="info-value"><?= htmlspecialchars($detail['kategori']) ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Tanggal Kejadian</span>
                                <span class="info-value"><?= $tanggal_dibuat ?></span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Lokasi</span>
                                <span class="info-value"><?= htmlspecialchars($detail['gedung']) ?> <?= htmlspecialchars($detail['detail_lokasi']) ?></span>
                            </div>
                        </div>

                        <div class="info-item" style="margin-bottom: 24px;">
                            <span class="info-label">Pelapor</span>
                            <div style="display: flex; align-items: center; gap: 8px; margin-top: 6px;">
                                <iconify-icon icon="lucide:user" width="16" style="color: var(--primary-color);"></iconify-icon>
                                <span class="info-value" style="font-weight: 700;">
                                    <?= htmlspecialchars($detail['nama_lengkap']) ?> <br>
                                    <span style="font-weight: 500; font-size: 12px; color: var(--text-muted);">
                                        (<?= htmlspecialchars($detail['npm']) ?>)
                                    </span>
                                </span>
                            </div>
                        </div>

                        <div class="info-item" style="margin-bottom: 24px;">
                            <span class="info-label" style="margin-bottom: 6px;">Deskripsi Masalah</span>
                            <div class="desc-box">
                                <?= nl2br(htmlspecialchars($detail['deskripsi'])) ?>
                            </div>
                        </div>

                        <div class="info-item">
                            <span class="info-label" style="margin-bottom: 12px;">Foto Bukti</span>
                            <?php if (!empty($detail['foto_bukti'])): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($detail['foto_bukti']) ?>" alt="Foto Bukti" class="foto-bukti-img">
                            <?php else: ?>
                                <div class="desc-box" style="text-align: center; border: 1px dashed #cbd5e1;">Tidak ada foto bukti yang diunggah.</div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if (!empty($detail['respon_admin'])): ?>
                        <div class="admin-response-card">
                            <div class="admin-response-header">
                                <div class="admin-profile">
                                    <div class="admin-avatar">
                                        <iconify-icon icon="lucide:shield-check" width="20"></iconify-icon>
                                    </div>
                                    <div>
                                        <h4 style="margin: 0; font-size: 14px; color: var(--text-main); display: flex; align-items: center; gap: 6px;">
                                            Admin Sarpras
                                            <span style="background: #e0e7ff; color: #3730a3; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: 700;">VERIFIED</span>
                                        </h4>
                                        <p style="margin: 0; font-size: 11px; color: var(--text-muted);">Respon Resmi</p>
                                    </div>
                                </div>
                                <span style="font-size: 11px; color: var(--text-muted);"><?= $tanggal_update ?></span>
                            </div>
                            <p style="margin: 0; font-size: 14px; color: #475569; font-style: italic; line-height: 1.6;">
                                "<?= nl2br(htmlspecialchars($detail['respon_admin'])) ?>"
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="right-col">
                    <div class="detail-card">
                        <div class="detail-card-title">
                            <iconify-icon icon="lucide:activity" width="20" style="color: var(--primary-color);"></iconify-icon> Status Penanganan
                        </div>

                        <div class="timeline">
                            <div class="timeline-item active">
                                <div class="timeline-dot"></div>
                                <div class="timeline-content">
                                    <h5>Laporan Diterima</h5>
                                    <p><?= $tanggal_dibuat ?></p>
                                </div>
                            </div>

                            <?php if ($status == 'ditolak'): ?>
                                <div class="timeline-item active-reject">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h5 style="color: var(--error-color);">Laporan Ditolak</h5>
                                        <p><?= $tanggal_update ?></p>
                                        <div class="timeline-box" style="background:#fef2f2; color:#b91c1c; border: 1px solid #fecaca;">
                                            Mohon cek catatan dari Admin Sarpras pada kolom respon.
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="timeline-item <?= ($status == 'diproses' || $status == 'selesai') ? 'active' : '' ?>">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h5>Sedang Diproses</h5>
                                        <?php if ($status == 'diproses' || $status == 'selesai'): ?>
                                            <p><?= $tanggal_update ?></p>
                                            <?php if ($status == 'diproses'): ?>
                                                <div class="timeline-box">Laporan diteruskan ke teknisi bagian terkait untuk segera ditangani.</div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <p>Menunggu peninjauan admin</p>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="timeline-item <?= ($status == 'selesai') ? 'active' : '' ?>">
                                    <div class="timeline-dot"></div>
                                    <div class="timeline-content">
                                        <h5>Selesai</h5>
                                        <?php if ($status == 'selesai'): ?>
                                            <p><?= $tanggal_update ?></p>
                                            <div class="timeline-box" style="background:#ecfdf5; color:#059669;">Fasilitas telah diperbaiki.</div>
                                        <?php else: ?>
                                            <p>Menunggu penanganan</p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="border-top: 1px solid var(--border-color); padding-top: 16px;">
                            <span class="info-label" style="display:block; margin-bottom: 12px;">Informasi Tambahan</span>
                            <div class="extra-info-row">
                                <span>Prioritas</span>
                                <?php
                                $prio_bg = ($detail['prioritas'] == 'Tinggi') ? '#fee2e2' : (($detail['prioritas'] == 'Sedang') ? '#ffedd5' : '#dbeafe');
                                $prio_text = ($detail['prioritas'] == 'Tinggi') ? '#b91c1c' : (($detail['prioritas'] == 'Sedang') ? '#c2410c' : '#2563eb');
                                ?>
                                <span style="background: <?= $prio_bg ?>; color: <?= $prio_text ?>; padding: 4px 10px; border-radius: 20px; font-weight: 700; font-size: 11px; text-transform: uppercase;">
                                    <?= htmlspecialchars($detail['prioritas']) ?>
                                </span>
                            </div>
                            <div class="extra-info-row">
                                <span>SLA Perbaikan</span>
                                <strong><?= $sla ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="action-widget" style="margin-top: 0;">
                        <div style="background: rgba(255,255,255,0.2); width: 32px; height: 32px; border-radius: 50%; display: flex; justify-content: center; align-items: center; margin-bottom: 16px;">
                            <iconify-icon icon="lucide:help-circle" width="20"></iconify-icon>
                        </div>
                        <h3 style="font-weight: 700;">Butuh bantuan lain?</h3>
                        <p style="font-size: 12px; margin-bottom: 24px;">Jika laporan tidak segera ditangani lebih dari SLA yang ditentukan, Anda dapat melakukan eskalasi melalui tombol di bawah.</p>
                        <a href="#" class="btn-white">Hubungi Helpdesk</a>
                    </div>
                </div>

            </div>
        </main>
    </div>

</body>

</html>