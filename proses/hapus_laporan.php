<?php
session_start();
require_once __DIR__ . '/../config/koneksi.php';

// Memastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $report_id = $_GET['id'];
    $user_id = $_SESSION['user_id'];

    // 1. Mengecek dulu apakah laporan ini milik user tersebut dan statusnya masih 'menunggu'
    // Ini penting agar user nakal tidak bisa menghapus laporan orang lain lewat URL
    $cek_query = $conn->prepare("SELECT foto_bukti FROM reports WHERE id = ? AND user_id = ? AND status = 'Menunggu'");
    $cek_query->bind_param("ii", $report_id, $user_id);
    $cek_query->execute();
    $result = $cek_query->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // 2. Menghapus file foto dari folder (jika ada)
        if (!empty($row['foto_bukti'])) {
            $file_path = __DIR__ . '/../assets/uploads/' . $row['foto_bukti'];
            if (file_exists($file_path)) {
                unlink($file_path); // Menghapus file fisik gambar
            }
        }

        // 3. Menghapus data dari database
        $hapus_stmt = $conn->prepare("DELETE FROM reports WHERE id = ?");
        $hapus_stmt->bind_param("i", $report_id);
        
        if ($hapus_stmt->execute()) {
            // Berhasil dihapus, lemparkan notifikasi ke dashboard
            $_SESSION['sukses_laporan'] = "Laporan berhasil dibatalkan dan dihapus.";
            header("Location: ../dashboard.php");
            exit();
        }
    } else {
        // Gagal (laporan tidak ditemukan, milik orang lain, atau sudah diproses)
        // Lemparkan kembali ke dashboard tanpa pesan sukses
        header("Location: ../dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>