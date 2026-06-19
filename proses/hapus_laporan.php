<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $report_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    $cek_query = $conn->prepare("SELECT foto_bukti FROM reports WHERE id = ? AND user_id = ? AND status = 'Menunggu'");
    $cek_query->bind_param("ii", $report_id, $user_id);
    $cek_query->execute();
    $result = $cek_query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (!empty($row['foto_bukti'])) {
            $file_path = __DIR__ . '/../assets/uploads/' . $row['foto_bukti'];
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        $hapus_stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
        $hapus_stmt->bind_param("i", $report_id);

        if ($hapus_stmt->execute()) {
            $_SESSION['sukses_laporan'] = "Laporan berhasil dibatalkan dan dihapus.";
            header("Location: ../dashboard.php");
            exit();
        }
    } else {
        header("Location: ../dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
