-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 20, 2023 at 05:19 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `activity2_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `post`
--

CREATE TABLE `post` (
  `post_id` int(11) NOT NULL,
  `details` text NOT NULL,
  `date_posted` date NOT NULL,
  `time_posted` time NOT NULL,
  `date_edited` date DEFAULT NULL,
  `time_edited` time DEFAULT NULL,
  `privacy` enum('public','private') NOT NULL,
  `postedby` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `post`
--

INSERT INTO `post` (`post_id`, `details`, `date_posted`, `time_posted`, `date_edited`, `time_edited`, `privacy`, `postedby`) VALUES
(1, 'test posting publicpost edited test', '2023-11-20', '01:32:51', '2023-11-20', '11:53:50', 'public', 4),
(2, 'private post!!!', '2023-11-20', '01:34:20', NULL, NULL, 'private', 4),
(3, 'updated to private', '2023-11-20', '02:11:37', '2023-11-20', '11:49:55', 'private', 4),
(4, 'another public posts', '2023-11-20', '02:14:29', '2023-11-20', '11:50:33', 'private', 4),
(5, 'take 1 of public post with included alertify!', '2023-11-20', '02:17:28', NULL, NULL, 'public', 4),
(24, 'edited to private', '2023-11-20', '09:51:13', '2023-11-20', '11:51:07', 'private', 4),
(26, 'test post public', '2023-11-20', '12:15:39', NULL, NULL, 'public', 5);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `username`, `password`) VALUES
(4, 'gab', '$2y$10$FvIJrhEP66yE59WVTcBqnuRgx4wGv3ZxvWU8LOqqV6E5MkpkXoYVa'),
(5, 'anonymous', '$2y$10$fHttWF92VDuxTeKThOxG6.rkiLt0R5otCgQlIpaT7/mEDYJEIZrRG');

--
-- Triggers `user`
--
DELIMITER $$
CREATE TRIGGER `delete_related_posts` BEFORE DELETE ON `user` FOR EACH ROW BEGIN
    DELETE FROM post WHERE postedby = OLD.user_id;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `post`
--
ALTER TABLE `post`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `fk_postedby_user` (`postedby`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `post`
--
ALTER TABLE `post`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_postedby_user` FOREIGN KEY (`postedby`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
