<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_SESSION['admin_id'])) {
    // Ubah semua laporan 'Menunggu' yang belum dibaca menjadi sudah dibaca (1)
    $stmt = $conn->prepare("UPDATE reports SET is_admin_read = 1 WHERE status = 'Menunggu' AND is_admin_read = 0");
    
    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}
?>