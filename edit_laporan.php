<?php
require_once __DIR__ . '/proses/proses_edit.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Laporan #CR-<?= str_pad($laporan['id'], 4, "0", STR_PAD_LEFT) ?></title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>iconify-icon { display: inline-flex; justify-content: center; align-items: center; }</style>
</head>
<body class="dashboard-page">

    <div class="dashboard-layout">
        
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-icon"><iconify-icon icon="lucide:graduation-cap" width="24"></iconify-icon></div>
                <div class="brand-text"><h3>CampusReport</h3><p>Akun Mahasiswa</p></div>
            </div>
            <ul class="nav-menu">
                <li class="nav-item"><a href="dashboard.php"><iconify-icon icon="lucide:layout-dashboard" width="20"></iconify-icon> Dashboard</a></li>
                <li class="nav-item"><a href="buat_laporan.php"><iconify-icon icon="lucide:file-edit" width="20"></iconify-icon> Buat Laporan</a></li>
                <li class="nav-item active"><a href="#"><iconify-icon icon="lucide:activity" width="20"></iconify-icon> Status Laporan</a></li>
                <li class="nav-item"><a href="#"><iconify-icon icon="lucide:history" width="20"></iconify-icon> Riwayat</a></li>
            </ul>
            <div class="sidebar-bottom">
                <ul class="nav-menu">
                    <li class="nav-item"><a href="logout.php" class="logout-btn"><iconify-icon icon="lucide:log-out" width="20"></iconify-icon> Keluar</a></li>
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
                    <a href="#" style="color: var(--text-muted);"><iconify-icon icon="lucide:bell" width="22"></iconify-icon></a>
                    <div class="user-profile" style="margin-left: 8px;">
                        <div class="avatar"><?= $inisial ?></div>
                        <div class="user-info">
                            <h4><?= htmlspecialchars($nama_user) ?></h4>
                            <p>MHS-2024</p>
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-header">
                <h2>Revisi Laporan Anda</h2>
                <p>Ubah detail informasi yang diperlukan. Laporan ini masih dalam antrean peninjauan.</p>
            </div>

            <?= isset($pesan) ? $pesan : '' ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-card">
                    
                    <div class="form-grid-2-col">
                        <div class="left-col">
                            <div class="form-group">
                                <label for="judul_laporan">Judul Laporan</label>
                                <input type="text" id="judul_laporan" name="judul_laporan" class="form-control" value="<?= htmlspecialchars($laporan['judul_laporan']) ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="kategori">Kategori Fasilitas</label>
                                <select id="kategori" name="kategori" class="form-control" required>
                                    <?php 
                                        $opsi_kategori = ['Kelistrikan & Lampu', 'AC & Ventilasi', 'Furnitur Kelas', 'Proyektor & IT', 'Infrastruktur & Bangunan', 'Lainnya'];
                                        foreach($opsi_kategori as $opsi):
                                            $selected = ($laporan['kategori'] == $opsi) ? 'selected' : '';
                                            echo "<option value='$opsi' $selected>$opsi</option>";
                                        endforeach;
                                    ?>
                                </select>
                            </div>

                            <div class="form-grid-inner">
                                <div class="form-group">
                                    <label for="gedung">Gedung/Area</label>
                                    <input type="text" id="gedung" name="gedung" class="form-control" value="<?= htmlspecialchars($laporan['gedung']) ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="detail_lokasi">Detail Lokasi</label>
                                    <input type="text" id="detail_lokasi" name="detail_lokasi" class="form-control" value="<?= htmlspecialchars($laporan['detail_lokasi']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="right-col">
                            <div class="form-group">
                                <label>Tingkat Prioritas</label>
                                <div class="priority-group">
                                    <input type="radio" id="prioritas_rendah" name="prioritas" value="Rendah" <?= ($laporan['prioritas'] == 'Rendah') ? 'checked' : '' ?> required>
                                    <label for="prioritas_rendah">Rendah</label>
                                    
                                    <input type="radio" id="prioritas_sedang" name="prioritas" value="Sedang" <?= ($laporan['prioritas'] == 'Sedang') ? 'checked' : '' ?>>
                                    <label for="prioritas_sedang">Sedang</label>
                                    
                                    <input type="radio" id="prioritas_tinggi" name="prioritas" value="Tinggi" <?= ($laporan['prioritas'] == 'Tinggi') ? 'checked' : '' ?>>
                                    <label for="prioritas_tinggi">Tinggi</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Masalah</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" required><?= htmlspecialchars($laporan['deskripsi']) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        <label style="display:flex; justify-content:space-between;">
                            Ganti Foto Bukti
                            <span style="color:var(--primary-color); font-weight:600;">(Opsional)</span>
                        </label>
                        <div class="upload-area">
                            <input type="file" name="foto_bukti" accept="image/png, image/jpeg, image/jpg" id="fileInput">
                            <div class="upload-icon">
                                <iconify-icon icon="lucide:image-plus" width="24"></iconify-icon>
                            </div>
                            <h4 id="fileNameDisplay">Klik/Seret foto baru ke sini</h4>
                            <p>Biarkan kosong jika Anda tidak ingin mengubah foto saat ini.</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='detail_laporan.php?id=<?= $report_id ?>'">Batal</button>
                        <button type="submit" class="btn-submit-report">Simpan Perubahan</button>
                    </div>

                </div>
            </form>

        </main>
    </div>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
            if(e.target.files.length > 0) {
                var fileName = e.target.files[0].name;
                document.getElementById('fileNameDisplay').innerText = fileName;
                document.getElementById('fileNameDisplay').style.color = 'var(--primary-color)';
            }
        });
    </script>
</body>
</html>