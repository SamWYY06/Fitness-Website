-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 22, 2024 at 12:24 PM
-- Server version: 5.1.41
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `exro`
--

CREATE TABLE IF NOT EXISTS `exro` (
  `date` date NOT NULL,
  `uid` int(5) unsigned NOT NULL,
  `pushups` int(3) DEFAULT NULL,
  `situps` int(3) DEFAULT NULL,
  `squads` int(3) DEFAULT NULL,
  PRIMARY KEY (`date`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `exro`
--

INSERT INTO `exro` (`date`, `uid`, `pushups`, `situps`, `squads`) VALUES
('2024-10-15', 23456, 2, 34, 2),
('2024-10-20', 12345, 1, 2, 2),
('2024-10-22', 12345, 1, 1, 2),
('2024-10-19', 12345, 12, 23, 5),
('2024-10-21', 12345, 99, 2, 3),
('2024-10-24', 12345, 0, 0, 0),
('2024-10-23', 12345, 2, 2, 1),
('2024-10-26', 12345, 1, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `uid` int(5) unsigned NOT NULL AUTO_INCREMENT,
  `pw` varchar(30) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23457 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`uid`, `pw`) VALUES
(12345, 'qwer'),
(23456, 'asdfg');

-- --------------------------------------------------------

--
-- Table structure for table `weight`
--

CREATE TABLE IF NOT EXISTS `weight` (
  `date` date NOT NULL DEFAULT '0000-00-00',
  `uid` int(5) unsigned NOT NULL,
  `weight` double(5,2) DEFAULT NULL,
  PRIMARY KEY (`date`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `weight`
--

INSERT INTO `weight` (`date`, `uid`, `weight`) VALUES
('2024-10-15', 12345, 111.00),
('2024-10-17', 12345, 999.99),
('2024-10-16', 23456, 33.99),
('2024-10-01', 12345, 12.00),
('2024-10-28', 12345, 111.11);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
