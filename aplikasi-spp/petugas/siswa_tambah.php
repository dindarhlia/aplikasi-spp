<?php 
// 1. Memanggil header (otomatis cek session login)
include '../layouts/header.php'; 

// 2. Menghubungkan ke database
include '../config/koneksi.php';

$pesan_sukses = "";
$pesan_gagal  = "";

// 3. Memproses data ketika tombol Simpan ditekan
if (isset($_POST['tombol_simpan'])) {
    $nisn       = mysqli_real_escape_string($koneksi, $_POST['input_nisn']);
    $nis        = mysqli_real_escape_string($koneksi, $_POST['input_nis']);
    $nama       = mysqli_real_escape_string($koneksi, $_POST['input_nama']);
    $id_kelas   = mysqli_real_escape_string($koneksi, $_POST['input_id_kelas']);
    $alamat     = mysqli_real_escape_string($koneksi, $_POST['input_alamat']);
    $no_telp    = mysqli_real_escape_string($koneksi, $_POST['input_notelp']);
    $id_spp     = mysqli_real_escape_string($koneksi, $_POST['input_id_spp']);

    // Ambil data nama_kelas secara otomatis berdasarkan id_kelas yang dipilih
    $cari_kelas   = mysqli_query($koneksi, "SELECT nama_kelas FROM tb_kelas WHERE id_kelas='$id_kelas'");
    $data_kelas   = mysqli_fetch_assoc($cari_kelas);
    $nama_kelas   = $data_kelas['nama_kelas'];

    // Cek apakah NISN sudah terdaftar sebelumnya agar tidak terjadi error duplicate primary key
    $cek_nisn = mysqli_query($koneksi, "SELECT nisn FROM tb_siswa WHERE nisn='$nisn'");
    if (mysqli_num_rows($cek_nisn) > 0) {
        $pesan_gagal = "Gagal! NISN sudah digunakan oleh siswa lain.";
    } else {
        // Query Insert data ke tb_siswa
        $query_insert = "INSERT INTO tb_siswa (nisn, nis, nama, id_kelas, nama_kelas, alamat, no_telp, id_spp) 
                         VALUES ('$nisn', '$nis', '$nama', '$id_kelas', '$nama_kelas', '$alamat', '$no_telp', '$id_spp')";
        
        if (mysqli_query($koneksi, $query_insert)) {
            $pesan_sukses = "Data siswa bernama <strong>$nama</strong> berhasil disimpan!";
        } else {
            $pesan_gagal = "Gagal menyimpan data: " . mysqli_error($koneksi);
        }
    }
}

// 4. Mengambil data kelas untuk opsi pilihan (Dropdown Select)
$query_kelas = mysqli_query($koneksi, "SELECT * FROM tb_kelas");

// 5. Mengambil data SPP untuk opsi pilihan (Dropdown Select)
$query_spp = mysqli_query($koneksi, "SELECT * FROM tb_spp");
?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-dark fw-bold">Tambah Data Master Siswa</h4>
            <a href="siswa_tampil.php" class="btn btn-secondary btn-sm">Kembali ke Tabel</a>
        </div>

        <div class="card shadow-sm mb-5">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0 py-1">Formulir Input Data Siswa Baru</h6>
            </div>
            <div class="card-body p-4">

                <?php if ($pesan_sukses != "") : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= $pesan_sukses; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if ($pesan_gagal != "") : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= $pesan_gagal; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NISN (10 Digit)</label>
                            <input type="text" name="input_nisn" maxlength="10" class="form-control" placeholder="Contoh: 0061234561" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIS (8 Digit)</label>
                            <input type="text" name="input_nis" maxlength="8" class="form-control" placeholder="Contoh: 2324001" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap Siswa</label>
                        <input type="text" name="input_nama" class="form-control" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Kelas</label>
                            <select name="input_id_kelas" class="form-select" required>
                                <option value="">-- Pilih Kelas & Jurusan --</option>
                                <?php while($row_kelas = mysqli_fetch_assoc($query_kelas)) : ?>
                                    <option value="<?= $row_kelas['id_kelas']; ?>">
                                        <?= $row_kelas['nama_kelas']; ?> (<?= $row_kelas['komp_keahlian']; ?>)
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Pilih Tarif SPP</label>
                            <select name="input_id_spp" class="form-select" required>
                                <option value="">-- Pilih Tahun Ajaran (Tarif) --</option>
                                <?php while($row_spp = mysqli_fetch_assoc($query_spp)) : ?>
                                    <option value="<?= $row_spp['id_spp']; ?>">
                                        Tahun <?= $row_spp['tahun']; ?> - Rp <?= number_format($row_spp['nominal'], 0, ',', '.'); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor Telepon / HP</label>
                        <input type="text" name="input_notelp" maxlength="13" class="form-control" placeholder="Contoh: 081234567890" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap Rumah</label>
                        <textarea name="input_alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap tempat tinggal" required></textarea>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-end gap-2">
                        <button type="reset" class="btn btn-warning text-white">Reset Input</button>
                        <button type="submit" name="tombol_simpan" class="btn btn-primary px-4">Simpan Data Siswa</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php 
// Memanggil footer untuk menutup tag HTML
include '../layouts/footer.php'; 
?>