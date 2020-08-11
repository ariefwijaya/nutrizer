/*
 Navicat Premium Data Transfer

 Source Server         : 172.17.12.53 musica storage
 Source Server Type    : MySQL
 Source Server Version : 80021
 Source Host           : localhost:3306
 Source Schema         : dbnutrizer

 Target Server Type    : MySQL
 Target Server Version : 80021
 File Encoding         : 65001

 Date: 10/08/2020 14:09:28
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tbl_admin_login
-- ----------------------------
DROP TABLE IF EXISTS `tbl_admin_login`;
CREATE TABLE `tbl_admin_login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  `resource` varchar(200) DEFAULT NULL,
  `resource_version` varchar(200) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `iby` varchar(30) DEFAULT NULL,
  `idt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of tbl_admin_login
-- ----------------------------
BEGIN;
INSERT INTO `tbl_admin_login` VALUES (18, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Mac OS X', 'admin', '2020-08-07 12:59:35');
INSERT INTO `tbl_admin_login` VALUES (19, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Mac OS X', 'admin', '2020-08-08 01:14:57');
INSERT INTO `tbl_admin_login` VALUES (20, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Mac OS X', 'admin', '2020-08-08 06:51:28');
INSERT INTO `tbl_admin_login` VALUES (21, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Windows 10', 'admin', '2020-08-08 14:32:14');
INSERT INTO `tbl_admin_login` VALUES (22, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Mac OS X', 'admin', '2020-08-09 00:51:58');
INSERT INTO `tbl_admin_login` VALUES (23, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Mac OS X', 'admin', '2020-08-09 15:38:19');
INSERT INTO `tbl_admin_login` VALUES (24, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Mac OS X', 'admin', '2020-08-09 16:43:14');
INSERT INTO `tbl_admin_login` VALUES (25, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Mac OS X', 'admin', '2020-08-10 00:01:36');
INSERT INTO `tbl_admin_login` VALUES (26, 'admin', '172.17.12.1', 'Chrome', '84.0.4147.105', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/84.0.4147.105 Safari/537.36', 'Mac OS X', 'admin', '2020-08-10 07:25:05');
INSERT INTO `tbl_admin_login` VALUES (27, 'admin', '172.17.12.1', 'Firefox', '79.0', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:79.0) Gecko/20100101 Firefox/79.0', 'Windows 10', 'admin', '2020-08-10 11:43:07');
COMMIT;

-- ----------------------------
-- Table structure for tbl_admin_privilege
-- ----------------------------
DROP TABLE IF EXISTS `tbl_admin_privilege`;
CREATE TABLE `tbl_admin_privilege` (
  `id` int NOT NULL AUTO_INCREMENT,
  `privilege_name` varchar(70) NOT NULL,
  `description` varchar(90) DEFAULT NULL,
  `isdeleted` smallint DEFAULT '0',
  `remark` varchar(100) DEFAULT NULL,
  `iby` varchar(30) DEFAULT NULL,
  `idt` datetime DEFAULT CURRENT_TIMESTAMP,
  `uby` varchar(30) DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  `dby` varchar(30) DEFAULT NULL,
  `ddt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of tbl_admin_privilege
-- ----------------------------
BEGIN;
INSERT INTO `tbl_admin_privilege` VALUES (1, 'Administrator', 'Root of Administrator', 0, NULL, NULL, '2020-08-07 13:50:31', NULL, NULL, NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_admin_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_admin_user`;
CREATE TABLE `tbl_admin_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `lastpw_time` datetime DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `privilege_id` int DEFAULT NULL,
  `avatar_img` varchar(255) DEFAULT NULL,
  `lastlogin_id` varchar(50) DEFAULT NULL,
  `lastlogin_time` datetime DEFAULT NULL,
  `lastlogin_from` varchar(50) DEFAULT NULL,
  `islocked` tinyint DEFAULT '0',
  `idt` datetime DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  `ddt` datetime DEFAULT NULL,
  `iby` varchar(50) DEFAULT NULL,
  `uby` varchar(50) DEFAULT NULL,
  `dby` varchar(50) DEFAULT NULL,
  `isdeleted` tinyint DEFAULT '0',
  `remarksdeleted` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_admin_user
-- ----------------------------
BEGIN;
INSERT INTO `tbl_admin_user` VALUES (1, 'admin', 'Administrator', '827ccb0eea8a706c4c34a16891f84e7b', NULL, NULL, 1, '7ec41a8b86368349bb4127bfef7500e8.png', '27', '2020-08-10 11:43:07', 'web', 0, NULL, '2020-08-10 12:37:18', NULL, NULL, 'admin', NULL, 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_config
-- ----------------------------
DROP TABLE IF EXISTS `tbl_config`;
CREATE TABLE `tbl_config` (
  `option` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `value` mediumtext CHARACTER SET latin1 COLLATE latin1_swedish_ci,
  `enable` tinyint DEFAULT '0',
  `idt` datetime DEFAULT CURRENT_TIMESTAMP,
  `udt` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `iby` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `uby` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  PRIMARY KEY (`option`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of tbl_config
-- ----------------------------
BEGIN;
INSERT INTO `tbl_config` VALUES ('banner_home', '{\"title\":\"Ayo perangi COVID! asdsad adsadasda\",\"subtitle\":\"Dengan menjaga kebersihan diri dan memperhatikan pola makanan dengan gizi seimbang\",\"linkUrl\":\"http:\\/\\/google.com\"}', 1, '2020-08-03 02:26:36', '2020-08-10 13:33:34', NULL, 'admin');
INSERT INTO `tbl_config` VALUES ('mobile_app_info', '{\n            \"forceUpdate\": false,\n            \"appName\": \"Nutrizer\",\n            \"buildNumber\": \"1\",\n            \"packageName\": \"com.etramatech.nutrizer\",\n            \"version\": \"1.0.0\"\n        }', 1, '2020-08-06 18:42:56', '2020-08-06 18:58:31', NULL, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_food
-- ----------------------------
DROP TABLE IF EXISTS `tbl_food`;
CREATE TABLE `tbl_food` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `kkal` float DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order_pos` int DEFAULT NULL,
  `idt` datetime DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  `ddt` datetime DEFAULT NULL,
  `iby` varchar(50) DEFAULT NULL,
  `uby` varchar(50) DEFAULT NULL,
  `dby` varchar(50) DEFAULT NULL,
  `isdeleted` tinyint DEFAULT '0',
  `remarksdeleted` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_food
-- ----------------------------
BEGIN;
INSERT INTO `tbl_food` VALUES (1, 'Kentang', 130, NULL, 1, NULL, '2020-08-10 13:48:52', NULL, NULL, 'admin', NULL, 0, NULL);
INSERT INTO `tbl_food` VALUES (2, 'Ubi Jalar', NULL, NULL, NULL, NULL, NULL, '2020-08-10 12:08:40', NULL, NULL, 'admin', 1, 'test');
INSERT INTO `tbl_food` VALUES (3, 'Pisang', NULL, NULL, 2, '2020-08-10 12:10:56', NULL, NULL, 'admin', NULL, NULL, 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_food_cat
-- ----------------------------
DROP TABLE IF EXISTS `tbl_food_cat`;
CREATE TABLE `tbl_food_cat` (
  `food_id` int NOT NULL,
  `food_cat_id` int NOT NULL,
  `idt` datetime DEFAULT NULL,
  `iby` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`food_id`,`food_cat_id`),
  KEY `food_cat_id` (`food_cat_id`),
  CONSTRAINT `tbl_food_cat_ibfk_1` FOREIGN KEY (`food_id`) REFERENCES `tbl_food` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_food_cat_ibfk_2` FOREIGN KEY (`food_cat_id`) REFERENCES `tbl_food_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Relation between tbl_food and tbl_food_category';

-- ----------------------------
-- Records of tbl_food_cat
-- ----------------------------
BEGIN;
INSERT INTO `tbl_food_cat` VALUES (1, 1, '2020-08-10 13:48:32', 'admin');
INSERT INTO `tbl_food_cat` VALUES (2, 3, '2020-08-10 10:55:56', 'admin');
COMMIT;

-- ----------------------------
-- Table structure for tbl_food_category
-- ----------------------------
DROP TABLE IF EXISTS `tbl_food_category`;
CREATE TABLE `tbl_food_category` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order_pos` int DEFAULT NULL,
  `food_count` int DEFAULT '0',
  `idt` datetime DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  `ddt` datetime DEFAULT NULL,
  `iby` varchar(50) DEFAULT NULL,
  `uby` varchar(50) DEFAULT NULL,
  `dby` varchar(50) DEFAULT NULL,
  `isdeleted` tinyint DEFAULT '0',
  `remarksdeleted` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_food_category
-- ----------------------------
BEGIN;
INSERT INTO `tbl_food_category` VALUES (1, 'Sayuran', NULL, 1, 1, NULL, '2020-08-10 10:39:42', NULL, NULL, 'admin', NULL, 0, NULL);
INSERT INTO `tbl_food_category` VALUES (2, 'Buah', NULL, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL);
INSERT INTO `tbl_food_category` VALUES (3, 'Kacang-kacangan', NULL, 3, 1, '2020-08-10 10:39:59', NULL, NULL, 'admin', NULL, NULL, 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_history_login
-- ----------------------------
DROP TABLE IF EXISTS `tbl_history_login`;
CREATE TABLE `tbl_history_login` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) DEFAULT NULL,
  `ip_address` varchar(20) DEFAULT NULL,
  `resource` varchar(200) DEFAULT NULL,
  `resource_version` varchar(200) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `platform` varchar(255) DEFAULT NULL,
  `iby` varchar(30) DEFAULT NULL,
  `idt` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Records of tbl_history_login
-- ----------------------------
BEGIN;
INSERT INTO `tbl_history_login` VALUES (1, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-20 16:31:21');
INSERT INTO `tbl_history_login` VALUES (2, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-20 16:31:57');
INSERT INTO `tbl_history_login` VALUES (3, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-20 16:34:26');
INSERT INTO `tbl_history_login` VALUES (4, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-29 16:18:45');
INSERT INTO `tbl_history_login` VALUES (5, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-29 16:19:02');
INSERT INTO `tbl_history_login` VALUES (6, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-29 16:20:06');
INSERT INTO `tbl_history_login` VALUES (7, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-29 16:20:11');
INSERT INTO `tbl_history_login` VALUES (8, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-29 16:32:13');
INSERT INTO `tbl_history_login` VALUES (9, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-07-29 16:37:32');
INSERT INTO `tbl_history_login` VALUES (10, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-08-01 17:20:02');
INSERT INTO `tbl_history_login` VALUES (11, 'appdev', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev', '2020-08-01 19:19:35');
INSERT INTO `tbl_history_login` VALUES (12, 'appdev2', '172.17.12.1', '', '', 'Dart/2.8 (dart:io)', 'Unknown Platform', 'appdev2', '2020-08-04 15:41:44');
INSERT INTO `tbl_history_login` VALUES (13, 'appdev', '36.76.190.218', '', '', 'Dart/2.9 (dart:io)', 'Unknown Platform', 'appdev', '2020-08-06 15:08:56');
INSERT INTO `tbl_history_login` VALUES (14, 'appdev', '114.125.230.198', '', '', 'Dart/2.9 (dart:io)', 'Unknown Platform', 'appdev', '2020-08-06 18:00:06');
INSERT INTO `tbl_history_login` VALUES (15, 'appdev', '172.17.12.1', '', '', 'Dart/2.9 (dart:io)', 'Unknown Platform', 'appdev', '2020-08-06 18:48:25');
INSERT INTO `tbl_history_login` VALUES (16, 'appdev', '172.17.12.1', '', '', 'Dart/2.9 (dart:io)', 'Unknown Platform', 'appdev', '2020-08-06 19:37:43');
INSERT INTO `tbl_history_login` VALUES (17, 'appdev', '158.140.165.100', '', '', 'Dart/2.9 (dart:io)', 'Unknown Platform', 'appdev', '2020-08-06 20:10:52');
INSERT INTO `tbl_history_login` VALUES (18, 'appdev', '172.17.12.1', '', '', 'Dart/2.9 (dart:io)', 'Unknown Platform', 'appdev', '2020-08-10 13:11:29');
COMMIT;

-- ----------------------------
-- Table structure for tbl_kek
-- ----------------------------
DROP TABLE IF EXISTS `tbl_kek`;
CREATE TABLE `tbl_kek` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `subtitle` varchar(50) DEFAULT NULL,
  `content` mediumtext,
  `order_pos` int DEFAULT NULL,
  `idt` datetime DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  `ddt` datetime DEFAULT NULL,
  `iby` varchar(50) DEFAULT NULL,
  `uby` varchar(50) DEFAULT NULL,
  `dby` varchar(50) DEFAULT NULL,
  `isdeleted` tinyint DEFAULT '0',
  `remarksdeleted` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COMMENT='Tabel untuk menyimpan data Kekurangan Energi Kronis';

-- ----------------------------
-- Records of tbl_kek
-- ----------------------------
BEGIN;
INSERT INTO `tbl_kek` VALUES (1, 'Faktor Penyebab COVID', 'Kenali penyebabnya sejak sejak dini', '<p>Faktor - faktor yang menyebabkan kekurangan energi kronis (<strong>KEK</strong>) :</p>\n\n<ul>\n	<li>Asupan makanan yang tidak sesuai kebutuhan</li>\n	<li>Usia ibu hamil yang terlalu muda/tua</li>\n	<li>Beban kerja ibu terlalu berat</li>\n	<li>Penyakit infeksi yang dialami ibu hamil</li>\n</ul>\n', 1, '2020-08-03 13:48:55', NULL, NULL, 'ariefw', NULL, NULL, 0, NULL);
INSERT INTO `tbl_kek` VALUES (2, 'Tanda dan Gejala Terinfeksi Virus Corona', NULL, '<p>Satu</p>\r\n\r\n<p>Dua tiga</p>\r\n\r\n<ol>\r\n	<li>Konten UJi test</li>\r\n</ol>\r\n', 2, NULL, '2020-08-09 17:26:49', NULL, NULL, 'admin', NULL, 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_nutri_food_cat
-- ----------------------------
DROP TABLE IF EXISTS `tbl_nutri_food_cat`;
CREATE TABLE `tbl_nutri_food_cat` (
  `nutrition_type_id` int NOT NULL,
  `food_cat_id` int NOT NULL,
  `idt` datetime DEFAULT NULL,
  `iby` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`nutrition_type_id`,`food_cat_id`),
  KEY `food_cat_id` (`food_cat_id`),
  CONSTRAINT `tbl_nutri_food_cat_ibfk_1` FOREIGN KEY (`nutrition_type_id`) REFERENCES `tbl_nutrition_type` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `tbl_nutri_food_cat_ibfk_2` FOREIGN KEY (`food_cat_id`) REFERENCES `tbl_food_category` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Relation between tbl_nutrition_type and tbl_food_category';

-- ----------------------------
-- Records of tbl_nutri_food_cat
-- ----------------------------
BEGIN;
INSERT INTO `tbl_nutri_food_cat` VALUES (1, 1, '2020-08-10 10:23:20', 'admin');
INSERT INTO `tbl_nutri_food_cat` VALUES (2, 1, '2020-08-10 10:23:15', 'admin');
COMMIT;

-- ----------------------------
-- Table structure for tbl_nutrition_type
-- ----------------------------
DROP TABLE IF EXISTS `tbl_nutrition_type`;
CREATE TABLE `tbl_nutrition_type` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order_pos` int DEFAULT NULL,
  `food_cat_count` int DEFAULT '0',
  `idt` datetime DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  `ddt` datetime DEFAULT NULL,
  `iby` varchar(50) DEFAULT NULL,
  `uby` varchar(50) DEFAULT NULL,
  `dby` varchar(50) DEFAULT NULL,
  `isdeleted` tinyint DEFAULT '0',
  `remarksdeleted` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_nutrition_type
-- ----------------------------
BEGIN;
INSERT INTO `tbl_nutrition_type` VALUES (1, 'Karbohidrat', '33ac61c7252ba6590ff4efb98150a054.png', 1, 1, '2020-08-03 19:51:21', '2020-08-10 08:07:08', NULL, NULL, 'admin', NULL, 0, NULL);
INSERT INTO `tbl_nutrition_type` VALUES (2, 'Protein', NULL, 2, 1, NULL, '2020-08-10 08:00:06', NULL, NULL, 'admin', NULL, 0, NULL);
COMMIT;

-- ----------------------------
-- Table structure for tbl_user
-- ----------------------------
DROP TABLE IF EXISTS `tbl_user`;
CREATE TABLE `tbl_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) DEFAULT NULL,
  `nickname` varchar(50) DEFAULT NULL,
  `gender` enum('F','M') DEFAULT NULL,
  `height` float DEFAULT NULL,
  `weight` float DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `lastpw_time` datetime DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `lastlogin_id` varchar(50) DEFAULT NULL,
  `lastlogin_time` datetime DEFAULT NULL,
  `lastlogin_from` varchar(50) DEFAULT NULL,
  `islocked` tinyint DEFAULT '0',
  `idt` datetime DEFAULT NULL,
  `udt` datetime DEFAULT NULL,
  `ddt` datetime DEFAULT NULL,
  `iby` varchar(50) DEFAULT NULL,
  `uby` varchar(50) DEFAULT NULL,
  `dby` varchar(50) DEFAULT NULL,
  `isdeleted` tinyint DEFAULT '0',
  `remarksdeleted` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of tbl_user
-- ----------------------------
BEGIN;
INSERT INTO `tbl_user` VALUES (1, 'appdev', 'App Devx', 'M', 187, 76, '827ccb0eea8a706c4c34a16891f84e7b', '2020-08-02 19:00:23', 'ariefwiijaya@gmail.com', '1996-04-03', 'rAETDFyt', '2020-08-10 13:11:29', 'mobile', 0, '2020-07-20 16:34:26', '2020-08-10 13:11:29', NULL, 'appdev', 'appdev', NULL, 0, NULL);
INSERT INTO `tbl_user` VALUES (2, 'appdev2', 'Application Dev 2', 'M', 164, 49, '827ccb0eea8a706c4c34a16891f84e7b', NULL, 'test@gmail.com', '2020-08-04', 'YsQZr6G3', '2020-08-04 15:41:44', 'mobile', 0, '2020-08-04 15:41:44', '2020-08-04 15:42:11', NULL, 'appdev2', 'appdev2', NULL, 0, NULL);
COMMIT;

SET FOREIGN_KEY_CHECKS = 1;
