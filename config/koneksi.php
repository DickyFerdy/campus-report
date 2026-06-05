<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "campus_report";
$port = 3306;

// koneksi ke database
$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>