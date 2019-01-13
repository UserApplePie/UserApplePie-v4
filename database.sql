-- phpMyAdmin SQL Dump
-- version 4.2.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 31, 2016 at 06:31 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

-- UserApplePie v4.2.1

-- Instructions
-- Import this file to your mySQL database

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `uap4`
--

-- --------------------------------------------------------

--
-- Table structure for table `uap4_activitylog`
--

CREATE TABLE IF NOT EXISTS `uap4_activitylog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `additionalinfo` varchar(500) NOT NULL DEFAULT 'none',
  `ip` varchar(39) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_attempts`
--

CREATE TABLE IF NOT EXISTS `uap4_attempts` (
  `ip` varchar(39) DEFAULT NULL,
  `count` int(11) DEFAULT NULL,
  `expiredate` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_groups`
--

CREATE TABLE IF NOT EXISTS `uap4_groups` (
  `groupID` int(11) NOT NULL AUTO_INCREMENT,
  `groupName` varchar(150) DEFAULT NULL,
  `groupDescription` varchar(255) DEFAULT NULL,
  `groupFontColor` varchar(20) DEFAULT NULL,
  `groupFontWeight` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`groupID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_sessions`
--

CREATE TABLE IF NOT EXISTS `uap4_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `username` varchar(30) DEFAULT NULL,
  `hash` varchar(32) DEFAULT NULL,
  `expiredate` datetime DEFAULT NULL,
  `ip` varchar(39) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_users`
--

CREATE TABLE IF NOT EXISTS `uap4_users` (
  `userID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `password` varchar(128) DEFAULT NULL,
  `pass_change_timestamp` datetime DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `gender` varchar(8) DEFAULT NULL,
  `userImage` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `aboutme` text DEFAULT NULL,
  `signature` text DEFAULT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT '0',
  `activekey` varchar(15) NOT NULL DEFAULT '0',
  `resetkey` varchar(15) NOT NULL DEFAULT '0',
  `LastLogin` datetime DEFAULT NULL,
  `privacy_massemail` varchar(5) NOT NULL DEFAULT 'true',
  `privacy_pm` varchar(5) NOT NULL DEFAULT 'true',
  `SignUp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_users_groups`
--

CREATE TABLE IF NOT EXISTS `uap4_users_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `groupID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_users_online`
--

CREATE TABLE IF NOT EXISTS `uap4_users_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) DEFAULT NULL,
  `lastAccess` datetime DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `lastAccess` (`lastAccess`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_sitelogs`
--

CREATE TABLE IF NOT EXISTS `uap4_sitelogs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `membername` varchar(255) DEFAULT NULL,
  `refer` text,
  `useragent` text,
  `cfile` varchar(255) DEFAULT NULL,
  `uri` text,
  `ipaddy` varchar(255) DEFAULT NULL,
  `server` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 PACK_KEYS=0 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_users_groups`
-- Sets first user as Admin
--

INSERT INTO `uap4_users_groups` (`userID`, `groupID`) VALUES
(1, 4);

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_groups`
--

INSERT INTO `uap4_groups` (`groupID`, `groupName`, `groupDescription`, `groupFontColor`, `groupFontWeight`) VALUES
(1, 'New Member', 'Site Members that Recently Registered to the Web Site.', 'GREEN', 'Bold'),
(2, 'Member', 'Site Members That Have Been Here a While.', 'BLUE', 'BOLD'),
(3, 'Moderator', 'Site Members That Have a Little Extra Privilege on the Site.', 'ORANGE', 'BOLD'),
(4, 'Administrator', 'Site Members That Have Full Access To The Site.', 'RED', 'BOLD');

-- --------------------------------------------------------

--
-- Table structure for table `uap4_messages`
--

CREATE TABLE IF NOT EXISTS `uap4_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `to_userID` int(11) DEFAULT NULL,
  `from_userID` int(11) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `date_read` datetime DEFAULT NULL,
  `date_sent` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `to_delete` varchar(5) NOT NULL DEFAULT 'false',
  `from_delete` varchar(5) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_cat`
--

CREATE TABLE IF NOT EXISTS `uap4_forum_cat` (
  `forum_id` int(20) NOT NULL AUTO_INCREMENT,
  `forum_name` varchar(255) DEFAULT NULL,
  `forum_title` varchar(255) DEFAULT NULL,
  `forum_cat` varchar(255) DEFAULT NULL,
  `forum_des` text DEFAULT NULL,
  `forum_perm` int(20) NOT NULL DEFAULT '1',
  `forum_order_title` int(11) NOT NULL DEFAULT '1',
  `forum_order_cat` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`forum_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_groups`
--

CREATE TABLE IF NOT EXISTS `uap4_forum_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `forum_group` varchar(50) DEFAULT NULL,
  `groupID` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_images`
--

CREATE TABLE IF NOT EXISTS `uap4_forum_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `imageName` varchar(255) DEFAULT NULL,
  `imageLocation` varchar(255) DEFAULT NULL,
  `imageSize` int(11) DEFAULT NULL,
  `forumID` int(11) DEFAULT NULL,
  `forumTopicID` int(11) DEFAULT NULL,
  `forumTopicReplyID` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_posts`
--

CREATE TABLE IF NOT EXISTS `uap4_forum_posts` (
  `forum_post_id` int(20) NOT NULL AUTO_INCREMENT,
  `forum_id` int(20) DEFAULT NULL,
  `forum_user_id` int(20) DEFAULT NULL,
  `forum_title` varchar(255) DEFAULT NULL,
  `forum_content` text DEFAULT NULL,
  `forum_edit_date` varchar(20) DEFAULT NULL,
  `forum_status` int(11) NOT NULL DEFAULT '1',
  `subscribe_email` varchar(10) NOT NULL DEFAULT 'true',
  `allow` varchar(11) NOT NULL DEFAULT 'TRUE',
  `hide_reason` varchar(255) DEFAULT NULL,
  `hide_userID` int(11) DEFAULT NULL,
  `hide_timestamp` datetime DEFAULT NULL,
  `forum_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`forum_post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_post_replies`
--

CREATE TABLE IF NOT EXISTS `uap4_forum_post_replies` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fpr_post_id` int(20) DEFAULT NULL,
  `fpr_id` int(20) DEFAULT NULL,
  `fpr_user_id` int(20) DEFAULT NULL,
  `fpr_title` varchar(255) DEFAULT NULL,
  `fpr_content` text DEFAULT NULL,
  `subscribe_email` varchar(10) NOT NULL DEFAULT 'true',
  `fpr_edit_date` varchar(20) DEFAULT NULL,
  `allow` varchar(11) NOT NULL DEFAULT 'TRUE',
  `hide_reason` varchar(255) DEFAULT NULL,
  `hide_userID` int(11) DEFAULT NULL,
  `hide_timestamp` datetime DEFAULT NULL,
  `fpr_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_settings`
--

CREATE TABLE IF NOT EXISTS `uap4_forum_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `setting_title` varchar(255) DEFAULT NULL,
  `setting_value` varchar(255) DEFAULT NULL,
  `setting_value_2` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_sweets`
--

CREATE TABLE IF NOT EXISTS `uap4_sweets` (
  `sid` int(10) NOT NULL AUTO_INCREMENT,
  `sweet_id` int(10) DEFAULT NULL,
  `sweet_sec_id` int(10) DEFAULT NULL,
  `sweet_location` varchar(255) DEFAULT NULL,
  `sweet_user_ip` varchar(50) DEFAULT NULL,
  `sweet_server` varchar(255) DEFAULT NULL,
  `sweet_uri` varchar(255) DEFAULT NULL,
  `sweet_owner_userid` int(10) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`sid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_views`
--

CREATE TABLE IF NOT EXISTS `uap4_views` (
  `vid` int(10) NOT NULL AUTO_INCREMENT,
  `view_id` int(10) DEFAULT NULL,
  `view_sec_id` int(10) DEFAULT NULL,
  `view_location` varchar(255) DEFAULT NULL,
  `view_user_ip` varchar(50) DEFAULT NULL,
  `view_server` varchar(255) DEFAULT NULL,
  `view_uri` varchar(255) DEFAULT NULL,
  `view_owner_userid` int(10) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`vid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


-- --------------------------------------------------------

--
-- Dumping data for table `uap4_forum_cat`
--

INSERT INTO `uap4_forum_cat` (`forum_id`, `forum_name`, `forum_title`, `forum_cat`, `forum_des`, `forum_perm`, `forum_order_title`, `forum_order_cat`) VALUES
(1, 'forum', 'Forum', 'Welcome', 'Welcome to the Forum.', 1, 1, 1);

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_forum_settings`
--

INSERT INTO `uap4_forum_settings` (`id`, `setting_title`, `setting_value`, `setting_value_2`) VALUES
(1, 'forum_on_off', 'Enabled', ''),
(2, 'forum_title', 'Forum', ''),
(3, 'forum_description', 'Welcome to the Forum', ''),
(4, 'forum_topic_limit', '20', ''),
(5, 'forum_topic_reply_limit', '10', ''),
(6, 'forum_posts_group_change_enable', 'true', ''),
(7, 'forum_posts_group_change', '15', '');

-- --------------------------------------------------------

--
-- Table structure for table `uap4_settings`
--

CREATE TABLE `uap4_settings` (
  `setting_id` int(10) NOT NULL AUTO_INCREMENT,
  `setting_title` varchar(255) DEFAULT NULL,
  `setting_data` text,
  PRIMARY KEY (`setting_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_settings`
--

INSERT INTO `uap4_settings` (`setting_id`, `setting_title`, `setting_data`) VALUES
(1, 'site_title', 'My UAP4 Web Site'),
(2, 'site_description', 'Welcome to My UAP4 Web Site'),
(3, 'site_keywords', 'UAP, UserApplePie'),
(4, 'site_user_activation', 'false'),
(5, 'site_email_username', ''),
(6, 'site_email_password', ''),
(7, 'site_email_fromname', ''),
(8, 'site_email_host', ''),
(9, 'site_email_port', ''),
(10, 'site_email_smtp', ''),
(11, 'site_email_site', ''),
(12, 'site_recapcha_public', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'),
(13, 'site_recapcha_private', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe'),
(14, 'site_user_invite_code', ''),
(15, 'site_theme', 'default'),
(16, 'max_attempts', '5'),
(17, 'security_duration', '5'),
(18, 'session_duration', '1'),
(19, 'session_duration_rm', '1'),
(20, 'min_username_length', '5'),
(21, 'max_username_length', '30'),
(22, 'min_password_length', '5'),
(23, 'max_password_length', '30'),
(24, 'min_email_length', '5'),
(25, 'max_email_length', '100'),
(26, 'random_key_length', '15'),
(27, 'default_timezone', 'America/Chicago'),
(28, 'users_pageinator_limit', '20'),
(29, 'friends_pageinator_limit', '20'),
(30, 'message_quota_limit', '50'),
(31, 'message_pageinator_limit', '10'),
(32, 'sweet_title_display', 'Sweets'),
(33, 'sweet_button_display', 'Sweet');

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_tracker`
--

CREATE TABLE `uap4_forum_tracker` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `post_id` int(11) DEFAULT NULL,
  `forum_id` int(11) DEFAULT NULL,
  `last_visit` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

  -- --------------------------------------------------------

  --
  -- Table structure for table `uap4_forum_post_tracker`
  --

  CREATE TABLE `uap4_forum_post_tracker` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `forum_post_id` int(11) DEFAULT NULL,
    `forum_reply_id` int(11) DEFAULT NULL,
    `tracker_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 COMMENT='Keeps track of all forum posts and replies for better sort';

  -- --------------------------------------------------------

--
-- Table structure for table `uap4_friends`
--

CREATE TABLE `uap4_friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid1` int(15) DEFAULT NULL,
  `uid2` int(15) DEFAULT NULL,
  `status1` varchar(4) NOT NULL DEFAULT '0',
  `status2` varchar(4) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_routes`
--

CREATE TABLE `uap4_routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `controller` varchar(255) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `arguments` varchar(255) DEFAULT NULL,
  `enable` int(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_routes`
--

INSERT INTO `uap4_routes` (`id`, `controller`, `method`, `url`, `arguments`, `enable`) VALUES
(1, 'Home', 'About', 'About', '', 1),
(2, 'Home', 'Contact', 'Contact', '', 1);

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_forum_groups`
--

INSERT INTO `uap4_forum_groups` (`id`, `forum_group`, `groupID`) VALUES
(1, 'users', 1),
(2, 'users', 2),
(3, 'users', 3),
(4, 'users', 4),
(5, 'mods', 3),
(6, 'mods', 4),
(7, 'admins', 4);

-- --------------------------------------------------------

--
-- Table structure for table `uap4_links`
--

CREATE TABLE `uap4_links` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `link_order` int(11) DEFAULT '0',
  `link_order_drop_down` int(11) DEFAULT '0',
  `drop_down` int(11) DEFAULT '0',
  `drop_down_for` int(11) DEFAULT '0',
  `require_plugin` varchar(255) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_links`
--

INSERT INTO `uap4_links` (`id`, `title`, `url`, `alt_text`, `location`, `link_order`, `link_order_drop_down`, `drop_down`, `drop_down_for`, `require_plugin`, `timestamp`) VALUES
(1, 'Home', 'Home', 'Home Page', 'header_main', 1, 0, 0, 0, NULL, '2018-07-08 10:47:22'),
(2, 'About', 'About', 'About Us', 'header_main', 2, 0, 1, 0, NULL, '2018-07-08 10:48:40'),
(3, 'Contact', 'Contact', 'Contact Us', 'header_main', 3, 0, 0, 0, '', '2018-07-08 10:49:16'),
(6, 'About', 'About', 'About', 'header_main', 2, 1, 0, 2, NULL, '2018-07-08 12:28:20'),
(8, 'Footer', 'Home', 'Footer', 'footer', 1, 0, 0, 0, NULL, '2018-07-15 22:12:13'),
(10, 'New', 'New', 'New', 'new', 1, 0, 0, 0, '', '2018-07-21 12:28:44'),
(11, 'Contact Us', 'Contact', '', 'header_main', 2, 2, NULL, 2, '', '2018-08-17 00:25:19');
