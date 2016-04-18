-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- 主机: 127.7.59.2:3306
-- 生成日期: 2016-04-18 08:56:14
-- 服务器版本: 5.5.45
-- PHP 版本: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `tieba`
--

-- --------------------------------------------------------

--
-- 表的结构 `tongji_num`
--

DROP TABLE IF EXISTS `tongji_num`;
CREATE TABLE IF NOT EXISTS `tongji_num` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kw` varchar(255) DEFAULT NULL,
  `shijian` int(11) DEFAULT NULL,
  `member_num` int(20) DEFAULT NULL,
  `thread_num` int(20) DEFAULT NULL,
  `post_num` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27502 ;

-- --------------------------------------------------------

--
-- 表的结构 `tongji_result`
--

DROP TABLE IF EXISTS `tongji_result`;
CREATE TABLE IF NOT EXISTS `tongji_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(255) DEFAULT NULL,
  `kw` varchar(255) DEFAULT NULL,
  `un` varchar(255) DEFAULT NULL,
  `keyword` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7095 ;

-- --------------------------------------------------------

--
-- 表的结构 `tongji_threads`
--

DROP TABLE IF EXISTS `tongji_threads`;
CREATE TABLE IF NOT EXISTS `tongji_threads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tid` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `author` varchar(255) DEFAULT NULL,
  `fname` varchar(255) DEFAULT NULL,
  `shijian` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `tongji_tieba`
--

DROP TABLE IF EXISTS `tongji_tieba`;
CREATE TABLE IF NOT EXISTS `tongji_tieba` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kw` varchar(255) NOT NULL,
  `fid` int(11) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `shijian` varchar(255) DEFAULT NULL,
  `member_num` int(20) DEFAULT NULL,
  `thread_num` int(20) DEFAULT NULL,
  `post_num` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
