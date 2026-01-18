-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2026 at 04:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teman_jalan`
--

-- --------------------------------------------------------

--
-- Table structure for table `activities`
--

CREATE TABLE `activities` (
  `activity_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `place_id` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `order_number` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activities`
--

INSERT INTO `activities` (`activity_id`, `event_id`, `place_id`, `title`, `description`, `start_time`, `end_time`, `order_number`) VALUES
(1, 1, 1, 'Food & Drinks', 'Kumpul pagi sambil ngopi dan sarapan ringan', '2024-11-18 02:00:00', '2024-11-18 03:30:00', 1),
(2, 1, 2, 'Tickets & Entrance', 'Jalan-jalan ke Monas dan naik ke puncak monumen', '2024-11-18 04:00:00', '2024-11-18 06:00:00', 2),
(3, 1, 4, 'Food & Drinks', 'Makan siang bareng di restoran Sate Senayan', '2024-11-18 06:30:00', '2024-11-18 08:00:00', 3),
(4, 1, 3, 'Shopping', 'Belanja oleh-oleh dan jalan-jalan di mall', '2024-11-18 08:30:00', '2024-11-18 11:00:00', 4),
(5, 1, 5, 'Transport', 'Isi bensin untuk perjalanan pulang', '2024-11-18 11:15:00', '2024-11-18 11:30:00', 5),
(6, 2, 5, 'Transport', 'Isi bensin sebelum berangkat ke Bandung', '2024-10-09 23:00:00', '2024-10-09 23:15:00', 1),
(7, 2, 8, 'Accommodation', 'Check-in hotel untuk menginap 1 malam', '2024-10-10 03:00:00', '2024-10-11 05:00:00', 2),
(8, 2, 9, 'Food & Drinks', 'Makan siang masakan Padang', '2024-10-10 05:30:00', '2024-10-10 07:00:00', 3),
(9, 2, 7, 'Shopping', 'Belanja baju dan souvenir di factory outlet', '2024-10-10 07:30:00', '2024-10-10 10:00:00', 4),
(10, 2, 6, 'Tickets & Entrance', 'Bermain di Trans Studio Bandung', '2024-10-10 11:00:00', '2024-10-10 14:00:00', 5),
(11, 2, 10, 'Shared Items', 'Beli air minum galon dan snack untuk di hotel', '2024-10-10 14:30:00', '2024-10-10 15:00:00', 6),
(12, 3, 15, 'Transport', 'Sewa mobil untuk keliling Yogyakarta 3 hari', '2024-09-20 01:00:00', '2024-09-22 13:00:00', 1),
(13, 3, 14, 'Accommodation', 'Menginap di hotel heritage 2 malam', '2024-09-20 07:00:00', '2024-09-22 05:00:00', 2),
(14, 3, 12, 'Tickets & Entrance', 'Tiket masuk Candi Prambanan dan tour guide', '2024-09-20 08:00:00', '2024-09-20 10:30:00', 3),
(15, 3, 13, 'Food & Drinks', 'Makan malam gudeg khas Yogyakarta', '2024-09-20 11:30:00', '2024-09-20 13:00:00', 4),
(16, 3, 11, 'Shopping', 'Belanja oleh-oleh khas Jogja di Malioboro', '2024-09-21 03:00:00', '2024-09-21 05:30:00', 5),
(17, 3, 14, 'Entertainment / Fun', 'Karaoke bareng di lounge hotel', '2024-09-21 13:00:00', '2024-09-21 16:00:00', 6),
(18, 4, 5, 'Transport', 'Isi bensin untuk perjalanan Jakarta-Surabaya', '2024-08-14 22:30:00', '2024-08-14 22:45:00', 1),
(19, 4, 19, 'Accommodation', 'Check-in Hotel Majapahit untuk 1 malam', '2024-08-15 07:00:00', '2024-08-16 05:00:00', 2),
(20, 4, 17, 'Food & Drinks', 'Makan rawon setan yang legendaris', '2024-08-15 12:00:00', '2024-08-15 13:30:00', 3),
(21, 4, 18, 'Tickets & Entrance', 'Tour museum House of Sampoerna', '2024-08-16 02:00:00', '2024-08-16 04:00:00', 4),
(22, 4, 16, 'Shopping', 'Belanja oleh-oleh khas Surabaya di Tunjungan Plaza', '2024-08-16 06:00:00', '2024-08-16 08:30:00', 5),
(23, 4, 20, 'Entertainment / Fun', 'Nonton film bareng di bioskop', '2024-08-16 09:00:00', '2024-08-16 11:30:00', 6),
(24, 5, 3, 'test', 'test', '2026-01-12 05:00:00', '2026-01-12 06:00:00', 1),
(25, 5, 20, 'test2', 'test2', '2026-01-12 07:00:00', '2026-01-12 08:00:00', 2),
(26, 7, 17, 'test3', 'test3', '2026-01-18 18:01:00', '2026-01-18 18:12:00', 1),
(27, 8, 10, 'test4', 'test4', '2026-01-19 18:01:00', '2026-01-19 18:12:00', 1),
(28, 9, 15, 'test5', 'test5', '2026-01-21 05:12:00', '2026-01-21 06:13:00', 1),
(29, 10, 6, 'test7', 'test7', '2026-01-20 05:02:00', '2026-01-20 06:13:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `creator_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `event_name`, `description`, `event_date`, `creator_id`, `status`, `created_at`) VALUES
(1, 'Weekend Gathering Jakarta', 'Ngumpul santai di Jakarta sambil makan dan jalan-jalan', '2024-11-18', 1, 'completed', '2025-11-24 03:02:39'),
(2, 'Bandung Adventure Trip', 'Liburan 2 hari 1 malam ke Bandung untuk belanja dan wisata', '2024-10-10', 2, 'completed', '2025-10-30 03:02:39'),
(3, 'Yogyakarta Cultural Tour', 'Explore budaya dan kuliner Yogyakarta selama 3 hari', '2024-09-20', 3, 'completed', '2025-10-10 03:02:39'),
(4, 'Surabaya Culinary Experience', 'Wisata kuliner dan sejarah di Surabaya 2 hari 1 malam', '2024-08-15', 4, 'completed', '2025-09-05 03:02:39'),
(5, 'test', 'test', '2026-01-12', 1, 'completed', '2026-01-08 10:03:51'),
(6, 'test 2', 'test 2', '2026-01-18', 1, 'completed', '2026-01-18 09:29:50'),
(7, 'test3', 'test3', '2026-01-18', 1, 'completed', '2026-01-18 11:09:08'),
(8, 'test4', 'test4', '2026-01-18', 1, 'completed', '2026-01-18 11:26:23'),
(9, 'test5', 'test5', '2026-01-18', 1, 'completed', '2026-01-18 11:33:36'),
(10, 'test7', 'test7', '2026-01-18', 1, 'completed', '2026-01-18 12:04:36');

-- --------------------------------------------------------

--
-- Table structure for table `event_participants`
--

CREATE TABLE `event_participants` (
  `participant_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_participants`
--

INSERT INTO `event_participants` (`participant_id`, `event_id`, `user_id`, `joined_at`) VALUES
(1, 1, 1, '2025-11-24 03:02:39'),
(2, 1, 2, '2025-11-25 03:02:39'),
(3, 1, 3, '2025-11-25 03:02:39'),
(4, 2, 2, '2025-10-30 03:02:39'),
(5, 2, 4, '2025-10-31 03:02:39'),
(6, 2, 5, '2025-10-31 03:02:39'),
(7, 3, 3, '2025-10-10 03:02:39'),
(8, 3, 1, '2025-10-11 03:02:39'),
(9, 3, 5, '2025-10-11 03:02:39'),
(10, 4, 4, '2025-09-05 03:02:39'),
(11, 4, 1, '2025-09-06 03:02:39'),
(12, 4, 2, '2025-09-06 03:02:39'),
(13, 5, 2, '2026-01-09 02:44:07'),
(14, 8, 2, '2026-01-18 04:26:27'),
(15, 9, 2, '2026-01-18 04:33:39'),
(16, 10, 2, '2026-01-18 05:04:40');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `activity_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `paid_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `expense_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`expense_id`, `event_id`, `activity_id`, `description`, `amount`, `paid_by_user_id`, `expense_date`) VALUES
(1, 1, 1, 'Kopi dan breakfast menu untuk 3 orang', 285000.00, 1, '2024-11-17 17:00:00'),
(2, 1, 2, 'Tiket masuk Monas 3 orang @ 15rb', 45000.00, 2, '2024-11-17 17:00:00'),
(3, 1, 3, 'Makan siang sate dan nasi goreng', 520000.00, 3, '2024-11-17 17:00:00'),
(4, 1, 4, 'Belanja baju dan aksesoris', 750000.00, 1, '2024-11-17 17:00:00'),
(5, 1, 5, 'Bensin Pertalite 20 liter', 200000.00, 2, '2024-11-17 17:00:00'),
(6, 2, 6, 'Bensin full tank untuk Jakarta-Bandung', 450000.00, 2, '2024-10-09 17:00:00'),
(7, 2, 7, 'Kamar hotel deluxe 2 kamar untuk 1 malam', 1800000.00, 5, '2024-10-09 17:00:00'),
(8, 2, 8, 'Makan siang nasi padang 3 porsi', 375000.00, 2, '2024-10-09 17:00:00'),
(9, 2, 9, 'Belanja baju dan jacket di FO', 1250000.00, 4, '2024-10-09 17:00:00'),
(10, 2, 10, 'Tiket Trans Studio 3 orang', 750000.00, 5, '2025-02-09 17:00:00'),
(11, 2, 10, 'Air galon dan snack untuk hotel', 85000.00, 2, '2025-02-09 17:00:00'),
(12, 3, 11, 'Rental mobil Avanza 3 hari termasuk driver', 1500000.00, 3, '2024-12-19 17:00:00'),
(13, 3, 11, 'Bensin dan parkir selama di Yogya', 320000.00, 1, '2024-12-19 17:00:00'),
(14, 3, 12, 'Hotel Phoenix 2 kamar untuk 2 malam', 3200000.00, 5, '2024-12-19 17:00:00'),
(15, 3, 13, 'Tiket Prambanan 3 orang + guide', 225000.00, 3, '2024-12-19 17:00:00'),
(16, 3, 14, 'Makan gudeg komplit 3 porsi', 180000.00, 1, '2024-12-19 17:00:00'),
(17, 3, 15, 'Oleh-oleh bakpia, gudeg kaleng, dan batik', 850000.00, 5, '2024-12-20 17:00:00'),
(18, 3, 16, 'Sewa speaker bluetooth untuk karaoke', 150000.00, 3, '2024-12-20 17:00:00'),
(19, 5, 24, 'test', 100000.00, 2, '2026-01-11 17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `expense_shares`
--

CREATE TABLE `expense_shares` (
  `share_id` bigint(20) UNSIGNED NOT NULL,
  `expense_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount_owed` decimal(10,2) DEFAULT NULL,
  `is_settled` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_shares`
--

INSERT INTO `expense_shares` (`share_id`, `expense_id`, `user_id`, `amount_owed`, `is_settled`) VALUES
(1, 19, 1, 50000.00, 0),
(2, 19, 2, 50000.00, 0);

-- --------------------------------------------------------

--
-- Table structure for table `friendships`
--

CREATE TABLE `friendships` (
  `friendship_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `friend_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `times_together` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `friendships`
--

INSERT INTO `friendships` (`friendship_id`, `user_id`, `friend_id`, `status`, `times_together`, `created_at`) VALUES
(1, 1, 2, 'accepted', 4, '2026-01-08 10:50:48');

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
(2, '2025_12_14_061126_create_teman_jalan_table', 1),
(3, '2026_01_07_000000_add_details_to_places_table', 1);

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
-- Table structure for table `places`
--

CREATE TABLE `places` (
  `place_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `places`
--

INSERT INTO `places` (`place_id`, `name`, `address`, `latitude`, `longitude`, `description`, `category`, `is_active`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Kopi Kenangan Grand Indonesia', 'Grand Indonesia Mall, Jl. MH Thamrin No.1, Jakarta Pusat', -6.19539600, 106.82299800, 'Coffee shop dengan signature kopi susu kekinian', 'Cafe', 1, NULL, '2026-01-08 03:02:39', NULL),
(2, 'Monumen Nasional (Monas)', 'Jl. Silang Monas, Gambir, Jakarta Pusat', -6.17539200, 106.82715300, 'Monumen ikonik Jakarta dengan taman luas di sekitarnya', 'Wisata', 1, NULL, '2026-01-08 03:02:39', NULL),
(3, 'Central Park Mall', 'Jl. Letjen S. Parman Kav.28, Jakarta Barat', -6.17830600, 106.79081000, 'Mall modern dengan berbagai tenant dan area kuliner', 'Mall', 1, NULL, '2026-01-08 03:02:39', NULL),
(4, 'Sate Khas Senayan', 'Plaza Senayan, Jl. Asia Afrika Lot.19, Jakarta Selatan', -6.22582500, 106.79938900, 'Restoran sate dan masakan Indonesia dengan cita rasa autentik', 'Restaurant', 1, NULL, '2026-01-08 03:02:39', NULL),
(5, 'SPBU Pertamina Gatot Subroto', 'Jl. Gatot Subroto, Jakarta Selatan', -6.22414000, 106.82794000, 'SPBU strategis untuk isi bensin', 'Gas Station', 1, NULL, '2026-01-08 03:02:39', NULL),
(6, 'Trans Studio Bandung', 'Jl. Gatot Subroto No.289, Bandung', -6.92707400, 107.63725300, 'Theme park indoor terbesar di Indonesia', 'Wisata', 1, NULL, '2026-01-08 03:02:39', NULL),
(7, 'Factory Outlet Rumah Mode', 'Jl. Setiabudhi No.41, Bandung', -6.86993200, 107.59777800, 'Factory outlet dengan berbagai brand fashion', 'Shopping', 1, NULL, '2026-01-08 03:02:39', NULL),
(8, 'Hotel Jayakarta Bandung', 'Jl. Ir. H. Juanda No.381A, Bandung', -6.90776300, 107.60049400, 'Hotel bintang 4 dengan pemandangan kota Bandung', 'Hotel', 1, NULL, '2026-01-08 03:02:39', NULL),
(9, 'Warung Nasi Ampera', 'Jl. Veteran No.25, Bandung', -6.91227300, 107.61089400, 'Restoran masakan Padang legendaris di Bandung', 'Restaurant', 1, NULL, '2026-01-08 03:02:39', NULL),
(10, 'Indomaret Dago', 'Jl. Ir. H. Juanda No.120, Bandung', -6.89776000, 107.61258000, 'Minimarket untuk beli kebutuhan sehari-hari', 'Minimarket', 1, NULL, '2026-01-08 03:02:39', NULL),
(11, 'Malioboro Mall', 'Jl. Malioboro No.52-58, Yogyakarta', -7.79253100, 110.36546300, 'Pusat perbelanjaan di jantung kota Yogyakarta', 'Mall', 1, NULL, '2026-01-08 03:02:39', NULL),
(12, 'Candi Prambanan', 'Jl. Raya Solo-Yogyakarta No.16, Sleman', -7.75202000, 110.49146700, 'Kompleks candi Hindu terbesar di Indonesia', 'Wisata', 1, NULL, '2026-01-08 03:02:39', NULL),
(13, 'Gudeg Yu Djum', 'Jl. Wijilan No.167, Yogyakarta', -7.80281300, 110.36576000, 'Rumah makan gudeg terkenal dengan cita rasa khas Yogya', 'Restaurant', 1, NULL, '2026-01-08 03:02:39', NULL),
(14, 'The Phoenix Hotel Yogyakarta', 'Jl. Jenderal Sudirman No.9, Yogyakarta', -7.78364000, 110.39087600, 'Hotel heritage dengan arsitektur kolonial yang indah', 'Hotel', 1, NULL, '2026-01-08 03:02:39', NULL),
(15, 'Rental Mobil Jogja Transport', 'Jl. Magelang No.15, Yogyakarta', -7.79706800, 110.37052900, 'Jasa rental mobil dengan driver berpengalaman', 'Transport', 1, NULL, '2026-01-08 03:02:39', NULL),
(16, 'Tunjungan Plaza', 'Jl. Basuki Rahmat No.8-12, Surabaya', -7.26274400, 112.73825100, 'Mall besar di pusat kota Surabaya', 'Mall', 1, NULL, '2026-01-08 03:02:39', NULL),
(17, 'Rawon Setan', 'Jl. Embong Malang No.78, Surabaya', -7.26860000, 112.74353000, 'Warung rawon legendaris yang buka malam hari', 'Restaurant', 1, NULL, '2026-01-08 03:02:39', NULL),
(18, 'House of Sampoerna', 'Jl. Taman Sampoerna No.6, Surabaya', -7.24534000, 112.74044000, 'Museum sejarah rokok kretek Sampoerna', 'Museum', 1, NULL, '2026-01-08 03:02:39', NULL),
(19, 'Hotel Majapahit Surabaya', 'Jl. Tunjungan No.65, Surabaya', -7.26562000, 112.73958000, 'Hotel mewah bersejarah dengan arsitektur kolonial', 'Hotel', 1, NULL, '2026-01-08 03:02:39', NULL),
(20, 'Cinema XXI Tunjungan Plaza', 'Tunjungan Plaza 4 Lt.3, Surabaya', -7.26274400, 112.73825100, 'Bioskop modern di dalam mall', 'Cinema', 1, NULL, '2026-01-08 03:02:39', NULL);

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
('ZGmyBMDrEjnTp7yvqilomeDhCurkVfSzsFbSYvAf', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:147.0) Gecko/20100101 Firefox/147.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiV2k1dDhGMGRGMWRsY2ZaNHdBUWVhUjNMczVwS0N0OW5ncUl0UDF0TiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9oaXN0b3J5Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTt9', 1768738429);

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
  `profile_picture_url` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `profile_picture_url`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Sarah Wijaya', 'sarah.wijaya@gmail.com', NULL, '$2y$12$ktrVd5gzp/lXkih4v2s8seFT0I3Ct64LeoXaLuzwzVllmHHnaTEyS', NULL, 'user', NULL, '2026-01-08 03:02:37', '2026-01-08 03:02:37'),
(2, 'Rizki Pratama', 'rizki.pratama@gmail.com', NULL, '$2y$12$BGDDDKijSVP2g/4HdL6rnedhY0P3kJ0ymLFb3GetolCKVHfreMQl6', NULL, 'user', NULL, '2026-01-08 03:02:38', '2026-01-08 03:02:38'),
(3, 'Maya Kusuma', 'maya.kusuma@gmail.com', NULL, '$2y$12$GxREITbPfIqvHL.cI5RlTuzHqNet/0.j.nj67lMah8XlZerKapV9q', NULL, 'user', NULL, '2026-01-08 03:02:38', '2026-01-08 03:02:38'),
(4, 'Dimas Anggara', 'dimas.anggara@gmail.com', NULL, '$2y$12$HFDFq/PlmKFIAIr49zyDHuCxHiGbceGl3Vgu5fLTObYjBGR/FyrqC', NULL, 'user', NULL, '2026-01-08 03:02:39', '2026-01-08 03:02:39'),
(5, 'Putri Maharani', 'putri.maharani@gmail.com', NULL, '$2y$12$riUtlMs4Y/d302hYN4c0yus2zFRu/qBF5NXu.pPSgsO4w4jqE9oM.', NULL, 'user', NULL, '2026-01-08 03:02:39', '2026-01-08 03:02:39');

-- --------------------------------------------------------

--
-- Table structure for table `user_place_visits`
--

CREATE TABLE `user_place_visits` (
  `visit_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `place_id` bigint(20) UNSIGNED DEFAULT NULL,
  `visit_count` int(11) NOT NULL DEFAULT 0,
  `last_visit_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_place_visits`
--

INSERT INTO `user_place_visits` (`visit_id`, `user_id`, `place_id`, `visit_count`, `last_visit_date`) VALUES
(1, 1, 1, 5, '2025-01-18'),
(2, 1, 2, 3, '2025-01-18'),
(3, 1, 10, 3, '2026-01-18'),
(4, 1, 11, 2, '2024-12-20'),
(5, 2, 1, 4, '2025-01-18'),
(6, 2, 2, 1, '2025-01-18'),
(7, 2, 5, 1, '2025-02-10'),
(8, 2, 6, 4, '2026-01-18'),
(9, 3, 4, 2, '2025-01-18'),
(10, 3, 9, 4, '2024-12-21'),
(11, 3, 10, 1, '2024-12-20'),
(12, 3, 12, 2, '2024-12-20'),
(13, 4, 6, 2, '2025-02-10'),
(14, 4, 7, 1, '2025-02-10'),
(15, 4, 8, 1, '2025-02-10'),
(16, 5, 5, 2, '2025-02-10'),
(17, 5, 9, 3, '2024-12-21'),
(18, 5, 11, 1, '2024-12-20'),
(19, 2, 3, 1, '2026-01-12'),
(20, 2, 20, 1, '2026-01-12'),
(21, 1, 3, 1, '2026-01-12'),
(22, 1, 20, 1, '2026-01-12'),
(23, 1, 17, 1, '2026-01-18'),
(24, 2, 10, 1, '2026-01-18'),
(25, 2, 15, 1, '2026-01-18'),
(26, 1, 15, 1, '2026-01-18'),
(27, 1, 6, 1, '2026-01-18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `activities_event_id_foreign` (`event_id`),
  ADD KEY `activities_place_id_foreign` (`place_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `events_creator_id_foreign` (`creator_id`);

--
-- Indexes for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD PRIMARY KEY (`participant_id`),
  ADD KEY `event_participants_event_id_foreign` (`event_id`),
  ADD KEY `event_participants_user_id_foreign` (`user_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `expenses_event_id_foreign` (`event_id`),
  ADD KEY `expenses_activity_id_foreign` (`activity_id`),
  ADD KEY `expenses_paid_by_user_id_foreign` (`paid_by_user_id`);

--
-- Indexes for table `expense_shares`
--
ALTER TABLE `expense_shares`
  ADD PRIMARY KEY (`share_id`),
  ADD KEY `expense_shares_expense_id_foreign` (`expense_id`),
  ADD KEY `expense_shares_user_id_foreign` (`user_id`);

--
-- Indexes for table `friendships`
--
ALTER TABLE `friendships`
  ADD PRIMARY KEY (`friendship_id`),
  ADD KEY `friendships_user_id_foreign` (`user_id`),
  ADD KEY `friendships_friend_id_foreign` (`friend_id`);

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
-- Indexes for table `places`
--
ALTER TABLE `places`
  ADD PRIMARY KEY (`place_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `user_place_visits`
--
ALTER TABLE `user_place_visits`
  ADD PRIMARY KEY (`visit_id`),
  ADD KEY `user_place_visits_user_id_foreign` (`user_id`),
  ADD KEY `user_place_visits_place_id_foreign` (`place_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `activity_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `event_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `event_participants`
--
ALTER TABLE `event_participants`
  MODIFY `participant_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `expense_shares`
--
ALTER TABLE `expense_shares`
  MODIFY `share_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `friendships`
--
ALTER TABLE `friendships`
  MODIFY `friendship_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `places`
--
ALTER TABLE `places`
  MODIFY `place_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user_place_visits`
--
ALTER TABLE `user_place_visits`
  MODIFY `visit_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activities`
--
ALTER TABLE `activities`
  ADD CONSTRAINT `activities_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `activities_place_id_foreign` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_creator_id_foreign` FOREIGN KEY (`creator_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `event_participants`
--
ALTER TABLE `event_participants`
  ADD CONSTRAINT `event_participants_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_activity_id_foreign` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`activity_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_paid_by_user_id_foreign` FOREIGN KEY (`paid_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `expense_shares`
--
ALTER TABLE `expense_shares`
  ADD CONSTRAINT `expense_shares_expense_id_foreign` FOREIGN KEY (`expense_id`) REFERENCES `expenses` (`expense_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expense_shares_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `friendships`
--
ALTER TABLE `friendships`
  ADD CONSTRAINT `friendships_friend_id_foreign` FOREIGN KEY (`friend_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `friendships_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_place_visits`
--
ALTER TABLE `user_place_visits`
  ADD CONSTRAINT `user_place_visits_place_id_foreign` FOREIGN KEY (`place_id`) REFERENCES `places` (`place_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_place_visits_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
