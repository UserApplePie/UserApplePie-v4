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

INSERT INTO `uap4_pages` (`id`, `url`, `controller`, `method`, `edit_timestamp`) VALUES
(1, 'Home', 'Home', 'Home', NULL),
(2, 'Templates', 'Home', 'Templates', NULL),
(3, 'assets', 'Home', 'assets', NULL),
(4, 'Register', 'Auth', 'register', NULL),
(5, 'Activate', 'Auth', 'activate', NULL),
(6, 'Forgot-Password', 'Auth', 'forgotPassword', NULL),
(7, 'ResetPassword', 'Auth', 'resetPassword', NULL),
(8, 'Resend-Activation-Email', 'Auth', 'resendActivation', NULL),
(9, 'Login', 'Auth', 'login', NULL),
(10, 'Logout', 'Auth', 'logout', NULL),
(11, 'Settings', 'Auth', 'settings', NULL),
(12, 'Change-Email', 'Auth', 'changeEmail', NULL),
(13, 'Change-Password', 'Auth', 'changePassword', NULL),
(14, 'Edit-Profile', 'Members', 'editProfile', NULL),
(15, 'Edit-Profile-Images', 'Members', 'editProfileImages', NULL),
(16, 'Privacy-Settings', 'Members', 'privacy', NULL),
(17, 'Account-Settings', 'Members', 'account', NULL),
(18, 'LiveCheckEmail', 'LiveCheck', 'emailCheck', NULL),
(19, 'LiveCheckUserName', 'LiveCheck', 'userNameCheck', NULL),
(20, 'Members', 'Members', 'members', NULL),
(21, 'Online-Members', 'Members', 'online', NULL),
(22, 'Profile', 'Members', 'viewProfile', NULL),
(23, 'AdminPanel', 'AdminPanel', 'Dashboard', NULL),
(24, 'AdminPanel-Settings', 'AdminPanel', 'Settings', NULL),
(25, 'AdminPanel-AdvancedSettings', 'AdminPanel', 'AdvancedSettings', NULL),
(26, 'AdminPanel-EmailSettings', 'AdminPanel', 'EmailSettings', NULL),
(27, 'AdminPanel-Users', 'AdminPanel', 'Users', NULL),
(28, 'AdminPanel-User', 'AdminPanel', 'User', NULL),
(29, 'AdminPanel-Groups', 'AdminPanel', 'Groups', NULL),
(30, 'AdminPanel-Group', 'AdminPanel', 'Group', NULL),
(31, 'AdminPanel-MassEmail', 'AdminPanel', 'MassEmail', NULL),
(32, 'AdminPanel-SystemRoutes', 'AdminPanel', 'SystemRoutes', NULL),
(33, 'AdminPanel-SystemRoute', 'AdminPanel', 'SystemRoute', NULL),
(34, 'AdminPanel-AuthLogs', 'AdminPanel', 'AuthLogs', NULL),
(35, 'AdminPanel-SiteLinks', 'AdminPanel', 'SiteLinks', NULL),
(36, 'AdminPanel-SiteLink', 'AdminPanel', 'SiteLink', NULL),
(37, 'AdminPanel-Adds', 'AdminPanel', 'Adds', NULL),
(38, 'AdminPanel-Upgrade', 'AdminPanel', 'Upgrade', NULL),
(39, 'AdminPanel-PagesPermissions', 'AdminPanel', 'PagesPermissions', NULL),
(40, 'AdminPanel-PagePermissions', 'AdminPanel', 'PagePermissions', NULL),
(41, 'ChangeLang', 'ChangeLang', 'index', NULL),
(42, 'Forum', 'Plugins\\Forum\\Controllers\\Forum', 'forum', NULL),
(43, 'Topics', 'Plugins\\Forum\\Controllers\\Forum', 'topics', NULL),
(44, 'Topic', 'Plugins\\Forum\\Controllers\\Forum', 'topic', NULL),
(45, 'NewTopic', 'Plugins\\Forum\\Controllers\\Forum', 'newtopic', NULL),
(46, 'AdminPanel-Forum-Categories', 'Plugins\\Forum\\Controllers\\ForumAdmin', 'forum_categories', NULL),
(47, 'AdminPanel-Forum-Blocked-Content', 'Plugins\\Forum\\Controllers\\ForumAdmin', 'forum_blocked', NULL),
(48, 'AdminPanel-Forum-Unpublished-Content', 'Plugins\\Forum\\Controllers\\ForumAdmin', 'forum_unpublished', NULL),
(49, 'SearchForum', 'Plugins\\Forum\\Controllers\\Forum', 'forumSearch', NULL),
(50, 'AdminPanel-Forum-Settings', 'Plugins\\Forum\\Controllers\\ForumAdmin', 'forum_settings', NULL),
(51, 'Messages', 'Plugins\\Messages\\Controllers\\Messages', 'messages', NULL),
(52, 'ViewMessage', 'Plugins\\Messages\\Controllers\\Messages', 'view', NULL),
(53, 'MessagesInbox', 'Plugins\\Messages\\Controllers\\Messages', 'inbox', NULL),
(54, 'MessagesOutbox', 'Plugins\\Messages\\Controllers\\Messages', 'outbox', NULL),
(55, 'NewMessage', 'Plugins\\Messages\\Controllers\\Messages', 'newmessage', NULL),
(56, 'Friends', 'Plugins\\Friends\\Controllers\\Friends', 'friends', NULL),
(57, 'FriendRequests', 'Plugins\\Friends\\Controllers\\Friends', 'friendrequests', NULL),
(58, 'UnFriend', 'Plugins\\Friends\\Controllers\\Friends', 'unfriend', NULL),
(59, 'AddFriend', 'Plugins\\Friends\\Controllers\\Friends', 'addfriend', NULL),
(60, 'ApproveFriend', 'Plugins\\Friends\\Controllers\\Friends', 'approvefriend', NULL),
(61, 'CancelFriend', 'Plugins\\Friends\\Controllers\\Friends', 'cancelfriend', NULL),
(62, 'About', 'Home', 'About', NULL),
(63, 'Contact', 'Home', 'Contact', NULL);

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

INSERT INTO `uap4_pages_permissions` (`page_id`, `group_id`) VALUES
(1, 0),(2, 0),(3, 0),(4, 0),(5, 0),(6, 0),(7, 0),(8, 0),(9, 0),(10, 0),(18, 0),
(19, 0),(20, 0),(21, 0),(22, 0),(41, 0),(42, 0),(43, 0),(44, 0),(49, 0),(62, 0),
(63, 0),(17, 1),(17, 2),(17, 3),(17, 4),(59, 1),(59, 2),(59, 3),(59, 4),(23, 4),
(37, 4),(25, 4),(34, 4),(26, 4),(47, 4),(46, 4),(50, 4),(48, 4),(30, 4),(29, 4),
(31, 4),(40, 4),(39, 4),(24, 4),(36, 4),(35, 4),(33, 4),(32, 4),(38, 4),(28, 4),
(27, 4),(60, 1),(60, 2),(60, 3),(60, 4),(61, 1),(61, 2),(61, 3),(61, 4),(12, 1),
(12, 2),(12, 3),(12, 4),(13, 1),(13, 2),(13, 3),(13, 4),(14, 1),(14, 2),(14, 3),
(14, 4),(15, 1),(15, 2),(15, 3),(15, 4),(57, 1),(57, 2),(57, 3),(57, 4),(56, 1),
(56, 2),(56, 3),(56, 4),(51, 1),(51, 2),(51, 3),(51, 4),(53, 1),(53, 2),(53, 3),
(53, 4),(54, 1),(54, 2),(54, 3),(54, 4),(55, 1),(55, 2),(55, 3),(55, 4),(45, 1),
(45, 2),(45, 3),(45, 4),(16, 1),(16, 2),(16, 3),(16, 4),(11, 1),(11, 2),(11, 3),
(11, 4),(58, 1),(58, 2),(58, 3),(58, 4),(52, 1),(52, 2),(52, 3),(52, 4);

-- --------------------------------------------------------
