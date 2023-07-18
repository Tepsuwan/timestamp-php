-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 159.65.142.2
-- Generation Time: May 01, 2023 at 08:40 AM
-- Server version: 5.7.41-0ubuntu0.18.04.1
-- PHP Version: 7.4.3-4ubuntu2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `baezenic_people`
--

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `session_id` varchar(255) COLLATE utf8_bin NOT NULL,
  `expires` int(11) UNSIGNED NOT NULL,
  `data` text COLLATE utf8_bin
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

--
-- Dumping data for table `sessions`
--
-- --------------------------------------------------------

--
-- Table structure for table `t_people`
--

CREATE TABLE `t_people` (
  `id` varchar(11) NOT NULL,
  `sort_order` int(10) NOT NULL,
  `titlename` varchar(20) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `NickName` varchar(225) NOT NULL,
  `ShortName` varchar(225) NOT NULL,
  `password` text CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `Competence` varchar(255) NOT NULL,
  `Role` varchar(255) NOT NULL,
  `Team` varchar(100) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `Main_Team` varchar(10) NOT NULL,
  `Experience` text NOT NULL,
  `Started` date NOT NULL,
  `Stopped` date NOT NULL,
  `Education` text NOT NULL,
  `Work` text NOT NULL,
  `WorkinB` varchar(225) NOT NULL,
  `Responses` varchar(225) NOT NULL,
  `Language` varchar(255) NOT NULL,
  `Phone` varchar(255) NOT NULL,
  `Skype` varchar(255) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `bdate` date NOT NULL,
  `zone` varchar(10) NOT NULL COMMENT 'THPTY=Pattaya,Vn=Vietnam,NO=Norway,THBKK=Bangkok\n\nzone is country_code',
  `Country` varchar(255) NOT NULL,
  `Office` varchar(255) NOT NULL,
  `sub_office` varchar(30) NOT NULL COMMENT 'HN=Hanoi,HCM=Ho Chi Minh,PTY=Pattaya,BKK=Bangkok',
  `Namepic` varchar(255) NOT NULL,
  `info` varchar(5) NOT NULL,
  `is_enabled` varchar(1) NOT NULL,
  `workday` varchar(20) NOT NULL,
  `status` varchar(1) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL COMMENT 'N=new staff, A=operator,Y=ออก,F=free',
  `is_admin` varchar(1) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL,
  `position_role_id` varchar(30) NOT NULL,
  `people_category` varchar(45) DEFAULT NULL,
  `create_id` varchar(6) NOT NULL,
  `date_create` datetime NOT NULL,
  `modify_id` varchar(6) NOT NULL,
  `date_modify` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `t_people`
--

INSERT INTO `t_people` (`id`, `sort_order`, `titlename`, `Name`, `NickName`, `ShortName`, `password`, `Competence`, `Role`, `Team`, `Main_Team`, `Experience`, `Started`, `Stopped`, `Education`, `Work`, `WorkinB`, `Responses`, `Language`, `Phone`, `Skype`, `Email`, `bdate`, `zone`, `Country`, `Office`, `sub_office`, `Namepic`, `info`, `is_enabled`, `workday`, `status`, `is_admin`, `position_role_id`, `people_category`, `create_id`, `date_create`, `modify_id`, `date_modify`) VALUES
('00101', 8, 'Mr.', 'Poonsak Muangwang', 'Tom', 'MTP', 'd9d160edbe3482860c1fd1b09a14ef18', 'Web Prograning   PHP  MVC CI Nodejs Vuejs laravel Mysql mongogDB ', 'Web Design', 'WEB', 'DEV', '1.System Administrator 2 year Pattaya Sea Sand Sun Resort and Spa \r\n2.System Administrator 6 mount MRI Co,. ltd', '2013-07-30', '0000-00-00', 'The Redemptorist Vocational School for People with Disabilities.', '2 year Pattaya Sea Sand Sun Resort and Spa                                                                                      ', ' Web programing', '', 'Thai,English', '+6685-4382834', 'Poonsak M.', 'tom@baezeni.com', '1989-10-09', 'TH', 'Thailand', 'Bangkok', 'PTY', '201808211405561534835157.jpg', '', '1', ' 8.00', 'A', 'Y', '', '59e438a2ea2bd', '', '0000-00-00 00:00:00', '00101', '2020-12-23 09:05:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`session_id`);

--
-- Indexes for table `t_people`
--
ALTER TABLE `t_people`
  ADD PRIMARY KEY (`id`),
  ADD KEY `INDEX` (`people_category`,`position_role_id`,`is_admin`,`Office`,`sort_order`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
