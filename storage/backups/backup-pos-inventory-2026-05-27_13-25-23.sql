mysqldump: Deprecated program name. It will be removed in a future release, use '/usr/bin/mariadb-dump' instead
/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19-12.2.2-MariaDB, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: pos-inventory
-- ------------------------------------------------------
-- Server version	12.2.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*M!100616 SET @OLD_NOTE_VERBOSITY=@@NOTE_VERBOSITY, NOTE_VERBOSITY=0 */;

--
-- Table structure for table `branch_product`
--

DROP TABLE IF EXISTS `branch_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `branch_product` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `branch_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branch_product_branch_id_product_id_unique` (`branch_id`,`product_id`),
  KEY `branch_product_product_id_foreign` (`product_id`),
  CONSTRAINT `branch_product_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `branch_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branch_product`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `branch_product` WRITE;
/*!40000 ALTER TABLE `branch_product` DISABLE KEYS */;
INSERT INTO `branch_product` VALUES
(1,1,1,100,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(2,1,2,100,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(3,1,3,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(4,1,4,80,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(5,1,5,80,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(6,1,6,29,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(7,1,7,15,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(8,1,8,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(9,1,9,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(10,1,10,30,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(11,1,21,120,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(12,1,22,100,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(13,1,23,119,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(14,1,24,60,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(15,1,25,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(16,1,26,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(17,1,27,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(18,1,36,60,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(19,1,37,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(20,1,38,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(21,1,39,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(22,1,40,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(23,1,41,60,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(24,1,42,69,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(25,1,43,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(26,1,44,99,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(27,1,45,100,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(28,1,46,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(29,1,47,30,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(30,1,48,25,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(31,1,51,100,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(32,1,52,60,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(33,1,53,199,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(34,1,54,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(35,1,55,80,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(36,1,56,100,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(37,1,57,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(38,1,61,79,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(39,1,62,59,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(40,1,63,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(41,1,64,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(42,1,65,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(43,1,66,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(44,1,67,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(45,1,68,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(46,1,69,30,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(47,1,70,60,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(48,1,71,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(49,1,72,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(50,1,73,29,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(51,1,74,14,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(52,1,75,30,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(53,1,76,20,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(54,1,77,19,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(55,1,78,15,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(56,1,79,20,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(57,1,11,30,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(58,1,12,30,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(59,1,13,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(60,1,14,30,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(61,1,15,40,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(62,1,16,50,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(63,1,17,20,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(64,1,18,40,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(65,1,19,15,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(66,1,20,9,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(67,1,49,25,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(68,1,50,30,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(69,1,28,40,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(70,1,29,40,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(71,1,30,40,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(72,1,31,25,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(73,1,32,50,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(74,1,33,40,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(75,1,34,30,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(76,1,35,99,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(77,1,81,40,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(78,1,82,49,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(79,1,83,30,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(80,1,84,25,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(81,1,85,20,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(82,1,86,10,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(83,1,87,20,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(84,1,88,14,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(85,1,89,15,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(86,1,90,20,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(87,1,91,10,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(88,1,92,10,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(89,1,93,7,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(90,1,94,20,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(91,1,95,15,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(92,1,96,24,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(93,1,97,20,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(94,1,98,25,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(95,1,99,20,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(96,1,100,19,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(97,1,102,7,'2026-05-27 05:01:16','2026-05-27 05:01:16');
/*!40000 ALTER TABLE `branch_product` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `branches`
--

DROP TABLE IF EXISTS `branches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `branches` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `branches_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `branches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `branches` WRITE;
/*!40000 ALTER TABLE `branches` DISABLE KEYS */;
INSERT INTO `branches` VALUES
(1,'Pusat','PST','Jl. Raya Utama No. 1','021-5550001','pusat@pos.test',1,'2026-05-27 02:04:35','2026-05-27 02:04:35');
/*!40000 ALTER TABLE `branches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES
(1,'Makanan','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(2,'Minuman','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(3,'Snack & Cemilan','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(4,'Alat Tulis','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(5,'Kebersihan','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(6,'Sembako','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(7,'Obat & Vitamin','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(8,'Peralatan Rumah','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(9,'Susu & Bayi','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(10,'Rokok & Herbal','2026-05-27 02:04:50','2026-05-27 02:04:50');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `held_carts`
--

DROP TABLE IF EXISTS `held_carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `held_carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `label` varchar(255) DEFAULT NULL,
  `items` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`items`)),
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `held_carts_user_id_foreign` (`user_id`),
  KEY `held_carts_branch_id_foreign` (`branch_id`),
  CONSTRAINT `held_carts_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `held_carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `held_carts`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `held_carts` WRITE;
/*!40000 ALTER TABLE `held_carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `held_carts` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES
(1,'0001_01_01_000000_create_users_table',1),
(2,'0001_01_01_000001_create_cache_table',1),
(3,'0001_01_01_000002_create_jobs_table',1),
(4,'2026_05_26_132225_add_role_to_users_table',1),
(5,'2026_05_26_132300_create_categories_table',1),
(6,'2026_05_26_132301_create_products_table',1),
(7,'2026_05_26_132302_create_suppliers_table',1),
(8,'2026_05_26_132303_create_stock_ins_table',1),
(9,'2026_05_26_132304_create_stock_in_items_table',1),
(10,'2026_05_26_132305_create_stock_outs_table',1),
(11,'2026_05_26_132306_create_stock_out_items_table',1),
(12,'2026_05_26_132307_create_stock_movements_table',1),
(13,'2026_05_26_132308_create_sales_table',1),
(14,'2026_05_26_132309_create_sale_items_table',1),
(15,'2026_05_26_135730_create_purchase_orders_table',1),
(16,'2026_05_26_135731_create_purchase_order_items_table',1),
(17,'2026_05_26_140138_add_voided_at_to_sales_table',1),
(18,'2026_05_26_140138_create_held_carts_table',1),
(19,'2026_05_27_000001_create_branches_table',1),
(20,'2026_05_27_000002_create_branch_product_table',1),
(21,'2026_05_27_000003_add_branch_id_to_tables',1),
(22,'2026_05_27_093527_add_invoice_image_to_stock_ins_table',2),
(23,'2026_05_27_100000_create_settings_table',3),
(24,'2026_05_27_131218_add_tax_rate_to_sales_table',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(255) NOT NULL,
  `barcode` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `sell_price` decimal(15,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `minimum_stock` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_sku_unique` (`sku`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=103 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES
(1,1,'MKN001','8991002101225','Indomie Goreng',2500.00,3500.00,100,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(2,1,'MKN002','8991002101256','Indomie Kuah Rasa Ayam',2500.00,3500.00,100,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(3,1,'MKN003','8991002101348','Indomie Goreng Jumbo',4000.00,5500.00,50,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(4,1,'MKN004','8991002102307','Mie Sedaap Goreng',2500.00,3500.00,80,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(5,1,'MKN005','8991002102314','Mie Sedaap Kuah Rasa Soto',2500.00,3500.00,80,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(6,1,'MKN006','8997013990012','Beras Ramos 1kg',12000.00,15000.00,29,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:13:49'),
(7,1,'MKN007','8997013990029','Beras Setra Wangi 5kg',60000.00,75000.00,15,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(8,1,'MKN008','8997021990032','Telur Ayam 1kg',22000.00,28000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(9,1,'MKN009','8997021990049','Minyak Goreng 1L',14000.00,18000.00,50,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(10,1,'MKN010','8997021990056','Tepung Terigu 1kg',8000.00,11000.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(11,1,'MKN011','8997021990063','Kecap Manis Bango 550ml',14000.00,18000.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(12,1,'MKN012','8997021990070','Saos Sambal ABC 340ml',11000.00,14000.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(13,1,'MKN013','8997021990087','Garam Halus Refina 500g',3500.00,5000.00,50,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(14,1,'MKN014','8997021990094','Kaldu Ayam Masako 240gr',9500.00,12500.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(15,1,'MKN015','8997021990100','Sarden ABC 155gr',6500.00,9000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(16,1,'MKN016','8997021990117','Susu Kental Manis Frisian Flag',9000.00,12000.00,50,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(17,1,'MKN017','8997021990124','Kopi Kapal Api 380gr',24000.00,30000.00,20,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(18,1,'MKN018','8997021990131','Teh Celup Tong Tji 25ct',5000.00,7000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(19,1,'MKN019','8997021990148','Madurasa 500gr',18000.00,23500.00,15,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(20,1,'MKN020','8997021990155','Corned Beef Pronas 340gr',30000.00,38000.00,9,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 05:56:42'),
(21,2,'MNM001','8996001310112','Air Mineral Vit 600ml',2000.00,3000.00,120,30,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(22,2,'MNM002','8996001310129','Air Mineral Aqua 600ml',2500.00,3500.00,100,30,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(23,2,'MNM003','8996001310136','Air Mineral Le Minerale 600ml',2000.00,3000.00,119,30,NULL,1,'2026-05-27 02:04:50','2026-05-27 05:56:42'),
(24,2,'MNM004','8996001310143','Teh Botol Sosro 500ml',4000.00,5500.00,60,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(25,2,'MNM005','8996001310150','Coca-Cola 390ml',4500.00,6000.00,50,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(26,2,'MNM006','8996001310167','Fanta 390ml',4500.00,6000.00,50,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(27,2,'MNM007','8996001310174','Sprite 390ml',4500.00,6000.00,50,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(28,2,'MNM008','8996001310181','Pulpy Orange 300ml',5000.00,7000.00,40,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(29,2,'MNM009','8996001310198','Mizone 500ml',5500.00,7500.00,40,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(30,2,'MNM010','8996001310204','Pocari Sweat 500ml',6000.00,8000.00,40,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(31,2,'MNM011','8996001310211','Yakult 5btl',10000.00,13000.00,25,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(32,2,'MNM012','8996001310228','Susu UHT Ultra Milk 250ml',6000.00,8000.00,50,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(33,2,'MNM013','8996001310235','Susu UHT Diamond 250ml',5500.00,7500.00,40,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(34,2,'MNM014','8996001310242','Jus Bukit 350ml',5500.00,7500.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(35,2,'MNM015','8996001310259','Ale-Ale 250ml',1500.00,2500.00,99,30,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:13:49'),
(36,3,'SNK001','8996002110112','Chitato 68gr',7000.00,10000.00,60,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(37,3,'SNK002','8996002110129','Lays 68gr',7000.00,10000.00,50,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(38,3,'SNK003','8996002110136','Qtela 68gr',6500.00,9000.00,50,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(39,3,'SNK004','8996002110143','Taro 60gr',6000.00,8500.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(40,3,'SNK005','8996002110150','Cheetos 68gr',6500.00,9000.00,50,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(41,3,'SNK006','8996002110167','Oreo 133gr',8000.00,11000.00,60,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(42,3,'SNK007','8996002110174','Roma Malkist 135gr',5000.00,7500.00,69,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:06:06'),
(43,3,'SNK008','8996002110181','Wafer Tango 225gr',12000.00,16000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(44,3,'SNK009','8996002110198','Beng-Beng 40gr',2500.00,4000.00,99,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:13:25'),
(45,3,'SNK010','8996002110204','Top 40gr',2500.00,4000.00,100,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(46,3,'SNK011','8996002110211','Pilus Garuda 200gr',8000.00,11000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(47,3,'SNK012','8996002110228','Kacang Garuda 200gr',10000.00,13500.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(48,3,'SNK013','8996002110235','Good Time 150gr',12000.00,16000.00,25,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(49,3,'SNK014','8996002110242','Chocolatos 20gr',2000.00,3000.00,25,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(50,3,'SNK015','8996002110259','Permen Relaxa 50gr',4000.00,6000.00,30,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(51,4,'ALT001','8996003110112','Buku Tulis Sinar Dunia 38lb',3500.00,5000.00,100,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(52,4,'ALT002','8996003110129','Buku Gambar A4',5000.00,7000.00,60,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(53,4,'ALT003','8996003110136','Pulpen Standard AE7',2000.00,3500.00,199,30,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:10:15'),
(54,4,'ALT004','8996003110143','Pulpen Pilot G2',12000.00,16000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(55,4,'ALT005','8996003110150','Pensil 2B Faber Castell',3000.00,5000.00,80,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(56,4,'ALT006','8996003110167','Penghapus Joyko',2000.00,3000.00,100,20,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(57,4,'ALT007','8996003110174','Penggaris 30cm Joyko',4000.00,5500.00,50,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(58,4,'ALT008','8996003110181','Lem Uhu 35gr',8000.00,11000.00,0,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(59,4,'ALT009','8996003110198','Gunting Kenko',7000.00,10000.00,0,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(60,4,'ALT010','8996003110204','Cutter Joyko',5000.00,7000.00,0,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(61,5,'KBR001','8996004110112','Sabun Lifebuoy 100gr',4000.00,5500.00,79,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:10:15'),
(62,5,'KBR002','8996004110129','Pasta Gigi Pepsodent 120gr',8000.00,11000.00,59,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:06:06'),
(63,5,'KBR003','8996004110136','Sikat Gigi Formula',4000.00,6000.00,50,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(64,5,'KBR004','8996004110143','Shampoo Clear 80ml',5000.00,7000.00,40,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(65,5,'KBR005','8996004110150','Sabun Cuci Piring Sunlight 450ml',8000.00,11000.00,50,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(66,5,'KBR006','8996004110167','Deterjen Rinso 500gr',11000.00,14500.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(67,5,'KBR007','8996004110174','Pewangi Molto 250ml',7000.00,10000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(68,5,'KBR008','8996004110181','Pemutih Bayclin 500ml',5000.00,7500.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(69,5,'KBR009','8996004110198','Pembersih Lantai So Klin 750ml',10000.00,13500.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(70,5,'KBR010','8996004110204','Tissue Paseo 200ct',6000.00,9000.00,60,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(71,6,'SBK001','8996005110112','Minyak Goreng Minyakita 1L',14000.00,18000.00,50,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(72,6,'SBK002','8996005110129','Gula Pasir Gulaku 1kg',13500.00,17000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(73,6,'SBK003','8996005110136','Tepung Segitiga Biru 1kg',10000.00,13000.00,29,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:06:06'),
(74,6,'SBK004','8996005110143','Beras Kepala 5kg',55000.00,68000.00,14,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:13:03'),
(75,6,'SBK005','8996005110150','Kacang Hijau 500gr',12000.00,15500.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(76,6,'SBK006','8996005110167','Bawang Merah 1kg',28000.00,35000.00,20,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(77,6,'SBK007','8996005110174','Bawang Putih 1kg',25000.00,32000.00,19,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:13:49'),
(78,6,'SBK008','8996005110181','Cabai Merah 1kg',35000.00,45000.00,15,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(79,6,'SBK009','8996005110198','Telur Bebek 1kg',27000.00,34000.00,20,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(80,6,'SBK010','8996005110204','Mie Kering Eko 200gr',6000.00,8500.00,0,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(81,7,'OBT001','8996006110112','Paracetamol 500mg 10kap',5000.00,8000.00,40,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(82,7,'OBT002','8996006110129','Antangin JRG 3sachet',4000.00,6000.00,49,15,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:13:25'),
(83,7,'OBT003','8996006110136','Tolak Angin 6sachet',8000.00,11000.00,30,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(84,7,'OBT004','8996006110143','Minyak Kayu Putih Cap Lang 30ml',14000.00,19000.00,25,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(85,7,'OBT005','8996006110150','Betadine 15ml',15000.00,20000.00,20,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(86,8,'PRT001','8996007110112','Gelas Kaca 250ml 6pcs',25000.00,35000.00,10,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(87,8,'PRT002','8996007110129','Piring Melamin 20cm',8000.00,12000.00,20,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(88,8,'PRT003','8996007110136','Sendok Stainless 12pcs',20000.00,28000.00,14,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:10:15'),
(89,8,'PRT004','8996007110143','Ember Plastik 10L',18000.00,25000.00,15,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(90,8,'PRT005','8996007110150','Lap Pel Magic',12000.00,17000.00,20,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(91,9,'SUS001','8996008110112','Susu SGM 3+ 400gr',32000.00,40000.00,10,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(92,9,'SUS002','8996008110129','Susu Dancow 1+ 400gr',35000.00,44000.00,10,5,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(93,9,'SUS003','8996008110136','Popok Merries M 44ct',70000.00,88000.00,7,3,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:10:15'),
(94,9,'SUS004','8996008110143','Tisu Basah Baby Pigeon',18000.00,24000.00,20,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(95,9,'SUS005','8996008110150','Minyak Telon Tresno Joy 100ml',22000.00,29000.00,15,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(96,10,'ROK001','8996009110112','Sampoerna Kretek 12btg',18000.00,24000.00,24,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:10:15'),
(97,10,'ROK002','8996009110129','Dji Sam Soe Magnum 12btg',20000.00,26000.00,20,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(98,10,'ROK003','8996009110136','Gudang Garam Signature 12btg',18000.00,24000.00,25,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(99,10,'ROK004','8996009110143','Surya Pro Mild 12btg',20000.00,26000.00,20,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 02:04:51'),
(100,10,'ROK005','8996009110150','Sampoerna Mild 12btg',22000.00,28000.00,19,10,NULL,1,'2026-05-27 02:04:50','2026-05-27 06:10:15'),
(102,9,'SUS006','8996008112323','Popok Bayi Mommy Poko',75000.00,90000.00,7,3,NULL,1,'2026-05-27 04:52:09','2026-05-27 06:16:42');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `purchase_order_items`
--

DROP TABLE IF EXISTS `purchase_order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_order_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `purchase_order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  KEY `purchase_order_items_product_id_foreign` (`product_id`),
  CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_order_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `purchase_order_items` WRITE;
/*!40000 ALTER TABLE `purchase_order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_order_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `purchase_orders`
--

DROP TABLE IF EXISTS `purchase_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `purchase_orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `po_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `approved_by` bigint(20) unsigned DEFAULT NULL,
  `received_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  KEY `purchase_orders_created_by_foreign` (`created_by`),
  KEY `purchase_orders_approved_by_foreign` (`approved_by`),
  KEY `purchase_orders_branch_id_foreign` (`branch_id`),
  CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `purchase_orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `purchase_orders`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `purchase_orders` WRITE;
/*!40000 ALTER TABLE `purchase_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `purchase_orders` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sale_items`
--

DROP TABLE IF EXISTS `sale_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sale_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sale_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sale_items_sale_id_foreign` (`sale_id`),
  KEY `sale_items_product_id_foreign` (`product_id`),
  CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sale_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sale_items` WRITE;
/*!40000 ALTER TABLE `sale_items` DISABLE KEYS */;
INSERT INTO `sale_items` VALUES
(1,1,23,1,3000.00,3000.00,'2026-05-27 05:56:42','2026-05-27 05:56:42'),
(2,1,20,1,38000.00,38000.00,'2026-05-27 05:56:42','2026-05-27 05:56:42'),
(3,2,62,1,11000.00,11000.00,'2026-05-27 06:06:06','2026-05-27 06:06:06'),
(4,2,42,1,7500.00,7500.00,'2026-05-27 06:06:06','2026-05-27 06:06:06'),
(5,2,73,1,13000.00,13000.00,'2026-05-27 06:06:06','2026-05-27 06:06:06'),
(6,3,102,1,90000.00,90000.00,'2026-05-27 06:10:15','2026-05-27 06:10:15'),
(7,3,93,1,88000.00,88000.00,'2026-05-27 06:10:15','2026-05-27 06:10:15'),
(8,3,53,1,3500.00,3500.00,'2026-05-27 06:10:15','2026-05-27 06:10:15'),
(9,3,100,1,28000.00,28000.00,'2026-05-27 06:10:15','2026-05-27 06:10:15'),
(10,3,96,1,24000.00,24000.00,'2026-05-27 06:10:15','2026-05-27 06:10:15'),
(11,3,61,1,5500.00,5500.00,'2026-05-27 06:10:15','2026-05-27 06:10:15'),
(12,3,88,1,28000.00,28000.00,'2026-05-27 06:10:15','2026-05-27 06:10:15'),
(13,4,74,1,68000.00,68000.00,'2026-05-27 06:13:03','2026-05-27 06:13:03'),
(14,5,82,1,6000.00,6000.00,'2026-05-27 06:13:25','2026-05-27 06:13:25'),
(15,5,44,1,4000.00,4000.00,'2026-05-27 06:13:25','2026-05-27 06:13:25'),
(16,6,35,1,2500.00,2500.00,'2026-05-27 06:13:49','2026-05-27 06:13:49'),
(17,6,77,1,32000.00,32000.00,'2026-05-27 06:13:49','2026-05-27 06:13:49'),
(18,6,6,1,15000.00,15000.00,'2026-05-27 06:13:49','2026-05-27 06:13:49'),
(19,7,102,1,90000.00,90000.00,'2026-05-27 06:15:59','2026-05-27 06:15:59'),
(20,8,102,1,90000.00,90000.00,'2026-05-27 06:16:42','2026-05-27 06:16:42');
/*!40000 ALTER TABLE `sale_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sales`
--

DROP TABLE IF EXISTS `sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(255) NOT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax_rate` decimal(5,2) DEFAULT NULL,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `payment_method` varchar(255) NOT NULL,
  `paid_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `change_amount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `voided_at` timestamp NULL DEFAULT NULL,
  `cashier_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `voided_by` bigint(20) unsigned DEFAULT NULL,
  `void_reason` text DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sales_invoice_number_unique` (`invoice_number`),
  KEY `sales_cashier_id_foreign` (`cashier_id`),
  KEY `sales_voided_by_foreign` (`voided_by`),
  KEY `sales_branch_id_foreign` (`branch_id`),
  CONSTRAINT `sales_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sales_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`),
  CONSTRAINT `sales_voided_by_foreign` FOREIGN KEY (`voided_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sales`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sales` WRITE;
/*!40000 ALTER TABLE `sales` DISABLE KEYS */;
INSERT INTO `sales` VALUES
(1,'INV-20260527-0001',NULL,41000.00,0.00,4100.00,NULL,45100.00,'cash',50000.00,4900.00,NULL,2,'2026-05-27 05:56:42','2026-05-27 05:56:42',NULL,NULL,1),
(2,'INV-20260527-0002',NULL,31500.00,0.00,1575.00,NULL,33075.00,'cash',0.00,0.00,NULL,2,'2026-05-27 06:06:06','2026-05-27 06:06:06',NULL,NULL,1),
(3,'INV-20260527-0003',NULL,267000.00,0.00,13350.00,NULL,280350.00,'cash',300000.00,19650.00,NULL,2,'2026-05-27 06:10:15','2026-05-27 06:10:15',NULL,NULL,1),
(4,'INV-20260527-0004',NULL,68000.00,0.00,3400.00,0.05,71400.00,'e-wallet',0.00,0.00,NULL,2,'2026-05-27 06:13:03','2026-05-27 06:13:03',NULL,NULL,1),
(5,'INV-20260527-0005',NULL,10000.00,0.00,500.00,0.05,10500.00,'qris',0.00,0.00,NULL,2,'2026-05-27 06:13:25','2026-05-27 06:13:25',NULL,NULL,1),
(6,'INV-20260527-0006',NULL,49500.00,0.00,2475.00,0.05,51975.00,'transfer',0.00,0.00,NULL,2,'2026-05-27 06:13:49','2026-05-27 06:13:49',NULL,NULL,1),
(7,'INV-20260527-0007',NULL,90000.00,0.00,0.00,0.00,90000.00,'cash',100000.00,10000.00,NULL,2,'2026-05-27 06:15:59','2026-05-27 06:15:59',NULL,NULL,1),
(8,'INV-20260527-0008',NULL,90000.00,0.00,0.00,0.00,90000.00,'transfer',0.00,0.00,NULL,2,'2026-05-27 06:16:42','2026-05-27 06:16:42',NULL,NULL,1);
/*!40000 ALTER TABLE `sales` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES
('8Vqwx8vMmMwo8SU4lv5a5RxSl4EJu9UCLbFDCVJm',2,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:151.0) Gecko/20100101 Firefox/151.0','eyJfdG9rZW4iOiJTVlFhaWJUZ3pkS3Y1ZE45aVJpVmxGSDdTc0g2OHp5UlE0a2JMbTNCIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9wb3MiLCJyb3V0ZSI6InBvcy5pbmRleCJ9fQ==',1779888320),
('T02huHuu7CMDhM8gu5sqOD6D2krioAa1YIf2QPrj',1,'127.0.0.1','Mozilla/5.0 (X11; Linux x86_64; rv:151.0) Gecko/20100101 Firefox/151.0','eyJfdG9rZW4iOiJBVmJwbjRxMTJ0UW1DYWt1S3dOZjY3NHVDbWpGQTJObll0MEJwcHVrIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9yZXBvcnRzIiwicm91dGUiOiJyZXBvcnRzLmluZGV4In19',1779888317);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES
(1,'tax_rate','0','2026-05-27 05:30:16','2026-05-27 06:14:58');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_in_items`
--

DROP TABLE IF EXISTS `stock_in_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_in_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stock_in_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `subtotal` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_in_items_stock_in_id_foreign` (`stock_in_id`),
  KEY `stock_in_items_product_id_foreign` (`product_id`),
  CONSTRAINT `stock_in_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_in_items_stock_in_id_foreign` FOREIGN KEY (`stock_in_id`) REFERENCES `stock_ins` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_in_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_in_items` WRITE;
/*!40000 ALTER TABLE `stock_in_items` DISABLE KEYS */;
INSERT INTO `stock_in_items` VALUES
(1,1,1,100,2500.00,250000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(2,1,2,100,2500.00,250000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(3,1,3,50,4000.00,200000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(4,1,4,80,2500.00,200000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(5,1,5,80,2500.00,200000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(6,1,6,30,12000.00,360000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(7,1,7,15,60000.00,900000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(8,1,8,40,22000.00,880000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(9,1,9,50,14000.00,700000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(10,1,10,30,8000.00,240000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(11,2,21,120,2000.00,240000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(12,2,22,100,2500.00,250000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(13,2,23,120,2000.00,240000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(14,2,24,60,4000.00,240000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(15,2,25,50,4500.00,225000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(16,2,26,50,4500.00,225000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(17,2,27,50,4500.00,225000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(18,2,36,60,7000.00,420000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(19,2,37,50,7000.00,350000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(20,2,38,50,6500.00,325000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(21,3,39,40,6000.00,240000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(22,3,40,50,6500.00,325000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(23,3,41,60,8000.00,480000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(24,3,42,70,5000.00,350000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(25,3,43,40,12000.00,480000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(26,3,44,100,2500.00,250000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(27,3,45,100,2500.00,250000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(28,3,46,40,8000.00,320000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(29,3,47,30,10000.00,300000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(30,3,48,25,12000.00,300000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(31,4,51,100,3500.00,350000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(32,4,52,60,5000.00,300000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(33,4,53,200,2000.00,400000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(34,4,54,40,12000.00,480000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(35,4,55,80,3000.00,240000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(36,4,56,100,2000.00,200000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(37,4,57,50,4000.00,200000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(38,4,61,80,4000.00,320000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(39,4,62,60,8000.00,480000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(40,4,63,50,4000.00,200000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(41,5,64,40,5000.00,200000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(42,5,65,50,8000.00,400000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(43,5,66,40,11000.00,440000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(44,5,67,40,7000.00,280000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(45,5,68,40,5000.00,200000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(46,5,69,30,10000.00,300000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(47,5,70,60,6000.00,360000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(48,5,71,50,14000.00,700000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(49,5,72,40,13500.00,540000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(50,5,73,30,10000.00,300000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(51,6,74,15,55000.00,825000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(52,6,75,30,12000.00,360000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(53,6,76,20,28000.00,560000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(54,6,77,20,25000.00,500000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(55,6,78,15,35000.00,525000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(56,6,79,20,27000.00,540000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(57,6,11,30,14000.00,420000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(58,6,12,30,11000.00,330000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(59,6,13,50,3500.00,175000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(60,6,14,30,9500.00,285000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(61,7,15,40,6500.00,260000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(62,7,16,50,9000.00,450000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(63,7,17,20,24000.00,480000.00,'2026-05-27 02:04:50','2026-05-27 02:04:50'),
(64,7,18,40,5000.00,200000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(65,7,19,15,18000.00,270000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(66,7,20,10,30000.00,300000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(67,7,49,25,2000.00,50000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(68,7,50,30,4000.00,120000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(69,8,28,40,5000.00,200000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(70,8,29,40,5500.00,220000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(71,8,30,40,6000.00,240000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(72,8,31,25,10000.00,250000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(73,8,32,50,6000.00,300000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(74,8,33,40,5500.00,220000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(75,8,34,30,5500.00,165000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(76,8,35,100,1500.00,150000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(77,9,81,40,5000.00,200000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(78,9,82,50,4000.00,200000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(79,9,83,30,8000.00,240000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(80,9,84,25,14000.00,350000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(81,9,85,20,15000.00,300000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(82,9,86,10,25000.00,250000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(83,9,87,20,8000.00,160000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(84,9,88,15,20000.00,300000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(85,9,89,15,18000.00,270000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(86,9,90,20,12000.00,240000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(87,10,91,10,32000.00,320000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(88,10,92,10,35000.00,350000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(89,10,93,8,70000.00,560000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(90,10,94,20,18000.00,360000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(91,10,95,15,22000.00,330000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(92,10,96,25,18000.00,450000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(93,10,97,20,20000.00,400000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(94,10,98,25,18000.00,450000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(95,10,99,20,20000.00,400000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(96,10,100,20,22000.00,440000.00,'2026-05-27 02:04:51','2026-05-27 02:04:51'),
(97,11,102,10,75000.00,750000.00,'2026-05-27 05:01:16','2026-05-27 05:01:16');
/*!40000 ALTER TABLE `stock_in_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_ins`
--

DROP TABLE IF EXISTS `stock_ins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_ins` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `invoice_number` varchar(255) DEFAULT NULL,
  `invoice_image` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_ins_supplier_id_foreign` (`supplier_id`),
  KEY `stock_ins_created_by_foreign` (`created_by`),
  KEY `stock_ins_branch_id_foreign` (`branch_id`),
  CONSTRAINT `stock_ins_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_ins_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `stock_ins_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_ins`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_ins` WRITE;
/*!40000 ALTER TABLE `stock_ins` DISABLE KEYS */;
INSERT INTO `stock_ins` VALUES
(1,1,'INV-20260526-001',NULL,'Restok rutin bulan Mei - Makanan & Minuman',1,'2026-05-27 02:04:50','2026-05-27 02:04:50',1),
(2,2,'INV-20260526-002',NULL,'Restok minuman dan snack',1,'2026-05-27 02:04:50','2026-05-27 02:04:50',1),
(3,3,'INV-20260526-003',NULL,'Restok snack dan cemilan',1,'2026-05-27 02:04:50','2026-05-27 02:04:50',1),
(4,4,'INV-20260526-004',NULL,'Restok alat tulis dan kebersihan',1,'2026-05-27 02:04:50','2026-05-27 02:04:50',1),
(5,5,'INV-20260526-005',NULL,'Restok sembako dan kebersihan',1,'2026-05-27 02:04:50','2026-05-27 02:04:50',1),
(6,6,'INV-20260526-006',NULL,'Restok sembako dan bumbu dapur',1,'2026-05-27 02:04:50','2026-05-27 02:04:50',1),
(7,1,'INV-20260526-007',NULL,'Restok makanan tambahan - mie instan dan sarden',1,'2026-05-27 02:04:50','2026-05-27 02:04:50',1),
(8,2,'INV-20260526-008',NULL,'Restok minuman dan snack ringan',1,'2026-05-27 02:04:51','2026-05-27 02:04:51',1),
(9,3,'INV-20260526-009',NULL,'Restok barang khusus - obat, peralatan rumah, susu',1,'2026-05-27 02:04:51','2026-05-27 02:04:51',1),
(10,4,'INV-20260526-010',NULL,'Restok susu bayi dan rokok',1,'2026-05-27 02:04:51','2026-05-27 02:04:51',1),
(11,3,'172638363833','stock-invoices/QJIrJbxMtNPRQlxg4jsWJlHwnQc8vY4uvc5OtCdM.jpg',NULL,1,'2026-05-27 05:01:16','2026-05-27 05:01:16',1);
/*!40000 ALTER TABLE `stock_ins` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint(20) unsigned NOT NULL,
  `type` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `before_stock` int(11) NOT NULL,
  `after_stock` int(11) NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  KEY `stock_movements_branch_id_foreign` (`branch_id`),
  CONSTRAINT `stock_movements_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=118 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES
(1,1,'in',100,0,100,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(2,2,'in',100,0,100,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(3,3,'in',50,0,50,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(4,4,'in',80,0,80,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(5,5,'in',80,0,80,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(6,6,'in',30,0,30,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(7,7,'in',15,0,15,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(8,8,'in',40,0,40,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(9,9,'in',50,0,50,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(10,10,'in',30,0,30,'stock_in',1,1,'2026-05-27 09:04:50',NULL),
(11,21,'in',120,0,120,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(12,22,'in',100,0,100,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(13,23,'in',120,0,120,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(14,24,'in',60,0,60,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(15,25,'in',50,0,50,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(16,26,'in',50,0,50,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(17,27,'in',50,0,50,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(18,36,'in',60,0,60,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(19,37,'in',50,0,50,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(20,38,'in',50,0,50,'stock_in',2,1,'2026-05-27 09:04:50',NULL),
(21,39,'in',40,0,40,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(22,40,'in',50,0,50,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(23,41,'in',60,0,60,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(24,42,'in',70,0,70,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(25,43,'in',40,0,40,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(26,44,'in',100,0,100,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(27,45,'in',100,0,100,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(28,46,'in',40,0,40,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(29,47,'in',30,0,30,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(30,48,'in',25,0,25,'stock_in',3,1,'2026-05-27 09:04:50',NULL),
(31,51,'in',100,0,100,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(32,52,'in',60,0,60,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(33,53,'in',200,0,200,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(34,54,'in',40,0,40,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(35,55,'in',80,0,80,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(36,56,'in',100,0,100,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(37,57,'in',50,0,50,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(38,61,'in',80,0,80,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(39,62,'in',60,0,60,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(40,63,'in',50,0,50,'stock_in',4,1,'2026-05-27 09:04:50',NULL),
(41,64,'in',40,0,40,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(42,65,'in',50,0,50,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(43,66,'in',40,0,40,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(44,67,'in',40,0,40,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(45,68,'in',40,0,40,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(46,69,'in',30,0,30,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(47,70,'in',60,0,60,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(48,71,'in',50,0,50,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(49,72,'in',40,0,40,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(50,73,'in',30,0,30,'stock_in',5,1,'2026-05-27 09:04:50',NULL),
(51,74,'in',15,0,15,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(52,75,'in',30,0,30,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(53,76,'in',20,0,20,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(54,77,'in',20,0,20,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(55,78,'in',15,0,15,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(56,79,'in',20,0,20,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(57,11,'in',30,0,30,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(58,12,'in',30,0,30,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(59,13,'in',50,0,50,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(60,14,'in',30,0,30,'stock_in',6,1,'2026-05-27 09:04:50',NULL),
(61,15,'in',40,0,40,'stock_in',7,1,'2026-05-27 09:04:50',NULL),
(62,16,'in',50,0,50,'stock_in',7,1,'2026-05-27 09:04:50',NULL),
(63,17,'in',20,0,20,'stock_in',7,1,'2026-05-27 09:04:51',NULL),
(64,18,'in',40,0,40,'stock_in',7,1,'2026-05-27 09:04:51',NULL),
(65,19,'in',15,0,15,'stock_in',7,1,'2026-05-27 09:04:51',NULL),
(66,20,'in',10,0,10,'stock_in',7,1,'2026-05-27 09:04:51',NULL),
(67,49,'in',25,0,25,'stock_in',7,1,'2026-05-27 09:04:51',NULL),
(68,50,'in',30,0,30,'stock_in',7,1,'2026-05-27 09:04:51',NULL),
(69,28,'in',40,0,40,'stock_in',8,1,'2026-05-27 09:04:51',NULL),
(70,29,'in',40,0,40,'stock_in',8,1,'2026-05-27 09:04:51',NULL),
(71,30,'in',40,0,40,'stock_in',8,1,'2026-05-27 09:04:51',NULL),
(72,31,'in',25,0,25,'stock_in',8,1,'2026-05-27 09:04:51',NULL),
(73,32,'in',50,0,50,'stock_in',8,1,'2026-05-27 09:04:51',NULL),
(74,33,'in',40,0,40,'stock_in',8,1,'2026-05-27 09:04:51',NULL),
(75,34,'in',30,0,30,'stock_in',8,1,'2026-05-27 09:04:51',NULL),
(76,35,'in',100,0,100,'stock_in',8,1,'2026-05-27 09:04:51',NULL),
(77,81,'in',40,0,40,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(78,82,'in',50,0,50,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(79,83,'in',30,0,30,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(80,84,'in',25,0,25,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(81,85,'in',20,0,20,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(82,86,'in',10,0,10,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(83,87,'in',20,0,20,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(84,88,'in',15,0,15,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(85,89,'in',15,0,15,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(86,90,'in',20,0,20,'stock_in',9,1,'2026-05-27 09:04:51',NULL),
(87,91,'in',10,0,10,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(88,92,'in',10,0,10,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(89,93,'in',8,0,8,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(90,94,'in',20,0,20,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(91,95,'in',15,0,15,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(92,96,'in',25,0,25,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(93,97,'in',20,0,20,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(94,98,'in',25,0,25,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(95,99,'in',20,0,20,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(96,100,'in',20,0,20,'stock_in',10,1,'2026-05-27 09:04:51',NULL),
(97,102,'in',10,0,10,'stock_in',11,1,'2026-05-27 05:01:16',1),
(98,23,'sale',1,120,119,'sale',1,2,'2026-05-27 05:56:42',1),
(99,20,'sale',1,10,9,'sale',1,2,'2026-05-27 05:56:42',1),
(100,62,'sale',1,60,59,'sale',2,2,'2026-05-27 06:06:06',1),
(101,42,'sale',1,70,69,'sale',2,2,'2026-05-27 06:06:06',1),
(102,73,'sale',1,30,29,'sale',2,2,'2026-05-27 06:06:06',1),
(103,102,'sale',1,10,9,'sale',3,2,'2026-05-27 06:10:15',1),
(104,93,'sale',1,8,7,'sale',3,2,'2026-05-27 06:10:15',1),
(105,53,'sale',1,200,199,'sale',3,2,'2026-05-27 06:10:15',1),
(106,100,'sale',1,20,19,'sale',3,2,'2026-05-27 06:10:15',1),
(107,96,'sale',1,25,24,'sale',3,2,'2026-05-27 06:10:15',1),
(108,61,'sale',1,80,79,'sale',3,2,'2026-05-27 06:10:15',1),
(109,88,'sale',1,15,14,'sale',3,2,'2026-05-27 06:10:15',1),
(110,74,'sale',1,15,14,'sale',4,2,'2026-05-27 06:13:03',1),
(111,82,'sale',1,50,49,'sale',5,2,'2026-05-27 06:13:25',1),
(112,44,'sale',1,100,99,'sale',5,2,'2026-05-27 06:13:25',1),
(113,35,'sale',1,100,99,'sale',6,2,'2026-05-27 06:13:49',1),
(114,77,'sale',1,20,19,'sale',6,2,'2026-05-27 06:13:49',1),
(115,6,'sale',1,30,29,'sale',6,2,'2026-05-27 06:13:49',1),
(116,102,'sale',1,9,8,'sale',7,2,'2026-05-27 06:15:59',1),
(117,102,'sale',1,8,7,'sale',8,2,'2026-05-27 06:16:42',1);
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_out_items`
--

DROP TABLE IF EXISTS `stock_out_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_out_items` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `stock_out_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `qty` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_out_items_stock_out_id_foreign` (`stock_out_id`),
  KEY `stock_out_items_product_id_foreign` (`product_id`),
  CONSTRAINT `stock_out_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_out_items_stock_out_id_foreign` FOREIGN KEY (`stock_out_id`) REFERENCES `stock_outs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_out_items`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_out_items` WRITE;
/*!40000 ALTER TABLE `stock_out_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_out_items` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `stock_outs`
--

DROP TABLE IF EXISTS `stock_outs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_outs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_outs_created_by_foreign` (`created_by`),
  KEY `stock_outs_branch_id_foreign` (`branch_id`),
  CONSTRAINT `stock_outs_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  CONSTRAINT `stock_outs_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_outs`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `stock_outs` WRITE;
/*!40000 ALTER TABLE `stock_outs` DISABLE KEYS */;
/*!40000 ALTER TABLE `stock_outs` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `suppliers`
--

DROP TABLE IF EXISTS `suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `suppliers` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `suppliers`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `suppliers` WRITE;
/*!40000 ALTER TABLE `suppliers` DISABLE KEYS */;
INSERT INTO `suppliers` VALUES
(1,'PT Sumber Makmur Sejahtera','021-5551234','Jl. Industri Raya No. 45, Jakarta Utara','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(2,'CV Berkah Jaya Abadi','021-5555678','Jl. Merdeka No. 88, Bandung','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(3,'UD Maju Lancar','0341-5559012','Jl. Veteran No. 12, Malang','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(4,'PT Indah Logistik Utama','031-5553456','Jl. Raya Manyar No. 67, Surabaya','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(5,'CV Niaga Lestari','061-5557890','Jl. Diponegoro No. 23, Medan','2026-05-27 02:04:50','2026-05-27 02:04:50'),
(6,'PT Bintang Suplai','021-5552345','Jl. Krakatau No. 100, Tangerang','2026-05-27 02:04:50','2026-05-27 02:04:50');
/*!40000 ALTER TABLE `suppliers` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'kasir',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  KEY `users_branch_id_foreign` (`branch_id`),
  CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

SET @OLD_AUTOCOMMIT=@@AUTOCOMMIT, @@AUTOCOMMIT=0;
LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'Admin','admin@pos.test','2026-05-27 02:04:36','$2y$12$8s3J2ETO/R3VBV4fJ2e87eo.0cwK0GQ2.Okji0Sr3HiaFkxsBScr6','admin','mxZzHm8mEgAbr5AWLjMA7ZH32MuGqMdmT9cURbnoBDLqCHjsfcL0ATIsZHb9','2026-05-27 02:04:36','2026-05-27 02:04:36',1),
(2,'Kasir','kasir@pos.test','2026-05-27 02:04:36','$2y$12$.D4Rhj1FGqMQtijh/hKdO.bPVytNdBgcHBAivOE8Dr91Vlw0mxrci','kasir','RhE5KP9haLlQ77RaIVs47llbtxOnE35CXzRZFzH7lnqWrVum4MBztr7t9LKV','2026-05-27 02:04:36','2026-05-27 02:04:36',1),
(3,'Owner','owner@pos.test','2026-05-27 02:04:37','$2y$12$K3usBs.i1B.gaphuETQxSunKqxHVqDMumBS/r8rVUTHDfp/CKDbR2','owner','hle0GPeXMNhddSWicWCAznyyjNxRuS5TOCuBmvbYcEuVL4c0aqNvguNpMSmh','2026-05-27 02:04:37','2026-05-27 02:04:37',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
COMMIT;
SET AUTOCOMMIT=@OLD_AUTOCOMMIT;

--
-- Dumping routines for database 'pos-inventory'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*M!100616 SET NOTE_VERBOSITY=@OLD_NOTE_VERBOSITY */;

-- Dump completed on 2026-05-27 20:25:23
