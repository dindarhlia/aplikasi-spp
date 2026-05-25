<?php 
include '../layouts/header.php'; 
include '../config/koneksi.php';

$pesan = "";

// 1. Ambil data lama berdasarkan ID yang mau diedit
$id_kelas = mysqli_real_escape_string($koneksi, $_GET['id']);
$query_ambil = mysqli_query($koneksi, "SELECT * FROM tb_kelas WHERE id_kelas = '$id_kelas'");
$data_lama = mysqli_fetch_assoc($query_ambil);

// 2. Proses update data ketika tombol Simpan Perubahan ditekan
if (isset($_POST['tombol_update'])) {
    $nama_kelas   = mysqli_real_escape_string($koneksi, $_POST['input_nama_kelas']);
    $komp_keahlian = mysqli_real_escape_string($koneksi, $_POST['input_jurusan']);

    $query_update = "UPDATE tb_kelas SET nama_kelas='$nama_kelas', komp_keahlian='$komp_keahlian' WHERE id_kelas='$id_kelas'";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data kelas berhasil diubah!'); window.location='kelas_tampil.php';</script>";
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengubah data: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h6 class="mb-0">Form Edit Data Kelas</h6>
            </div>
            <div class="card-body">
                <?= $pesan; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label">ID Kelas (Tidak Dapat Diubah)</label>
                        <input type="text" class="form-control bg-light" value="<?= $data_lama['id_kelas']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kelas</label>
                        <input type="text" name="input_nama_kelas" class="form-control" value="<?= $data_lama['nama_kelas']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Kompetensi Keahlian (Jurusan)</label>
                        <input type="text" name="input_jurusan" class="form-control" value="<?= $data_lama['komp_keahlian']; ?>" required>
                    </div>
                    <div class="d-flex justify-content-end gap-2">
                        <a href="kelas_tampil.php" class="btn btn-secondary">Batal</a>
                        <button type="submit" name="tombol_update" class="btn btn-warning text-white">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>