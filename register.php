<?php
require_once __DIR__ . '/proses/proses_register.php';

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - CampusReport</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>

    <div class="register-container">
        <div class="logo">CampusReport</div>

        <h2 class="title">Daftar Akun CampusReport</h2>
        <p class="subtitle">Bergabunglah untuk melaporkan dan memantau<br>fasilitas kampus dengan mudah.</p>

        <?= isset($pesan) ? $pesan : '' ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="nama_lengkap">Nama Lengkap</label>
                <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control" placeholder="Masukkan Nama Lengkap" required>
            </div>

            <div class="form-group">
                <label for="npm">NPM (Nomor Pokok Mahasiswa)</label>
                <input type="text" id="npm" name="npm" class="form-control" placeholder="Masukkan NPM Anda" required>
            </div>

            <div class="form-group">
                <label for="email">Email Kampus (@ac.id)</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="npm@universitas.ac.id" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label for="konfirmasi_password">Konfirmasi Password</label>
                    <input type="password" id="konfirmasi_password" name="konfirmasi_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-group">
                <label for="program_studi">Fakultas / Program Studi</label>
                <select id="program_studi" name="program_studi" class="form-control" required>
                    <option value="" disabled selected>Pilih Program Studi</option>
                    <option value="Informatika">Informatika</option>
                    <option value="Sistem Informasi">Sistem Informasi</option>
                    <option value="Teknik Mesin">Teknik Mesin</option>
                    <option value="Manajemen Bisnis">Manajemen Bisnis</option>
                    <option value="Ilmu Komunikasi">Ilmu Komunikasi</option>
                </select>
            </div>

            <div class="checkbox-group">
                <input type="checkbox" name="syarat" id="syarat" required>
                <label for="syarat">
                    Saya menyetujui <a href="syarat_ketentuan.php" target="_blank" style="color: var(--primary-color); font-weight: 700; text-decoration: none;">Syarat & Ketentuan</a> yang berlaku di platform ini.
                </label>
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>
        </form>

        <div class="footer-text">
            Sudah punya akun? <a href="index.php">Masuk</a>
        </div>
    </div>

</body>

</html>