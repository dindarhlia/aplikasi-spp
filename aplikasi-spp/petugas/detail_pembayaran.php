<?php 
// 1. Memanggil header sidebar kiri
include '../layouts/header.php'; 

// 2. Menghubungkan ke konfigurasi database
include '../config/koneksi.php';

// Menangkap keyword pencarian jika petugas ingin memfilter laporan
$keyword = "";
if (isset($_POST['tombol_cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['input_cari']);
}

// 3. Query SQL untuk mengambil seluruh riwayat transaksi pembayaran SPP
$query_sandi = "SELECT tb_pembayaran.*, tb_siswa.nama, tb_kelas.nama_kelas, tb_spp.nominal AS tarif_spp
                FROM tb_pembayaran
                INNER JOIN tb_siswa ON tb_pembayaran.nisn = tb_siswa.nisn
                INNER JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas
                INNER JOIN tb_spp ON tb_pembayaran.id_spp = tb_spp.id_spp";

// Jika ada pencarian, filter berdasarkan nama siswa atau NISN (Aman Bebas dari Crash Column)
if ($keyword != "") {
    $query_sandi .= " WHERE tb_siswa.nama LIKE '%$keyword%' 
                      OR tb_pembayaran.nisn LIKE '%$keyword%'";
}

// Urutkan berdasarkan tanggal pembayaran terbaru
$query_sandi .= " ORDER BY tb_pembayaran.tgl_bayar DESC";
$eksekusi = mysqli_query($koneksi, $query_sandi);
?>

<div class="row">
    <div class="col-md-12">
        
        <div class="card card-body bg-white mb-4 shadow-sm py-2">
            <h5 class="text-dark fw-bold mb-0">Detail Laporan Riwayat Pembayaran</h5>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <p class="text-muted small mb-0">Menampilkan seluruh log transaksi pembayaran SPP masuk.</p>
            </div>
            
            <form action="" method="POST" class="d-flex gap-1" style="max-width: 350px;">
                <input type="text" name="input_cari" class="form-control form-control-sm" placeholder="Cari Nama atau NISN..." value="<?= $keyword; ?>">
                <button type="submit" name="tombol_cari" class="btn btn-secondary btn-sm px-3">Cari</button>
                <?php if ($keyword != "") : ?>
                    <a href="detail_pembayaran.php" class="btn btn-outline-danger btn-sm">Reset</a>
                <?php endif; ?>
            </form>
        </div>

        <div class="card shadow-sm border-dark rounded-0 mb-5">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered mb-0 align-middle" style="font-size: 13px;">
                        <thead class="table-light text-uppercase text-center">
                            <tr>
                                <th width="5%">No</th>
                                <th width="12%">ID Bayar</th>
                                <th width="12%">NISN</th>
                                <th>Nama Siswa</th>
                                <th width="10%">Kelas</th>
                                <th width="15%">Bulan Dibayar</th>
                                <th width="10%">Jml Bulan</th>
                                <th>Total Bayar</th>
                                <th width="15%">Tgl Transaksi</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            if (mysqli_num_rows($eksekusi) == 0) : 
                            ?>
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-4">Belum ada riwayat transaksi pembayaran yang tercatat.</td>
                                </tr>
                            <?php else : ?>
                                <?php while ($data = mysqli_fetch_assoc($eksekusi)) : 
                                    // Menghitung total bayar: nominal uang yang diserahkan dikurangi kembalian
                                    $total_bayar = (int)$data['jumlah_bayar'] - (int)$data['kembalian'];

                                    // Mengubah angka tanggal bayar menjadi Nama Bulan Bahasa Indonesia secara otomatis via PHP
                                    $array_bulan = array(
                                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                    );
                                    $angka_bulan = date('m', strtotime($data['tgl_bayar']));
                                    $nama_bulan_indo = $array_bulan[$angka_bulan];
                                ?>
                                <tr>
                                    <td class="text-center"><?= $no++; ?></td>
                                    <td class="text-center"><span class="badge bg-dark">#<?= $data['id_pembayaran']; ?></span></td>
                                    <td class="text-center fw-bold"><?= $data['nisn']; ?></td>
                                    <td><strong><?= $data['nama']; ?></strong></td>
                                    <td class="text-center"><?= $data['nama_kelas']; ?></td>
                                    
                                    <td class="text-center fw-semibold text-secondary">
                                        <?= $nama_bulan_indo; ?> (<?= date('Y', strtotime($data['tgl_bayar'])); ?>)
                                    </td>
                                    
                                    <td class="text-center fw-bold text-primary"><?= $data['jumlah_bulan']; ?> Bulan</td>
                                    <td class="text-end fw-bold text-success">Rp <?= number_format($total_bayar, 0, ',', '.'); ?></td>
                                    <td class="text-center"><?= date('d-m-Y H:i', strtotime($data['tgl_bayar'])); ?></td>
                                    
                                    <td class="text-center">
                                        <button onclick="cetakNota('<?= $data['id_pembayaran']; ?>')" class="btn btn-outline-primary btn-sm py-0 px-2" style="font-size: 11px;">
                                            🖨️ Cetak
                                        </button>
                                    </td>
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

<script>
function cetakNota(idPembayaran) {
    // Membuka berkas nota_cetak.php pada tab baru browser dengan membawa parameter ID transaksi yang dipilih
    window.open("nota_cetak.php?id=" + idPembayaran, "_blank");
}
</script>

<?php 
// 4. Memanggil komponen footer untuk menutup tag HTML
include '../layouts/footer.php'; 
?>