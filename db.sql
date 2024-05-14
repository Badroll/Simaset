-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.31 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             12.5.0.6677
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for simaset
CREATE DATABASE IF NOT EXISTS `simaset` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;
USE `simaset`;

-- Dumping structure for table simaset.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `BARANG_ID` int(11) NOT NULL AUTO_INCREMENT,
  `BARANG_KODE_SENSUS` varchar(50) DEFAULT NULL,
  `BARANG_QR_SVG` text,
  `BARANG_NAMA` varchar(100) DEFAULT NULL,
  `BARANG_OPD` int(11) DEFAULT NULL,
  `BARANG_KONDISI` varchar(50) DEFAULT NULL,
  `BARANG_KEBERADAAN` varchar(50) DEFAULT NULL,
  `BARANG_WAKTU_PENDATAAN` datetime DEFAULT NULL,
  `BARANG_FOTO` varchar(50) DEFAULT NULL,
  `BARANG_KETERANGAN` text,
  PRIMARY KEY (`BARANG_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simaset.barang: 0 rows
/*!40000 ALTER TABLE `barang` DISABLE KEYS */;
/*!40000 ALTER TABLE `barang` ENABLE KEYS */;

-- Dumping structure for table simaset.dinas
CREATE TABLE IF NOT EXISTS `dinas` (
  `DINAS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `DINAS_NAMA` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`DINAS_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simaset.dinas: 15 rows
/*!40000 ALTER TABLE `dinas` DISABLE KEYS */;
INSERT INTO `dinas` (`DINAS_ID`, `DINAS_NAMA`) VALUES
	(1, 'Dinas Pendidikan'),
	(2, 'Dinas Kesehatan'),
	(3, 'Dinas Pekerjaan Umum Dan Penataan Ruang'),
	(4, 'Dinas Perumahan Rakyat Dan Kawasan Permukiman dan Lingkungan Hidup'),
	(5, 'Dinas Sosial'),
	(6, 'Dinas Ketahanan Pangan dan Pertanian'),
	(7, 'Dinas Kependudukan Dan Pencatatan Sipil'),
	(8, 'Dinas Pemberdayaan Masyarakat Dan Desa, P3A dan PPKB'),
	(9, 'Dinas Perhubungan'),
	(10, 'Dinas Komunikasi Dan Informatika'),
	(11, 'Dinas Penanaman Modal Dan Pelayanan Terpadu Satu Pintu dan Tenaga Kerja'),
	(12, 'Dinas Kepemudaan Dan Olahraga dan Pariwisata'),
	(13, 'Dinas Kearsipan dan Perpustakaan'),
	(14, 'Dinas Kelautan Dan Perikanan'),
	(15, 'Dinas Perindustrian, Perdagangan, Koperasi, Usaha Kecil Dan Menengah');
/*!40000 ALTER TABLE `dinas` ENABLE KEYS */;

-- Dumping structure for table simaset.kritik
CREATE TABLE IF NOT EXISTS `kritik` (
  `KRITIK_ID` int(11) NOT NULL AUTO_INCREMENT,
  `KRITIK_USER_ID` int(11) NOT NULL,
  `KRITIK_ISI` text,
  PRIMARY KEY (`KRITIK_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simaset.kritik: 2 rows
/*!40000 ALTER TABLE `kritik` DISABLE KEYS */;
INSERT INTO `kritik` (`KRITIK_ID`, `KRITIK_USER_ID`, `KRITIK_ISI`) VALUES
	(1, 1, '12345678'),
	(2, 1, 'asdas');
/*!40000 ALTER TABLE `kritik` ENABLE KEYS */;

-- Dumping structure for table simaset._reference
CREATE TABLE IF NOT EXISTS `_reference` (
  `R_CATEGORY` varchar(50) NOT NULL,
  `R_ID` varchar(50) NOT NULL,
  `R_VALUE` varchar(50) DEFAULT NULL,
  `R_ORDER` int(11) DEFAULT '0',
  PRIMARY KEY (`R_ID`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simaset._reference: 11 rows
/*!40000 ALTER TABLE `_reference` DISABLE KEYS */;
INSERT INTO `_reference` (`R_CATEGORY`, `R_ID`, `R_VALUE`, `R_ORDER`) VALUES
	('USER_GENDER', 'USER_GENDER_MALE', 'Laki-laki', 1),
	('USER_GENDER', 'USER_GENDER_FEMALE', 'Perempuan', 2),
	('USER_TYPE', 'USER_TYPE_ADMIN', 'Admin', 2),
	('USER_TYPE', 'USER_TYPE_PETUGAS', 'Petugas', 1),
	('JABATAN', 'JABATAN_1', 'Jabatan 1', 1),
	('KONDISI', 'KONDISI_1', 'BAIK', 1),
	('KEBERADAAN', 'KEBERADAAN_1', 'ADA', 1),
	('KEBERADAAN', 'KEBERADAAN_2', 'SEDANG DICARI', 2),
	('KEBERADAAN', 'KEBERADAAN_3', 'HILANG', 3),
	('KONDISI', 'KONDISI_2', 'KURANG BAIK', 2),
	('KONDISI', 'KONDISI_3', 'RUSAK', 3);
/*!40000 ALTER TABLE `_reference` ENABLE KEYS */;

-- Dumping structure for table simaset._setting
CREATE TABLE IF NOT EXISTS `_setting` (
  `S_ID` varchar(100) NOT NULL,
  `S_VALUE` varchar(100) NOT NULL,
  `S_INFO` varchar(100) NOT NULL,
  PRIMARY KEY (`S_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simaset._setting: ~2 rows (approximately)
INSERT INTO `_setting` (`S_ID`, `S_VALUE`, `S_INFO`) VALUES
	('APP_NAME', 'Simaset', 'Nama aplikasi'),
	('APP_NAME_LONG', 'Simaset', 'Nama aplikasi versi panjang');

-- Dumping structure for table simaset._user
CREATE TABLE IF NOT EXISTS `_user` (
  `USER_ID` int(11) NOT NULL AUTO_INCREMENT,
  `USER_TOKEN` varchar(100) DEFAULT NULL,
  `USER_PASSWORD_HASH` varchar(100) DEFAULT NULL,
  `USER_NIP` varchar(50) DEFAULT NULL,
  `USER_TYPE` varchar(50) DEFAULT NULL,
  `USER_NAMA` varchar(100) DEFAULT NULL,
  `USER_OPD` int(11) DEFAULT NULL,
  `USER_JABATAN` varchar(50) DEFAULT NULL,
  `USER_HP` varchar(30) DEFAULT NULL,
  `USER_EMAIL` varchar(50) DEFAULT NULL,
  `USER_AVATAR_PATH` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`USER_ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- Dumping data for table simaset._user: 2 rows
/*!40000 ALTER TABLE `_user` DISABLE KEYS */;
INSERT INTO `_user` (`USER_ID`, `USER_TOKEN`, `USER_PASSWORD_HASH`, `USER_NIP`, `USER_TYPE`, `USER_NAMA`, `USER_OPD`, `USER_JABATAN`, `USER_HP`, `USER_EMAIL`, `USER_AVATAR_PATH`) VALUES
	(1, '563f6a9fc158141585c6740279da5128e3c9afb0f0d44592112045cca7bb906c', '12345678', '201', 'USER_TYPE_PETUGAS', 'User 1', 1, 'JABATAN_1', '081215992673', 'badrulcr5@gmail.com', '-'),
	(2, 'bde294423ab9d2e8b707613c06dd4882e944bfa12fcbed874aab1bf3159c7339', '12345678', '101', 'USER_TYPE_ADMIN', 'Admin 1', 1, 'JABATAN_1', '0812345', 'admin1@gmail.com', '-');
/*!40000 ALTER TABLE `_user` ENABLE KEYS */;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
