<?php
session_start();
include '../config/koneksi.php';

$error_pesan = "";

// Jika tombol login ditekan
if (isset($_POST['tombol_login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['input_user']);
    $password = mysqli_real_escape_string($koneksi, $_POST['input_pass']);
    
    // Mengubah inputan password menjadi MD5 sesuai enkripsi database
    $password_md5 = md5($password);

    // Cek data ke tb_petugas
    $query = "SELECT * FROM tb_petugas WHERE username='$username' AND password='$password_md5'";
    $eksekusi = mysqli_query($koneksi, $query);
    $hitung_data = mysqli_num_rows($eksekusi);

    if ($hitung_data > 0) {
        $data_user = mysqli_fetch_assoc($eksekusi);

        // Menyimpan data penting ke dalam Session login
        $_SESSION['id_petugas']   = $data_user['id_petugas'];
        $_SESSION['username']     = $data_user['username'];
        $_SESSION['nama_petugas'] = $data_user['nama_petugas'];
        $_SESSION['level']        = $data_user['level']; 

        // Redirect secara dinamis sesuai dengan level hak akses
        if ($_SESSION['level'] == 'admin' || $_SESSION['level'] == 'petugas') {
            header("Location: ../petugas/dashboard.php");
            exit;
        } else if ($_SESSION['level'] == 'siswa') {
            header("Location: ../siswa/histori_pembayaran.php");
            exit;
        }
    } else {
        $error_pesan = "Username atau Password salah!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Aplikasi SPP Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            border: 1px solid #ced4da;
            border-radius: 8px;
        }
        .card-header {
            background-color: #0d6efd; /* Warna biru utama */
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-4">
            
            <div class="card shadow">
                <div class="card-header text-white text-center py-3">
                    <h5 class="mb-0 text-uppercase tracking-wide" style="font-size: 15px;">
                        Aplikasi Pembayaran SPP
                    </h5>
                </div>
                
                <div class="card-body p-4 bg-white">
                    
                    <?php if ($error_pesan != "") : ?>
                        <div class="alert alert-danger text-center py-2 mb-3 fw-semibold" style="font-size: 13px;" role="alert">
                            ⚠️ <?= $error_pesan; ?>
                        </div>
                    <?php endif; ?>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 14px;">Username</label>
                            <input type="text" name="input_user" class="form-control border-dark" required placeholder="Masukkan username anda...">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold text-secondary" style="font-size: 14px;">Password</label>
                            <input type="password" name="input_pass" class="form-control border-dark" required placeholder="Masukkan password anda...">
                        </div>
                        
                        <button type="submit" name="tombol_login" class="btn btn-primary w-100 mt-3 fw-bold shadow-sm">
                            Masuk ke Sistem
                        </button>
                    </form>

                </div>
            </div>

            <div class="text-center text-muted mt-3" style="font-size: 11px;">
                Sistem Informasi SPP &copy; 2026 - Ujikom TI
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>