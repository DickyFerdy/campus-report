<?php
require_once __DIR__ . '/proses/proses_login.php';
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - CampusReport</title>
    <link rel="stylesheet" href="../assets/css/style.css?v=<?= time(); ?>">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>
</head>

<body class="admin-auth-body">

    <div class="brand-top">
        CampusReport
    </div>

    <div class="admin-auth-card">
        <div class="admin-icon-lock">
            <iconify-icon icon="lucide:shield-check"></iconify-icon>
        </div>
        <h2>Login Admin CampusReport</h2>
        <p class="subtitle">Masuk ke dashboard pengelola untuk memverifikasi laporan dan menindaklanjuti laporan.</p>

        <?php if (!empty($pesan)): ?>
            <?= $pesan ?>
        <?php else: ?>
            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; display: flex; align-items: flex-start; gap: 12px; text-align: left; margin-bottom: 24px;">
                <iconify-icon icon="lucide:alert-triangle" style="color: #dc2626; font-size: 20px; flex-shrink: 0; margin-top: 2px;"></iconify-icon>
                <div>
                    <div style="color: #991b1b; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 4px;">PERINGATAN</div>
                    <div style="color: #b91c1c; font-size: 13px; line-height: 1.5;">Akses terbatas bagi staf dan admin yang berwenang.</div>
                </div>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="input-with-icon">
                <label for="email">Email Admin</label>
                <iconify-icon icon="lucide:mail" class="left-icon"></iconify-icon>
                <input type="email" id="email" name="email" placeholder="admin@universitas.ac.id" required autofocus>
            </div>

            <div class="input-with-icon">
                <label for="password">Password</label>
                <iconify-icon icon="lucide:key" class="left-icon"></iconify-icon>
                <input type="password" id="password" name="password" placeholder="••••••••" required>
                <button type="button" class="toggle-pwd" id="togglePwd" title="Tampilkan Password">
                    <iconify-icon icon="lucide:eye"></iconify-icon>
                </button>
            </div>

            <div style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: var(--text-muted); margin-bottom: 24px; text-align: left;">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="cursor: pointer; margin: 0;">Ingat saya</label>
            </div>

            <button type="submit" class="btn-submit-report" style="width: 100%; padding: 14px;">Masuk sebagai Admin</button>
        </form>
    </div>

    <div class="security-footer">
        Internal Security Protocol Active
    </div>

    <script>
        document.getElementById('togglePwd').addEventListener('click', function() {
            const pwdInput = document.getElementById('password');
            const type = pwdInput.getAttribute('type') === 'password' ? 'text' : 'password';
            pwdInput.setAttribute('type', type);

            const icon = this.querySelector('iconify-icon');
            if (type === 'text') {
                icon.setAttribute('icon', 'lucide:eye-off');
                this.style.color = 'var(--primary-color)';
            } else {
                icon.setAttribute('icon', 'lucide:eye');
                this.style.color = '#94a3b8';
            }
        });
    </script>

</body>

</html>