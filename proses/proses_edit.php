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
    header("Location: dashboard.php");
    exit();
}

$stmt = $conn->prepare("SELECT * FROM reports WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $report_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: dashboard.php");
    exit();
}
$laporan = $result->fetch_assoc();
$stmt->close();

if (strtolower($laporan['status']) !== 'menunggu') {
    header("Location: detail_laporan.php?id=" . $report_id);
    exit();
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judul_laporan = trim($_POST['judul_laporan'] ?? '');
    $kategori      = trim($_POST['kategori'] ?? '');
    $gedung        = trim($_POST['gedung'] ?? '');
    $detail_lokasi = trim($_POST['detail_lokasi'] ?? '');
    $prioritas     = trim($_POST['prioritas'] ?? '');
    $deskripsi     = trim($_POST['deskripsi'] ?? '');

    if (empty($judul_laporan) || empty($kategori) || empty($gedung) || empty($detail_lokasi) || empty($prioritas) || empty($deskripsi)) {
        $pesan = "<div class='alert-error'>Semua kolom teks wajib diisi!</div>";
    } else {
        $foto_bukti = $laporan['foto_bukti'];

        if (isset($_FILES['foto_bukti']) && $_FILES['foto_bukti']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['foto_bukti']['tmp_name'];
            $file_name = $_FILES['foto_bukti']['name'];
            $file_size = $_FILES['foto_bukti']['size'];

            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['png', 'jpg', 'jpeg'];

            if (!in_array($ext, $allowed_ext)) {
                $pesan = "<div class='alert-error'>Format foto harus PNG, JPG, atau JPEG!</div>";
            } else if ($file_size > 5242880) {
                $pesan = "<div class='alert-error'>Ukuran foto maksimal 5MB!</div>";
            } else {
                $new_file_name = uniqid('rep_', true) . '.' . $ext;
                $upload_path = __DIR__ . '/../assets/uploads/';
                $destination = $upload_path . $new_file_name;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $foto_bukti = $new_file_name;

                    if (!empty($laporan['foto_bukti']) && file_exists($upload_path . $laporan['foto_bukti'])) {
                        unlink($upload_path . $laporan['foto_bukti']);
                    }
                } else {
                    $pesan = "<div class='alert-error'>Sistem gagal mengunggah foto baru.</div>";
                }
            }
        }

        if (empty($pesan)) {
            $stmt_update = $conn->prepare("UPDATE reports SET judul_laporan=?, kategori=?, gedung=?, detail_lokasi=?, prioritas=?, deskripsi=?, foto_bukti=? WHERE id=? AND user_id=?");
            $stmt_update->bind_param("sssssssii", $judul_laporan, $kategori, $gedung, $detail_lokasi, $prioritas, $deskripsi, $foto_bukti, $report_id, $user_id);

            if ($stmt_update->execute()) {
                $pesan = "<div class='alert-success'>Laporan berhasil diperbarui! <a href='detail_laporan.php?id=$report_id' style='color:#166534; font-weight:700;'>Lihat Detail</a></div>";

                $laporan['judul_laporan'] = $judul_laporan;
                $laporan['kategori'] = $kategori;
                $laporan['gedung'] = $gedung;
                $laporan['detail_lokasi'] = $detail_lokasi;
                $laporan['prioritas'] = $prioritas;
                $laporan['deskripsi'] = $deskripsi;
                $laporan['foto_bukti'] = $foto_bukti;
            } else {
                $pesan = "<div class='alert-error'>Terjadi kesalahan database: " . $stmt_update->error . "</div>";
            }
            $stmt_update->close();
        }
    }
}

$words = explode(" ", $nama_user);
$inisial = "";
foreach ($words as $w) {
    $inisial .= mb_substr($w, 0, 1);
}
$inisial = strtoupper(substr($inisial, 0, 2));
if (empty($inisial)) $inisial = "M";
