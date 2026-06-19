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

$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($report_id === 0) {
    header("Location: ../dashboard.php");
    exit();
}

$stmt = $conn->prepare("
    SELECT r.*, u.nama_lengkap, u.npm 
    FROM reports r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.id = ? AND r.user_id = ?
");

if (!$stmt) {
    die("<h3 style='color:red;'>ERROR DATABASE:</h3> <p>" . $conn->error . "</p> 
         <p>Pastikan Anda sudah menjalankan ALTER TABLE untuk menambahkan <b>respon_admin</b> dan <b>updated_at</b>.</p>");
}

$stmt->bind_param("ii", $report_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: ../dashboard.php");
    exit();
}

$detail = $result->fetch_assoc();
$stmt->close();

$prioritas = $detail['prioritas'] ?? 'Sedang';
$sla = "24 Jam";
if ($prioritas == 'Tinggi') $sla = "12 Jam";
if ($prioritas == 'Rendah') $sla = "48 Jam";

$bulan_indo = [
    1 => 'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember'
];

$ts_dibuat = strtotime($detail['created_at']);
$tanggal_dibuat = date('d', $ts_dibuat) . ' ' . $bulan_indo[(int)date('m', $ts_dibuat)] . ' ' . date('Y, H:i', $ts_dibuat) . ' WIB';

$ts_update = !empty($detail['updated_at']) ? strtotime($detail['updated_at']) : $ts_dibuat;
$tanggal_update = date('d', $ts_update) . ' ' . $bulan_indo[(int)date('m', $ts_update)] . ' ' . date('Y, H:i', $ts_update);

$words = explode(" ", $nama_user);
$inisial = "";
foreach ($words as $w) {
    $inisial .= mb_substr($w, 0, 1);
}
$inisial = strtoupper(substr($inisial, 0, 2));
if (empty($inisial)) $inisial = "M";
?>