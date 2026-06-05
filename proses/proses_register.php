<?php
require_once 'config/koneksi.php';

$pesan = ""; // variabel untuk menampung notifikasi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama_lengkap  = $_POST['nama_lengkap'];
    $npm           = $_POST['npm'];
    $email         = $_POST['email'];
    $password      = $_POST['password'];
    $konfirmasi    = $_POST['konfirmasi_password'];
    $program_studi = $_POST['program_studi'];

    // validasi input
    if (empty($nama_lengkap) || empty($npm) || empty($email) || empty($password) || empty($program_studi)) {
        $pesan = "<p style='color: #dc2626; text-align: center; font-size: 13px;'>Semua kolom wajib diisi!</p>";
    } 
    else if ($password !== $konfirmasi) {
        $pesan = "<p style='color: #dc2626; text-align: center; font-size: 13px;'>Password dan Konfirmasi tidak cocok!</p>";
    } 
    else if (!isset($_POST['syarat'])) {
        $pesan = "<p style='color: #dc2626; text-align: center; font-size: 13px;'>Anda harus menyetujui Syarat & Ketentuan.</p>";
    } 
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (nama_lengkap, npm, email, password, program_studi) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nama_lengkap, $npm, $email, $hashed_password, $program_studi);

        if ($stmt->execute()) {
            $pesan = "<p style='color: #16a34a; text-align: center; font-size: 13px;'>Pendaftaran berhasil! Silahkan <a href='login.php' style='color: #1a56db; font-weight:600;'>Masuk</a>.</p>";
        } else {
            if ($conn->errno == 1062) {
                $pesan = "<p style='color: #dc2626; text-align: center; font-size: 13px;'>NPM atau Email Kampus sudah terdaftar!</p>";
            } else {
                $pesan = "<p style='color: #dc2626; text-align: center; font-size: 13px;'>Terjadi kesalahan: " . $stmt->error . "</p>";
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>