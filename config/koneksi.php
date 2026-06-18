<?php
date_default_timezone_set('Asia/Jakarta');
    
$host = "sql112.infinityfree.com";
$user = "if0_42094837";
$pass = "noviaNur1704";
$dbname = "if0_42094837_campus_report";
$port = 3306;

// koneksi ke database
$conn = new mysqli($host, $user, $pass, $dbname, $port);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$conn->query("SET time_zone = '+07:00'");
?>