-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.13 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table proyek.bahan_bakar
CREATE TABLE IF NOT EXISTS `bahan_bakar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_bahan_bakar` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.bahan_bakar: ~6 rows (approximately)
/*!40000 ALTER TABLE `bahan_bakar` DISABLE KEYS */;
INSERT INTO `bahan_bakar` (`id`, `nama_bahan_bakar`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Bio Solar', '2021-07-23 13:28:20', '2021-07-23 13:30:48', NULL);
/*!40000 ALTER TABLE `bahan_bakar` ENABLE KEYS */;

-- Dumping structure for table proyek.cabang
CREATE TABLE IF NOT EXISTS `cabang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_cabang` varchar(191) DEFAULT NULL,
  `alamat_cabang` varchar(191) DEFAULT NULL,
  `email_cabang` varchar(191) DEFAULT NULL,
  `telp_cabang` varchar(30) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- Dumping data for table proyek.cabang: ~1 rows (approximately)
/*!40000 ALTER TABLE `cabang` DISABLE KEYS */;
INSERT INTO `cabang` (`id`, `nama_cabang`, `alamat_cabang`, `email_cabang`, `telp_cabang`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Cabang Bekasi', 'Bekasi Timur III', 'bekasi@example.com', '04294829', '2021-07-22 19:22:10', '2021-07-22 19:23:23', NULL);
/*!40000 ALTER TABLE `cabang` ENABLE KEYS */;

-- Dumping structure for table proyek.dana_masuk
CREATE TABLE IF NOT EXISTS `dana_masuk` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_proyek` bigint(20) DEFAULT NULL,
  `tgl_transaksi` date DEFAULT NULL,
  `keterangan` text,
  `jumlah` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.dana_masuk: ~6 rows (approximately)
/*!40000 ALTER TABLE `dana_masuk` DISABLE KEYS */;
INSERT INTO `dana_masuk` (`id`, `id_proyek`, `tgl_transaksi`, `keterangan`, `jumlah`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, '2021-07-22', 'DP 25%', 3250000000, '2021-07-23 15:49:13', '2021-07-23 15:49:27', NULL);
/*!40000 ALTER TABLE `dana_masuk` ENABLE KEYS */;

-- Dumping structure for table proyek.dokumentasi
CREATE TABLE IF NOT EXISTS `dokumentasi` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama_dokumen` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `keterangan` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `file` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.dokumentasi: ~6 rows (approximately)
/*!40000 ALTER TABLE `dokumentasi` DISABLE KEYS */;
INSERT INTO `dokumentasi` (`id`, `nama_dokumen`, `tgl`, `keterangan`, `file`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Contoh', '2021-07-23', 'Test aja', NULL, '2021-07-23 12:46:20', '2021-07-23 13:07:33', NULL);
/*!40000 ALTER TABLE `dokumentasi` ENABLE KEYS */;

-- Dumping structure for table proyek.kegiatan
CREATE TABLE IF NOT EXISTS `kegiatan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_proyek` bigint(20) DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.kegiatan: ~1 rows (approximately)
/*!40000 ALTER TABLE `kegiatan` DISABLE KEYS */;
INSERT INTO `kegiatan` (`id`, `id_proyek`, `tgl_mulai`, `tgl_selesai`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, '2021-07-23', '2023-07-31', '2021-07-23 04:19:02', '2021-07-23 04:22:19', NULL);
/*!40000 ALTER TABLE `kegiatan` ENABLE KEYS */;

-- Dumping structure for table proyek.kendaraan
CREATE TABLE IF NOT EXISTS `kendaraan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_kendaraan` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `nomor_polisi` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `nomor_mesin` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.kendaraan: ~1 rows (approximately)
/*!40000 ALTER TABLE `kendaraan` DISABLE KEYS */;
INSERT INTO `kendaraan` (`id`, `jenis_kendaraan`, `nomor_polisi`, `nomor_mesin`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Truck Fuso', 'Hb 1234 GR', '31313', '2021-07-23 03:51:22', '2021-07-23 03:52:12', NULL);
/*!40000 ALTER TABLE `kendaraan` ENABLE KEYS */;

-- Dumping structure for table proyek.operasional
CREATE TABLE IF NOT EXISTS `operasional` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_proyek` bigint(20) DEFAULT NULL,
  `tgl_kegiatan` date DEFAULT NULL,
  `keterangan` text,
  `jenis_transaksi` enum('kredit','debit') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `jumlah` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.operasional: ~0 rows (approximately)
/*!40000 ALTER TABLE `operasional` DISABLE KEYS */;
INSERT INTO `operasional` (`id`, `id_proyek`, `tgl_kegiatan`, `keterangan`, `jenis_transaksi`, `jumlah`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, '2021-07-23', 'Membeli ATK', 'debit', 150000, '2021-07-23 08:27:34', '2021-07-23 08:34:17', NULL);
/*!40000 ALTER TABLE `operasional` ENABLE KEYS */;

-- Dumping structure for table proyek.pegawai
CREATE TABLE IF NOT EXISTS `pegawai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `alamat` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `tgl_lahir` date DEFAULT NULL,
  `tempat_lahir` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `no_telp` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` enum('Y','N') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.pegawai: ~1 rows (approximately)
/*!40000 ALTER TABLE `pegawai` DISABLE KEYS */;
INSERT INTO `pegawai` (`id`, `nama`, `alamat`, `tgl_lahir`, `tempat_lahir`, `email`, `no_telp`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Dadang', 'Jakarta', '2021-07-23', 'Bandung Kota', 'dadang@gmail.com', '098777', NULL, '2021-07-22 19:10:13', '2021-07-22 19:10:48', NULL);
/*!40000 ALTER TABLE `pegawai` ENABLE KEYS */;

-- Dumping structure for table proyek.pemakaian_bbm
CREATE TABLE IF NOT EXISTS `pemakaian_bbm` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_kendaraan` int(11) DEFAULT NULL,
  `jumlah_pemakaian` double DEFAULT NULL,
  `id_bahan_bakar` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.pemakaian_bbm: ~0 rows (approximately)
/*!40000 ALTER TABLE `pemakaian_bbm` DISABLE KEYS */;
INSERT INTO `pemakaian_bbm` (`id`, `id_kendaraan`, `jumlah_pemakaian`, `id_bahan_bakar`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 425, 1, '2021-07-23 14:43:10', '2021-07-23 14:49:18', NULL);
/*!40000 ALTER TABLE `pemakaian_bbm` ENABLE KEYS */;

-- Dumping structure for table proyek.pemakaian_material
CREATE TABLE IF NOT EXISTS `pemakaian_material` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama_bahan` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `tgl_penggunaan` date DEFAULT NULL,
  `jumlah_pemakaian` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.pemakaian_material: ~6 rows (approximately)
/*!40000 ALTER TABLE `pemakaian_material` DISABLE KEYS */;
INSERT INTO `pemakaian_material` (`id`, `nama_bahan`, `tgl_penggunaan`, `jumlah_pemakaian`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Batu Merah', '2021-07-20', 99999, '2021-07-23 15:05:08', '2021-07-23 15:07:53', NULL);
/*!40000 ALTER TABLE `pemakaian_material` ENABLE KEYS */;

-- Dumping structure for table proyek.penggunaan_kendaraan
CREATE TABLE IF NOT EXISTS `penggunaan_kendaraan` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `id_proyek` bigint(20) DEFAULT NULL,
  `id_kendaraan` int(11) DEFAULT NULL,
  `tgl_kegiatan` date DEFAULT NULL,
  `pemakaian_bbm` double DEFAULT NULL,
  `id_bahan_bakar` int(11) DEFAULT NULL,
  `jumlah_rpm` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.penggunaan_kendaraan: ~0 rows (approximately)
/*!40000 ALTER TABLE `penggunaan_kendaraan` DISABLE KEYS */;
INSERT INTO `penggunaan_kendaraan` (`id`, `id_proyek`, `id_kendaraan`, `tgl_kegiatan`, `pemakaian_bbm`, `id_bahan_bakar`, `jumlah_rpm`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 1, 1, '2021-07-21', 38.7, 1, 100, '2021-07-23 14:15:33', '2021-07-23 14:23:37', NULL);
/*!40000 ALTER TABLE `penggunaan_kendaraan` ENABLE KEYS */;

-- Dumping structure for table proyek.proyek
CREATE TABLE IF NOT EXISTS `proyek` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `nama_proyek` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `lokasi` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `jangka_waktu` varchar(100) DEFAULT NULL,
  `tgl_mulai` date DEFAULT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `konsultan_pengawas` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `kontraktor_pelaksana` varchar(191) DEFAULT NULL,
  `nilai_kontrak` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.proyek: ~1 rows (approximately)
/*!40000 ALTER TABLE `proyek` DISABLE KEYS */;
INSERT INTO `proyek` (`id`, `nama_proyek`, `lokasi`, `jangka_waktu`, `tgl_mulai`, `tgl_selesai`, `konsultan_pengawas`, `kontraktor_pelaksana`, `nilai_kontrak`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Proyek Flayover', 'Jakarta', '2 tahun 5 bulan', '2021-07-23', '2023-07-31', 'Jaya Abadi', 'Maju Terus Pantang Mundur', 200000000000, '2021-07-23 04:07:20', '2021-07-23 04:10:41', NULL);
/*!40000 ALTER TABLE `proyek` ENABLE KEYS */;

-- Dumping structure for table proyek.transaksi
CREATE TABLE IF NOT EXISTS `transaksi` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tgl_transaksi` date DEFAULT NULL,
  `keterangan` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `jumlah` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.transaksi: ~0 rows (approximately)
/*!40000 ALTER TABLE `transaksi` DISABLE KEYS */;
INSERT INTO `transaksi` (`id`, `tgl_transaksi`, `keterangan`, `jumlah`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, '2021-07-23', 'Membeli AC', 3250000, '2021-07-23 08:41:53', '2021-07-23 12:00:17', NULL);
/*!40000 ALTER TABLE `transaksi` ENABLE KEYS */;

-- Dumping structure for table proyek.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `hak_akses` enum('admin','tim lapangan','bendahara','direktur') CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `id_proyek` bigint(20) DEFAULT NULL,
  `username` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `password` varchar(191) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- Dumping data for table proyek.users: ~3 rows (approximately)
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `nama`, `hak_akses`, `id_proyek`, `username`, `password`, `created_at`, `updated_at`, `deleted_at`) VALUES
	(1, 'Administrator', 'admin', NULL, 'admin', '$2y$10$ZQRO79GDqnQ0B8JPiZfEHu8OLo7VkoDzThMzx030JaIZPVPfrTeHG', NULL, NULL, NULL),
	(2, 'Tim Lapangan', 'tim lapangan', 1, 'lapangan', '$2y$10$9WQJHGE1bK1c.xeghxfhdu9GHvSe2wUX6Xf8lvgk1ARhg5ELttnuq', '2021-07-23 04:42:27', '2021-07-23 04:51:46', NULL),
	(3, 'Bendahara', 'bendahara', 1, 'bendahara', '$2y$10$KS5PyBJSkEoJVFGmQxl3.ee6EDt8VzKSAT3XkZ2i4D7P2IwU0MeKW', '2021-07-23 08:08:38', '2021-07-23 08:08:38', NULL),
	(4, 'Direktur', 'direktur', 1, 'direktur', '$2y$10$igWsX6rAsGom9o/Uaeb.leEzXICJDO6BCi2FsZlGmoTlP0hAigwAW', '2021-07-23 13:09:45', '2021-07-23 13:10:07', NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
