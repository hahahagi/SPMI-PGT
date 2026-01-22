<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_spmi"; // Sesuai nama database yang baru dibuat

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) die("Koneksi Gagal: " . mysqli_connect_error());
