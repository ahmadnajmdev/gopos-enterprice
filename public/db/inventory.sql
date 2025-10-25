-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 20, 2025 at 08:06 PM
-- Server version: 11.5.2-MariaDB
-- PHP Version: 8.3.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('inventory_cache_356a192b7913b04c54574d18c28d46e6395428ab', 'i:1;', 1745179345),
('inventory_cache_356a192b7913b04c54574d18c28d46e6395428ab:timer', 'i:1745179345;', 1745179345),
('inventory_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3', 'i:1;', 1745171202),
('inventory_cache_livewire-rate-limiter:a17961fa74e9275d529f489537f179c05d50c2f3:timer', 'i:1745171202;', 1745171202),
('inventory_cache_settings_', 'a:2:{s:7:\"general\";a:3:{s:10:\"brand_name\";s:11:\"ProSoftware\";s:4:\"logo\";s:36:\"logos/01JSA5WNA68NP46YN0XB84RS55.png\";s:9:\"logo_dark\";N;}s:7:\"invoice\";a:7:{s:12:\"invoice_logo\";N;s:20:\"invoice_footer_title\";s:27:\"Thank you for your business\";s:26:\"invoice_footer_description\";s:66:\"We appreciate your business and look forward to serving you again.\";s:17:\"your_company_name\";N;s:20:\"your_company_address\";N;s:18:\"your_company_phone\";N;s:18:\"your_company_email\";N;}}', 2060532190);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `active`, `created_at`, `updated_at`) VALUES
(1, 'ئامێری ناوماڵ', 1, '2025-04-20 18:31:10', '2025-04-20 18:31:10'),
(2, 'ئامێرەکان', 1, '2025-04-20 18:31:35', '2025-04-20 18:31:35'),
(4, 'کوتاڵ', 1, '2025-04-20 18:32:02', '2025-04-20 18:32:02'),
(5, 'ئەلیکترۆنیات', 1, '2025-04-20 18:32:15', '2025-04-20 18:32:15'),
(6, 'خواردن', 1, '2025-04-20 18:32:46', '2025-04-20 18:32:46');

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversations`
--

CREATE TABLE `chat_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `user_query` text NOT NULL,
  `generated_sql` text DEFAULT NULL,
  `ai_response` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `email`, `phone`, `image`, `address`, `note`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad Najm', NULL, '07517110459', '01JSA8KNZ3K1HB0SW2K0WT4V71.jpg', NULL, NULL, 1, '2025-04-20 18:50:42', '2025-04-20 18:50:42');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_type_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_types`
--

CREATE TABLE `expense_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `income_type_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `income_types`
--

CREATE TABLE `income_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_03_18_162729_create_categories_table', 1),
(5, '2025_03_18_162742_create_units_table', 1),
(6, '2025_03_18_162752_create_products_table', 1),
(7, '2025_03_18_163339_create_product_attributes_table', 1),
(8, '2025_03_18_163622_create_suppliers_table', 1),
(9, '2025_03_19_140244_add_custom_fields_to_users_table', 1),
(10, '2025_03_20_211801_add_avatar_url_to_users_table', 1),
(11, '2025_03_24_180653_create_purchases_table', 1),
(12, '2025_03_24_180705_create_purchase_items_table', 1),
(13, '2025_03_24_180730_create_customers_table', 1),
(14, '2025_03_24_180740_create_sales_table', 1),
(15, '2025_03_24_180750_create_sale_items_table', 1),
(16, '2025_03_25_232200_add_themes_settings_to_users_table', 1),
(17, '2025_04_08_111925_create_expense_types_table', 1),
(18, '2025_04_08_111933_create_expenses_table', 1),
(19, '2025_04_08_111941_create_income_types_table', 1),
(20, '2025_04_08_111947_create_incomes_table', 1),
(21, '2025_04_11_102428_create_settings_table', 1),
(22, '2025_04_12_000000_create_chat_conversations_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `cost` decimal(10,2) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` decimal(8,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `unit_id`, `name`, `description`, `image`, `cost`, `price`, `stock`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'موبەڕیدەی گەورە', NULL, '01JSA7PRWZ101X0A41ET39SCMK.jpg', 300000.00, 345000.00, 5.00, '2025-04-20 18:34:54', '2025-04-20 19:58:21'),
(2, 1, 1, 'پانکەی سەقفی زیرەکی LED', NULL, '01JSA7X4B694D7VMNJBFXXME0C.jpg', 55000.00, 75000.00, 200.00, '2025-04-20 18:38:23', '2025-04-20 19:03:46'),
(3, 1, 1, 'ئامێری جل شۆردن', NULL, '01JSA853YXG5Z6BNAXSW76WGF7.jpg', 120000.00, 155000.00, 5.00, '2025-04-20 18:42:44', '2025-04-20 19:57:32'),
(4, 6, 1, 'ماستی شیرەمەنی و شیر', NULL, '01JSA87MPGC6627PV1K7N7Z069.jpg', 2000.00, 7000.00, 200.00, '2025-04-20 18:44:07', '2025-04-20 19:02:41'),
(5, 6, 1, 'قاوە', NULL, '01JSA89FXNC12K4M0YV2Q86MCE.jpg', 10000.00, 15000.00, 59.00, '2025-04-20 18:45:08', '2025-04-20 19:42:00'),
(6, 4, 1, 'BLAZERS + چاکەتی تاکسیدۆ', NULL, '01JSA8BBRVHPMKZPZMVQPPDNKD.jpg', 30000.00, 45000.00, 16.00, '2025-04-20 18:46:09', '2025-04-20 19:58:50'),
(7, 2, 1, 'پاککەرەوەی هەوای پێشکەوتوو', NULL, '01JSA8E09EW3GHQ4AV23T5WF6W.jpg', 10000.00, 25000.00, 179.00, '2025-04-20 18:47:35', '2025-04-20 19:42:00');

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

CREATE TABLE `product_attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_date` date NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `purchase_number`, `supplier_id`, `purchase_date`, `sub_total`, `discount_amount`, `total_amount`, `paid_amount`, `created_at`, `updated_at`) VALUES
(1, 'PUR-00001', 1, '2025-04-20', 24000000.00, 0.00, 24000000.00, 24000000.00, '2025-04-20 18:57:12', '2025-04-20 19:00:40'),
(2, 'PUR-00002', 1, '2025-04-20', 6000000.00, 0.00, 6000000.00, 6000000.00, '2025-04-20 18:58:17', '2025-04-20 19:00:27'),
(3, 'PUR-00003', 1, '2025-04-20', 2000000.00, 0.00, 2000000.00, 2000000.00, '2025-04-20 18:59:35', '2025-04-20 19:00:10'),
(4, 'PUR-00004', 1, '2025-04-20', 2000000.00, 0.00, 2000000.00, 2000000.00, '2025-04-20 19:01:12', '2025-04-20 19:01:12'),
(5, 'PUR-00005', 1, '2025-04-20', 400000.00, 0.00, 400000.00, 400000.00, '2025-04-20 19:02:41', '2025-04-20 19:02:41'),
(6, 'PUR-00006', 1, '2025-04-20', 11000000.00, 0.00, 11000000.00, 11000000.00, '2025-04-20 19:03:29', '2025-04-20 19:03:46'),
(8, 'PUR-00007', 1, '2025-04-20', 60000000.00, 0.00, 60000000.00, 0.00, '2025-04-20 19:34:02', '2025-04-20 19:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `stock` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `cost`, `stock`, `tax_amount`, `discount_amount`, `total_amount`, `note`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 120000.00, 200.00, 0.00, 0.00, 24000000.00, NULL, '2025-04-20 18:57:12', '2025-04-20 18:57:12'),
(2, 2, 6, 30000.00, 200.00, 0.00, 0.00, 6000000.00, NULL, '2025-04-20 18:58:17', '2025-04-20 18:58:17'),
(3, 3, 7, 10000.00, 200.00, 0.00, 0.00, 2000000.00, NULL, '2025-04-20 18:59:35', '2025-04-20 18:59:35'),
(4, 4, 5, 10000.00, 200.00, 0.00, 0.00, 2000000.00, NULL, '2025-04-20 19:01:12', '2025-04-20 19:01:12'),
(5, 5, 4, 2000.00, 200.00, 0.00, 0.00, 400000.00, NULL, '2025-04-20 19:02:41', '2025-04-20 19:02:41'),
(6, 6, 2, 55000.00, 200.00, 0.00, 0.00, 11000000.00, NULL, '2025-04-20 19:03:29', '2025-04-20 19:03:29'),
(8, 8, 1, 300000.00, 200.00, 0.00, 0.00, 60000000.00, NULL, '2025-04-20 19:34:02', '2025-04-20 19:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `sale_number` varchar(255) NOT NULL,
  `sale_date` date NOT NULL,
  `sub_total` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_id`, `sale_number`, `sale_date`, `sub_total`, `discount`, `total_amount`, `paid_amount`, `note`, `created_at`, `updated_at`) VALUES
(7, 1, 'INV-0001', '2025-04-20', 85000.00, 0.00, 85000.00, 85000.00, NULL, '2025-04-20 19:41:07', '2025-04-20 19:41:07'),
(8, 1, 'INV-0008', '2025-04-20', 29450000.00, 0.00, 29450000.00, 29450000.00, NULL, '2025-04-20 19:42:00', '2025-04-20 19:42:00'),
(9, 1, 'INV-0009', '2025-04-20', 6975000.00, 0.00, 6975000.00, 6975000.00, NULL, '2025-04-20 19:57:32', '2025-04-20 19:57:32'),
(10, 1, 'INV-0010', '2025-04-20', 51750000.00, 0.00, 51750000.00, 51750000.00, NULL, '2025-04-20 19:58:06', '2025-04-20 19:58:06'),
(11, 1, 'INV-0011', '2025-04-20', 15525000.00, 0.00, 15525000.00, 15525000.00, NULL, '2025-04-20 19:58:21', '2025-04-20 19:58:21'),
(12, 1, 'INV-0012', '2025-04-20', 4725000.00, 0.00, 4725000.00, 4725000.00, NULL, '2025-04-20 19:58:50', '2025-04-20 19:58:50');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` decimal(10,2) NOT NULL,
  `tax_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `price`, `stock`, `tax_amount`, `discount_amount`, `total_amount`, `note`, `created_at`, `updated_at`) VALUES
(7, 7, 7, 25000.00, 1.00, 0.00, 0.00, 25000.00, NULL, '2025-04-20 19:41:07', '2025-04-20 19:41:07'),
(8, 7, 6, 45000.00, 1.00, 0.00, 0.00, 45000.00, NULL, '2025-04-20 19:41:07', '2025-04-20 19:41:07'),
(9, 7, 5, 15000.00, 1.00, 0.00, 0.00, 15000.00, NULL, '2025-04-20 19:41:07', '2025-04-20 19:41:07'),
(10, 8, 7, 25000.00, 20.00, 0.00, 0.00, 500000.00, NULL, '2025-04-20 19:42:00', '2025-04-20 19:42:00'),
(11, 8, 6, 45000.00, 80.00, 0.00, 0.00, 3600000.00, NULL, '2025-04-20 19:42:00', '2025-04-20 19:42:00'),
(12, 8, 5, 15000.00, 140.00, 0.00, 0.00, 2100000.00, NULL, '2025-04-20 19:42:00', '2025-04-20 19:42:00'),
(13, 8, 3, 155000.00, 150.00, 0.00, 0.00, 23250000.00, NULL, '2025-04-20 19:42:00', '2025-04-20 19:42:00'),
(14, 9, 3, 155000.00, 45.00, 0.00, 0.00, 6975000.00, NULL, '2025-04-20 19:57:32', '2025-04-20 19:57:32'),
(15, 10, 1, 345000.00, 150.00, 0.00, 0.00, 51750000.00, NULL, '2025-04-20 19:58:06', '2025-04-20 19:58:06'),
(16, 11, 1, 345000.00, 45.00, 0.00, 0.00, 15525000.00, NULL, '2025-04-20 19:58:21', '2025-04-20 19:58:21'),
(17, 12, 6, 45000.00, 105.00, 0.00, 0.00, 4725000.00, NULL, '2025-04-20 19:58:50', '2025-04-20 19:58:50');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('k99kpBDrAXZtTVAMopekTPYXaPK0TrKsqhRuoqo9', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.0.0 Safari/537.36', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiU0RsMzNaZEhXZ3UwU0JvVG5ONDV4VzJqNEl6ZG9Talp6YmdvcDVEVCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vaW52ZW50b3J5LnRlc3QiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkdGF5d2JkY3lOM0lvVEVIZzRrN3RDdXFGejE2WXJsaU5YVk5leklKVzRQRldKU3RRT1A5SFciO3M6ODoiZmlsYW1lbnQiO2E6MDp7fXM6NjoibG9jYWxlIjtzOjM6ImNrYiI7fQ==', 1745179532);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`value`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `created_at`, `updated_at`) VALUES
(1, 'general', '{\"brand_name\":\"MyApp\",\"logo\":null}', '2025-04-20 17:45:30', '2025-04-20 17:45:30'),
(2, 'invoice', '{\"invoice_logo\":null,\"invoice_footer_title\":\"Thank you for your business\",\"invoice_footer_description\":\"We appreciate your business and look forward to serving you again.\"}', '2025-04-20 17:45:30', '2025-04-20 17:45:30'),
(3, 'general.brand_name', '\"ProSoftware\"', '2025-04-20 17:52:21', '2025-04-20 18:02:52'),
(4, 'general.logo', '\"logos\\/01JSA5WNA68NP46YN0XB84RS55.png\"', '2025-04-20 17:52:21', '2025-04-20 18:03:10'),
(5, 'general.logo_dark', NULL, '2025-04-20 17:52:21', '2025-04-20 17:52:43'),
(6, 'invoice.your_company_name', NULL, '2025-04-20 17:52:21', '2025-04-20 17:52:21'),
(7, 'invoice.your_company_address', NULL, '2025-04-20 17:52:21', '2025-04-20 17:52:21'),
(8, 'invoice.your_company_phone', NULL, '2025-04-20 17:52:21', '2025-04-20 17:52:21'),
(9, 'invoice.your_company_email', NULL, '2025-04-20 17:52:21', '2025-04-20 17:52:21'),
(10, 'invoice.invoice_logo', NULL, '2025-04-20 17:52:21', '2025-04-20 17:52:21'),
(11, 'invoice.invoice_footer_title', '\"Thank you for your business\"', '2025-04-20 17:52:21', '2025-04-20 17:52:21'),
(12, 'invoice.invoice_footer_description', '\"We appreciate your business and look forward to serving you again.\"', '2025-04-20 17:52:22', '2025-04-20 17:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `note` text DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`, `email`, `phone`, `image`, `address`, `note`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Ahmad Najm', NULL, '07517110459', '01JSA8Z5RFGY0RDNA3ANWQJKPV.jpg', NULL, NULL, 1, '2025-04-20 18:56:58', '2025-04-20 18:56:58');

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `abbreviation` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `abbreviation`, `created_at`, `updated_at`) VALUES
(1, 'دانە  | quantity', 'pcs', '2025-04-20 18:34:33', '2025-04-20 18:34:33');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `custom_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`custom_fields`)),
  `avatar_url` varchar(255) DEFAULT NULL,
  `theme` varchar(255) DEFAULT 'default',
  `theme_color` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `active`, `remember_token`, `created_at`, `updated_at`, `custom_fields`, `avatar_url`, `theme`, `theme_color`) VALUES
(1, 'Test User', 'test@admin.com', '2025-04-20 17:45:30', '$2y$12$taywbdcyN3IoTEHg4k7tCuqFz16YrliNXVNezIJW4PFWJStQOP9HW', 1, 'uH69MNfHtN', '2025-04-20 17:45:30', '2025-04-20 20:01:26', NULL, 'avatars/01JSACN6ZXWX8ARXS4GX33F76G.jpg', 'default', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_conversations_user_id_foreign` (`user_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_expense_type_id_foreign` (`expense_type_id`);

--
-- Indexes for table `expense_types`
--
ALTER TABLE `expense_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_types_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incomes_income_type_id_foreign` (`income_type_id`);

--
-- Indexes for table `income_types`
--
ALTER TABLE `income_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `income_types_name_unique` (`name`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attributes_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchases_purchase_number_unique` (`purchase_number`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_sale_number_unique` (`sale_number`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_types`
--
ALTER TABLE `expense_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `income_types`
--
ALTER TABLE `income_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chat_conversations`
--
ALTER TABLE `chat_conversations`
  ADD CONSTRAINT `chat_conversations_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_expense_type_id_foreign` FOREIGN KEY (`expense_type_id`) REFERENCES `expense_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_income_type_id_foreign` FOREIGN KEY (`income_type_id`) REFERENCES `income_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_attributes`
--
ALTER TABLE `product_attributes`
  ADD CONSTRAINT `product_attributes_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
