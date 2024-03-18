-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 18 Mar 2024 pada 15.57
-- Versi server: 11.3.2-MariaDB
-- Versi PHP: 8.3.4

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
CREATE DATABASE IF NOT EXISTS `sesendokneo_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sesendokneo_db`;

-- --------------------------------------------------------

--
-- Struktur dari tabel `akun_neo`
--

DROP TABLE IF EXISTS `akun_neo`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `asb_neo`
--

DROP TABLE IF EXISTS `asb_neo`;
CREATE TABLE `asb_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_barang` varchar(400) NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `kd_akun` text NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `aset_neo`
--

DROP TABLE IF EXISTS `aset_neo`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita_neo`
--

DROP TABLE IF EXISTS `berita_neo`;
CREATE TABLE `berita_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `judul` varchar(400) NOT NULL,
  `kelompok` varchar(50) NOT NULL,
  `uraian_html` text NOT NULL,
  `uraian_singkat` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `tgl_insert` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(50) NOT NULL,
  `username_update` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `urutan` int(11) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bidang_urusan_neo`
--

DROP TABLE IF EXISTS `bidang_urusan_neo`;
CREATE TABLE `bidang_urusan_neo` (
  `id` int(11) NOT NULL,
  `kode` varchar(4) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `disable` int(11) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_paket_neo`
--

DROP TABLE IF EXISTS `daftar_paket_neo`;
CREATE TABLE `daftar_paket_neo` (
  `id` int(11) NOT NULL,
  `kd_rup` varchar(25) DEFAULT NULL,
  `kd_paket` varchar(25) DEFAULT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(50) NOT NULL,
  `tahun` year(4) NOT NULL,
  `uraian` text NOT NULL,
  `id_uraian` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`id_uraian`)),
  `kd_sub_keg` text NOT NULL,
  `volume` int(11) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(36,12) DEFAULT NULL,
  `jumlah` decimal(36,12) DEFAULT NULL,
  `pagu` decimal(36,12) NOT NULL,
  `metode_pengadaan` varchar(255) DEFAULT NULL,
  `metode_pemilihan` varchar(255) DEFAULT NULL,
  `pengadaan_penyedia` varchar(255) DEFAULT NULL,
  `jns_kontrak` varchar(255) DEFAULT NULL,
  `renc_output` varchar(255) DEFAULT NULL,
  `output` varchar(255) DEFAULT NULL,
  `id_rekanan` int(11) NOT NULL,
  `nama_rekanan` varchar(255) DEFAULT NULL,
  `nama_ppk` varchar(255) DEFAULT NULL,
  `nip_ppk` varchar(25) DEFAULT NULL,
  `nama_pptk` varchar(255) DEFAULT NULL,
  `waktu_pelaksanaan` int(11) DEFAULT NULL,
  `waktu_pemeliharaan` int(11) DEFAULT NULL,
  `nip_pptk` varchar(25) DEFAULT NULL,
  `tgl_kontrak` date DEFAULT NULL,
  `no_kontrak` varchar(255) DEFAULT NULL,
  `tgl_persiapan_kont` datetime DEFAULT NULL,
  `no_persiapan_kont` varchar(255) DEFAULT NULL,
  `tgl_spmk` datetime DEFAULT NULL,
  `no_spmk` varchar(255) DEFAULT NULL,
  `addendum` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`addendum`)),
  `tgl_undangan` datetime DEFAULT NULL,
  `no_undangan` varchar(255) DEFAULT NULL,
  `tgl_penawaran` datetime DEFAULT NULL,
  `no_penawaran` varchar(255) DEFAULT NULL,
  `tgl_nego` datetime DEFAULT NULL,
  `no_nego` varchar(255) DEFAULT NULL,
  `tgl_sppbj` date DEFAULT NULL,
  `no_sppbj` varchar(255) DEFAULT NULL,
  `tgl_pho` datetime DEFAULT NULL,
  `no_pho` varchar(255) DEFAULT NULL,
  `tgl_fho` datetime DEFAULT NULL,
  `no_fho` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(400) DEFAULT NULL,
  `file_kontrak` varchar(255) DEFAULT NULL,
  `file_addendum` varchar(255) DEFAULT NULL,
  `file_pho` varchar(255) DEFAULT NULL,
  `file_fho` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `file_laporan` varchar(255) DEFAULT NULL,
  `file_dokumentasi0` varchar(255) DEFAULT NULL,
  `file_dokumentasi50` varchar(255) DEFAULT NULL,
  `file_dokumentasi100` varchar(255) DEFAULT NULL,
  `disable` tinyint(1) DEFAULT 0,
  `setujui` tinyint(1) DEFAULT 0,
  `kunci` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_realisasi_neo`
--

DROP TABLE IF EXISTS `daftar_realisasi_neo`;
CREATE TABLE `daftar_realisasi_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` int(11) NOT NULL,
  `kd_opd` int(11) NOT NULL,
  `tahun` int(11) NOT NULL,
  `id_daftar_paket` int(11) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `realisasi_vol` decimal(36,12) DEFAULT NULL,
  `realisasi_jumlah` decimal(36,12) NOT NULL,
  `id_uraian` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`id_uraian`)),
  `tanggal_bukti` date NOT NULL,
  `nomor_bukti` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `tgl_input` datetime NOT NULL,
  `username_update` varchar(255) NOT NULL,
  `tgl_update` datetime NOT NULL,
  `file` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_uraian_paket`
--

DROP TABLE IF EXISTS `daftar_uraian_paket`;
CREATE TABLE `daftar_uraian_paket` (
  `id` int(11) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `id_dok_anggaran` int(11) NOT NULL,
  `dok` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(50) NOT NULL,
  `kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(50) NOT NULL,
  `kel_rek` varchar(50) NOT NULL,
  `jumlah_pagu` decimal(36,12) NOT NULL,
  `jumlah_kontrak` decimal(36,12) NOT NULL,
  `vol_kontrak` decimal(36,12) NOT NULL,
  `sat_kontrak` varchar(50) NOT NULL,
  `realisasi_vol` decimal(36,12) DEFAULT NULL,
  `realisasi_jumlah` decimal(36,12) DEFAULT NULL,
  `keterangan` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `db_asn_pemda_neo`
--

DROP TABLE IF EXISTS `db_asn_pemda_neo`;
CREATE TABLE `db_asn_pemda_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(50) NOT NULL,
  `nama` varchar(400) NOT NULL,
  `gelar_depan` varchar(50) DEFAULT NULL,
  `gelar` varchar(60) DEFAULT NULL,
  `nip` varchar(18) NOT NULL,
  `t4_lahir` varchar(100) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `file_akta_lahir` varchar(255) DEFAULT NULL,
  `golongan` varchar(1) NOT NULL,
  `ruang` varchar(1) NOT NULL,
  `agama` varchar(100) DEFAULT NULL,
  `kelamin` varchar(100) DEFAULT NULL,
  `jenis_kepeg` varchar(100) DEFAULT NULL,
  `status_kepeg` varchar(100) DEFAULT NULL,
  `jabatan` varchar(50) DEFAULT NULL,
  `id_jabatan` int(11) DEFAULT NULL,
  `no_ktp` varchar(100) DEFAULT NULL,
  `file_ktp` varchar(255) DEFAULT NULL,
  `no_kk` varchar(255) DEFAULT NULL,
  `tgl_kk` date DEFAULT NULL,
  `file_kk` varchar(255) DEFAULT NULL,
  `npwp` varchar(21) DEFAULT NULL,
  `file_npwp` varchar(255) DEFAULT NULL,
  `alamat` varchar(300) DEFAULT NULL,
  `kontak_person` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `no_buku_nikah` varchar(225) DEFAULT NULL,
  `tgl_nikah` date DEFAULT NULL,
  `file_buku_nikah` varchar(255) DEFAULT NULL,
  `nama_anak` varchar(400) DEFAULT NULL,
  `nik_anak` varchar(400) DEFAULT NULL,
  `nama_ayah` varchar(100) DEFAULT NULL,
  `nama_ibu` varchar(100) DEFAULT NULL,
  `nama_pasangan` varchar(100) DEFAULT NULL,
  `no_karpeg` varchar(100) DEFAULT NULL,
  `tgl_karpeg` date DEFAULT NULL,
  `file_karpeg` varchar(255) DEFAULT NULL,
  `no_taspen` varchar(100) DEFAULT NULL,
  `tgl_taspen` date DEFAULT NULL,
  `no_karsi_karsu` varchar(100) DEFAULT NULL,
  `tgl_karsi_karsu` date DEFAULT NULL,
  `file_karsi_karsu` varchar(255) DEFAULT NULL,
  `nmr_sk_terakhir` varchar(150) DEFAULT NULL,
  `tgl_tmt_akhir` date DEFAULT NULL,
  `pj_sk_terakhir` varchar(150) DEFAULT NULL,
  `nmr_sk_cpns` varchar(150) DEFAULT NULL,
  `tgl_sk_cpns` date DEFAULT NULL,
  `pj_sk_cpns` varchar(150) DEFAULT NULL,
  `nmr_sk_pns` varchar(150) DEFAULT NULL,
  `tgl_sk_pns` date DEFAULT NULL,
  `pj_sk_pns` varchar(150) DEFAULT NULL,
  `sk_pangkat_terakhir` varchar(100) DEFAULT NULL,
  `tgl_sk_terakhir` date DEFAULT NULL,
  `pend_sekolah_sd` varchar(225) DEFAULT NULL,
  `pend_ijasah_sd` varchar(225) DEFAULT NULL,
  `pend_tgl_tmt_sd` date DEFAULT NULL,
  `pend_t4_sd` varchar(225) DEFAULT NULL,
  `pend_file_sd` varchar(225) DEFAULT NULL,
  `pend_sekolah_smp` varchar(225) DEFAULT NULL,
  `pend_ijasah_smp` varchar(225) DEFAULT NULL,
  `pend_tgl_tmt_smp` date DEFAULT NULL,
  `pend_t4_smp` varchar(225) DEFAULT NULL,
  `pend_file_smp` varchar(225) DEFAULT NULL,
  `pend_sekolah_smu` varchar(255) DEFAULT NULL,
  `pend_ijasah_smu` varchar(255) DEFAULT NULL,
  `pend_tgl_tmt_smu` date DEFAULT NULL,
  `pend_t4_smu` varchar(255) DEFAULT NULL,
  `pend_file_smu` varchar(80) DEFAULT NULL,
  `pend_sekolah_s1` varchar(255) DEFAULT NULL,
  `pend_ijasah_s1` varchar(255) DEFAULT NULL,
  `pend_tgl_tmt_s1` date DEFAULT NULL,
  `pend_t4_s1` varchar(255) DEFAULT NULL,
  `pend_file_s1` varchar(80) DEFAULT NULL,
  `pend_sekolah_s2` varchar(255) DEFAULT NULL,
  `pend_ijasah_s2` varchar(255) DEFAULT NULL,
  `pend_tgl_tmt_s2` date DEFAULT NULL,
  `pend_t4_s2` varchar(255) DEFAULT NULL,
  `pend_file_s2` varchar(80) DEFAULT NULL,
  `pend_sekolah_s3` varchar(255) DEFAULT NULL,
  `pend_ijasah_s3` varchar(255) DEFAULT NULL,
  `pend_tgl_tmt_s3` date DEFAULT NULL,
  `pend_t4_s3` varchar(255) DEFAULT NULL,
  `pend_file_s3` varchar(80) DEFAULT NULL,
  `pend_sekolah_akhir` varchar(255) DEFAULT NULL,
  `pend_ijasah_akhir` varchar(255) DEFAULT NULL,
  `pend_tgl_tmt_akhir` date DEFAULT NULL,
  `pend_t4_akhir` varchar(255) DEFAULT NULL,
  `pend_file_akhir` varchar(80) DEFAULT NULL,
  `file_photo` varchar(255) DEFAULT NULL,
  `gapok` decimal(20,2) DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT NULL,
  `no_urut` int(11) DEFAULT NULL,
  `unit_kerja` varchar(255) DEFAULT NULL,
  `no_rekening` varchar(150) DEFAULT NULL,
  `nama_bank` varchar(150) DEFAULT NULL,
  `urutan` int(11) DEFAULT NULL,
  `kelompok` int(11) DEFAULT NULL,
  `suka` int(11) DEFAULT NULL,
  `follow` int(11) DEFAULT NULL,
  `keterangan` varchar(400) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dpa_neo`
--

DROP TABLE IF EXISTS `dpa_neo`;
CREATE TABLE `dpa_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(50) NOT NULL,
  `kel_rek` varchar(50) NOT NULL,
  `objek_belanja` varchar(255) NOT NULL,
  `uraian` text NOT NULL,
  `jenis_kelompok` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `jenis_standar_harga` varchar(6) NOT NULL,
  `id_standar_harga` int(11) DEFAULT NULL,
  `komponen` varchar(400) DEFAULT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `tkdn` decimal(36,12) DEFAULT NULL,
  `pajak` tinyint(1) DEFAULT NULL,
  `harga_satuan` decimal(36,12) NOT NULL,
  `vol_1` decimal(36,12) NOT NULL,
  `vol_2` decimal(36,12) DEFAULT NULL,
  `vol_3` decimal(36,12) DEFAULT NULL,
  `vol_4` decimal(36,12) DEFAULT NULL,
  `vol_5` decimal(36,12) DEFAULT NULL,
  `sat_1` varchar(50) NOT NULL,
  `sat_2` varchar(50) DEFAULT NULL,
  `sat_3` varchar(50) DEFAULT NULL,
  `sat_4` varchar(50) DEFAULT NULL,
  `sat_5` varchar(50) DEFAULT NULL,
  `volume` decimal(36,12) DEFAULT NULL,
  `jumlah` decimal(36,12) NOT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `keterangan` varchar(400) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username_input` varchar(255) NOT NULL,
  `username_update` varchar(255) NOT NULL,
  `kunci` tinyint(1) DEFAULT 0,
  `setujui` tinyint(1) DEFAULT 0,
  `id_renja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `dppa_neo`
--

DROP TABLE IF EXISTS `dppa_neo`;
CREATE TABLE `dppa_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(50) NOT NULL,
  `kel_rek` varchar(50) NOT NULL,
  `objek_belanja` varchar(255) NOT NULL,
  `uraian` text NOT NULL,
  `jenis_kelompok` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `jenis_standar_harga` varchar(6) NOT NULL,
  `id_standar_harga` int(11) DEFAULT NULL,
  `komponen` varchar(400) DEFAULT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `tkdn` decimal(36,12) DEFAULT NULL,
  `pajak` tinyint(1) DEFAULT NULL,
  `harga_satuan` decimal(36,12) NOT NULL,
  `vol_1` decimal(36,12) NOT NULL,
  `vol_2` decimal(36,12) DEFAULT NULL,
  `vol_3` decimal(36,12) DEFAULT NULL,
  `vol_4` decimal(36,12) DEFAULT NULL,
  `vol_5` decimal(36,12) DEFAULT NULL,
  `sat_1` varchar(50) NOT NULL,
  `sat_2` varchar(50) DEFAULT NULL,
  `sat_3` varchar(50) DEFAULT NULL,
  `sat_4` varchar(50) DEFAULT NULL,
  `sat_5` varchar(50) DEFAULT NULL,
  `volume` decimal(36,12) DEFAULT NULL,
  `jumlah` decimal(36,12) NOT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `vol_1_p` decimal(36,12) DEFAULT NULL,
  `vol_2_p` decimal(36,12) DEFAULT NULL,
  `vol_3_p` decimal(36,12) DEFAULT NULL,
  `vol_4_p` decimal(36,12) DEFAULT NULL,
  `vol_5_p` decimal(36,12) DEFAULT NULL,
  `sat_1_p` varchar(50) DEFAULT NULL,
  `sat_2_p` varchar(50) DEFAULT NULL,
  `sat_3_p` varchar(50) DEFAULT NULL,
  `sat_4_p` varchar(50) DEFAULT NULL,
  `sat_5_p` varchar(50) DEFAULT NULL,
  `volume_p` decimal(36,12) DEFAULT NULL,
  `jumlah_p` decimal(36,12) DEFAULT NULL,
  `sumber_dana_p` varchar(255) DEFAULT NULL,
  `keterangan` varchar(400) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username_input` varchar(255) NOT NULL,
  `username_update` varchar(255) NOT NULL,
  `kunci` tinyint(1) DEFAULT 0,
  `setujui` tinyint(1) DEFAULT 0,
  `id_dpa` int(11) DEFAULT NULL,
  `id_renja_p` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hspk_neo`
--

DROP TABLE IF EXISTS `hspk_neo`;
CREATE TABLE `hspk_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_barang` text NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(18,6) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `kd_akun` text NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kd_wilayah_neo`
--

DROP TABLE IF EXISTS `kd_wilayah_neo`;
CREATE TABLE `kd_wilayah_neo` (
  `id` int(11) NOT NULL,
  `kode` int(11) NOT NULL,
  `uraian` int(11) NOT NULL,
  `prioritas_pembangunan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`prioritas_pembangunan`)),
  `disable` int(11) DEFAULT NULL,
  `peraturan` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan_neo`
--

DROP TABLE IF EXISTS `kegiatan_neo`;
CREATE TABLE `kegiatan_neo` (
  `id` int(8) NOT NULL,
  `kode` varchar(8) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mapping_aset_akun`
--

DROP TABLE IF EXISTS `mapping_aset_akun`;
CREATE TABLE `mapping_aset_akun` (
  `id` int(11) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_aset` varchar(400) NOT NULL,
  `kd_akun` varchar(25) DEFAULT NULL,
  `uraian_akun` varchar(400) DEFAULT NULL,
  `kelompok` varchar(5) DEFAULT NULL,
  `disable` int(11) NOT NULL DEFAULT 0,
  `aksi` tinyint(1) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `organisasi_neo`
--

DROP TABLE IF EXISTS `organisasi_neo`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengaturan_neo`
--

DROP TABLE IF EXISTS `pengaturan_neo`;
CREATE TABLE `pengaturan_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `tahun` year(4) NOT NULL,
  `tahun_renstra` year(4) NOT NULL,
  `aturan_anggaran` varchar(255) NOT NULL,
  `aturan_organisasi` int(11) DEFAULT NULL,
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
  `tanggal` datetime NOT NULL,
  `username` varchar(266) NOT NULL,
  `kunci` tinyint(1) DEFAULT 0,
  `setujui` tinyint(1) DEFAULT 0,
  `kunci_renstra` tinyint(1) DEFAULT NULL,
  `kunci_renja` tinyint(1) DEFAULT NULL,
  `kunci_dpa` tinyint(1) DEFAULT NULL,
  `kunci_renja_p` tinyint(1) DEFAULT NULL,
  `kunci_dppa` tinyint(1) DEFAULT NULL,
  `kunci_paket` tinyint(1) DEFAULT NULL,
  `kunci_realisasi` tinyint(1) DEFAULT NULL,
  `setujui_renstra` tinyint(1) DEFAULT NULL,
  `setujui_renja` tinyint(1) DEFAULT NULL,
  `setujui_dpa` tinyint(1) DEFAULT NULL,
  `setujui_renja_p` tinyint(1) DEFAULT NULL,
  `setujui_dppa` tinyint(1) DEFAULT NULL,
  `setujui_paket` tinyint(1) DEFAULT NULL,
  `setujui_realisasi` tinyint(1) DEFAULT NULL,
  `id_opd_tampilkan` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peraturan_neo`
--

DROP TABLE IF EXISTS `peraturan_neo`;
CREATE TABLE `peraturan_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `kode` varchar(255) NOT NULL,
  `type_dok` varchar(255) NOT NULL,
  `judul` varchar(400) NOT NULL,
  `judul_singkat` varchar(400) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rab_paket_neo`
--

DROP TABLE IF EXISTS `rab_paket_neo`;
CREATE TABLE `rab_paket_neo` (
  `id` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(50) NOT NULL,
  `id_renja_p` int(11) NOT NULL,
  `id_dpa` int(11) NOT NULL,
  `id_dppa` int(11) NOT NULL,
  `nomor` varchar(255) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `satuan` varchar(50) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `vol_hps` decimal(36,12) DEFAULT NULL,
  `vol_penawaran` decimal(36,12) DEFAULT NULL,
  `vol_negoisasi` decimal(36,12) DEFAULT NULL,
  `harga_sat_hps` decimal(36,12) DEFAULT NULL,
  `harga_sat_penawaran` decimal(36,12) DEFAULT NULL,
  `harga_sat_negoisasi` decimal(36,12) DEFAULT NULL,
  `pajak` decimal(8,2) DEFAULT NULL,
  `jumlah_hps` decimal(36,12) DEFAULT NULL,
  `jumlah_penawaran` decimal(36,12) DEFAULT NULL,
  `jumlah_negoisasi` decimal(36,12) DEFAULT NULL,
  `KBBI` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `tanggal` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `realisasi_neo`
--

DROP TABLE IF EXISTS `realisasi_neo`;
CREATE TABLE `realisasi_neo` (
  `id` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_wilayah` varchar(100) NOT NULL,
  `kd_opd` varchar(100) NOT NULL,
  `id_paket` int(11) NOT NULL,
  `ket_paket` text NOT NULL,
  `id_uraian_paket` int(11) NOT NULL,
  `ket_uraian_paket` varchar(400) NOT NULL,
  `vol` decimal(36,12) NOT NULL,
  `jumlah` decimal(36,12) NOT NULL,
  `tanggal` date NOT NULL,
  `file` varchar(400) NOT NULL,
  `username` varchar(255) NOT NULL,
  `user_update` varchar(255) NOT NULL,
  `tgl_insert` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `keterangan` varchar(400) NOT NULL,
  `disable` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekanan_neo`
--

DROP TABLE IF EXISTS `rekanan_neo`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `renja_neo`
--

DROP TABLE IF EXISTS `renja_neo`;
CREATE TABLE `renja_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(50) NOT NULL,
  `kel_rek` varchar(50) NOT NULL,
  `objek_belanja` varchar(255) NOT NULL,
  `uraian` text NOT NULL,
  `jenis_kelompok` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `jenis_standar_harga` varchar(6) NOT NULL,
  `id_standar_harga` int(11) DEFAULT NULL,
  `komponen` varchar(400) DEFAULT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `tkdn` decimal(36,12) DEFAULT NULL,
  `pajak` tinyint(1) DEFAULT NULL,
  `harga_satuan` decimal(36,12) NOT NULL,
  `vol_1` decimal(36,12) NOT NULL,
  `vol_2` decimal(36,12) DEFAULT NULL,
  `vol_3` decimal(36,12) DEFAULT NULL,
  `vol_4` decimal(36,12) DEFAULT NULL,
  `vol_5` decimal(36,12) DEFAULT NULL,
  `sat_1` varchar(50) NOT NULL,
  `sat_2` varchar(50) DEFAULT NULL,
  `sat_3` varchar(50) DEFAULT NULL,
  `sat_4` varchar(50) DEFAULT NULL,
  `sat_5` varchar(50) DEFAULT NULL,
  `volume` decimal(36,12) DEFAULT NULL,
  `jumlah` decimal(36,12) NOT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `keterangan` varchar(400) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username_input` varchar(255) NOT NULL,
  `username_update` varchar(255) NOT NULL,
  `kunci` tinyint(1) DEFAULT 0,
  `setujui` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `renja_p_neo`
--

DROP TABLE IF EXISTS `renja_p_neo`;
CREATE TABLE `renja_p_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(50) NOT NULL,
  `kd_akun` varchar(50) NOT NULL,
  `kel_rek` varchar(50) NOT NULL,
  `objek_belanja` varchar(255) NOT NULL,
  `uraian` text NOT NULL,
  `jenis_kelompok` varchar(255) NOT NULL,
  `kelompok` varchar(255) NOT NULL,
  `jenis_standar_harga` varchar(6) NOT NULL,
  `id_standar_harga` int(11) DEFAULT NULL,
  `komponen` varchar(400) DEFAULT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `tkdn` decimal(36,12) DEFAULT NULL,
  `pajak` tinyint(1) DEFAULT NULL,
  `harga_satuan` decimal(36,12) NOT NULL,
  `vol_1` decimal(36,12) NOT NULL,
  `vol_2` decimal(36,12) DEFAULT NULL,
  `vol_3` decimal(36,12) DEFAULT NULL,
  `vol_4` decimal(36,12) DEFAULT NULL,
  `vol_5` decimal(36,12) DEFAULT NULL,
  `sat_1` varchar(50) NOT NULL,
  `sat_2` varchar(50) DEFAULT NULL,
  `sat_3` varchar(50) DEFAULT NULL,
  `sat_4` varchar(50) DEFAULT NULL,
  `sat_5` varchar(50) DEFAULT NULL,
  `volume` decimal(36,12) DEFAULT NULL,
  `jumlah` decimal(36,12) NOT NULL,
  `sumber_dana` varchar(255) DEFAULT NULL,
  `vol_1_p` decimal(36,12) DEFAULT NULL,
  `vol_2_p` decimal(36,12) DEFAULT NULL,
  `vol_3_p` decimal(36,12) DEFAULT NULL,
  `vol_4_p` decimal(36,12) DEFAULT NULL,
  `vol_5_p` decimal(36,12) DEFAULT NULL,
  `sat_1_p` varchar(50) DEFAULT NULL,
  `sat_2_p` varchar(50) DEFAULT NULL,
  `sat_3_p` varchar(50) DEFAULT NULL,
  `sat_4_p` varchar(50) DEFAULT NULL,
  `sat_5_p` varchar(50) DEFAULT NULL,
  `volume_p` decimal(36,12) DEFAULT NULL,
  `jumlah_p` decimal(36,12) DEFAULT NULL,
  `sumber_dana_p` varchar(255) DEFAULT NULL,
  `keterangan` varchar(400) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username_input` varchar(255) NOT NULL,
  `username_update` varchar(255) NOT NULL,
  `kunci` tinyint(1) DEFAULT 0,
  `setujui` tinyint(1) DEFAULT 0,
  `id_dpa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `renstra_skpd_neo`
--

DROP TABLE IF EXISTS `renstra_skpd_neo`;
CREATE TABLE `renstra_skpd_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(15) NOT NULL,
  `kd_opd` varchar(255) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(255) NOT NULL,
  `kel_rek` varchar(50) NOT NULL,
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
  `kondisi_akhir` decimal(32,12) DEFAULT NULL,
  `jumlah` decimal(40,12) NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `setujui` tinyint(1) DEFAULT 0,
  `kunci` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `satuan_neo`
--

DROP TABLE IF EXISTS `satuan_neo`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sbu_neo`
--

DROP TABLE IF EXISTS `sbu_neo`;
CREATE TABLE `sbu_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_barang` text NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(18,6) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `kd_akun` text NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ssh_neo`
--

DROP TABLE IF EXISTS `ssh_neo`;
CREATE TABLE `ssh_neo` (
  `id` int(8) NOT NULL,
  `kd_wilayah` varchar(25) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_aset` varchar(25) NOT NULL,
  `uraian_barang` text NOT NULL,
  `spesifikasi` text DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(18,6) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `kd_akun` text NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `username` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kegiatan_neo`
--

DROP TABLE IF EXISTS `sub_kegiatan_neo`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_keg_dpa_neo`
--

DROP TABLE IF EXISTS `sub_keg_dpa_neo`;
CREATE TABLE `sub_keg_dpa_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(22) NOT NULL,
  `kel_rek` varchar(50) NOT NULL,
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
  `sumber_dana` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
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
  `kelompok_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{}',
  `keterangan_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{}' CHECK (json_valid(`keterangan_json`)),
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `setujui` tinyint(1) DEFAULT 0,
  `kunci` tinyint(1) DEFAULT 0,
  `setujui_p` tinyint(1) DEFAULT 0,
  `kunci_p` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_keg_renja_neo`
--

DROP TABLE IF EXISTS `sub_keg_renja_neo`;
CREATE TABLE `sub_keg_renja_neo` (
  `id` int(11) NOT NULL,
  `kd_wilayah` varchar(50) NOT NULL,
  `kd_opd` varchar(22) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kd_sub_keg` varchar(22) NOT NULL,
  `kel_rek` varchar(50) NOT NULL,
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
  `sumber_dana` text CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
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
  `kelompok_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{}',
  `keterangan_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{}' CHECK (json_valid(`keterangan_json`)),
  `tanggal` datetime NOT NULL,
  `tgl_update` datetime NOT NULL,
  `username` varchar(255) NOT NULL,
  `setujui` tinyint(1) DEFAULT 0,
  `kunci` tinyint(1) DEFAULT 0,
  `setujui_p` tinyint(1) DEFAULT 0,
  `kunci_p` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sumber_dana_neo`
--

DROP TABLE IF EXISTS `sumber_dana_neo`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tujuan_sasaran_renstra_neo`
--

DROP TABLE IF EXISTS `tujuan_sasaran_renstra_neo`;
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
  `username` varchar(255) NOT NULL,
  `setujui` tinyint(1) DEFAULT 0,
  `kunci` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_sesendok_biila`
--

DROP TABLE IF EXISTS `user_sesendok_biila`;
CREATE TABLE `user_sesendok_biila` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `nip` varchar(18) DEFAULT NULL,
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
  `ket` varchar(250) DEFAULT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Dumping data untuk tabel `user_sesendok_biila`
--

INSERT INTO `user_sesendok_biila` (`id`, `username`, `email`, `nama`, `nip`, `password`, `kd_organisasi`, `nama_org`, `kd_wilayah`, `type_user`, `photo`, `tgl_daftar`, `tgl_login`, `tahun`, `kontak_person`, `font_size`, `warna_tbl`, `scrolling_table`, `disable_login`, `disable_anggaran`, `disable_kontrak`, `disable_realisasi`, `disable_chat`, `ket`, `disable`) VALUES
(1, 'alwi_mansyur', 'alwi@gmail.com', 'Alwi Mansyur', NULL, '$2y$10$wkIJCe8dk3YaLaaIScBOBOAY4M8cLEyDsFm66Xhwo9U3p/wcik9Bi', '1.03.0.00.0.00.01.0000', 'DINAS PEKERJAAN UMUM DAN PENATAAN RUANG', '76.01', 'user', 'images/avatar/default.jpeg', '2018-06-04 21:57:05', '2024-03-18 20:35:12', '2024', 'pasangkayu ji', 90.00, 'non', 'short', 0, 0, 0, 0, 1, 'apa yang dapat saya berikan', 0),
(2, 'nabiila', 'nabiila@gmail.com', 'nabiila', NULL, '$2y$10$wkIJCe8dk3YaLaaIScBOBOAY4M8cLEyDsFm66Xhwo9U3p/wcik9Bi', '1.03.0.00.0.00.01.0000', 'DINAS PEKERJAAN UMUM DAN PENATAAN RUANG', '76.01', 'admin', 'images/avatar/bbf4f78067dad81bec03965da604932e9e18f570_2.jpg', '2018-06-09 15:54:29', '2024-03-18 20:35:29', '2024', '08128888', 80.00, 'non', 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk Pasangkayu', 0),
(3, 'inayah', 'inayah@gmail.com', 'inayah', NULL, '$2y$10$wkIJCe8dk3YaLaaIScBOBOAY4M8cLEyDsFm66Xhwo9U3p/wcik9Bi', '1.03.0.00.0.00.01.0000', 'DINAS PEKERJAAN UMUM DAN PENATAAN RUANG', '', 'user', 'images/avatar/default.jpeg', '2018-06-22 22:04:17', '2020-03-08 02:30:41', '2024', '', 80.00, NULL, 'short', 0, 0, 0, 0, 1, 'dimana mana hatiku senang oke', 0),
(4, 'Arlinda', 'arlinda@gmail.com', 'Arlinda Achmad', NULL, '$2y$10$wkIJCe8dk3YaLaaIScBOBOAY4M8cLEyDsFm66Xhwo9U3p/wcik9Bi', '', 'Prof', '', 'admin', 'images/avatar/default.jpeg', '2018-07-10 14:27:06', '2018-10-21 12:23:09', '2024', '', 80.00, NULL, 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk Pasangkayu.', 0),
(5, 'administrator', 'alwi.mansyur@gmail.com', 'administrator', NULL, '$2y$10$wkIJCe8dk3YaLaaIScBOBOAY4M8cLEyDsFm66Xhwo9U3p/wcik9Bi', '', 'administrator AHSP', '', 'user', 'images/avatar/c14719a7f71e46badf2cf93ae373ae9797281782_9.png', '2023-02-09 23:41:34', '2023-02-23 00:05:26', '2024', '08128886665', 80.00, 'non', 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk mu', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `wilayah_neo`
--

DROP TABLE IF EXISTS `wilayah_neo`;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

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
ALTER TABLE `asb_neo` ADD FULLTEXT KEY `uraian_barang` (`uraian_barang`);

--
-- Indeks untuk tabel `aset_neo`
--
ALTER TABLE `aset_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `berita_neo`
--
ALTER TABLE `berita_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `bidang_urusan_neo`
--
ALTER TABLE `bidang_urusan_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `daftar_paket_neo`
--
ALTER TABLE `daftar_paket_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `daftar_realisasi_neo`
--
ALTER TABLE `daftar_realisasi_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `daftar_uraian_paket`
--
ALTER TABLE `daftar_uraian_paket`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `db_asn_pemda_neo`
--
ALTER TABLE `db_asn_pemda_neo`
  ADD UNIQUE KEY `id` (`id`);

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
-- Indeks untuk tabel `rab_paket_neo`
--
ALTER TABLE `rab_paket_neo`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `realisasi_neo`
--
ALTER TABLE `realisasi_neo`
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
ALTER TABLE `sbu_neo` ADD FULLTEXT KEY `uraian_barang` (`uraian_barang`);

--
-- Indeks untuk tabel `ssh_neo`
--
ALTER TABLE `ssh_neo`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `ssh_neo` ADD FULLTEXT KEY `uraian_barang` (`uraian_barang`);

--
-- Indeks untuk tabel `sub_kegiatan_neo`
--
ALTER TABLE `sub_kegiatan_neo`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `sub_kegiatan_neo` ADD FULLTEXT KEY `nomenklatur_urusan` (`nomenklatur_urusan`);

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
ALTER TABLE `tujuan_sasaran_renstra_neo` ADD FULLTEXT KEY `text` (`text`);

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
-- AUTO_INCREMENT untuk tabel `berita_neo`
--
ALTER TABLE `berita_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `bidang_urusan_neo`
--
ALTER TABLE `bidang_urusan_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `daftar_paket_neo`
--
ALTER TABLE `daftar_paket_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `daftar_realisasi_neo`
--
ALTER TABLE `daftar_realisasi_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `daftar_uraian_paket`
--
ALTER TABLE `daftar_uraian_paket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `db_asn_pemda_neo`
--
ALTER TABLE `db_asn_pemda_neo`
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
-- AUTO_INCREMENT untuk tabel `rab_paket_neo`
--
ALTER TABLE `rab_paket_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `realisasi_neo`
--
ALTER TABLE `realisasi_neo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
