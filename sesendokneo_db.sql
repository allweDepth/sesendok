-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 27 Des 2023 pada 07.14
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
-- Struktur dari tabel `akun`
--

CREATE TABLE `akun` (
  `id` int(8) NOT NULL,
  `kode` varchar(17) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `pendapatan` tinyint(1) NOT NULL,
  `belanja` tinyint(1) NOT NULL,
  `pembiayaan` tinyint(1) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `asb`
--

CREATE TABLE `asb` (
  `id` int(8) NOT NULL,
  `kode` varchar(22) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `bidang_urusan`
--

CREATE TABLE `bidang_urusan` (
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
-- Struktur dari tabel `dpa`
--

CREATE TABLE `dpa` (
  `id` int(11) NOT NULL,
  `kode_opd` varchar(22) NOT NULL,
  `kode_bidang_urusan` varchar(22) NOT NULL,
  `kode_program` varchar(22) NOT NULL,
  `kode_kegiatan` varchar(22) NOT NULL,
  `kode_sub_kegiatan` varchar(22) NOT NULL,
  `kode_akun` varchar(22) NOT NULL,
  `uraian` varchar(400) NOT NULL,
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
  `disable` tinyint(1) NOT NULL,
  `tahun` int(11) NOT NULL,
  `tgl_input` date NOT NULL,
  `aksi` int(11) NOT NULL,
  `keterangan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `hspk`
--

CREATE TABLE `hspk` (
  `id` int(8) NOT NULL,
  `kode` varchar(22) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `kegiatan`
--

CREATE TABLE `kegiatan` (
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
-- Struktur dari tabel `organisasi`
--

CREATE TABLE `organisasi` (
  `id` int(8) NOT NULL,
  `kode_urusan` varchar(2) NOT NULL,
  `kode` varchar(19) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `program`
--

CREATE TABLE `program` (
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
-- Struktur dari tabel `satuan`
--

CREATE TABLE `satuan` (
  `id` int(11) NOT NULL,
  `uraian` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  `aksi` varchar(50) NOT NULL,
  `keterangan` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sbu`
--

CREATE TABLE `sbu` (
  `id` int(8) NOT NULL,
  `kode` varchar(22) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `ssh`
--

CREATE TABLE `ssh` (
  `id` int(8) NOT NULL,
  `kode` varchar(22) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `spesifikasi` varchar(400) DEFAULT NULL,
  `satuan` varchar(400) NOT NULL,
  `harga_satuan` decimal(12,0) NOT NULL,
  `tkdn` varchar(400) DEFAULT NULL,
  `merek` varchar(400) DEFAULT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sub_kegiatan`
--

CREATE TABLE `sub_kegiatan` (
  `id` int(8) NOT NULL,
  `kode` varchar(18) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `sumber_dana`
--

CREATE TABLE `sumber_dana` (
  `id` int(8) NOT NULL,
  `kode` varchar(9) NOT NULL,
  `uraian` varchar(400) NOT NULL,
  `peraturan` varchar(255) NOT NULL,
  `disable` tinyint(1) NOT NULL DEFAULT 0,
  `aksi` varchar(255) DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_ahsp`
--

CREATE TABLE `user_ahsp` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `password` varchar(225) NOT NULL,
  `nama_org` varchar(400) NOT NULL,
  `type_user` varchar(20) NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'default.jpeg',
  `tgl_daftar` datetime NOT NULL,
  `tgl_login` datetime NOT NULL,
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
-- Dumping data untuk tabel `user_ahsp`
--

INSERT INTO `user_ahsp` (`id`, `username`, `email`, `nama`, `password`, `nama_org`, `type_user`, `photo`, `tgl_daftar`, `tgl_login`, `kontak_person`, `font_size`, `warna_tbl`, `scrolling_table`, `disable_login`, `disable_anggaran`, `disable_kontrak`, `disable_realisasi`, `disable_chat`, `ket`) VALUES
(1, 'alwi_mansyur', 'alwi@gmail.com', 'Alwi Mansyur', '$2y$10$phmt521EHu3PEkilYD/TJ.i1U.ZcMjAHAJt4y88r3O0tfbgs8HQl6', 'Alwi Mansyur', 'user', 'images/avatar/default.jpeg', '2018-06-04 21:57:05', '2023-12-25 13:42:54', 'pasangkayu ji', 90.00, 'non', 'short', 0, 0, 0, 0, 1, 'apa yang dapat saya berikan'),
(2, 'nabiila', 'nabiila@gmail.com', 'nabiila', '$2y$10$Zxp6h5J9v8MiUtUZpDvNKe81qhVaN9gBTVusn/ov9mVwti/du1q1G', 'PT. Angin Ribat Skali dan satgat mengesankan sekali', 'admin', 'images/avatar/bbf4f78067dad81bec03965da604932e9e18f570_2.jpg', '2018-06-09 15:54:29', '2023-12-27 01:26:36', '08128888', 80.00, 'non', 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk Pasangkayu'),
(3, 'inayah', 'inayah@gmail.com', 'inayah', '$2y$10$J1RLk2kaKqYeuFs2q76vxuoPYTi3cA8dCjRISJlnwlsi3sdHoAKg.', 'PT. Angin Ribat Skali dan satgat mengesankan sekali', 'user', 'images/avatar/default.jpeg', '2018-06-22 22:04:17', '2020-03-08 02:30:41', '', 80.00, NULL, 'short', 0, 0, 0, 0, 1, 'dimana mana hatiku senang oke'),
(4, 'Arlinda', 'arlinda@gmail.com', 'Arlinda Achmad', '$2y$10$V.f/.ElwettBd3jyJfMR5epHT0s8NVqaU/mL8ZIqIJo.HBb.6x/Qi', 'Prof', 'admin', 'images/avatar/default.jpeg', '2018-07-10 14:27:06', '2018-10-21 12:23:09', '', 80.00, NULL, 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk Pasangkayu.'),
(5, 'administrator', 'alwi.mansyur@gmail.com', 'administrator', '$2y$10$cFR8KdFGXUFBZ5C5payBEOb3aPEXtvYwAKO6Gc6Zdqyjo7WRuDY8.', 'administrator AHSP', 'user', 'images/avatar/c14719a7f71e46badf2cf93ae373ae9797281782_9.png', '2023-02-09 23:41:34', '2023-02-23 00:05:26', '08128886665', 80.00, 'non', 'short', 0, 0, 0, 0, 1, 'Apa yang dapat saya berikan untuk mu');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `bidang_urusan`
--
ALTER TABLE `bidang_urusan`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `dpa`
--
ALTER TABLE `dpa`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `program`
--
ALTER TABLE `program`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `satuan`
--
ALTER TABLE `satuan`
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
-- AUTO_INCREMENT untuk tabel `bidang_urusan`
--
ALTER TABLE `bidang_urusan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `dpa`
--
ALTER TABLE `dpa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `program`
--
ALTER TABLE `program`
  MODIFY `id` int(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `satuan`
--
ALTER TABLE `satuan`
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
