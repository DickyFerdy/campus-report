<?php
session_start();

require_once __DIR__ . '/../../config/koneksi.php';

// Proteksi Keamanan: Pastikan yang mengakses adalah Admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$admin_nama = $_SESSION['admin_nama'];

// Logika Inisial Profil Admin (Misal: "Admin Pengelola" -> "AP")
$words = explode(" ", $admin_nama);
$inisial_admin = "";
foreach ($words as $w) {
    $inisial_admin .= mb_substr($w, 0, 1);
}
$inisial_admin = strtoupper(substr($inisial_admin, 0, 2));
if (empty($inisial_admin)) $inisial_admin = "AD";

$stat_total = 0;
$stat_menunggu = 0;
$stat_diproses = 0;
$stat_selesai = 0;
$stat_ditolak = 0;

$stmt_stats = $conn->prepare("SELECT status, COUNT(*) as jumlah FROM reports GROUP BY status");
$stmt_stats->execute();
$result_stats = $stmt_stats->get_result();

while ($row = $result_stats->fetch_assoc()) {
    $stat_total += $row['jumlah']; // Tambahkan ke total keseluruhan
    
    $status_lower = strtolower($row['status']);
    if ($status_lower == 'menunggu') $stat_menunggu = $row['jumlah'];
    if ($status_lower == 'diproses') $stat_diproses = $row['jumlah'];
    if ($status_lower == 'selesai') $stat_selesai = $row['jumlah'];
    if ($status_lower == 'ditolak') $stat_ditolak = $row['jumlah'];
}
$stmt_stats->close();

$recent_reports = [];
$stmt_recent = $conn->prepare("SELECT id, judul_laporan, kategori, gedung, detail_lokasi, status, created_at FROM reports ORDER BY created_at DESC LIMIT 5");
$stmt_recent->execute();
$result_recent = $stmt_recent->get_result();

while ($row = $result_recent->fetch_assoc()) {
    $recent_reports[] = $row;
}
$stmt_recent->close();

$bulan_indo = [1 => 'Okt', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
if (!function_exists('format_tgl_admin')) {
    function format_tgl_admin($datetime) {
        global $bulan_indo;
        $ts = strtotime($datetime);
        return date('d', $ts) . ' ' . $bulan_indo[(int)date('m', $ts)] . '<br><span style="font-size:12px; color:#94a3b8;">' . date('Y', $ts) . '</span>';
    }
}
?>