-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 02, 2023 at 11:09 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gas_alam_sentosa`
--

-- --------------------------------------------------------

--
-- Table structure for table `barangs`
--

CREATE TABLE `barangs` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `tipe` varchar(45) DEFAULT NULL,
  `alur` varchar(45) DEFAULT NULL,
  `harga_beli` double DEFAULT NULL,
  `file_gambar` varchar(45) DEFAULT NULL,
  `kode_acc` varchar(45) DEFAULT NULL,
  `aktif` tinyint(4) DEFAULT NULL,
  `kategori_barang_id` int(11) NOT NULL,
  `satuan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `kode`, `nama`, `tipe`, `alur`, `harga_beli`, `file_gambar`, `kode_acc`, `aktif`, `kategori_barang_id`, `satuan_id`) VALUES
(4, 'DUM', 'Dummy', 'Persediaan', 'Jual', 100000, NULL, '0000', 1, 1, 1),
(5, '001110001', 'coba ahoirw ajsjs', 'Persediaan', 'Jual', 1000, '001110001.png', '134', 1, 1, 1),
(6, '001060001', 'ruhag', 'Persediaan', 'Jual', 13989, NULL, '8928', 1, 1, 1),
(7, '001070001', 'jnr', 'Jasa', 'Beli', 23987, NULL, '9302', 1, 1, 1),
(8, '001080001', 'tes', NULL, 'All', 392049, NULL, '91340', 1, 1, 8),
(9, '002110001', 'ekg', NULL, 'All', 324902, NULL, '0293049', 1, 2, 11),
(10, '002110002', 'ekg', NULL, 'All', 324902, NULL, '0293049', 1, 2, 11),
(11, '001050001', 'akeg', NULL, 'Jual', 2983498, NULL, '2942', 1, 1, 5),
(12, '0010080001', 'hai', 'Persediaan', 'Beli', 8249, '0010080001.png', '320', 1, 1, 1),
(13, '0010080002', 'hai', NULL, 'Jual', 8249, NULL, '320', 1, 1, 10),
(14, '0010080003', 'hai', NULL, 'All', 8249, NULL, '320', 1, 1, 10),
(15, '0010080004', 'hai', NULL, 'Jual', 8249, '0010080004.png', '320', 1, 1, 10),
(16, '001120001', 'lkrk', NULL, 'Jual', 82989, NULL, '329', 1, 1, 12),
(17, '001030001', 'Barangg', 'Persediaan', 'Beli', 13000, NULL, '45326', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `detail_history_request_orders`
--

CREATE TABLE `detail_history_request_orders` (
  `barang_id` int(11) NOT NULL,
  `history_request_order_id` int(11) NOT NULL,
  `harga_beli` double DEFAULT NULL,
  `kuantitas` double DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `ppn` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `detail_history_request_orders`
--

INSERT INTO `detail_history_request_orders` (`barang_id`, `history_request_order_id`, `harga_beli`, `kuantitas`, `harga_jual`, `ppn`, `subtotal`) VALUES
(6, 1, 13989, 3, 40000, 11, 133200),
(6, 2, 13989, 3, 40000, 11, 133200),
(6, 4, 13989, 3, 40000, 11, 133200);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pelanggans`
--

CREATE TABLE `detail_pelanggans` (
  `id` int(11) NOT NULL,
  `alamat` varchar(130) DEFAULT NULL,
  `kota` varchar(45) DEFAULT NULL,
  `provinsi` varchar(45) DEFAULT NULL,
  `kode_pos` varchar(45) DEFAULT NULL,
  `nama_purchasing` varchar(45) DEFAULT NULL,
  `kontak_purchasing` varchar(45) DEFAULT NULL,
  `email_purchasing` varchar(45) DEFAULT NULL,
  `nama_finance` varchar(45) DEFAULT NULL,
  `kontak_finance` varchar(45) DEFAULT NULL,
  `email_finance` varchar(45) DEFAULT NULL,
  `top` int(11) DEFAULT NULL,
  `keterangan_top` varchar(45) DEFAULT NULL,
  `pelanggan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `detail_pelanggans`
--

INSERT INTO `detail_pelanggans` (`id`, `alamat`, `kota`, `provinsi`, `kode_pos`, `nama_purchasing`, `kontak_purchasing`, `email_purchasing`, `nama_finance`, `kontak_finance`, `email_finance`, `top`, `keterangan_top`, `pelanggan_id`) VALUES
(6, 'Jl. Dummy Detail No.12345', 'Tabanan', 'Bali', '', 'purchasing', '12345', '', 'finance', '12345', '', 2, NULL, 7),
(7, 'Jl. Blabla', 'Surabaya', 'Jawa Timur', '12345', NULL, NULL, NULL, NULL, NULL, NULL, 5, 'setelah order', 7),
(8, 'ijrgaijg', 'Jembrana', 'Bali', '1234', '', '', '', '', '', '', 11, '', 7),
(16, 'agkw', NULL, NULL, '', '', '', '', '', '', '', 4, '', 10),
(17, 'abcs', 'Gianyar', 'Bali', '', '', '', '', '', '', '', 13, '', 10),
(18, 'abks', 'Jembrana', 'Bali', '', '', '', '', '', '', '', 5, '', 10),
(20, 'lbgu', 'Badung', 'Bali', '', '', '', '', '', '', '', 5, '', 7),
(21, 'Jl. Alamat 1304', 'Aceh Tenggara', 'Aceh', '', '', '', '', '', '', '', 5, '', 11),
(22, 'Jl. Alamat 1202324', 'Aceh Timur', 'Aceh', '', '', '', '', '', '', '', 4, '', 12),
(23, 'Jl. Alamat 13 es2', 'Aceh Singkil', 'Aceh', '', '', '', '', '', '', '', 2, '', 12);

-- --------------------------------------------------------

--
-- Table structure for table `detail_penawaran_barangs`
--

CREATE TABLE `detail_penawaran_barangs` (
  `barang_id` int(11) NOT NULL,
  `penawaran_barang_id` int(11) NOT NULL,
  `kuantitas` double DEFAULT NULL,
  `harga_beli` double DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `ppn` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `detail_penawaran_barangs`
--

INSERT INTO `detail_penawaran_barangs` (`barang_id`, `penawaran_barang_id`, `kuantitas`, `harga_beli`, `harga_jual`, `ppn`, `subtotal`) VALUES
(4, 7, 13, NULL, 12000, 11, 173160),
(4, 25, 12, NULL, 15000, 11, 199800),
(4, 26, 2, NULL, 12000, 11, 26640),
(4, 29, 5, 100000, 40000, 11, 222000),
(4, 30, 10, 100000, 15000, 11, 166500),
(5, 25, 10, NULL, 40000, 11, 444000),
(5, 28, 5, 1000, 15000, 11, 83250),
(8, 28, 10, 392049, 20000, 11, 222000);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pipeline_marketings`
--

CREATE TABLE `detail_pipeline_marketings` (
  `barang_id` int(11) NOT NULL,
  `pipeline_marketing_id` int(11) NOT NULL,
  `kuantitas` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `detail_pipeline_marketings`
--

INSERT INTO `detail_pipeline_marketings` (`barang_id`, `pipeline_marketing_id`, `kuantitas`) VALUES
(4, 3, 3),
(4, 12, 2),
(4, 19, 5),
(4, 20, 5),
(4, 21, 13),
(4, 22, 13),
(4, 23, 4),
(5, 13, 5),
(5, 15, 5),
(5, 16, 2),
(5, 17, 2),
(5, 18, 2),
(5, 19, 3),
(5, 20, 3),
(5, 21, 4),
(5, 22, 4),
(6, 24, 3),
(8, 17, 5),
(8, 18, 5);

-- --------------------------------------------------------

--
-- Table structure for table `detail_request_orders`
--

CREATE TABLE `detail_request_orders` (
  `barang_id` int(11) NOT NULL,
  `request_order_id` int(11) NOT NULL,
  `harga_beli` double DEFAULT NULL,
  `kuantitas` double DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `ppn` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `detail_request_orders`
--

INSERT INTO `detail_request_orders` (`barang_id`, `request_order_id`, `harga_beli`, `kuantitas`, `harga_jual`, `ppn`, `subtotal`) VALUES
(4, 2, NULL, 3, 12000, 11, 39960),
(4, 11, NULL, 2, 13000, 11, 28860),
(4, 20, NULL, 2, 1200, 11, 2664),
(4, 26, 100000, 5, 40000, 11, 222000),
(4, 27, 100000, 13, 15000, 11, 216450),
(4, 28, 100000, 4, 15000, 11, 66600),
(4, 29, 100000, 5, 12000, 11, 66600),
(5, 23, NULL, 5, 135732, 11, 753313),
(5, 24, NULL, 3, 15500, 11, 51615),
(5, 25, 1000, 7, 15000, 11, 116550),
(5, 26, 1000, 3, 50000, 11, 166500),
(5, 27, 1000, 4, 40000, 11, 177600),
(6, 29, 13989, 5, 40000, 11, 222000),
(8, 25, 392049, 9, 20000, 11, 199800),
(11, 24, NULL, 2, 1400000, 0, 3108000),
(13, 24, NULL, 5, 10000, 11, 55500);

-- --------------------------------------------------------

--
-- Table structure for table `detail_surat_jalans`
--

CREATE TABLE `detail_surat_jalans` (
  `barang_id` int(11) NOT NULL,
  `surat_jalan_id` int(11) NOT NULL,
  `kuantitas` double DEFAULT NULL,
  `harga_beli` double DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `ppn` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `detail_surat_jalans`
--

INSERT INTO `detail_surat_jalans` (`barang_id`, `surat_jalan_id`, `kuantitas`, `harga_beli`, `harga_jual`, `ppn`, `subtotal`) VALUES
(4, 9, 3, NULL, 12000, 11, 39960),
(4, 12, 5, 100000, 40000, 11, 222000),
(4, 14, 2, 100000, 15000, 11, 33300),
(5, 8, 5, NULL, 40000, 11, 222000),
(5, 11, 3, 1000, 15000, 11, 49950),
(5, 12, 3, 1000, 50000, 11, 166500),
(5, 13, 5, 1000, 15000, 11, 83250),
(5, 14, 1, 1000, 40000, 11, 44400),
(8, 11, 4, 392049, 20000, 11, 88800);

-- --------------------------------------------------------

--
-- Table structure for table `divisis`
--

CREATE TABLE `divisis` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `divisis`
--

INSERT INTO `divisis` (`id`, `nama`) VALUES
(1, 'Marketing'),
(3, 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `harga_barangs`
--

CREATE TABLE `harga_barangs` (
  `barang_id` int(11) NOT NULL,
  `detail_pelanggan_id` int(11) NOT NULL,
  `harga_jual` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `harga_barangs`
--

INSERT INTO `harga_barangs` (`barang_id`, `detail_pelanggan_id`, `harga_jual`) VALUES
(4, 16, 2100),
(4, 21, 40000),
(4, 22, 15000),
(4, 23, 12000),
(5, 6, 15000),
(5, 18, 50000),
(5, 21, 50000),
(5, 22, 40000),
(6, 17, 5000),
(6, 18, 6000),
(6, 23, 40000),
(8, 6, 20000),
(11, 18, 14000);

-- --------------------------------------------------------

--
-- Table structure for table `history_request_orders`
--

CREATE TABLE `history_request_orders` (
  `id` int(11) NOT NULL,
  `diskon` double DEFAULT NULL,
  `biaya_tambahan` double DEFAULT NULL,
  `tanggal_kirim` date DEFAULT NULL,
  `no_po` varchar(45) DEFAULT NULL,
  `tanggal_po` date DEFAULT NULL,
  `tanggal_konfirmasi` date DEFAULT NULL,
  `request_order_id` int(11) NOT NULL,
  `detail_pelanggan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `history_request_orders`
--

INSERT INTO `history_request_orders` (`id`, `diskon`, `biaya_tambahan`, `tanggal_kirim`, `no_po`, `tanggal_po`, `tanggal_konfirmasi`, `request_order_id`, `detail_pelanggan_id`) VALUES
(1, 0, 0, '2023-05-23', '1212', '2023-05-19', '2023-05-02', 29, 23),
(2, 0, 0, '2023-05-23', '1214', '2023-05-19', '2023-05-02', 29, 23),
(4, 0, 0, '2023-05-23', '1214', '2023-05-19', NULL, 29, 23);

-- --------------------------------------------------------

--
-- Table structure for table `kategori_barangs`
--

CREATE TABLE `kategori_barangs` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `kategori_barangs`
--

INSERT INTO `kategori_barangs` (`id`, `kode`, `nama`) VALUES
(1, '001', 'Kategori Dummy'),
(2, '002', 'COba'),
(3, '003', NULL),
(4, '004', 'hai');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggans`
--

CREATE TABLE `pelanggans` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `badan_usaha` varchar(45) DEFAULT NULL,
  `nama_perusahaan` varchar(45) DEFAULT NULL,
  `kontak_perusahaan` varchar(45) DEFAULT NULL,
  `alamat` varchar(130) DEFAULT NULL,
  `kota` varchar(45) DEFAULT NULL,
  `provinsi` varchar(45) DEFAULT NULL,
  `kode_pos` varchar(45) DEFAULT NULL,
  `nama_direktur` varchar(45) DEFAULT NULL,
  `kontak_direktur` varchar(45) DEFAULT NULL,
  `nama_pelanggan` varchar(45) DEFAULT NULL,
  `kontak_pelanggan` varchar(45) DEFAULT NULL,
  `ktp` varchar(45) DEFAULT NULL,
  `npwp` varchar(45) DEFAULT NULL,
  `status_piutang` varchar(45) DEFAULT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pelanggans`
--

INSERT INTO `pelanggans` (`id`, `kode`, `badan_usaha`, `nama_perusahaan`, `kontak_perusahaan`, `alamat`, `kota`, `provinsi`, `kode_pos`, `nama_direktur`, `kontak_direktur`, `nama_pelanggan`, `kontak_pelanggan`, `ktp`, `npwp`, `status_piutang`, `marketing_id`) VALUES
(7, 'DUM1', 'PT', 'Dummy', '10231920', 'Jl. Dummy Umum', 'Paser', 'Kalimantan Timur', '1231', 'direktur', '0123919', 'pelanggan', NULL, '12931010', '12931010', 'Lancar', 1),
(8, 'DUM2', 'CV', 'Halo', '12309488', NULL, 'Denpasar', 'Bali', '2939', NULL, NULL, NULL, NULL, NULL, NULL, 'Lancar', 1),
(9, 'DUM3', 'Perum', 'Dana Untuk Masyarakat', '123456', 'arjpaojrg', 'Lebak', 'Banten', '', '', '', 'absc', '21345', '', '', 'Lancar', 1),
(10, 'B1', 'UD', 'Blabal', '1234556', 'ABr e23', 'Lebak', 'Banten', '', '', '', 'Nabsi', '13094', '', '', 'Lancar', 1),
(11, 'HC1', 'PT', 'Halo Coba', '2308324', 'Jl. Alamat 1203', 'Aceh Singkil', 'Aceh', '', '', '', 'Adi', '420', '', '', 'Lancar', 6),
(12, 'PD1', 'PT', 'Perusahaan Dummy', '12345', 'Jl. Alamat 1203', 'Simeulue', 'Aceh', '', '', '', 'Pelangan', '123414', '', '', 'Lancar', 15);

-- --------------------------------------------------------

--
-- Table structure for table `penawaran_barangs`
--

CREATE TABLE `penawaran_barangs` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `diskon` double DEFAULT NULL,
  `biaya_tambahan` double DEFAULT NULL,
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `detail_pelanggan_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `penawaran_barangs`
--

INSERT INTO `penawaran_barangs` (`id`, `kode`, `diskon`, `biaya_tambahan`, `tanggal_dibuat`, `detail_pelanggan_id`, `marketing_id`) VALUES
(7, 'PWR/IT/2023/04/0001', 1000, 10000, '2023-04-06', 6, 1),
(8, 'PWR/IT/2023/04/0002', 0, 0, '2023-04-10', 6, 1),
(11, 'PWR/IT/2023/04/0003', 0, 0, '2023-04-10', 6, 1),
(12, 'PWR/IT/2023/04/0004', 100, 12010, '2023-04-10', 6, 1),
(18, 'PWR/IT/2023/04/0005', 0, 0, '2023-04-10', 6, 1),
(21, 'PWR/IT/2023/04/0006', 12000, 1000, '2023-04-10', 6, 1),
(23, 'PWR/IT/2023/04/0007', 0, 0, '2023-04-10', 6, 1),
(25, 'PWR/IT/2023/04/0008', 0, 0, '2023-04-10', 6, 1),
(26, 'PWR/IT/2023/04/0009', 0, 0, '2023-04-13', 7, 1),
(27, 'PWR/IT/2023/04/0010', 0, 0, '2023-04-13', 7, 1),
(28, 'PWR/IT/2023/04/0011', 1000, 0, '2023-04-26', 6, 1),
(29, 'PWR/BS1/2023/04/0001', 4000, 13000, '2023-04-28', 21, 14),
(30, 'PWR/JN1/2023/05/0001', 0, 0, '2023-05-01', 22, 15);

-- --------------------------------------------------------

--
-- Table structure for table `pipeline_marketings`
--

CREATE TABLE `pipeline_marketings` (
  `id` int(11) NOT NULL,
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `tanggal_survey` date DEFAULT NULL,
  `tanggal_instalasi` date DEFAULT NULL,
  `status_pelanggan` varchar(45) DEFAULT NULL,
  `detail_pelanggan_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL,
  `request_order_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pipeline_marketings`
--

INSERT INTO `pipeline_marketings` (`id`, `tanggal_dibuat`, `tanggal_survey`, `tanggal_instalasi`, `status_pelanggan`, `detail_pelanggan_id`, `marketing_id`, `request_order_id`) VALUES
(3, '2023-04-06', '2023-03-29', '2023-04-08', 'Progress', 6, 1, NULL),
(4, '2023-04-08', '2023-04-04', '2023-04-03', 'Progress', 6, 1, NULL),
(6, '2023-04-12', '2023-03-29', '2023-04-08', 'Order', 6, 1, 11),
(12, '2023-04-12', '2023-03-29', '2023-04-08', 'Order', 6, 1, 20),
(13, '2023-04-13', '0000-00-00', '2023-04-18', 'Prepare', 6, 1, NULL),
(14, '2023-04-13', '0000-00-00', '2023-04-12', 'Progress', 7, 1, NULL),
(15, '2023-04-13', '0000-00-00', '2023-04-18', 'Order', 6, 1, NULL),
(16, '2023-04-14', '0000-00-00', '2023-04-18', 'Installed', 6, 1, 24),
(17, '2023-04-26', '2023-04-13', '2023-04-18', 'Prepare', 6, 1, NULL),
(18, '2023-04-26', '2023-04-13', '2023-04-18', 'Installed', 6, 1, 25),
(19, '2023-04-28', '0000-00-00', '2023-04-27', 'Survey', 21, 14, NULL),
(20, '2023-04-28', '0000-00-00', '2023-04-27', 'Installed', 21, 14, 26),
(21, '2023-05-01', '0000-00-00', '2023-05-17', 'Progress', 22, 15, NULL),
(22, '2023-05-01', '0000-00-00', '2023-05-17', 'Installed', 22, 15, 27),
(23, '2023-05-02', '0000-00-00', '2023-05-17', 'Installed', 22, 15, 28),
(24, '2023-05-02', NULL, NULL, 'Installed', 23, 15, 29);

-- --------------------------------------------------------

--
-- Table structure for table `ppns`
--

CREATE TABLE `ppns` (
  `id` int(11) NOT NULL,
  `jumlah` double DEFAULT NULL,
  `aktif` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `ppns`
--

INSERT INTO `ppns` (`id`, `jumlah`, `aktif`) VALUES
(1, 11, 1);

-- --------------------------------------------------------

--
-- Table structure for table `request_orders`
--

CREATE TABLE `request_orders` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `diskon` double DEFAULT NULL,
  `biaya_tambahan` double DEFAULT NULL,
  `tanggal_kirim` date DEFAULT NULL,
  `no_po` varchar(45) DEFAULT NULL,
  `tanggal_po` date DEFAULT NULL,
  `file_po` varchar(45) DEFAULT NULL,
  `aktif` tinyint(4) DEFAULT NULL,
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `tanggal_konfirmasi` date DEFAULT NULL,
  `detail_pelanggan_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `request_orders`
--

INSERT INTO `request_orders` (`id`, `kode`, `diskon`, `biaya_tambahan`, `tanggal_kirim`, `no_po`, `tanggal_po`, `file_po`, `aktif`, `tanggal_dibuat`, `tanggal_konfirmasi`, `detail_pelanggan_id`, `marketing_id`, `manager_id`) VALUES
(2, 'RO/IT/2023/04/0001', 5000, 0, '2023-04-05', '8249', '2023-04-12', 'RO_IT_2023_04_0001.pdf', 1, '2023-04-08', '2023-04-26', 6, 1, 1),
(3, 'RO/IT/2023/04/0002', 0, 0, '2023-04-06', '82493', '2023-04-12', NULL, 1, '2023-04-08', NULL, 6, 1, 1),
(4, 'RO/IT/2023/04/0003', 0, 0, '2023-04-05', '82493', '2023-04-12', NULL, 1, '2023-04-08', NULL, 6, 1, NULL),
(5, 'RO/IT/2023/04/0004', 0, 0, '2023-04-05', '82493', '2023-04-12', 'RO_IT_2023_04_0004.png', 1, '2023-04-08', NULL, 6, 1, NULL),
(6, 'RO/IT/2023/04/0005', 0, 0, '2023-04-06', '89483', '2023-04-03', 'RO_IT_2023_04_0005.pdf', 1, '2023-04-08', NULL, 6, 1, NULL),
(7, 'RO/IT/2023/04/0006', 0, 0, '2023-04-04', '', '0000-00-00', NULL, 1, '2023-04-08', '2023-04-26', 6, 1, 1),
(8, 'RO/IT/2023/04/0007', 0, 0, '2023-04-12', '', '0000-00-00', NULL, 1, '2023-04-08', '2023-04-26', 6, 1, 1),
(11, 'RO/IT/2023/04/0008', 0, 0, '2023-04-11', '123', '2023-04-03', 'RO_IT_2023_04_0008.pdf', 1, '2023-04-12', NULL, 6, 1, NULL),
(20, 'RO/IT/2023/04/0009', 0, 0, '2023-04-04', '23', '2023-04-09', 'RO_IT_2023_04_0009.pdf', 1, '2023-04-12', NULL, 6, 1, NULL),
(23, 'RO/IT/2023/04/0010', 0, 0, '2023-04-18', '345', '2023-04-27', 'RO_IT_2023_04_0010.pdf', 1, '2023-04-13', NULL, 6, 1, NULL),
(24, 'RO/IT/2023/04/0011', 13000, 15000, '2023-04-19', '1234', '2023-04-19', NULL, 1, '2023-04-14', '2023-04-26', 6, 1, 1),
(25, 'RO/IT/2023/04/0012', 50000, 10000, '2023-04-14', '17266', '2023-04-28', NULL, 1, '2023-04-26', '2023-04-26', 6, 1, 1),
(26, 'RO/BS1/2023/04/0001', 4000, 15000, '2023-05-06', '14030', '2023-05-10', 'RO_BS1_2023_04_0001.pdf', 1, '2023-04-28', '2023-04-28', 21, 14, 14),
(27, 'RO/JN1/2023/05/0001', 0, 0, '2023-05-12', '1234', '2023-05-11', 'RO_JN1_2023_05_0001.pdf', 1, '2023-05-01', '2023-05-01', 22, 15, NULL),
(28, 'RO/JN1/2023/05/0002', 0, 0, '2023-05-12', '1212', '2023-05-26', NULL, 1, '2023-05-02', NULL, 22, 15, NULL),
(29, 'RO/JN1/2023/05/0003', 4000, 0, '2023-05-23', '1214', '2023-05-19', NULL, 1, '2023-05-02', NULL, 23, 15, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nama`) VALUES
(1, 'Superadmin'),
(2, 'Direktur'),
(3, 'Manager'),
(4, 'Staff');

-- --------------------------------------------------------

--
-- Table structure for table `satuans`
--

CREATE TABLE `satuans` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `satuans`
--

INSERT INTO `satuans` (`id`, `kode`, `nama`) VALUES
(1, '01', 'dummy'),
(2, '02', 'ea'),
(3, '03', 'pcs'),
(4, '04', 'set'),
(5, '05', 'meter'),
(6, '06', 'batang'),
(7, '07', 'lbr'),
(8, '08', 'tabung'),
(10, '008', 'coba'),
(11, '11', 'kv'),
(12, '12', 'coba'),
(13, '13', 'tes');

-- --------------------------------------------------------

--
-- Table structure for table `surat_jalans`
--

CREATE TABLE `surat_jalans` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `diskon` double DEFAULT NULL,
  `biaya_tambahan` double DEFAULT NULL,
  `tanggal_kirim` date DEFAULT NULL,
  `nama_driver` varchar(45) DEFAULT NULL,
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `request_order_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `surat_jalans`
--

INSERT INTO `surat_jalans` (`id`, `kode`, `diskon`, `biaya_tambahan`, `tanggal_kirim`, `nama_driver`, `tanggal_dibuat`, `request_order_id`, `marketing_id`) VALUES
(2, 'SJ/IT/2023/04/0001', NULL, NULL, '2023-04-11', 'Driver Dumm', '2023-04-10', 3, 1),
(7, 'SJ/IT/2023/04/0002', NULL, NULL, '2023-04-27', '', '2023-04-10', 6, 1),
(8, 'SJ/2023/04/0003', 42000, 0, '2023-04-06', 'hai', '2023-04-13', 2, 1),
(9, 'SJ/2023/04/0004', NULL, NULL, '2023-04-05', '', '2023-04-13', 2, 1),
(10, 'SJ/2023/04/0005', NULL, NULL, '2023-04-05', '', '2023-04-13', 2, 1),
(11, 'SJ/2023/04/0006', 50000, 10000, '2023-04-14', 'adi', '2023-04-27', 25, 1),
(12, 'SJ/2023/04/0007', 4000, 15000, '2023-05-06', 'driver', '2023-04-28', 26, 14),
(13, 'SJ/2023/04/0008', 5000, 0, '2023-04-05', '', '2023-04-28', 2, 1),
(14, 'SJ/2023/05/0001', 0, 0, '2023-05-12', '', '2023-05-01', 27, 15);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `username` varchar(45) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `aktif` tinyint(4) DEFAULT 1,
  `role_id` int(11) NOT NULL,
  `divisi_id` int(11) DEFAULT NULL,
  `atasan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `kode`, `username`, `nama`, `password`, `aktif`, `role_id`, `divisi_id`, `atasan_id`) VALUES
(1, 'IT', 'superadmin', 'IT Superadmin', '$2y$10$aCrVmUYd7u9o20mI0E2OEeXTLhQCJqhJXtHm/O1BpPnQ.Ndn1oNDK', 1, 1, NULL, NULL),
(2, 'DUM', 'marketing', 'Marketing Dummy', '$2y$10$4a7OWGm3soya4JcDVUm/buRUCJ4Ftml1epW1SJiynwH5z2Sc5Jct2', 1, 3, 1, NULL),
(5, 'coba', 'coba', 'coba', '$2y$10$4a7OWGm3soya4JcDVUm/buRUCJ4Ftml1epW1SJiynwH5z2Sc5Jct2', 0, 4, 1, 1),
(6, 'HD1', 'halodummy', 'Halo Dummy', '$2y$10$4a7OWGm3soya4JcDVUm/buRUCJ4Ftml1epW1SJiynwH5z2Sc5Jct2', 1, 4, 1, 1),
(7, 'HD2', 'haidommy', 'Hai Dommy', '$2y$10$4a7OWGm3soya4JcDVUm/buRUCJ4Ftml1epW1SJiynwH5z2Sc5Jct2', 0, 4, 1, 2),
(10, 'HB1', 'haloo', 'Haia Bass', '$2y$10$4a7OWGm3soya4JcDVUm/buRUCJ4Ftml1epW1SJiynwH5z2Sc5Jct2', 1, 4, 1, 2),
(14, 'BS1', 'budisan', 'Budi Santoso', '$2y$10$4a7OWGm3soya4JcDVUm/buRUCJ4Ftml1epW1SJiynwH5z2Sc5Jct2', 1, 3, 1, 1),
(15, 'JN1', 'jabeshnehemiah', 'Jabesh Nehemiah', '$2y$10$z842LITyuJltvVbGr4GWSOnipW/km6AhkjiuFxMeDsOsvhm5uJqXC', 1, 3, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barangs`
--
ALTER TABLE `barangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`),
  ADD KEY `fk_barangs_kategori_barangs_idx` (`kategori_barang_id`),
  ADD KEY `fk_barangs_satuans1_idx` (`satuan_id`);

--
-- Indexes for table `detail_history_request_orders`
--
ALTER TABLE `detail_history_request_orders`
  ADD PRIMARY KEY (`barang_id`,`history_request_order_id`),
  ADD KEY `fk_barangs_has_history_request_orders_history_request_order_idx` (`history_request_order_id`),
  ADD KEY `fk_barangs_has_history_request_orders_barangs1_idx` (`barang_id`);

--
-- Indexes for table `detail_pelanggans`
--
ALTER TABLE `detail_pelanggans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detail_pelanggans_pelanggans1_idx` (`pelanggan_id`);

--
-- Indexes for table `detail_penawaran_barangs`
--
ALTER TABLE `detail_penawaran_barangs`
  ADD PRIMARY KEY (`barang_id`,`penawaran_barang_id`),
  ADD KEY `fk_barangs_has_penawaran_barangs_penawaran_barangs1_idx` (`penawaran_barang_id`),
  ADD KEY `fk_barangs_has_penawaran_barangs_barangs1_idx` (`barang_id`);

--
-- Indexes for table `detail_pipeline_marketings`
--
ALTER TABLE `detail_pipeline_marketings`
  ADD PRIMARY KEY (`barang_id`,`pipeline_marketing_id`),
  ADD KEY `fk_barangs_has_pipeline_marketings_pipeline_marketings1_idx` (`pipeline_marketing_id`),
  ADD KEY `fk_barangs_has_pipeline_marketings_barangs1_idx` (`barang_id`);

--
-- Indexes for table `detail_request_orders`
--
ALTER TABLE `detail_request_orders`
  ADD PRIMARY KEY (`barang_id`,`request_order_id`),
  ADD KEY `fk_barangs_has_request_orders_request_orders1_idx` (`request_order_id`),
  ADD KEY `fk_barangs_has_request_orders_barangs1_idx` (`barang_id`);

--
-- Indexes for table `detail_surat_jalans`
--
ALTER TABLE `detail_surat_jalans`
  ADD PRIMARY KEY (`barang_id`,`surat_jalan_id`),
  ADD KEY `fk_barangs_has_surat_jalans_surat_jalans1_idx` (`surat_jalan_id`),
  ADD KEY `fk_barangs_has_surat_jalans_barangs1_idx` (`barang_id`);

--
-- Indexes for table `divisis`
--
ALTER TABLE `divisis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `harga_barangs`
--
ALTER TABLE `harga_barangs`
  ADD PRIMARY KEY (`barang_id`,`detail_pelanggan_id`),
  ADD KEY `fk_barangs_has_detail_pelanggans_detail_pelanggans1_idx` (`detail_pelanggan_id`),
  ADD KEY `fk_barangs_has_detail_pelanggans_barangs1_idx` (`barang_id`);

--
-- Indexes for table `history_request_orders`
--
ALTER TABLE `history_request_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_history_request_orders_request_orders1_idx` (`request_order_id`),
  ADD KEY `fk_history_request_orders_detail_pelanggans1_idx` (`detail_pelanggan_id`);

--
-- Indexes for table `kategori_barangs`
--
ALTER TABLE `kategori_barangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`);

--
-- Indexes for table `pelanggans`
--
ALTER TABLE `pelanggans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`),
  ADD KEY `fk_pelanggans_users1_idx` (`marketing_id`);

--
-- Indexes for table `penawaran_barangs`
--
ALTER TABLE `penawaran_barangs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`),
  ADD KEY `fk_penawaran_barangs_detail_pelanggans1_idx` (`detail_pelanggan_id`),
  ADD KEY `fk_penawaran_barangs_users1_idx` (`marketing_id`);

--
-- Indexes for table `pipeline_marketings`
--
ALTER TABLE `pipeline_marketings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pipeline_marketings_detail_pelanggans1_idx` (`detail_pelanggan_id`),
  ADD KEY `fk_pipeline_marketings_users1_idx` (`marketing_id`),
  ADD KEY `fk_pipeline_marketings_request_orders1_idx` (`request_order_id`);

--
-- Indexes for table `ppns`
--
ALTER TABLE `ppns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `request_orders`
--
ALTER TABLE `request_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`),
  ADD KEY `fk_request_orders_detail_pelanggans1_idx` (`detail_pelanggan_id`),
  ADD KEY `fk_request_orders_users1_idx` (`marketing_id`),
  ADD KEY `fk_request_orders_users2_idx` (`manager_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `satuans`
--
ALTER TABLE `satuans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`);

--
-- Indexes for table `surat_jalans`
--
ALTER TABLE `surat_jalans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`),
  ADD KEY `fk_surat_jalans_users1_idx` (`marketing_id`),
  ADD KEY `fk_surat_jalans_request_orders1_idx` (`request_order_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`),
  ADD UNIQUE KEY `username_UNIQUE` (`username`),
  ADD KEY `fk_users_roles1_idx` (`role_id`),
  ADD KEY `fk_users_users1_idx` (`atasan_id`),
  ADD KEY `fk_users_divisis1_idx` (`divisi_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barangs`
--
ALTER TABLE `barangs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `detail_pelanggans`
--
ALTER TABLE `detail_pelanggans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `divisis`
--
ALTER TABLE `divisis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `history_request_orders`
--
ALTER TABLE `history_request_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kategori_barangs`
--
ALTER TABLE `kategori_barangs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pelanggans`
--
ALTER TABLE `pelanggans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `penawaran_barangs`
--
ALTER TABLE `penawaran_barangs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pipeline_marketings`
--
ALTER TABLE `pipeline_marketings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `ppns`
--
ALTER TABLE `ppns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request_orders`
--
ALTER TABLE `request_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `satuans`
--
ALTER TABLE `satuans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `surat_jalans`
--
ALTER TABLE `surat_jalans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangs`
--
ALTER TABLE `barangs`
  ADD CONSTRAINT `fk_barangs_kategori_barangs` FOREIGN KEY (`kategori_barang_id`) REFERENCES `kategori_barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_barangs_satuans1` FOREIGN KEY (`satuan_id`) REFERENCES `satuans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `detail_history_request_orders`
--
ALTER TABLE `detail_history_request_orders`
  ADD CONSTRAINT `fk_barangs_has_history_request_orders_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_barangs_has_history_request_orders_history_request_orders1` FOREIGN KEY (`history_request_order_id`) REFERENCES `history_request_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `detail_pelanggans`
--
ALTER TABLE `detail_pelanggans`
  ADD CONSTRAINT `fk_detail_pelanggans_pelanggans1` FOREIGN KEY (`pelanggan_id`) REFERENCES `pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `detail_penawaran_barangs`
--
ALTER TABLE `detail_penawaran_barangs`
  ADD CONSTRAINT `fk_barangs_has_penawaran_barangs_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_barangs_has_penawaran_barangs_penawaran_barangs1` FOREIGN KEY (`penawaran_barang_id`) REFERENCES `penawaran_barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `detail_pipeline_marketings`
--
ALTER TABLE `detail_pipeline_marketings`
  ADD CONSTRAINT `fk_barangs_has_pipeline_marketings_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_barangs_has_pipeline_marketings_pipeline_marketings1` FOREIGN KEY (`pipeline_marketing_id`) REFERENCES `pipeline_marketings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `detail_request_orders`
--
ALTER TABLE `detail_request_orders`
  ADD CONSTRAINT `fk_barangs_has_request_orders_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_barangs_has_request_orders_request_orders1` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `detail_surat_jalans`
--
ALTER TABLE `detail_surat_jalans`
  ADD CONSTRAINT `fk_barangs_has_surat_jalans_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_barangs_has_surat_jalans_surat_jalans1` FOREIGN KEY (`surat_jalan_id`) REFERENCES `surat_jalans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `harga_barangs`
--
ALTER TABLE `harga_barangs`
  ADD CONSTRAINT `fk_barangs_has_detail_pelanggans_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_barangs_has_detail_pelanggans_detail_pelanggans1` FOREIGN KEY (`detail_pelanggan_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `history_request_orders`
--
ALTER TABLE `history_request_orders`
  ADD CONSTRAINT `fk_history_request_orders_detail_pelanggans1` FOREIGN KEY (`detail_pelanggan_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_history_request_orders_request_orders1` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pelanggans`
--
ALTER TABLE `pelanggans`
  ADD CONSTRAINT `fk_pelanggans_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `penawaran_barangs`
--
ALTER TABLE `penawaran_barangs`
  ADD CONSTRAINT `fk_penawaran_barangs_detail_pelanggans1` FOREIGN KEY (`detail_pelanggan_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_penawaran_barangs_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `pipeline_marketings`
--
ALTER TABLE `pipeline_marketings`
  ADD CONSTRAINT `fk_pipeline_marketings_detail_pelanggans1` FOREIGN KEY (`detail_pelanggan_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pipeline_marketings_request_orders1` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pipeline_marketings_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `request_orders`
--
ALTER TABLE `request_orders`
  ADD CONSTRAINT `fk_request_orders_detail_pelanggans1` FOREIGN KEY (`detail_pelanggan_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_request_orders_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_request_orders_users2` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `surat_jalans`
--
ALTER TABLE `surat_jalans`
  ADD CONSTRAINT `fk_surat_jalans_request_orders1` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_surat_jalans_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_divisis1` FOREIGN KEY (`divisi_id`) REFERENCES `divisis` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_roles1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_users1` FOREIGN KEY (`atasan_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
