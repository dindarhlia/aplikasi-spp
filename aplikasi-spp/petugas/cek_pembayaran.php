<?php 
// 1. Memanggil header sidebar kiri
include '../layouts/header.php'; 

// 2. Menghubungkan ke database
include '../config/koneksi.php';

$keyword_nisn = "";
$query_lunas  = null;

// Jika tombol "CEK PEMBAYARAN" ditekan
if (isset($_POST['tombol_cek'])) {
    $keyword_nisn = mysqli_real_escape_string($koneksi, $_POST['input_nisn']);
    
    // Mengambil data transaksi lengkap termasuk kolom jumlah_bulan
    $query_lunas = mysqli_query($koneksi, "SELECT tb_pembayaran.*, tb_siswa.nama, tb_kelas.nama_kelas 
                                           FROM tb_pembayaran 
                                           INNER JOIN tb_siswa ON tb_pembayaran.nisn = tb_siswa.nisn
                                           INNER JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas
                                           WHERE tb_pembayaran.nisn = '$keyword_nisn' AND tb_pembayaran.status = 'Sudah Lunas'");
}

// Ambil data untuk tabel SUDAH LUNAS dengan SUM(jumlah_bulan) agar total bulannya terakumulasi jika bayar berkali-kali
$all_lunas = mysqli_query($koneksi, "SELECT tb_pembayaran.nisn, SUM(tb_pembayaran.jumlah_bulan) AS total_bulan, tb_siswa.nama, tb_kelas.nama_kelas 
                                     FROM tb_pembayaran 
                                     INNER JOIN tb_siswa ON tb_pembayaran.nisn = tb_siswa.nisn
                                     INNER JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas
                                     WHERE tb_pembayaran.status = 'Sudah Lunas' 
                                     GROUP BY tb_pembayaran.nisn");

// Ambil data untuk tabel BELUM LUNAS
$all_belum = mysqli_query($koneksi, "SELECT tb_siswa.*, tb_kelas.nama_kelas 
                                     FROM tb_siswa 
                                     INNER JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas
                                     WHERE tb_siswa.nisn NOT IN (SELECT nisn FROM tb_pembayaran WHERE status='Sudah Lunas')");
?>

<div class="row">
    <div class="col-md-12">
        
        <div class="card card-body bg-white mb-4 shadow-sm py-2">
            <h5 class="text-dark fw-bold mb-0">Cek Pembayaran</h5>
        </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow-sm border-dark rounded-0">
                    <div class="card-body bg-light">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Cari NISN dengan memasukkan Keyword</label>
                                <div class="input-group">
                                    <input type="text" name="input_nisn" class="form-control border-dark" placeholder="Masukkan NISN..." value="<?= $keyword_nisn; ?>" required>
                                    <button type="submit" name="tombol_cek" class="btn btn-primary fw-bold" style="font-size: 12px;">CEK PEMBAYARAN</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm border-dark rounded-0 h-100">
                    <div class="card-header bg-secondary text-white fw-bold rounded-0 py-1" style="font-size: 14px;">
                        Data Hasil Pencarian
                    </div>
                    <div class="card-body bg-white p-2">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered mb-0 align-middle" style="font-size: 12px;">
                                <thead class="table-light text-center">
                                    <tr>
                                        <th>ID Pay</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Bulan Dibayar</th>
                                        <th>Tgl Bayar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($query_lunas === null) : ?>
                                        <tr><td colspan="6" class="text-center text-muted py-3">Masukkan NISN di sebelah kiri untuk melihat hasil tracking.</td></tr>
                                    <?php elseif (mysqli_num_rows($query_lunas) == 0) : ?>
                                        <tr><td colspan="6" class="text-center text-danger py-3">Siswa dengan NISN ini belum memiliki riwayat lunas.</td></tr>
                                    <?php else : ?>
                                        <?php while ($row = mysqli_fetch_assoc($query_lunas)) : ?>
                                        <tr>
                                            <td class="text-center"><?= $row['id_pembayaran']; ?></td>
                                            <td><strong><?= $row['nama']; ?></strong></td>
                                            <td class="text-center"><?= $row['nama_kelas']; ?></td>
                                            <td class="text-center fw-bold text-primary"><?= $row['jumlah_bulan']; ?> Bulan</td>
                                            <td class="text-center"><?= date('d/m/Y', strtotime($row['tgl_bayar'])); ?></td>
                                            <td class="text-center"><span class="badge bg-success">Lunas</span></td>
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

        <div class="row">
            
            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-dark rounded-0">
                    <div class="card-header bg-success text-white fw-bold rounded-0 py-2 text-center" style="font-size: 14px;">
                        🟢 Siswa Yang Sudah Lunas
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-bordered mb-0 align-middle" style="font-size: 12px;">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jml Bulan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($all_lunas) == 0) : ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">Belum ada siswa yang lunas.</td></tr>
                                <?php else : ?>
                                    <?php while($l = mysqli_fetch_assoc($all_lunas)): ?>
                                    <tr>
                                        <td class="text-center"><?= $l['nisn']; ?></td>
                                        <td><strong><?= $l['nama']; ?></strong></td>
                                        <td class="text-center"><?= $l['nama_kelas']; ?></td>
                                        <td class="text-center fw-bold text-success"><?= $l['total_bulan']; ?> Bulan</td>
                                        <td class="text-center"><span class="badge bg-success">Sudah Lunas</span></td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm border-dark rounded-0">
                    <div class="card-header bg-danger text-white fw-bold rounded-0 py-2 text-center" style="font-size: 14px;">
                        🔴 Siswa Yang Belum Lunas
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-striped table-bordered mb-0 align-middle" style="font-size: 12px;">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jml Bulan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($all_belum) == 0) : ?>
                                    <tr><td colspan="5" class="text-center text-muted py-3">Semua siswa sudah lunas.</td></tr>
                                <?php else : ?>
                                    <?php while($b = mysqli_fetch_assoc($all_belum)): ?>
                                    <tr>
                                        <td class="text-center"><?= $b['nisn']; ?></td>
                                        <td><strong><?= $b['nama']; ?></strong></td>
                                        <td class="text-center"><?= $b['nama_kelas']; ?></td>
                                        <td class="text-center text-muted">0 Bulan</td>
                                        <td class="text-center"><span class="badge bg-danger">Belum Lunas</span></td>
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
</div>

<?php 
include '../layouts/footer.php'; 
?>