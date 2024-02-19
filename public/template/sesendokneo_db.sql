-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 19 Feb 2024 pada 20.25
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sesendokneo_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun_neo`
--

CREATE TABLE `akun_neo` (
  `id` int(8) NOT NULL,
  `akun` int(11) NOT NULL,
  `kelompok` int(11) DEFAULT NULL,
  `jenis` int(11) DEFAULT NULL,
  `objek` int(11) DEFAULT NULL,
  `rincian_objek` int(11) DEFAULT NULL,
  `sub_rincian_objek` int(11) DEFAULT NULL,
  `kode` varchar(25) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `belanja` tinyint(1) DEFAULT NULL,
  `pembiayaan` tinyint(1) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `asb_neo`
--

CREATE TABLE `asb_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_kel` varchar(400) NOT NULL,
  `uraian_barang` varchar(400) NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `kd_rek_akun` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`kd_rek_akun`)),
  `kd_rek_akun_asli` text NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aset_neo`
--

CREATE TABLE `aset_neo` (
  `id` int(8) NOT NULL,
  `akun` int(11) NOT NULL,
  `kelompok` int(11) DEFAULT NULL,
  `jenis` int(11) DEFAULT NULL,
  `objek` int(11) DEFAULT NULL,
  `rincian_objek` int(11) DEFAULT NULL,
  `sub_rincian_objek` int(11) DEFAULT NULL,
  `kode` varchar(25) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `belanja` tinyint(1) DEFAULT NULL,
  `pembiayaan` tinyint(1) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bidang_urusan_neo`
--

CREATE TABLE `bidang_urusan_neo` (
  `id` int(11) NOT NULL,
  `kode` varchar(4) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `disable` int(11) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dpa_neo`
--

CREATE TABLE `dpa_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(22) NOT NULL,
  `kel_kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(22) NOT NULL,
  `kel_kd_akun` varchar(50) NOT NULL DEFAULT '0',
  `disable_komponen` tinyint(1) NOT NULL DEFAULT 0,
  `uraian` text NOT NULL,
  `jenis_kelompok` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `jenis_standar_harga` varchar(6) NOT NULL,
  `id_standar_harga` int(11) NOT NULL,
  `komponen` varchar(400) NOT NULL,
  `spesifikasi` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `vol_1` decimal(12,0) NOT NULL,
  `vol_2` decimal(12,0) NOT NULL,
  `vol_3` decimal(12,0) NOT NULL,
  `vol_4` decimal(12,0) NOT NULL,
  `vol_5` decimal(12,0) NOT NULL,
  `sat_1` varchar(50) NOT NULL,
  `sat_2` varchar(50) NOT NULL,
  `sat_3` varchar(50) NOT NULL,
  `sat_4` varchar(50) NOT NULL,
  `sat_5` varchar(50) NOT NULL,
  `jumlah` decimal(12,0) NOT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `keterangan` int(11) NOT NULL,
  `aksi` int(11) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  `tgl_input` date NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username_input` varchar(255) NOT NULL,
  `username_update` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dppa_neo`
--

CREATE TABLE `dppa_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(22) NOT NULL,
  `kel_kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(22) NOT NULL,
  `kel_kd_akun` varchar(50) NOT NULL DEFAULT '0',
  `disable_komponen` tinyint(1) NOT NULL DEFAULT 0,
  `uraian` text NOT NULL,
  `jenis_kelompok` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `jenis_standar_harga` varchar(6) NOT NULL,
  `id_standar_harga` int(11) NOT NULL,
  `komponen` varchar(400) NOT NULL,
  `spesifikasi` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `vol_1` decimal(12,0) NOT NULL,
  `vol_2` decimal(12,0) NOT NULL,
  `vol_3` decimal(12,0) NOT NULL,
  `vol_4` decimal(12,0) NOT NULL,
  `vol_5` decimal(12,0) NOT NULL,
  `sat_1` varchar(50) NOT NULL,
  `sat_2` varchar(50) NOT NULL,
  `sat_3` varchar(50) NOT NULL,
  `sat_4` varchar(50) NOT NULL,
  `sat_5` varchar(50) NOT NULL,
  `jumlah` decimal(12,0) NOT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `keterangan` int(11) NOT NULL,
  `aksi` int(11) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  `tgl_input` date NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username_input` varchar(255) NOT NULL,
  `username_update` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hspk_neo`
--

CREATE TABLE `hspk_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_kel` varchar(400) NOT NULL,
  `uraian_barang` text NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(18,6) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `kd_rek_akun` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`kd_rek_akun`)),
  `kd_rek_akun_asli` text NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kd_wilayah_neo`
--

CREATE TABLE `kd_wilayah_neo` (
  `id` int(11) NOT NULL,
  `kode` int(11) NOT NULL,
  `uraian` int(11) NOT NULL,
  `prioritas_pembangunan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`prioritas_pembangunan`)),
  `disable` int(11) DEFAULT NULL,
  `peraturan` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan_neo`
--

CREATE TABLE `kegiatan_neo` (
  `id` int(8) NOT NULL,
  `kode` varchar(8) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapping_aset_akun`
--

CREATE TABLE `mapping_aset_akun` (
  `id` int(11) NOT NULL,
  `kode_aset` varchar(17) NOT NULL,
  `uraian_aset` varchar(400) NOT NULL,
  `kode_akun` varchar(17) DEFAULT NULL,
  `uraian_akun` varchar(400) DEFAULT NULL,
  `kelompok` varchar(5) DEFAULT NULL,
  `disable` int(11) NOT NULL DEFAULT 0,
  `aksi` tinyint(1) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `organisasi_neo`
--

CREATE TABLE `organisasi_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `kode` varchar(30) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `alamat` varchar(255) DEFAULT NULL,
  `nama_kepala` varchar(255) DEFAULT NULL,
  `nip_kepala` varchar(19) DEFAULT NULL,
  `singkatan` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `tahun_renstra` year(4) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan_neo`
--

CREATE TABLE `pengaturan_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `tahun` year(4) NOT NULL,
  `aturan_anggaran` varchar(255) NOT NULL,
  `aturan_pengadaan` varchar(255) NOT NULL,
  `aturan_akun` int(11) NOT NULL,
  `aturan_asb` int(11) NOT NULL,
  `aturan_sbu` int(11) NOT NULL,
  `aturan_ssh` int(11) NOT NULL,
  `aturan_hspk` int(11) NOT NULL,
  `aturan_sumber_dana` int(11) NOT NULL,
  `aturan_sub_kegiatan` int(11) NOT NULL,
  `rpjmd_aktif` varchar(255) DEFAULT NULL,
  `awal_renja` datetime DEFAULT NULL,
  `akhir_renja` datetime DEFAULT NULL,
  `awal_dpa` datetime DEFAULT NULL,
  `akhir_dpa` datetime DEFAULT NULL,
  `awal_renja_p` datetime DEFAULT NULL,
  `akhir_renja_p` datetime DEFAULT NULL,
  `awal_dppa` datetime DEFAULT NULL,
  `akhir_dppa` datetime DEFAULT NULL,
  `awal_renstra` datetime DEFAULT NULL,
  `akhir_renstra` datetime DEFAULT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(266) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peraturan_neo`
--

CREATE TABLE `peraturan_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `type_dok` varchar(255) NOT NULL,
  `judul` varchar(400) NOT NULL,
  `nomor` varchar(255) NOT NULL,
  `bentuk` varchar(255) NOT NULL,
  `bentuk_singkat` varchar(255) NOT NULL,
  `t4_penetapan` varchar(255) NOT NULL,
  `tgl_penetapan` date NOT NULL,
  `tgl_pengundangan` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(50) DEFAULT NULL,
  `file` varchar(400) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `program_neo`
--

CREATE TABLE `program_neo` (
  `id` int(8) NOT NULL,
  `kode` varchar(8) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekanan_neo`
--

CREATE TABLE `rekanan_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `nama_perusahaan` varchar(255) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `npwp` varchar(21) NOT NULL,
  `no_rekening` varchar(255) DEFAULT NULL,
  `bank_rekening` varchar(255) DEFAULT NULL,
  `atas_nama_rekening` varchar(255) DEFAULT NULL,
  `direktur` varchar(255) NOT NULL,
  `jabatan` varchar(150) DEFAULT NULL,
  `no_ktp` varchar(255) DEFAULT NULL,
  `alamat_dir` varchar(255) DEFAULT NULL,
  `no_akta_pendirian` varchar(255) DEFAULT NULL,
  `tgl_akta_pendirian` date DEFAULT NULL,
  `lokasi_notaris_pendirian` varchar(255) DEFAULT NULL,
  `nama_notaris_pendirian` varchar(255) DEFAULT NULL,
  `notaris_perubahan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`notaris_perubahan`)),
  `data_lain` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`data_lain`)),
  `file` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `username` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `renja_neo`
--

CREATE TABLE `renja_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(22) NOT NULL,
  `kel_kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(22) NOT NULL,
  `kel_kd_akun` varchar(50) NOT NULL DEFAULT '0',
  `disable_komponen` tinyint(1) NOT NULL DEFAULT 0,
  `uraian` text NOT NULL,
  `jenis_kelompok` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `jenis_standar_harga` varchar(6) NOT NULL,
  `id_standar_harga` int(11) NOT NULL,
  `komponen` varchar(400) NOT NULL,
  `spesifikasi` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `vol_1` decimal(12,0) NOT NULL,
  `vol_2` decimal(12,0) NOT NULL,
  `vol_3` decimal(12,0) NOT NULL,
  `vol_4` decimal(12,0) NOT NULL,
  `vol_5` decimal(12,0) NOT NULL,
  `sat_1` varchar(50) NOT NULL,
  `sat_2` varchar(50) NOT NULL,
  `sat_3` varchar(50) NOT NULL,
  `sat_4` varchar(50) NOT NULL,
  `sat_5` varchar(50) NOT NULL,
  `jumlah` decimal(12,0) NOT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `keterangan` int(11) NOT NULL,
  `aksi` int(11) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  `tgl_input` date NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username_input` varchar(255) NOT NULL,
  `username_update` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `renja_p_neo`
--

CREATE TABLE `renja_p_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(22) NOT NULL,
  `kel_kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(22) NOT NULL,
  `kel_kd_akun` varchar(50) NOT NULL DEFAULT '0',
  `disable_komponen` tinyint(1) NOT NULL DEFAULT 0,
  `uraian` text NOT NULL,
  `jenis_kelompok` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `jenis_standar_harga` varchar(6) NOT NULL,
  `id_standar_harga` int(11) NOT NULL,
  `komponen` varchar(400) NOT NULL,
  `spesifikasi` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `satuan` varchar(255) NOT NULL,
  `vol_1` decimal(12,0) NOT NULL,
  `vol_2` decimal(12,0) NOT NULL,
  `vol_3` decimal(12,0) NOT NULL,
  `vol_4` decimal(12,0) NOT NULL,
  `vol_5` decimal(12,0) NOT NULL,
  `sat_1` varchar(50) NOT NULL,
  `sat_2` varchar(50) NOT NULL,
  `sat_3` varchar(50) NOT NULL,
  `sat_4` varchar(50) NOT NULL,
  `sat_5` varchar(50) NOT NULL,
  `jumlah` decimal(12,0) NOT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `keterangan` int(11) NOT NULL,
  `aksi` int(11) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  `tgl_input` date NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username_input` varchar(255) NOT NULL,
  `username_update` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `renstra_skpd_neo`
--

CREATE TABLE `renstra_skpd_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(15) NOT NULL,
  `kd_opd` varchar(255) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `tujuan` int(11) DEFAULT NULL,
  `sasaran` int(11) DEFAULT NULL,
  `sasaran_txt` text DEFAULT NULL,
  `uraian_prog_keg` text NOT NULL,
  `indikator` text DEFAULT NULL,
  `satuan` varchar(255) DEFAULT NULL,
  `data_capaian_awal` decimal(32,12) DEFAULT NULL,
  `target_thn_1` decimal(32,12) DEFAULT NULL,
  `dana_thn_1` decimal(32,12) DEFAULT NULL,
  `target_thn_2` decimal(32,12) DEFAULT NULL,
  `dana_thn_2` decimal(32,12) DEFAULT NULL,
  `target_thn_3` decimal(32,12) DEFAULT NULL,
  `dana_thn_3` decimal(32,12) DEFAULT NULL,
  `target_thn_4` decimal(32,12) DEFAULT NULL,
  `dana_thn_4` decimal(32,12) DEFAULT NULL,
  `target_thn_5` decimal(32,12) DEFAULT NULL,
  `dana_thn_5` decimal(32,12) DEFAULT NULL,
  `target_thn_6` decimal(32,12) DEFAULT NULL,
  `dana_thn_6` decimal(32,12) DEFAULT NULL,
  `kondisi_akhir` decimal(32,12) NOT NULL,
  `unit_kerja` varchar(255) NOT NULL,
  `lokasi` varchar(255) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `satuan_neo`
--

CREATE TABLE `satuan_neo` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `item` varchar(255) NOT NULL,
  `sebutan_lain` varchar(255) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL,
  `aksi` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sbu_neo`
--

CREATE TABLE `sbu_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_kel` varchar(400) NOT NULL,
  `uraian_barang` varchar(400) NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(18,6) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `kd_rek_akun` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`kd_rek_akun`)),
  `kd_rek_akun_asli` text NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ssh_neo`
--

CREATE TABLE `ssh_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_kel` varchar(400) NOT NULL,
  `uraian_barang` text NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(18,6) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `kd_rek_akun` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`kd_rek_akun`)),
  `kd_rek_akun_asli` text NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kegiatan_neo`
--

CREATE TABLE `sub_kegiatan_neo` (
  `id` int(8) NOT NULL,
  `urusan` varchar(2) NOT NULL,
  `bidang` varchar(2) NOT NULL,
  `prog` int(11) DEFAULT NULL,
  `keg` varchar(8) DEFAULT NULL,
  `sub_keg` int(11) DEFAULT NULL,
  `kode` varchar(25) NOT NULL,
  `nomenklatur_urusan` text NOT NULL,
  `kinerja` text DEFAULT NULL,
  `indikator` text DEFAULT NULL,
  `satuan` varchar(100) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_keg_dpa_neo`
--

CREATE TABLE `sub_keg_dpa_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(22) NOT NULL,
  `kel_kd_sub_keg` varchar(50) NOT NULL,
  `uraian` text NOT NULL,
  `tolak_ukur_capaian_keg` varchar(255) DEFAULT NULL,
  `target_kinerja_capaian_keg` varchar(400) DEFAULT NULL,
  `tolak_ukur_keluaran` varchar(400) DEFAULT NULL,
  `target_kinerja_keluaran` varchar(255) DEFAULT NULL,
  `tolak_ukur_hasil` varchar(255) DEFAULT NULL,
  `target_kinerja_hasil` varchar(255) DEFAULT NULL,
  `tolak_ukur_capaian_keg_p` varchar(255) DEFAULT NULL,
  `target_kinerja_capaian_keg_p` varchar(400) DEFAULT NULL,
  `tolak_ukur_keluaran_p` varchar(400) DEFAULT NULL,
  `target_kinerja_keluaran_p` varchar(255) DEFAULT NULL,
  `tolak_ukur_hasil_p` varchar(255) DEFAULT NULL,
  `target_kinerja_hasil_p` varchar(255) DEFAULT NULL,
  `sumber_dana` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{}',
  `lokasi` varchar(255) DEFAULT NULL,
  `keluaran_sub_keg` varchar(255) DEFAULT NULL,
  `keluaran_sub_keg_p` varchar(255) DEFAULT NULL,
  `awal_pelaksanaan` date DEFAULT NULL,
  `akhir_pelaksanaan` date DEFAULT NULL,
  `jumlah_pagu` decimal(40,12) DEFAULT NULL,
  `jumlah_pagu_p` decimal(40,12) DEFAULT NULL,
  `jumlah_rincian` decimal(40,12) DEFAULT NULL,
  `jumlah_rincian_p` decimal(40,12) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL,
  `aksi` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_keg_renja_neo`
--

CREATE TABLE `sub_keg_renja_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(22) NOT NULL,
  `kel_kd_sub_keg` varchar(50) NOT NULL,
  `uraian` text NOT NULL,
  `tolak_ukur_capaian_keg` varchar(255) DEFAULT NULL,
  `target_kinerja_capaian_keg` varchar(400) DEFAULT NULL,
  `tolak_ukur_keluaran` varchar(400) DEFAULT NULL,
  `target_kinerja_keluaran` varchar(255) DEFAULT NULL,
  `tolak_ukur_hasil` varchar(255) DEFAULT NULL,
  `target_kinerja_hasil` varchar(255) DEFAULT NULL,
  `tolak_ukur_capaian_keg_p` varchar(255) DEFAULT NULL,
  `target_kinerja_capaian_keg_p` varchar(400) DEFAULT NULL,
  `tolak_ukur_keluaran_p` varchar(400) DEFAULT NULL,
  `target_kinerja_keluaran_p` varchar(255) DEFAULT NULL,
  `tolak_ukur_hasil_p` varchar(255) DEFAULT NULL,
  `target_kinerja_hasil_p` varchar(255) DEFAULT NULL,
  `sumber_dana` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{}',
  `lokasi` varchar(255) DEFAULT NULL,
  `keluaran_sub_keg` varchar(255) DEFAULT NULL,
  `keluaran_sub_keg_p` varchar(255) DEFAULT NULL,
  `awal_pelaksanaan` date DEFAULT NULL,
  `akhir_pelaksanaan` date DEFAULT NULL,
  `jumlah_pagu` decimal(40,12) DEFAULT NULL,
  `jumlah_pagu_p` decimal(40,12) DEFAULT NULL,
  `jumlah_rincian` decimal(40,12) DEFAULT NULL,
  `jumlah_rincian_p` decimal(40,12) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL,
  `aksi` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sumber_dana_neo`
--

CREATE TABLE `sumber_dana_neo` (
  `id` int(8) NOT NULL,
  `sumber_dana` int(11) NOT NULL,
  `kelompok` int(11) DEFAULT NULL,
  `jenis` int(11) DEFAULT NULL,
  `objek` int(11) DEFAULT NULL,
  `rincian_objek` int(11) DEFAULT NULL,
  `sub_rincian_objek` int(11) NOT NULL,
  `kode` varchar(25) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tujuan_sasaran_renstra_neo`
--

CREATE TABLE `tujuan_sasaran_renstra_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(15) NOT NULL,
  `kd_opd` varchar(255) NOT NULL,
  `tahun` year(4) NOT NULL,
  `id_tujuan` int(11) DEFAULT NULL,
  `kelompok` varchar(50) NOT NULL,
  `text` text NOT NULL,
  `indikator` text DEFAULT NULL,
  `keterangan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_sesendok_biila`
--

CREATE TABLE `user_sesendok_biila` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(225) NOT NULL,
  `kd_organisasi` varchar(30) NOT NULL,
  `nama_org` varchar(400) NOT NULL,
  `kd_wilayah` varchar(255) NOT NULL,
  `type_user` varchar(20) NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'default.jpeg',
  `tgl_daftar` datetime NOT NULL,
  `tgl_login` datetime NOT NULL,
  `tahun` year(4) NOT NULL,
  `kontak_person` varchar(255) DEFAULT NULL,
  `font_size` decimal(5,2) DEFAULT NULL,
  `warna_tbl` varchar(150) DEFAULT NULL,
  `scrolling_table` varchar(50) DEFAULT NULL,
  `disable_login` tinyint(1) NOT NULL DEFAULT 1,
  `disable_anggaran` tinyint(1) NOT NULL DEFAULT 1,
  `disable_kontrak` tinyint(1) NOT NULL DEFAULT 1,
  `disable_realisasi` tinyint(1) NOT NULL DEFAULT 1,
  `disable_chat` tinyint(1) NOT NULL DEFAULT 1,
  `ket` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `user_sesendok_biila`
--

INSERT INTO `user_sesendok_biila` (`id`, `username`, `email`, `nama`, `password`, `kd_organisasi`, `nama_org`, `kd_wilayah`, `type_user`, `photo`, `tgl_daftar`, `tgl_login`, `tahun`, `kontak_person`, `font_size`, `warna_tbl`, `scrolling_table`, `disable_login`, `disable_anggaran`, `disable_kontrak`, `disable_realisasi`, `disable_chat`, `ket`) VALUES
(1, 'alwi_mansyur', 'alwi@gmail.com', 'Alwi Mansyur', '$2y$10$phmt521EHu3PEkilYD/TJ.i1U.ZcMjAHAJt4y88r3O0tfbgs8HQl6', '1.03.0.00.0.00.01.0000', 'Alwi Mansyur', '76.01', 'user', 'images/avatar/default.jpeg', '2018-06-04 21:57:05', '2024-01-26 14:17:26', '2024', 'pasangkayu ji', 90.00, 'non', 'short', 0, 0, 0, 0, 1, 'apa yang dapat saya berikan'),
(2, 'nabiila', 'nabiila@gmail.com', 'nabiila', '$2y$10$Zxp6h5J9v8MiUtUZpDvNKe81qhVaN9gBTVusn/ov9mVwti/du1q1G', '1.03.0.00.0.00.01.0000', 'PT. Angin Ribat Skali dan satgat mengesankan sekali', '76.01', 'admin', 'images/avatar/bbf4f78067dad81bec03965da604932e9e18f570_2.jpg', '2018-06-09 15:54:29', '2024-02-20 03:24:56', '2024', '08128888', 80.00, 'non', 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk Pasangkayu'),
(3, 'inayah', 'inayah@gmail.com', 'inayah', '$2y$10$J1RLk2kaKqYeuFs2q76vxuoPYTi3cA8dCjRISJlnwlsi3sdHoAKg.', '', 'PT. Angin Ribat Skali dan satgat mengesankan sekali', '', 'user', 'images/avatar/default.jpeg', '2018-06-22 22:04:17', '2020-03-08 02:30:41', '2024', '', 80.00, NULL, 'short', 0, 0, 0, 0, 1, 'dimana mana hatiku senang oke'),
(4, 'Arlinda', 'arlinda@gmail.com', 'Arlinda Achmad', '$2y$10$V.f/.ElwettBd3jyJfMR5epHT0s8NVqaU/mL8ZIqIJo.HBb.6x/Qi', '', 'Prof', '', 'admin', 'images/avatar/default.jpeg', '2018-07-10 14:27:06', '2018-10-21 12:23:09', '2024', '', 80.00, NULL, 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk Pasangkayu.'),
(5, 'administrator', 'alwi.mansyur@gmail.com', 'administrator', '$2y$10$cFR8KdFGXUFBZ5C5payBEOb3aPEXtvYwAKO6Gc6Zdqyjo7WRuDY8.', '', 'administrator AHSP', '', 'user', 'images/avatar/c14719a7f71e46badf2cf93ae373ae9797281782_9.png', '2023-02-09 23:41:34', '2023-02-23 00:05:26', '2024', '08128886665', 80.00, 'non', 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk mu');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah_neo`
--

CREATE TABLE `wilayah_neo` (
  `id` int(11) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `status` varchar(255) NOT NULL,
  `jml_kec` int(11) DEFAULT NULL,
  `jml_kel` int(11) DEFAULT NULL,
  `jml_desa` int(11) DEFAULT NULL,
  `luas` decimal(20,12) DEFAULT NULL,
  `penduduk` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `akun_neo`
--
ALTER TABLE `akun_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `asb_neo`
--
ALTER TABLE `asb_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `aset_neo`
--
ALTER TABLE `aset_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bidang_urusan_neo`
--
ALTER TABLE `bidang_urusan_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dpa_neo`
--
ALTER TABLE `dpa_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dppa_neo`
--
ALTER TABLE `dppa_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `hspk_neo`
--
ALTER TABLE `hspk_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `kd_wilayah_neo`
--
ALTER TABLE `kd_wilayah_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `mapping_aset_akun`
--
ALTER TABLE `mapping_aset_akun`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `organisasi_neo`
--
ALTER TABLE `organisasi_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pengaturan_neo`
--
ALTER TABLE `pengaturan_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peraturan_neo`
--
ALTER TABLE `peraturan_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `program_neo`
--
ALTER TABLE `program_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rekanan_neo`
--
ALTER TABLE `rekanan_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `renja_neo`
--
ALTER TABLE `renja_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `renja_p_neo`
--
ALTER TABLE `renja_p_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `renstra_skpd_neo`
--
ALTER TABLE `renstra_skpd_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `satuan_neo`
--
ALTER TABLE `satuan_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sbu_neo`
--
ALTER TABLE `sbu_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ssh_neo`
--
ALTER TABLE `ssh_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sub_kegiatan_neo`
--
ALTER TABLE `sub_kegiatan_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sub_keg_dpa_neo`
--
ALTER TABLE `sub_keg_dpa_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sub_keg_renja_neo`
--
ALTER TABLE `sub_keg_renja_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `sumber_dana_neo`
--
ALTER TABLE `sumber_dana_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `tujuan_sasaran_renstra_neo`
--
ALTER TABLE `tujuan_sasaran_renstra_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_sesendok_biila`
--
ALTER TABLE `user_sesendok_biila`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeks untuk tabel `wilayah_neo`
--
ALTER TABLE `wilayah_neo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `akun_neo`
--
ALTER TABLE `akun_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `asb_neo`
--
ALTER TABLE `asb_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `aset_neo`
--
ALTER TABLE `aset_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `bidang_urusan_neo`
--
ALTER TABLE `bidang_urusan_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dpa_neo`
--
ALTER TABLE `dpa_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dppa_neo`
--
ALTER TABLE `dppa_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `hspk_neo`
--
ALTER TABLE `hspk_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `kd_wilayah_neo`
--
ALTER TABLE `kd_wilayah_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `mapping_aset_akun`
--
ALTER TABLE `mapping_aset_akun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `organisasi_neo`
--
ALTER TABLE `organisasi_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `pengaturan_neo`
--
ALTER TABLE `pengaturan_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peraturan_neo`
--
ALTER TABLE `peraturan_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `program_neo`
--
ALTER TABLE `program_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rekanan_neo`
--
ALTER TABLE `rekanan_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `renja_neo`
--
ALTER TABLE `renja_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `renja_p_neo`
--
ALTER TABLE `renja_p_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `renstra_skpd_neo`
--
ALTER TABLE `renstra_skpd_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `satuan_neo`
--
ALTER TABLE `satuan_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sbu_neo`
--
ALTER TABLE `sbu_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ssh_neo`
--
ALTER TABLE `ssh_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sub_kegiatan_neo`
--
ALTER TABLE `sub_kegiatan_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sub_keg_dpa_neo`
--
ALTER TABLE `sub_keg_dpa_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sub_keg_renja_neo`
--
ALTER TABLE `sub_keg_renja_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `sumber_dana_neo`
--
ALTER TABLE `sumber_dana_neo`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tujuan_sasaran_renstra_neo`
--
ALTER TABLE `tujuan_sasaran_renstra_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user_sesendok_biila`
--
ALTER TABLE `user_sesendok_biila`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `wilayah_neo`
--
ALTER TABLE `wilayah_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
