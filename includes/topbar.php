<?php
if (!isset($conn)) {
    die("<b>Critical Error:</b> Variabel koneksi database (<code>\$conn</code>) tidak ditemukan. Pastikan koneksi database telah diinisialisasi sebelum memanggil <code>topbar.php</code>.");
}

$topbar_nama = $_SESSION['nama_lengkap'] ?? 'Mahasiswa';
$words = explode(" ", $topbar_nama);
$topbar_inisial = "";
foreach ($words as $w) {
    $topbar_inisial .= mb_substr($w, 0, 1);
}
$topbar_inisial = strtoupper(substr($topbar_inisial, 0, 2));
if (empty($topbar_inisial)) $topbar_inisial = "M";

$topbar_npm = $_SESSION['npm'] ?? '';

if (empty($topbar_npm)) {
    $stmt_npm = $conn->prepare("SELECT npm FROM users WHERE id = ?");
    $stmt_npm->bind_param("i", $_SESSION['user_id']);
    $stmt_npm->execute();
    $res_npm = $stmt_npm->get_result();
    if ($row_npm = $res_npm->fetch_assoc()) {
        $topbar_npm = $row_npm['npm'];
        $_SESSION['npm'] = $topbar_npm;
    }
    $stmt_npm->close();
}

$dua_digit = substr($topbar_npm, 0, 2);
if (empty($dua_digit)) $dua_digit = "24";
$teks_angkatan = "MHS-20" . $dua_digit;

$notif_items = [];
$unread_count = 0;
$user_id_notif = $_SESSION['user_id'];

$stmt_notif = $conn->prepare("SELECT id, judul_laporan, status, updated_at, is_notif_read FROM reports WHERE user_id = ? AND status != 'Menunggu' ORDER BY updated_at DESC LIMIT 5");
$stmt_notif->bind_param("i", $user_id_notif);
$stmt_notif->execute();
$res_notif = $stmt_notif->get_result();
while ($row = $res_notif->fetch_assoc()) {
    $notif_items[] = $row;
    if ($row['is_notif_read'] == 0) {
        $unread_count++;
    }
}
$stmt_notif->close();


if (!function_exists('time_ago_notif')) {
    function time_ago_notif(string $datetime): string
    {
        $diff = time() - strtotime($datetime);
        if ($diff < 60) return "Baru saja";
        if ($diff < 3600) return floor($diff / 60) . " mnt lalu";
        if ($diff < 86400) return floor($diff / 3600) . " jam lalu";
        return floor($diff / 86400) . " hari lalu";
    }
}
?>

<header class="topbar">
    <form action="riwayat.php" method="GET" class="search-bar" style="margin: 0; width: 300px; display: flex; align-items: center;">
        <iconify-icon icon="lucide:search" width="18" style="color: #9ca3af;"></iconify-icon>
        <input type="text" name="search" placeholder="Cari laporan..." style="border:none; outline:none; background:transparent; width:100%; margin-left:8px;" required>
    </form>

    <div class="topbar-right" style="display: flex; align-items: center; gap: 16px;">

        <div class="notif-wrapper" id="notifWrapper">
            <button class="notif-btn" id="notifBtn">
                <iconify-icon icon="lucide:bell" width="22"></iconify-icon>
                <?php if ($unread_count > 0): ?>
                    <span class="notif-badge" id="notifBadge"></span>
                <?php endif; ?>
            </button>

            <div class="notif-dropdown" id="notifDropdown">
                <div class="notif-header">
                    <h4>Notifikasi</h4>
                    <span id="markReadBtn" style="font-size: 11px; color: var(--primary-color); cursor: pointer; font-weight: 600;">Tandai dibaca</span>
                </div>
                <div class="notif-body">
                    <?php if (count($notif_items) > 0): ?>
                        <?php foreach ($notif_items as $n): ?>
                            <?php
                            $st = strtolower($n['status']);
                            $n_color = '#3b82f6';
                            $n_icon = 'lucide:loader-2';
                            $n_bg = '#eff6ff';
                            $n_text = 'sedang diproses oleh teknisi.';

                            if ($st == 'selesai') {
                                $n_color = '#10b981';
                                $n_icon = 'lucide:check-circle-2';
                                $n_bg = '#ecfdf5';
                                $n_text = 'telah selesai diperbaiki.';
                            } elseif ($st == 'ditolak') {
                                $n_color = '#ef4444';
                                $n_icon = 'lucide:x-circle';
                                $n_bg = '#fee2e2';
                                $n_text = 'ditolak oleh admin. Cek alasannya.';
                            }

                            $opacity = ($n['is_notif_read'] == 1) ? '0.6' : '1';
                            ?>
                            <a href="detail_laporan.php?id=<?= $n['id'] ?>" class="notif-item" style="opacity: <?= $opacity ?>;">
                                <div class="notif-icon-box" style="background-color: <?= $n_bg ?>; color: <?= $n_color ?>;">
                                    <iconify-icon icon="<?= $n_icon ?>" width="20"></iconify-icon>
                                </div>
                                <div class="notif-text">
                                    <h5>Status Diperbarui</h5>
                                    <p>Laporan "<strong><?= htmlspecialchars(substr($n['judul_laporan'], 0, 25)) ?>...</strong>" <?= $n_text ?></p>
                                    <div class="notif-time"><?= time_ago_notif($n['updated_at']) ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="notif-empty">
                            <iconify-icon icon="lucide:bell-off" width="32" style="color:#cbd5e1; margin-bottom:8px;"></iconify-icon>
                            <p style="margin:0;">Belum ada notifikasi baru.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="user-profile" style="margin-left: 8px; padding-left: 16px; border-left: 1px solid var(--border-color);">
            <div class="avatar"><?= $topbar_inisial ?></div>
            <div class="user-info">
                <h4><?= htmlspecialchars($topbar_nama) ?></h4>
                <p><?= $teks_angkatan ?></p>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const notifBtn = document.getElementById('notifBtn');
        const notifDropdown = document.getElementById('notifDropdown');
        const markReadBtn = document.getElementById('markReadBtn');
        const notifBadge = document.getElementById('notifBadge');

        notifBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            notifDropdown.classList.toggle('show');
        });

        window.addEventListener('click', function(e) {
            if (!document.getElementById('notifWrapper').contains(e.target)) {
                notifDropdown.classList.remove('show');
            }
        });

        if (markReadBtn) {
            markReadBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                fetch('proses/tandai_dibaca.php')
                    .then(response => response.text())
                    .then(data => {
                        if (data.trim() === 'success') {
                            if (notifBadge) notifBadge.style.display = 'none';
                            markReadBtn.innerText = 'Telah dibaca ✓';
                            markReadBtn.style.color = 'var(--text-muted)';
                            markReadBtn.style.cursor = 'default';
                            document.querySelectorAll('.notif-item').forEach(item => {
                                item.style.opacity = '0.6';
                            });
                        }
                    });
            });
        }
    });
</script>