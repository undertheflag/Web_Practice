-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 2018-04-06 12:31:35
-- 服务器版本： 10.1.31-MariaDB
-- PHP Version: 7.2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ijdb`
--

-- --------------------------------------------------------

--
-- 表的结构 `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `permissions` int(63) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `author`
--

INSERT INTO `author` (`id`, `name`, `email`, `password`, `permissions`) VALUES
(1, 'admin', 'admin@chiahsiang.xyz', '$2y$10$b38GLupskzrHkPGt/U5Kte.C/ZtuKk5gpHLZt0N.9DQkKJzQ2Gf9u', 63),
(2, 'zjx', 'maigebaoer@qq.com', '$2y$10$ZvXvRm70Iw7MN/Jm71yW/OUao0Pu6vpHp7vPCTQQ3yidRUHaKSo4u', 0);

-- --------------------------------------------------------

--
-- 表的结构 `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(1, 'Programming Jokes'),
(2, 'One Liners');

-- --------------------------------------------------------

--
-- 表的结构 `joke`
--

CREATE TABLE `joke` (
  `id` int(11) NOT NULL,
  `joketext` text,
  `jokedate` date NOT NULL,
  `authorId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 `joke`
--

INSERT INTO `joke` (`id`, `joketext`, `jokedate`, `authorId`) VALUES
(1, 'How many programmers does it take to screw in a lightbulb? None, it\'s a hardware problem.', '2017-04-01', 1),
(2, 'Why did the programmer quit his job? He didn\'t get arrays', '2017-04-01', 1),
(3, 'Why was the empty array stuck outside? It didn\'t have any keys', '2017-04-01', 2),
(4, 'How do functions break up? They stop calling each other', '2017-08-09', 2),
(5, 'How do you tell HTML from HTML5? Try it out in Internet Explorer. Did it work? No? It\'s HTML5', '2017-08-09', 2),
(6, 'You don\'t need any training to be a litter picker, you pick it up as you go', '2017-08-09', 2);

-- --------------------------------------------------------

--
-- 表的结构 `joke_category`
--

CREATE TABLE `joke_category` (
  `jokeId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- 转存表中的数据 `joke_category`
--

INSERT INTO `joke_category` (`jokeId`, `categoryId`) VALUES
(1, 1),
(1, 2),
(2, 1),
(3, 1),
(4, 2),
(5, 1),
(6, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `joke`
--
ALTER TABLE `joke`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `joke_category`
--
ALTER TABLE `joke_category`
  ADD PRIMARY KEY (`jokeId`,`categoryId`);

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- 使用表AUTO_INCREMENT `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `joke`
--
ALTER TABLE `joke`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
