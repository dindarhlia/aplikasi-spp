<?php 
// 1. Memanggil header sidebar
include '../layouts/header.php'; 

// 2. Menghubungkan ke database
include '../config/koneksi.php';

// Menangkap keyword pencarian jika ada yang diinput oleh user
$keyword = "";
if (isset($_POST['tombol_cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['input_cari']);
}

// 3. Query mengambil data siswa dikombinasikan dengan pencarian dinamis
$query_sandi = "SELECT tb_siswa.*, tb_kelas.nama_kelas, tb_spp.nominal 
                FROM tb_siswa
                INNER JOIN tb_kelas ON tb_siswa.id_kelas = tb_kelas.id_kelas
                INNER JOIN tb_spp ON tb_siswa.id_spp = tb_spp.id_spp";

// Jika user sedang mencari sesuatu, tambahkan klausa WHERE
if ($keyword != "") {
    $query_sandi .= " WHERE tb_siswa.nisn LIKE '%$keyword%' 
                      OR tb_siswa.nis LIKE '%$keyword%' 
                      OR tb_siswa.nama LIKE '%$keyword%' 
                      OR tb_kelas.nama_kelas LIKE '%$keyword%'";
}

$eksekusi = mysqli_query($koneksi, $query_sandi);
?>

<div class="row">
    <div class="col-md-12">
        
        <div class="card card-body bg-white mb-4 shadow-sm py-2">
            <h5 class="text-dark fw-bold mb-0">Data Siswa</h5>
        </div>

        <form action="" method="POST" id="form_siswa">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                
                <div class="d-flex gap-2">
                    <a href="siswa_tambah.php" class="btn btn-primary btn-sm px-3 fw-bold">TAMBAH</a>
                    <button type="button" onclick="aksiEdit()" class="btn btn-warning btn-sm px-3 fw-bold text-white">UBAH</button>
                    <button type="button" onclick="aksiHapus()" class="btn btn-danger btn-sm px-3 fw-bold">HAPUS</button>
                </div>

                <div class="d-flex gap-1" style="max-width: 300px;">
                    <input type="text" name="input_cari" class="form-control form-control-sm" placeholder="Cari NISN, Nama, atau Kelas..." value="<?= $keyword; ?>">
                    <button type="submit" name="tombol_cari" class="btn btn-secondary btn-sm px-3">Cari</button>
                    <?php if ($keyword != "") : ?>
                        <a href="siswa_tampil.php" class="btn btn-outline-danger btn-sm">Reset</a>
                    <?php endif; ?>
                </div>

            </div>

            <div class="card shadow-sm border-dark rounded-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0 align-middle style-table" style="font-size: 13px;">
                            <thead class="table-light text-uppercase text-center">
                                <tr>
                                    <th width="4%">Pilih</th>
                                    <th width="4%">No</th>
                                    <th>NISN</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>ID Kelas</th>
                                    <th>Nama Kelas</th>
                                    <th>Alamat</th>
                                    <th>No. Telp</th>
                                    <th>ID SPP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                if (mysqli_num_rows($eksekusi) == 0) : 
                                ?>
                                    <tr>
                                        <td colspan="10" class="text-center text-muted py-4">Data siswa tidak ditemukan atau masih kosong.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php while ($data = mysqli_fetch_assoc($eksekusi)) : ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="radio" name="pilih_nisn" value="<?= $data['nisn']; ?>" class="form-check-input border-dark">
                                        </td>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center fw-bold"><?= $data['nisn']; ?></td>
                                        <td class="text-center"><?= $data['nis']; ?></td>
                                        <td><strong><?= $data['nama']; ?></strong></td>
                                        <td class="text-center"><?= $data['id_kelas']; ?></td>
                                        <td class="text-center"><?= $data['nama_kelas']; ?></td>
                                        <td><?= $data['alamat']; ?></td>
                                        <td class="text-center"><?= $data['no_telp']; ?></td>
                                        <td class="text-center"><?= $data['id_spp']; ?></td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
function ambilNisnTerpilih() {
    // Mencari elemen radio button yang sedang dicentang oleh user
    var pilihan = document.querySelector('input[name="pilih_nisn"]:checked');
    if (pilihan) {
        return pilihan.value;
    }
    return null;
}

function aksiEdit() {
    var id = ambilNisnTerpilih();
    if (id) {
        // Alihkan halaman menuju siswa_edit.php membawa ID parameter GET
        window.location.href = "siswa_edit.php?id=" + id;
    } else {
        alert("Silakan pilih salah satu data siswa terlebih dahulu pada tabel (klik bulatan kiri) untuk mengubah!");
    }
}

function aksiHapus() {
    var id = ambilNisnTerpilih();
    if (id) {
        if (confirm("Apakah Anda yakin ingin menghapus data siswa dengan NISN " + id + " ini?")) {
            // Alihkan halaman menuju file siswa_hapus.php membawa ID parameter GET
            window.location.href = "siswa_hapus.php?id=" + id;
        }
    } else {
        alert("Silakan pilih salah satu data siswa terlebih dahulu pada tabel (klik bulatan kiri) untuk menghapus!");
    }
}
</script>

<?php 
// 4. Memanggil komponen footer
include '../layouts/footer.php'; 
?>