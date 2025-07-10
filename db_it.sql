-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Apr 2023 pada 03.15
-- Versi server: 10.4.24-MariaDB
-- Versi PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_it`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_catrige`
--

CREATE TABLE `tb_catrige` (
  `id_catrige` int(11) NOT NULL,
  `id_printer` int(11) NOT NULL,
  `type_catrige` varchar(150) NOT NULL,
  `color_catrige` varchar(150) NOT NULL,
  `jml_catrige` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_form`
--

CREATE TABLE `tb_form` (
  `id_form` int(11) NOT NULL,
  `id_catrige` int(11) NOT NULL,
  `depart` varchar(150) NOT NULL,
  `tanggal_form` varchar(100) NOT NULL,
  `qty_pengambilan` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_ip`
--

CREATE TABLE `tb_ip` (
  `id_ip` int(11) NOT NULL,
  `ip_address` varchar(100) NOT NULL,
  `user_ip` varchar(100) NOT NULL,
  `status_ip` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_komputer`
--

CREATE TABLE `tb_komputer` (
  `kode_assets_kom` varchar(100) NOT NULL,
  `nama_assets_kom` varchar(150) NOT NULL,
  `tgl_pembelian_kom` varchar(100) NOT NULL,
  `user_kom` varchar(100) NOT NULL,
  `ip_kom` varchar(100) NOT NULL,
  `spec_kom` varchar(150) NOT NULL,
  `lokasi_kom` varchar(100) NOT NULL,
  `qty_kom` int(11) NOT NULL,
  `desc_kom` varchar(150) NOT NULL,
  `keterangan_kom` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_perbaikan`
--

CREATE TABLE `tb_perbaikan` (
  `id_perbaikan_kom` int(11) NOT NULL,
  `kode_assets_kom` varchar(100) NOT NULL,
  `deskripsi_perbaikan` varchar(150) NOT NULL,
  `tanggal_perbaikan` varchar(100) NOT NULL,
  `status_perbaikan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_printer`
--

CREATE TABLE `tb_printer` (
  `id_printer` int(11) NOT NULL,
  `nama_printer` varchar(100) NOT NULL,
  `lokasi_printer` varchar(100) NOT NULL,
  `ip_printer` varchar(100) NOT NULL,
  `status_printer` varchar(100) NOT NULL,
  `tanggal_printer` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tb_user`
--

CREATE TABLE `tb_user` (
  `id_user` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `username` varchar(150) NOT NULL,
  `password` varchar(100) NOT NULL,
  `role` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data untuk tabel `tb_user`
--

INSERT INTO `tb_user` (`id_user`, `nama`, `username`, `password`, `role`) VALUES
(1, 'Wibowo', 'wibowo21', '7a369a7865db60e5bb6818757684f4e4', '1');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `tb_catrige`
--
ALTER TABLE `tb_catrige`
  ADD PRIMARY KEY (`id_catrige`),
  ADD KEY `id_printer` (`id_printer`);

--
-- Indeks untuk tabel `tb_form`
--
ALTER TABLE `tb_form`
  ADD PRIMARY KEY (`id_form`),
  ADD KEY `id_catrige` (`id_catrige`);

--
-- Indeks untuk tabel `tb_ip`
--
ALTER TABLE `tb_ip`
  ADD PRIMARY KEY (`id_ip`);

--
-- Indeks untuk tabel `tb_komputer`
--
ALTER TABLE `tb_komputer`
  ADD PRIMARY KEY (`kode_assets_kom`);

--
-- Indeks untuk tabel `tb_perbaikan`
--
ALTER TABLE `tb_perbaikan`
  ADD PRIMARY KEY (`id_perbaikan_kom`),
  ADD KEY `kode_assets_kom` (`kode_assets_kom`);

--
-- Indeks untuk tabel `tb_printer`
--
ALTER TABLE `tb_printer`
  ADD PRIMARY KEY (`id_printer`);

--
-- Indeks untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `tb_catrige`
--
ALTER TABLE `tb_catrige`
  MODIFY `id_catrige` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_form`
--
ALTER TABLE `tb_form`
  MODIFY `id_form` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_ip`
--
ALTER TABLE `tb_ip`
  MODIFY `id_ip` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_perbaikan`
--
ALTER TABLE `tb_perbaikan`
  MODIFY `id_perbaikan_kom` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_printer`
--
ALTER TABLE `tb_printer`
  MODIFY `id_printer` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT untuk tabel `tb_user`
--
ALTER TABLE `tb_user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
