<?php
// Tambahkan guard pengecekan session untuk keamanan dan mencegah error duplikasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$nama_user = $_SESSION['nama_lengkap'];

$words = explode(" ", $nama_user);
$inisial = "";
foreach ($words as $w) {
    $inisial .= mb_substr($w, 0, 1);
}
$inisial = strtoupper(substr($inisial, 0, 2));

$stat_total = 0;
$stat_menunggu = 0;
$stat_diproses = 0;
$stat_selesai = 0;

$stmt_stats = $conn->prepare("SELECT status, COUNT(*) as jumlah FROM reports WHERE user_id = ? GROUP BY status");
$stmt_stats->bind_param("i", $user_id);
$stmt_stats->execute();
$result_stats = $stmt_stats->get_result();

while ($row = $result_stats->fetch_assoc()) {
    $stat_total += $row['jumlah'];
    if ($row['status'] == 'Menunggu') $stat_menunggu = $row['jumlah'];
    if ($row['status'] == 'Diproses') $stat_diproses = $row['jumlah'];
    if ($row['status'] == 'Selesai') $stat_selesai = $row['jumlah'];
}
$stmt_stats->close();

$recent_reports = [];
$stmt_recent = $conn->prepare("SELECT id, judul_laporan, kategori, gedung, status, created_at, foto_bukti FROM reports WHERE user_id = ? ORDER BY created_at DESC LIMIT 2");
$stmt_recent->bind_param("i", $user_id);
$stmt_recent->execute();
$result_recent = $stmt_recent->get_result();

while ($row = $result_recent->fetch_assoc()) {
    $recent_reports[] = $row;
}
$stmt_recent->close();
