<?php
// KUNCI KEAMANAN: Hapus atau beri komentar pada baris 'die()' di bawah ini 
// HANYA jika Anda butuh mereset/menambah admin saat development. 
// JANGAN LUPA KEMBALIKAN BARIS INI SETELAH SELESAI.
// die("Akses ditolak: File setup_admin.php telah dikunci demi keamanan sistem.");

// setup_admin.php (Jalankan sekali di browser lalu hapus)
require_once '../../config/koneksi.php';

$email = 'admin@universitas.ac.id';

// MENGECEK APAKAH ADMIN SUDAH ADA SEBELUM INSERT
$stmt_check = $conn->prepare("SELECT id FROM admins WHERE email = ?");
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result = $stmt_check->get_result();

if ($result->num_rows > 0) {
    echo "<h3 style='color:orange;'><iconify-icon icon='lucide:alert-circle'></iconify-icon> Tindakan dibatalkan: Admin dengan email $email sudah ada di dalam database.</h3>";
} else {
    // Jika belum ada, proses insert berjalan
    $nama = 'Admin Sarpras';
    $pass = password_hash('admin123', PASSWORD_DEFAULT);

    $stmt_insert = $conn->prepare("INSERT INTO admins (nama_admin, email, password) VALUES (?, ?, ?)");
    $stmt_insert->bind_param("sss", $nama, $email, $pass);

    if ($stmt_insert->execute()) {
        echo "<h3 style='color:green;'><iconify-icon icon='lucide:check-circle'></iconify-icon> Admin berhasil dibuat! (Email: $email | Pass: admin123)</h3>";
    } else {
        echo "<h3 style='color:red;'>Gagal membuat admin: " . $conn->error . "</h3>";
    }
    $stmt_insert->close();
}

$stmt_check->close();
