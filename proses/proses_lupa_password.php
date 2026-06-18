<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $npm   = trim($_POST['npm'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($npm) || empty($email)) {
        $pesan = "<div class='alert-error'><iconify-icon icon='lucide:alert-circle'></iconify-icon> NPM dan Email wajib diisi!</div>";
    } else {
        $stmt = $conn->prepare("SELECT id, nama_lengkap FROM users WHERE npm = ? AND email = ?");
        $stmt->bind_param("ss", $npm, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // TODO: Implementasi pengiriman email menggunakan PHPMailer
            // Saat ini hanya simulasi UI untuk keperluan prototype
            $pesan = "<div class='alert-success' style='line-height: 1.5;'>
                        <iconify-icon icon='lucide:check-circle' style='font-size:20px; vertical-align:middle; margin-right:8px;'></iconify-icon>
                        <strong>Permintaan Berhasil!</strong><br>
                        Link instruksi pemulihan kata sandi telah dikirimkan ke email terdaftar Anda (<em>" . htmlspecialchars($email) . "</em>). Silakan periksa kotak masuk atau folder spam Anda dalam waktu 5 menit.
                      </div>";
        } else {
            $pesan = "<div class='alert-error'><iconify-icon icon='lucide:x-circle'></iconify-icon> Kombinasi NPM dan Email tidak ditemukan di sistem kami.</div>";
        }
        $stmt->close();
    }
}
