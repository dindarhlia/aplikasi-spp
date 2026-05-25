<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Proteksi halaman: jika tidak ada session username, tendang ke login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Pembayaran SPP Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Desain Sidebar Kiri Sesuai Gambar Soal */
        .sidebar {
            min-height: 100vh;
            background-color: #7a828a; /* Warna abu-abu medium sesuai lembar ujian */
        }
        .sidebar .menu-title {
            background-color: #5c636a;
            color: #ffffff;
            font-weight: bold;
            padding: 15px 20px;
            font-size: 14px;
        }
        .sidebar .nav-link {
            color: #ffffff;
            padding: 12px 20px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            font-size: 14px;
            border-radius: 0;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: #ffffff;
            color: #333333;
            font-weight: bold;
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <div class="col-md-3 col-lg-2 px-0 sidebar shadow-sm">
            <div class="menu-title border-bottom">
                👥 Menu Admin
            </div>
            <div class="nav flex-column nav-pills">
                <a href="dashboard.php" class="nav-link">Dashboard</a>
                <a href="kelas_tampil.php" class="nav-link">Data Kelas</a>
                <a href="siswa_tampil.php" class="nav-link">Data Siswa</a>
                <a href="cek_pembayaran.php" class="nav-link">Cek Pembayaran</a>
                <a href="pembayaran.php" class="nav-link">Pembayaran</a>
                <a href="detail_pembayaran.php" class="nav-link">Detail Pembayaran</a>
                <a href="petugas_tampil.php" class="nav-link">Data Petugas</a>
                <a href="../auth/logout.php" class="nav-link text-warning mt-3" onclick="return confirm('Yakin ingin keluar dari aplikasi?')">🚪 Keluar</a>
            </div>
        </div>

        <div class="col-md-9 col-lg-10 p-4 main-content">
            <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                <h6 class="text-secondary mb-0">Selamat Datang, <strong class="text-dark"><?= $_SESSION['nama_petugas']; ?></strong></h6>
                <span class="badge bg-secondary">Akses: <?= ucfirst($_SESSION['level']); ?></span>
            </div>