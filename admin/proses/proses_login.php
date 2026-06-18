<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$pesan = "";

// ===========================================================================
// KONFIGURASI RATE LIMITING (BRUTE-FORCE PROTECTION)
// ===========================================================================
$max_attempts = 5;       // Maksimal percobaan gagal
$lockout_time = 300;     // Waktu tunggu dalam detik (300 detik = 5 menit)
$is_locked = false;

// Pengecekan status lockout
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $max_attempts) {
    $time_passed = time() - $_SESSION['last_failed_login'];

    if ($time_passed < $lockout_time) {
        $is_locked = true;
        $time_left = $lockout_time - $time_passed;
        $minutes_left = ceil($time_left / 60);
        $pesan = "<div class='alert-error' style='background-color:#fee2e2; color:#b91c1c; padding:12px; border-radius:6px;'><iconify-icon icon='lucide:shield-alert'></iconify-icon> Terlalu banyak percobaan gagal. Silakan coba lagi dalam $minutes_left menit.</div>";
    } else {
        // Reset percobaan jika waktu tunggu sudah selesai
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['last_failed_login']);
    }
}
// ===========================================================================

// Proses Form jika status tidak terkunci
if ($_SERVER["REQUEST_METHOD"] == "POST" && !$is_locked) {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $pesan = "<div class='alert-error'><iconify-icon icon='lucide:alert-triangle'></iconify-icon> Email dan Password wajib diisi!</div>";
    } else {
        $stmt = $conn->prepare("SELECT id, nama_admin, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['password'])) {
                // Berhasil Login: Reset hitungan gagal
                unset($_SESSION['login_attempts']);
                unset($_SESSION['last_failed_login']);

                // Mencegah Session Fixation
                session_regenerate_id(true);

                $_SESSION['admin_id']   = $admin['id'];
                $_SESSION['admin_nama'] = $admin['nama_admin'];

                header("Location: dashboard.php");
                exit();
            } else {
                // Gagal Password: Catat percobaan
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                $_SESSION['last_failed_login'] = time();
                $_SESSION['error_admin'] = "Akses Ditolak: Password salah!";
                header("Location: ../login.php?tab=admin");
                exit();
            }
        } else {
            // Gagal Email: Catat percobaan (untuk mencegah user enumeration timing attack)
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['last_failed_login'] = time();
            $_SESSION['error_admin'] = "Akses Ditolak: Email tidak terdaftar!";
            header("Location: ../login.php?tab=admin");
            exit();
        }
        $stmt->close();
    }
}
