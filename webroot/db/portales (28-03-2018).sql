-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Dec 05, 2016 at 09:00 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "-03:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Table structure for table `canciones`
--

CREATE TABLE IF NOT EXISTS `canciones` (
  `id` int(11) NOT NULL,
  `url_yt` text NOT NULL,
  `video_id` varchar(255) NOT NULL,
  `title` varchar(50) NOT NULL,
  `artist` varchar(50) NOT NULL,
  `album` varchar(50) NOT NULL,
  `duration` varchar(50) NOT NULL,
  `year` int(4) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `downloaded` tinyint(1) DEFAULT '0',
  `fecha_publish` datetime DEFAULT NULL,
  `creado` datetime DEFAULT NULL,
  `modificado` datetime DEFAULT NULL
  `genre` varchar(50) NOT NULL,
  `filesize` varchar(15) DEFAULT NULL,
  `sample_rate` int(50) DEFAULT NULL,
  `bitrate` int(50) DEFAULT NULL,
  `dataformat` varchar(15) NOT NULL,
  `quality` varchar(50) NOT NULL,
  `url_yt_download` text NOT NULL,
  `filename` varchar(255) NOT NULL,
) ENGINE=InnoDB AUTO_INCREMENT=301665 DEFAULT CHARSET=utf8;
