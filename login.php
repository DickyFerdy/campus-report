<?php
// Memastikan proses login sudah disesuaikan untuk menerima parameter role (mahasiswa/admin)
include 'proses/proses_login.php';

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
        /* Tambahan CSS untuk Toggle Button */
        .role-toggle {
            display: flex;
            background-color: #f1f5f9;
            border-radius: 9999px;
            padding: 4px;
            margin-bottom: 24px;
            width: 100%;
        }

        .role-btn {
            flex: 1;
            padding: 12px 0;
            text-align: center;
            border-radius: 9999px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            background: transparent;
            border: none;
            transition: all 0.3s ease;
        }

        .role-btn.active {
            background-color: #0056b3;
            color: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Menyembunyikan form yang tidak aktif */
        .form-section {
            display: none;
        }

        .form-section.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(5px);
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
        <div class="logo" style="text-align: center; margin-bottom: 20px;">CampusReport</div>

        <div class="role-toggle">
            <button type="button" class="role-btn active" id="btn-mahasiswa" onclick="switchRole('mahasiswa')">Mahasiswa</button>
            <button type="button" class="role-btn" id="btn-admin" onclick="switchRole('admin')">Admin / Pengelola</button>
        </div>

        <?= isset($pesan) ? $pesan : '' ?>

        <div id="form-mahasiswa" class="form-section active">
            <h2 class="title" style="text-align: center;">Selamat Datang di<br>CampusReport</h2>
            <p class="subtitle" style="text-align: center;">Laporkan kerusakan fasilitas kampus dengan mudah<br>dan pantau progresnya secara real-time.</p>

            <form action="" method="POST">
                <input type="hidden" name="role" value="mahasiswa">

                <div class="form-group">
                    <label for="identifier">Email / NPM</label>
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

            <div class="divider">
                <span>ATAU</span>
            </div>

            <div class="footer-text" style="text-align: center;">
                Belum punya akun? <a href="register.php">Daftar</a>
            </div>
        </div>

        <div id="form-admin" class="form-section">
            <div class="admin-icon-lock" style="text-align: center; font-size: 40px; color: #0056b3;">
                <iconify-icon icon="lucide:shield-check"></iconify-icon>
            </div>
            <h2 style="text-align: center;">Login Admin CampusReport</h2>
            <?php if (isset($_SESSION['error_admin'])): ?>
                <div style="color: #dc2626; background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-bottom: 20px; text-align: center; font-size: 14px;">
                    <iconify-icon icon="lucide:x-circle" style="vertical-align: middle; margin-right: 5px;"></iconify-icon>
                    <?= $_SESSION['error_admin']; ?>
                </div>
                <?php unset($_SESSION['error_admin']); // Hapus pesan setelah ditampilkan 
                ?>
            <?php endif; ?>
            <p class="subtitle" style="text-align: center;">Masuk ke dashboard pengelola untuk memverifikasi dan menindaklanjuti laporan.</p>

            <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; display: flex; align-items: flex-start; gap: 12px; text-align: left; margin-bottom: 24px;">
                <iconify-icon icon="lucide:alert-triangle" style="color: #dc2626; font-size: 20px; flex-shrink: 0; margin-top: 2px;"></iconify-icon>
                <div>
                    <div style="color: #991b1b; font-size: 11px; font-weight: 800; letter-spacing: 0.5px; margin-bottom: 4px;">PERINGATAN</div>
                    <div style="color: #b91c1c; font-size: 13px; line-height: 1.5;">Akses terbatas bagi staf dan admin yang berwenang.</div>
                </div>
            </div>

            <form action="admin/index.php" method="POST">

                <div class="input-with-icon form-group">
                    <label for="email">Email Admin</label>
                    <iconify-icon icon="lucide:mail" class="left-icon"></iconify-icon>
                    <input type="email" id="email" name="email" class="form-control" placeholder="admin@universitas.ac.id" required>
                </div>

                <div class="input-with-icon form-group" style="position: relative;">
                    <label for="password_admin">Password</label>
                    <iconify-icon icon="lucide:key" class="left-icon"></iconify-icon>
                    <input type="password" id="password_admin" name="password" class="form-control" placeholder="••••••••" required>
                    <button type="button" class="toggle-pwd" id="togglePwd" title="Tampilkan Password" style="position: absolute; right: 10px; top: 35px; background: none; border: none; cursor: pointer;">
                        <iconify-icon icon="lucide:eye"></iconify-icon>
                    </button>
                </div>

                <div class="checkbox-group" style="margin-bottom: 24px;">
                    <input type="checkbox" name="remember" id="remember_admin">
                    <label for="remember_admin">Ingat saya</label>
                </div>

                <button type="submit" class="btn-submit" style="width: 100%;">Masuk sebagai Admin</button>
            </form>

            <div class="security-footer" style="text-align: center; margin-top: 20px; font-size: 12px; color: #94a3b8;">
                Internal Security Protocol Active
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk mengganti antar tab Mahasiswa dan Admin
        function switchRole(role) {
            // Menghapus status active dari semua tombol dan form
            document.getElementById('btn-mahasiswa').classList.remove('active');
            document.getElementById('btn-admin').classList.remove('active');
            document.getElementById('form-mahasiswa').classList.remove('active');
            document.getElementById('form-admin').classList.remove('active');

            // Menambahkan status active ke tombol dan form yang dipilih
            if (role === 'mahasiswa') {
                document.getElementById('btn-mahasiswa').classList.add('active');
                document.getElementById('form-mahasiswa').classList.add('active');
            } else if (role === 'admin') {
                document.getElementById('btn-admin').classList.add('active');
                document.getElementById('form-admin').classList.add('active');
            }
        }

        // Fungsi untuk toggle password pada form admin
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
    </script>

</body>

</html>