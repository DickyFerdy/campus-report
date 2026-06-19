<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (isset($_SESSION['admin_id'])) {
    $stmt = $conn->prepare("UPDATE reports SET is_admin_read = 1 WHERE status = 'Menunggu' AND is_admin_read = 0 ORDER BY created_at DESC LIMIT 5");

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}
?>