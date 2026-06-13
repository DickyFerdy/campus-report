<?php
session_start();

include 'proses/proses_laporan.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nama_user = $_SESSION['nama_lengkap'];

$words = explode(" ", $nama_user);
$inisial = "";
foreach ($words as $w) {
    $inisial .= mb_substr($w, 0, 1);
}
$inisial = strtoupper(substr($inisial, 0, 2));
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Laporan Baru - CampusReport</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>
        iconify-icon { display: inline-flex; justify-content: center; align-items: center; }
    </style>
</head>
<body class="dashboard-page">

    <div class="dashboard-layout">
        
        <?php 
            $current_page = 'buat_laporan'; 
            include 'includes/sidebar.php'; 
        ?>

        <main class="main-content">
            
            <header class="topbar">
                <div class="search-bar">
                    <iconify-icon icon="lucide:search" width="18" style="color: #9ca3af;"></iconify-icon>
                    <input type="text" placeholder="Cari laporan...">
                </div>
                <div class="topbar-right">
                    <a href="#" style="color: var(--text-muted); display: flex; align-items: center;">
                        <iconify-icon icon="lucide:bell" width="22"></iconify-icon>
                    </a>
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
                <h2>Buat Laporan Baru</h2>
                <p>Isi detail laporan untuk membantu kami menangani masalah dengan lebih cepat.</p>
            </div>

            <?= isset($pesan) ? $pesan : '' ?>

            <form action="" method="POST" enctype="multipart/form-data">
                <div class="form-card">
                    
                    <div class="form-grid-2-col">
                        <div class="left-col">
                            <div class="form-group">
                                <label for="judul_laporan">Judul Laporan</label>
                                <input type="text" id="judul_laporan" name="judul_laporan" class="form-control" placeholder="Contoh: AC Mati di Ruang 302" required>
                            </div>

                            <div class="form-group">
                                <label for="kategori">Kategori Fasilitas</label>
                                <select id="kategori" name="kategori" class="form-control" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    <option value="Kelistrikan & Lampu">Kelistrikan & Lampu</option>
                                    <option value="AC & Ventilasi">AC & Ventilasi</option>
                                    <option value="Furnitur Kelas">Furnitur Kelas</option>
                                    <option value="Proyektor & IT">Proyektor & IT</option>
                                    <option value="Infrastruktur & Bangunan">Infrastruktur & Bangunan</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div class="form-grid-inner">
                                <div class="form-group">
                                    <label for="gedung">Gedung/Area</label>
                                    <input type="text" id="gedung" name="gedung" class="form-control" placeholder="Gedung C" required>
                                </div>
                                <div class="form-group">
                                    <label for="detail_lokasi">Detail Lokasi</label>
                                    <input type="text" id="detail_lokasi" name="detail_lokasi" class="form-control" placeholder="Lantai 3, R.302" required>
                                </div>
                            </div>
                        </div>

                        <div class="right-col">
                            <div class="form-group">
                                <label>Tingkat Prioritas</label>
                                <div class="priority-group">
                                    <input type="radio" id="prioritas_rendah" name="prioritas" value="Rendah" required>
                                    <label for="prioritas_rendah">Rendah</label>
                                    
                                    <input type="radio" id="prioritas_sedang" name="prioritas" value="Sedang">
                                    <label for="prioritas_sedang">Sedang</label>
                                    
                                    <input type="radio" id="prioritas_tinggi" name="prioritas" value="Tinggi">
                                    <label for="prioritas_tinggi">Tinggi</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="deskripsi">Deskripsi Masalah</label>
                                <textarea id="deskripsi" name="deskripsi" class="form-control" placeholder="Jelaskan kondisi kerusakan secara detail..." required></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 10px;">
                        <label>Unggah Foto Bukti</label>
                        <div class="upload-area">
                            <input type="file" name="foto_bukti" accept="image/png, image/jpeg, image/jpg" required id="fileInput">
                            <div class="upload-icon">
                                <iconify-icon icon="lucide:cloud-upload" width="24"></iconify-icon>
                            </div>
                            <h4 id="fileNameDisplay">Klik atau seret foto ke sini</h4>
                            <p>PNG, JPG atau JPEG (Maks. 5MB)</p>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='dashboard.php'">Batal</button>
                        <button type="submit" class="btn-submit-report">Kirim Laporan</button>
                    </div>

                </div>
            </form>
            <div class="info-cards-container">
                <div class="info-card">
                    <div class="info-card-header">
                        <iconify-icon icon="lucide:zap" width="20" style="color: var(--primary-color);"></iconify-icon>
                        <h4>Respon Cepat</h4>
                    </div>
                    <p>Laporan Anda akan diteruskan ke teknisi terkait dalam waktu < 30 menit.</p>
                </div>
                
                <div class="info-card">
                    <div class="info-card-header">
                        <iconify-icon icon="lucide:shield-check" width="20" style="color: var(--primary-color);"></iconify-icon>
                        <h4>Privasi Terjaga</h4>
                    </div>
                    <p>Identitas pelapor bersifat anonim bagi pihak ketiga di luar kampus.</p>
                </div>

                <div class="info-card">
                    <div class="info-card-header">
                        <iconify-icon icon="lucide:bar-chart-2" width="20" style="color: var(--primary-color);"></iconify-icon>
                        <h4>Pantau Progres</h4>
                    </div>
                    <p>Dapatkan notifikasi real-time saat status laporan Anda berubah.</p>
                </div>
            </div>

        </main>
    </div>

    <script>
        document.getElementById('fileInput').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            document.getElementById('fileNameDisplay').innerText = fileName;
            document.getElementById('fileNameDisplay').style.color = 'var(--primary-color)';
        });
    </script>
</body>
</html>