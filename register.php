<?php 
include 'proses/proses_register.php'; 
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

        <?= $pesan ?>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" placeholder="Masukkan Nama Lengkap Anda" required>
            </div>

            <div class="form-group">
                <label>NPM (Nomor Pokok Mahasiswa)</label>
                <input type="text" name="npm" class="form-control" placeholder="Masukkan NPM Anda" required>
            </div>

            <div class="form-group">
                <label>Email Kampus (@ac.id)</label>
                <input type="email" name="email" class="form-control" placeholder="npm@universitas.ac.id" required>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
                <div class="form-group">
                    <label>Konfirmasi Password</label>
                    <input type="password" name="konfirmasi_password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="form-group">
                <label>Program Studi</label>
                <select name="program_studi" class="form-control" required>
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
                <label for="syarat">Saya setuju dengan <a href="#">Syarat & Ketentuan</a> yang berlaku.</label>
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>
        </form>

        <div class="footer-text">
            Sudah punya akun? <a href="login.php">Masuk</a>
        </div>
    </div>

</body>
</html>