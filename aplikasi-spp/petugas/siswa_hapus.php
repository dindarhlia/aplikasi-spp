<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$nisn = mysqli_real_escape_string($koneksi, $_GET['id']);

// Eksekusi hapus siswa
$query_hapus = "DELETE FROM tb_siswa WHERE nisn = '$nisn'";

if (mysqli_query($koneksi, $query_hapus)) {
    header("Location: siswa_tampil.php?status=hapus_sukses");
} else {
    header("Location: siswa_tampil.php?status=hapus_gagal");
}
exit;
?>