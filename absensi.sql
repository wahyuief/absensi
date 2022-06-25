-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 25, 2022 at 01:43 AM
-- Server version: 10.7.3-MariaDB-log
-- PHP Version: 7.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `absensi`
--

-- --------------------------------------------------------

--
-- Table structure for table `absensi`
--

CREATE TABLE `absensi` (
  `id_absensi` int(11) UNSIGNED NOT NULL,
  `id_matkul` int(11) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `jadwal` datetime NOT NULL DEFAULT current_timestamp(),
  `keterangan` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `name` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `name`, `description`) VALUES
(1, 'admin', 'Administrator'),
(2, 'member', 'Default group'),
(3, 'dosen', ''),
(4, 'mahasiswa', '');

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id_kelas` int(11) UNSIGNED NOT NULL,
  `nama_kelas` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id_kelas`, `nama_kelas`) VALUES
(1, 'TIF A'),
(2, 'TIF B'),
(3, 'TIF C');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_mahasiswa`
--

CREATE TABLE `kelas_mahasiswa` (
  `id_km` int(11) UNSIGNED NOT NULL,
  `id_kelas` int(11) UNSIGNED NOT NULL,
  `id_semester` int(11) UNSIGNED NOT NULL,
  `id_mahasiswa` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kelas_mahasiswa`
--

INSERT INTO `kelas_mahasiswa` (`id_km`, `id_kelas`, `id_semester`, `id_mahasiswa`) VALUES
(1, 3, 5, 12),
(2, 3, 6, 14);

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE `login_attempts` (
  `id` int(11) UNSIGNED NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `login` varchar(100) NOT NULL,
  `time` int(11) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `mata_kuliah`
--

CREATE TABLE `mata_kuliah` (
  `id_matkul` int(11) UNSIGNED NOT NULL,
  `id_semester` int(11) UNSIGNED NOT NULL,
  `id_dosen` int(11) UNSIGNED NOT NULL,
  `nama_matkul` varchar(100) NOT NULL,
  `sks` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `mata_kuliah`
--

INSERT INTO `mata_kuliah` (`id_matkul`, `id_semester`, `id_dosen`, `nama_matkul`, `sks`) VALUES
(1, 5, 13, 'Matematika Diskrit', 3),
(2, 5, 13, 'Pemrograman Web', 2),
(3, 6, 13, 'Robotik', 3),
(4, 6, 13, 'Mobile Application', 2);

-- --------------------------------------------------------

--
-- Table structure for table `matkul_mahasiswa`
--

CREATE TABLE `matkul_mahasiswa` (
  `id_mm` int(11) UNSIGNED NOT NULL,
  `id_matkul` int(11) UNSIGNED NOT NULL,
  `id_mahasiswa` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `matkul_mahasiswa`
--

INSERT INTO `matkul_mahasiswa` (`id_mm`, `id_matkul`, `id_mahasiswa`) VALUES
(1, 1, 12),
(2, 4, 14),
(3, 3, 14);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` bigint(20) NOT NULL,
  `sender_id` bigint(20) NOT NULL,
  `receiver_id` bigint(20) NOT NULL,
  `title` varchar(64) NOT NULL,
  `message` text NOT NULL,
  `read_on` datetime DEFAULT NULL,
  `sent_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `options`
--

CREATE TABLE `options` (
  `option_name` varchar(64) NOT NULL,
  `option_value` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `options`
--

INSERT INTO `options` (`option_name`, `option_value`) VALUES
('site_name', 'Absensi'),
('site_description', 'Baseigniter'),
('author', 'Wahyu Arief'),
('email', 'wahyuief@gmail.com'),
('timezone', 'Asia/Jakarta'),
('accent_color', 'success'),
('site_title', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `semester`
--

CREATE TABLE `semester` (
  `id_semester` int(11) UNSIGNED NOT NULL,
  `tahun` varchar(100) NOT NULL,
  `keterangan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `semester`
--

INSERT INTO `semester` (`id_semester`, `tahun`, `keterangan`) VALUES
(2, '2021/2022', 'Ganjil'),
(4, '2021/2022', 'Genap'),
(5, '2022/2023', 'Ganjil'),
(6, '2022/2023', 'Genap');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) UNSIGNED NOT NULL,
  `email` varchar(254) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `home_address` text DEFAULT NULL,
  `avatar` varchar(128) DEFAULT NULL,
  `last_login` int(11) UNSIGNED DEFAULT NULL,
  `uuid` varchar(64) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL,
  `activation_selector` varchar(255) DEFAULT NULL,
  `activation_code` varchar(255) DEFAULT NULL,
  `forgotten_password_selector` varchar(255) DEFAULT NULL,
  `forgotten_password_code` varchar(255) DEFAULT NULL,
  `forgotten_password_time` int(11) UNSIGNED DEFAULT NULL,
  `remember_selector` varchar(255) DEFAULT NULL,
  `remember_code` varchar(255) DEFAULT NULL,
  `created_on` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `fullname`, `phone`, `company`, `gender`, `birth_date`, `home_address`, `avatar`, `last_login`, `uuid`, `ip_address`, `active`, `activation_selector`, `activation_code`, `forgotten_password_selector`, `forgotten_password_code`, `forgotten_password_time`, `remember_selector`, `remember_code`, `created_on`) VALUES
(1, 'admin@absensi.test', 'administrator', '$argon2id$v=19$m=2048,t=1,p=1$eks4TEdMMWxtRzZna05GWA$A2wGpsXdQpYIMCSBhLxziD1bivndOf1wENiO5ndoSMM', 'Administrator', '085219842984', 'PT. Majapahit Teknologi Nusantara', NULL, NULL, NULL, '6cce0297bab361ef2e11af2c6fd3b4a9.jpg', 1656121121, '7c270c35-855b-528d-622f-9a3e2c04b177', '127.0.0.1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1653917341),
(12, 'fauzan@zakir.com', '2138791232', '$argon2id$v=19$m=2048,t=1,p=1$NENablVQcVBjSFlYRTFGMQ$pPPwDq5m95GSgzb1CjGtn/ljo/ypet1V6Xr0fj+RmbY', 'Fauzan Zakir', '081231238189', '', NULL, NULL, NULL, NULL, 1656121141, '360c71a6-a22d-cf0c-dc17-44eb3a64d5ff', '127.0.0.1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1655821402),
(13, 'adam@arif.com', '324243223', '$argon2id$v=19$m=2048,t=1,p=1$b0w3TWNxVmpFUUxpdlAxbg$ej/WQ5L9PMFk/2rsPvWyLBHeKgW+a+cdDn/wM2ekfTU', 'Adam Arif', '081232478392', NULL, NULL, NULL, NULL, NULL, 1656120893, '538646e6-3aba-f1f9-7ad5-acf0e33930fb', '127.0.0.1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1655831393),
(14, 'toleudin@gmail.com', '4533132132', '$argon2id$v=19$m=2048,t=1,p=1$NHVFbHppYjJFcHAxTU9mbg$ebgfi8G03smYHevRRFUVU6z3+5NgqJrlpaUIlhFUbSI', 'Tole Udin', '0812432893423', NULL, NULL, NULL, NULL, NULL, NULL, '3d5c40c0-b5af-8708-7eef-0d68e49f85a5', '127.0.0.1', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1656098831);

-- --------------------------------------------------------

--
-- Table structure for table `users_groups`
--

CREATE TABLE `users_groups` (
  `id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `group_id` mediumint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_groups`
--

INSERT INTO `users_groups` (`id`, `user_id`, `group_id`) VALUES
(32, 1, 1),
(35, 12, 2),
(36, 12, 4),
(37, 13, 2),
(38, 13, 3),
(39, 14, 2),
(40, 14, 4);

-- --------------------------------------------------------

--
-- Table structure for table `users_photos`
--

CREATE TABLE `users_photos` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_user` int(11) UNSIGNED NOT NULL,
  `photo` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id_absensi`),
  ADD KEY `id_matkul` (`id_matkul`) USING BTREE,
  ADD KEY `id_user` (`id_user`) USING BTREE;

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id_kelas`);

--
-- Indexes for table `kelas_mahasiswa`
--
ALTER TABLE `kelas_mahasiswa`
  ADD PRIMARY KEY (`id_km`),
  ADD KEY `id_semester` (`id_semester`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_kelas` (`id_kelas`);

--
-- Indexes for table `login_attempts`
--
ALTER TABLE `login_attempts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD PRIMARY KEY (`id_matkul`),
  ADD KEY `user_id` (`id_dosen`),
  ADD KEY `id_dosen` (`id_dosen`),
  ADD KEY `id_semester` (`id_semester`);

--
-- Indexes for table `matkul_mahasiswa`
--
ALTER TABLE `matkul_mahasiswa`
  ADD PRIMARY KEY (`id_mm`),
  ADD KEY `id_mahasiswa` (`id_mahasiswa`),
  ADD KEY `id_matkul` (`id_matkul`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id_semester`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_email` (`email`),
  ADD UNIQUE KEY `uuid` (`uuid`),
  ADD UNIQUE KEY `uc_activation_selector` (`activation_selector`),
  ADD UNIQUE KEY `uc_forgotten_password_selector` (`forgotten_password_selector`),
  ADD UNIQUE KEY `uc_remember_selector` (`remember_selector`);

--
-- Indexes for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_users_groups` (`user_id`,`group_id`),
  ADD KEY `fk_users_groups_users1_idx` (`user_id`),
  ADD KEY `fk_users_groups_groups1_idx` (`group_id`);

--
-- Indexes for table `users_photos`
--
ALTER TABLE `users_photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id_absensi` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id_kelas` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kelas_mahasiswa`
--
ALTER TABLE `kelas_mahasiswa`
  MODIFY `id_km` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `login_attempts`
--
ALTER TABLE `login_attempts`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  MODIFY `id_matkul` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `matkul_mahasiswa`
--
ALTER TABLE `matkul_mahasiswa`
  MODIFY `id_mm` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `semester`
--
ALTER TABLE `semester`
  MODIFY `id_semester` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users_groups`
--
ALTER TABLE `users_groups`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users_photos`
--
ALTER TABLE `users_photos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`),
  ADD CONSTRAINT `absensi_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`);

--
-- Constraints for table `kelas_mahasiswa`
--
ALTER TABLE `kelas_mahasiswa`
  ADD CONSTRAINT `kelas_mahasiswa_ibfk_1` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `kelas_mahasiswa_ibfk_2` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id_semester`),
  ADD CONSTRAINT `kelas_mahasiswa_ibfk_3` FOREIGN KEY (`id_kelas`) REFERENCES `kelas` (`id_kelas`);

--
-- Constraints for table `mata_kuliah`
--
ALTER TABLE `mata_kuliah`
  ADD CONSTRAINT `mata_kuliah_ibfk_1` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id_semester`),
  ADD CONSTRAINT `mata_kuliah_ibfk_2` FOREIGN KEY (`id_dosen`) REFERENCES `users` (`id`);

--
-- Constraints for table `matkul_mahasiswa`
--
ALTER TABLE `matkul_mahasiswa`
  ADD CONSTRAINT `matkul_mahasiswa_ibfk_1` FOREIGN KEY (`id_matkul`) REFERENCES `mata_kuliah` (`id_matkul`),
  ADD CONSTRAINT `matkul_mahasiswa_ibfk_2` FOREIGN KEY (`id_mahasiswa`) REFERENCES `users` (`id`);

--
-- Constraints for table `users_groups`
--
ALTER TABLE `users_groups`
  ADD CONSTRAINT `fk_users_groups_groups1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_users_groups_users1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `users_photos`
--
ALTER TABLE `users_photos`
  ADD CONSTRAINT `users_photos_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
