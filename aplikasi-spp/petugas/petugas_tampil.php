<?php 
// 1. Memanggil header sidebar kiri
include '../layouts/header.php'; 

// 2. Menghubungkan ke database
include '../config/koneksi.php';

// Menangkap keyword pencarian jika ada yang diinput oleh user
$keyword = "";
if (isset($_POST['tombol_cari'])) {
    $keyword = mysqli_real_escape_string($koneksi, $_POST['input_cari']);
}

// 3. Query mengambil data dari tb_petugas
$query_sandi = "SELECT * FROM tb_petugas";

// Jika user melakukan pencarian, filter berdasarkan nama atau username
if ($keyword != "") {
    $query_sandi .= " WHERE nama_petugas LIKE '%$keyword%' OR username LIKE '%$keyword%' OR level LIKE '%$keyword%'";
}

$eksekusi = mysqli_query($koneksi, $query_sandi);
?>

<div class="row">
    <div class="col-md-12">
        
        <div class="card card-body bg-white mb-4 shadow-sm py-2">
            <h5 class="text-dark fw-bold mb-0">Data Petugas</h5>
        </div>

        <form action="" method="POST" id="form_petugas">
            
            <div class="d-flex justify-content-between align-items-center mb-3">
                
                <div class="d-flex gap-2">
                    <a href="petugas_tambah.php" class="btn btn-primary btn-sm px-3 fw-bold">TAMBAH</a>
                    <button type="button" onclick="aksiEdit()" class="btn btn-warning btn-sm px-3 fw-bold text-white">UBAH</button>
                    <button type="button" onclick="aksiHapus()" class="btn btn-danger btn-sm px-3 fw-bold">HAPUS</button>
                </div>

                <div class="d-flex gap-1" style="max-width: 300px;">
                    <input type="text" name="input_cari" class="form-control form-control-sm" placeholder="Cari nama, user, atau level..." value="<?= $keyword; ?>">
                    <button type="submit" name="tombol_cari" class="btn btn-secondary btn-sm px-3">Cari</button>
                    <?php if ($keyword != "") : ?>
                        <a href="petugas_tampil.php" class="btn btn-outline-danger btn-sm">Reset</a>
                    <?php endif; ?>
                </div>

            </div>

            <div class="card shadow-sm border-dark rounded-0">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered mb-0 align-middle" style="font-size: 13px;">
                            <thead class="table-light text-uppercase text-center">
                                <tr>
                                    <th width="5%">Pilih</th>
                                    <th width="5%">No</th>
                                    <th>ID Petugas</th>
                                    <th>Username</th>
                                    <th>Nama Petugas</th>
                                    <th>Level Akses</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                if (mysqli_num_rows($eksekusi) == 0) : 
                                ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">Data petugas tidak ditemukan atau masih kosong.</td>
                                    </tr>
                                <?php else : ?>
                                    <?php while ($data = mysqli_fetch_assoc($eksekusi)) : ?>
                                    <tr>
                                        <td class="text-center">
                                            <input type="radio" name="pilih_id" value="<?= $data['id_petugas']; ?>" class="form-check-input border-dark">
                                        </td>
                                        <td class="text-center"><?= $no++; ?></td>
                                        <td class="text-center fw-bold"><span class="badge bg-secondary"><?= $data['id_petugas']; ?></span></td>
                                        <td><?= $data['username']; ?></td>
                                        <td><strong><?= $data['nama_petugas']; ?></strong></td>
                                        <td class="text-center">
                                            <?php if ($data['level'] == 'admin') : ?>
                                                <span class="badge bg-danger">Admin</span>
                                            <?php elseif ($data['level'] == 'petugas') : ?>
                                                <span class="badge bg-primary">Petugas</span>
                                            <?php else : ?>
                                                <span class="badge bg-info text-dark">Siswa</span>
                                            <?php endif; ?>
                                        </td>
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
function ambilIdTerpilih() {
    var pilihan = document.querySelector('input[name="pilih_id"]:checked');
    if (pilihan) {
        return pilihan.value;
    }
    return null;
}

function aksiEdit() {
    var id = ambilIdTerpilih();
    if (id) {
        window.location.href = "petugas_edit.php?id=" + id;
    } else {
        alert("Silakan pilih salah satu data petugas terlebih dahulu pada tabel untuk mengubah!");
    }
}

function aksiHapus() {
    var id = ambilIdTerpilih();
    if (id) {
        if (confirm("Apakah Anda yakin ingin menghapus data petugas dengan ID " + id + " ini?")) {
            window.location.href = "petugas_hapus.php?id=" + id;
        }
    } else {
        alert("Silakan pilih salah satu data petugas terlebih dahulu pada tabel untuk menghapus!");
    }
}
</script>

<?php 
// 4. Memanggil komponen footer
include '../layouts/footer.php'; 
?>