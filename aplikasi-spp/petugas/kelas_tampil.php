<?php 
// 1. Memanggil header (otomatis cek session login dan proteksi halaman)
include '../layouts/header.php'; 

// 2. Menghubungkan ke konfigurasi database
include '../config/koneksi.php';

// 3. Mengambil seluruh data dari tb_kelas untuk ditampilkan di tabel
$query = "SELECT * FROM tb_kelas";
$eksekusi = mysqli_query($koneksi, $query);
?>

<div class="row">
    <div class="col-md-12">
        
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-dark fw-bold">Data Master Kelas</h4>
            <a href="kelas_tambah.php" class="btn btn-primary btn-sm">+ Tambah Kelas</a>
        </div>

        <?php if (isset($_GET['status'])) : ?>
            <?php if ($_GET['status'] == 'hapus_sukses') : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    Data kelas berhasil dihapus dari sistem!
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php elseif ($_GET['status'] == 'hapus_gagal') : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    Gagal menghapus! Data kelas ini masih digunakan oleh data siswa di tabel master siswa.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped mb-0 align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th width="5%" class="text-center">No</th>
                                <th width="15%">ID Kelas</th>
                                <th width="20%">Nama Kelas</th>
                                <th>Kompetensi Keahlian (Jurusan)</th>
                                <th width="20%" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            // Melakukan perulangan untuk membaca array data dari database
                            while ($data = mysqli_fetch_assoc($eksekusi)) : 
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++; ?></td>
                                
                                <td><span class="badge bg-secondary"><?= $data['id_kelas']; ?></span></td>
                                
                                <td><strong><?= $data['nama_kelas']; ?></strong></td>
                                
                                <td><?= $data['komp_keahlian']; ?></td>
                                
                                <td class="text-center">
                                    <a href="kelas_edit.php?id=<?= $data['id_kelas']; ?>" class="btn btn-warning btn-sm text-white" style="font-size: 12px;">Edit</a>
                                    
                                    <a href="kelas_hapus.php?id=<?= $data['id_kelas']; ?>" class="btn btn-danger btn-sm" style="font-size: 12px;" onclick="return confirm('Yakin ingin menghapus kelas <?= $data['nama_kelas']; ?>? Tindakan ini tidak dapat dibatalkan.')">Hapus</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<?php 
// Memanggil footer untuk menutup tag container dan load Javascript Bootstrap
include '../layouts/footer.php'; 
?>