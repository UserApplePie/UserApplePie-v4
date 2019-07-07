-- phpMyAdmin SQL Dump
-- version 4.2.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 31, 2016 at 06:31 PM
-- Server version: 5.5.47-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.14

-- UserApplePie v4.3.0

-- Instructions
-- Import this file to your mySQL database

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `uap4`
--

-- --------------------------------------------------------

--
-- Table structure for table `uap4_version`
--

CREATE TABLE IF NOT EXISTS `uap4_version` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `version` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_users_groups`
-- Sets first user as Admin
--

INSERT INTO `uap4_version` (`version`) VALUES
('4.3.0');

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
  `website` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `aboutme` text DEFAULT NULL,
  `signature` text DEFAULT NULL,
  `isactive` tinyint(1) NOT NULL DEFAULT '0',
  `activekey` varchar(15) NOT NULL DEFAULT '0',
  `resetkey` varchar(15) NOT NULL DEFAULT '0',
  `LastLogin` datetime DEFAULT NULL,
  `privacy_massemail` varchar(5) NOT NULL DEFAULT 'true',
  `privacy_pm` varchar(5) NOT NULL DEFAULT 'true',
  `privacy_profile` varchar(20) NOT NULL DEFAULT 'Public',
  `SignUp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`userID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

CREATE INDEX index_username ON uap4_users(username);

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

CREATE INDEX index_timestamp ON uap4_sitelogs(timestamp);

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
  `forum_publish` int(1) NOT NULL DEFAULT '0',
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

CREATE INDEX index_posts_title ON uap4_forum_posts(forum_title);
CREATE FULLTEXT INDEX index_posts_content ON uap4_forum_posts(forum_content);

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_post_replies`
--

CREATE TABLE IF NOT EXISTS `uap4_forum_post_replies` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `fpr_post_id` int(20) DEFAULT NULL,
  `fpr_id` int(20) DEFAULT NULL,
  `fpr_user_id` int(20) DEFAULT NULL,
  `fpr_content` text DEFAULT NULL,
  `forum_publish` int(1) NOT NULL DEFAULT '0',
  `subscribe_email` varchar(10) NOT NULL DEFAULT 'true',
  `fpr_edit_date` varchar(20) DEFAULT NULL,
  `allow` varchar(11) NOT NULL DEFAULT 'TRUE',
  `hide_reason` varchar(255) DEFAULT NULL,
  `hide_userID` int(11) DEFAULT NULL,
  `hide_timestamp` datetime DEFAULT NULL,
  `fpr_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE FULLTEXT INDEX index_posts_content ON uap4_forum_post_replies(fpr_content);

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
(7, 'forum_posts_group_change', '15', ''),
(8, 'forum_max_image_size', '800,600', '');

-- --------------------------------------------------------

--
-- Table structure for table `uap4_settings`
--

CREATE TABLE IF NOT EXISTS `uap4_settings` (
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
(12, 'site_recapcha_public', ''),
(13, 'site_recapcha_private', ''),
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
(33, 'sweet_button_display', 'Sweet'),
(34, 'image_max_size', '800,600'),
(35, 'site_message', '');

-- --------------------------------------------------------

--
-- Table structure for table `uap4_forum_tracker`
--

CREATE TABLE IF NOT EXISTS `uap4_forum_tracker` (
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

  CREATE TABLE IF NOT EXISTS `uap4_forum_post_tracker` (
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

CREATE TABLE IF NOT EXISTS `uap4_friends` (
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

CREATE TABLE IF NOT EXISTS `uap4_routes` (
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

CREATE TABLE IF NOT EXISTS `uap4_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `link_order` int(11) DEFAULT '0',
  `link_order_drop_down` int(11) DEFAULT '0',
  `drop_down` int(11) DEFAULT '0',
  `drop_down_for` int(11) DEFAULT '0',
  `require_plugin` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `permission` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_links`
--

INSERT INTO `uap4_links` (`id`, `title`, `url`, `alt_text`, `location`, `link_order`, `link_order_drop_down`, `drop_down`, `drop_down_for`, `require_plugin`, `timestamp`) VALUES
(1, 'Home', 'Home', 'Home Page', 'header_main', 1, 0, 0, 0, NULL, NOW()),
(2, 'About', 'About', 'About Us', 'header_main', 2, 0, 1, 0, NULL, NOW()),
(3, 'Contact', 'Contact', 'Contact Us', 'header_main', 3, 0, 0, 0, '', NOW()),
(6, 'About', 'About', 'About', 'header_main', 2, 1, 0, 2, NULL, NOW()),
(8, 'Footer', 'Home', 'Footer', 'footer', 1, 0, 0, 0, NULL, NOW()),
(10, 'New', 'New', 'New', 'new', 1, 0, 0, 0, '', NOW()),
(11, 'Contact Us', 'Contact', '', 'header_main', 2, 2, NULL, 2, '', NOW()),
(12, 'Forum', 'Forum', 'Forum', 'header_main', 4, 0, 0, 0, 'Forum', NOW());

-- --------------------------------------------------------

--
-- Table structure for table `uap4_users_images`
--

CREATE TABLE IF NOT EXISTS `uap4_users_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userID` int(11) DEFAULT NULL,
  `userImage` varchar(255) DEFAULT NULL,
  `defaultImage` int(11) NOT NULL DEFAULT '0',
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_status`
--

CREATE TABLE IF NOT EXISTS `uap4_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status_userID` int(11) DEFAULT NULL,
  `status_feeling` varchar(255) DEFAULT NULL,
  `status_content` text,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_comments`
--

CREATE TABLE IF NOT EXISTS `uap4_comments` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `com_id` int(10) DEFAULT NULL,
  `com_sec_id` int(10) DEFAULT NULL,
  `com_location` varchar(255) DEFAULT NULL,
  `com_user_ip` varchar(50) DEFAULT NULL,
  `com_server` varchar(255) DEFAULT NULL,
  `com_uri` varchar(255) DEFAULT NULL,
  `com_owner_userid` int(10) DEFAULT NULL,
  `com_content` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uap4_pages`
--

CREATE TABLE IF NOT EXISTS `uap4_pages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `method` varchar(255) DEFAULT NULL,
  `edit_timestamp` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_pages`
--

INSERT INTO `uap4_pages` (`id`, `url`, `controller`, `method`, `edit_timestamp`, `timestamp`) VALUES
(1, 'Home', 'Home', 'Home', NULL, '2019-07-07 02:24:43'),
(2, 'Templates', 'Home', 'Templates', NULL, '2019-07-07 02:24:43'),
(3, 'assets', 'Home', 'assets', NULL, '2019-07-07 02:24:44'),
(4, 'Register', 'Auth', 'register', NULL, '2019-07-07 02:24:44'),
(5, 'Activate', 'Auth', 'activate', NULL, '2019-07-07 02:24:44'),
(6, 'Forgot-Password', 'Auth', 'forgotPassword', NULL, '2019-07-07 02:24:44'),
(7, 'ResetPassword', 'Auth', 'resetPassword', NULL, '2019-07-07 02:24:44'),
(8, 'Resend-Activation-Email', 'Auth', 'resendActivation', NULL, '2019-07-07 02:24:44'),
(9, 'Login', 'Auth', 'login', NULL, '2019-07-07 02:24:44'),
(10, 'Logout', 'Auth', 'logout', NULL, '2019-07-07 02:24:44'),
(11, 'Settings', 'Auth', 'settings', NULL, '2019-07-07 02:24:44'),
(12, 'Change-Email', 'Auth', 'changeEmail', NULL, '2019-07-07 02:24:45'),
(13, 'Change-Password', 'Auth', 'changePassword', NULL, '2019-07-07 02:24:45'),
(14, 'Edit-Profile', 'Members', 'editProfile', NULL, '2019-07-07 02:24:45'),
(15, 'Edit-Profile-Images', 'Members', 'editProfileImages', NULL, '2019-07-07 02:24:45'),
(16, 'Privacy-Settings', 'Members', 'privacy', NULL, '2019-07-07 02:24:45'),
(17, 'Account-Settings', 'Members', 'account', NULL, '2019-07-07 02:24:45'),
(18, 'LiveCheckEmail', 'LiveCheck', 'emailCheck', NULL, '2019-07-07 02:24:45'),
(19, 'LiveCheckUserName', 'LiveCheck', 'userNameCheck', NULL, '2019-07-07 02:24:45'),
(20, 'Members', 'Members', 'members', NULL, '2019-07-07 02:24:45'),
(21, 'Online-Members', 'Members', 'online', NULL, '2019-07-07 02:24:45'),
(22, 'Profile', 'Members', 'viewProfile', NULL, '2019-07-07 02:24:45'),
(23, 'AdminPanel', 'AdminPanel', 'Dashboard', NULL, '2019-07-07 02:24:46'),
(24, 'AdminPanel-Settings', 'AdminPanel', 'Settings', NULL, '2019-07-07 02:24:46'),
(25, 'AdminPanel-AdvancedSettings', 'AdminPanel', 'AdvancedSettings', NULL, '2019-07-07 02:24:46'),
(26, 'AdminPanel-EmailSettings', 'AdminPanel', 'EmailSettings', NULL, '2019-07-07 02:24:46'),
(27, 'AdminPanel-Users', 'AdminPanel', 'Users', NULL, '2019-07-07 02:24:46'),
(28, 'AdminPanel-User', 'AdminPanel', 'User', NULL, '2019-07-07 02:24:46'),
(29, 'AdminPanel-Groups', 'AdminPanel', 'Groups', NULL, '2019-07-07 02:24:46'),
(30, 'AdminPanel-Group', 'AdminPanel', 'Group', NULL, '2019-07-07 02:24:46'),
(31, 'AdminPanel-MassEmail', 'AdminPanel', 'MassEmail', NULL, '2019-07-07 02:24:46'),
(32, 'AdminPanel-SystemRoutes', 'AdminPanel', 'SystemRoutes', NULL, '2019-07-07 02:24:46'),
(33, 'AdminPanel-SystemRoute', 'AdminPanel', 'SystemRoute', NULL, '2019-07-07 02:24:46'),
(34, 'AdminPanel-AuthLogs', 'AdminPanel', 'AuthLogs', NULL, '2019-07-07 02:24:47'),
(35, 'AdminPanel-SiteLinks', 'AdminPanel', 'SiteLinks', NULL, '2019-07-07 02:24:47'),
(36, 'AdminPanel-SiteLink', 'AdminPanel', 'SiteLink', NULL, '2019-07-07 02:24:47'),
(37, 'AdminPanel-Adds', 'AdminPanel', 'Adds', NULL, '2019-07-07 02:24:47'),
(38, 'AdminPanel-Upgrade', 'AdminPanel', 'Upgrade', NULL, '2019-07-07 02:24:47'),
(39, 'AdminPanel-PagesPermissions', 'AdminPanel', 'PagesPermissions', NULL, '2019-07-07 02:24:47'),
(40, 'AdminPanel-PagePermissions', 'AdminPanel', 'PagePermissions', NULL, '2019-07-07 02:24:47'),
(41, 'ChangeLang', 'ChangeLang', 'index', NULL, '2019-07-07 02:24:47'),
(42, 'Forum', 'Plugins\\Forum\\Controllers\\Forum', 'forum', NULL, '2019-07-07 02:24:47'),
(43, 'Topics', 'Plugins\\Forum\\Controllers\\Forum', 'topics', NULL, '2019-07-07 02:24:47'),
(44, 'Topic', 'Plugins\\Forum\\Controllers\\Forum', 'topic', NULL, '2019-07-07 02:24:48'),
(45, 'NewTopic', 'Plugins\\Forum\\Controllers\\Forum', 'newtopic', NULL, '2019-07-07 02:24:48'),
(46, 'AdminPanel-Forum-Categories', 'Plugins\\Forum\\Controllers\\ForumAdmin', 'forum_categories', NULL, '2019-07-07 02:24:48'),
(47, 'AdminPanel-Forum-Blocked-Content', 'Plugins\\Forum\\Controllers\\ForumAdmin', 'forum_blocked', NULL, '2019-07-07 02:24:48'),
(48, 'AdminPanel-Forum-Unpublished-Content', 'Plugins\\Forum\\Controllers\\ForumAdmin', 'forum_unpublished', NULL, '2019-07-07 02:24:48'),
(49, 'SearchForum', 'Plugins\\Forum\\Controllers\\Forum', 'forumSearch', NULL, '2019-07-07 02:24:48'),
(50, 'AdminPanel-Forum-Settings', 'Plugins\\Forum\\Controllers\\ForumAdmin', 'forum_settings', NULL, '2019-07-07 02:24:48'),
(51, 'Messages', 'Plugins\\Messages\\Controllers\\Messages', 'messages', NULL, '2019-07-07 02:24:48'),
(52, 'ViewMessage', 'Plugins\\Messages\\Controllers\\Messages', 'view', NULL, '2019-07-07 02:24:48'),
(53, 'MessagesInbox', 'Plugins\\Messages\\Controllers\\Messages', 'inbox', NULL, '2019-07-07 02:24:48'),
(54, 'MessagesOutbox', 'Plugins\\Messages\\Controllers\\Messages', 'outbox', NULL, '2019-07-07 02:24:49'),
(55, 'NewMessage', 'Plugins\\Messages\\Controllers\\Messages', 'newmessage', NULL, '2019-07-07 02:24:49'),
(56, 'Friends', 'Plugins\\Friends\\Controllers\\Friends', 'friends', NULL, '2019-07-07 02:24:49'),
(57, 'FriendRequests', 'Plugins\\Friends\\Controllers\\Friends', 'friendrequests', NULL, '2019-07-07 02:24:49'),
(58, 'UnFriend', 'Plugins\\Friends\\Controllers\\Friends', 'unfriend', NULL, '2019-07-07 02:24:49'),
(59, 'AddFriend', 'Plugins\\Friends\\Controllers\\Friends', 'addfriend', NULL, '2019-07-07 02:24:49'),
(60, 'ApproveFriend', 'Plugins\\Friends\\Controllers\\Friends', 'approvefriend', NULL, '2019-07-07 02:24:49'),
(61, 'CancelFriend', 'Plugins\\Friends\\Controllers\\Friends', 'cancelfriend', NULL, '2019-07-07 02:24:49'),
(62, 'About', 'Home', 'About', NULL, '2019-07-07 02:24:49'),
(63, 'Contact', 'Home', 'Contact', NULL, '2019-07-07 02:24:49');

-- --------------------------------------------------------

--
-- Table structure for table `uap4_pages_permissions`
--

CREATE TABLE IF NOT EXISTS `uap4_pages_permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_id` int(11) DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Dumping data for table `uap4_pages_permissions`
--

INSERT INTO `uap4_pages_permissions` (`id`, `page_id`, `group_id`, `timestamp`) VALUES
(1, 1, 0, '2019-07-07 02:24:43'),
(2, 2, 0, '2019-07-07 02:24:44'),
(3, 3, 0, '2019-07-07 02:24:44'),
(4, 4, 0, '2019-07-07 02:24:44'),
(5, 5, 0, '2019-07-07 02:24:44'),
(7, 7, 0, '2019-07-07 02:24:44'),
(8, 8, 0, '2019-07-07 02:24:44'),
(9, 9, 0, '2019-07-07 02:24:44'),
(10, 10, 0, '2019-07-07 02:24:44'),
(18, 18, 0, '2019-07-07 02:24:45'),
(19, 19, 0, '2019-07-07 02:24:45'),
(20, 20, 0, '2019-07-07 02:24:45'),
(21, 21, 0, '2019-07-07 02:24:45'),
(22, 22, 0, '2019-07-07 02:24:45'),
(41, 41, 0, '2019-07-07 02:24:47'),
(42, 42, 0, '2019-07-07 02:24:47'),
(43, 43, 0, '2019-07-07 02:24:47'),
(44, 44, 0, '2019-07-07 02:24:48'),
(49, 49, 0, '2019-07-07 02:24:48'),
(62, 62, 0, '2019-07-07 02:24:49'),
(63, 63, 0, '2019-07-07 02:24:49'),
(64, 17, 1, '2019-07-07 02:25:10'),
(65, 17, 2, '2019-07-07 02:25:10'),
(66, 17, 3, '2019-07-07 02:25:10'),
(67, 17, 4, '2019-07-07 02:25:10'),
(68, 59, 1, '2019-07-07 02:25:45'),
(69, 59, 2, '2019-07-07 02:25:45'),
(70, 59, 3, '2019-07-07 02:25:45'),
(71, 59, 4, '2019-07-07 02:25:45'),
(72, 23, 4, '2019-07-07 02:25:54'),
(73, 37, 4, '2019-07-07 02:26:02'),
(74, 25, 4, '2019-07-07 02:26:11'),
(75, 34, 4, '2019-07-07 02:26:28'),
(76, 26, 4, '2019-07-07 02:26:36'),
(77, 47, 4, '2019-07-07 02:26:46'),
(78, 46, 4, '2019-07-07 02:26:53'),
(79, 50, 4, '2019-07-07 02:27:00'),
(80, 48, 4, '2019-07-07 02:27:08'),
(81, 30, 4, '2019-07-07 02:27:17'),
(82, 29, 4, '2019-07-07 02:27:26'),
(83, 31, 4, '2019-07-07 02:27:37'),
(84, 40, 4, '2019-07-07 02:27:47'),
(85, 39, 4, '2019-07-07 02:27:55'),
(86, 24, 4, '2019-07-07 02:28:03'),
(87, 36, 4, '2019-07-07 02:28:12'),
(88, 35, 4, '2019-07-07 02:28:22'),
(89, 33, 4, '2019-07-07 02:28:30'),
(90, 32, 4, '2019-07-07 02:28:39'),
(91, 38, 4, '2019-07-07 02:28:47'),
(92, 28, 4, '2019-07-07 02:28:55'),
(93, 27, 4, '2019-07-07 02:29:04'),
(94, 60, 1, '2019-07-07 02:29:15'),
(95, 60, 2, '2019-07-07 02:29:16'),
(96, 60, 3, '2019-07-07 02:29:16'),
(97, 60, 4, '2019-07-07 02:29:16'),
(98, 61, 1, '2019-07-07 02:29:28'),
(99, 61, 2, '2019-07-07 02:29:28'),
(100, 61, 3, '2019-07-07 02:29:28'),
(101, 61, 4, '2019-07-07 02:29:28'),
(102, 12, 1, '2019-07-07 02:29:42'),
(103, 12, 2, '2019-07-07 02:29:42'),
(104, 12, 3, '2019-07-07 02:29:42'),
(105, 12, 4, '2019-07-07 02:29:42'),
(106, 13, 1, '2019-07-07 02:29:54'),
(107, 13, 2, '2019-07-07 02:29:54'),
(108, 13, 3, '2019-07-07 02:29:54'),
(109, 13, 4, '2019-07-07 02:29:54'),
(110, 14, 1, '2019-07-07 02:30:06'),
(111, 14, 2, '2019-07-07 02:30:06'),
(112, 14, 3, '2019-07-07 02:30:06'),
(113, 14, 4, '2019-07-07 02:30:06'),
(114, 15, 1, '2019-07-07 02:30:15'),
(115, 15, 2, '2019-07-07 02:30:15'),
(116, 15, 3, '2019-07-07 02:30:15'),
(117, 15, 4, '2019-07-07 02:30:15'),
(118, 6, 1, '2019-07-07 02:30:27'),
(119, 6, 2, '2019-07-07 02:30:27'),
(120, 6, 3, '2019-07-07 02:30:27'),
(121, 6, 4, '2019-07-07 02:30:27'),
(122, 57, 1, '2019-07-07 02:30:48'),
(123, 57, 2, '2019-07-07 02:30:48'),
(124, 57, 3, '2019-07-07 02:30:48'),
(125, 57, 4, '2019-07-07 02:30:48'),
(126, 56, 1, '2019-07-07 02:31:04'),
(127, 56, 2, '2019-07-07 02:31:04'),
(128, 56, 3, '2019-07-07 02:31:04'),
(129, 56, 4, '2019-07-07 02:31:04'),
(130, 51, 1, '2019-07-07 02:31:30'),
(131, 51, 2, '2019-07-07 02:31:30'),
(132, 51, 3, '2019-07-07 02:31:30'),
(133, 51, 4, '2019-07-07 02:31:30'),
(134, 53, 1, '2019-07-07 02:31:40'),
(135, 53, 2, '2019-07-07 02:31:41'),
(136, 53, 3, '2019-07-07 02:31:41'),
(137, 53, 4, '2019-07-07 02:31:41'),
(138, 54, 1, '2019-07-07 02:31:51'),
(139, 54, 2, '2019-07-07 02:31:51'),
(140, 54, 3, '2019-07-07 02:31:51'),
(141, 54, 4, '2019-07-07 02:31:51'),
(142, 55, 1, '2019-07-07 02:32:05'),
(143, 55, 2, '2019-07-07 02:32:06'),
(144, 55, 3, '2019-07-07 02:32:06'),
(145, 55, 4, '2019-07-07 02:32:06'),
(146, 45, 1, '2019-07-07 02:32:20'),
(147, 45, 2, '2019-07-07 02:32:20'),
(148, 45, 3, '2019-07-07 02:32:20'),
(149, 45, 4, '2019-07-07 02:32:20'),
(150, 16, 1, '2019-07-07 02:32:35'),
(151, 16, 2, '2019-07-07 02:32:35'),
(152, 16, 3, '2019-07-07 02:32:35'),
(153, 16, 4, '2019-07-07 02:32:35'),
(154, 11, 1, '2019-07-07 02:33:51'),
(155, 11, 2, '2019-07-07 02:33:51'),
(156, 11, 3, '2019-07-07 02:33:51'),
(157, 11, 4, '2019-07-07 02:33:51'),
(158, 58, 1, '2019-07-07 02:34:09'),
(159, 58, 2, '2019-07-07 02:34:09'),
(160, 58, 3, '2019-07-07 02:34:09'),
(161, 58, 4, '2019-07-07 02:34:09'),
(162, 52, 1, '2019-07-07 02:34:20'),
(163, 52, 2, '2019-07-07 02:34:20'),
(164, 52, 3, '2019-07-07 02:34:20'),
(165, 52, 4, '2019-07-07 02:34:20');

-- --------------------------------------------------------
