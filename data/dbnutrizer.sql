-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.1.28-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             10.2.0.5599
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table dbnutrizer.tbl_history_login
CREATE TABLE IF NOT EXISTS `tbl_history_login` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  `browser_type` varchar(200) DEFAULT NULL,
  `browser_version` varchar(200) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `iby` varchar(30) DEFAULT NULL,
  `idt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- Dumping data for table dbnutrizer.tbl_history_login: ~0 rows (approximately)
DELETE FROM `tbl_history_login`;
/*!40000 ALTER TABLE `tbl_history_login` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_history_login` ENABLE KEYS */;

-- Dumping structure for table dbnutrizer.tbl_user
CREATE TABLE IF NOT EXISTS `tbl_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `lastpw_time` datetime DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `lastlogin_id` varchar(50) DEFAULT NULL,
  `lastlogin_time` datetime DEFAULT NULL,
  `lastlogin_from` varchar(50) DEFAULT NULL,
  `idt` datetime DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  `ddt` datetime DEFAULT NULL,
  `iby` varchar(50) DEFAULT NULL,
  `uby` varchar(50) DEFAULT NULL,
  `dby` varchar(50) DEFAULT NULL,
  `isdeleted` tinyint(4) DEFAULT NULL,
  `remarksdeleted` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table dbnutrizer.tbl_user: ~0 rows (approximately)
DELETE FROM `tbl_user`;
/*!40000 ALTER TABLE `tbl_user` DISABLE KEYS */;
/*!40000 ALTER TABLE `tbl_user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
