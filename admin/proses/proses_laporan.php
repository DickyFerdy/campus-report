<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

// Proteksi akses admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$admin_nama = $_SESSION['admin_nama'];
$words = explode(" ", $admin_nama);
$inisial_admin = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

// Tangkap Parameter Filter
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : 'semua';
$status   = isset($_GET['status']) ? $_GET['status'] : 'semua';
$sort     = isset($_GET['sort']) ? $_GET['sort'] : 'terbaru';
$page     = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($page < 1) $page = 1;

// Base Query dengan JOIN untuk mendapat nama pelapor
$sql_base = "FROM reports r JOIN users u ON r.user_id = u.id WHERE 1=1";

$params = [];
$types = "";

if ($kategori !== 'semua') {
    $sql_base .= " AND r.kategori = ?";
    $params[] = $kategori;
    $types .= "s";
}
if ($status !== 'semua') {
    $sql_base .= " AND LOWER(r.status) = ?";
    $params[] = strtolower($status);
    $types .= "s";
}
if ($search !== '') {
    $search_param = "%{$search}%";
    $sql_base .= " AND (r.judul_laporan LIKE ? OR r.detail_lokasi LIKE ? OR u.nama_lengkap LIKE ?)";
    array_push($params, $search_param, $search_param, $search_param);
    $types .= "sss";
}

// Hitung total baris untuk Pagination
$sql_count = "SELECT COUNT(r.id) as total " . $sql_base;
$stmt_count = $conn->prepare($sql_count);
if (!empty($params)) $stmt_count->bind_param($types, ...$params);
$stmt_count->execute();
$total_rows = $stmt_count->get_result()->fetch_assoc()['total'];
$stmt_count->close();

$limit = 5;
$total_pages = ceil($total_rows / $limit);
$offset = ($page - 1) * $limit;

// Ambil Data Utama
$order_sql = ($sort === 'terlama') ? 'ASC' : 'DESC';
$sql_data = "SELECT r.id, r.judul_laporan, r.kategori, r.gedung, r.detail_lokasi, r.status, r.created_at, u.nama_lengkap as pelapor " . $sql_base . " ORDER BY r.created_at " . $order_sql . " LIMIT ? OFFSET ?";
$types .= "ii";
array_push($params, $limit, $offset);

$stmt_data = $conn->prepare($sql_data);
if (!empty($params)) $stmt_data->bind_param($types, ...$params);
$stmt_data->execute();
$reports = $stmt_data->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_data->close();

$stat_hari_ini = 0;
$stat_urgent = 0;
$stat_respon = "0j";

// 1. Laporan Hari Ini
$res1 = $conn->query("SELECT COUNT(*) as jml FROM reports WHERE DATE(created_at) = CURDATE()");
if ($res1) $stat_hari_ini = $res1->fetch_assoc()['jml'];

// 2. Laporan Urgent (Prioritas Tinggi & Masih Menunggu)
$res2 = $conn->query("SELECT COUNT(*) as jml FROM reports WHERE prioritas = 'Tinggi' AND status = 'Menunggu'");
if ($res2) $stat_urgent = $res2->fetch_assoc()['jml'];

// 3. Rata-rata Respon (Selisih waktu dibuat dan diproses)
$res3 = $conn->query("SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) AS avg_min FROM reports WHERE status != 'Menunggu'");
if ($res3) {
    $row3 = $res3->fetch_assoc();
    if ($row3['avg_min'] > 0) {
        $jam = round($row3['avg_min'] / 60, 1);
        $stat_respon = $jam . "j";
    } else {
        $stat_respon = "-";
    }
}

$bulan_indo = [1 => 'Okt', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];
function format_tgl_admin($datetime) {
    global $bulan_indo;
    $ts = strtotime($datetime);
    return date('d', $ts) . ' ' . $bulan_indo[(int)date('m', $ts)] . '<br><span style="font-size:12px; color:#94a3b8;">' . date('Y', $ts) . '</span>';
}
?>