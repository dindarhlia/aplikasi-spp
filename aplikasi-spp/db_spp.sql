-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 25, 2026 at 01:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_spp`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_cek_pembayaran`
--

CREATE TABLE `tb_cek_pembayaran` (
  `nisn` varchar(10) NOT NULL,
  `tgl_terakhir_bayar` date NOT NULL,
  `tgl_sekaraang` date NOT NULL,
  `status_pembayaran` enum('Belum Lunas','Sudah Lunas') NOT NULL,
  `jumlah_bulan` varchar(5) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_telp` varchar(13) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_cek_pembayaran`
--

INSERT INTO `tb_cek_pembayaran` (`nisn`, `tgl_terakhir_bayar`, `tgl_sekaraang`, `status_pembayaran`, `jumlah_bulan`, `nama`, `no_telp`) VALUES
('0061234561', '2026-05-10', '2026-05-25', 'Sudah Lunas', '1', 'Rian Hidayat', '081234567890'),
('0071234562', '2026-05-12', '2026-05-25', 'Sudah Lunas', '1', 'Dewi Lestari', '081234567891');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id_kelas` varchar(11) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL,
  `komp_keahlian` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id_kelas`, `nama_kelas`, `komp_keahlian`) VALUES
('KLS001', 'X-RPL', 'Rekayasa Perangkat Lunak'),
('KLS002', 'XI-RPL', 'Rekayasa Perangkat Lunak'),
('KLS003', 'XII-RPL', 'Rekayasa Perangkat Lunak'),
('KLS004', 'X-TKJ', 'Teknik Komputer dan Jaringan'),
('KLS005', 'XI-TKJ', 'Teknik Komputer dan Jaringan'),
('KLS006', 'XII-RPL', 'Rekayasa Perangkat Lunak');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pembayaran`
--

CREATE TABLE `tb_pembayaran` (
  `id_pembayaran` varchar(11) NOT NULL,
  `status` enum('Belum Lunas','Sudah Lunas') NOT NULL,
  `nisn` varchar(10) NOT NULL,
  `tgl_bayar` date NOT NULL,
  `tgl_terakhir_bayar` date NOT NULL,
  `batas_pembayaran` date NOT NULL,
  `jumlah_bulan` varchar(10) NOT NULL,
  `id_spp` varchar(40) NOT NULL,
  `nominal_bayar` varchar(100) NOT NULL,
  `jumlah_bayar` varchar(40) NOT NULL,
  `kembalian` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_pembayaran`
--

INSERT INTO `tb_pembayaran` (`id_pembayaran`, `status`, `nisn`, `tgl_bayar`, `tgl_terakhir_bayar`, `batas_pembayaran`, `jumlah_bulan`, `id_spp`, `nominal_bayar`, `jumlah_bayar`, `kembalian`) VALUES
('TR260525161', 'Sudah Lunas', '0081234563', '2026-05-25', '2026-05-25', '2026-05-25', '1', 'SPP002', '300000', '400000', '100000'),
('TR260525289', 'Sudah Lunas', '0061234561', '2026-05-25', '2026-05-25', '2026-05-25', '1', 'SPP003', '350000', '350000', '0'),
('TR260525322', 'Sudah Lunas', '1234567891', '2026-05-25', '2026-05-25', '2026-05-25', '2', 'SPP004', '800000', '800000', '0'),
('TR260525569', 'Sudah Lunas', '1234567891', '2026-05-25', '2026-05-25', '2026-05-25', '1', 'SPP004', '400000', '400000', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tb_petugas`
--

CREATE TABLE `tb_petugas` (
  `id_petugas` varchar(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(32) NOT NULL,
  `nama_petugas` varchar(35) NOT NULL,
  `level` enum('admin','petugas','siswa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_petugas`
--

INSERT INTO `tb_petugas` (`id_petugas`, `username`, `password`, `nama_petugas`, `level`) VALUES
('PTG001', 'adminutama', '0192023a7bbd73250516f069df18b500', 'Ahmad Saputra', 'admin'),
('PTG002', 'petugas1', '570c396b3fc856eceb8aa7357f32af1a', 'Anggi Agustin', 'petugas'),
('SWS001', 'siswa1', '3afa0d81296a4f17d477ec823261b1ec', 'Chrisna Putra', 'siswa');

-- --------------------------------------------------------

--
-- Table structure for table `tb_siswa`
--

CREATE TABLE `tb_siswa` (
  `nisn` varchar(10) NOT NULL,
  `nis` varchar(8) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `id_kelas` varchar(11) NOT NULL,
  `nama_kelas` varchar(10) NOT NULL,
  `alamat` text NOT NULL,
  `no_telp` varchar(13) NOT NULL,
  `id_spp` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_siswa`
--

INSERT INTO `tb_siswa` (`nisn`, `nis`, `nama`, `id_kelas`, `nama_kelas`, `alamat`, `no_telp`, `id_spp`) VALUES
('0061234561', '2324001', 'Rian Hidayat', 'KLS003', 'XII-RPL', 'Jl. Merdeka No. 10', '081234567891', 'SPP003'),
('0071234562', '2324002', 'Dewi Lestari', 'KLS003', 'XII-RPL', 'Jl. Mawar No. 5', '081234567891', 'SPP003'),
('0081234563', '2425001', 'Andi Wijaya', 'KLS002', 'XI-RPL', 'Jl. Melati No. 12', '081234567892', 'SPP002'),
('0081234564', '2425002', 'Siti Rahma', 'KLS002', 'XI-RPL', 'Jl. Anggrek No. 3', '081234567893', 'SPP002'),
('1234567891', '12345678', 'Chrisna Putra', 'KLS001', 'X-RPL', 'pondok aren, tangerang selatan', '08872887777', 'SPP004');

-- --------------------------------------------------------

--
-- Table structure for table `tb_spp`
--

CREATE TABLE `tb_spp` (
  `id_spp` varchar(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `nominal` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_spp`
--

INSERT INTO `tb_spp` (`id_spp`, `tahun`, `nominal`) VALUES
('SPP001', 2023, '250000'),
('SPP002', 2024, '300000'),
('SPP003', 2025, '350000'),
('SPP004', 2026, '400000'),
('SPP005', 2027, '450000');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_cek_pembayaran`
--
ALTER TABLE `tb_cek_pembayaran`
  ADD PRIMARY KEY (`nisn`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD PRIMARY KEY (`id_pembayaran`),
  ADD KEY `nisn` (`nisn`);

--
-- Indexes for table `tb_petugas`
--
ALTER TABLE `tb_petugas`
  ADD PRIMARY KEY (`id_petugas`);

--
-- Indexes for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD PRIMARY KEY (`nisn`),
  ADD KEY `id_kelas` (`id_kelas`),
  ADD KEY `id_spp` (`id_spp`);

--
-- Indexes for table `tb_spp`
--
ALTER TABLE `tb_spp`
  ADD PRIMARY KEY (`id_spp`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_cek_pembayaran`
--
ALTER TABLE `tb_cek_pembayaran`
  ADD CONSTRAINT `tb_cek_pembayaran_ibfk_1` FOREIGN KEY (`nisn`) REFERENCES `tb_siswa` (`nisn`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tb_pembayaran`
--
ALTER TABLE `tb_pembayaran`
  ADD CONSTRAINT `tb_pembayaran_ibfk_1` FOREIGN KEY (`nisn`) REFERENCES `tb_siswa` (`nisn`) ON UPDATE CASCADE;

--
-- Constraints for table `tb_siswa`
--
ALTER TABLE `tb_siswa`
  ADD CONSTRAINT `tb_siswa_ibfk_1` FOREIGN KEY (`id_kelas`) REFERENCES `tb_kelas` (`id_kelas`) ON UPDATE CASCADE,
  ADD CONSTRAINT `tb_siswa_ibfk_2` FOREIGN KEY (`id_spp`) REFERENCES `tb_spp` (`id_spp`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
