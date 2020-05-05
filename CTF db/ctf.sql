-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 23, 2017 at 07:48 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ctf`
--
CREATE DATABASE IF NOT EXISTS `ctf` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `ctf`;

-- --------------------------------------------------------

--
-- Table structure for table `answered`
--

CREATE TABLE IF NOT EXISTS `answered` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `qsnid` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_user_qsn` (`userid`,`qsnid`),
  KEY `fuser` (`userid`),
  KEY `fqsn` (`qsnid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=46 ;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `qsn` varchar(500) NOT NULL,
  `ans` varchar(255) NOT NULL,
  `score` int(11) NOT NULL DEFAULT '20',
  `hint` varchar(200) NOT NULL,
  `link` varchar(400) NOT NULL,
  `filepath` varchar(400) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `qsn`, `ans`, `score`, `hint`, `link`, `filepath`) VALUES
(1, 'Suspendisse interdum varius iaculis. Nullam eget arcu eleifend, pulvinar dolor in, gravida velit. Aenean nunc nisl, fringilla sed aliquet at, mattis ut sem?', 'Ans1', 20, 'hint1', '', ''),
(2, 'Aliquam rhoncus mi nunc, quis mollis enim ullamcorper a. Duis dictum sed lorem in lobortis. Phasellus in odio et augue scelerisque convallis. Ut nec metus consequat, sollicitudin diam vitae, fringilla risus?', 'Ans2', 20, 'hint2', 'https://www.google.com', ''),
(3, 'Cras ut lectus sagittis, fermentum neque vel, varius urna. Praesent dignissim scelerisque lorem ut pulvinar?', 'Ans3', 40, 'hint3', '', 'filepath2'),
(4, 'Vestibulum ante ipsum primis in faucibus orci luctus et ultrices posuere cubilia Curae; Praesent quis posuere mi, nec congue lorem. Quisque massa nibh, laoreet sit amet pulvinar sed, ultricies suscipit est?', 'Ans4', 70, 'hint4', 'https://www.azeesoft.com', 'filepath4');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fullname` varchar(60) NOT NULL,
  `fullname2` varchar(60) NOT NULL,
  `kriya_id` varchar(50) NOT NULL,
  `kriya_id2` varchar(50) NOT NULL,
  `username` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL,
  `start_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `fullname2`, `kriya_id`, `kriya_id2`, `username`, `password`, `start_time`) VALUES
(12, 'a', 'das', 'asd', 'asd', 'a', 'U2nEpCnfdxMB4MktPiARnj6MUq57CebEzk3U4C+tNpZYxFaXQMEE1z7G82e6+44Qpoc=:lgxQJwxZKOKqNJxM6v6WIzVvzsrv3t+3G5+xCnDZC32ak2zA7VhqKwsz3vRgtp9PWQ0=', '2017-02-23 12:15:47');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answered`
--
ALTER TABLE `answered`
  ADD CONSTRAINT `fqsn_con` FOREIGN KEY (`qsnid`) REFERENCES `questions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fuser_con` FOREIGN KEY (`userid`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
