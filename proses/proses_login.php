<?php
// Mengecek apakah sesi sudah berjalan sebelum memulai sesi baru
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Pastikan ini hanya memproses form dari tab Mahasiswa
    if (isset($_POST['role']) && $_POST['role'] === 'mahasiswa') {

        $identifier = trim($_POST['identifier'] ?? '');
        $password   = $_POST['password'] ?? '';

        if (empty($identifier) || empty($password)) {
            $_SESSION['error_mhs'] = "Email/NPM dan Password wajib diisi!";
            header("Location: login.php");
            exit();
        } else {
            // Update query untuk ikut mengambil kolom 'npm'
            $stmt = $conn->prepare("SELECT id, nama_lengkap, npm, password FROM users WHERE email = ? OR npm = ?");
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                if (password_verify($password, $row['password'])) {
                    $_SESSION['user_id'] = $row['id'];
                    $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                    $_SESSION['npm'] = $row['npm']; // Simpan NPM ke session agar tidak perlu query ulang di topbar

                    header("Location: dashboard.php");
                    exit();
                } else {
                    // JIKA PASSWORD SALAH
                    $_SESSION['error_mhs'] = "Password yang Anda masukkan salah!";
                    header("Location: login.php");
                    exit();
                }
            } else {
                // JIKA EMAIL/NPM TIDAK DITEMUKAN
                $_SESSION['error_mhs'] = "Akun dengan NPM/Email tersebut tidak ditemukan!";
                header("Location: login.php");
                exit();
            }

            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
        }
    }
}
?>