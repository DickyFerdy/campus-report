<?php
require_once __DIR__ . '/proses/proses_login.php';

if (isset($_SESSION['pesan_admin'])) {
    $pesan = $_SESSION['pesan_admin'];
    unset($_SESSION['pesan_admin']);
}

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
    <title>Masuk - CampusReport</title>
    <link rel="stylesheet" href="assets/css/style.css?v=<?= time(); ?>">
    <script src="https://code.iconify.design/iconify-icon/1.0.8/iconify-icon.min.js"></script>

    <style>
        body {
            background-color: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .login-container {
            background: #ffffff;
            width: 100%;
            max-width: 420px;
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);
            margin: 20px;
            box-sizing: border-box;
        }

        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #1d4ed8;
            letter-spacing: -0.5px;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .subtitle {
            font-size: 14px;
            color: #64748b;
            line-height: 1.5;
            margin: 0 0 24px 0;
        }

        .role-toggle {
            display: flex;
            background-color: #f1f5f9;
            border-radius: 999px;
            padding: 4px;
            margin-bottom: 32px;
            width: 100%;
            box-sizing: border-box;
        }

        .role-btn {
            flex: 1;
            padding: 10px 0;
            text-align: center;
            border-radius: 999px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            color: #64748b;
            background: transparent;
            border: none;
            transition: all 0.3s ease;
        }

        .role-btn.active {
            background-color: #0056b3;
            color: white;
            box-shadow: 0 2px 8px rgba(29, 78, 216, 0.2);
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #334155;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            width: 100%;
        }

        .input-wrapper .left-icon {
            position: absolute;
            left: 14px;
            color: #94a3b8;
            font-size: 18px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 14px;
            color: #0f172a;
            box-sizing: border-box;
            transition: 0.2s;
            background: #f8fafc;
        }

        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            background: #ffffff;
            box-shadow: 0 0 0 4px #eff6ff;
        }

        .input-wrapper .form-control.has-icon {
            padding-left: 42px;
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input {
            margin: 0;
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #1d4ed8;
        }

        .checkbox-group label {
            font-size: 13px;
            color: #64748b;
            cursor: pointer;
            margin: 0;
            font-weight: 500;
        }

        .forgot-password {
            font-size: 13px;
            font-weight: 600;
            color: #3b82f6;
            text-decoration: none;
        }

        .forgot-password:hover {
            text-decoration: underline;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background-color: #1d4ed8;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: 0.2s;
        }

        .btn-submit:hover {
            background-color: #1e40af;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(29, 78, 216, 0.2);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
            color: #cbd5e1;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }

        .divider span {
            padding: 0 16px;
            font-size: 12px;
            font-weight: 700;
            color: #94a3b8;
        }

        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="logo" style="text-align: center; margin-bottom: 24px;">CampusReport</div>

        <div class="role-toggle">
            <button type="button" class="role-btn active" id="btn-mahasiswa" onclick="switchRole('mahasiswa')">Mahasiswa</button>
            <button type="button" class="role-btn" id="btn-admin" onclick="switchRole('admin')">Admin / Pengelola</button>
        </div>

        <?= isset($pesan) ? $pesan : '' ?>

        <div id="form-mahasiswa" class="form-section active">
            <h2 class="title" style="text-align: center;">Selamat Datang</h2>
            <p class="subtitle" style="text-align: center;">Laporkan kerusakan fasilitas kampus dengan mudah dan pantau progresnya.</p>

            <form action="" method="POST">
                <input type="hidden" name="role" value="mahasiswa">

                <div class="form-group">
                    <label for="identifier">Email atau NPM</label>
                    <input type="text" id="identifier" name="identifier" class="form-control" placeholder="Masukkan Email atau NPM" required>
                </div>

                <div class="form-group">
                    <label for="password_mhs">Password</label>
                    <input type="password" id="password_mhs" name="password" class="form-control" placeholder="••••••••" required>
                </div>

                <div class="options-row">
                    <div class="checkbox-group">
                        <input type="checkbox" name="remember" id="remember_mhs">
                        <label for="remember_mhs">Ingat saya</label>
                    </div>
                    <a href="lupa_password.php" class="forgot-password">Lupa kata sandi?</a>
                </div>

                <button type="submit" name="login" class="btn-submit">Masuk ke CampusReport</button>
            </form>

            <div class="divider"><span>ATAU</span></div>

            <div style="text-align: center; font-size: 13px; color: #64748b;">
                Belum punya akun? <a href="register.php" style="color: #3b82f6; font-weight: 700; text-decoration: none;">Daftar di sini</a>
            </div>
        </div>

        <div id="form-admin" class="form-section">
            <div style="text-align: center; font-size: 48px; color: #1d4ed8; margin-bottom: 8px; line-height: 1;">
                <iconify-icon icon="lucide:shield-check"></iconify-icon>
            </div>
            <h2 class="title" style="text-align: center;">Portal Pengelola</h2>
            <p class="subtitle" style="text-align: center;">Verifikasi dan tindak lanjuti laporan masuk.</p>

            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 14px 16px; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px;">
                <iconify-icon icon="lucide:info" style="color: #2563eb; font-size: 20px; flex-shrink: 0; margin-top: 2px;"></iconify-icon>
                <div>
                    <div style="color: #1e40af; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 2px;">INFORMASI SETUP DEMO</div>
                    <div style="color: #1e3a8a; font-size: 13px; line-height: 1.4;">
                        Akses pertama kali? Silakan jalankan <a href="admin/setup/setup_admin.php" style="color: #2563eb; font-weight: 700; text-decoration: none; border-bottom: 1px dashed #2563eb; transition: 0.2s;" onmouseover="this.style.opacity='0.8'" onmouseout="this.style.opacity='1'">setup_admin.php</a> untuk membuat akun pengelola.
                    </div>
                </div>
            </div>

            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 14px 16px; display: flex; align-items: flex-start; gap: 12px; margin-bottom: 24px;">
                <iconify-icon icon="lucide:alert-triangle" style="color: #dc2626; font-size: 20px; flex-shrink: 0; margin-top: 2px;"></iconify-icon>
                <div>
                    <div style="color: #991b1b; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 2px;">RESTRICTED AREA</div>
                    <div style="color: #b91c1c; font-size: 13px; line-height: 1.4;">Akses terbatas hanya bagi staf dan admin yang berwenang.</div>
                </div>
            </div>

            <form action="admin/proses/proses_login.php" method="POST">

                <div class="form-group">
                    <label for="email">Email Admin</label>
                    <div class="input-wrapper">
                        <iconify-icon icon="lucide:mail" class="left-icon"></iconify-icon>
                        <input type="email" id="email" name="email" class="form-control has-icon" placeholder="admin@universitas.ac.id" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_admin">Password</label>
                    <div class="input-wrapper">
                        <iconify-icon icon="lucide:key" class="left-icon"></iconify-icon>
                        <input type="password" id="password_admin" name="password" class="form-control has-icon" placeholder="••••••••" required>
                        <button type="button" id="togglePwd" style="position: absolute; right: 14px; background: none; border: none; cursor: pointer; color: #94a3b8; display: flex; align-items: center; justify-content: center; padding: 4px;">
                            <iconify-icon icon="lucide:eye" style="font-size: 18px;"></iconify-icon>
                        </button>
                    </div>
                </div>

                <div class="options-row" style="margin-bottom: 32px;">
                    <div class="checkbox-group">
                        <input type="checkbox" name="remember" id="remember_admin">
                        <label for="remember_admin">Sesi tetap masuk</label>
                    </div>
                </div>

                <button type="submit" class="btn-submit">Akses Dashboard Admin</button>
            </form>

            <div style="text-align: center; margin-top: 24px; font-size: 11px; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px;">
                <iconify-icon icon="lucide:lock" style="vertical-align: -2px;"></iconify-icon> INTERNAL SECURITY PROTOCOL ACTIVE
            </div>
        </div>
    </div>

    <script>
        function switchRole(role) {
            document.getElementById('btn-mahasiswa').classList.remove('active');
            document.getElementById('btn-admin').classList.remove('active');
            document.getElementById('form-mahasiswa').classList.remove('active');
            document.getElementById('form-admin').classList.remove('active');

            if (role === 'mahasiswa') {
                document.getElementById('btn-mahasiswa').classList.add('active');
                document.getElementById('form-mahasiswa').classList.add('active');
            } else if (role === 'admin') {
                document.getElementById('btn-admin').classList.add('active');
                document.getElementById('form-admin').classList.add('active');
            }
        }

        document.getElementById('togglePwd').addEventListener('click', function() {
            const pwdInput = document.getElementById('password_admin');
            const type = pwdInput.getAttribute('type') === 'password' ? 'text' : 'password';
            pwdInput.setAttribute('type', type);

            const icon = this.querySelector('iconify-icon');
            if (type === 'text') {
                icon.setAttribute('icon', 'lucide:eye-off');
                this.style.color = '#0056b3';
            } else {
                icon.setAttribute('icon', 'lucide:eye');
                this.style.color = '#94a3b8';
            }
        });

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('role') === 'admin') {
            switchRole('admin');
        }
    </script>

</body>

</html>