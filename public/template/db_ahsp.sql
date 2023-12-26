-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 02 Mar 2023 pada 13.49
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
-- Database: `db_ahsp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisa_alat`
--

CREATE TABLE `analisa_alat` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `jenis_peralatan` varchar(400) NOT NULL,
  `tenaga` double(12,6) DEFAULT NULL,
  `kapasitas` double(20,6) DEFAULT NULL,
  `sat_kapasitas` varchar(120) DEFAULT NULL,
  `harga_pakai` decimal(25,6) DEFAULT NULL,
  `umur` double(20,6) DEFAULT NULL,
  `jam_kerja_1_tahun` double(20,6) DEFAULT NULL,
  `nilai_sisa` double(20,6) DEFAULT NULL,
  `faktor_pengembalian_mdl` double(20,6) DEFAULT NULL,
  `biaya_pengembalian_mdl` double(20,6) DEFAULT NULL,
  `asuransi` double(20,6) DEFAULT NULL,
  `total_biaya_pasti` double(20,6) DEFAULT NULL,
  `bahan_bakar` double(20,6) DEFAULT NULL,
  `bahan_bakar1` double(20,6) DEFAULT NULL,
  `bahan_bakar2` double(20,6) DEFAULT NULL,
  `bahan_bakar3` double(20,6) DEFAULT NULL,
  `minyak_pelumas` double(20,6) DEFAULT NULL,
  `biaya_bbm` double(20,6) DEFAULT NULL,
  `koef_workshop` double(20,6) DEFAULT NULL,
  `biaya_workshop` double(20,6) DEFAULT NULL,
  `koef_perbaikan` double(20,6) DEFAULT NULL,
  `biaya_perbaikan` double(20,6) DEFAULT NULL,
  `jumlah_operator` int(11) DEFAULT NULL,
  `upah_operator` double(20,6) DEFAULT NULL,
  `jumlah_pembantu_ope` int(11) DEFAULT NULL,
  `upah_pembantu_ope` double(20,6) DEFAULT NULL,
  `total_biaya_operasi` double(20,6) DEFAULT NULL,
  `total_biaya_sewa` double(20,6) DEFAULT NULL,
  `keterangan` varchar(400) NOT NULL,
  `ket_alat` varchar(400) DEFAULT NULL,
  `ketentuan_tambahan` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisa_alat_custom`
--

CREATE TABLE `analisa_alat_custom` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(100) NOT NULL,
  `kode` varchar(100) DEFAULT NULL,
  `nomor` varchar(50) DEFAULT NULL,
  `uraian` varchar(400) NOT NULL,
  `koefisien` double(20,6) DEFAULT NULL,
  `satuan` varchar(100) DEFAULT NULL,
  `harga_satuan` decimal(24,12) DEFAULT NULL,
  `jumlah_harga` decimal(30,6) DEFAULT NULL,
  `rumus` varchar(255) DEFAULT NULL,
  `jenis_kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisa_item_pekerjaan`
--

CREATE TABLE `analisa_item_pekerjaan` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(100) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nomor` varchar(50) DEFAULT NULL,
  `uraian` varchar(400) NOT NULL,
  `koefisien` double(24,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(24,6) DEFAULT NULL,
  `rumus` varchar(255) DEFAULT NULL,
  `jenis_kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisa_pekerjaan_bm`
--

CREATE TABLE `analisa_pekerjaan_bm` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(100) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nomor` varchar(50) DEFAULT NULL,
  `uraian` varchar(400) DEFAULT NULL,
  `koefisien` double(24,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(24,6) DEFAULT NULL,
  `jumlah_harga` decimal(30,6) DEFAULT NULL,
  `rumus` varchar(255) DEFAULT NULL,
  `jenis_kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisa_pekerjaan_ck`
--

CREATE TABLE `analisa_pekerjaan_ck` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(150) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nomor` varchar(50) DEFAULT NULL,
  `uraian` text NOT NULL,
  `koefisien` double(24,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(24,6) DEFAULT NULL,
  `jumlah_harga` decimal(30,6) DEFAULT NULL,
  `rumus` varchar(255) DEFAULT NULL,
  `jenis_kode` varchar(100) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisa_pekerjaan_sda`
--

CREATE TABLE `analisa_pekerjaan_sda` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(100) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nomor` varchar(50) DEFAULT NULL,
  `uraian` varchar(400) NOT NULL,
  `koefisien` double(24,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(24,6) DEFAULT NULL,
  `jumlah_harga` decimal(30,6) DEFAULT NULL,
  `rumus` varchar(255) DEFAULT NULL,
  `jenis_kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `analisa_quarry`
--

CREATE TABLE `analisa_quarry` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(100) NOT NULL,
  `kode` varchar(50) DEFAULT NULL,
  `nomor` varchar(50) DEFAULT NULL,
  `uraian` varchar(400) NOT NULL,
  `koefisien` double(24,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_satuan` decimal(24,6) DEFAULT NULL,
  `jumlah_harga` decimal(30,6) DEFAULT NULL,
  `rumus` varchar(255) DEFAULT NULL,
  `jenis_kode` varchar(50) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `daftar_satuan`
--

CREATE TABLE `daftar_satuan` (
  `id` int(11) NOT NULL,
  `value` varchar(100) NOT NULL,
  `item` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `sebutan_lain` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `divisi`
--

CREATE TABLE `divisi` (
  `id` int(11) NOT NULL,
  `tahun` year(4) NOT NULL,
  `kode` varchar(150) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `satuan` varchar(150) DEFAULT NULL,
  `bidang` varchar(255) NOT NULL,
  `tingkat` int(11) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `harga_sat_upah_bahan`
--

CREATE TABLE `harga_sat_upah_bahan` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kode` varchar(120) NOT NULL,
  `jenis` varchar(50) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `satuan` varchar(120) NOT NULL,
  `harga_satuan` decimal(40,12) DEFAULT NULL,
  `sumber_data` varchar(400) DEFAULT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `keterangan` varchar(400) DEFAULT NULL,
  `tahun` int(11) NOT NULL,
  `status` varchar(200) NOT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `informasi_umum`
--

CREATE TABLE `informasi_umum` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kode` varchar(50) NOT NULL,
  `nomor_uraian` varchar(40) DEFAULT NULL,
  `uraian` varchar(400) NOT NULL,
  `nilai` decimal(20,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `keterangan` varchar(400) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `laporan_harian`
--

CREATE TABLE `laporan_harian` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(150) NOT NULL,
  `kode` varchar(150) NOT NULL,
  `tanggal` date NOT NULL,
  `type` varchar(150) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`value`)),
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lokasi_proyek`
--

CREATE TABLE `lokasi_proyek` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `sta_pengenal_X` float(20,12) NOT NULL,
  `sta_pengenal_Y` float(20,12) NOT NULL,
  `polyline` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `polygon` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`polygon`)),
  `marker` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`marker`)),
  `circle` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`circle`)),
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `monev`
--

CREATE TABLE `monev` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(255) NOT NULL,
  `id_rab` int(11) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `realisasi_fisik` float(30,6) NOT NULL,
  `realisasi_keu` decimal(20,6) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `tgl_input` datetime NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `nama_pkt_proyek`
--

CREATE TABLE `nama_pkt_proyek` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `nama_proyek` varchar(400) NOT NULL,
  `tahun_anggaran` int(11) NOT NULL,
  `tanggal_buat` datetime(4) NOT NULL DEFAULT current_timestamp(4) ON UPDATE current_timestamp(4),
  `nilai_kontrak` decimal(30,2) DEFAULT NULL,
  `no_kontrak` varchar(150) DEFAULT NULL,
  `tgl_kontrak` date DEFAULT NULL,
  `no_spm` varchar(150) DEFAULT NULL,
  `tgl_spm` date DEFAULT NULL,
  `addendum` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT '{}' CHECK (json_valid(`addendum`)),
  `no_pho` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `tgl_pho` date DEFAULT NULL,
  `no_fho` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `tgl_fho` date DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `nama_user` varchar(300) NOT NULL,
  `keterangan` varchar(400) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL,
  `status` varchar(100) NOT NULL,
  `owner` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`owner`)),
  `id_dasar_ahsp` int(11) DEFAULT NULL,
  `id_pelaksana` int(11) DEFAULT NULL,
  `id_konsultan` int(11) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `peraturan_data_umum`
--

CREATE TABLE `peraturan_data_umum` (
  `id` int(11) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `file` varchar(255) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `tanggal_upload` datetime NOT NULL,
  `status` varchar(50) NOT NULL,
  `kd_proyek` varchar(255) DEFAULT NULL,
  `id_user` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rekanan`
--

CREATE TABLE `rekanan` (
  `id` int(11) NOT NULL,
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
  `no_sortir` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rencana_anggaran_biaya`
--

CREATE TABLE `rencana_anggaran_biaya` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(100) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `volume` double(30,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_dasar` decimal(30,6) DEFAULT NULL,
  `jumlah_op` decimal(30,6) DEFAULT NULL,
  `harga_satuan` decimal(30,6) DEFAULT NULL,
  `jumlah_harga` decimal(30,6) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rencana_anggaran_biaya_addendum`
--

CREATE TABLE `rencana_anggaran_biaya_addendum` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(100) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `volume` double(30,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_dasar` decimal(30,6) DEFAULT NULL,
  `jumlah_op` decimal(30,6) DEFAULT NULL,
  `harga_satuan` decimal(30,6) DEFAULT NULL,
  `jumlah_harga` decimal(30,6) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rencana_anggaran_biaya_realisasi`
--

CREATE TABLE `rencana_anggaran_biaya_realisasi` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(100) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `volume` double(30,6) DEFAULT NULL,
  `satuan` varchar(50) DEFAULT NULL,
  `harga_dasar` decimal(30,6) DEFAULT NULL,
  `jumlah_op` decimal(30,6) DEFAULT NULL,
  `harga_satuan` decimal(30,6) DEFAULT NULL,
  `jumlah_harga` decimal(30,6) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `no_sortir` int(11) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ruang_chat`
--

CREATE TABLE `ruang_chat` (
  `id` int(11) NOT NULL,
  `waktu_input` datetime NOT NULL,
  `waktu_edit` datetime DEFAULT NULL,
  `uraian` blob NOT NULL,
  `id_pengirim` int(11) NOT NULL,
  `id_tujuan` varchar(255) NOT NULL DEFAULT '0',
  `dibaca` int(11) DEFAULT NULL,
  `id_reply` int(11) NOT NULL DEFAULT 0,
  `like` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`like`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `schedule_table`
--

CREATE TABLE `schedule_table` (
  `id` int(11) NOT NULL,
  `kd_proyek` varchar(150) NOT NULL,
  `kd_analisa` varchar(150) NOT NULL,
  `id_rab` int(11) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `durasi` int(11) DEFAULT NULL,
  `mulai` int(11) DEFAULT NULL,
  `bobot` float(20,6) DEFAULT NULL,
  `bobot_selesai` float(25,6) DEFAULT NULL,
  `dependent` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`dependent`)),
  `type` varchar(150) NOT NULL,
  `no_sortir` int(11) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_ahsp`
--

CREATE TABLE `user_ahsp` (
  `id` int(11) NOT NULL,
  `id_admin` int(11) DEFAULT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(225) NOT NULL,
  `nama_org` varchar(400) NOT NULL,
  `type_user` varchar(20) NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'default.jpeg',
  `tgl_daftar` datetime NOT NULL,
  `tgl_login` datetime NOT NULL,
  `thn_aktif_anggaran` year(4) DEFAULT NULL,
  `kd_proyek_aktif` varchar(150) DEFAULT NULL,
  `ket` varchar(250) DEFAULT NULL,
  `aktif` tinyint(1) NOT NULL DEFAULT 0,
  `aktif_edit` int(11) NOT NULL DEFAULT 1,
  `kontak_person` varchar(255) DEFAULT NULL,
  `font_size` decimal(5,2) DEFAULT NULL,
  `aktif_chat` int(11) DEFAULT NULL,
  `warna_tbl` varchar(150) DEFAULT NULL,
  `scrolling_table` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data untuk tabel `user_ahsp`
--

INSERT INTO `user_ahsp` (`id`, `id_admin`, `username`, `email`, `nama`, `password`, `nama_org`, `type_user`, `photo`, `tgl_daftar`, `tgl_login`, `thn_aktif_anggaran`, `kd_proyek_aktif`, `ket`, `aktif`, `aktif_edit`, `kontak_person`, `font_size`, `aktif_chat`, `warna_tbl`, `scrolling_table`) VALUES
(1, 0, 'alwi_mansyur', 'alwi@gmail.com', 'Alwi Mansyur', '$2y$10$phmt521EHu3PEkilYD/TJ.i1U.ZcMjAHAJt4y88r3O0tfbgs8HQl6', 'Alwi Mansyur', 'user', 'images/avatar/default.jpeg', '2018-06-04 21:57:05', '2023-02-23 00:17:14', NULL, 'P001', 'apa yang dapat saya berikan', 1, 1, 'pasangkayu ji', '90.00', NULL, 'non', 'short'),
(2, 0, 'nabiila', 'nabiila@gmail.com', 'nabiila', '$2y$10$Zxp6h5J9v8MiUtUZpDvNKe81qhVaN9gBTVusn/ov9mVwti/du1q1G', 'PT. Angin Ribat Skali dan satgat mengesankan sekali', 'admin', 'images/avatar/bbf4f78067dad81bec03965da604932e9e18f570_2.jpg', '2018-06-09 15:54:29', '2023-03-02 20:48:20', 2023, 'M001', 'Apa yang dapat saya berikan untuk Pasangkayu', 1, 1, '08128888', '80.00', NULL, 'non', 'short'),
(3, 0, 'inayah', 'inayah@gmail.com', 'inayah', '$2y$10$J1RLk2kaKqYeuFs2q76vxuoPYTi3cA8dCjRISJlnwlsi3sdHoAKg.', 'PT. Angin Ribat Skali dan satgat mengesankan sekali', 'user', 'images/avatar/default.jpeg', '2018-06-22 22:04:17', '2020-03-08 02:30:41', NULL, NULL, 'dimana mana hatiku senang oke', 1, 1, '', '80.00', NULL, NULL, 'short'),
(4, 0, 'Arlinda', 'arlinda@gmail.com', 'Arlinda Achmad', '$2y$10$V.f/.ElwettBd3jyJfMR5epHT0s8NVqaU/mL8ZIqIJo.HBb.6x/Qi', 'Prof', 'admin', 'images/avatar/default.jpeg', '2018-07-10 14:27:06', '2018-10-21 12:23:09', NULL, NULL, 'Apa yang dapat saya berikan untuk Pasangkayu.', 1, 1, '', '80.00', NULL, NULL, 'short'),
(5, NULL, 'administrator', 'alwi.mansyur@gmail.com', 'administrator', '$2y$10$cFR8KdFGXUFBZ5C5payBEOb3aPEXtvYwAKO6Gc6Zdqyjo7WRuDY8.', 'administrator AHSP', 'user', 'images/avatar/c14719a7f71e46badf2cf93ae373ae9797281782_9.png', '2023-02-09 23:41:34', '2023-02-23 00:05:26', 2023, 'M001', 'Apa yang dapat saya berikan untuk mu', 1, 1, '08128886665', '80.00', 0, 'non', 'short');

-- --------------------------------------------------------

--
-- Struktur dari tabel `wisdom`
--

CREATE TABLE `wisdom` (
  `id` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `nama_quote` varchar(150) DEFAULT NULL,
  `tanggal` datetime NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `analisa_alat`
--
ALTER TABLE `analisa_alat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisa_alat_custom`
--
ALTER TABLE `analisa_alat_custom`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisa_item_pekerjaan`
--
ALTER TABLE `analisa_item_pekerjaan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisa_pekerjaan_bm`
--
ALTER TABLE `analisa_pekerjaan_bm`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisa_pekerjaan_ck`
--
ALTER TABLE `analisa_pekerjaan_ck`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisa_pekerjaan_sda`
--
ALTER TABLE `analisa_pekerjaan_sda`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `analisa_quarry`
--
ALTER TABLE `analisa_quarry`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `daftar_satuan`
--
ALTER TABLE `daftar_satuan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `harga_sat_upah_bahan`
--
ALTER TABLE `harga_sat_upah_bahan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `informasi_umum`
--
ALTER TABLE `informasi_umum`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `laporan_harian`
--
ALTER TABLE `laporan_harian`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `lokasi_proyek`
--
ALTER TABLE `lokasi_proyek`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `monev`
--
ALTER TABLE `monev`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `nama_pkt_proyek`
--
ALTER TABLE `nama_pkt_proyek`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `peraturan_data_umum`
--
ALTER TABLE `peraturan_data_umum`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rekanan`
--
ALTER TABLE `rekanan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rencana_anggaran_biaya`
--
ALTER TABLE `rencana_anggaran_biaya`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rencana_anggaran_biaya_addendum`
--
ALTER TABLE `rencana_anggaran_biaya_addendum`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `rencana_anggaran_biaya_realisasi`
--
ALTER TABLE `rencana_anggaran_biaya_realisasi`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `ruang_chat`
--
ALTER TABLE `ruang_chat`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `schedule_table`
--
ALTER TABLE `schedule_table`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `user_ahsp`
--
ALTER TABLE `user_ahsp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `analisa_alat`
--
ALTER TABLE `analisa_alat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `analisa_alat_custom`
--
ALTER TABLE `analisa_alat_custom`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `analisa_item_pekerjaan`
--
ALTER TABLE `analisa_item_pekerjaan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `analisa_pekerjaan_bm`
--
ALTER TABLE `analisa_pekerjaan_bm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `analisa_pekerjaan_ck`
--
ALTER TABLE `analisa_pekerjaan_ck`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `analisa_pekerjaan_sda`
--
ALTER TABLE `analisa_pekerjaan_sda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `analisa_quarry`
--
ALTER TABLE `analisa_quarry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `daftar_satuan`
--
ALTER TABLE `daftar_satuan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `harga_sat_upah_bahan`
--
ALTER TABLE `harga_sat_upah_bahan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `informasi_umum`
--
ALTER TABLE `informasi_umum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `laporan_harian`
--
ALTER TABLE `laporan_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `lokasi_proyek`
--
ALTER TABLE `lokasi_proyek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `monev`
--
ALTER TABLE `monev`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `nama_pkt_proyek`
--
ALTER TABLE `nama_pkt_proyek`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `peraturan_data_umum`
--
ALTER TABLE `peraturan_data_umum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rekanan`
--
ALTER TABLE `rekanan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rencana_anggaran_biaya`
--
ALTER TABLE `rencana_anggaran_biaya`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rencana_anggaran_biaya_addendum`
--
ALTER TABLE `rencana_anggaran_biaya_addendum`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `rencana_anggaran_biaya_realisasi`
--
ALTER TABLE `rencana_anggaran_biaya_realisasi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `ruang_chat`
--
ALTER TABLE `ruang_chat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `schedule_table`
--
ALTER TABLE `schedule_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `user_ahsp`
--
ALTER TABLE `user_ahsp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
