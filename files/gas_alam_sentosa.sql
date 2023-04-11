-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 11, 2023 at 04:16 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.9

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
  `harga_beli` double DEFAULT NULL,
  `file_gambar` varchar(45) DEFAULT NULL,
  `kode_acc` varchar(45) DEFAULT NULL,
  `kategori_barang_id` int(11) NOT NULL,
  `satuan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `barangs`
--

INSERT INTO `barangs` (`id`, `kode`, `nama`, `tipe`, `harga_beli`, `file_gambar`, `kode_acc`, `kategori_barang_id`, `satuan_id`) VALUES
(4, 'DUM', 'Dummy', 'Persediaan', 100000, NULL, '0000', 1, 1),
(5, '001110001', 'coba', 'Persediaan', 1000, '001110001.png', '134', 1, 1),
(6, '001060001', 'ruhag', 'Persediaan', 13989, NULL, '8928', 1, 1),
(7, '001070001', 'jnr', 'Jasa', 23987, NULL, '9302', 1, 1),
(8, '001080001', 'tes', NULL, 392049, NULL, '91340', 1, 8),
(9, '002110001', 'ekg', NULL, 324902, NULL, '0293049', 2, 11),
(10, '002110002', 'ekg', NULL, 324902, NULL, '0293049', 2, 11),
(11, '001050001', 'akeg', NULL, 2983498, NULL, '2942', 1, 5),
(12, '0010080001', 'hai', NULL, 8249, NULL, '320', 1, 10),
(13, '0010080002', 'hai', NULL, 8249, NULL, '320', 1, 10),
(14, '0010080003', 'hai', NULL, 8249, NULL, '320', 1, 10),
(15, '0010080004', 'hai', NULL, 8249, '0010080004.png', '320', 1, 10),
(16, '001120001', 'lkrk', NULL, 82989, NULL, '329', 1, 12);

-- --------------------------------------------------------

--
-- Table structure for table `detail_pelanggans`
--

CREATE TABLE `detail_pelanggans` (
  `id` int(11) NOT NULL,
  `provinsi` varchar(45) DEFAULT NULL,
  `kota` varchar(45) DEFAULT NULL,
  `alamat` varchar(130) DEFAULT NULL,
  `kode_pos` varchar(45) DEFAULT NULL,
  `nama_purchasing` varchar(45) DEFAULT NULL,
  `kontak_purchasing` varchar(45) DEFAULT NULL,
  `email_purchasing` varchar(45) DEFAULT NULL,
  `nama_finance` varchar(45) DEFAULT NULL,
  `kontak_finance` varchar(45) DEFAULT NULL,
  `email_finance` varchar(45) DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `top` int(11) DEFAULT NULL,
  `pelanggan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_pelanggans`
--

INSERT INTO `detail_pelanggans` (`id`, `provinsi`, `kota`, `alamat`, `kode_pos`, `nama_purchasing`, `kontak_purchasing`, `email_purchasing`, `nama_finance`, `kontak_finance`, `email_finance`, `harga_jual`, `top`, `pelanggan_id`) VALUES
(6, 'Bali', 'Tabanan', 'hrhawhrgkajgkjabrkgjawkrjgwargawhawhajtaethkjlektjklajhlknalhjw4bkbgkwrgbkawjbhkjahwh', '', 'purchasing', '12345', '', 'finance', '12345', '', 100000, 2, 7);

-- --------------------------------------------------------

--
-- Table structure for table `detail_penawaran_barangs`
--

CREATE TABLE `detail_penawaran_barangs` (
  `barang_id` int(11) NOT NULL,
  `penawaran_barang_id` int(11) NOT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `harga_jual` double DEFAULT NULL,
  `ppn` double DEFAULT NULL,
  `subtotal` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `detail_penawaran_barangs`
--

INSERT INTO `detail_penawaran_barangs` (`barang_id`, `penawaran_barang_id`, `kuantitas`, `harga_jual`, `ppn`, `subtotal`) VALUES
(4, 7, 21, 130000, 11, 3030300),
(4, 25, 12, 15000, 11, 199800),
(4, 26, 21, 14000, 0, 326340),
(4, 27, 12, 67000, 11, 892440),
(5, 25, 10, 40000, 11, 444000);

-- --------------------------------------------------------

--
-- Table structure for table `detail_request_orders`
--

CREATE TABLE `detail_request_orders` (
  `id` int(11) NOT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `request_order_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `detail_surat_jalans`
--

CREATE TABLE `detail_surat_jalans` (
  `id` int(11) NOT NULL,
  `kuantitas` int(11) DEFAULT NULL,
  `detail_request_order_id` int(11) NOT NULL,
  `surat_jalan_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `divisis`
--

CREATE TABLE `divisis` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `divisis`
--

INSERT INTO `divisis` (`id`, `nama`) VALUES
(1, 'Marketing'),
(3, 'Finance');

-- --------------------------------------------------------

--
-- Table structure for table `kategori_barangs`
--

CREATE TABLE `kategori_barangs` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `kategori_barangs`
--

INSERT INTO `kategori_barangs` (`id`, `kode`, `nama`) VALUES
(1, '001', 'Kategori Dummy'),
(2, '002', 'COba');

-- --------------------------------------------------------

--
-- Table structure for table `pelanggans`
--

CREATE TABLE `pelanggans` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `nama_perusahaan` varchar(45) DEFAULT NULL,
  `kontak_perusahaan` varchar(45) DEFAULT NULL,
  `badan_usaha` varchar(45) DEFAULT NULL,
  `nama_direktur` varchar(45) DEFAULT NULL,
  `kontak_direktur` varchar(45) DEFAULT NULL,
  `nama_pelanggan` varchar(45) DEFAULT NULL,
  `ktp` varchar(45) DEFAULT NULL,
  `npwp` varchar(45) DEFAULT NULL,
  `provinsi` varchar(45) DEFAULT NULL,
  `kota` varchar(45) DEFAULT NULL,
  `alamat` varchar(130) DEFAULT NULL,
  `kode_pos` varchar(45) DEFAULT NULL,
  `status_piutang` varchar(45) DEFAULT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pelanggans`
--

INSERT INTO `pelanggans` (`id`, `kode`, `nama_perusahaan`, `kontak_perusahaan`, `badan_usaha`, `nama_direktur`, `kontak_direktur`, `nama_pelanggan`, `ktp`, `npwp`, `provinsi`, `kota`, `alamat`, `kode_pos`, `status_piutang`, `marketing_id`) VALUES
(7, 'DUM', 'perusahaan', '10231920', 'PT', 'direktur', '0123919', 'pelanggan', '12931010', '12931010', 'Kalimantan Timur', 'Paser', 'Jl. Dummy Umum', '1231', 'Lancar', 1);

-- --------------------------------------------------------

--
-- Table structure for table `penawaran_barangs`
--

CREATE TABLE `penawaran_barangs` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `diskon` double DEFAULT NULL,
  `biaya_tambahan` double DEFAULT NULL,
  `tanggal_dibuat` date NOT NULL DEFAULT current_timestamp(),
  `detail_pelanggan_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(26, 'PWR/IT/2023/04/0009', 0, 0, '2023-04-11', 6, 1),
(27, 'PWR/IT/2023/04/0010', 0, 0, '2023-04-11', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pipeline_marketings`
--

CREATE TABLE `pipeline_marketings` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `pemakaian` int(11) DEFAULT NULL,
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `tanggal_survey` date DEFAULT NULL,
  `tanggal_instalasi` date DEFAULT NULL,
  `status_pelanggan` varchar(45) DEFAULT NULL,
  `detail_pelanggan_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pipeline_marketings`
--

INSERT INTO `pipeline_marketings` (`id`, `kode`, `pemakaian`, `tanggal_dibuat`, `tanggal_survey`, `tanggal_instalasi`, `status_pelanggan`, `detail_pelanggan_id`, `marketing_id`) VALUES
(3, 'PM/IT/2023/04/0001', 10, '2023-04-06', '2023-03-29', '2023-04-08', 'Prepare', 6, 1),
(4, 'PM/IT/2023/04/0002', 3, '2023-04-08', '2023-04-04', '2023-04-03', 'Progress', 6, 1),
(6, 'PM/IT/2023/04/0003', 12, '2023-04-11', '2023-04-19', '2023-04-23', 'Installed', 6, 1),
(7, 'PM/IT/2023/04/0004', 22, '2023-04-11', '2023-04-13', '2023-04-29', 'Uninstalled', 6, 1),
(8, 'PM/IT/2023/04/0005', 11, '2023-04-11', '2023-04-15', '2023-04-28', '', 6, 1),
(9, 'PM/IT/2023/04/0006', 21, '2023-04-11', '2023-04-07', '2023-04-21', '', 6, 1),
(10, 'PM/IT/2023/04/0007', 11, '2023-04-11', '2023-04-05', '2023-04-14', '', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ppns`
--

CREATE TABLE `ppns` (
  `id` int(11) NOT NULL,
  `jumlah` double DEFAULT NULL,
  `aktif` tinyint(4) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `kuantitas` int(11) DEFAULT NULL,
  `tanggal_kirim` date DEFAULT NULL,
  `no_po` varchar(45) DEFAULT NULL,
  `tanggal_po` date DEFAULT NULL,
  `file_po` varchar(45) DEFAULT NULL,
  `aktif` tinyint(4) DEFAULT NULL,
  `detail_pelanggan_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL,
  `pipeline_marketing_id` int(11) DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `request_orders`
--

INSERT INTO `request_orders` (`id`, `kode`, `tanggal_dibuat`, `kuantitas`, `tanggal_kirim`, `no_po`, `tanggal_po`, `file_po`, `aktif`, `detail_pelanggan_id`, `marketing_id`, `pipeline_marketing_id`, `manager_id`) VALUES
(2, 'RO/IT/2023/04/0001', '2023-04-08', 14, '2023-04-05', '82493', '2023-04-12', NULL, 1, 6, 1, 3, NULL),
(3, 'RO/IT/2023/04/0002', '2023-04-08', 12, '2023-04-05', '82493', '2023-04-12', NULL, 0, 6, 1, 3, NULL),
(4, 'RO/IT/2023/04/0003', '2023-04-08', 12, '2023-04-05', '82493', '2023-04-12', NULL, 0, 6, 1, 3, NULL),
(5, 'RO/IT/2023/04/0004', '2023-04-08', 12, '2023-04-05', '82493', '2023-04-12', 'RO_IT_2023_04_0004.png', 1, 6, 1, 3, NULL),
(6, 'RO/IT/2023/04/0005', '2023-04-08', 123, '2023-04-06', '89483', '2023-04-03', 'RO_IT_2023_04_0005.pdf', 0, 6, 1, 3, NULL),
(7, 'RO/IT/2023/04/0006', '2023-04-08', 45, '2023-04-04', '', '0000-00-00', NULL, 0, 6, 1, 3, NULL),
(8, 'RO/IT/2023/04/0007', '2023-04-08', 622, '2023-04-12', '', '0000-00-00', NULL, 1, 6, 1, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nama` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
(13, '13', 'tes'),
(14, '14', NULL),
(15, '15', NULL),
(16, '16', NULL),
(17, '17', NULL),
(18, '18', NULL),
(19, '19', NULL),
(20, '20', NULL),
(21, '21', NULL),
(22, '22', 'hei');

-- --------------------------------------------------------

--
-- Table structure for table `surat_jalans`
--

CREATE TABLE `surat_jalans` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `tanggal_kirim` date DEFAULT NULL,
  `nama_driver` varchar(45) DEFAULT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `surat_jalans`
--

INSERT INTO `surat_jalans` (`id`, `kode`, `tanggal_dibuat`, `tanggal_kirim`, `nama_driver`, `marketing_id`) VALUES
(2, 'SJ/IT/2023/04/0001', '2023-04-10', '2023-04-11', 'Driver Dumm', 1),
(7, 'SJ/IT/2023/04/0002', '2023-04-10', '2023-04-27', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `nama` varchar(45) DEFAULT NULL,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT 'password',
  `aktif` tinyint(4) DEFAULT 1,
  `role_id` int(11) NOT NULL,
  `divisi_id` int(11) DEFAULT NULL,
  `atasan_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `kode`, `nama`, `username`, `password`, `aktif`, `role_id`, `divisi_id`, `atasan_id`) VALUES
(1, 'IT', 'IT Superadmin', 'superadmin', 'superadmin', 1, 3, NULL, NULL),
(2, 'DUM', 'Marketing Dummy', 'marketing', 'marketing', 1, 1, 1, NULL),
(5, 'coba', 'coba', 'coba', 'password', 0, 4, NULL, 1);

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
-- Indexes for table `detail_request_orders`
--
ALTER TABLE `detail_request_orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detail_request_orders_request_orders1_idx` (`request_order_id`),
  ADD KEY `fk_detail_request_orders_barangs1_idx` (`barang_id`);

--
-- Indexes for table `detail_surat_jalans`
--
ALTER TABLE `detail_surat_jalans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_detail_surat_jalans_detail_request_orders1_idx` (`detail_request_order_id`),
  ADD KEY `fk_detail_surat_jalans_surat_jalans1_idx` (`surat_jalan_id`);

--
-- Indexes for table `divisis`
--
ALTER TABLE `divisis`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `kode` (`kode`),
  ADD KEY `fk_pipeline_marketings_detail_pelanggans1_idx` (`detail_pelanggan_id`),
  ADD KEY `fk_pipeline_marketings_users1_idx` (`marketing_id`);

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
  ADD KEY `fk_request_orders_pipeline_marketings1_idx` (`pipeline_marketing_id`),
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
  ADD KEY `fk_surat_jalans_users1_idx` (`marketing_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode_UNIQUE` (`kode`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `detail_pelanggans`
--
ALTER TABLE `detail_pelanggans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `detail_request_orders`
--
ALTER TABLE `detail_request_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `detail_surat_jalans`
--
ALTER TABLE `detail_surat_jalans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `divisis`
--
ALTER TABLE `divisis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kategori_barangs`
--
ALTER TABLE `kategori_barangs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pelanggans`
--
ALTER TABLE `pelanggans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `penawaran_barangs`
--
ALTER TABLE `penawaran_barangs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `pipeline_marketings`
--
ALTER TABLE `pipeline_marketings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `ppns`
--
ALTER TABLE `ppns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request_orders`
--
ALTER TABLE `request_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
-- Constraints for table `detail_request_orders`
--
ALTER TABLE `detail_request_orders`
  ADD CONSTRAINT `fk_detail_request_orders_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detail_request_orders_request_orders1` FOREIGN KEY (`request_order_id`) REFERENCES `request_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `detail_surat_jalans`
--
ALTER TABLE `detail_surat_jalans`
  ADD CONSTRAINT `fk_detail_surat_jalans_detail_request_orders1` FOREIGN KEY (`detail_request_order_id`) REFERENCES `detail_request_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_detail_surat_jalans_surat_jalans1` FOREIGN KEY (`surat_jalan_id`) REFERENCES `surat_jalans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

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
  ADD CONSTRAINT `fk_pipeline_marketings_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `request_orders`
--
ALTER TABLE `request_orders`
  ADD CONSTRAINT `fk_request_orders_detail_pelanggans1` FOREIGN KEY (`detail_pelanggan_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_request_orders_pipeline_marketings1` FOREIGN KEY (`pipeline_marketing_id`) REFERENCES `pipeline_marketings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_request_orders_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_request_orders_users2` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `surat_jalans`
--
ALTER TABLE `surat_jalans`
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
