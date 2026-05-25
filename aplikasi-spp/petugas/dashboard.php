<?php 
// 1. Memanggil komponen header sidebar kiri
include '../layouts/header.php'; 

// 2. Menghubungkan ke database untuk menghitung data real-time
include '../config/koneksi.php';

// Query A: Menghitung jumlah siswa yang sudah lunas
$query_lunas = mysqli_query($koneksi, "SELECT COUNT(DISTINCT nisn) AS total FROM tb_pembayaran WHERE status='Sudah Lunas'");
$data_lunas = mysqli_fetch_assoc($query_lunas);
$total_lunas = $data_lunas['total'];

// Query B: Menghitung seluruh siswa di sekolah
$query_semua = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tb_siswa");
$data_semua = mysqli_fetch_assoc($query_semua);
$total_siswa = $data_semua['total'];

// Perhitungan matematika: Siswa belum lunas
$total_belum_lunas = $total_siswa - $total_lunas;
?>

<div class="row justify-content-center mt-3 mb-5">
    <div class="col-md-10 d-flex justify-content-center gap-4">
        
        <div class="card px-4 py-3 text-center border-dark shadow-sm" style="min-width: 240px; border-radius: 10px; background-color: #ffffff;">
            <div class="card-body p-1">
                <small class="text-dark fw-semibold d-block mb-2" style="font-size: 14px;">Siswa Yang Sudah Lunas</small>
                <div class="d-flex justify-content-center align-items-center gap-1">
                    <span class="text-muted" style="font-size: 14px;">Total : </span>
                    <strong class="text-dark" style="font-size: 18px;"><?= $total_lunas; ?> Siswa</strong>
                </div>
            </div>
        </div>

        <div class="card px-4 py-3 text-center border-dark shadow-sm" style="min-width: 240px; border-radius: 10px; background-color: #ffffff;">
            <div class="card-body p-1">
                <small class="text-dark fw-semibold d-block mb-2" style="font-size: 14px;">Siswa Yang Belum Lunas</small>
                <div class="d-flex justify-content-center align-items-center gap-1">
                    <span class="text-muted" style="font-size: 14px;">Total : </span>
                    <strong class="text-dark" style="font-size: 18px;"><?= $total_belum_lunas; ?> Siswa</strong>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="row" style="margin-top: 120px;">
    <div class="col-md-12 text-center">
        <div class="d-inline-block p-3">
            <h1 class="fw-bold text-secondary tracking-wide mb-2" style="font-size: 2.3rem; letter-spacing: 3px;">
                APLIKASI PEMBAYARAN
            </h1>
            <h1 class="fw-bold text-secondary tracking-wide" style="font-size: 2.3rem; letter-spacing: 3px;">
                SPP SEKOLAH
            </h1>
        </div>
    </div>
</div>

<?php 
// 3. Memanggil komponen footer
include '../layouts/footer.php'; 
?>