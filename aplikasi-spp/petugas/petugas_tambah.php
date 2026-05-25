<?php 
include '../layouts/header.php'; 
include '../config/koneksi.php';

$pesan = "";

if (isset($_POST['tombol_simpan'])) {
    $id_petugas   = mysqli_real_escape_string($koneksi, $_POST['input_id']);
    $username     = mysqli_real_escape_string($koneksi, $_POST['input_user']);
    $password     = mysqli_real_escape_string($koneksi, $_POST['input_pass']);
    $nama_petugas = mysqli_real_escape_string($koneksi, $_POST['input_nama']);
    $level        = mysqli_real_escape_string($koneksi, $_POST['input_level']);

    // Enkripsi password menggunakan MD5 sesuai standar database db_spp
    $password_md5 = md5($password);

    // Cek apakah ID atau Username sudah terdaftar
    $cek_dulu = mysqli_query($koneksi, "SELECT * FROM tb_petugas WHERE id_petugas='$id_petugas' OR username='$username'");
    if (mysqli_num_rows($cek_dulu) > 0) {
        $pesan = "<div class='alert alert-danger'>Gagal! ID Petugas atau Username sudah digunakan.</div>";
    } else {
        $query_insert = "INSERT INTO tb_petugas (id_petugas, username, password, nama_petugas, level) 
                         VALUES ('$id_petugas', '$username', '$password_md5', '$nama_petugas', '$level')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            echo "<script>alert('Data petugas berhasil ditambahkan!'); window.location='petugas_tampil.php';</script>";
            exit;
        } else {
            $pesan = "<div class='alert alert-danger'>Gagal menyimpan: " . mysqli_error($koneksi) . "</div>";
        }
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-body bg-white mb-3 shadow-sm py-2">
            <h5 class="text-dark fw-bold mb-0">Tambah Data Petugas</h5>
        </div>

        <div class="card shadow-sm border-dark rounded-0">
            <div class="card-header bg-primary text-white fw-bold rounded-0">FORM TAMBAH PETUGAS</div>
            <div class="card-body bg-white p-4">
                <?= $pesan; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID Petugas</label>
                        <input type="text" name="input_id" class="form-control border-dark" placeholder="Contoh: PTG006" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="input_user" class="form-control border-dark" placeholder="Masukkan username login..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="input_pass" class="form-control border-dark" placeholder="Masukkan password..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap Petugas</label>
                        <input type="text" name="input_nama" class="form-control border-dark" placeholder="Masukkan nama lengkap..." required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Level Hak Akses</label>
                        <select name="input_level" class="form-select border-dark" required>
                            <option value="petugas">Petugas</option>
                            <option value="admin">Admin</option>
                            <option value="siswa">Siswa</option>
                        </select>
                    </div>
                    <hr class="border-dark">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="petugas_tampil.php" class="btn btn-secondary px-4">BATAL</a>
                        <button type="submit" name="tombol_simpan" class="btn btn-primary px-4 fw-bold">SIMPAN DATA</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>