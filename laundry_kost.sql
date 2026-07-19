-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for laundry_kost
CREATE DATABASE IF NOT EXISTS `laundry_kost` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `laundry_kost`;

-- Dumping structure for table laundry_kost.cache
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.cache: ~0 rows (approximately)

-- Dumping structure for table laundry_kost.cache_locks
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.cache_locks: ~0 rows (approximately)

-- Dumping structure for table laundry_kost.customers
CREATE TABLE IF NOT EXISTS `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.customers: ~3 rows (approximately)
INSERT INTO `customers` (`id`, `name`, `phone`, `address`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 'Budi Santoso', '081234567890', 'Jl. Mawar No. 12, Jakarta', 'Pelanggan setia laundry kiloan.', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(2, 'Siti Rahma', '081987654321', 'Kost Lestari, Kamar 101', 'Penghuni Kost Kamar 101.', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(3, 'Andi Wijaya', '085678901234', 'Kost Lestari, Kamar 102', 'Penghuni Kost Kamar 102.', '2026-07-13 09:08:46', '2026-07-13 09:08:46');

-- Dumping structure for table laundry_kost.deliveries
CREATE TABLE IF NOT EXISTS `deliveries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `laundry_order_id` bigint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `delivery_date` date DEFAULT NULL,
  `delivery_time` time DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deliveries_laundry_order_id_foreign` (`laundry_order_id`),
  CONSTRAINT `deliveries_laundry_order_id_foreign` FOREIGN KEY (`laundry_order_id`) REFERENCES `laundry_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.deliveries: ~5 rows (approximately)
INSERT INTO `deliveries` (`id`, `laundry_order_id`, `type`, `status`, `delivery_date`, `delivery_time`, `address`, `notes`, `created_at`, `updated_at`) VALUES
	(1, 2, 'pickup', 'completed', '2026-07-12', '12:00:00', 'Kost Lestari, Kamar 101', 'Siti Rahma laundry bag hijau.', '2026-07-13 09:08:46', '2026-07-13 10:00:07'),
	(2, 4, 'delivery', 'completed', '2026-07-13', '17:00:00', 'Jl. Mawar No. 12, Jakarta', 'Budi Santoso - harap telepon dulu.', '2026-07-13 09:08:46', '2026-07-13 09:55:02'),
	(3, 5, 'pickup', 'completed', '2026-07-17', '10:00:00', 'Jl. Mawar No. 12, Jakarta', 'Jadwal penjemputan awal.', '2026-07-14 18:33:13', '2026-07-14 18:33:37'),
	(4, 6, 'pickup', 'completed', '2026-07-15', '09:00:00', 'Jl. Mawar No. 12, Jakarta', 'Jadwal penjemputan awal.', '2026-07-14 18:37:33', '2026-07-14 18:38:11'),
	(5, 6, 'delivery', 'completed', '2026-07-16', '09:00:00', 'Jl. Mawar No. 12, Jakarta', 'Jadwal pengantaran estimasi selesai.', '2026-07-14 18:37:33', '2026-07-14 18:38:22');

-- Dumping structure for table laundry_kost.failed_jobs
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.failed_jobs: ~0 rows (approximately)

-- Dumping structure for table laundry_kost.finance_transactions
CREATE TABLE IF NOT EXISTS `finance_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `sourceable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sourceable_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `finance_transactions_sourceable_type_sourceable_id_index` (`sourceable_type`,`sourceable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.finance_transactions: ~21 rows (approximately)
INSERT INTO `finance_transactions` (`id`, `type`, `category`, `date`, `amount`, `payment_method`, `notes`, `sourceable_type`, `sourceable_id`, `created_at`, `updated_at`) VALUES
	(1, 'income', 'laundry', '2026-07-10', 28000.00, 'cash', 'Pembayaran ORD-20260710-001 (Lunas)', 'App\\Models\\LaundryOrder', 1, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(2, 'income', 'laundry', '2026-07-12', 33000.00, 'transfer', 'Pembayaran ORD-20260712-002 (Lunas + Pickup)', 'App\\Models\\LaundryOrder', 2, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(3, 'income', 'laundry', '2026-07-13', 15000.00, 'ewallet', 'Pembayaran DP ORD-20260713-003', 'App\\Models\\LaundryOrder', 3, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(4, 'income', 'kost', '2026-06-20', 1500000.00, 'transfer', 'Sewa Bulanan Kamar 101 - Siti Rahma', 'App\\Models\\Tenant', 1, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(5, 'income', 'kost', '2026-06-16', 1500000.00, 'transfer', 'Sewa Bulanan Kamar 102 - Andi Wijaya', 'App\\Models\\Tenant', 2, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(6, 'income', 'kost', '2026-06-13', 1800000.00, 'cash', 'Sewa Bulanan Kamar 105 - Eko Prasetyo', 'App\\Models\\Tenant', 3, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(7, 'expense', 'listrik', '2026-07-05', 600000.00, 'transfer', 'Token Listrik Token Utama Kost & Laundry', NULL, NULL, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(9, 'expense', 'air', '2026-07-06', 250000.00, 'transfer', 'Tagihan Air PDAM Bulan Juni', NULL, NULL, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(11, 'income', 'laundry', '2026-07-14', 15000.00, 'cash', 'Pelunasan/Cicilan tambahan order ORD-20260713-003 (lunas)', 'App\\Models\\LaundryOrder', 3, '2026-07-13 17:46:09', '2026-07-13 17:46:09'),
	(12, 'income', 'laundry', '2026-07-14', 35000.00, 'cash', 'Pelunasan/Cicilan tambahan order ORD-20260712-004 (lunas)', 'App\\Models\\LaundryOrder', 4, '2026-07-13 17:46:25', '2026-07-13 17:46:25'),
	(13, 'income', 'kost', '2026-07-15', 2400000.00, 'transfer', 'Pembayaran awal sewa & deposit Kamar 106 - Penghuni: Aufa', 'App\\Models\\Tenant', 5, '2026-07-14 18:26:08', '2026-07-14 18:26:08'),
	(14, 'income', 'kost', '2026-07-15', 1500000.00, 'cash', 'Perpanjangan sewa Kost Kamar 102 - Andi Wijaya (1 bulan)', NULL, NULL, '2026-07-14 18:27:43', '2026-07-14 18:27:43'),
	(15, 'income', 'kost', '2026-07-15', 1500000.00, 'cash', 'Perpanjangan sewa Kost Kamar 102 - Andi Wijaya (1 bulan)', NULL, NULL, '2026-07-14 18:27:56', '2026-07-14 18:27:56'),
	(16, 'income', 'kost', '2026-07-15', 1800000.00, 'cash', 'Perpanjangan sewa Kost Kamar 105 - Eko Prasetyo (1 bulan)', NULL, NULL, '2026-07-14 18:28:06', '2026-07-14 18:28:06'),
	(17, 'income', 'laundry', '2026-07-15', 19000.00, 'cash', 'Pembayaran awal order ORD-20260715-001 (lunas)', 'App\\Models\\LaundryOrder', 5, '2026-07-14 18:33:13', '2026-07-14 18:33:13'),
	(18, 'income', 'kost', '2026-07-15', 1800000.00, 'cash', 'Perpanjangan sewa Kost Kamar 105 - Eko Prasetyo (1 bulan)', 'App\\Models\\Tenant', 3, '2026-07-14 18:34:36', '2026-07-14 18:34:36'),
	(19, 'income', 'laundry', '2026-07-15', 12000.00, 'cash', 'Pembayaran awal order ORD-20260715-002 (lunas)', 'App\\Models\\LaundryOrder', 6, '2026-07-14 18:37:33', '2026-07-14 18:37:33'),
	(20, 'income', 'kost', '2026-07-15', 2000000.00, 'transfer', 'Pembayaran awal sewa & deposit Kamar 104 - Penghuni: Viktor', 'App\\Models\\Tenant', 6, '2026-07-14 18:40:47', '2026-07-14 18:40:47'),
	(21, 'income', 'kost', '2026-07-15', 1800000.00, 'cash', 'Perpanjangan sewa Kost Kamar 105 - Eko Prasetyo (1 bulan)', 'App\\Models\\Tenant', 3, '2026-07-14 23:15:01', '2026-07-14 23:15:01'),
	(22, 'income', 'kost', '2026-07-15', 1800000.00, 'cash', 'Perpanjangan sewa Kost Kamar 104 - Viktor (1 bulan)', 'App\\Models\\Tenant', 6, '2026-07-14 23:15:29', '2026-07-14 23:15:29'),
	(23, 'income', 'laundry', '2026-07-15', 6000.00, 'cash', 'Pelunasan/Cicilan tambahan order ORD-20260715-003 (lunas)', 'App\\Models\\LaundryOrder', 7, '2026-07-14 23:20:15', '2026-07-14 23:20:15');

-- Dumping structure for table laundry_kost.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.jobs: ~0 rows (approximately)

-- Dumping structure for table laundry_kost.job_batches
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.job_batches: ~0 rows (approximately)

-- Dumping structure for table laundry_kost.laundry_orders
CREATE TABLE IF NOT EXISTS `laundry_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `weight` decimal(8,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `additional_fees` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_price` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'belum_bayar',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'baru',
  `delivery_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'none',
  `estimation_date` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `laundry_orders_order_number_unique` (`order_number`),
  KEY `laundry_orders_customer_id_foreign` (`customer_id`),
  KEY `laundry_orders_service_id_foreign` (`service_id`),
  KEY `laundry_orders_created_by_foreign` (`created_by`),
  CONSTRAINT `laundry_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `laundry_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `laundry_orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `laundry_services` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.laundry_orders: ~7 rows (approximately)
INSERT INTO `laundry_orders` (`id`, `order_number`, `customer_id`, `service_id`, `weight`, `price`, `additional_fees`, `total_price`, `paid_amount`, `payment_status`, `payment_method`, `status`, `delivery_type`, `estimation_date`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
	(1, 'ORD-20260710-001', 1, 1, 3.50, 28000.00, 0.00, 28000.00, 28000.00, 'lunas', 'cash', 'diambil_diantar', 'none', '2026-07-10 17:00:00', 'Selesai tepat waktu.', 3, '2026-07-10 03:00:00', '2026-07-13 09:08:46'),
	(2, 'ORD-20260712-002', 2, 2, 5.00, 30000.00, 3000.00, 33000.00, 33000.00, 'lunas', 'transfer', 'diambil_diantar', 'pickup', '2026-07-13 17:00:00', 'Ambil di kamar.', 3, '2026-07-12 04:30:00', '2026-07-13 10:00:07'),
	(3, 'ORD-20260713-003', 3, 3, 1.00, 25000.00, 5000.00, 30000.00, 30000.00, 'lunas', 'cash', 'diambil_diantar', 'none', '2026-07-15 17:00:00', 'Sepatu putih kanvas.', 3, '2026-07-13 02:15:00', '2026-07-13 17:46:09'),
	(4, 'ORD-20260712-004', 1, 1, 4.00, 32000.00, 3000.00, 35000.00, 35000.00, 'lunas', 'cash', 'diambil_diantar', 'delivery', '2026-07-12 17:00:00', 'Siap diantar sore hari.', 3, '2026-07-12 07:00:00', '2026-07-13 17:46:25'),
	(5, 'ORD-20260715-001', 1, 2, 4.00, 16000.00, 3000.00, 19000.00, 19000.00, 'lunas', 'cash', 'diambil_diantar', 'pickup', '2026-07-16 18:33:13', NULL, 1, '2026-07-14 18:33:13', '2026-07-14 18:33:37'),
	(6, 'ORD-20260715-002', 1, 1, 1.00, 6000.00, 6000.00, 12000.00, 12000.00, 'lunas', 'cash', 'diambil_diantar', 'pickup_delivery', '2026-07-15 18:37:33', NULL, 1, '2026-07-14 18:37:33', '2026-07-14 18:38:22'),
	(7, 'ORD-20260715-003', 2, 1, 1.00, 6000.00, 0.00, 6000.00, 6000.00, 'lunas', 'cash', 'diambil_diantar', 'none', '2026-07-15 23:19:02', NULL, 3, '2026-07-14 23:19:02', '2026-07-14 23:20:15');

-- Dumping structure for table laundry_kost.laundry_services
CREATE TABLE IF NOT EXISTS `laundry_services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.laundry_services: ~3 rows (approximately)
INSERT INTO `laundry_services` (`id`, `name`, `price`, `duration_days`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'Laundry 1 Hari', 6000.00, 1, 1, '2026-07-13 09:08:46', '2026-07-13 09:17:49'),
	(2, 'Laundry 2 Hari', 4000.00, 2, 1, '2026-07-13 09:08:46', '2026-07-13 09:18:03'),
	(3, 'Cuci Sepatu', 25000.00, 3, 1, '2026-07-13 09:08:46', '2026-07-13 09:08:46');

-- Dumping structure for table laundry_kost.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.migrations: ~13 rows (approximately)
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '0001_01_01_000001_create_cache_table', 1),
	(3, '0001_01_01_000002_create_jobs_table', 1),
	(4, '2026_07_13_160637_create_settings_table', 1),
	(5, '2026_07_13_160638_create_customers_table', 1),
	(6, '2026_07_13_160638_create_laundry_services_table', 1),
	(7, '2026_07_13_160639_create_laundry_orders_table', 1),
	(8, '2026_07_13_160639_create_rooms_table', 1),
	(9, '2026_07_13_160640_create_finance_transactions_table', 1),
	(10, '2026_07_13_160640_create_tenants_table', 1),
	(11, '2026_07_13_160641_create_notifications_table', 1),
	(12, '2026_07_13_160642_create_deliveries_table', 1),
	(13, '2026_07_13_163131_add_payment_type_to_tenants_table', 2);

-- Dumping structure for table laundry_kost.notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.notifications: ~0 rows (approximately)

-- Dumping structure for table laundry_kost.password_reset_tokens
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.password_reset_tokens: ~0 rows (approximately)

-- Dumping structure for table laundry_kost.rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `room_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'kosong',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `rooms_room_number_unique` (`room_number`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.rooms: ~6 rows (approximately)
INSERT INTO `rooms` (`id`, `room_number`, `price`, `status`, `created_at`, `updated_at`) VALUES
	(1, '101', 1500000.00, 'terisi', '2026-07-13 09:08:46', '2026-07-13 09:57:20'),
	(2, '102', 1500000.00, 'terisi', '2026-07-13 09:08:46', '2026-07-14 18:27:56'),
	(3, '103', 1500000.00, 'kosong', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(4, '104', 1800000.00, 'terisi', '2026-07-13 09:08:46', '2026-07-14 23:15:29'),
	(5, '105', 1800000.00, 'terisi', '2026-07-13 09:08:46', '2026-07-14 23:15:01'),
	(6, '106', 1200000.00, 'terisi', '2026-07-13 09:08:46', '2026-07-14 18:26:08');

-- Dumping structure for table laundry_kost.sessions
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.sessions: ~1 rows (approximately)
INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
	('eXnlGeuqe7a4dDxVMvZtxqP510x8g4ExxeQfBeVc', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', 'eyJfdG9rZW4iOiJGRHNLOWpLNXZQejlhM2oyVWtCSFN0UkVBbG9wWU1ranNpUXFzYUNDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9kYXNoYm9hcmQiLCJyb3V0ZSI6ImRhc2hib2FyZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sInVybCI6W10sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxfQ==', 1784177227);

-- Dumping structure for table laundry_kost.settings
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.settings: ~6 rows (approximately)
INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
	(1, 'business_name', 'Lestari Laundry & Kost', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(2, 'business_address', 'Jl. Merdeka No. 45, Jakarta Selatan', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(3, 'business_phone', '081234567890', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(4, 'fee_express', '5000', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(5, 'fee_pickup', '3000', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(6, 'fee_delivery', '3000', '2026-07-13 09:08:46', '2026-07-13 09:08:46');

-- Dumping structure for table laundry_kost.tenants
CREATE TABLE IF NOT EXISTS `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `room_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `monthly_fee` decimal(10,2) NOT NULL,
  `deposit` decimal(10,2) NOT NULL DEFAULT '0.00',
  `payment_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dimuka',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tenants_room_id_foreign` (`room_id`),
  CONSTRAINT `tenants_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.tenants: ~6 rows (approximately)
INSERT INTO `tenants` (`id`, `name`, `phone`, `room_id`, `start_date`, `end_date`, `monthly_fee`, `deposit`, `payment_type`, `notes`, `status`, `created_at`, `updated_at`) VALUES
	(1, 'Siti Rahma', '081987654321', 1, '2026-06-20', '2026-08-20', 1500000.00, 200000.00, 'dimuka', 'Bayar tepat waktu.', 'aktif', '2026-07-13 09:08:46', '2026-07-13 09:57:20'),
	(2, 'Andi Wijaya', '085678901234', 2, '2026-06-16', '2026-09-16', 1500000.00, 200000.00, 'dimuka', 'Kunci duplikat diberikan.', 'aktif', '2026-07-13 09:08:46', '2026-07-14 18:27:56'),
	(3, 'Eko Prasetyo', '087712345678', 5, '2026-06-13', '2026-10-15', 1800000.00, 200000.00, 'dimuka', 'Rencana perpanjang.', 'aktif', '2026-07-13 09:08:46', '2026-07-14 23:15:01'),
	(4, 'Dewi Sartika', '089988887777', 3, '2026-01-01', '2026-06-01', 1500000.00, 200000.00, 'dimuka', 'Sudah keluar dan deposit dikembalikan.', 'selesai', '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(5, 'Aufa', '08137812361', 6, '2026-07-15', '2026-08-15', 1200000.00, 1200000.00, 'dimuka', NULL, 'aktif', '2026-07-14 18:26:08', '2026-07-14 18:26:08'),
	(6, 'Viktor', '08123712612', 4, '2026-07-15', '2026-09-15', 1800000.00, 200000.00, 'dimuka', NULL, 'aktif', '2026-07-14 18:40:47', '2026-07-14 23:15:29');

-- Dumping structure for table laundry_kost.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'staff',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_username_unique` (`username`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table laundry_kost.users: ~3 rows (approximately)
INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'Administrator', 'admin', 'admin@laundrykost.com', 'admin', NULL, '$2y$12$sPuzxLn15EvfX4RjDDryme5nflx498hj2j9n8m343sO.9MIctTuiC', NULL, '2026-07-13 09:08:45', '2026-07-13 09:08:45'),
	(2, 'Owner Usaha', 'owner', 'owner@laundrykost.com', 'owner', NULL, '$2y$12$rOjwM3vhyoYd4GNLJY7Ng.jBbwQY54tk8syeZDvczXEUpzKaxAMMa', NULL, '2026-07-13 09:08:46', '2026-07-13 09:08:46'),
	(3, 'Staff Laundry', 'staff', 'staff@laundrykost.com', 'staff', NULL, '$2y$12$EfSyXVSoKhzp84nSUNu.1uTAy/AiASITFHBeXZh/G7HgpbbwpeeQC', NULL, '2026-07-13 09:08:46', '2026-07-13 09:08:46');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
