<?php
// 1. Memulai server session data
session_start();

// 2. Menghubungkan ke konfigurasi database
include '../config/koneksi.php';

// Proteksi halaman: pastikan hanya admin yang bisa mengeksekusi operasi data master
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// Mengambil id_kelas dari parameter URL (metode GET)
$id_kelas = mysqli_real_escape_string($koneksi, $_GET['id']);

// BUNGKUS DENGAN TRY-CATCH: Menjinakkan Fatal Error Foreign Key Restrict
try {
    // Eksekusi kueri hapus data master kelas
    $query_hapus = "DELETE FROM tb_kelas WHERE id_kelas = '$id_kelas'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        // Jika records berhasil dihapus (tidak terikat data siswa mana pun)
        header("Location: kelas_tampil.php?status=hapus_sukses");
        exit;
    } else {
        // Jika gagal karena problem kueri lain
        header("Location: kelas_tampil.php?status=hapus_gagal");
        exit;
    }
} catch (mysqli_sql_exception $e) {
    // JIKA MENABRAK ATURAN FOREIGN KEY: Tangkap errornya di sini dan lempar parameter gagal
    header("Location: kelas_tampil.php?status=hapus_gagal");
    exit;
}
?>