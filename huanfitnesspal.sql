-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 28, 2024 at 04:48 AM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `huanfitnesspal`
--

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE IF NOT EXISTS `consultations` (
  `consultationID` int(11) NOT NULL AUTO_INCREMENT,
  `preferredDate` date NOT NULL,
  `preferredTime` time NOT NULL,
  `notes` mediumtext NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`consultationID`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`preferredDate`, `preferredTime`, `consultantName`, `notes`, `consultationID`) VALUES
('0000-00-00', '00:00:00', '', '', 0),
('2024-10-27', '00:00:00', '', '', 0),
('0000-00-00', '00:00:00', '', '', 0),
('2024-10-18', '16:23:00', '', '', 0),
('2024-10-29', '00:34:00', '', '', 0),
('2024-10-30', '18:00:00', '', 'asdjmaslkjdoad', 0),
('2024-10-05', '00:50:00', '', 'asdas', 0);

-- --------------------------------------------------------

--
-- Table structure for table `exercises`
--

CREATE TABLE IF NOT EXISTS `exercises` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `starred` tinyint(1) DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `exercises`
--

INSERT INTO `exercises` (`id`, `name`, `starred`, `created_at`) VALUES
(1, 'Bench Press', 0, '2024-10-25 19:38:53'),
(2, 'Deadlift', 0, '2024-10-25 19:38:53'),
(3, 'Squat', 1, '2024-10-25 19:38:53'),
(4, 'Pull-up', 0, '2024-10-25 19:38:53'),
(5, 'Push-up', 0, '2024-10-25 19:38:53'),
(6, 'Shoulder Press', 1, '2024-10-25 19:38:53'),
(7, 'Bicep Curls', 0, '2024-10-25 19:38:53'),
(8, 'Stretching', 0, '2024-10-26 07:55:50'),
(9, 'Running', 1, '2024-10-26 07:56:15'),
(10, 'Plank', 0, '2024-10-27 07:46:19'),
(11, 'Jumping Jacks', 1, '2024-10-27 14:39:49');

-- --------------------------------------------------------

--
-- Table structure for table `exercise_sessions`
--

CREATE TABLE IF NOT EXISTS `exercise_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `exercise_id` int(11) NOT NULL,
  `session_date` date NOT NULL,
  `session_time` time NOT NULL,
  `duration` int(11) NOT NULL,
  `completed` tinyint(1) DEFAULT '0',
  `completion_time` datetime DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `exercise_id` (`exercise_id`),
  KEY `idx_user_completed_date` (`user_id`,`completed`,`session_date`),
  KEY `idx_date_user_completed` (`session_date`,`user_id`,`completed`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `exercise_sessions`
--

INSERT INTO `exercise_sessions` (`id`, `user_id`, `exercise_id`, `session_date`, `session_time`, `duration`, `completed`, `completion_time`, `last_updated`) VALUES
(1, 0, 9, '2024-10-27', '19:30:00', 30, 0, NULL, '2024-10-27 07:37:18'),
(2, 1, 1, '2024-10-27', '18:50:00', 15, 0, NULL, '2024-10-27 14:38:27'),
(3, 1, 3, '2024-10-27', '18:00:00', 15, 0, NULL, '2024-10-27 14:38:26'),
(4, 2, 9, '2024-10-27', '23:00:00', 15, 0, NULL, '2024-10-27 14:40:41'),
(5, 1, 11, '2024-10-28', '06:20:00', 15, 0, NULL, '2024-10-27 16:19:51');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `paymentID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `consultationID` int(11) NOT NULL,
  `paymentFor` text NOT NULL,
  `paymentMethod` text NOT NULL,
  `paymentAmount` text NOT NULL,
  `paymentDate` date NOT NULL,
  PRIMARY KEY (`paymentID`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  FOREIGN KEY (`consultationID`) REFERENCES `consultations`(`consultationID`) ON DELETE CASCADE ON UPDATE CASCADE
);


--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`paymentID`, `memberID`, `consultationID`, `paymentFor`, `paymentMethod`, `paymentAmount`, `paymentDate`) VALUES
(1, 0, 0, 'Consultation', 'Touch n Go', 'RM 20', '2024-10-18'),
(2, 0, 0, 'Consultation', 'Bank Transfer', 'RM 20', '2024-10-18'),
(3, 0, 0, 'Consultation', 'Credit Card', 'RM 20', '2024-10-18'),
(4, 0, 0, 'Consultation', 'Credit Card', 'RM 20', '2024-10-18'),
(5, 0, 0, 'Consultation', 'Credit Card', 'RM 20', '2024-10-18'),
(6, 0, 0, 'Consultation', 'Credit Card', 'RM 20', '2024-10-18'),
(7, 0, 0, 'Consultation', 'Credit Card', 'RM 20', '2024-10-18'),
(8, 0, 0, 'Consultation', 'Credit Card', 'RM 20', '2024-10-18'),
(9, 0, 0, 'Gold Membership', 'Bank Transfer', 'RM 100', '2024-10-18'),
(10, 0, 0, 'Consultation', '', 'RM 20', '2024-10-18'),
(11, 0, 0, 'Consultation', 'Bank Transfer', 'RM 20', '2024-10-18'),
(12, 0, 0, 'Consultation', 'Touch n Go', 'RM 20', '2024-10-18'),
(13, 0, 0, 'Consultation', 'Credit Card', 'RM 20', '2024-10-18'),
(14, 0, 0, 'Consultation', 'Bank Transfer', 'RM 20', '2024-10-26'),
(15, 0, 0, 'Gold Membership', 'Credit Card', 'RM 100', '2024-10-26'),
(16, 0, 0, 'Bronze Membership', 'Bank Transfer', 'RM 50', '2024-10-26'),
(17, 0, 0, 'Gold Membership', 'Bank Transfer', 'RM 100', '2024-10-26'),
(18, 0, 0, 'Consultation', 'Bank Transfer', 'RM 20', '2024-10-27'),
(19, 0, 0, 'Consultation', 'Bank Transfer', 'RM 20', '2024-10-27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `gender` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `gender`) VALUES
(1, 'mykrizx', 'krizx@gmail.com', '123456', 'user', 'female'),
(3, 'syahminerz', 'syah@gmail.com', '123456', 'user', 'male'),
(2, 'test', 'test@gmail.com', '123456', 'user', 'male'),
(4, 'admin', 'admin@gmail.com', 'admin123', 'admin', 'male');

-- --------------------------------------------------------

--
-- Table structure for table `water`
--

CREATE TABLE IF NOT EXISTS `water` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `amount` int(10) NOT NULL,
  `entry_time` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Dumping data for table `water`
--

INSERT INTO `water` (`id`, `amount`, `entry_time`, `user_id`) VALUES
(1, 300, '2024-10-23 01:21:20', 0),
(2, 200, '2024-10-23 01:21:20', 0),
(3, 400, '2024-10-23 01:21:22', 0),
(9, 400, '2024-10-23 15:33:11', 0),
(10, 450, '2024-10-23 15:33:12', 0),
(11, 500, '2024-10-23 15:33:13', 0),
(12, 250, '2024-10-24 16:50:42', 0),
(13, 150, '2024-10-24 16:50:43', 0),
(14, 500, '2024-10-24 16:50:44', 0),
(15, 550, '2024-10-24 16:50:45', 0),
(16, 200, '2024-10-24 16:50:47', 0),
(17, 400, '2024-10-24 16:50:48', 0),
(18, 200, '2024-10-26 07:46:43', 0),
(19, 300, '2024-10-26 15:55:24', 0),
(20, 450, '2024-10-26 15:55:25', 0),
(21, 550, '2024-10-26 15:55:26', 0),
(22, 150, '2024-10-26 18:29:28', 0),
(23, 200, '2024-10-27 05:41:08', 0),
(24, 250, '2024-10-27 05:53:10', 0),
(25, 300, '2024-10-27 05:53:11', 0),
(30, 250, '2024-10-27 07:09:01', 0),
(31, 250, '2024-10-27 15:27:03', 1),
(32, 400, '2024-10-27 15:27:05', 1),
(33, 150, '2024-10-27 22:42:23', 2),
(34, 200, '2024-10-27 22:42:24', 2),
(35, 350, '2024-10-27 22:42:25', 2),
(36, 150, '2024-10-28 00:19:47', 1),
(37, 250, '2024-10-28 00:19:47', 1),
(38, 300, '2024-10-28 00:22:55', 3),
(39, 450, '2024-10-28 00:22:57', 3);

-- --------------------------------------------------------

--
-- Table structure for table `weight_log`
--

CREATE TABLE IF NOT EXISTS `weight_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `entry_date` date NOT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_user_date` (`user_id`,`entry_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `weight_log`
--

INSERT INTO `weight_log` (`id`, `user_id`, `weight`, `entry_date`, `last_updated`) VALUES
(3, 1, '47.60', '2024-10-27', '2024-10-27 17:34:29'),
(5, 2, '47.70', '2024-10-27', '2024-10-27 14:41:25'),
(6, 3, '49.00', '2024-10-27', '2024-10-27 16:22:26');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
