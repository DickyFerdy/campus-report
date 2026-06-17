<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
                // Sesi disederhanakan
                $_SESSION['admin_id']   = $admin['id'];
                $_SESSION['admin_nama'] = $admin['nama_admin'];
                
                header("Location: dashboard.php");
                exit();
            } else {
                $pesan = "<div class='alert-error'><iconify-icon icon='lucide:lock'></iconify-icon> Akses Ditolak: Password salah!</div>";
            }
        } else {
            $pesan = "<div class='alert-error'><iconify-icon icon='lucide:user-x'></iconify-icon> Akses Ditolak: Email tidak terdaftar!</div>";
        }
        $stmt->close();
    }
}
?>