-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 05, 2026 at 04:37 PM
-- Server version: 11.8.9-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u113170201_varidian`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('varidian-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0', 'i:1;', 1793890822),
('varidian-cache-da4b9237bacccdf19c0760cab7aec4a8359010b0:timer', 'i:1793890822;', 1793890822);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache_locks`
--

INSERT INTO `cache_locks` (`key`, `owner`, `expiration`) VALUES
('varidian-cache-laravel_unique_job:App\\Jobs\\ProvisionHostingAccount:2', 'sIoQBpQI4bsY1cai', 1793967873),
('varidian-cache-laravel_unique_job:App\\Jobs\\ProvisionHostingAccount:3', 'm5ruspXNNq9TWtx7', 1793970339),
('varidian-cache-laravel_unique_job:App\\Jobs\\ProvisionHostingAccount:4', 'G5HhdI4q25ZiVGjT', 1793898036);

-- --------------------------------------------------------

--
-- Table structure for table `commissions`
--

CREATE TABLE `commissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `lead_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payout_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `base_ngwee` int(11) NOT NULL,
  `rate_percent` tinyint(3) UNSIGNED DEFAULT NULL,
  `amount_ngwee` int(11) NOT NULL,
  `earned_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_inquiries`
--

CREATE TABLE `contact_inquiries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `organisation` varchar(200) DEFAULT NULL,
  `phone` varchar(30) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `product_interest` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `mailed_at` timestamp NULL DEFAULT NULL,
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
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `number` varchar(255) DEFAULT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `subscription_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'subscription',
  `plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `amount_ngwee` int(10) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `due_at` timestamp NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `number`, `team_id`, `subscription_id`, `type`, `plan_id`, `description`, `amount_ngwee`, `status`, `due_at`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 'INV-000001', 2, 1, 'subscription', 2, 'Starter hosting for lumastech.com — 1 month', 19900, 'void', '2026-11-03 16:35:24', NULL, '2026-11-03 16:35:24', '2026-11-05 12:20:56'),
(2, 'INV-000002', 2, 2, 'subscription', 1, 'Community hosting for lumastech.com — 1 month', 100, 'paid', '2026-11-05 12:21:22', '2026-11-05 12:24:33', '2026-11-05 12:21:22', '2026-11-05 12:24:33'),
(3, 'INV-000003', 2, 3, 'subscription', 1, 'Community hosting for lumasweb.com — 1 month', 100, 'paid', '2026-11-05 13:04:53', '2026-11-05 13:05:39', '2026-11-05 13:04:53', '2026-11-05 13:05:39'),
(4, 'INV-000004', 2, 4, 'subscription', 1, 'Community hosting for lumasai.com — 1 month', 100, 'paid', '2026-11-05 14:59:19', '2026-11-05 15:00:36', '2026-11-05 14:59:19', '2026-11-05 15:00:36');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` smallint(5) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(4, 'default', '{\"uuid\":\"a0bdaa2a-c27c-49ae-b000-16c935b4d9e1\",\"displayName\":\"App\\\\Jobs\\\\ProvisionHostingAccount\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":5,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":\"60,300,900,3600\",\"timeout\":null,\"retryUntil\":null,\"deleteWhenMissingModels\":false,\"data\":{\"commandName\":\"App\\\\Jobs\\\\ProvisionHostingAccount\",\"command\":\"O:32:\\\"App\\\\Jobs\\\\ProvisionHostingAccount\\\":2:{s:12:\\\"subscription\\\";O:45:\\\"Illuminate\\\\Contracts\\\\Database\\\\ModelIdentifier\\\":5:{s:5:\\\"class\\\";s:23:\\\"App\\\\Models\\\\Subscription\\\";s:2:\\\"id\\\";i:4;s:9:\\\"relations\\\";a:0:{}s:10:\\\"connection\\\";s:5:\\\"mysql\\\";s:15:\\\"collectionClass\\\";N;}s:11:\\\"afterCommit\\\";b:1;}\",\"batchId\":null},\"createdAt\":1793890836,\"illuminate:log:context\":{\"data\":[],\"hidden\":{\"laravel_unique_job_cache_store\":\"s:8:\\\"database\\\";\",\"laravel_unique_job_key\":\"s:53:\\\"laravel_unique_job:App\\\\Jobs\\\\ProvisionHostingAccount:4\\\";\"}},\"delay\":null}', 0, NULL, 1793890836, 1793890836);

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
-- Table structure for table `leads`
--

CREATE TABLE `leads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `organisation` varchar(255) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `interest` varchar(255) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `review_note` varchar(255) DEFAULT NULL,
  `team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `confirmed_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `won_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
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
(4, '2025_08_14_170933_add_two_factor_columns_to_users_table', 1),
(5, '2026_01_27_000001_create_teams_table', 1),
(6, '2026_01_27_000002_add_current_team_id_to_users_table', 1),
(7, '2026_10_31_200453_create_contact_inquiries_table', 2),
(8, '2026_10_31_200453_create_plans_table', 2),
(9, '2026_10_31_201414_create_subscriptions_table', 2),
(10, '2026_10_31_201415_create_invoices_table', 2),
(11, '2026_10_31_201415_create_payments_table', 2),
(12, '2026_10_31_202430_add_is_admin_to_users_table', 3),
(13, '2026_10_31_202430_add_ssl_issued_at_to_subscriptions_table', 3),
(14, '2026_10_31_205118_add_plan_changes_to_billing_tables', 3),
(15, '2026_10_31_213137_create_partners_table', 3),
(16, '2026_10_31_213138_create_leads_table', 3),
(17, '2026_10_31_213140_create_payouts_table', 3),
(18, '2026_10_31_213141_create_commissions_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `partners`
--

CREATE TABLE `partners` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `nrc_number` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `payout_method` varchar(255) NOT NULL,
  `payout_details` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `agreement_accepted_at` timestamp NOT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `terminated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_id` bigint(20) UNSIGNED NOT NULL,
  `reference` varchar(255) NOT NULL,
  `amount_ngwee` int(10) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL,
  `lenco_reference` varchar(255) DEFAULT NULL,
  `channel` varchar(255) DEFAULT NULL,
  `failure_reason` varchar(255) DEFAULT NULL,
  `provider_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`provider_response`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `invoice_id`, `reference`, `amount_ngwee`, `status`, `lenco_reference`, `channel`, `failure_reason`, `provider_response`, `paid_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'VAR-01M6H48ABX7D3HZ03ABNKTJDS3', 19900, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-11-03 16:35:38', '2026-11-03 16:35:38'),
(2, 1, 'VAR-01M6H4BE0TKEFDE5144Y0EE1ZF', 19900, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-11-03 16:37:20', '2026-11-03 16:37:20'),
(3, 1, 'VAR-01M6H4BN4VNE880GGFX5AV63VQ', 19900, 'pending', NULL, NULL, NULL, NULL, NULL, '2026-11-03 16:37:27', '2026-11-03 16:37:27'),
(4, 2, 'VAR-01M6NTGB4TGGRZ6ZDNSMY09A1M', 100, 'successful', '26309013299', 'mobile-money', NULL, '{\"id\":\"73a30871-726b-4925-8664-667581665d6d\",\"initiatedAt\":\"2026-11-05T12:21:31.094Z\",\"completedAt\":\"2026-11-05T12:24:28.957Z\",\"amount\":\"1.00\",\"fee\":\"0.01\",\"bearer\":\"merchant\",\"currency\":\"ZMW\",\"reference\":\"VAR-01M6NTGB4TGGRZ6ZDNSMY09A1M\",\"lencoReference\":\"26309013299\",\"type\":\"mobile-money\",\"status\":\"successful\",\"source\":\"api\",\"reasonForFailure\":null,\"settlementStatus\":\"settled\",\"settlement\":{\"id\":\"666e9f62-d4f6-4427-995c-ac94920cca1b\",\"amountSettled\":\"0.99\",\"currency\":\"ZMW\",\"createdAt\":\"2026-11-05T12:24:29.056Z\",\"settledAt\":\"2026-11-05T12:24:29.273Z\",\"status\":\"settled\",\"type\":\"instant\",\"accountId\":\"9b9410cf-bfc4-4273-9bca-08c28ebfd1b6\"},\"mobileMoneyDetails\":{\"country\":\"zm\",\"phone\":\"0971864421\",\"operator\":\"airtel\",\"accountName\":\"LUBOMBA MULOMYA\",\"operatorTransactionId\":\"MP261105.1421.D55229\"},\"bankAccountDetails\":null,\"cardDetails\":null}', '2026-11-05 12:24:33', '2026-11-05 12:21:27', '2026-11-05 12:24:33'),
(5, 3, 'VAR-01M6NX00PCSW7Y71BZV8G4HHRN', 100, 'successful', '26309014480', 'mobile-money', NULL, '{\"id\":\"d5894c88-86f7-4384-a10e-6451064de5cf\",\"initiatedAt\":\"2026-11-05T13:05:00.978Z\",\"completedAt\":\"2026-11-05T13:05:33.688Z\",\"amount\":\"1.00\",\"fee\":\"0.01\",\"bearer\":\"merchant\",\"currency\":\"ZMW\",\"reference\":\"VAR-01M6NX00PCSW7Y71BZV8G4HHRN\",\"lencoReference\":\"26309014480\",\"type\":\"mobile-money\",\"status\":\"successful\",\"source\":\"api\",\"reasonForFailure\":null,\"settlementStatus\":\"settled\",\"settlement\":{\"id\":\"85b87e31-d707-4452-8ecc-a9419fab2553\",\"amountSettled\":\"0.99\",\"currency\":\"ZMW\",\"createdAt\":\"2026-11-05T13:05:33.866Z\",\"settledAt\":\"2026-11-05T13:05:34.429Z\",\"status\":\"settled\",\"type\":\"instant\",\"accountId\":\"9b9410cf-bfc4-4273-9bca-08c28ebfd1b6\"},\"mobileMoneyDetails\":{\"country\":\"zm\",\"phone\":\"0971864421\",\"operator\":\"airtel\",\"accountName\":\"LUBOMBA MULOMYA\",\"operatorTransactionId\":\"MP261105.1505.D64540\"},\"bankAccountDetails\":null,\"cardDetails\":null}', '2026-11-05 13:05:39', '2026-11-05 13:04:58', '2026-11-05 13:05:39'),
(6, 4, 'VAR-01M6P3HFG8HY1ZWP8RZ0NJE9ZH', 100, 'successful', '26309017659', 'mobile-money', NULL, '{\"id\":\"65e94874-b551-4ca5-9fdb-3e76e2b4c2ca\",\"initiatedAt\":\"2026-11-05T14:59:23.559Z\",\"completedAt\":\"2026-11-05T15:00:33.376Z\",\"amount\":\"1.00\",\"fee\":\"0.01\",\"bearer\":\"merchant\",\"currency\":\"ZMW\",\"reference\":\"VAR-01M6P3HFG8HY1ZWP8RZ0NJE9ZH\",\"lencoReference\":\"26309017659\",\"type\":\"mobile-money\",\"status\":\"successful\",\"source\":\"api\",\"reasonForFailure\":null,\"settlementStatus\":\"settled\",\"settlement\":{\"id\":\"db6f91a2-4d7c-4efb-976b-2bc44971eb90\",\"amountSettled\":\"0.99\",\"currency\":\"ZMW\",\"createdAt\":\"2026-11-05T15:00:33.583Z\",\"settledAt\":\"2026-11-05T15:00:34.046Z\",\"status\":\"settled\",\"type\":\"instant\",\"accountId\":\"9b9410cf-bfc4-4273-9bca-08c28ebfd1b6\"},\"mobileMoneyDetails\":{\"country\":\"zm\",\"phone\":\"0971864421\",\"operator\":\"airtel\",\"accountName\":\"LUBOMBA MULOMYA\",\"operatorTransactionId\":\"MP261105.1659.D85495\"},\"bankAccountDetails\":null,\"cardDetails\":null}', '2026-11-05 15:00:36', '2026-11-05 14:59:22', '2026-11-05 15:00:36');

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

CREATE TABLE `payouts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `partner_id` bigint(20) UNSIGNED NOT NULL,
  `amount_ngwee` int(10) UNSIGNED NOT NULL,
  `method` varchar(255) NOT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `recorded_by` bigint(20) UNSIGNED DEFAULT NULL,
  `paid_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `plans`
--

CREATE TABLE `plans` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `audience` varchar(255) DEFAULT NULL,
  `hestia_package` varchar(255) NOT NULL,
  `price_ngwee` int(10) UNSIGNED NOT NULL,
  `storage_gb` int(10) UNSIGNED NOT NULL,
  `bandwidth_gb` int(10) UNSIGNED NOT NULL,
  `websites` int(10) UNSIGNED NOT NULL,
  `databases` int(10) UNSIGNED NOT NULL,
  `cron_jobs` int(10) UNSIGNED NOT NULL,
  `backups` int(10) UNSIGNED NOT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `plans`
--

INSERT INTO `plans` (`id`, `name`, `slug`, `audience`, `hestia_package`, `price_ngwee`, `storage_gb`, `bandwidth_gb`, `websites`, `databases`, `cron_jobs`, `backups`, `is_featured`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Community', 'community', 'For personal sites, clubs and churches', 'Community', 100, 1, 10, 1, 1, 2, 3, 0, 1, 0, '2026-11-03 16:31:42', '2026-11-05 12:20:47'),
(2, 'Starter', 'starter', 'For a small business website', 'Starter', 19900, 5, 20, 1, 1, 2, 3, 0, 1, 1, '2026-11-03 16:31:42', '2026-11-03 16:31:42'),
(3, 'Business', 'business', 'For growing SMEs with several sites', 'Business', 49900, 20, 75, 5, 10, 10, 7, 1, 1, 2, '2026-11-03 16:31:42', '2026-11-03 16:31:42'),
(4, 'Developer', 'developer', 'For developers and agencies hosting clients', 'Developer', 89900, 40, 150, 10, 20, 25, 7, 0, 1, 3, '2026-11-03 16:31:42', '2026-11-03 16:31:42');

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
('hT39oZN1IjiuEGFvaO6UdFNo4XOuD2miNzUvltfB', NULL, '195.201.166.159', 'Mozilla/5.0 (compatible; NukeScanner/1.0; +https://nuke.hgate483.site)', 'eyJfdG9rZW4iOiJGVFMwTGU0YU1rcUZuYm1OSnFVdEw1MHpCVzlIbWwwN2JTOFRMdmRxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC92YXJpZGlhbmxhYi5jb20iLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1793892871),
('irVmZOQtqe0dPQyL0tP3JPELiDaaKkkjTqqO8tkp', NULL, '195.201.166.159', 'Mozilla/5.0 (compatible; NukeScanner/1.0; +https://nuke.hgate483.site)', 'eyJfdG9rZW4iOiJNb0F2a3k0VDR5dUR1NFRTUlJzZkVqclRGQVo3VHlGbkpKa1ltU2ZhIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC92YXJpZGlhbmxhYi5jb20iLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1793892871),
('JfohLJmQZ6wl1fjyEHxUQnWiQlrGECFgl2wbaUh6', NULL, '114.119.134.80', 'Mozilla/5.0 (Linux; Android 7.0;) AppleWebKit/537.36 (HTML, like Gecko) Mobile Safari/537.36 (compatible; PetalBot;+https://webmaster.petalsearch.com/site/petalbot)', 'eyJfdG9rZW4iOiJrOGh6anA4ZkdDTzFDRzEyRXpXeUQ4bWlRSkVCV3NZQUxvTThMeXVDIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC92YXJpZGlhbmxhYi5jb20iLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1793892086),
('NeOOtnom9RwSFJmG7dvaeQz7oFWD62R0h341hfDv', NULL, '198.13.159.189', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJEcElScGVSYjlVc1NDaExrcVdJS2F6RmcydE5Ld29vY2pVT1ZBU3pBIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC92YXJpZGlhbmxhYi5jb20iLCJyb3V0ZSI6ImhvbWUifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==', 1793892796),
('wHowklbQOK0YnOCEkDgLswkeRsLZHdCYrkYRnzSq', 2, '216.234.213.93', 'Mozilla/5.0 (X11; Linux x86_64; rv:153.0) Gecko/20100101 Firefox/153.0', 'eyJfdG9rZW4iOiJhQ2ppbVFoMWZHaGNubXF4UUJybUhtbnh1QmtVeENDTXNBb1lHRXp0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHBzOlwvXC92YXJpZGlhbmxhYi5jb21cL2x1bWFzcy10ZWFtLTFcL2Rhc2hib2FyZCIsInJvdXRlIjoiZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjJ9', 1793890988);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `plan_id` bigint(20) UNSIGNED NOT NULL,
  `scheduled_plan_id` bigint(20) UNSIGNED DEFAULT NULL,
  `domain` varchar(255) NOT NULL,
  `hestia_username` varchar(32) NOT NULL,
  `status` varchar(255) NOT NULL,
  `current_period_starts_at` timestamp NULL DEFAULT NULL,
  `current_period_ends_at` timestamp NULL DEFAULT NULL,
  `provisioned_at` timestamp NULL DEFAULT NULL,
  `ssl_issued_at` timestamp NULL DEFAULT NULL,
  `suspended_at` timestamp NULL DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`id`, `team_id`, `plan_id`, `scheduled_plan_id`, `domain`, `hestia_username`, `status`, `current_period_starts_at`, `current_period_ends_at`, `provisioned_at`, `ssl_issued_at`, `suspended_at`, `cancelled_at`, `created_at`, `updated_at`) VALUES
(1, 2, 2, NULL, 'lumastech.com', 'lumastech', 'cancelled', NULL, NULL, NULL, NULL, NULL, '2026-11-05 12:20:56', '2026-11-03 16:35:24', '2026-11-05 12:20:56'),
(2, 2, 1, NULL, 'lumastech.com', 'lumastech7798', 'active', '2026-11-05 12:24:33', '2026-12-05 12:24:33', NULL, NULL, NULL, NULL, '2026-11-05 12:21:22', '2026-11-05 12:24:33'),
(3, 2, 1, NULL, 'lumasweb.com', 'lumasweb', 'active', '2026-11-05 13:05:39', '2026-12-05 13:05:39', NULL, NULL, NULL, NULL, '2026-11-05 13:04:53', '2026-11-05 13:05:39'),
(4, 2, 1, NULL, 'lumasai.com', 'lumasai', 'active', '2026-11-05 15:00:36', '2026-12-05 15:00:36', NULL, NULL, NULL, NULL, '2026-11-05 14:59:19', '2026-11-05 15:00:36');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `is_personal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`id`, `name`, `slug`, `is_personal`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Lumas\'s Team', 'lumass-team', 1, '2026-06-19 00:07:09', '2026-06-19 00:07:09', NULL),
(2, 'Lumas\'s Team', 'lumass-team-1', 1, '2026-11-03 16:23:43', '2026-11-03 16:23:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `team_invitations`
--

CREATE TABLE `team_invitations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(64) NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `invited_by` bigint(20) UNSIGNED NOT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `accepted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `team_members`
--

CREATE TABLE `team_members` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `team_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `team_members`
--

INSERT INTO `team_members` (`id`, `team_id`, `user_id`, `role`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'owner', '2026-06-19 00:07:09', '2026-06-19 00:07:09'),
(2, 2, 2, 'owner', '2026-11-03 16:23:43', '2026-11-03 16:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `is_admin`, `email_verified_at`, `password`, `current_team_id`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Lumas', 'admin@admin.com', 0, NULL, '$2y$12$QzGMOrnL4Jc9i5s.oBGVCu0xxxTbVV1hmihN93TeL3NSfjVw1kQ3m', 1, NULL, NULL, NULL, NULL, '2026-06-19 00:07:09', '2026-06-19 00:07:09'),
(2, 'Lumas', 'lumastech@gmail.com', 1, NULL, '$2y$12$7e8wFsyqAkMkb/aPG5wv5.ct4cb3NxeD/C3Fd6bXcGrd1GYfT1KIu', 2, NULL, NULL, NULL, NULL, '2026-11-03 16:23:43', '2026-11-03 16:27:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `commissions`
--
ALTER TABLE `commissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `commissions_invoice_id_unique` (`invoice_id`),
  ADD KEY `commissions_partner_id_foreign` (`partner_id`),
  ADD KEY `commissions_lead_id_foreign` (`lead_id`),
  ADD KEY `commissions_payout_id_foreign` (`payout_id`),
  ADD KEY `commissions_earned_at_index` (`earned_at`);

--
-- Indexes for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_number_unique` (`number`),
  ADD KEY `invoices_team_id_foreign` (`team_id`),
  ADD KEY `invoices_subscription_id_foreign` (`subscription_id`),
  ADD KEY `invoices_status_index` (`status`),
  ADD KEY `invoices_plan_id_foreign` (`plan_id`);

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
-- Indexes for table `leads`
--
ALTER TABLE `leads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leads_partner_id_foreign` (`partner_id`),
  ADD KEY `leads_team_id_foreign` (`team_id`),
  ADD KEY `leads_status_index` (`status`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partners`
--
ALTER TABLE `partners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `partners_user_id_unique` (`user_id`),
  ADD KEY `partners_status_index` (`status`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_reference_unique` (`reference`),
  ADD KEY `payments_invoice_id_foreign` (`invoice_id`),
  ADD KEY `payments_status_index` (`status`);

--
-- Indexes for table `payouts`
--
ALTER TABLE `payouts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payouts_partner_id_foreign` (`partner_id`),
  ADD KEY `payouts_recorded_by_foreign` (`recorded_by`);

--
-- Indexes for table `plans`
--
ALTER TABLE `plans`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `plans_slug_unique` (`slug`),
  ADD UNIQUE KEY `plans_hestia_package_unique` (`hestia_package`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `subscriptions_hestia_username_unique` (`hestia_username`),
  ADD KEY `subscriptions_team_id_foreign` (`team_id`),
  ADD KEY `subscriptions_plan_id_foreign` (`plan_id`),
  ADD KEY `subscriptions_status_index` (`status`),
  ADD KEY `subscriptions_scheduled_plan_id_foreign` (`scheduled_plan_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teams_slug_unique` (`slug`);

--
-- Indexes for table `team_invitations`
--
ALTER TABLE `team_invitations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_invitations_code_unique` (`code`),
  ADD KEY `team_invitations_team_id_foreign` (`team_id`),
  ADD KEY `team_invitations_invited_by_foreign` (`invited_by`);

--
-- Indexes for table `team_members`
--
ALTER TABLE `team_members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `team_members_team_id_user_id_unique` (`team_id`,`user_id`),
  ADD KEY `team_members_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_current_team_id_foreign` (`current_team_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `commissions`
--
ALTER TABLE `commissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_inquiries`
--
ALTER TABLE `contact_inquiries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leads`
--
ALTER TABLE `leads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `partners`
--
ALTER TABLE `partners`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `payouts`
--
ALTER TABLE `payouts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `plans`
--
ALTER TABLE `plans`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `teams`
--
ALTER TABLE `teams`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `team_invitations`
--
ALTER TABLE `team_invitations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `team_members`
--
ALTER TABLE `team_members`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `commissions`
--
ALTER TABLE `commissions`
  ADD CONSTRAINT `commissions_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `commissions_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `commissions_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commissions_payout_id_foreign` FOREIGN KEY (`payout_id`) REFERENCES `payouts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `invoices_subscription_id_foreign` FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `invoices_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leads`
--
ALTER TABLE `leads`
  ADD CONSTRAINT `leads_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leads_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `partners`
--
ALTER TABLE `partners`
  ADD CONSTRAINT `partners_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_invoice_id_foreign` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payouts`
--
ALTER TABLE `payouts`
  ADD CONSTRAINT `payouts_partner_id_foreign` FOREIGN KEY (`partner_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payouts_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_plan_id_foreign` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
  ADD CONSTRAINT `subscriptions_scheduled_plan_id_foreign` FOREIGN KEY (`scheduled_plan_id`) REFERENCES `plans` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `subscriptions_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_invitations`
--
ALTER TABLE `team_invitations`
  ADD CONSTRAINT `team_invitations_invited_by_foreign` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_invitations_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `team_members`
--
ALTER TABLE `team_members`
  ADD CONSTRAINT `team_members_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `team_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_current_team_id_foreign` FOREIGN KEY (`current_team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
