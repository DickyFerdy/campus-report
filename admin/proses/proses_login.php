<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$max_attempts = 5;
$lockout_time = 300;
$is_locked = false;

if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= $max_attempts) {
    $time_passed = time() - $_SESSION['last_failed_login'];

    if ($time_passed < $lockout_time) {
        $is_locked = true;
        $time_left = $lockout_time - $time_passed;
        $minutes_left = ceil($time_left / 60);

        $_SESSION['pesan_admin'] = "<div style='background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:14px 16px; margin-bottom:24px; color:#b91c1c; font-size:13px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:8px; text-align:center;'><iconify-icon icon='lucide:shield-alert' style='font-size:18px;'></iconify-icon><span>Terlalu banyak percobaan gagal. Coba lagi dalam $minutes_left menit.</span></div>";
        header("Location: ../../index.php?role=admin");
        exit();
    } else {
        $_SESSION['login_attempts'] = 0;
        unset($_SESSION['last_failed_login']);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$is_locked) {
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email) || empty($password)) {
        $_SESSION['pesan_admin'] = "<div style='background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:14px 16px; margin-bottom:24px; color:#b91c1c; font-size:13px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:8px; text-align:center;'><iconify-icon icon='lucide:alert-triangle' style='font-size:18px;'></iconify-icon><span>Email dan Password wajib diisi!</span></div>";
        header("Location: ../../index.php?role=admin");
        exit();
    } else {
        $stmt = $conn->prepare("SELECT id, nama_admin, password FROM admins WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();

            if (password_verify($password, $admin['password'])) {
                unset($_SESSION['login_attempts']);
                unset($_SESSION['last_failed_login']);
                session_regenerate_id(true);

                $_SESSION['admin_id']   = $admin['id'];
                $_SESSION['admin_nama'] = $admin['nama_admin'];

                header("Location: ../dashboard.php");
                exit();
            } else {
                $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
                $_SESSION['last_failed_login'] = time();

                $_SESSION['pesan_admin'] = "<div style='background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:14px 16px; margin-bottom:24px; color:#b91c1c; font-size:13px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:8px; text-align:center;'><iconify-icon icon='lucide:lock' style='font-size:18px;'></iconify-icon><span>Akses Ditolak: Password salah!</span></div>";
                header("Location: ../../index.php?role=admin");
                exit();
            }
        } else {
            $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
            $_SESSION['last_failed_login'] = time();

            $_SESSION['pesan_admin'] = "<div style='background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:14px 16px; margin-bottom:24px; color:#b91c1c; font-size:13px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:8px; text-align:center;'><iconify-icon icon='lucide:user-x' style='font-size:18px;'></iconify-icon><span>Akses Ditolak: Email tidak terdaftar!</span></div>";
            header("Location: ../../index.php?role=admin");
            exit();
        }
        $stmt->close();
    }
}
?>