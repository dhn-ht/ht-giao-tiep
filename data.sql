-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th12 02, 2024 lúc 03:54 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `he_thong_giao_tiep`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(555) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(555) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `channels`
--

CREATE TABLE `channels` (
  `id` int(11) NOT NULL,
  `channel_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `room_id` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `channels`
--

INSERT INTO `channels` (`id`, `channel_name`, `room_id`, `created_by`, `created_at`) VALUES
(1, 'học', 1, 2, '2024-10-24 04:43:59'),
(4, 'Phòng chat', 2, 2, '2024-10-26 04:53:06'),
(5, 'Kênh', 9, 7, '2024-10-26 13:57:53'),
(6, 'Kênh 2', 9, 7, '2024-10-26 14:04:56'),
(7, 'Chat', 10, 9, '2024-10-26 15:56:18'),
(8, 'Chat1', 11, 9, '2024-10-26 15:57:33'),
(10, 'a', 12, 8, '2024-10-27 12:38:05'),
(11, 'b', 12, 8, '2024-10-27 12:40:31'),
(12, 'b', 13, 8, '2024-10-27 12:40:44'),
(13, 'Kênh', 2, 2, '2024-10-28 13:45:02'),
(14, 'Kênh 2', 2, 2, '2024-10-29 13:15:39'),
(16, 'Kênh 3', 3, 2, '2024-10-29 14:22:02'),
(17, 'Kênh 1', 15, 2, '2024-10-30 14:22:20'),
(18, 'Kênh 2', 3, 2, '2024-10-30 14:24:35'),
(19, 'Kênh 2', 3, 2, '2024-11-12 14:43:06');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `channel_members`
--

CREATE TABLE `channel_members` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `created_by`, `created_at`) VALUES
(3, 'Phòng gaming', 2, '2024-10-24 16:14:10'),
(8, 'Phòng', 7, '2024-10-26 13:57:02'),
(9, 'Phòng A', 7, '2024-10-26 13:57:43'),
(10, 'Phòng gaming', 9, '2024-10-26 15:56:12'),
(11, 'Phòng chat', 9, '2024-10-26 15:57:27'),
(12, 'A', 8, '2024-10-27 12:38:00'),
(13, 'b', 8, '2024-10-27 12:40:41'),
(14, 'Phòng B', 2, '2024-10-29 14:21:32'),
(15, 'Phòng C', 2, '2024-10-30 14:21:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `schools`
--

INSERT INTO `schools` (`id`, `school_name`) VALUES
(1, 'Đại học A');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('teacher','student') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `school_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `school_id`, `full_name`, `created_at`, `image`) VALUES
(1, 'hocsinh', '$2y$10$d5ayqOUXMMOgYfA5uiN0NejVetHecew36cMAoBTDygczyuU2byH16', 'hocsinh@gmail.com', 'student', 1, 'Nguyễn Văn A', '2024-10-24 04:43:02', 'uploads/profile_671b25aeb81a30.53281190.jpg'),
(2, 'giaovien', '$2y$10$0fQJ0N0dMdwL0yZrZrUIweEo8gH4LflmBtKhgbRoKKXShQm4IdISW', 'giaovien@gmail.com', 'teacher', 1, 'giaoviens', '2024-10-24 04:43:33', 'uploads/profile_671b25aeb81a30.53281190.jpg'),
(5, 'chauthebao', '$2y$10$Ec1ZgP1IoZx.8Qo34dODduaoUMD/IQRsfx6vAbELE.57.4mGcuOT.', 'chauthebao@gmail.com', 'teacher', 1, 'chauthebao', '2024-10-25 04:59:26', 'uploads/profile_671b25aeb81a30.53281190.jpg'),
(6, 'test1', '$2y$10$ETYNABJUyZkvi4WaxPnAk.TBM2G2srMd38wqyocwctFSc8tPNSdgy', 'a@gmail.com', 'student', 1, 'Dvan', '2024-10-25 14:44:15', 'uploads/profile_671baebf701817.00426183.jpg'),
(7, 'D', '$2y$10$a7QbMGmLTrHcckt29c/t0u9p6dApv6LGem9lXbukz9Ndcb7CY8Uae', '19020241@vnu.edu.vn', 'teacher', 1, 'Dang', '2024-10-26 13:56:24', 'uploads/profile_671cf508ad3220.77228170.jpg'),
(8, 'a', '$2y$10$oPul/LNPY.qg0.vJ2NI7XeM6meQCHNd6vD3jWOveqLThIa4f8KHYi', 'abc@gmail.com', 'teacher', 1, 'a', '2024-10-26 14:07:10', 'uploads/profile_671cf78e1f7ab7.29882828.jpg'),
(9, 't1', '$2y$10$pLSqTUi2AOVnw6JqEleX.e.09RbSfvFRbP0fLxqor2.VWHWbe.DsG', '1@gmail.com', 'teacher', 1, 'lam', '2024-10-26 15:55:37', 'uploads/profile_671d10f98d6d47.57519147.jpg'),
(10, 'ad', '$2y$10$1IyQ5BfMMb0grQMyusGKfOD5jur0dgnVnYQj69g43I4MzW26osttO', 'addd@mail.com', 'student', 1, 'ad', '2024-10-29 12:43:12', 'uploads/profile_6720d86087a877.31231330.jpg'),
(11, 'giaovienA', '$2y$10$Kj9eL2PndvWbxM5VR8vRFuyzgC3BJPD675VZEAJEUvfWIgV8A3Psm', 'giaovien@mail.com', 'teacher', 1, 'giaovien', '2024-10-29 14:14:10', 'uploads/profile_6720edb245f017.84108628.jpg');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `channels`
--
ALTER TABLE `channels`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_create` (`created_by`);

--
-- Chỉ mục cho bảng `channel_members`
--
ALTER TABLE `channel_members`
  ADD PRIMARY KEY (`id`),
  ADD KEY `member_id` (`user_id`),
  ADD KEY `channel_id` (`channel_id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `channel_id_message` (`channel_id`),
  ADD KEY `user_id_message` (`user_id`);

--
-- Chỉ mục cho bảng `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_create` (`created_by`);

--
-- Chỉ mục cho bảng `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `user_school` (`school_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `channels`
--
ALTER TABLE `channels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `channel_members`
--
ALTER TABLE `channel_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT cho bảng `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `channels`
--
ALTER TABLE `channels`
  ADD CONSTRAINT `channel_create` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `channel_members`
--
ALTER TABLE `channel_members`
  ADD CONSTRAINT `channel_id` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `member_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `channel_id_message` FOREIGN KEY (`channel_id`) REFERENCES `channels` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_id_message` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `room_create` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `user_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
