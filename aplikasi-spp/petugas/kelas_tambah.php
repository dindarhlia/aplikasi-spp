<?php 
include '../layouts/header.php'; 
include '../config/koneksi.php';

$pesan = "";

if (isset($_POST['tombol_simpan'])) {
    $id_kelas      = mysqli_real_escape_string($koneksi, $_POST['input_id']);
    $nama_kelas    = mysqli_real_escape_string($koneksi, $_POST['input_nama_kelas']);
    $komp_keahlian = mysqli_real_escape_string($koneksi, $_POST['input_jurusan']);

    // Cek apakah ID Kelas sudah terdaftar
    $cek_dulu = mysqli_query($koneksi, "SELECT id_kelas FROM tb_kelas WHERE id_kelas='$id_kelas'");
    if (mysqli_num_rows($cek_dulu) > 0) {
        $pesan = "<div class='alert alert-danger'>Gagal! ID Kelas sudah digunakan.</div>";
    } else {
        $query_insert = "INSERT INTO tb_kelas (id_kelas, nama_kelas, komp_keahlian) VALUES ('$id_kelas', '$nama_kelas', '$komp_keahlian')";
        if (mysqli_query($koneksi, $query_insert)) {
            echo "<script>alert('Data kelas baru berhasil disimpan!'); window.location='kelas_tampil.php';</script>";
            exit;
        } else {
            $pesan = "<div class='alert alert-danger'>Gagal menyimpan data: " . mysqli_error($koneksi) . "</div>";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-body bg-white mb-3 shadow-sm py-2">
            <h5 class="text-dark fw-bold mb-0">Tambah Data Master Kelas</h5>
        </div>

        <div class="card shadow-sm border-dark rounded-0">
            <div class="card-header bg-primary text-white fw-bold rounded-0">FORM INPUT KELAS BARU</div>
            <div class="card-body bg-white p-4">
                <?= $pesan; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID Kelas</label>
                        <input type="text" name="input_id" class="form-control border-dark" placeholder="Contoh: KLS006" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Tingkatan Kelas</label>
                        <input type="text" name="input_nama_kelas" class="form-control border-dark" placeholder="Contoh: XII-RPL" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Kompetensi Keahlian (Jurusan)</label>
                        <input type="text" name="input_jurusan" class="form-control border-dark" placeholder="Contoh: Rekayasa Perangkat Lunak" required>
                    </div>
                    <hr class="border-dark">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="kelas_tampil.php" class="btn btn-secondary px-4">BATAL</a>
                        <button type="submit" name="tombol_simpan" class="btn btn-primary px-4 fw-bold">SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>