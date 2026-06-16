-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2026 at 02:06 AM
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
-- Database: `sistem_pengiriman_logistik`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `id_barang` varchar(10) NOT NULL,
  `nama_barang` varchar(100) DEFAULT NULL,
  `kategori` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `berat_standar` decimal(10,2) DEFAULT NULL,
  `harga_standar` decimal(12,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`id_barang`, `nama_barang`, `kategori`, `deskripsi`, `berat_standar`, `harga_standar`) VALUES
('BRG-001', 'Ipad', 'Elektronik', '', 1.50, 6000000.00),
('BRG-002', 'Laptop', 'Elektronik', 'Lenovo Legion', 1.50, 6000000.00),
('BRG-003', 'Meja Belajar', 'Rumah Tangga', 'Kebutuhan Belajar', 5.00, 300000.00),
('BRG-004', 'Helm bogo', 'Fashion', 'Keselamatan Berkendara', 2.00, 350000.00),
('BRG-005', 'Mouse', 'Elektronik', '', 0.50, 200000.00),
('BRG-006', 'Keyboard', 'Elektronik', '', 0.70, 300000.00),
('BRG-007', 'Komputer', 'Elektronik', '', 2.00, 17000000.00),
('BRG-008', 'Microphone', 'Elektronik', '', 0.70, 300000.00),
('BRG-009', 'Kursi Gaming', 'Rumah Tangga', '', 2.00, 500000.00),
('BRG-010', 'Jaket Free Fire', 'Fashion', '', 0.20, 20000.00),
('BRG-011', 'Paracetamol', 'Kesehatan', '', 1.50, 20000.00),
('BRG-012', 'Tahu Pocong', 'Makanan', '', 0.10, 2000.00),
('BRG-021', 'Handphone', 'Elektronik', '', 0.70, 15000000.00);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id_customer` varchar(10) NOT NULL,
  `nama_customer` varchar(100) DEFAULT NULL,
  `alamat_asal` varchar(255) DEFAULT NULL,
  `kota_asal` varchar(50) DEFAULT NULL,
  `provinsi_asal` varchar(50) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tgl_daftar` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id_customer`, `nama_customer`, `alamat_asal`, `kota_asal`, `provinsi_asal`, `no_telp`, `email`, `tgl_daftar`) VALUES
('CST-001', 'Lionel Messi', '', 'Garut', 'Jawa Barat', '081234567000', 'lionel@gmail.com', '2026-06-05 21:57:16'),
('CST-002', 'Asep', 'Wanaraja', 'Garut', 'Jawa Barat', '0812345678910', 'asepsangmc@mail.com', '2026-05-26 19:03:39'),
('CST-003', 'Fadil', 'Wanaraja', 'Garut', 'Jawa Timur', '0812345678911', 'fadil@mail.com', '2026-05-26 19:06:51'),
('CST-004', 'Daris', 'Bebedahan', 'Garut', 'Jawa Tengah', '0812345678912', 'daris@mail.com', '2026-05-26 19:26:07'),
('CST-005', 'Prayoga', '', 'Garut', 'Jawa Timur', '0812345678913', 'prayoga@mail.com', '2026-06-15 09:10:18'),
('CST-006', 'Abdul', '', 'Garut', 'Jawa Barat', '0812345678913', 'abdul@mail.com', '2026-06-15 09:11:37'),
('CST-007', 'Jakir Expiliner', 'Karawang', 'jakarta', 'dki jakarta', '08952216003', 'jakir@mail.com', '2026-06-02 16:10:39'),
('CST-008', 'Ikal', '', 'Garut', 'Jawa Barat', '0812345678913', 'ikal@mail.com', '2026-06-15 09:12:06'),
('CST-009', 'Ilham', '', 'Garut', 'Sulawesi Timur', '0812345678913', 'ilham@mail.com', '2026-06-15 09:12:42'),
('CST-010', 'Lamine Yamal', '', 'Madrid', 'Cataluna', '081234567889', 'yamal@mail.com', '2026-06-05 20:39:25');

-- --------------------------------------------------------

--
-- Table structure for table `detail_pengiriman`
--

CREATE TABLE `detail_pengiriman` (
  `id_pengiriman` varchar(10) NOT NULL,
  `id_barang` varchar(10) NOT NULL,
  `jumlah_barang` int(11) DEFAULT NULL,
  `berat_barang` decimal(10,2) DEFAULT NULL,
  `harga_unit` decimal(12,2) DEFAULT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `catatan_barang` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `detail_pengiriman`
--

INSERT INTO `detail_pengiriman` (`id_pengiriman`, `id_barang`, `jumlah_barang`, `berat_barang`, `harga_unit`, `subtotal`, `catatan_barang`) VALUES
('PGM-001', 'BRG-004', 10, 20.00, 350000.00, 3500000.00, ''),
('PGM-002', 'BRG-005', 5, 2.50, 200000.00, 1000000.00, ''),
('PGM-003', 'BRG-003', 10, 50.00, 300000.00, 3000000.00, ''),
('PGM-004', 'BRG-001', 1, 1.50, 6000000.00, 6000000.00, 'Hitam'),
('PGM-005', 'BRG-012', 20, 2.00, 2000.00, 40000.00, 'Super Pedas'),
('PGM-006', 'BRG-006', 1, 0.70, 300000.00, 300000.00, 'Sedang'),
('PGM-007', 'BRG-021', 1, 0.70, 15000000.00, 15000000.00, 'Iphone 19 Pro Max '),
('PGM-008', 'BRG-004', 5, 10.00, 350000.00, 1750000.00, 'Ukuran L'),
('PGM-009', 'BRG-002', 1, 1.50, 6000000.00, 6000000.00, ''),
('PGM-010', 'BRG-002', 8, 12.00, 6000000.00, 48000000.00, ''),
('PGM-011', 'BRG-010', 1, 0.20, 20000.00, 20000.00, ''),
('PGM-012', 'BRG-009', 4, 8.00, 500000.00, 2000000.00, ''),
('PGM-013', 'BRG-011', 1, 1.50, 20000.00, 20000.00, ''),
('PGM-014', 'BRG-005', 1, 0.50, 200000.00, 200000.00, ''),
('PGM-015', 'BRG-005', 1, 0.50, 200000.00, 200000.00, ''),
('PGM-016', 'BRG-008', 1, 0.70, 300000.00, 300000.00, ''),
('PGM-017', 'BRG-002', 1, 1.50, 6000000.00, 6000000.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `kurir`
--

CREATE TABLE `kurir` (
  `id_kurir` varchar(10) NOT NULL,
  `nama_kurir` varchar(100) DEFAULT NULL,
  `no_telp` varchar(15) DEFAULT NULL,
  `area_operasi` varchar(100) DEFAULT NULL,
  `status_kurir` enum('aktif','nonaktif','cuti') DEFAULT NULL,
  `kendaraan` varchar(50) DEFAULT NULL,
  `rating` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kurir`
--

INSERT INTO `kurir` (`id_kurir`, `nama_kurir`, `no_telp`, `area_operasi`, `status_kurir`, `kendaraan`, `rating`) VALUES
('KUR-001', 'Haikal', '0812345678913', 'Garut', 'nonaktif', 'Sepeda', 5.00),
('KUR-002', 'Rojaka', '0812345678917', 'Cinunuk', 'cuti', 'Mobil Box', 5.00),
('KUR-003', 'Halland', '0819236473', 'Norwegia', 'aktif', 'Motor', 5.00),
('KUR-004', 'Sayyid', '08123456789156', 'Cimalaka', 'aktif', 'Motor', 5.00),
('KUR-005', 'Bruno Fernandes', '0819236473', 'Portugal', 'aktif', 'Truk', 5.00);

-- --------------------------------------------------------

--
-- Table structure for table `pengiriman`
--

CREATE TABLE `pengiriman` (
  `id_pengiriman` varchar(10) NOT NULL,
  `id_customer` varchar(10) DEFAULT NULL,
  `tgl_pengiriman` date DEFAULT NULL,
  `alamat_tujuan` varchar(255) DEFAULT NULL,
  `kota_tujuan` varchar(50) DEFAULT NULL,
  `provinsi_tujuan` varchar(50) DEFAULT NULL,
  `penerima_nama` varchar(100) DEFAULT NULL,
  `penerima_telp` varchar(15) DEFAULT NULL,
  `total_barang` int(11) DEFAULT NULL,
  `total_berat` decimal(10,2) DEFAULT NULL,
  `total_biaya` decimal(12,2) DEFAULT NULL,
  `catatan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengiriman`
--

INSERT INTO `pengiriman` (`id_pengiriman`, `id_customer`, `tgl_pengiriman`, `alamat_tujuan`, `kota_tujuan`, `provinsi_tujuan`, `penerima_nama`, `penerima_telp`, `total_barang`, `total_berat`, `total_biaya`, `catatan`) VALUES
('PGM-001', 'CST-006', '2026-01-15', 'Garut, Wanaraja', 'Garut', 'Jawa Barat', 'Leonardo', '081234567891700', NULL, NULL, 3500000.00, 'P p apa....'),
('PGM-002', 'CST-002', '2026-02-17', NULL, 'Bandung', 'Jawa Barat', 'Leonardo', '081234567891700', NULL, NULL, 1000000.00, NULL),
('PGM-003', 'CST-004', '2026-03-17', NULL, 'Cinunuk', 'Jawa Barat', 'Vinci', '081234567891700', NULL, NULL, 3000000.00, NULL),
('PGM-004', 'CST-003', '2026-04-15', NULL, 'Bandung', 'Jawa Barat', 'Dena', '081234567891700', NULL, NULL, 6000000.00, NULL),
('PGM-005', 'CST-008', '2026-05-15', NULL, 'Cinunuk', 'Kalimantan Utara', 'Robi', '081234567891700', NULL, NULL, 40000.00, NULL),
('PGM-006', 'CST-009', '2026-06-15', NULL, 'Samarang', 'Sumatra Utara', 'Robi', '081234567891700', NULL, NULL, 300000.00, NULL),
('PGM-007', 'CST-007', '2026-07-15', NULL, 'Samarang', 'Bermuda', 'Yu Zhong', '081234567891700', NULL, NULL, 15000000.00, NULL),
('PGM-008', 'CST-010', '2026-08-15', NULL, 'Bandung', 'Catalunya', 'Rapinha', '081234567891700', NULL, NULL, 1750000.00, NULL),
('PGM-009', 'CST-001', '2026-08-15', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 6000000.00, NULL),
('PGM-010', 'CST-005', '2026-09-15', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 48000000.00, NULL),
('PGM-011', 'CST-006', '2026-10-15', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 20000.00, NULL),
('PGM-012', 'CST-002', '2026-11-15', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 2000000.00, NULL),
('PGM-013', 'CST-003', '2026-12-15', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 20000.00, NULL),
('PGM-014', 'CST-002', '2026-08-05', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 200000.00, NULL),
('PGM-015', 'CST-008', '2026-11-05', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 200000.00, NULL),
('PGM-016', 'CST-001', '2026-12-05', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 300000.00, NULL),
('PGM-017', 'CST-004', '2026-09-05', NULL, 'Garut', 'Jawa Barat', 'Yu Zhong', '081234567891700', NULL, NULL, 6000000.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `penugasan_kurir`
--

CREATE TABLE `penugasan_kurir` (
  `id_pengiriman` varchar(10) NOT NULL,
  `id_kurir` varchar(10) NOT NULL,
  `urutan_kurir` int(11) NOT NULL,
  `tgl_mulai_tugas` datetime DEFAULT NULL,
  `tgl_selesai_tugas` datetime DEFAULT NULL,
  `kota_asal_rute` varchar(50) DEFAULT NULL,
  `kota_tujuan_rute` varchar(50) DEFAULT NULL,
  `jarak_km` decimal(10,2) DEFAULT NULL,
  `biaya_kurir` decimal(12,2) DEFAULT NULL,
  `status_tugas` enum('pending','pickup','in_transit','delivered','failed') DEFAULT NULL,
  `catatan_tugas` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penugasan_kurir`
--

INSERT INTO `penugasan_kurir` (`id_pengiriman`, `id_kurir`, `urutan_kurir`, `tgl_mulai_tugas`, `tgl_selesai_tugas`, `kota_asal_rute`, `kota_tujuan_rute`, `jarak_km`, `biaya_kurir`, `status_tugas`, `catatan_tugas`) VALUES
('PGM-001', 'KUR-005', 1, '2026-06-15 02:30:55', NULL, 'Garut', 'Cibaduyut', 21.00, 5000.00, 'delivered', 'P p apa...'),
('PGM-002', 'KUR-004', 2, '2026-06-15 02:36:35', NULL, 'Garut', 'Tasikmalaya', 21.00, 5000.00, 'delivered', ''),
('PGM-003', 'KUR-005', 3, '2026-06-15 02:39:20', NULL, 'Garut', 'Cinunuk', 21.00, 5000.00, 'delivered', ''),
('PGM-004', 'KUR-004', 1, '2026-06-15 07:41:05', NULL, 'Garut', 'Bandung', 21.00, 10000.00, 'delivered', NULL),
('PGM-005', 'KUR-003', 2, '2026-06-15 07:44:18', NULL, 'Garut', 'Cinunuk', 21.00, 10000.00, 'delivered', NULL),
('PGM-006', 'KUR-005', 1, '2026-06-15 07:45:52', NULL, 'Garut', 'Samarang', 21.00, 6000.00, 'delivered', NULL),
('PGM-007', 'KUR-003', 1, '2026-06-15 07:47:38', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'delivered', ''),
('PGM-008', 'KUR-004', 1, '2026-06-15 07:49:28', NULL, 'Garut', 'Catalunya', 21.00, 45000.00, 'delivered', NULL),
('PGM-009', 'KUR-005', 1, '2026-06-15 07:54:14', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'delivered', NULL),
('PGM-010', 'KUR-003', 1, '2026-06-15 07:55:04', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'delivered', NULL),
('PGM-011', 'KUR-005', 1, '2026-06-15 07:55:57', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'pending', NULL),
('PGM-012', 'KUR-005', 1, '2026-06-15 07:56:54', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'pickup', NULL),
('PGM-013', 'KUR-003', 1, '2026-06-15 07:57:53', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'in_transit', NULL),
('PGM-014', 'KUR-005', 1, '2026-06-15 07:58:49', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'failed', NULL),
('PGM-015', 'KUR-004', 1, '2026-06-15 07:59:45', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'delivered', NULL),
('PGM-016', 'KUR-003', 1, '2026-06-15 08:01:24', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'delivered', NULL),
('PGM-017', 'KUR-005', 1, '2026-06-15 08:04:39', NULL, 'Garut', 'Samarang', 21.00, 4000.00, 'delivered', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `riwayat_status`
--

CREATE TABLE `riwayat_status` (
  `id_riwayat` varchar(15) NOT NULL,
  `id_pengiriman` varchar(10) DEFAULT NULL,
  `id_status` varchar(10) DEFAULT NULL,
  `tgl_status_berubah` datetime DEFAULT NULL,
  `lokasi_status` varchar(100) DEFAULT NULL,
  `catatan_status` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `riwayat_status`
--

INSERT INTO `riwayat_status` (`id_riwayat`, `id_pengiriman`, `id_status`, `tgl_status_berubah`, `lokasi_status`, `catatan_status`) VALUES
('RWY-050131', 'PGM-002', 'delivered', '2026-06-15 09:37:00', 'Gudang Cinunuk', NULL),
('RWY-069544', 'PGM-009', 'delivered', '2026-06-15 14:54:00', 'Gudang Samarang', NULL),
('RWY-122177', 'PGM-010', 'delivered', '2026-06-15 14:55:00', 'Gudang Cinunuk', NULL),
('RWY-172353', 'PGM-011', 'pending', '2026-06-15 14:56:00', 'Gudang Samarang', NULL),
('RWY-186711', 'PGM-003', 'delivered', '2026-06-15 09:39:00', 'Gudang Bandung', NULL),
('RWY-229609', 'PGM-012', 'pickup', '2026-06-15 14:57:00', 'Gudang Bandung', NULL),
('RWY-285873', 'PGM-013', 'in_transit', '2026-06-15 14:58:00', 'Gudang Samarang', NULL),
('RWY-291934', 'PGM-004', 'delivered', '2026-06-15 14:41:00', 'Gudang Bandung', NULL),
('RWY-342135', 'PGM-014', 'failed', '2026-06-15 14:59:00', 'Gudang Samarang', NULL),
('RWY-401297', 'PGM-015', 'delivered', '2026-06-15 15:00:00', 'Gudang Cinunuk', NULL),
('RWY-497868', 'PGM-016', 'delivered', '2026-06-15 15:01:00', 'Gudang Cinunuk', NULL),
('RWY-501749', 'PGM-005', 'delivered', '2026-06-15 14:45:00', 'Gudang Cinunuk', NULL),
('RWY-579052', 'PGM-006', 'delivered', '2026-06-15 14:46:00', 'Gudang Samarang', NULL),
('RWY-684516', 'PGM-007', 'delivered', '2026-06-15 14:48:00', 'Gudang Samarang', NULL),
('RWY-691350', 'PGM-017', 'delivered', '2026-06-15 15:04:00', 'Gudang Cinunuk', NULL),
('RWY-698248', 'PGM-001', 'delivered', '2026-06-15 09:31:00', 'Gudang Cinunuk', 'P p apaa...'),
('RWY-804253', 'PGM-008', 'delivered', '2026-06-15 14:50:00', 'Gudang Samarang', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

CREATE TABLE `status` (
  `id_status` varchar(10) NOT NULL,
  `nama_status` varchar(50) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`id_status`, `nama_status`, `deskripsi`) VALUES
('delivered', 'Terkirim', NULL),
('failed', 'Gagal', NULL),
('in_transit', 'Dalam Perjalanan', NULL),
('pending', 'Menunggu', NULL),
('pickup', 'Pickup', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`id_barang`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id_customer`);

--
-- Indexes for table `detail_pengiriman`
--
ALTER TABLE `detail_pengiriman`
  ADD PRIMARY KEY (`id_pengiriman`,`id_barang`),
  ADD KEY `id_barang` (`id_barang`);

--
-- Indexes for table `kurir`
--
ALTER TABLE `kurir`
  ADD PRIMARY KEY (`id_kurir`);

--
-- Indexes for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD PRIMARY KEY (`id_pengiriman`),
  ADD KEY `id_customer` (`id_customer`);

--
-- Indexes for table `penugasan_kurir`
--
ALTER TABLE `penugasan_kurir`
  ADD PRIMARY KEY (`id_pengiriman`,`id_kurir`,`urutan_kurir`),
  ADD KEY `id_kurir` (`id_kurir`);

--
-- Indexes for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  ADD PRIMARY KEY (`id_riwayat`),
  ADD KEY `id_pengiriman` (`id_pengiriman`),
  ADD KEY `id_status` (`id_status`);

--
-- Indexes for table `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id_status`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detail_pengiriman`
--
ALTER TABLE `detail_pengiriman`
  ADD CONSTRAINT `detail_pengiriman_ibfk_1` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`),
  ADD CONSTRAINT `detail_pengiriman_ibfk_2` FOREIGN KEY (`id_barang`) REFERENCES `barang` (`id_barang`);

--
-- Constraints for table `pengiriman`
--
ALTER TABLE `pengiriman`
  ADD CONSTRAINT `pengiriman_ibfk_1` FOREIGN KEY (`id_customer`) REFERENCES `customer` (`id_customer`);

--
-- Constraints for table `penugasan_kurir`
--
ALTER TABLE `penugasan_kurir`
  ADD CONSTRAINT `penugasan_kurir_ibfk_1` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`),
  ADD CONSTRAINT `penugasan_kurir_ibfk_2` FOREIGN KEY (`id_kurir`) REFERENCES `kurir` (`id_kurir`);

--
-- Constraints for table `riwayat_status`
--
ALTER TABLE `riwayat_status`
  ADD CONSTRAINT `riwayat_status_ibfk_1` FOREIGN KEY (`id_pengiriman`) REFERENCES `pengiriman` (`id_pengiriman`),
  ADD CONSTRAINT `riwayat_status_ibfk_2` FOREIGN KEY (`id_status`) REFERENCES `status` (`id_status`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
