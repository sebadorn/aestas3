-- phpMyAdmin SQL Dump
-- version 3.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 13, 2014 at 08:07 PM
-- Server version: 5.5.25a
-- PHP Version: 5.4.4

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `aestas3`
--

-- --------------------------------------------------------

--
-- Table structure for table `ae_categories`
--

CREATE TABLE IF NOT EXISTS `ae_categories` (
  `ca_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ca_title` varchar(255) NOT NULL,
  `ca_permalink` varchar(255) NOT NULL,
  `ca_parent` int(10) unsigned DEFAULT NULL,
  `ca_status` enum('available','trash') NOT NULL DEFAULT 'available',
  PRIMARY KEY (`ca_id`),
  KEY `ca_permalink` (`ca_permalink`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ae_commentfilters`
--

CREATE TABLE IF NOT EXISTS `ae_commentfilters` (
  `cf_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cf_name` varchar(80) NOT NULL,
  `cf_target` enum('ip','name','email','url','content') NOT NULL,
  `cf_match` varchar(255) NOT NULL,
  `cf_action` enum('approve','unapprove','spam','trash','drop') NOT NULL DEFAULT 'spam',
  `cf_status` enum('active','inactive') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`cf_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ae_comments`
--

CREATE TABLE IF NOT EXISTS `ae_comments` (
  `co_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `co_ip` tinytext NOT NULL,
  `co_post` int(11) NOT NULL,
  `co_user` smallint(5) unsigned DEFAULT NULL,
  `co_name` varchar(255) DEFAULT NULL,
  `co_email` varchar(255) DEFAULT NULL,
  `co_url` varchar(255) DEFAULT NULL,
  `co_datetime` datetime NOT NULL,
  `co_content` mediumtext NOT NULL,
  `co_status` enum('approved','spam','trash','unapproved') NOT NULL DEFAULT 'unapproved',
  PRIMARY KEY (`co_id`),
  KEY `co_post` (`co_post`,`co_status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ae_media`
--

CREATE TABLE IF NOT EXISTS `ae_media` (
  `m_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `m_name` varchar(255) NOT NULL,
  `m_datetime` datetime NOT NULL,
  `m_type` varchar(80) NOT NULL,
  `m_meta` varchar(255) DEFAULT NULL,
  `m_user` smallint(5) unsigned NOT NULL,
  `m_status` enum('available','trash') NOT NULL DEFAULT 'available',
  PRIMARY KEY (`m_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ae_pages`
--

CREATE TABLE IF NOT EXISTS `ae_pages` (
  `pa_id` int(11) NOT NULL AUTO_INCREMENT,
  `pa_title` text NOT NULL,
  `pa_permalink` varchar(255) NOT NULL,
  `pa_content` longtext,
  `pa_datetime` datetime NOT NULL,
  `pa_edit` datetime DEFAULT NULL,
  `pa_user` smallint(6) NOT NULL,
  `pa_comments` enum('open','closed','disabled') NOT NULL DEFAULT 'open',
  `pa_status` enum('published','draft','trash') NOT NULL DEFAULT 'draft',
  PRIMARY KEY (`pa_id`),
  KEY `pa_permalink` (`pa_permalink`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ae_posts`
--

CREATE TABLE IF NOT EXISTS `ae_posts` (
  `po_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `po_title` text NOT NULL,
  `po_permalink` varchar(255) NOT NULL,
  `po_content` longtext,
  `po_datetime` datetime NOT NULL,
  `po_edit` datetime DEFAULT NULL,
  `po_tags` text,
  `po_user` smallint(6) NOT NULL,
  `po_comments` enum('open','closed','disabled') NOT NULL DEFAULT 'open',
  `po_status` enum('published','draft','trash') NOT NULL DEFAULT 'draft',
  PRIMARY KEY (`po_id`),
  KEY `po_permalink` (`po_permalink`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `ae_posts2categories`
--

CREATE TABLE IF NOT EXISTS `ae_posts2categories` (
  `pc_post` int(11) NOT NULL,
  `pc_category` int(11) NOT NULL,
  UNIQUE KEY `pc_post` (`pc_post`,`pc_category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ae_settings`
--

CREATE TABLE IF NOT EXISTS `ae_settings` (
  `s_key` varchar(255) NOT NULL,
  `s_value` varchar(255) NOT NULL,
  PRIMARY KEY (`s_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ae_users`
--

CREATE TABLE IF NOT EXISTS `ae_users` (
  `u_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `u_pwd` varchar(80) NOT NULL,
  `u_name_intern` varchar(255) NOT NULL,
  `u_name_extern` varchar(255) DEFAULT NULL,
  `u_permalink` varchar(255) NOT NULL,
  `u_status` enum('active','suspended') NOT NULL DEFAULT 'suspended',
  PRIMARY KEY (`u_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;