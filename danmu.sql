-- phpMyAdmin SQL Dump
-- version 4.4.15.6
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: 2017-02-19 14:54:08
-- 服务器版本： 5.5.48-log
-- PHP Version: 5.6.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `aaa`
--

-- --------------------------------------------------------

--
-- 表的结构 `tr_admin`
--

CREATE TABLE IF NOT EXISTS `tr_admin` (
  `aid` int(11) NOT NULL,
  `user` varchar(64) NOT NULL,
  `password` varchar(128) NOT NULL,
  `salt` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

--
-- 转存表中的数据 `tr_admin`
--

INSERT INTO `tr_admin` (`aid`, `user`, `password`, `salt`) VALUES
(1, 'admin', 'cc679f0d5e7ac1dc85715084e402c2fe', 910133);

-- --------------------------------------------------------

--
-- 表的结构 `tr_control`
--

CREATE TABLE IF NOT EXISTS `tr_control` (
  `cid` int(11) NOT NULL,
  `control` text CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `time` int(11) NOT NULL,
  `vid` text NOT NULL,
  `is_true` int(11) NOT NULL COMMENT '开启'
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tr_ondo`
--

CREATE TABLE IF NOT EXISTS `tr_ondo` (
  `oid` int(11) NOT NULL,
  `gid` varchar(64) NOT NULL,
  `uid` int(11) NOT NULL,
  `hash` varchar(64) NOT NULL,
  `name` text CHARACTER SET utf8 NOT NULL,
  `uri` text CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `status` varchar(16) NOT NULL,
  `dir` varchar(200) NOT NULL,
  `complete` bigint(11) NOT NULL,
  `total` bigint(11) NOT NULL,
  `speed` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(128) NOT NULL,
  `uid_json` longtext NOT NULL,
  `precent` int(11) NOT NULL,
  `video` int(11) NOT NULL,
  `del` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- 表的结构 `tr_pre`
--

CREATE TABLE IF NOT EXISTS `tr_pre` (
  `mid` int(11) NOT NULL,
  `magnet` text NOT NULL COMMENT '动漫花园提交magnet',
  `time` int(11) NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `dir` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- 表的结构 `tr_video`
--

CREATE TABLE IF NOT EXISTS `tr_video` (
  `vid` int(11) NOT NULL,
  `or_name` text CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `name` text CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `dir` varchar(200) NOT NULL,
  `oid` int(11) NOT NULL,
  `time` int(11) NOT NULL,
  `magnet` text NOT NULL,
  `click` int(11) NOT NULL,
  `tag` text CHARACTER SET utf8 COLLATE utf8_general_mysql500_ci NOT NULL,
  `img` varchar(200) NOT NULL,
  `img_array` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tr_admin`
--
ALTER TABLE `tr_admin`
  ADD PRIMARY KEY (`aid`),
  ADD UNIQUE KEY `aid` (`aid`);

--
-- Indexes for table `tr_control`
--
ALTER TABLE `tr_control`
  ADD PRIMARY KEY (`cid`);

--
-- Indexes for table `tr_ondo`
--
ALTER TABLE `tr_ondo`
  ADD PRIMARY KEY (`oid`);

--
-- Indexes for table `tr_pre`
--
ALTER TABLE `tr_pre`
  ADD PRIMARY KEY (`mid`);

--
-- Indexes for table `tr_video`
--
ALTER TABLE `tr_video`
  ADD PRIMARY KEY (`vid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tr_admin`
--
ALTER TABLE `tr_admin`
  MODIFY `aid` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tr_control`
--
ALTER TABLE `tr_control`
  MODIFY `cid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_ondo`
--
ALTER TABLE `tr_ondo`
  MODIFY `oid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_pre`
--
ALTER TABLE `tr_pre`
  MODIFY `mid` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tr_video`
--
ALTER TABLE `tr_video`
  MODIFY `vid` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
