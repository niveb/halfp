-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 24, 2021 at 03:23 PM
-- Server version: 5.7.30-0ubuntu0.18.04.1-log
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sitedb`
--

-- --------------------------------------------------------

--
-- Table structure for table `chats`
--

CREATE TABLE `chats` (
  `id` bigint(20) NOT NULL,
  `chat_id` bigint(20) NOT NULL,
  `user_src_id` bigint(20) NOT NULL,
  `user_dst_id` bigint(20) NOT NULL,
  `message` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `message_type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chats`
--

INSERT INTO `chats` (`id`, `chat_id`, `user_src_id`, `user_dst_id`, `message`, `is_read`, `message_type`, `time`) VALUES
(1, 1, 16, 9, 'YrrbplMi', 1, 'text', '2020-11-24 12:08:02'),
(2, 1, 16, 9, 'ELbM7g==', 1, 'text', '2020-11-24 12:08:16'),
(3, 1, 16, 9, 'ELzC70ZqIAplhI5i', 1, 'text', '2020-11-24 12:08:27'),
(4, 1, 9, 16, 'EfTY70FxK08wy481uWdFletpc12fGsiFWw==', 1, 'text', '2020-11-24 14:17:50'),
(5, 1, 9, 16, 'Ob3R7153MBU=', 1, 'text', '2020-11-24 14:18:01'),
(6, 1, 16, 9, 'H6HQrlM5ZRBwgpA44g==', 1, 'text', '2020-11-24 14:18:22'),
(7, 1, 16, 9, 'FLzapAd5MQpog5Iu9ClDn+snLFnSEMWU', 1, 'text', '2020-11-24 14:18:40'),
(8, 1, 16, 9, 'Hrrxq1E3FkRw3ZQktX8doKlwDG2kOZyVC23xpd0R', 1, 'file', '2020-11-24 14:19:40'),
(9, 2, 18, 16, 'YrrbplMi', 1, 'text', '2020-11-24 14:21:51'),
(10, 2, 18, 16, 'ELbQqkJ9PFM9yto=', 0, 'text', '2020-11-24 14:21:59'),
(11, 2, 18, 16, 'OaHQ7153MApog54vvTYR2Q==', 0, 'text', '2020-11-24 14:22:05');

-- --------------------------------------------------------

--
-- Table structure for table `chat_blocks`
--

CREATE TABLE `chat_blocks` (
  `id` int(11) NOT NULL,
  `user_src_id` bigint(20) NOT NULL,
  `user_dst_id` bigint(20) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `image_comments`
--

CREATE TABLE `image_comments` (
  `id` bigint(20) NOT NULL,
  `image_id` bigint(20) NOT NULL,
  `image_comment` text NOT NULL,
  `date` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `image_likes`
--

CREATE TABLE `image_likes` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `image_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `id` int(11) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `target_id` bigint(20) NOT NULL,
  `type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(256) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `borndate` date NOT NULL,
  `country` varchar(20) NOT NULL,
  `city` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `reg_ip` varchar(50) NOT NULL,
  `last_ip` varchar(50) NOT NULL,
  `reg_date` bigint(20) NOT NULL,
  `last_online` bigint(20) NOT NULL,
  `verified` tinyint(1) DEFAULT NULL,
  `profile_bio` text,
  `profile_image` varchar(255) DEFAULT NULL,
  `searchable` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `firstname`, `surname`, `borndate`, `country`, `city`, `phone`, `gender`, `reg_ip`, `last_ip`, `reg_date`, `last_online`, `verified`, `profile_bio`, `profile_image`, `searchable`) VALUES
(7, 'lucia', 'lucia@test.test', '$2y$12$nXl2pvTszTBQbbgTZjG/eerh4Gn.hQDnV.lsFsoe5i9uCmPiVtCam', 'Lucia', 'Besico', '1978-08-09', 'italia', 'roma', '0000000000', 'female', '172.17.0.1', '172.17.0.1', 1603800262, 1603800262, NULL, 'lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ipsum lore ips', 'ZwQEg/Y5a3JTM0g4rUNcmt7Du1.png', 1),
(8, 'sara.selmi', 'sara@test.test', '$2y$12$hks/SWJWDVu.KbfLZp9c8uOm02r/7NELJV1z38nRC8itw2ZloN2i6', 'Sara', 'Selmi', '1991-07-10', 'italy', 'milano', '0000000000', 'female', '172.17.0.1', '172.17.0.1', 1603800313, 1603800313, NULL, NULL, 'no_profile.png', 1),
(9, 'Julia.Solen', 'test1@test.x', '$2y$12$RdDtip0EYj3MXryTUMaxoe9.hgb4xH2xHFwsCMRhM6YahTApm8eIu', 'Julia', 'Solen', '1990-12-18', 'it', 'roma', '000000000', 'female', '172.17.0.1', '172.17.0.1', 1606208457, 1606208457, NULL, 'Erasmus student. I love travelling', 'NzBrD/BdKXC7Ub2FtM3rukHTRp.jpg', 1),
(10, 'Mariaa89', 'test2@test2.x', '$2y$12$muxjIuC5zkCazoGCT8bWFOepiqtXAz09/eIUTb4ChxccYb1DRHyc6', 'Maria', 'Fillici', '1989-08-09', 'it', 'roma', '000000000', 'female', '172.17.0.1', '172.17.0.1', 1606209093, 1606209093, NULL, 'sono piuttosto timida', 'GiM18/nBqwYkW4ExuiRcbAzda2.jpg', 1),
(11, 'Sarett', 'test3@test3.x', '$2y$12$fS4GFwm5ad/vQeekxuSQpORFfoUwkwl6Q1UKsKExvU5YK.SAeUauK', 'Sara', 'Stefani', '1991-01-10', 'it', 'milano', '00000000', 'female', '172.17.0.1', '172.17.0.1', 1606209260, 1606209260, NULL, 'Sempre me stessa', 'mepwo/DXdZb6wsafiJzGTcYumF.jpg', 1),
(12, 'Luis', 'test4@test4.x', '$2y$12$gZi/4Jz68muKTd1Ej6oz6.2CGZea9xPMC4A2ZRWWDqi296Ey0uf9O', 'Luigi', 'Losso', '1993-09-02', 'it', 'roma', '00000000', 'male', '172.17.0.1', '172.17.0.1', 1606210383, 1606210383, NULL, NULL, 'CL7DE/tYVAL5acRq6KMHB10Doj.jpg', 1),
(13, 'postissi_grazia', 'test5@test5.x', '$2y$12$EKUlgp2gF7QOezPjDMXOZug/.1moVs87A97bgl6Sq36AK/P1ZyCZO', 'Grazia', 'Postissi', '1982-07-07', 'it', 'torino', '0000000', 'female', '172.17.0.1', '172.17.0.1', 1606210783, 1606210783, NULL, 'Appassionata di biologia', 'OvT1C/c6xNK9eJD1u0rkEW7ltQ.jpg', 1),
(14, 'Camicami', 'test6@test6.x', '$2y$12$/udKIxqgxiMDox2jxbYTaOGh8N9IEmld6XrZlJIrP5ttz4KaULO5W', 'Camilla', 'Cassino', '1992-03-12', 'it', 'roma', '00000000', 'female', '172.17.0.1', '172.17.0.1', 1606211612, 1606211612, NULL, 'Amo gli animali', 'UbnP7/Mab4jGLPqBgiRVAecx0I.jpg', 1),
(15, 'Caterina.Lori', 'test7@test7.x', '$2y$12$4DUNCQXXZRe5/16YjL.VkumRCz0oxvL/uPkZ8Py3hORBrA9Sb5Q2K', 'Caterina', 'Lori', '1994-11-03', 'it', 'messina', '0000000', 'female', '172.17.0.1', '172.17.0.1', 1606218141, 1606218141, NULL, 'Amante dei libri', 'dH8Xk/y3XUMIW5FbVJpkOTEN2A.jpg', 1),
(16, 'RealKate', 'task8@task8.x', '$2y$12$8hFeu6JpPxRqgoJc9tfaP.ZahxUY6te501L/sMr9lIP5JKzrOUtUS', 'Kate', 'Sehi', '1995-09-10', 'it', 'milano', '00000000', 'female', '172.17.0.1', '172.17.0.1', 1606218832, 1606218832, NULL, 'I speak english and a little bit of italian', 'cmtXH/lcXBhJanoViDPsz2r1ey.jpg', 1),
(17, 'Marzia.Moretti', 'test9@test9.x', '$2y$12$dCQNUze2RCHk4FAIVQGW6uvnC.limkjNoW90JA/1rhvDZFbT1Zz4e', 'Marzia', 'Moretti', '1987-08-09', 'it', 'roma', '00000000', 'female', '172.17.0.1', '172.17.0.1', 1606218950, 1606218950, NULL, 'Mi ritengo una persona semplice, ma elegante', 'cbe1m/sq2IZTS6OWinyHUGCJuw.jpg', 1),
(18, 'IlMike', 'test10@test10.x', '$2y$12$J3BaKMd/N8me8SHbKqZGv.A6/mF5PlELM3u5yLQkxxq4bthWcTwMO', 'Michele', 'Tanni', '1989-12-18', 'it', 'milano', '00000000', 'male', '172.17.0.1', '172.17.0.1', 1606219056, 1606219056, NULL, 'Laureato!!!', 'Othl9/qHy0prjs8eTxQFtbaVzm.jpg', 1),
(19, 'Mariox', 'test11@test11.x', '$2y$12$tQZGzK3kFt8bDxFkCDmnQeuGAl8Wam4EKdrgFs8zZ1DJ6CjfXhjE.', 'Mario', 'Solin', '1990-07-06', 'it', 'roma', '000000000', 'male', '172.17.0.1', '172.17.0.1', 1606297546, 1606297546, NULL, NULL, 'ib5Nk/8TpkCYJEQ1NxDPSslcrB.jpg', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_following`
--

CREATE TABLE `user_following` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `following_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_following`
--

INSERT INTO `user_following` (`id`, `user_id`, `following_id`) VALUES
(14, 7, 8),
(15, 15, 12),
(16, 19, 17),
(17, 19, 16),
(18, 19, 15),
(19, 19, 14),
(20, 19, 11),
(21, 19, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user_images`
--

CREATE TABLE `user_images` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `image_url` varchar(255) NOT NULL,
  `upload_date` bigint(20) NOT NULL,
  `image_description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_images`
--

INSERT INTO `user_images` (`id`, `user_id`, `image_url`, `upload_date`, `image_description`) VALUES
(5, 7, '3PYFx/cDJWMinYGUH8IEpdTw46.jpg', 1604502284, 'test &lt;b&gt;mmm&lt;/b&gt;'),
(6, 7, 'aGhQs/xWGr1kshCI0YKj34ivzX.png', 1604503099, ''),
(7, 9, 'XrVq5/X2qas9rWYdFR4JTCeijx.jpg', 1606208874, 'my garden :)'),
(8, 9, 'SJ1zw/2XCJ1qFBSae0vQknboWd.jpg', 1606208938, 'my garden pt.2 #green'),
(9, 11, 'TR1Nv/dR6FGrjKLHQkOwJ0upSv.jpg', 1606209353, ''),
(10, 14, 'aJL4Q/Ra89mCr0kDZ1bQ23jKo6.jpg', 1606211739, 'Il mio cane <br />\r\nðŸ˜Š'),
(12, 14, 'U8vBn/FYWI0geQh9Al3jNUn1Ho.jpg', 1606217925, ''),
(13, 18, 'JX1QY/yXZTN7AdWfGxJwQE4Dv1.jpg', 1606219145, '');

-- --------------------------------------------------------

--
-- Table structure for table `user_notif`
--

CREATE TABLE `user_notif` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `user_two_id` bigint(20) NOT NULL,
  `date` bigint(20) NOT NULL,
  `activity` tinyint(1) NOT NULL,
  `data` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_notif`
--

INSERT INTO `user_notif` (`id`, `user_id`, `user_two_id`, `date`, `activity`, `data`) VALUES
(34, 7, 8, 1604910283, 1, NULL),
(35, 15, 12, 1606218321, 1, NULL),
(36, 19, 17, 1606297857, 1, NULL),
(37, 19, 16, 1606297871, 1, NULL),
(38, 19, 15, 1606297888, 1, NULL),
(39, 19, 14, 1606297905, 1, NULL),
(40, 19, 11, 1606297914, 1, NULL),
(41, 19, 9, 1606297923, 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chats`
--
ALTER TABLE `chats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_src_id` (`user_src_id`),
  ADD KEY `user_dst_id` (`user_dst_id`);

--
-- Indexes for table `chat_blocks`
--
ALTER TABLE `chat_blocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_blocks_ibfk_1` (`user_dst_id`),
  ADD KEY `user_src_id` (`user_src_id`);

--
-- Indexes for table `image_comments`
--
ALTER TABLE `image_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `commentdeletion` (`image_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `image_likes`
--
ALTER TABLE `image_likes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `likedeletion` (`image_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `target_id` (`target_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_following`
--
ALTER TABLE `user_following`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `following_id` (`following_id`);

--
-- Indexes for table `user_images`
--
ALTER TABLE `user_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_notif`
--
ALTER TABLE `user_notif`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_two_id` (`user_two_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chats`
--
ALTER TABLE `chats`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `chat_blocks`
--
ALTER TABLE `chat_blocks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_comments`
--
ALTER TABLE `image_comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `image_likes`
--
ALTER TABLE `image_likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `user_following`
--
ALTER TABLE `user_following`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `user_images`
--
ALTER TABLE `user_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `user_notif`
--
ALTER TABLE `user_notif`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chats`
--
ALTER TABLE `chats`
  ADD CONSTRAINT `chats_ibfk_1` FOREIGN KEY (`user_src_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chats_ibfk_2` FOREIGN KEY (`user_dst_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_blocks`
--
ALTER TABLE `chat_blocks`
  ADD CONSTRAINT `chat_blocks_ibfk_1` FOREIGN KEY (`user_dst_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_blocks_ibfk_2` FOREIGN KEY (`user_src_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `image_comments`
--
ALTER TABLE `image_comments`
  ADD CONSTRAINT `commentdeletion` FOREIGN KEY (`image_id`) REFERENCES `user_images` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `image_comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `image_likes`
--
ALTER TABLE `image_likes`
  ADD CONSTRAINT `image_likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `likedeletion` FOREIGN KEY (`image_id`) REFERENCES `user_images` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`target_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_following`
--
ALTER TABLE `user_following`
  ADD CONSTRAINT `user_following_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_following_ibfk_2` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_images`
--
ALTER TABLE `user_images`
  ADD CONSTRAINT `user_images_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_notif`
--
ALTER TABLE `user_notif`
  ADD CONSTRAINT `user_notif_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_notif_ibfk_2` FOREIGN KEY (`user_two_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
