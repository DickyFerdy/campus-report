<?php
// Pengecekan session agar aman dari error duplikasi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Memanggil database
require_once __DIR__ . '/../config/koneksi.php';

// 1. Mengecek Sesi (Aman dari error $nama_user)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];
$nama_user = $_SESSION['nama_lengkap'] ?? 'Mahasiswa'; // Pakai fallback jika session kosong

// 2. Mengambil ID dari URL
$report_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($report_id === 0) {
    header("Location: ../dashboard.php");
    exit();
}

// ===========================================================================
// BLOK PENANGANAN POST (UPDATE STATUS & TANGGAPAN ADMIN) + VALIDASI CSRF
// ===========================================================================
$pesan = ''; // Inisialisasi variabel pesan agar siap dirender di HTML
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Validasi CSRF Token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die('Aksi ditolak: Token CSRF tidak valid atau kadaluarsa.');
    }

    // 2. Mengambil data dari form
    $respon_admin = trim($_POST['respon_admin'] ?? '');
    $action = $_POST['action'] ?? '';
    $new_status = '';

    // 3. Menentukan status baru berdasarkan tombol (action) yang ditekan
    if ($action === 'proses') {
        $new_status = 'Diproses';
    } elseif ($action === 'selesai') {
        $new_status = 'Selesai';
    } elseif ($action === 'tolak') {
        $new_status = 'Ditolak';
    }

    // 4. Menjalankan query update ke database
    if ($new_status !== '') {
        $update_stmt = $conn->prepare("UPDATE reports SET status = ?, respon_admin = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->bind_param("ssi", $new_status, $respon_admin, $report_id);
    } else {
        // Jika action = 'tanggapan_saja', update respon_admin saja (status tidak berubah)
        $update_stmt = $conn->prepare("UPDATE reports SET respon_admin = ?, updated_at = NOW() WHERE id = ?");
        $update_stmt->bind_param("si", $respon_admin, $report_id);
    }

    if ($update_stmt->execute()) {
        $pesan = "<div class='alert alert-success' style='margin-bottom: 16px;'>Tindakan berhasil disimpan!</div>";
    } else {
        $pesan = "<div class='alert alert-danger' style='margin-bottom: 16px;'>Gagal menyimpan tindakan: " . $conn->error . "</div>";
    }
    $update_stmt->close();

    // 5. Regenerasi token CSRF setelah form sukses diproses (Best Practice)
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
// ===========================================================================

// 3. Pengecekan error SQL
$stmt = $conn->prepare("
    SELECT r.*, u.nama_lengkap, u.npm 
    FROM reports r 
    JOIN users u ON r.user_id = u.id 
    WHERE r.id = ? AND r.user_id = ?
");

// Jika Query gagal, menampilkan error jelas!
if (!$stmt) {
    die("<h3 style='color:red;'>ERROR DATABASE:</h3> <p>" . $conn->error . "</p> 
         <p>Pastikan Anda sudah menjalankan ALTER TABLE untuk menambahkan <b>respon_admin</b> dan <b>updated_at</b>.</p>");
}

$stmt->bind_param("ii", $report_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Jika laporan tidak ditemukan
if ($result->num_rows === 0) {
    header("Location: ../dashboard.php");
    exit();
}

$detail = $result->fetch_assoc();
$stmt->close();

// 4. Logika SLA
$prioritas = $detail['prioritas'] ?? 'Sedang'; // Cegah error undefined
$sla = "24 Jam";
if ($prioritas == 'Tinggi') $sla = "12 Jam";
if ($prioritas == 'Rendah') $sla = "48 Jam";

// 5. Logika Tanggal Anti-Error
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

// Tanggal Pembuatan
$ts_dibuat = strtotime($detail['created_at']);
$tanggal_dibuat = date('d', $ts_dibuat) . ' ' . $bulan_indo[(int)date('m', $ts_dibuat)] . ' ' . date('Y, H:i', $ts_dibuat) . ' WIB';

// Tanggal Update (Jika laporan lama/belum di-update, pakai tanggal dibuat agar tidak error)
$ts_update = !empty($detail['updated_at']) ? strtotime($detail['updated_at']) : $ts_dibuat;
$tanggal_update = date('d', $ts_update) . ' ' . $bulan_indo[(int)date('m', $ts_update)] . ' ' . date('Y, H:i', $ts_update);

// 6. Inisial Profil
$words = explode(" ", $nama_user);
$inisial = "";
foreach ($words as $w) {
    $inisial .= mb_substr($w, 0, 1);
}
$inisial = strtoupper(substr($inisial, 0, 2));
if (empty($inisial)) $inisial = "M"; // Fallback jika nama kosong
?>