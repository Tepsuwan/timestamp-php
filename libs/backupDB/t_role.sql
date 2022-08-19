-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 31, 2015 at 04:40 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bz_timestamp`
--

-- --------------------------------------------------------

--
-- Table structure for table `t_role`
--

CREATE TABLE IF NOT EXISTS `t_role` (
  `role_id` varchar(20) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_discription` text NOT NULL,
  `role_key` int(1) NOT NULL,
  `create_uid` varchar(20) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_uid` varchar(20) NOT NULL,
  `update_date` datetime NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_role`
--

INSERT INTO `t_role` (`role_id`, `role_name`, `role_discription`, `role_key`, `create_uid`, `create_date`, `update_uid`, `update_date`) VALUES
('55d3f18cd06f7', 'Administrator', '', 1, '00072', '2015-08-19 10:01:32', '00072', '2015-08-19 10:10:34'),
('55d3f371153b2', 'Accounting', '', 2, '00072', '2015-08-19 10:09:37', '00072', '2015-08-19 14:00:46'),
('55d3f3978049b', 'Leader FP', 'Leader Floorplan', 3, '00072', '2015-08-19 10:10:15', '00072', '2015-08-19 10:11:28'),
('55d3f3cbbcba8', 'Leader PE', 'Leader Photo edit', 4, '00072', '2015-08-19 10:11:07', '00072', '2015-08-19 10:11:38'),
('55d6e95e578c5', 'Leader 3D', '', 5, '00072', '2015-08-21 16:03:26', '', '0000-00-00 00:00:00'),
('55d6e971079fe', 'Leader CA', '', 6, '00072', '2015-08-21 16:03:45', '', '0000-00-00 00:00:00'),
('55dad51d7c8fe', 'Leader SU', '', 7, '00072', '2015-08-24 15:26:05', '', '0000-00-00 00:00:00'),
('55dbd8dec5516', 'Leader IT', '', 8, '00072', '2015-08-25 09:54:22', '', '0000-00-00 00:00:00');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
