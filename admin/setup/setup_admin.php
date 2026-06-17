<?php
// setup_admin.php (Jalankan sekali di browser lalu hapus)
require_once '../../config/koneksi.php';

$nama = 'Admin Sarpras';
$email = 'admin@universitas.ac.id';
$pass = password_hash('admin123', PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO admins (nama_admin, email, password) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $nama, $email, $pass);

if ($stmt->execute()) echo "<h3 style='color:green;'>Admin berhasil dibuat! (Email: admin@universitas.ac.id | Pass: admin123)</h3>";
?>