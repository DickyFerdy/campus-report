<?php
include 'proses/proses_lupa_password.php';

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
    <title>Lupa Kata Sandi - CampusReport</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
    <style>
        /* Desain Khusus Centered Auth Page */
        body.auth-body {
            background-color: var(--bg-body);
            display: flex; justify-content: center; align-items: center;
            min-height: 100vh; margin: 0; padding: 20px;
        }
        .auth-card-centered {
            background: var(--bg-surface);
            padding: 40px; border-radius: 16px;
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.05);
            width: 100%; max-width: 420px;
        }
        .auth-brand {
            text-align: center; margin-bottom: 24px;
        }
        .auth-brand .icon-box {
            background-color: var(--primary-focus); color: var(--primary-color);
            width: 48px; height: 48px; border-radius: 12px;
            display: inline-flex; justify-content: center; align-items: center; margin-bottom: 12px;
        }
        .auth-brand h2 { margin: 0 0 6px 0; font-size: 22px; color: var(--text-main); }
        .auth-brand p { margin: 0; font-size: 13px; color: var(--text-muted); line-height: 1.5; }
        
        .form-footer-link {
            text-align: center; margin-top: 24px; font-size: 13px; color: var(--text-muted);
        }
        .form-footer-link a {
            color: var(--primary-color); font-weight: 700; text-decoration: none;
            display: inline-flex; align-items: center; gap: 4px; transition: 0.2s;
        }
        .form-footer-link a:hover { text-decoration: underline; }
    </style>
</head>
<body class="auth-body">

    <div class="auth-card-centered">
        <div class="auth-brand">
            <div class="icon-box">
                <iconify-icon icon="lucide:key-round" width="24"></iconify-icon>
            </div>
            <h2>Pemulihan Kata Sandi</h2>
            <p>Masukkan NPM dan Email terdaftar Anda untuk menerima instruksi pembuatan kata sandi baru.</p>
        </div>

        <?= $pesan ?>

        <form action="" method="POST" style="margin-top: 20px;">
            <div class="form-group">
                <label for="npm">NPM (Nomor Pokok Mahasiswa)</label>
                <div style="position: relative;">
                    <iconify-icon icon="lucide:id-card" width="16" style="position: absolute; left: 14px; top: 15px; color: #9ca3af;"></iconify-icon>
                    <input type="text" id="npm" name="npm" class="form-control" placeholder="Contoh: 24081010312" style="padding-left: 42px;" required>
                </div>
            </div>

            <div class="form-group">
                <label for="email">Email Institusi / Pribadi</label>
                <div style="position: relative;">
                    <iconify-icon icon="lucide:mail" width="16" style="position: absolute; left: 14px; top: 15px; color: #9ca3af;"></iconify-icon>
                    <input type="email" id="email" name="email" class="form-control" placeholder="nama@mahasiswa.ac.id" style="padding-left: 42px;" required>
                </div>
            </div>

            <button type="submit" class="btn-submit-report" style="width: 100%; margin-top: 10px; padding: 14px;">
                Kirim Link Pemulihan
            </button>
        </form>

        <div class="form-footer-link">
            <a href="login.php">
                <iconify-icon icon="lucide:arrow-left" width="14"></iconify-icon> Kembali ke Login
            </a>
        </div>
    </div>

</body>
</html>