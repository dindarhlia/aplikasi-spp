<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id_petugas = mysqli_real_escape_string($koneksi, $_GET['id']);

    // Mencegah admin menghapus akun dirinya sendiri saat login
    if ($id_petugas === $_SESSION['id_petugas']) {
        echo "<script>alert('Gagal! Anda tidak bisa menghapus akun yang sedang Anda gunakan sendiri.'); window.location='petugas_tampil.php';</script>";
        exit;
    }

    $query_hapus = "DELETE FROM tb_petugas WHERE id_petugas = '$id_petugas'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        echo "<script>alert('Data petugas berhasil dihapus!'); window.location='petugas_tampil.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data karena terikat relasi database.'); window.location='petugas_tampil.php';</script>";
    }
}
exit;
?>