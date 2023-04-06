-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 06, 2023 at 11:06 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `detail_pelanggans`
--

INSERT INTO `detail_pelanggans` (`id`, `provinsi`, `kota`, `alamat`, `kode_pos`, `nama_purchasing`, `kontak_purchasing`, `email_purchasing`, `nama_finance`, `kontak_finance`, `email_finance`, `harga_jual`, `top`, `pelanggan_id`) VALUES
(6, 'Bali', 'Tabanan', 'Jl. Dummy', '', 'purchasing', '12345', '', 'finance', '12345', '', 100000, 2, 7);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pelanggans`
--

INSERT INTO `pelanggans` (`id`, `kode`, `nama_perusahaan`, `kontak_perusahaan`, `badan_usaha`, `nama_direktur`, `kontak_direktur`, `nama_pelanggan`, `ktp`, `npwp`, `provinsi`, `kota`, `alamat`, `kode_pos`, `status_piutang`, `marketing_id`) VALUES
(7, 'DUM', 'perusahaan', '10231920', 'PT', 'direktur', '0123919', 'pelanggan', '12931010', '12931010', 'Kalimantan Timur', 'Paser', 'Jl. Dummy', '1231', 'Lancar', 1);

-- --------------------------------------------------------

--
-- Table structure for table `penawaran_barangs`
--

CREATE TABLE `penawaran_barangs` (
  `id` int(11) NOT NULL,
  `kode` varchar(45) NOT NULL,
  `harga_jual` double DEFAULT NULL,
  `diskon` double DEFAULT NULL,
  `biaya_tambahan` double DEFAULT NULL,
  `nominal_biaya` double DEFAULT NULL,
  `tanggal_dibuat` date NOT NULL DEFAULT current_timestamp(),
  `detail_pelanggan_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `ppn_id` int(11) DEFAULT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `penawaran_barangs`
--

INSERT INTO `penawaran_barangs` (`id`, `kode`, `harga_jual`, `diskon`, `biaya_tambahan`, `nominal_biaya`, `tanggal_dibuat`, `detail_pelanggan_id`, `barang_id`, `ppn_id`, `marketing_id`) VALUES
(7, 'PWR/IT/2023/04/0001', 1000000, 1000, 10000, 100000, '2023-04-06', 6, 4, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pipeline_marketings`
--

CREATE TABLE `pipeline_marketings` (
  `id` int(11) NOT NULL,
  `pemakaian` int(11) DEFAULT NULL,
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `tanggal_survey` date DEFAULT NULL,
  `tanggal_instalasi` date DEFAULT NULL,
  `status_pelanggan` varchar(45) DEFAULT NULL,
  `detail_pelanggan_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `pipeline_marketings`
--

INSERT INTO `pipeline_marketings` (`id`, `pemakaian`, `tanggal_dibuat`, `tanggal_survey`, `tanggal_instalasi`, `status_pelanggan`, `detail_pelanggan_id`, `marketing_id`) VALUES
(3, 10, '2023-04-06', '2023-03-29', '2023-04-08', 'Progress', 6, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ppns`
--

CREATE TABLE `ppns` (
  `id` int(11) NOT NULL,
  `jumlah` int(11) DEFAULT NULL,
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
  `tanggal_dibuat` date DEFAULT current_timestamp(),
  `kuantitas` int(11) DEFAULT NULL,
  `tanggal_kirim` date DEFAULT NULL,
  `no_po` varchar(45) DEFAULT NULL,
  `tanggal_po` date DEFAULT NULL,
  `file_po` varchar(45) DEFAULT NULL,
  `aktif` tinyint(4) DEFAULT NULL,
  `detail_pelanggan_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL,
  `pipeline_marketing_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `kuantitas` int(11) DEFAULT NULL,
  `tanggal_kirim` date DEFAULT NULL,
  `nama_driver` varchar(45) DEFAULT NULL,
  `request_orders_id` int(11) NOT NULL,
  `detail_pelanggans_id` int(11) NOT NULL,
  `marketing_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `kode`, `nama`, `username`, `password`, `aktif`, `role_id`, `divisi_id`, `atasan_id`) VALUES
(1, 'IT', 'IT Superadmin', 'superadmin', 'superadmin', 1, 1, NULL, NULL),
(2, 'DUM', 'Marketing Dummy', 'marketing', 'marketing', 1, 1, 1, NULL),
(5, 'coba', 'coba', 'coba', 'password', 0, 2, NULL, 1);

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
  ADD KEY `fk_penawaran_barangs_barangs1_idx` (`barang_id`),
  ADD KEY `fk_penawaran_barangs_ppns1_idx` (`ppn_id`),
  ADD KEY `fk_penawaran_barangs_users1_idx` (`marketing_id`);

--
-- Indexes for table `pipeline_marketings`
--
ALTER TABLE `pipeline_marketings`
  ADD PRIMARY KEY (`id`),
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
  ADD KEY `fk_request_orders_barangs1_idx` (`barang_id`);

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
  ADD KEY `fk_surat_jalans_request_orders1_idx` (`request_orders_id`),
  ADD KEY `fk_surat_jalans_detail_pelanggans1_idx` (`detail_pelanggans_id`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pipeline_marketings`
--
ALTER TABLE `pipeline_marketings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ppns`
--
ALTER TABLE `ppns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `request_orders`
--
ALTER TABLE `request_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
-- Constraints for table `pelanggans`
--
ALTER TABLE `pelanggans`
  ADD CONSTRAINT `fk_pelanggans_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `penawaran_barangs`
--
ALTER TABLE `penawaran_barangs`
  ADD CONSTRAINT `fk_penawaran_barangs_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_penawaran_barangs_detail_pelanggans1` FOREIGN KEY (`detail_pelanggan_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_penawaran_barangs_ppns1` FOREIGN KEY (`ppn_id`) REFERENCES `ppns` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
  ADD CONSTRAINT `fk_request_orders_barangs1` FOREIGN KEY (`barang_id`) REFERENCES `barangs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_request_orders_detail_pelanggans1` FOREIGN KEY (`detail_pelanggan_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_request_orders_pipeline_marketings1` FOREIGN KEY (`pipeline_marketing_id`) REFERENCES `pipeline_marketings` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_request_orders_users1` FOREIGN KEY (`marketing_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `surat_jalans`
--
ALTER TABLE `surat_jalans`
  ADD CONSTRAINT `fk_surat_jalans_detail_pelanggans1` FOREIGN KEY (`detail_pelanggans_id`) REFERENCES `detail_pelanggans` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_surat_jalans_request_orders1` FOREIGN KEY (`request_orders_id`) REFERENCES `request_orders` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
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
