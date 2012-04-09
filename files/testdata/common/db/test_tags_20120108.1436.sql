-- phpMyAdmin SQL Dump
-- version 3.4.7.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 14, 2012 at 01:43 PM
-- Server version: 5.5.9
-- PHP Version: 5.3.8

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT=0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `psc-cms_tests`
--

-- --------------------------------------------------------

--
-- Table structure for table `test_tags`
--

DROP TABLE IF EXISTS `test_tags`;
CREATE TABLE IF NOT EXISTS `test_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `label` (`label`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `test_tags`
--

INSERT INTO `test_tags` (`id`, `label`, `created`) VALUES
(1, 'migration', '0000-00-00 00:00:00'),
(2, 'integration', '0000-00-00 00:00:00'),
(3, 'php', '0000-00-00 00:00:00'),
(4, 'audio', '0000-00-00 00:00:00'),
(5, 'favorite', '0000-00-00 00:00:00'),
(6, 'locked', '0000-00-00 00:00:00');
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
