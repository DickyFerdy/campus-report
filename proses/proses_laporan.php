<?php
// Menambahkan pengecekan session untuk memastikan $_SESSION bisa diakses dengan aman
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user_id'])) {
     header("Location: index.php");
     exit();
}

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id       = $_SESSION['user_id'];
    $judul_laporan = trim($_POST['judul_laporan'] ?? '');
    $kategori      = trim($_POST['kategori'] ?? '');
    $gedung        = trim($_POST['gedung'] ?? '');
    $detail_lokasi = trim($_POST['detail_lokasi'] ?? '');
    $prioritas     = trim($_POST['prioritas'] ?? '');
    $deskripsi     = trim($_POST['deskripsi'] ?? '');

    // 1. Validasi input teks kosong
    if (empty($judul_laporan) || empty($kategori) || empty($gedung) || empty($detail_lokasi) || empty($prioritas) || empty($deskripsi)) {
        $pesan = "<div class='alert-error'>Semua kolom wajib diisi!</div>";
    } else {
        // 2. Proses Upload Foto Bukti
        $foto_bukti = "";

        // Mengecek apakah ada file yang diupload dan tidak ada error
        if (isset($_FILES['foto_bukti']) && $_FILES['foto_bukti']['error'] === UPLOAD_ERR_OK) {
            $file_tmp  = $_FILES['foto_bukti']['tmp_name'];
            $file_name = $_FILES['foto_bukti']['name'];
            $file_size = $_FILES['foto_bukti']['size'];

            // Mengambil ekstensi file (misal: jpg, png)
            $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_ext = ['png', 'jpg', 'jpeg'];

            // Validasi format file
            if (!in_array($ext, $allowed_ext)) {
                $pesan = "<div class='alert-error'>Format foto harus PNG, JPG, atau JPEG!</div>";
            }
            // Validasi ukuran maksimal (5MB = 5 * 1024 * 1024 byte)
            else if ($file_size > 5242880) {
                $pesan = "<div class='alert-error'>Ukuran foto maksimal 5MB!</div>";
            } else {
                // Membuat nama file unik agar tidak saling timpa
                $new_file_name = uniqid('rep_', true) . '.' . $ext;

                // Menentukan lokasi penyimpanan file
                $upload_path = __DIR__ . '/../assets/uploads/';
                $destination = $upload_path . $new_file_name;

                // Memindahkan file dari penyimpanan sementara ke folder uploads
                if (move_uploaded_file($file_tmp, $destination)) {
                    $foto_bukti = $new_file_name;
                } else {
                    $pesan = "<div class='alert-error'>Sistem gagal mengunggah foto. Pastikan folder assets/uploads/ sudah dibuat.</div>";
                }
            }
        } else {
            $pesan = "<div class='alert-error'>Foto bukti wajib diunggah!</div>";
        }

        // 3. Menyimpan ke Database (Jika tidak ada error dan foto berhasil diupload)
        if (empty($pesan) && !empty($foto_bukti)) {
            $stmt = $conn->prepare("INSERT INTO reports (user_id, judul_laporan, kategori, gedung, detail_lokasi, prioritas, deskripsi, foto_bukti) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

            // i = integer (user_id), s = string (sisanya)
            $stmt->bind_param("isssssss", $user_id, $judul_laporan, $kategori, $gedung, $detail_lokasi, $prioritas, $deskripsi, $foto_bukti);

            if ($stmt->execute()) {
                // Menyimpan pesan sukses ke dalam session
                $_SESSION['sukses_laporan'] = "Laporan berhasil dikirim! Teknisi kami akan segera meninjaunya.";

                // Diarahkan (redirect) kembali ke dashboard
                header("Location: dashboard.php");
                exit();
            } else {
                $pesan = "<div class='alert-error'>Terjadi kesalahan database: " . $stmt->error . "</div>";
            }

            if (isset($stmt) && $stmt instanceof mysqli_stmt) {
                $stmt->close();
            }
        }
    }
}
