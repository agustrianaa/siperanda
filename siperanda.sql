-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 04 Sep 2024 pada 04.34
-- Versi server: 10.4.27-MariaDB
-- Versi PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `siperanda`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `admin`
--

INSERT INTO `admin` (`id`, `user_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 2, 'Ini Admin', '2024-07-23 00:14:03', '2024-07-23 00:14:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `anggaran`
--

CREATE TABLE `anggaran` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `all_anggaran` decimal(20,2) DEFAULT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `anggaran_perunit` decimal(15,2) DEFAULT NULL,
  `tahun` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `anggaran`
--

INSERT INTO `anggaran` (`id`, `all_anggaran`, `unit_id`, `anggaran_perunit`, `tahun`, `created_at`, `updated_at`) VALUES
(2, '34000000000.00', NULL, NULL, '2025-01-01', '2024-07-30 20:05:57', '2024-07-30 20:05:57'),
(3, '8800000000.00', NULL, NULL, '2024-01-01', '2024-07-30 20:07:19', '2024-07-30 20:07:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `detail_rencana`
--

CREATE TABLE `detail_rencana` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rencana_id` bigint(20) UNSIGNED NOT NULL,
  `noparent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_komponen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `satuan_id` bigint(20) UNSIGNED NOT NULL,
  `volume` varchar(255) NOT NULL,
  `harga` decimal(15,2) DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `uraian` text DEFAULT NULL,
  `is_revised` int(20) DEFAULT NULL,
  `revisi_keterangan` varchar(255) DEFAULT NULL,
  `created_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `detail_rencana`
--

INSERT INTO `detail_rencana` (`id`, `rencana_id`, `noparent_id`, `kode_komponen_id`, `satuan_id`, `volume`, `harga`, `total`, `uraian`, `is_revised`, `revisi_keterangan`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 15, 5, '3', '12345.00', '37035.00', NULL, NULL, NULL, 'unit', '2024-07-23 00:42:51', '2024-07-23 00:42:51'),
(2, 1, NULL, 13, 1, '10', '12345.00', '123450.00', NULL, 2, NULL, 'unit', '2024-07-23 01:09:08', '2024-07-31 02:47:34'),
(3, 1, 2, 25, 5, '10', '12345.00', '123450.00', NULL, NULL, NULL, 'unit', '2024-07-23 01:41:11', '2024-07-23 01:41:11'),
(4, 1, NULL, 11, 5, '3', '25000.00', '75000.00', NULL, NULL, NULL, 'unit', '2024-07-23 02:15:59', '2024-07-23 02:15:59'),
(9, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, NULL, NULL, 'unit', '2024-08-03 07:21:22', '2024-08-04 00:52:26'),
(10, 22, 9, 19, 5, '8', '1234500.00', '9876000.00', NULL, 1, NULL, 'unit', '2024-08-04 00:14:06', '2024-08-05 02:10:18'),
(11, 22, 10, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', NULL, NULL, 'unit', '2024-08-04 02:04:09', '2024-08-04 02:04:09'),
(12, 22, NULL, 28, 8, '2', '800000.00', '1600000.00', NULL, NULL, NULL, 'unit', '2024-08-22 02:05:23', '2024-09-03 19:21:24'),
(13, 22, NULL, NULL, 5, '2', '250000.00', '500000.00', 'Pengabdian', NULL, NULL, 'unit', '2024-09-03 19:21:04', '2024-09-03 19:21:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `direksi`
--

CREATE TABLE `direksi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `jabatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `direksi`
--

INSERT INTO `direksi` (`id`, `user_id`, `name`, `jabatan`, `created_at`, `updated_at`) VALUES
(1, 3, 'Ini Direksi', NULL, '2024-07-23 00:14:04', '2024-07-23 00:14:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `failed_jobs`
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
-- Struktur dari tabel `kategori`
--

CREATE TABLE `kategori` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nama_kategori` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kategori`
--

INSERT INTO `kategori` (`id`, `nama_kategori`, `created_at`, `updated_at`) VALUES
(1, 'Kegiatan', '2024-05-15 19:25:58', '2024-05-15 19:25:58'),
(2, 'Program', '2024-05-15 19:26:08', '2024-05-15 19:26:08'),
(3, 'KRO', '2024-05-15 19:26:12', '2024-05-15 19:26:12'),
(4, 'RO', '2024-05-15 19:26:23', '2024-05-15 19:26:23'),
(5, 'Komponen', '2024-05-15 19:26:33', '2024-05-15 19:26:33'),
(6, 'Detil', '2024-05-18 07:30:54', '2024-05-18 07:30:54'),
(7, 'Sub Komponen', '2024-05-18 08:14:04', '2024-05-18 08:14:04');

-- --------------------------------------------------------

--
-- Struktur dari tabel `kode_komponen`
--

CREATE TABLE `kode_komponen` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `kode` varchar(255) NOT NULL,
  `kode_parent` bigint(20) UNSIGNED DEFAULT NULL,
  `kategori_id` bigint(20) UNSIGNED NOT NULL,
  `uraian` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `kode_komponen`
--

INSERT INTO `kode_komponen` (`id`, `kode`, `kode_parent`, `kategori_id`, `uraian`, `created_at`, `updated_at`) VALUES
(10, 'DL', NULL, 2, 'Program Pendidikan dan Pelatihan Vokasi', '2024-07-09 01:40:16', '2024-07-09 01:40:16'),
(11, '4262', 10, 1, 'Pembinaan Sekolah Menengah Kejuruan', '2024-07-09 01:40:59', '2024-07-09 01:40:59'),
(12, 'PDI', 11, 3, 'Sertifikasi Profesi dan SDM', '2024-07-09 01:42:07', '2024-07-09 01:42:07'),
(13, '4466', 10, 1, 'Penyediaan Dana Bantuan Operasional Perguruan Tinggi Negeri Vokasi', '2024-07-09 01:43:23', '2024-07-09 01:43:23'),
(14, 'BEI', 13, 3, 'Bantuan Lembaga', '2024-07-09 01:43:50', '2024-07-09 01:43:50'),
(15, '001', 14, 4, 'PT Vokasi penerima  Dukungan Operasional  (BOPTN Vokasi)', '2024-07-09 01:44:24', '2024-07-09 01:44:24'),
(16, '004', 15, 5, 'Dukungan Operasional Penyelenggaraan Pendidikan', '2024-07-09 01:45:17', '2024-07-09 01:45:17'),
(17, '002', 14, 4, 'PT Vokasi penerima   Dukungan Layanan Pembelajaran (BOPTN Vokasi)', '2024-07-09 01:45:52', '2024-07-09 01:45:52'),
(18, '004', 17, 5, 'Dukungan Operasional Penyelenggaraan Pendidikan', '2024-07-09 01:46:24', '2024-07-09 01:46:24'),
(19, '006', 14, 4, 'PT Vokasi penerima Dukungan Sarana dan Prasarana Pembelajaran (BOPTN Vokasi)', '2024-07-09 01:47:34', '2024-07-09 01:47:34'),
(20, '004', 19, 5, 'Dukungan Operasional Penyelenggaraan Pendidikan', '2024-07-09 01:48:03', '2024-07-09 01:48:03'),
(21, '007', 14, 4, 'PT Vokasi penerima Bantuan Pendanaan Berbasis Indikator Kinerja Utama (BOPTN Vokasi)', '2024-07-09 01:48:33', '2024-07-09 01:48:33'),
(22, '004', 21, 5, 'Dukungan Operasional Penyelenggaraan Pendidikan', '2024-07-09 01:48:58', '2024-07-09 01:48:58'),
(23, '4467', 10, 1, 'Peningkatan Kualitas dan Kapasitas Perguruan Tinggi Vokasi', '2024-07-09 01:49:35', '2024-07-09 01:49:35'),
(24, 'BEI', 23, 3, 'Bantuan Lembaga', '2024-07-09 01:50:07', '2024-07-09 01:50:07'),
(25, '002', 14, 4, 'Penelitian (PNBP/BLU Vokasi)', '2024-07-09 01:50:43', '2024-07-09 01:50:43'),
(26, '051', 25, 5, 'Pelatihan/Sosialisasi Penyusunan Proposal Penelitian - pnbp', '2024-07-09 01:51:52', '2024-07-09 01:51:52'),
(27, '052', 25, 5, 'Pelatihan/Sosialisasi Penyusunan Proposal Penelitian - blu', '2024-07-09 01:52:39', '2024-07-09 01:52:39'),
(28, '1445', 26, 5, 'Pengajuan', '2024-07-18 19:13:06', '2024-07-18 19:13:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2014_10_12_100000_create_password_resets_table', 1),
(4, '2019_08_19_000000_create_failed_jobs_table', 1),
(5, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(6, '2024_03_31_073357_create_super_admin_table', 1),
(7, '2024_03_31_073748_create_admin_table', 1),
(8, '2024_03_31_073810_create_direksi_table', 1),
(9, '2024_03_31_073828_create_unit_table', 1),
(10, '2024_05_07_064258_create_kategori_table', 1),
(11, '2024_05_07_065121_create_satuan_table', 1),
(12, '2024_05_12_143556_create_kode_komponen_table', 1),
(13, '2024_05_12_143601_create_rencana_table', 1),
(14, '2024_05_12_143605_create_detail_rencana_table', 1),
(15, '2024_05_12_144258_create_realisasi_table', 1),
(16, '2024_06_05_160403_create_rpd_table', 1),
(17, '2024_06_05_162449_create_anggaran_table', 1),
(18, '2024_06_05_174840_create_note_revisi_table', 1),
(19, '2024_06_21_072032_create_revisi_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `note_revisi`
--

CREATE TABLE `note_revisi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rencana_id` bigint(20) UNSIGNED NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `note_revisi`
--

INSERT INTO `note_revisi` (`id`, `rencana_id`, `note`, `created_at`, `updated_at`) VALUES
(3, 22, 'masuk ke usulan 2', '2024-08-03 07:21:59', '2024-08-03 07:21:59'),
(4, 1, 'revisi again', '2024-08-03 07:39:17', '2024-08-03 07:39:17'),
(5, 22, 'Revisi lagi', '2024-08-04 00:36:40', '2024-08-04 00:36:40'),
(6, 22, 'revisi ya', '2024-08-04 00:37:20', '2024-08-04 00:37:20'),
(7, 22, 'Masuka ke revisi 2', '2024-08-04 00:52:04', '2024-08-04 00:52:04'),
(8, 22, 'Masuk revisi 3', '2024-08-04 00:53:40', '2024-08-04 00:53:40'),
(9, 22, 'Masuk ke revisi 3', '2024-08-04 01:03:17', '2024-08-04 01:03:17'),
(10, 22, 'revisi ke 4', '2024-08-04 02:04:27', '2024-08-04 02:04:27'),
(11, 22, 'revisi ke 4', '2024-08-04 02:04:50', '2024-08-04 02:04:50'),
(12, 22, 'revisi ke 4', '2024-08-04 02:05:16', '2024-08-04 02:05:16'),
(13, 22, 'revisi ke 4', '2024-08-04 02:06:27', '2024-08-04 02:06:27'),
(14, 22, 'REV 5', '2024-08-05 02:12:12', '2024-08-05 02:12:12'),
(15, 22, 'rev 6', '2024-08-19 00:35:13', '2024-08-19 00:35:13'),
(16, 22, 'ok', '2024-08-19 00:44:19', '2024-08-19 00:44:19'),
(17, 22, 'top up', '2024-08-19 00:45:12', '2024-08-19 00:45:12'),
(18, 22, 'top up', '2024-08-19 00:48:01', '2024-08-19 00:48:01'),
(19, 22, 'rev 8', '2024-08-19 01:04:05', '2024-08-19 01:04:05'),
(20, 22, 'rev again', '2024-08-29 19:16:20', '2024-08-29 19:16:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `realisasi`
--

CREATE TABLE `realisasi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detail_rencana_id` bigint(20) UNSIGNED NOT NULL,
  `bulan_realisasi` varchar(255) DEFAULT NULL,
  `jumlah` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `realisasi`
--

INSERT INTO `realisasi` (`id`, `detail_rencana_id`, `bulan_realisasi`, `jumlah`, `created_at`, `updated_at`) VALUES
(9, 9, 'Agustus', '300000.00', '2024-08-19 00:51:52', '2024-08-19 00:51:52'),
(10, 9, 'Januari', '80000.00', '2024-08-22 01:47:06', '2024-08-22 01:47:06');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rencana`
--

CREATE TABLE `rencana` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `unit_id` bigint(20) UNSIGNED NOT NULL,
  `tahun` date NOT NULL,
  `jumlah` decimal(15,2) DEFAULT NULL,
  `anggaran` decimal(15,2) DEFAULT NULL,
  `status` enum('draft','submitted','approved','rejected','revisi','top_up') NOT NULL,
  `revision` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rencana`
--

INSERT INTO `rencana` (`id`, `unit_id`, `tahun`, `jumlah`, `anggaran`, `status`, `revision`, `created_at`, `updated_at`) VALUES
(1, 1, '2020-01-01', NULL, '90000000.00', 'approved', 0, '2024-07-23 00:42:07', '2024-07-31 02:46:01'),
(22, 1, '2021-01-01', NULL, '56000000.00', 'approved', 13, '2024-08-03 07:20:36', '2024-08-29 19:16:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `revisi`
--

CREATE TABLE `revisi` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rencana_id` bigint(20) UNSIGNED NOT NULL,
  `noparent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `kode_komponen_id` bigint(20) UNSIGNED DEFAULT NULL,
  `satuan_id` bigint(20) UNSIGNED NOT NULL,
  `volume` varchar(255) NOT NULL,
  `harga` decimal(15,2) DEFAULT NULL,
  `total` decimal(15,2) DEFAULT NULL,
  `uraian` text DEFAULT NULL,
  `revision` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `revisi`
--

INSERT INTO `revisi` (`id`, `rencana_id`, `noparent_id`, `kode_komponen_id`, `satuan_id`, `volume`, `harga`, `total`, `uraian`, `revision`, `created_at`, `updated_at`) VALUES
(23, 22, NULL, 27, 1, '2', '300000.00', '600000.00', NULL, 0, '2024-08-04 00:52:04', '2024-08-04 00:52:04'),
(24, 22, NULL, 19, 5, '8', '1234500.00', '9876000.00', NULL, 0, '2024-08-04 00:52:04', '2024-08-04 00:52:04'),
(25, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 1, '2024-08-04 00:53:40', '2024-08-04 00:53:40'),
(26, 22, NULL, 19, 5, '7', '1234500.00', '8641500.00', NULL, 1, '2024-08-04 00:53:40', '2024-08-04 00:53:40'),
(27, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 2, '2024-08-04 01:03:17', '2024-08-04 01:03:17'),
(28, 22, NULL, 19, 5, '10', '1234500.00', '12345000.00', NULL, 2, '2024-08-04 01:03:17', '2024-08-04 01:03:17'),
(35, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 3, '2024-08-04 02:06:27', '2024-08-04 02:06:27'),
(36, 22, NULL, 19, 5, '10', '1234500.00', '12345000.00', NULL, 3, '2024-08-04 02:06:27', '2024-08-04 02:06:27'),
(37, 22, NULL, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 3, '2024-08-04 02:06:27', '2024-08-04 02:06:27'),
(38, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 4, '2024-08-05 02:12:12', '2024-08-05 02:12:12'),
(39, 22, NULL, 19, 5, '8', '1234500.00', '9876000.00', NULL, 4, '2024-08-05 02:12:12', '2024-08-05 02:12:12'),
(40, 22, NULL, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 4, '2024-08-05 02:12:12', '2024-08-05 02:12:12'),
(41, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 5, '2024-08-19 00:35:13', '2024-08-19 00:35:13'),
(42, 22, 9, 19, 5, '8', '1234500.00', '9876000.00', NULL, 5, '2024-08-19 00:35:13', '2024-08-19 00:35:13'),
(43, 22, 10, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 5, '2024-08-19 00:35:13', '2024-08-19 00:35:13'),
(44, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 6, '2024-08-19 00:45:12', '2024-08-19 00:45:12'),
(45, 22, 9, 19, 5, '8', '1234500.00', '9876000.00', NULL, 6, '2024-08-19 00:45:12', '2024-08-19 00:45:12'),
(46, 22, 10, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 6, '2024-08-19 00:45:12', '2024-08-19 00:45:12'),
(47, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 7, '2024-08-19 01:04:05', '2024-08-19 01:04:05'),
(48, 22, 9, 19, 5, '8', '1234500.00', '9876000.00', NULL, 7, '2024-08-19 01:04:05', '2024-08-19 01:04:05'),
(49, 22, 12, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 7, '2024-08-19 01:04:05', '2024-08-19 01:04:05'),
(50, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 8, '2024-08-19 01:07:06', '2024-08-19 01:07:06'),
(51, 22, NULL, 19, 5, '8', '1234500.00', '9876000.00', NULL, 8, '2024-08-19 01:07:06', '2024-08-19 01:07:06'),
(52, 22, NULL, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 8, '2024-08-19 01:07:06', '2024-08-19 01:07:06'),
(53, 22, 10, 27, 1, '1', '300000.00', '300000.00', NULL, 9, '2024-08-19 01:12:04', '2024-08-19 01:12:04'),
(54, 22, 9, 19, 5, '8', '1234500.00', '9876000.00', NULL, 9, '2024-08-19 01:12:04', '2024-08-19 01:12:04'),
(55, 22, 10, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 9, '2024-08-19 01:12:04', '2024-08-19 01:12:04'),
(56, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 10, '2024-08-19 01:15:53', '2024-08-19 01:15:53'),
(57, 22, 9, 19, 5, '8', '1234500.00', '9876000.00', NULL, 10, '2024-08-19 01:15:53', '2024-08-19 01:15:53'),
(58, 22, 10, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 10, '2024-08-19 01:15:53', '2024-08-19 01:15:53'),
(59, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 11, '2024-08-22 02:00:32', '2024-08-22 02:00:32'),
(60, 22, 9, 19, 5, '8', '1234500.00', '9876000.00', NULL, 11, '2024-08-22 02:00:32', '2024-08-22 02:00:32'),
(61, 22, 10, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 11, '2024-08-22 02:00:32', '2024-08-22 02:00:32'),
(62, 22, NULL, 27, 1, '1', '300000.00', '300000.00', NULL, 12, '2024-08-29 19:16:20', '2024-08-29 19:16:20'),
(63, 22, 9, 19, 5, '8', '1234500.00', '9876000.00', NULL, 12, '2024-08-29 19:16:20', '2024-08-29 19:16:20'),
(64, 22, 10, NULL, 1, '3', '25000.00', '75000.00', 'Reward Mahasiswa', 12, '2024-08-29 19:16:20', '2024-08-29 19:16:20'),
(65, 22, NULL, 28, 8, '12', '800000000.00', '9600000000.00', NULL, 12, '2024-08-29 19:16:20', '2024-08-29 19:16:20');

-- --------------------------------------------------------

--
-- Struktur dari tabel `rpd`
--

CREATE TABLE `rpd` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `detail_rencana_id` bigint(20) UNSIGNED NOT NULL,
  `bulan_rpd` varchar(255) DEFAULT NULL,
  `jumlah` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `rpd`
--

INSERT INTO `rpd` (`id`, `detail_rencana_id`, `bulan_rpd`, `jumlah`, `created_at`, `updated_at`) VALUES
(21, 9, 'January', '300000.00', '2024-08-19 00:50:11', '2024-08-19 00:50:11'),
(46, 10, 'February', '600000.00', '2024-09-03 18:40:18', '2024-09-03 18:40:18'),
(47, 10, 'August', '80000.00', '2024-09-03 18:40:18', '2024-09-03 18:41:07'),
(48, 10, 'October', '1000000.00', '2024-09-03 18:40:18', '2024-09-03 18:40:18');

-- --------------------------------------------------------

--
-- Struktur dari tabel `satuan`
--

CREATE TABLE `satuan` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `satuan`
--

INSERT INTO `satuan` (`id`, `satuan`, `created_at`, `updated_at`) VALUES
(1, 'Buah', '2024-05-15 19:27:25', '2024-05-15 19:27:25'),
(5, 'Dosen', '2024-05-18 07:34:09', '2024-05-18 07:34:09'),
(6, 'Gedung', '2024-05-18 08:12:43', '2024-05-18 08:12:43'),
(7, 'Hari', '2024-05-18 08:13:33', '2024-05-18 08:13:33'),
(8, 'PCS', '2024-08-22 01:17:42', '2024-08-22 01:17:42'),
(9, 'Bulan', '2024-08-22 01:17:50', '2024-08-22 01:17:50'),
(10, 'Mahasiswa', '2024-08-22 01:17:56', '2024-08-22 01:17:56'),
(11, 'Paket', '2024-08-22 01:22:54', '2024-08-22 01:22:54');

-- --------------------------------------------------------

--
-- Struktur dari tabel `super_admin`
--

CREATE TABLE `super_admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `super_admin`
--

INSERT INTO `super_admin` (`id`, `user_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 1, 'Rita Manik', '2024-07-23 00:14:03', '2024-07-23 00:14:03');

-- --------------------------------------------------------

--
-- Struktur dari tabel `unit`
--

CREATE TABLE `unit` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `unit`
--

INSERT INTO `unit` (`id`, `user_id`, `name`, `created_at`, `updated_at`) VALUES
(1, 4, 'Teknik Informatika', '2024-07-23 00:14:04', '2024-07-23 00:14:04'),
(2, 5, 'UPA TIK', '2024-09-03 19:28:44', '2024-09-03 19:28:44'),
(3, 6, 'Teknik Mesin', '2024-09-03 19:29:07', '2024-09-03 19:29:07'),
(4, 7, 'UPA Bahasa', '2024-09-03 19:29:40', '2024-09-03 19:29:40'),
(5, 8, 'UPA Perpustakaan', '2024-09-03 19:30:04', '2024-09-03 19:30:04'),
(6, 9, 'TPTU', '2024-09-03 19:30:22', '2024-09-03 19:30:22'),
(7, 10, 'Keperawatan', '2024-09-03 19:31:51', '2024-09-03 19:31:51');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` char(25) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'rita@gmail.com', NULL, '$2y$12$FuACJyQ06j4WzafJWN.He.qh8UPBT1HrgEyj73OxAk3vmIbJCmhXq', 'super_admin', NULL, '2024-07-23 00:14:02', '2024-07-23 00:14:02'),
(2, 'admin@gmail.com', NULL, '$2y$12$DqME.syNLG9ygX6sr8mqhurhsadjgzuH0SufabPouepDhycaCHjAW', 'admin', NULL, '2024-07-23 00:14:03', '2024-07-23 00:14:03'),
(3, 'direksi@gmail.com', NULL, '$2y$12$2QAy0LoF2.CyK0/iYfKDcefaBYcqll.qo0PVq8jAB3FUs3y.8GL0K', 'direksi', NULL, '2024-07-23 00:14:04', '2024-07-23 00:14:04'),
(4, 'teknikinformatika@gmail.com', NULL, '$2y$12$3KqyXcM4VXUhaQ4qXO2PiOm4HP0Pdl67ZzEOYJHPJMCuKKSRvX7rO', 'unit', NULL, '2024-07-23 00:14:04', '2024-07-23 00:14:04'),
(5, 'upatik@gmail.com', NULL, '$2y$12$zXKbW7BJvA9fuAZU7LBvO.vyMqH31Y9e5ax42NkwAmYfVejUAyTza', 'unit', NULL, '2024-09-03 19:28:43', '2024-09-03 19:28:43'),
(6, 'teknikmesin@gmail.com', NULL, '$2y$12$aAvHYqs2QlyKsktZQV2skOB7wINUvY4CaBmwgC0gG8tjViiOCQFVS', 'unit', NULL, '2024-09-03 19:29:07', '2024-09-03 19:29:07'),
(7, 'upabahasa@gmail.com', NULL, '$2y$12$lzM.y/w1Fzg3ayvDWgwgauABVECu5SaBFHZm6VCz7E540NfZvMA5e', 'unit', NULL, '2024-09-03 19:29:40', '2024-09-03 19:29:40'),
(8, 'upaperpustakaan@gmail.com', NULL, '$2y$12$AnaE8QmbGl7k1aWbmUvMJ.e/EWRXXz4KZX31VBxpCj3pKCz1J83tO', 'unit', NULL, '2024-09-03 19:30:04', '2024-09-03 19:30:04'),
(9, 'tptu@gmail.com', NULL, '$2y$12$bg2Uqz6XvEp/KvWcTtkU2uSrmQjaJUrktEAzW3olrfCmlVvnZZRle', 'unit', NULL, '2024-09-03 19:30:22', '2024-09-03 19:30:22'),
(10, 'kp@gmail.com', NULL, '$2y$12$vIjt5uBI.4KkGbeKCjV2TO6jYXhokWIIg04JRdy3mT5y5AfNjvnu6', 'unit', NULL, '2024-09-03 19:31:51', '2024-09-03 19:31:51');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `anggaran`
--
ALTER TABLE `anggaran`
  ADD PRIMARY KEY (`id`),
  ADD KEY `anggaran_unit_id_foreign` (`unit_id`);

--
-- Indeks untuk tabel `detail_rencana`
--
ALTER TABLE `detail_rencana`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_rencana_rencana_id_foreign` (`rencana_id`),
  ADD KEY `detail_rencana_noparent_id_foreign` (`noparent_id`),
  ADD KEY `detail_rencana_kode_komponen_id_foreign` (`kode_komponen_id`),
  ADD KEY `detail_rencana_satuan_id_foreign` (`satuan_id`);

--
-- Indeks untuk tabel `direksi`
--
ALTER TABLE `direksi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `direksi_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indeks untuk tabel `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kode_komponen`
--
ALTER TABLE `kode_komponen`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kode_komponen_kategori_id_foreign` (`kategori_id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `note_revisi`
--
ALTER TABLE `note_revisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `note_revisi_rencana_id_foreign` (`rencana_id`);

--
-- Indeks untuk tabel `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indeks untuk tabel `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indeks untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indeks untuk tabel `realisasi`
--
ALTER TABLE `realisasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `realisasi_detail_rencana_id_foreign` (`detail_rencana_id`);

--
-- Indeks untuk tabel `rencana`
--
ALTER TABLE `rencana`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rencana_unit_id_foreign` (`unit_id`);

--
-- Indeks untuk tabel `revisi`
--
ALTER TABLE `revisi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `revisi_rencana_id_foreign` (`rencana_id`),
  ADD KEY `revisi_noparent_id_foreign` (`noparent_id`),
  ADD KEY `revisi_kode_komponen_id_foreign` (`kode_komponen_id`),
  ADD KEY `revisi_satuan_id_foreign` (`satuan_id`);

--
-- Indeks untuk tabel `rpd`
--
ALTER TABLE `rpd`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rpd_detail_rencana_id_foreign` (`detail_rencana_id`);

--
-- Indeks untuk tabel `satuan`
--
ALTER TABLE `satuan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `super_admin_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `unit_user_id_foreign` (`user_id`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `anggaran`
--
ALTER TABLE `anggaran`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `detail_rencana`
--
ALTER TABLE `detail_rencana`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT untuk tabel `direksi`
--
ALTER TABLE `direksi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `kode_komponen`
--
ALTER TABLE `kode_komponen`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT untuk tabel `note_revisi`
--
ALTER TABLE `note_revisi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT untuk tabel `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `realisasi`
--
ALTER TABLE `realisasi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `rencana`
--
ALTER TABLE `rencana`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT untuk tabel `revisi`
--
ALTER TABLE `revisi`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT untuk tabel `rpd`
--
ALTER TABLE `rpd`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT untuk tabel `satuan`
--
ALTER TABLE `satuan`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `super_admin`
--
ALTER TABLE `super_admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `unit`
--
ALTER TABLE `unit`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `anggaran`
--
ALTER TABLE `anggaran`
  ADD CONSTRAINT `anggaran_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `detail_rencana`
--
ALTER TABLE `detail_rencana`
  ADD CONSTRAINT `detail_rencana_kode_komponen_id_foreign` FOREIGN KEY (`kode_komponen_id`) REFERENCES `kode_komponen` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_rencana_noparent_id_foreign` FOREIGN KEY (`noparent_id`) REFERENCES `detail_rencana` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_rencana_rencana_id_foreign` FOREIGN KEY (`rencana_id`) REFERENCES `rencana` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `detail_rencana_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `direksi`
--
ALTER TABLE `direksi`
  ADD CONSTRAINT `direksi_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `kode_komponen`
--
ALTER TABLE `kode_komponen`
  ADD CONSTRAINT `kode_komponen_kategori_id_foreign` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `note_revisi`
--
ALTER TABLE `note_revisi`
  ADD CONSTRAINT `note_revisi_rencana_id_foreign` FOREIGN KEY (`rencana_id`) REFERENCES `rencana` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `realisasi`
--
ALTER TABLE `realisasi`
  ADD CONSTRAINT `realisasi_detail_rencana_id_foreign` FOREIGN KEY (`detail_rencana_id`) REFERENCES `detail_rencana` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rencana`
--
ALTER TABLE `rencana`
  ADD CONSTRAINT `rencana_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `revisi`
--
ALTER TABLE `revisi`
  ADD CONSTRAINT `revisi_kode_komponen_id_foreign` FOREIGN KEY (`kode_komponen_id`) REFERENCES `kode_komponen` (`id`),
  ADD CONSTRAINT `revisi_noparent_id_foreign` FOREIGN KEY (`noparent_id`) REFERENCES `detail_rencana` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revisi_rencana_id_foreign` FOREIGN KEY (`rencana_id`) REFERENCES `rencana` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `revisi_satuan_id_foreign` FOREIGN KEY (`satuan_id`) REFERENCES `satuan` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `rpd`
--
ALTER TABLE `rpd`
  ADD CONSTRAINT `rpd_detail_rencana_id_foreign` FOREIGN KEY (`detail_rencana_id`) REFERENCES `detail_rencana` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `super_admin`
--
ALTER TABLE `super_admin`
  ADD CONSTRAINT `super_admin_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ketidakleluasaan untuk tabel `unit`
--
ALTER TABLE `unit`
  ADD CONSTRAINT `unit_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
