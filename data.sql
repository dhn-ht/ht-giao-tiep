-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th10 28, 2024 lúc 12:08 PM
-- Phiên bản máy phục vụ: 10.3.39-MariaDB-cll-lve
-- Phiên bản PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `ht-giao-tiep`
--

DELIMITER $$
--
-- Thủ tục
--
$$

DELIMITER ;

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
  `channel_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
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
(12, 'b', 13, 8, '2024-10-27 12:40:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `channel_members`
--

CREATE TABLE `channel_members` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `channel_members`
--

INSERT INTO `channel_members` (`id`, `channel_id`, `user_id`, `joined_at`) VALUES
(7, NULL, 3, '2024-10-25 04:40:47'),
(8, 2, 3, '2024-10-25 04:42:47'),
(9, 2, 1, '2024-10-25 04:49:34'),
(10, 2, 6, '2024-10-25 14:47:55'),
(11, 1, 5, '2024-10-25 14:49:52'),
(12, 2, 5, '2024-10-25 14:56:53'),
(13, 4, 1, '2024-10-26 04:54:05'),
(14, 0, 7, '2024-10-26 13:58:24'),
(15, 0, 8, '2024-10-26 14:07:41'),
(16, 6, 8, '2024-10-26 14:08:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `channel_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `messages`
--

INSERT INTO `messages` (`id`, `channel_id`, `user_id`, `content`, `created_at`) VALUES
(1, 2, 1, '<p>dsads</p>', '2024-10-25 04:51:46'),
(2, 1, 2, '<p>ssssss</p>', '2024-10-25 05:08:00'),
(3, 1, 2, '<p>ds</p>', '2024-10-25 05:16:13'),
(4, 2, 1, '', '2024-10-25 14:52:31'),
(5, 1, 2, '<p>s</p>', '2024-10-25 14:55:01'),
(6, 1, 2, '<p>s</p>', '2024-10-25 14:55:15'),
(7, 2, 1, '<p>dcm</p>', '2024-10-25 14:55:28'),
(8, 2, 2, '', '2024-10-25 14:56:18'),
(9, 2, 2, '<p>dsds</p>', '2024-10-25 15:01:19'),
(10, 2, 1, '<p>dsdsdsd</p>', '2024-10-25 15:05:37'),
(11, 1, 2, '<p>Message</p>', '2024-10-26 01:32:14'),
(12, 1, 2, '<p>Messagesdsd</p>', '2024-10-26 01:32:42'),
(13, 1, 2, '<p>dsd</p>', '2024-10-26 01:32:48'),
(14, 1, 2, '<p>Messagedsds</p>', '2024-10-26 01:32:57'),
(15, 1, 2, '<p>Messageds</p>', '2024-10-26 01:33:18'),
(16, 1, 2, '<p>ds</p>', '2024-10-26 01:33:24'),
(17, 1, 2, '<p>sad</p>', '2024-10-26 01:33:31'),
(18, 1, 2, '<p>dsxz</p>', '2024-10-26 01:33:36'),
(19, 2, 1, '<p>sdadsad</p>', '2024-10-26 01:34:36'),
(20, 2, 1, '<p>x</p>', '2024-10-26 01:34:41'),
(21, 2, 1, '<p>cc</p>', '2024-10-26 01:34:48'),
(22, 2, 2, '<p>sssss</p>', '2024-10-26 01:49:20'),
(23, 2, 2, '<p>hi</p>', '2024-10-26 01:49:36'),
(24, 2, 2, '<p>s</p>', '2024-10-26 01:51:12'),
(25, 2, 2, '<p>xx</p>', '2024-10-26 01:51:17'),
(26, 4, 1, '<p>hello</p>', '2024-10-26 04:54:39'),
(27, 4, 2, '<ol><li>bài về nhà</li><li>học tập</li><li>làm bài</li></ol>', '2024-10-26 04:55:25'),
(28, 4, 2, '<p>xin chào</p><p>&nbsp;</p>', '2024-10-26 04:55:54'),
(29, 4, 2, '', '2024-10-26 05:27:36'),
(30, 4, 2, '', '2024-10-26 05:27:41'),
(31, 4, 2, '<figure class=\"table\"><table><tbody><tr><td>1</td><td>2</td></tr><tr><td>3</td><td>4</td></tr></tbody></table></figure>', '2024-10-26 05:28:07'),
(32, 5, 7, '<p>hello</p>', '2024-10-26 14:03:48'),
(33, 7, 9, '<p>hello</p>', '2024-10-26 15:56:27'),
(34, 8, 9, '<p>xin chào</p><p>&nbsp;</p>', '2024-10-26 15:58:03'),
(35, 9, 9, '<p>hello</p>', '2024-10-26 15:58:11'),
(36, 4, 1, '<p>hello</p><p>&nbsp;</p>', '2024-10-26 15:59:44'),
(37, 4, 1, '', '2024-10-26 16:00:07'),
(38, 10, 8, '<p>123</p>', '2024-10-27 12:38:23'),
(39, 10, 8, '<p>123213213</p>', '2024-10-27 12:38:28'),
(40, 10, 8, '<blockquote><p>fasf</p></blockquote>', '2024-10-27 12:38:58'),
(41, 11, 8, '<p>ầ</p>', '2024-10-27 12:41:01');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `rooms`
--

CREATE TABLE `rooms` (
  `id` int(11) NOT NULL,
  `room_name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `rooms`
--

INSERT INTO `rooms` (`id`, `room_name`, `created_by`, `created_at`) VALUES
(2, 's', 2, '2024-10-24 12:17:06'),
(3, 'Phòng gaming', 2, '2024-10-24 16:14:10'),
(8, 'Phòng', 7, '2024-10-26 13:57:02'),
(9, 'Phòng A', 7, '2024-10-26 13:57:43'),
(10, 'Phòng gaming', 9, '2024-10-26 15:56:12'),
(11, 'Phòng chat', 9, '2024-10-26 15:57:27'),
(12, 'A', 8, '2024-10-27 12:38:00'),
(13, 'b', 8, '2024-10-27 12:40:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Đang đổ dữ liệu cho bảng `schools`
--

INSERT INTO `schools` (`id`, `school_name`) VALUES
(1, 'Trường Đại Học A');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
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
(9, 't1', '$2y$10$pLSqTUi2AOVnw6JqEleX.e.09RbSfvFRbP0fLxqor2.VWHWbe.DsG', '1@gmail.com', 'teacher', 1, 'lam', '2024-10-26 15:55:37', 'uploads/profile_671d10f98d6d47.57519147.jpg');

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
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `channel_members`
--
ALTER TABLE `channel_members`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `username` (`username`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `channel_members`
--
ALTER TABLE `channel_members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT cho bảng `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
