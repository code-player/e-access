-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 31, 2015 at 09:20 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `eaccess`
--

-- --------------------------------------------------------

--
-- Table structure for table `sem_iii`
--

CREATE TABLE IF NOT EXISTS `sem_iii` (
`stu_id` int(11) NOT NULL,
  `enroll` varchar(32) NOT NULL,
  `batch` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `sem` varchar(4) NOT NULL,
  `branch` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sem_iv`
--

CREATE TABLE IF NOT EXISTS `sem_iv` (
`stu_id` int(11) NOT NULL,
  `enroll` varchar(32) NOT NULL,
  `batch` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `sem` varchar(4) NOT NULL,
  `branch` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sem_v`
--

CREATE TABLE IF NOT EXISTS `sem_v` (
`stu_id` int(11) NOT NULL,
  `enroll` varchar(32) NOT NULL,
  `batch` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `sem` varchar(4) NOT NULL,
  `branch` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sem_vi`
--

CREATE TABLE IF NOT EXISTS `sem_vi` (
`stu_id` int(11) NOT NULL,
  `enroll` varchar(32) NOT NULL,
  `batch` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `sem` varchar(4) NOT NULL,
  `branch` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sem_vii`
--

CREATE TABLE IF NOT EXISTS `sem_vii` (
`stu_id` int(11) NOT NULL,
  `enroll` varchar(32) NOT NULL,
  `batch` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `sem` varchar(4) NOT NULL,
  `branch` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `sem_viii`
--

CREATE TABLE IF NOT EXISTS `sem_viii` (
`stu_id` int(11) NOT NULL,
  `enroll` varchar(32) NOT NULL,
  `batch` int(11) NOT NULL,
  `first_name` varchar(32) NOT NULL,
  `last_name` varchar(32) NOT NULL,
  `email` varchar(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `sem` varchar(4) NOT NULL,
  `branch` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subject_details`
--

CREATE TABLE IF NOT EXISTS `subject_details` (
`id` int(11) NOT NULL,
  `sem` varchar(4) NOT NULL,
  `branch` varchar(32) NOT NULL,
  `course_name` text NOT NULL,
  `course_code` text NOT NULL,
  `credits` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`user_id` int(11) NOT NULL,
  `username` varchar(32) NOT NULL,
  `department` varchar(32) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `active` int(11) NOT NULL DEFAULT '0',
  `email_code` varchar(32) NOT NULL,
  `user_desc` varchar(200) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `department`, `first_name`, `last_name`, `email`, `password`, `phone`, `active`, `email_code`, `user_desc`) VALUES
(1, 'dummy', 'Information Technology', 'dummy', 'user', 'dummy@gmail.com', '5f4dcc3b5aa765d61d8327deb882cf99', '0987654321', 1, '', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sem_iii`
--
ALTER TABLE `sem_iii`
 ADD PRIMARY KEY (`stu_id`);

--
-- Indexes for table `sem_iv`
--
ALTER TABLE `sem_iv`
 ADD PRIMARY KEY (`stu_id`);

--
-- Indexes for table `sem_v`
--
ALTER TABLE `sem_v`
 ADD PRIMARY KEY (`stu_id`);

--
-- Indexes for table `sem_vi`
--
ALTER TABLE `sem_vi`
 ADD PRIMARY KEY (`stu_id`);

--
-- Indexes for table `sem_vii`
--
ALTER TABLE `sem_vii`
 ADD PRIMARY KEY (`stu_id`);

--
-- Indexes for table `sem_viii`
--
ALTER TABLE `sem_viii`
 ADD PRIMARY KEY (`stu_id`);

--
-- Indexes for table `subject_details`
--
ALTER TABLE `subject_details`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`user_id`), ADD KEY `user_id` (`user_id`), ADD KEY `user_id_2` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `sem_iii`
--
ALTER TABLE `sem_iii`
MODIFY `stu_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sem_iv`
--
ALTER TABLE `sem_iv`
MODIFY `stu_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sem_v`
--
ALTER TABLE `sem_v`
MODIFY `stu_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sem_vi`
--
ALTER TABLE `sem_vi`
MODIFY `stu_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sem_vii`
--
ALTER TABLE `sem_vii`
MODIFY `stu_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `sem_viii`
--
ALTER TABLE `sem_viii`
MODIFY `stu_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `subject_details`
--
ALTER TABLE `subject_details`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
