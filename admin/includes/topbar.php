<?php
require_once __DIR__ . '/../proses/proses_topbar.php';
?>

<nav class="navbar admin-navbar navbar-expand bg-white">
    <div class="container-fluid px-3 px-lg-4">
        <button class="sidebar-toggle" type="button" data-sidebar-toggle aria-controls="adminSidebar" aria-expanded="true" aria-label="Toggle sidebar">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <form action="laporan_masuk.php" method="GET" class="d-none d-md-flex ms-3 flex-grow-1" role="search">
            <input class="form-control search-input" type="search" name="search" placeholder="Cari laporan..." aria-label="Search" required>
        </form>

        <div class="navbar-actions ms-auto">

            <div class="dropdown" id="adminNotifWrapper">
                <button class="icon-button" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications" style="position: relative; border: none; background: transparent;" id="adminNotifBtn">
                    <?php if ($unread_admin_count > 0): ?>
                        <span class="notification-dot" id="adminNotifBadge" style="position: absolute; top: 0; right: 0; width: 8px; height: 8px; background: #ef4444; border-radius: 50%;"></span>
                    <?php endif; ?>
                    <i class="bi bi-bell" aria-hidden="true" style="font-size: 20px; color: #64748b;"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end notification-menu" style="min-width: 280px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); border-radius: 12px; padding: 0;">
                    <div class="dropdown-header fw-bold text-body" style="padding: 12px 16px; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <span>Laporan Menunggu (<?= $unread_admin_count ?>)</span>
                        <?php if ($unread_admin_count > 0): ?>
                            <span id="markReadAdminBtn" style="font-size: 11px; color: var(--primary-color); cursor: pointer; font-weight: 700;">Tandai dibaca ✓</span>
                        <?php endif; ?>
                    </div>

                    <div class="notif-body" style="max-height: 300px; overflow-y: auto;">
                        <?php if (count($notif_admin_items) > 0): ?>
                            <?php foreach ($notif_admin_items as $n): ?>
                                <?php
                                $bg_color = ($n['is_admin_read'] == 1) ? 'transparent' : '#f8fafc';
                                $opacity = ($n['is_admin_read'] == 1) ? '0.7' : '1';
                                ?>
                                <a class="dropdown-item admin-notif-item" href="detail_laporan_admin.php?id=<?= $n['id'] ?>" style="padding: 12px 16px; border-bottom: 1px solid #f1f5f9; display: flex; flex-direction: column; background: <?= $bg_color ?>; opacity: <?= $opacity ?>;">
                                    <span class="notification-title" style="font-size: 13px; font-weight: 600; color: #1e293b; margin-bottom: 4px;">
                                        Baru: <?= htmlspecialchars(substr($n['judul_laporan'], 0, 25)) ?>...
                                    </span>
                                    <span class="notification-time" style="font-size: 11px; color: #94a3b8;">
                                        <i class="bi bi-clock"></i> <?= time_ago_notif($n['created_at']) ?>
                                    </span>
                                </a>
                            <?php endforeach; ?>
                            <div style="padding: 8px; text-align: center; background: #f8fafc; border-top: 1px solid #e2e8f0;">
                                <a href="laporan_masuk.php?status=menunggu" style="font-size: 12px; font-weight: 700; color: var(--primary-color); text-decoration: none;">Lihat Semua</a>
                            </div>
                        <?php else: ?>
                            <div style="padding: 24px; text-align: center; color: #94a3b8;">
                                <i class="bi bi-check-circle" style="font-size: 24px; margin-bottom: 8px; display: block; color: #10b981;"></i>
                                <span style="font-size: 13px;">Semua laporan sudah diproses!</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="dropdown" style="margin-left: 16px;">
                <div class="profile-button" aria-expanded="false" style="display: flex; align-items: center; gap: 8px;">
                    <div class="avatar-img avatar-sm" style="display: inline-flex; align-items: center; justify-content: center; background: #e0e7ff; color: var(--primary-color); border-radius: 50%; font-weight: 700; width: 32px; height: 32px; font-size: 13px; text-transform: uppercase;">
                        <?= $inisial_admin ?? 'AD' ?>
                    </div>
                    <span class="profile-name d-none d-sm-inline" style="font-weight: 600; color: #1e293b;"><?= htmlspecialchars($admin_nama ?? 'Admin') ?></span>
                </div>
            </div>

        </div>
    </div>
</nav>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const markReadAdminBtn = document.getElementById('markReadAdminBtn');
        const adminNotifBadge = document.getElementById('adminNotifBadge');

        if (markReadAdminBtn) {
            markReadAdminBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                e.preventDefault();

                fetch('proses/notifikasi_admin.php')
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {
                            if (adminNotifBadge) adminNotifBadge.style.display = 'none';

                            markReadAdminBtn.innerText = 'Telah dibaca';
                            markReadAdminBtn.style.color = '#94a3b8';
                            markReadAdminBtn.style.cursor = 'default';

                            document.querySelectorAll('.admin-notif-item').forEach(item => {
                                item.style.background = 'transparent';
                                item.style.opacity = '0.7';
                            });
                        }
                    });
            });
        }
    });
</script>