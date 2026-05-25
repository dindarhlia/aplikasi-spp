<?php 
include '../layouts/header.php'; 
include '../config/koneksi.php';

$pesan = "";

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Pilih petugas terlebih dahulu dari tabel!'); window.location='petugas_tampil.php';</script>";
    exit;
}

$id_petugas = mysqli_real_escape_string($koneksi, $_GET['id']);
$query_ambil = mysqli_query($koneksi, "SELECT * FROM tb_petugas WHERE id_petugas = '$id_petugas'");
$data_lama = mysqli_fetch_assoc($query_ambil);

if (!$data_lama) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='petugas_tampil.php';</script>";
    exit;
}

if (isset($_POST['tombol_update'])) {
    $username     = mysqli_real_escape_string($koneksi, $_POST['input_user']);
    $nama_petugas = mysqli_real_escape_string($koneksi, $_POST['input_nama']);
    $level        = mysqli_real_escape_string($koneksi, $_POST['input_level']);
    $password_baru= mysqli_real_escape_string($koneksi, $_POST['input_pass']);

    // Logika jika password diisi berarti ganti password, jika kosong pakai password lama
    if (!empty($password_baru)) {
        $password_final = md5($password_baru);
    } else {
        $password_final = $data_lama['password'];
    }

    $query_update = "UPDATE tb_petugas SET 
                        username='$username', 
                        password='$password_final', 
                        nama_petugas='$nama_petugas', 
                        level='$level' 
                     WHERE id_petugas='$id_petugas'";
    
    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Data petugas berhasil diubah!'); window.location='petugas_tampil.php';</script>";
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengubah data: " . mysqli_error($koneksi) . "</div>";
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card card-body bg-white mb-3 shadow-sm py-2">
            <h5 class="text-dark fw-bold mb-0">Ubah Data Petugas</h5>
        </div>

        <div class="card shadow-sm border-dark rounded-0">
            <div class="card-header bg-warning text-white fw-bold rounded-0">FORM EDIT DATA PETUGAS</div>
            <div class="card-body bg-white p-4">
                <?= $pesan; ?>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">ID Petugas (Kunci - Readonly)</label>
                        <input type="text" class="form-control bg-light border-dark text-muted fw-bold" value="<?= $data_lama['id_petugas']; ?>" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Username</label>
                        <input type="text" name="input_user" class="form-control border-dark" value="<?= $data_lama['username']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password Baru</label>
                        <input type="password" name="input_pass" class="form-control border-dark" placeholder="Kosongkan jika tidak ingin mengganti password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap Petugas</label>
                        <input type="text" name="input_nama" class="form-control border-dark" value="<?= $data_lama['nama_petugas']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Level Hak Akses</label>
                        <select name="input_level" class="form-select border-dark" required>
                            <option value="petugas" <?= ($data_lama['level'] == 'petugas') ? 'selected' : ''; ?>>Petugas</option>
                            <option value="admin" <?= ($data_lama['level'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                            <option value="siswa" <?= ($data_lama['level'] == 'siswa') ? 'selected' : ''; ?>>Siswa</option>
                        </select>
                    </div>
                    <hr class="border-dark">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="petugas_tampil.php" class="btn btn-secondary px-4">BATAL</a>
                        <button type="submit" name="tombol_update" class="btn btn-warning text-white px-4 fw-bold">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../layouts/footer.php'; ?>