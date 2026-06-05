<?php 
include 'proses/proses_login.php'; 

// jika user sudah login, arahkan langsung ke dashboard
if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - CampusReport</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <div class="login-container">
        <div class="logo">CampusReport</div>
        
        <h2 class="title">Selamat Datang di<br>CampusReport</h2>
        <p class="subtitle">Laporkan kerusakan fasilitas kampus dengan mudah<br>dan pantau progresnya secara real-time.</p>

        <?= isset($pesan) ? $pesan : '' ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="identifier">Email / NPM</label>
                <input type="text" id="identifier" name="identifier" class="form-control" placeholder="Masukkan Email atau NPM" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <div class="options-row">
                <div class="checkbox-group">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>
                <a href="#" class="forgot-password">Lupa kata sandi?</a>
            </div>

            <button type="submit" class="btn-submit">Masuk ke CampusReport</button>
        </form>

        <div class="divider">
            <span>ATAU</span>
        </div>

        <div class="footer-text">
            Belum punya akun? <a href="register.php">Daftar</a>
        </div>
    </div>

</body>
</html>