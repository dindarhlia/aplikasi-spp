<?php 
// 1. Memanggil header (otomatis cek session login)
include '../layouts/header.php'; 

// 2. Menghubungkan ke database
include '../config/koneksi.php';

$pesan_sukses = "";
$pesan_gagal  = "";

// 3. Memproses data ketika transaksi disubmit
if (isset($_POST['tombol_bayar'])) {
    $id_pembayaran      = "TR" . date("ymd") . rand(100, 999); 
    $nisn               = mysqli_real_escape_string($koneksi, $_POST['input_nisn']);
    
    // Simpan format tanggal murni YYYY-MM-DD sesuai tipe data DATE di database Anda
    $tgl_bayar          = date("Y-m-d"); 
    
    $jumlah_bulan       = mysqli_real_escape_string($koneksi, $_POST['input_bulan']);
    $jumlah_bayar       = (int)$_POST['input_jumlah_bayar'];

    // Cari data tarif SPP siswa berdasarkan NISN yang diinput
    $cari_siswa = mysqli_query($koneksi, "SELECT id_spp FROM tb_siswa WHERE nisn='$nisn'");
    
    if (mysqli_num_rows($cari_siswa) > 0) {
        $data_siswa = mysqli_fetch_assoc($cari_siswa);
        $id_spp     = $data_siswa['id_spp'];

        // Ambil nominal SPP bulanan dari tb_spp
        $cari_spp   = mysqli_query($koneksi, "SELECT nominal FROM tb_spp WHERE id_spp='$id_spp'");
        $data_spp   = mysqli_fetch_assoc($cari_spp);
        $nominal_spp = (int)$data_spp['nominal'];

        // Menghitung total biaya (Tarif SPP x Jumlah Bulan yang dipilih)
        $total_tagihan = $nominal_spp * (int)$jumlah_bulan;

        if ($jumlah_bayar < $total_tagihan) {
            $pesan_gagal = "Gagal! Uang kurang. Total tagihan adalah Rp " . number_format($total_tagihan, 0, ',', '.');
        } else {
            $kembalian = $jumlah_bayar - $total_tagihan;
            $status    = "Sudah Lunas";

            // Query Insert murni menyesuaikan database asli Anda
            $query_pembayaran = "INSERT INTO tb_pembayaran 
                                (id_pembayaran, status, nisn, tgl_bayar, tgl_terakhir_bayar, batas_pembayaran, jumlah_bulan, id_spp, nominal_bayar, jumlah_bayar, kembalian) 
                                VALUES 
                                ('$id_pembayaran', '$status', '$nisn', '$tgl_bayar', '$tgl_bayar', '$tgl_bayar', '$jumlah_bulan', '$id_spp', '$total_tagihan', '$jumlah_bayar', '$kembalian')";

            if (mysqli_query($koneksi, $query_pembayaran)) {
                $pesan_sukses = "Transaksi Berhasil! ID: <strong>$id_pembayaran</strong>. Kembalian: Rp " . number_format($kembalian, 0, ',', '.');
            } else {
                $pesan_gagal = "Gagal menyimpan transaksi: " . mysqli_error($koneksi);
            }
        }
    } else {
        $pesan_gagal = "Gagal! Data siswa dengan NISN tersebut tidak ditemukan.";
    }
}

// 4. Mengambil 5 histori transaksi terakhir untuk ditampilkan di tabel sebelah kanan
$query_histori = mysqli_query($koneksi, "SELECT tb_pembayaran.*, tb_siswa.nama 
                                         FROM tb_pembayaran 
                                         INNER JOIN tb_siswa ON tb_pembayaran.nisn = tb_siswa.nisn 
                                         ORDER BY tb_pembayaran.tgl_bayar DESC, tb_pembayaran.id_pembayaran DESC LIMIT 5");
?>

<div class="row">
    <div class="col-md-5 mb-4">
        <div class="card shadow-sm border-dark rounded-0">
            <div class="card-header bg-success text-white fw-bold rounded-0">
                Entri Pembayaran SPP Baru
            </div>
            <div class="card-body bg-white">

                <?php if ($pesan_sukses != "") : ?>
                    <div class="alert alert-success" role="alert"><?= $pesan_sukses; ?></div>
                <?php endif; ?>

                <?php if ($pesan_gagal != "") : ?>
                    <div class="alert alert-danger" role="alert"><?= $pesan_gagal; ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Masukkan NISN Siswa</label>
                        <input type="text" name="input_nisn" class="form-control border-dark" placeholder="Contoh: 0061234561" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Bulan Yang Dibayar</label>
                        <select name="input_bulan" class="form-select border-dark" required>
                            <option value="1">1 Bulan</option>
                            <option value="2">2 Bulan</option>
                            <option value="3">3 Bulan</option>
                            <option value="6">6 Bulan (1 Semester)</option>
                            <option value="12">12 Bulan (1 Tahun)</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Jumlah Uang Yang Dibayarkan (Rp)</label>
                        <input type="number" name="input_jumlah_bayar" class="form-control border-dark" placeholder="Contoh: 400000" required>
                    </div>

                    <button type="submit" name="tombol_bayar" class="btn btn-success w-100 mt-2 fw-bold">Proses Bayar Sekarang</button>
                </form>

            </div>
        </div>
    </div>

    <div class="col-md-7 mb-4">
        <div class="card shadow-sm border-dark rounded-0">
            <div class="card-header bg-dark text-white fw-bold rounded-0">
                5 Transaksi Pembayaran Terakhir
            </div>
            <div class="card-body p-0 bg-white">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover mb-0 align-middle" style="font-size: 13px;">
                        <thead class="table-secondary text-center text-uppercase">
                            <tr>
                                <th>ID Pay</th>
                                <th>Nama Siswa</th>
                                <th>Bulan Dibayar</th>
                                <th>Tanggal Bayar</th>
                                <th width="15%">Total Tagihan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (mysqli_num_rows($query_histori) == 0) : ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Belum ada riwayat transaksi.</td>
                                </tr>
                            <?php else : ?>
                                <?php while ($histori = mysqli_fetch_assoc($query_histori)) : 
                                    // Membuat teks bulan dinamis dari kolom tgl_bayar database
                                    $array_bulan = array(
                                        '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                        '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                        '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                                    );
                                    $angka_bulan = date('m', strtotime($histori['tgl_bayar']));
                                    $nama_bulan_indo = $array_bulan[$angka_bulan];
                                ?>
                                <tr>
                                    <td class="text-center"><small class="fw-bold text-primary"><?= $histori['id_pembayaran']; ?></small></td>
                                    <td><strong><?= $histori['nama']; ?></strong></td>
                                    <td class="text-center fw-semibold text-secondary">
                                        <?= $nama_bulan_indo; ?> (<?= $histori['jumlah_bulan']; ?> Bln)
                                    </td>
                                    
                                    <td class="text-center">
                                        <?= date("d/m/Y", strtotime($histori['tgl_bayar'])); ?>
                                    </td>
                                    
                                    <td class="text-end fw-bold text-success">Rp <?= number_format($histori['nominal_bayar'], 0, ',', '.'); ?></td>
                                    <td class="text-center"><span class="badge bg-success"><?= $histori['status']; ?></span></td>
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

<?php 
// Memanggil footer
include '../layouts/footer.php'; 
?>