<?php
session_start();
require_once __DIR__ . '/../../config/koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../index.php");
    exit();
}

$admin_nama = $_SESSION['admin_nama'];
$words = explode(" ", $admin_nama);
$inisial_admin = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($report_id === 0) {
    header("Location: laporan_masuk.php");
    exit();
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $tanggapan = trim($_POST['respon_admin'] ?? '');

    $new_status = "";
    if ($action == 'proses') $new_status = 'Diproses';
    elseif ($action == 'selesai') $new_status = 'Selesai';
    elseif ($action == 'tolak') $new_status = 'Ditolak';

    if ($new_status !== "") {
        $stmt_upd = $conn->prepare("UPDATE reports SET status = ?, respon_admin = ?, is_notif_read = 0 WHERE id = ?");
        $stmt_upd->bind_param("ssi", $new_status, $tanggapan, $report_id);
    } else {
        $stmt_upd = $conn->prepare("UPDATE reports SET respon_admin = ?, is_notif_read = 0 WHERE id = ?");
        $stmt_upd->bind_param("si", $tanggapan, $report_id);
    }

    if ($stmt_upd->execute()) {
        $pesan = "<div class='alert-success' style='margin-bottom: 20px;'><iconify-icon icon='lucide:check-circle'></iconify-icon> Laporan berhasil diperbarui!</div>";
    } else {
        $pesan = "<div class='alert-error' style='margin-bottom: 20px;'><iconify-icon icon='lucide:alert-triangle'></iconify-icon> Gagal memperbarui laporan.</div>";
    }
    $stmt_upd->close();
}

$stmt = $conn->prepare("SELECT r.*, u.nama_lengkap as pelapor FROM reports r JOIN users u ON r.user_id = u.id WHERE r.id = ?");
$stmt->bind_param("i", $report_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: laporan_masuk.php");
    exit();
}
$detail = $result->fetch_assoc();
$stmt->close();

$bulan_indo = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
function format_tgl_full($datetime)
{
    global $bulan_indo;
    $ts = strtotime($datetime);
    return date('d', $ts) . ' ' . $bulan_indo[(int)date('m', $ts)] . ' ' . date('Y', $ts) . ' • ' . date('H:i A', $ts);
}
