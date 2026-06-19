<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("UPDATE reports SET is_notif_read = 1 WHERE user_id = ? AND status != 'Menunggu'");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }
    $stmt->close();
}
?>