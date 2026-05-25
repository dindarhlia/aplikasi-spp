<?php
// Aktifkan pelacak error agar jika ada salah ketik langsung muncul di browser
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host     = "localhost"; 
$username = "root";      
$password = "";          
$database = "db_spp";    

// Membuka koneksi ke database
$koneksi = mysqli_connect($host, $username, $password, $database);

// Cek koneksi berhasil atau tidak
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>