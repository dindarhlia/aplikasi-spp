<?php
session_start();
include '../config/koneksi.php';

// Proteksi akses login
if (!isset($_SESSION['username'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Menangkap ID Pembayaran dari parameter URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID Transaksi tidak ditemukan.");
}

$id_pembayaran = mysqli_real_escape_string($koneksi, $_GET['id']);

// Query mengambil data transaksi spesifik diJOIN dengan data siswa dan tarif SPP
$query = mysqli_query($koneksi, "SELECT tb_pembayaran.*, tb_siswa.nama, tb_siswa.nis, tb_siswa.nama_kelas, tb_spp.tahun 
                                 FROM tb_pembayaran
                                 INNER JOIN tb_siswa ON tb_pembayaran.nisn = tb_siswa.nisn
                                 INNER JOIN tb_spp ON tb_pembayaran.id_spp = tb_spp.id_spp
                                 WHERE tb_pembayaran.id_pembayaran = '$id_pembayaran'");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Detail transaksi tidak ditemukan di sistem database.");
}

// Konversi bulan otomatis via PHP
$array_bulan = array(
    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
);
$angka_bulan = date('m', strtotime($data['tgl_bayar']));
$nama_bulan_indo = $array_bulan[$angka_bulan];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kuitansi SPP - #<?= $data['id_pembayaran']; ?></title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            color: #000;
            background-color: #fff;
            padding: 20px;
            font-size: 14px;
        }
        .nota-box {
            max-width: 600px;
            margin: 0 auto;
            border: 1px dashed #000;
            padding: 20px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .header-title { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .divider { border-top: 1px dashed #000; margin: 15px 0; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 4px 0; vertical-align: top; }
        .total-row { font-weight: bold; font-size: 15px; }
        
        /* CSS Khusus Trigger Printer Otomatis */
        @media print {
            body { padding: 0; }
            .nota-box { border: none; max-width: 100%; }
            .btn-kembali { display: none; }
        }
    </style>
</head>
<body>

<div class="nota-box">
    <div class="text-center">
        <div class="header-title">SISTEM INFORMASI PEMBAYARAN SPP</div>
        <div>KUITANSI RESMI BUKTI TRANSAKSI MASUK</div>
        <small>Tangerang Selatan, Banten</small>
    </div>

    <div class="divider"></div>

    <table>
        <tr>
            <td width="35%"><strong>ID Pembayaran</strong></td>
            <td>: #<?= $data['id_pembayaran']; ?></td>
        </tr>
        <tr>
            <td><strong>Tanggal Bayar</strong></td>
            <td>: <?= date('d/m/Y', strtotime($data['tgl_bayar'])); ?></td>
        </tr>
        <tr>
            <td><strong>NISN / NIS</strong></td>
            <td>: <?= $data['nisn']; ?> / <?= $data['nis']; ?></td>
        </tr>
        <tr>
            <td><strong>Nama Siswa</strong></td>
            <td>: <?= $data['nama']; ?></td>
        </tr>
        <tr>
            <td><strong>Kelas</strong></td>
            <td>: <?= $data['nama_kelas']; ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <table>
        <thead>
            <tr>
                <th align="left">Deskripsi Iuran</th>
                <th align="center">Durasi</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>SPP Bulanan Angkatan Tahun <?= $data['tahun']; ?> (Alokasi: <?= $nama_bulan_indo; ?>)</td>
                <td align="center"><?= $data['jumlah_bulan']; ?> Bln</td>
                <td align="right">Rp <?= number_format($data['nominal_bayar'], 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan="3"><div class="divider"></div></td>
            </tr>
            <tr class="total-row">
                <td colspan="2" align="right">GRAND TOTAL :</td>
                <td align="right">Rp <?= number_format($data['nominal_bayar'], 0, ',', '.'); ?></td>
            </tr>
            <tr>
                <td colspan="2" align="right" style="color: #555; font-weight: normal;">Jumlah Uang Fisik :</td>
                <td align="right" style="color: #555;">Rp <?= number_format($data['jumlah_bayar'], 0, ',', '.'); ?></td>
            </tr>
            <tr class="total-row" style="color: green;">
                <td colspan="2" align="right">UANG KEMBALIAN :</td>
                <td align="right">Rp <?= number_format($data['kembalian'], 0, ',', '.'); ?></td>
            </tr>
        </tbody>
    </table>

    <div class="divider"></div>
    
    <table>
        <tr>
            <td width="50%"></td>
            <td align="center">
                <small>Petugas Administrasi,</small>
                <br><br><br><br>
                <strong><?= $_SESSION['nama_petugas']; ?></strong>
            </td>
        </tr>
    </table>
    
    <div class="text-center" style="margin-top: 25px;">
        <button class="btn-kembali" onclick="window.close();" style="padding: 5px 15px; cursor: pointer;">Tutup Halaman</button>
    </div>
</div>

<script>
    window.print();
</script>

</body>
</html>