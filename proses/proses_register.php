<?php
require_once __DIR__ . '/../config/koneksi.php';

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap  = trim($_POST['nama_lengkap'] ?? '');
    $npm           = trim($_POST['npm'] ?? '');
    $email         = trim($_POST['email'] ?? '');
    $password      = $_POST['password'] ?? '';
    $konfirmasi    = $_POST['konfirmasi_password'] ?? '';
    $program_studi = $_POST['program_studi'] ?? '';

    if (empty($nama_lengkap) || empty($npm) || empty($email) || empty($password) || empty($program_studi)) {
        $pesan = "<div class='alert-error'>Semua kolom wajib diisi!</div>";
    } else if ($password !== $konfirmasi) {
        $pesan = "<div class='alert-error'>Password dan Konfirmasi tidak cocok!</div>";
    } else if (!isset($_POST['syarat'])) {
        $pesan = "<div class='alert-error'>Anda harus menyetujui Syarat & Ketentuan.</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, npm, email, password, program_studi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama_lengkap, $npm, $email, $hashed_password, $program_studi);

        try {
            if ($stmt->execute()) {
                $pesan = "<div class='alert-success'>Pendaftaran berhasil! Silahkan <a href='index.php' style='color: #1a56db; font-weight:600;'>Masuk</a>.</div>";
            } else {
                $pesan = "<div class='alert-error'>Terjadi kesalahan: " . $stmt->error . "</div>";
            }
        } catch (mysqli_sql_exception $e) {
            if ((int)$e->getCode() === 1062) {
                $pesan = "<div class='alert-error'>NPM atau Email Kampus sudah terdaftar!</div>";
            } else {
                $pesan = "<div class='alert-error'>Terjadi kesalahan: " . $e->getMessage() . "</div>";
            }
        }

        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }
}
