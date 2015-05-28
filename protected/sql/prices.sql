-- phpMyAdmin SQL Dump
-- version 4.0.10.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 28, 2015 at 02:55 PM
-- Server version: 5.1.73-cll
-- PHP Version: 5.4.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `forbac_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `period` int(3) NOT NULL,
  `period_id` int(11) DEFAULT NULL,
  `type_id` int(3) NOT NULL,
  `name` float(6,2) NOT NULL,
  `protocol_id` int(10) unsigned NOT NULL,
  `portable_include` enum('1','') NOT NULL,
  `status` enum('1','') NOT NULL,
  `sort` int(10) unsigned NOT NULL,
  `portable_price` float(6,2) NOT NULL,
  `name1` float(6,2) DEFAULT NULL,
  `portable_price1` float(6,2) DEFAULT NULL,
  `name2` float(6,2) DEFAULT NULL,
  `portable_price2` float(6,2) DEFAULT NULL,
  `name3` float(6,2) DEFAULT NULL,
  `portable_price3` float(6,2) DEFAULT NULL,
  `name4` float(6,2) DEFAULT NULL,
  `portable_price4` float(6,2) DEFAULT NULL,
  `name5` float(6,2) DEFAULT NULL,
  `portable_price5` float(6,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC AUTO_INCREMENT=51 ;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `period`, `period_id`, `type_id`, `name`, `protocol_id`, `portable_include`, `status`, `sort`, `portable_price`, `name1`, `portable_price1`, `name2`, `portable_price2`, `name3`, `portable_price3`, `name4`, `portable_price4`, `name5`, `portable_price5`) VALUES
(1, 1, 2, 1, 50.00, 0, '', '1', 0, 55.00, 40.00, 45.00, 40.00, 41.00, 0.00, 0.00, 0.00, 0.00, 25.00, 30.00),
(3, 2, 3, 1, 90.00, 0, '', '1', 0, 95.00, 80.00, 85.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 50.00, 55.00),
(5, 3, 4, 1, 130.00, 0, '', '1', 0, 130.00, 120.00, 120.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 65.00, 70.00),
(7, 6, 5, 1, 250.00, 0, '1', '1', 0, 250.00, 220.00, 220.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 125.00, 130.00),
(8, 12, 6, 1, 500.00, 0, '1', '1', 0, 500.00, 450.00, 450.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 250.00, 250.00),
(9, 1, 2, 1, 60.00, 0, '', '1', 1, 65.00, 50.00, 55.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 30.00, 35.00),
(11, 2, 3, 1, 110.00, 0, '', '1', 1, 115.00, 100.00, 105.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 55.00, 60.00),
(13, 3, 4, 1, 170.00, 0, '1', '1', 1, 170.00, 160.00, 160.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 85.00, 90.00),
(14, 6, 5, 1, 300.00, 0, '1', '1', 1, 300.00, 280.00, 280.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 150.00, 150.00),
(15, 12, 6, 1, 530.00, 0, '1', '1', 1, 530.00, 500.00, 500.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 265.00, 265.00),
(16, 1, 2, 1, 70.00, 0, '', '1', 1, 75.00, 60.00, 65.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 35.00, 40.00),
(18, 2, 3, 1, 130.00, 0, '', '1', 1, 135.00, 120.00, 125.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 65.00, 70.00),
(20, 3, 4, 1, 200.00, 0, '1', '1', 1, 200.00, 180.00, 180.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 100.00, 100.00),
(21, 6, 5, 1, 350.00, 0, '1', '1', 1, 350.00, 300.00, 300.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 180.00, 180.00),
(22, 12, 6, 1, 630.00, 0, '1', '1', 1, 630.00, 600.00, 600.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 500.00, 500.00),
(23, 1, 2, 1, 80.00, 0, '', '1', 1, 85.00, 70.00, 75.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 40.00, 45.00),
(25, 2, 3, 1, 150.00, 0, '', '1', 1, 155.00, 140.00, 145.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 75.00, 80.00),
(27, 3, 4, 1, 220.00, 0, '1', '1', 1, 220.00, 210.00, 210.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 110.00, 110.00),
(28, 6, 5, 1, 400.00, 0, '1', '1', 1, 400.00, 380.00, 380.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 200.00, 200.00),
(29, 12, 6, 1, 750.00, 0, '1', '1', 1, 750.00, 700.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 350.00, 350.00),
(30, 1, 2, 2, 100.00, 0, '', '1', 1, 110.00, 90.00, 95.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 50.00, 55.00),
(32, 2, 3, 2, 180.00, 0, '', '1', 1, 190.00, 170.00, 175.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 90.00, 95.00),
(34, 3, 4, 2, 260.00, 0, '1', '1', 1, 260.00, 250.00, 250.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 130.00, 130.00),
(35, 6, 5, 2, 500.00, 0, '1', '1', 1, 500.00, 450.00, 450.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 250.00, 250.00),
(36, 12, 6, 2, 900.00, 0, '1', '1', 1, 900.00, 800.00, 800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 450.00, 450.00),
(37, 1, 2, 3, 100.00, 0, '', '1', 1, 110.00, 90.00, 95.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 50.00, 55.00),
(39, 2, 3, 3, 180.00, 0, '', '1', 1, 190.00, 170.00, 175.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 90.00, 95.00),
(41, 3, 4, 3, 260.00, 0, '1', '1', 1, 260.00, 250.00, 250.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 130.00, 130.00),
(42, 6, 5, 3, 500.00, 0, '1', '1', 1, 500.00, 450.00, 450.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 250.00, 250.00),
(43, 12, 6, 3, 900.00, 0, '1', '1', 1, 900.00, 800.00, 800.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 450.00, 450.00),
(44, 1, 2, 4, 180.00, 0, '', '1', 1, 190.00, 170.00, 175.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 90.00, 95.00),
(46, 2, 3, 4, 350.00, 0, '', '1', 1, 360.00, 340.00, 345.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 185.00, 190.00),
(48, 3, 4, 4, 500.00, 0, '1', '1', 1, 500.00, 480.00, 480.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 250.00, 250.00),
(49, 6, 5, 4, 900.00, 0, '1', '1', 1, 900.00, 850.00, 850.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 450.00, 450.00),
(50, 12, 6, 4, 1500.00, 0, '1', '1', 1, 1500.00, 1300.00, 1300.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 750.00, 750.00);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;