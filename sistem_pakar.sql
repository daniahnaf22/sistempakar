-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2024 at 01:57 PM
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
-- Database: `sistem_pakar`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `log_name` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `subject_type` varchar(255) DEFAULT NULL,
  `subject_id` bigint(20) UNSIGNED DEFAULT NULL,
  `causer_type` varchar(255) DEFAULT NULL,
  `causer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `properties` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`properties`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activity_log`
--

INSERT INTO `activity_log` (`id`, `log_name`, `description`, `subject_type`, `subject_id`, `causer_type`, `causer_id`, `properties`, `created_at`, `updated_at`) VALUES
(1, 'user', 'You have created user', 'App\\Models\\User', 1, NULL, NULL, '{\"attributes\":{\"name\":\"Dani Ahnaf Falih\",\"username\":\"admin\"}}', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(2, 'user', 'You have created user', 'App\\Models\\User', 2, NULL, NULL, '{\"attributes\":{\"name\":\"Johni\",\"username\":\"johni\"}}', '2024-05-06 12:57:56', '2024-05-06 12:57:56');

-- --------------------------------------------------------

--
-- Table structure for table `diagnosa_ds`
--

CREATE TABLE `diagnosa_ds` (
  `id_diagnosa` bigint(20) UNSIGNED NOT NULL,
  `nama_pemilik` varchar(255) NOT NULL,
  `diagnosa` longtext NOT NULL,
  `penyebab` longtext NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `diagnosa_ds`
--

INSERT INTO `diagnosa_ds` (`id_diagnosa`, `nama_pemilik`, `diagnosa`, `penyebab`, `created_at`, `updated_at`) VALUES
(1, 'wahyu', '{\"nama\":\"Rhinotracheitis\",\"nilai_belief\":0.88,\"persentase_penyakit\":\"88 %\",\"gejala_penyakit\":[{\"kode\":\"G1\",\"nama\":\"Diare Berdarah\"},{\"kode\":\"G5\",\"nama\":\"Sesak Nafas\"},{\"kode\":\"G8\",\"nama\":\"Batuk\"},{\"kode\":\"G9\",\"nama\":\"Muntah Dahak\"}]}', '{\"penyebab\":\"penyebab utama feline rhinotracheitis yaitu disebabkan karena virus\"}', '2024-05-08 04:49:38', '2024-05-08 04:49:38');

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
-- Table structure for table `gejalas`
--

CREATE TABLE `gejalas` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nilai_densitas` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gejalas`
--

INSERT INTO `gejalas` (`id`, `nama`, `kode`, `nilai_densitas`) VALUES
(1, 'Diare Berdarah', 'G1', 0.8),
(2, 'Demam', 'G2', 0.9),
(3, 'Muntah', 'G3', 0.5),
(4, 'Sariawan', 'G4', 1),
(5, 'Sesak Nafas', 'G5', 0.6),
(6, 'Gusi berdarah', 'G6', 0.8),
(7, 'Bersin', 'G7', 0.9),
(8, 'Batuk', 'G8', 0.9),
(9, 'Muntah Dahak', 'G9', 0.8),
(10, 'Anemia', 'G10', 0.6),
(11, 'Tidak Nafsu Makan', 'G11', 0.6),
(12, 'Denyut Jantung Cepat', 'G12', 0.2),
(13, 'Diarea', 'G13', 0.6),
(14, 'Dehidrasi', 'G14', 0.6),
(15, 'Susah Mengeluarkan Urin', 'G15', 1),
(16, 'Darah Dalam Urin', 'G16', 1),
(17, 'Sering Menjilati Daerah Genital', 'G17', 0.8),
(18, 'Peradangan pada selaput lendir mata', 'G18', 1),
(19, 'Benjolan bola mata', 'G19', 0.6),
(20, 'Sekresi mata', 'G20', 0.6),
(21, 'Kotoran yang sangat keras', 'G21', 0.8),
(22, 'Kotoran tidak bisa keluar', 'G22', 1),
(23, 'Lesu', 'G23', 0.6),
(24, 'Bulu Rontok', 'G24', 0.8),
(25, 'Jamur dikulit', 'G25', 0.8),
(26, 'Kulit Berkerak', 'G26', 0.8);

-- --------------------------------------------------------

--
-- Table structure for table `gejala_penyakit`
--

CREATE TABLE `gejala_penyakit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `gejala_id` int(10) UNSIGNED NOT NULL,
  `penyakit_id` int(10) UNSIGNED NOT NULL,
  `value_cf` double(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `gejala_penyakit`
--

INSERT INTO `gejala_penyakit` (`id`, `gejala_id`, `penyakit_id`, `value_cf`) VALUES
(1, 1, 1, 0.80),
(2, 2, 1, 1.00),
(3, 3, 1, 0.40),
(4, 2, 2, 1.00),
(5, 3, 2, 0.40),
(6, 4, 2, 1.00),
(7, 5, 2, 0.60),
(8, 6, 2, 0.80),
(9, 2, 3, 1.00),
(10, 5, 3, 0.60),
(11, 7, 3, 1.00),
(12, 8, 3, 1.00),
(13, 9, 3, 0.80),
(14, 2, 4, 0.60),
(15, 3, 4, 0.40),
(16, 10, 4, 0.60),
(17, 11, 4, 0.80),
(18, 12, 4, 0.20),
(19, 13, 4, 0.60),
(20, 14, 4, 0.60),
(21, 3, 5, 0.80),
(22, 11, 5, 0.80),
(23, 15, 5, 1.00),
(24, 16, 5, 1.00),
(25, 17, 5, 0.80),
(26, 7, 6, 0.80),
(27, 8, 6, 0.80),
(28, 18, 6, 1.00),
(29, 19, 6, 0.60),
(30, 20, 6, 0.60),
(31, 11, 7, 0.80),
(32, 21, 7, 0.80),
(33, 22, 7, 1.00),
(34, 23, 7, 0.60),
(35, 24, 8, 0.80),
(36, 25, 8, 0.80),
(37, 26, 8, 0.80);

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
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2017_08_24_000000_create_settings_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2021_04_23_030446_create_permission_tables', 1),
(6, '2021_04_28_072156_create_activity_log_table', 1),
(7, '2022_05_25_045640_create_penyakits_table', 1),
(8, '2022_05_25_045757_create_gejalas_table', 1),
(9, '2022_05_28_075608_create_riwayats_table', 1),
(10, '2022_06_27_191302_create_gejala_penyakit_table', 1),
(11, '2024_04_15_212623_create_rules_ds', 1),
(12, '2024_04_16_164732_create_ds_diagnosa', 1);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `penyakits`
--

CREATE TABLE `penyakits` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `penyebab` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `penyakits`
--

INSERT INTO `penyakits` (`id`, `kode`, `nama`, `penyebab`) VALUES
(1, 'P1', 'Panleukopenia', 'Panleukopenia disebabkan oleh feline parvovirus'),
(2, 'P2', 'Calici Virus', 'yang menyebabkan infeksi pernapasan ringan hingga parah pada kucing'),
(3, 'P3', 'Rhinotracheitis', 'penyebab utama feline rhinotracheitis yaitu disebabkan karena virus'),
(4, 'P4', 'Haemobartonellosis', ' Parasit ini dapat menyebabkan anemia hemolitik pada kucing, yang ditandai dengan penurunan jumlah sel darah merah atau kadar hemoglobin'),
(5, 'P5', 'FLUTD', 'FLUTD merujuk pada sekelompok penyakit yang mempengaruhi saluran kemih bagian bawah kucing'),
(6, 'P6', 'Chlamydiosis', 'Gejala chlamydia pada kucing bisa meliputi infeksi saluran pernapasan atas dan dapat menyebabkan konjungtivitis atau peradangan pada mata'),
(7, 'P7', 'Megacolon', 'kondisi kesehatan yang mempengaruhi usus besar kucing'),
(8, 'P8', 'Ring Worm', 'salah satu penyakit infeksi jamur kulit yang paling banyak ditemui pada kucing');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'dashboard', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(2, 'logs-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(3, 'logs-delete', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(4, 'role-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(5, 'role-create', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(6, 'role-edit', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(7, 'role-delete', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(8, 'member-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(9, 'member-create', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(10, 'member-edit', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(11, 'member-delete', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(12, 'setting-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(13, 'setting-edit', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(14, 'penyakit-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(15, 'penyakit-create', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(16, 'penyakit-edit', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(17, 'penyakit-delete', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(18, 'gejala-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(19, 'gejala-create', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(20, 'gejala-edit', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(21, 'gejala-delete', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(22, 'rules-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(23, 'rules-edit', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(24, 'rulesDs-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(25, 'rulesDs-edit', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(26, 'diagnosa', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(27, 'diagnosa-create', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(28, 'diagnosaDs', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(29, 'diagnosa-createDs', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(30, 'riwayat-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(31, 'riwayat-show', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(32, 'riwayatDs-list', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(33, 'riwayatDs-show', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55');

-- --------------------------------------------------------

--
-- Table structure for table `riwayats`
--

CREATE TABLE `riwayats` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama` varchar(255) NOT NULL,
  `hasil_diagnosa` text NOT NULL,
  `cf_max` text NOT NULL,
  `gejala_terpilih` text NOT NULL,
  `file_pdf` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riwayats`
--

INSERT INTO `riwayats` (`id`, `nama`, `hasil_diagnosa`, `cf_max`, `gejala_terpilih`, `file_pdf`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Wahyu', 'a:1:{i:3;a:4:{s:13:\"nama_penyakit\";s:15:\"Rhinotracheitis\";s:13:\"kode_penyakit\";s:2:\"P3\";s:6:\"gejala\";a:2:{i:0;a:5:{s:4:\"nama\";s:11:\"Sesak Nafas\";s:4:\"kode\";s:2:\"G5\";s:7:\"cf_user\";s:3:\"0.4\";s:7:\"cf_role\";d:0.6;s:15:\"hasil_perkalian\";d:0.24;}i:1;a:5:{s:4:\"nama\";s:5:\"Batuk\";s:4:\"kode\";s:2:\"G8\";s:7:\"cf_user\";s:3:\"0.8\";s:7:\"cf_role\";d:1;s:15:\"hasil_perkalian\";d:0.8;}}s:8:\"hasil_cf\";d:0.8480000000000001;}}', 'a:2:{i:0;d:0.8480000000000001;i:1;s:20:\"Rhinotracheitis (P3)\";}', 'a:3:{i:1;a:4:{s:4:\"nama\";s:14:\"Diare Berdarah\";s:4:\"kode\";s:2:\"G1\";s:7:\"cf_user\";s:3:\"0.6\";s:9:\"keyakinan\";s:17:\"Kemungkinan Besar\";}i:5;a:4:{s:4:\"nama\";s:11:\"Sesak Nafas\";s:4:\"kode\";s:2:\"G5\";s:7:\"cf_user\";s:3:\"0.4\";s:9:\"keyakinan\";s:7:\"Mungkin\";}i:8;a:4:{s:4:\"nama\";s:5:\"Batuk\";s:4:\"kode\";s:2:\"G8\";s:7:\"cf_user\";s:3:\"0.8\";s:9:\"keyakinan\";s:12:\"Hampir pasti\";}}', 'Diagnosa-Wahyu-1715143091.pdf', 1, '2024-05-08 04:38:11', '2024-05-08 04:38:14');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'web', '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(2, 'Pengguna', 'web', '2024-05-06 12:57:56', '2024-05-06 12:57:56');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_has_permissions`
--

INSERT INTO `role_has_permissions` (`permission_id`, `role_id`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(7, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(12, 1),
(13, 1),
(14, 1),
(15, 1),
(16, 1),
(17, 1),
(18, 1),
(19, 1),
(20, 1),
(21, 1),
(22, 1),
(23, 1),
(24, 1),
(24, 2),
(25, 1),
(25, 2),
(26, 1),
(26, 2),
(27, 1),
(27, 2),
(28, 1),
(29, 1),
(30, 1),
(31, 1),
(32, 1),
(33, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rule_ds`
--

CREATE TABLE `rule_ds` (
  `id_basis_pengetahuan` bigint(20) UNSIGNED NOT NULL,
  `kode_penyakit` varchar(255) NOT NULL,
  `kode_gejala` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rule_ds`
--

INSERT INTO `rule_ds` (`id_basis_pengetahuan`, `kode_penyakit`, `kode_gejala`, `created_at`, `updated_at`) VALUES
(1, 'P1', 'G1', NULL, NULL),
(2, 'P1', 'G2', NULL, NULL),
(3, 'P1', 'G3', NULL, NULL),
(4, 'P2', 'G2', NULL, NULL),
(5, 'P2', 'G3', NULL, NULL),
(6, 'P2', 'G4', NULL, NULL),
(7, 'P2', 'G5', NULL, NULL),
(8, 'P2', 'G6', NULL, NULL),
(9, 'P3', 'G2', NULL, NULL),
(10, 'P3', 'G5', NULL, NULL),
(11, 'P3', 'G7', NULL, NULL),
(12, 'P3', 'G8', NULL, NULL),
(13, 'P3', 'G9', NULL, NULL),
(14, 'P4', 'G2', NULL, NULL),
(15, 'P4', 'G3', NULL, NULL),
(16, 'P4', 'G10', NULL, NULL),
(17, 'P4', 'G11', NULL, NULL),
(18, 'P4', 'G12', NULL, NULL),
(19, 'P4', 'G13', NULL, NULL),
(20, 'P4', 'G14', NULL, NULL),
(21, 'P5', 'G3', NULL, NULL),
(22, 'P5', 'G11', NULL, NULL),
(23, 'P5', 'G15', NULL, NULL),
(24, 'P5', 'G16', NULL, NULL),
(25, 'P5', 'G17', NULL, NULL),
(26, 'P6', 'G7', NULL, NULL),
(27, 'P6', 'G8', NULL, NULL),
(28, 'P6', 'G18', NULL, NULL),
(29, 'P6', 'G19', NULL, NULL),
(30, 'P6', 'G20', NULL, NULL),
(31, 'P7', 'G11', NULL, NULL),
(32, 'P7', 'G21', NULL, NULL),
(33, 'P7', 'G22', NULL, NULL),
(34, 'P7', 'G23', NULL, NULL),
(35, 'P8', 'G24', NULL, NULL),
(36, 'P8', 'G25', NULL, NULL),
(37, 'P8', 'G26', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(10) UNSIGNED NOT NULL,
  `key` varchar(255) DEFAULT NULL,
  `value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `avatar`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Dani Ahnaf Falih', 'admin', '$2y$10$Vuc/9e2s6SNi6um1h9NVMuFnqOU08vXiIhiOBEdscO0as1V2N2nUu', NULL, NULL, '2024-05-06 12:57:55', '2024-05-06 12:57:55'),
(2, 'Johni', 'johni', '$2y$10$/u5bklrTdvB/vkigoOYwqeqTopiFgwxpZgAbNejzpeL9HuunY/hmS', NULL, NULL, '2024-05-06 12:57:56', '2024-05-06 12:57:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject` (`subject_type`,`subject_id`),
  ADD KEY `causer` (`causer_type`,`causer_id`),
  ADD KEY `activity_log_log_name_index` (`log_name`);

--
-- Indexes for table `diagnosa_ds`
--
ALTER TABLE `diagnosa_ds`
  ADD PRIMARY KEY (`id_diagnosa`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `gejalas`
--
ALTER TABLE `gejalas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `gejala_penyakit`
--
ALTER TABLE `gejala_penyakit`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `penyakits`
--
ALTER TABLE `penyakits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `riwayats`
--
ALTER TABLE `riwayats`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `rule_ds`
--
ALTER TABLE `rule_ds`
  ADD PRIMARY KEY (`id_basis_pengetahuan`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `settings_key_index` (`key`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `diagnosa_ds`
--
ALTER TABLE `diagnosa_ds`
  MODIFY `id_diagnosa` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gejalas`
--
ALTER TABLE `gejalas`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `gejala_penyakit`
--
ALTER TABLE `gejala_penyakit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `penyakits`
--
ALTER TABLE `penyakits`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `riwayats`
--
ALTER TABLE `riwayats`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `rule_ds`
--
ALTER TABLE `rule_ds`
  MODIFY `id_basis_pengetahuan` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
