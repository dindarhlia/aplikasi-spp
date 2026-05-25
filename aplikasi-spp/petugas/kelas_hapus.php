<?php
session_start();
include '../config/koneksi.php';

// Proteksi halaman: pastikan hanya admin yang bisa menghapus data master
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Mengambil id_kelas dari parameter URL (menggunakan metode GET)
$id_kelas = mysqli_real_escape_string($koneksi, $_GET['id']);

// Eksekusi query hapus
$query_hapus = "DELETE FROM tb_kelas WHERE id_kelas = '$id_kelas'";

if (mysqli_query($koneksi, $query_hapus)) {
    // Jika berhasil, kembali ke halaman tampil kelas dengan status sukses
    header("Location: kelas_tampil.php?status=hapus_sukses");
} else {
    // Jika gagal (misal karena id_kelas masih dipakai di tb_siswa / foreign key restrict)
    header("Location: kelas_tampil.php?status=hapus_gagal");
}
exit;
?>