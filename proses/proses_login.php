<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/koneksi.php';

$pesan = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $identifier = trim($_POST['identifier'] ?? '');
    $password   = $_POST['password'] ?? '';

    if (empty($identifier) || empty($password)) {
        $pesan = "<div class='alert-error'>Email/NPM dan Password wajib diisi!</div>";
    } else {
        $stmt = $conn->prepare("SELECT id, nama_lengkap, npm, password FROM users WHERE email = ? OR npm = ?");
        $stmt->bind_param("ss", $identifier, $identifier);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            if (password_verify($password, $row['password'])) {
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['npm'] = $row['npm'];

                header("Location: dashboard.php");
                exit();
            } else {
                $pesan = "<div class='alert-error'>Password yang Anda masukkan salah!</div>";
            }
        } else {
            $pesan = "<div class='alert-error'>Akun dengan NPM/Email tersebut tidak ditemukan!</div>";
        }

        if (isset($stmt) && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
    }
}
?>