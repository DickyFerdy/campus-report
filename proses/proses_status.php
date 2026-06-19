<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$nama_user = $_SESSION['nama_lengkap'] ?? 'Mahasiswa';

$filter = isset($_GET['filter']) ? strtolower($_GET['filter']) : 'semua';
$sort = isset($_GET['sort']) ? strtolower($_GET['sort']) : 'terbaru';

$query = "SELECT id, judul_laporan, gedung, detail_lokasi, status, created_at, updated_at FROM reports WHERE user_id = ?";
if ($filter !== 'semua') {
    $query .= " AND LOWER(status) = ?";
}

$order_sql = ($sort === 'terlama') ? 'ASC' : 'DESC';
$query .= " ORDER BY created_at " . $order_sql;

$stmt = $conn->prepare($query);
if ($filter !== 'semua') {
    $stmt->bind_param("is", $user_id, $filter);
} else {
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$reports = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$words = explode(" ", $nama_user);
$inisial = "";
foreach ($words as $w) {
    $inisial .= mb_substr($w, 0, 1);
}
$inisial = strtoupper(substr($inisial, 0, 2));

function time_ago(string $datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return "Baru saja";
    if ($diff < 3600) return floor($diff / 60) . " menit yang lalu";
    if ($diff < 86400) return floor($diff / 3600) . " jam yang lalu";
    if ($diff < 604800) return floor($diff / 86400) . " hari yang lalu";
    return date("d M Y", $time);
}

$bulan_indo = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
function format_tgl(string $datetime): string
{
    global $bulan_indo;
    $ts = strtotime($datetime);
    return date('d', $ts) . ' ' . $bulan_indo[(int)date('m', $ts)] . ' ' . date('Y', $ts);
}
