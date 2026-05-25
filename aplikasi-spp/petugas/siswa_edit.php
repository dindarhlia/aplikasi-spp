<?php 
// 1. Memanggil header sidebar kiri
include '../layouts/header.php'; 

// 2. Menghubungkan ke database
include '../config/koneksi.php';

$pesan = "";

// Cek apakah parameter ID (NISN) ada di URL atau tidak
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "<script>alert('Pilih siswa terlebih dahulu dari tabel!'); window.location='siswa_tampil.php';</script>";
    exit;
}

// Menangkap NISN siswa yang dikirim dari tombol UBAH di halaman depan
$nisn = mysqli_real_escape_string($koneksi, $_GET['id']);

// 3. Mengambil data lama siswa tersebut untuk dimasukkan ke dalam kotak form
$query_ambil = mysqli_query($koneksi, "SELECT * FROM tb_siswa WHERE nisn = '$nisn'");
$data_lama = mysqli_fetch_assoc($query_ambil);

// Jika data tidak ditemukan di database
if (!$data_lama) {
    echo "<script>alert('Data siswa tidak ditemukan!'); window.location='siswa_tampil.php';</script>";
    exit;
}

// 4. Memproses update data ketika tombol "Simpan Perubahan" ditekan
if (isset($_POST['tombol_update'])) {
    $nis        = mysqli_real_escape_string($koneksi, $_POST['input_nis']);
    $nama       = mysqli_real_escape_string($koneksi, $_POST['input_nama']);
    $id_kelas   = mysqli_real_escape_string($koneksi, $_POST['input_id_kelas']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['input_alamat']);
    $no_telp    = mysqli_real_escape_string($koneksi, $_POST['input_notelp']);
    $id_spp     = mysqli_real_escape_string($koneksi, $_POST['input_id_spp']);

    // Mengambil nama_kelas secara otomatis berdasarkan id_kelas yang dipilih baru
    $cari_kelas = mysqli_query($koneksi, "SELECT nama_kelas FROM tb_kelas WHERE id_kelas='$id_kelas'");
    $data_kelas = mysqli_fetch_assoc($cari_kelas);
    $nama_kelas = $data_kelas['nama_kelas'];

    // Query SQL Update Data Siswa
    $query_update = "UPDATE tb_siswa SET 
                        nis='$nis', 
                        nama='$nama', 
                        id_kelas='$id_kelas', 
                        nama_kelas='$nama_kelas', 
                        alamat='$alamat', 
                        no_telp='$no_telp', 
                        id_spp='$id_spp' 
                     WHERE nisn='$nisn'";
    
    if (mysqli_query($koneksi, $query_update)) {
        // Jika berhasil, munculkan alert sukses dan langsung tendang kembali ke tabel utama
        echo "<script>alert('Data siswa bernama $nama berhasil diubah!'); window.location='siswa_tampil.php';</script>";
        exit;
    } else {
        $pesan = "<div class='alert alert-danger'>Gagal mengubah data: " . mysqli_error($koneksi) . "</div>";
    }
}

// 5. Mengambil opsi pilihan untuk dropdown kelas dan tarif SPP
$query_kelas = mysqli_query($koneksi, "SELECT * FROM tb_kelas");
$query_spp   = mysqli_query($koneksi, "SELECT * FROM tb_spp");
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        
        <div class="card card-body bg-white mb-3 shadow-sm py-2">
            <h5 class="text-dark fw-bold mb-0">Ubah Data Master Siswa</h5>
        </div>

        <div class="card shadow-sm border-dark rounded-0 mb-5">
            <div class="card-header bg-warning text-white fw-bold rounded-0">
                FORM EDIT DATA SISWA
            </div>
            <div class="card-body p-4 bg-white">
                
                <?= $pesan; ?>

                <form action="" method="POST">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">NISN (Kunci - Tidak Bisa Diubah)</label>
                            <input type="text" class="form-control bg-light border-dark text-muted fw-bold" value="<?= $data_lama['nisn']; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nomor NIS</label>
                            <input type="text" name="input_nis" class="form-control border-dark" value="<?= $data_lama['nis']; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nama Lengkap Siswa</label>
                        <input type="text" name="input_nama" class="form-control border-dark" value="<?= $data_lama['nama']; ?>" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pilih Tingkatan Kelas</label>
                            <select name="input_id_kelas" class="form-select border-dark" required>
                                <?php while($row_kelas = mysqli_fetch_assoc($query_kelas)) : ?>
                                    <option value="<?= $row_kelas['id_kelas']; ?>" <?= ($row_kelas['id_kelas'] == $data_lama['id_kelas']) ? 'selected' : ''; ?>>
                                        <?= $row_kelas['nama_kelas']; ?> (<?= $row_kelas['komp_keahlian']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Pilih Alokasi Tarif SPP</label>
                            <select name="input_id_spp" class="form-select border-dark" required>
                                <?php while($row_spp = mysqli_fetch_assoc($query_spp)) : ?>
                                    <option value="<?= $row_spp['id_spp']; ?>" <?= ($row_spp['id_spp'] == $data_lama['id_spp']) ? 'selected' : ''; ?>>
                                        Tahun <?= $row_spp['tahun']; ?> - Rp <?= number_format($row_spp['nominal'], 0, ',', '.'); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nomor Telepon Aktif</label>
                        <input type="text" name="input_notelp" class="form-control border-dark" value="<?= $data_lama['no_telp']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Alamat Tempat Tinggal</label>
                        <textarea name="input_alamat" class="form-control border-dark" rows="3" required><?= $data_lama['alamat']; ?></textarea>
                    </div>

                    <hr class="border-dark">
                    <div class="d-flex justify-content-end gap-2">
                        <a href="siswa_tampil.php" class="btn btn-secondary px-4">BATAL</a>
                        <button type="submit" name="tombol_update" class="btn btn-warning text-white px-4 fw-bold">SIMPAN PERUBAHAN</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>

<?php 
// Memanggil footer sidebar kiri
include '../layouts/footer.php'; 
?>