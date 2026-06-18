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

// Menangkap parameter dari URL (GET)
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? strtolower($_GET['filter']) : 'semua';
$sort   = isset($_GET['sort']) ? strtolower($_GET['sort']) : 'terbaru';
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;

// 1. Mengambil Statistik Cepat (Total & Selesai)
$stat_total = 0;
$stat_selesai = 0;
$stmt_stat = $conn->prepare("SELECT status, COUNT(*) as jml FROM reports WHERE user_id = ? GROUP BY status");
$stmt_stat->bind_param("i", $user_id);
$stmt_stat->execute();
$res_stat = $stmt_stat->get_result();
while ($row = $res_stat->fetch_assoc()) {
    $stat_total += $row['jml'];
    if (strtolower($row['status']) == 'selesai') $stat_selesai = $row['jml'];
}
$stmt_stat->close();

// 2. Merakit Query Utama secara Dinamis
$sql_base = "FROM reports WHERE user_id = ?";
$types = "i";
$params = [$user_id];

if ($search !== '') {
    $sql_base .= " AND (judul_laporan LIKE ? OR detail_lokasi LIKE ? OR gedung LIKE ?)";
    $searchTerm = "%" . $search . "%";
    array_push($params, $searchTerm, $searchTerm, $searchTerm);
    $types .= "sss";
}

if ($filter !== 'semua') {
    $sql_base .= " AND LOWER(status) = ?";
    $params[] = $filter;
    $types .= "s";
}

// Menghitung total baris untuk Pagination
$sql_count = "SELECT COUNT(id) as total " . $sql_base;
$stmt_count = $conn->prepare($sql_count);
$stmt_count->bind_param($types, ...$params);
$stmt_count->execute();
$total_rows = $stmt_count->get_result()->fetch_assoc()['total'];
$stmt_count->close();

// Pengaturan Pagination
$limit = 5; // Menampilkan 5 laporan per halaman
$total_pages = ceil($total_rows / $limit);
$offset = ($page - 1) * $limit;

// Mengambil Data Laporan (Tambahkan Sort & Limit)
$order_sql = ($sort === 'terlama') ? 'ASC' : 'DESC';
$sql_data = "SELECT * " . $sql_base . " ORDER BY created_at " . $order_sql . " LIMIT ? OFFSET ?";
$types .= "ii";
array_push($params, $limit, $offset);

$stmt_data = $conn->prepare($sql_data);
$stmt_data->bind_param($types, ...$params);
$stmt_data->execute();
$reports = $stmt_data->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt_data->close();

// Logika Inisial Profil
$words = explode(" ", $nama_user);
$inisial = "";
foreach ($words as $w) {
    $inisial .= mb_substr($w, 0, 1);
}
$inisial = strtoupper(substr($inisial, 0, 2));

// Format Tanggal
$bulan_indo = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'];

function format_tgl_riwayat(string $datetime): string
{
    global $bulan_indo;
    $ts = strtotime($datetime);
    return date('d', $ts) . ' ' . $bulan_indo[(int)date('m', $ts)] . '<br><span style="font-size:11px; color:#94a3b8;">' . date('Y', $ts) . '</span>';
}
