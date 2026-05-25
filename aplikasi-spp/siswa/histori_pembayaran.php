<?php
// 1. Memulai session untuk membaca data login siswa
session_start();

// 2. Menghubungkan ke database
include '../config/koneksi.php';

// Proteksi Halaman: Cek apakah yang login benar-benar level siswa
if (!isset($_SESSION['username']) || $_SESSION['level'] !== 'siswa') {
    // Jika belum login atau bukan siswa, tendang balik ke halaman login petugas/auth
    header("Location: ../auth/login.php");
    exit;
}

// 3. Mengambil NISN siswa berdasarkan Nama Petugas/Siswa yang aktif di session login
// Pada data master uji coba kita, nama petugas disamakan dengan nama siswa untuk sinkronisasi
$nama_siswa_login = $_SESSION['nama_petugas'];

$cari_siswa = mysqli_query($koneksi, "SELECT nisn, nis, nama, nama_kelas FROM tb_siswa WHERE nama = '$nama_siswa_login'");
$data_siswa = mysqli_fetch_assoc($cari_siswa);

// Jika data siswa ditemukan di database
if ($data_siswa) {
    $nisn_siswa = $data_siswa['nisn'];

    // 4. Query SQL untuk mengambil seluruh riwayat pembayaran milik siswa yang sedang login ini
    $query_transaksi = mysqli_query($koneksi, "SELECT * FROM tb_pembayaran WHERE nisn = '$nisn_siswa' ORDER BY tgl_bayar DESC");
} else {
    die("Data profil siswa tidak ditemukan di sistem database.");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histori Pembayaran Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4 shadow-sm">
  <div class="container">
    <a class="navbar-brand fw-bold" href="#">Portal Siswa - Pembayaran SPP</a>
    <div class="d-flex align-items-center text-white">
        <span class="me-3">Selamat Datang, <strong><?= $data_siswa['nama']; ?></strong></span>
        <a href="../auth/logout.php" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin keluar dari portal siswa?')">Logout</a>
    </div>
  </div>
</nav>

<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-body bg-white rounded">
                    <h5 class="card-title text-primary fw-bold mb-3">Biodata Anda</h5>
                    <div class="row">
                        <div class="col-md-3"><strong>Nama Lengkap</strong></div>
                        <div class="col-md-9 text-muted">: <?= $data_siswa['nama']; ?></div>
                        
                        <div class="col-md-3"><strong>NISN / NIS</strong></div>
                        <div class="col-md-9 text-muted">: <?= $data_siswa['nisn']; ?> / <?= $data_siswa['nis']; ?></div>
                        
                        <div class="col-md-3"><strong>Kelas</strong></div>
                        <div class="col-md-9 text-muted">: <?= $data_siswa['nama_kelas']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white py-3">
                    <h6 class="mb-0 fw-bold">Riwayat Transaksi Pembayaran SPP Anda</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0 align-middle">
                            <thead class="table-secondary">
                                <tr>
                                    <th width="5%" class="text-center">No</th>
                                    <th>ID Pembayaran</th>
                                    <th>Tanggal Bayar</th>
                                    <th class="text-center">Jumlah Bulan</th>
                                    <th>Total Uang Masuk</th>
                                    <th>Status Kelulusan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                // Cek apakah siswa ini sudah pernah bayar atau belum sama sekali
                                if (mysqli_num_rows($query_transaksi) == 0) : 
                                ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Anda belum memiliki riwayat transaksi pembayaran SPP.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php while ($transaksi = mysqli_fetch_assoc($query_transaksi)) : ?>
                                    <tr>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td><span class="fw-bold text-primary"><?= $transaksi['id_pembayaran']; ?></span></td>
                                        <td><?= date("d F Y", strtotime($transaksi['tgl_bayar'])); ?></td>
                                        <td class="text-center"><?= $transaksi['jumlah_bulan']; ?> Bulan</td>
                                        <td>Rp <?= number_format($transaksi['nominal_bayar'], 0, ',', '.'); ?></td>
                                        <td><span class="badge bg-success"><?= $transaksi['status']; ?></span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <footer class="text-center text-muted py-4 mt-5 border-top" style="font-size: 12px;">
        Portal Informasi Keuangan Sekolah &copy; 2026 - Ujikom Teknik Informatika
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>