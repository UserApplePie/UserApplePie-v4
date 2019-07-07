-- UAP DB Updates from v4.2.1 to v4.3.0
-- --------------------------------------------------------
--
-- Table : uap4_forum_posts
-- Add forum_publish if not already updated
--
-- --------------------------------------------------------
  SET @dbname = DATABASE();
  SET @tablename = 'uap4_forum_posts';
  SET @columnname = 'forum_publish';
  SET @preparedStatement = (SELECT IF(
    (
      SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
      WHERE
        (table_name = @tablename)
        AND (table_schema = @dbname)
        AND (column_name = @columnname)
    ) > 0,
    'SELECT 1',
    CONCAT('ALTER TABLE ', @tablename, ' ADD ', @columnname, ' int(1) NOT NULL DEFAULT 1')
  ));
  PREPARE alterIfNotExists FROM @preparedStatement;
  EXECUTE alterIfNotExists;
  DEALLOCATE PREPARE alterIfNotExists;

  -- --------------------------------------------------------
  --
  -- Table : uap4_forum_post_replies
  -- Add forum_publish if not already updated
  --
  -- --------------------------------------------------------
  SET @dbname = DATABASE();
  SET @tablename = 'uap4_forum_post_replies';
  SET @columnname = 'forum_publish';
  SET @preparedStatement = (SELECT IF(
    (
      SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
      WHERE
        (table_name = @tablename)
        AND (table_schema = @dbname)
        AND (column_name = @columnname)
    ) > 0,
    'SELECT 1',
    CONCAT('ALTER TABLE ', @tablename, ' ADD ', @columnname, ' int(1) NOT NULL DEFAULT 1')
  ));
  PREPARE alterIfNotExists FROM @preparedStatement;
  EXECUTE alterIfNotExists;
  DEALLOCATE PREPARE alterIfNotExists;

  -- --------------------------------------------------------
  --
  -- Table : uap4_forum_posts
  -- Change forum_publish default to 0
  --
  -- --------------------------------------------------------
  ALTER TABLE uap4_forum_posts MODIFY COLUMN forum_publish int(1) NOT NULL DEFAULT '0';

  -- --------------------------------------------------------
  --
  -- Table : uap4_forum_post_replies
  -- Change forum_publish default to 0
  --
  -- --------------------------------------------------------
  ALTER TABLE uap4_forum_post_replies MODIFY COLUMN forum_publish int(1) NOT NULL DEFAULT '0';

  -- --------------------------------------------------------
  --
  -- Table : uap4_forum_settings
  -- Add forum_max_image_size setting
  --
  -- --------------------------------------------------------
  INSERT INTO uap4_forum_settings (setting_title, setting_value) VALUES ('forum_max_image_size', '800,600');

  -- --------------------------------------------------------
  --
  -- Table : uap4_forum_settings
  -- Add image_max_size setting
  --
  -- --------------------------------------------------------
  INSERT INTO uap4_settings (setting_title, setting_data) VALUES ('image_max_size', '800,600');

  -- --------------------------------------------------------
  --
  -- Table : uap4_version
  -- Add table if not already added
  --
  -- --------------------------------------------------------
  CREATE TABLE IF NOT EXISTS `uap4_version` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `version` varchar(30) DEFAULT NULL,
    PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

  -- --------------------------------------------------------
  --
  -- Table : uap4_users_images
  -- Add table if not already added
  --
  -- --------------------------------------------------------
  CREATE TABLE IF NOT EXISTS `uap4_users_images` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `userID` int(11) DEFAULT NULL,
    `userImage` varchar(255) DEFAULT NULL,
    `defaultImage` int(11) NOT NULL DEFAULT '1',
    `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
  ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

  -- --------------------------------------------------------
  --
  -- Table : uap4_users_images
  -- Copy userImage from uap4_users to uap4_users_images
  --
  -- --------------------------------------------------------
  Insert into uap4_users_images (userID, userImage)  select userID, userImage from uap4_users;

  -- --------------------------------------------------------
  --
  -- Table : uap4_users_images
  -- Reset the defaultImage defautl value to 0
  --
  -- --------------------------------------------------------
  ALTER TABLE uap4_users_images MODIFY COLUMN defaultImage int(11) NOT NULL DEFAULT '0';

  -- --------------------------------------------------------
  --
  -- Table : uap4_users
  -- Remove userImage colum from uap4_users structure
  --
  -- --------------------------------------------------------
  ALTER TABLE uap4_users DROP userImage;

  -- --------------------------------------------------------
  --
  -- Table : uap4_status
  -- Add table if not already added
  --
  -- --------------------------------------------------------
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
  -- Table : uap4_users
  -- Create Index for username
  --
  -- --------------------------------------------------------
  CREATE INDEX index_username ON uap4_users(username);

  -- --------------------------------------------------------
  --
  -- Table : uap4_sitelogs
  -- Create Index for timestamp
  --
  -- --------------------------------------------------------
  CREATE INDEX index_timestamp ON uap4_sitelogs(timestamp);

  -- --------------------------------------------------------
  --
  -- Table : uap4_forum_posts
  -- Create Index for forum_title
  --
  -- --------------------------------------------------------
  CREATE INDEX index_posts_title ON uap4_forum_posts(forum_title);

  -- --------------------------------------------------------
  --
  -- Table : uap4_forum_posts
  -- Create Index for forum_content
  --
  -- --------------------------------------------------------
  CREATE FULLTEXT INDEX index_posts_content ON uap4_forum_posts(forum_content);

  -- --------------------------------------------------------
  --
  -- Table : uap4_forum_post_replies
  -- Create Index for fpr_content
  --
  -- --------------------------------------------------------
  CREATE FULLTEXT INDEX index_posts_content ON uap4_forum_post_replies(fpr_content);

  -- --------------------------------------------------------
  --
  -- Table : uap4_users
  -- Add privacy_profile if not already updated
  --
  -- --------------------------------------------------------
    SET @dbname = DATABASE();
    SET @tablename = 'uap4_users';
    SET @columnname = 'privacy_profile';
    SET @preparedStatement = (SELECT IF(
      (
        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE
          (table_name = @tablename)
          AND (table_schema = @dbname)
          AND (column_name = @columnname)
      ) > 0,
      'SELECT 1',
      CONCAT('ALTER TABLE ', @tablename, ' ADD ', @columnname, ' VARCHAR(20) NOT NULL DEFAULT "Public" AFTER `privacy_pm`')
    ));
    PREPARE alterIfNotExists FROM @preparedStatement;
    EXECUTE alterIfNotExists;
    DEALLOCATE PREPARE alterIfNotExists;

  -- --------------------------------------------------------
  --
  -- Table : uap4_users
  -- Add privacy_profile if not already updated
  --
  -- --------------------------------------------------------
    SET @dbname = DATABASE();
    SET @tablename = 'uap4_users';
    SET @columnname = 'location';
    SET @preparedStatement = (SELECT IF(
      (
        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE
          (table_name = @tablename)
          AND (table_schema = @dbname)
          AND (column_name = @columnname)
      ) > 0,
      'SELECT 1',
      CONCAT('ALTER TABLE ', @tablename, ' ADD ', @columnname, ' VARCHAR(255) NULL DEFAULT NULL AFTER `website`')
    ));
    PREPARE alterIfNotExists FROM @preparedStatement;
    EXECUTE alterIfNotExists;
    DEALLOCATE PREPARE alterIfNotExists;

  -- --------------------------------------------------------
  --
  -- Table : uap4_comments
  -- Add table if not already added
  --
  -- --------------------------------------------------------
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
  -- Table : uap4_links
  -- Add permission if not already updated
  --
  -- --------------------------------------------------------
    SET @dbname = DATABASE();
    SET @tablename = 'uap4_links';
    SET @columnname = 'permission';
    SET @preparedStatement = (SELECT IF(
      (
        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE
          (table_name = @tablename)
          AND (table_schema = @dbname)
          AND (column_name = @columnname)
      ) > 0,
      'SELECT 1',
      CONCAT('ALTER TABLE ', @tablename, ' ADD ', @columnname, ' INT NOT NULL DEFAULT "0" AFTER `require_plugin`')
    ));
    PREPARE alterIfNotExists FROM @preparedStatement;
    EXECUTE alterIfNotExists;
    DEALLOCATE PREPARE alterIfNotExists;

  -- --------------------------------------------------------
  --
  -- Table : uap4_links
  -- Add icon if not already updated
  --
  -- --------------------------------------------------------
    SET @dbname = DATABASE();
    SET @tablename = 'uap4_links';
    SET @columnname = 'icon';
    SET @preparedStatement = (SELECT IF(
      (
        SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
        WHERE
          (table_name = @tablename)
          AND (table_schema = @dbname)
          AND (column_name = @columnname)
      ) > 0,
      'SELECT 1',
      CONCAT('ALTER TABLE ', @tablename, ' ADD ', @columnname, ' varchar(255) DEFAULT NULL AFTER `require_plugin`')
    ));
    PREPARE alterIfNotExists FROM @preparedStatement;
    EXECUTE alterIfNotExists;
    DEALLOCATE PREPARE alterIfNotExists;

  -- --------------------------------------------------------
  --
  -- Table structure for table `uap4_pages`
  --
  -- --------------------------------------------------------
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
  -- --------------------------------------------------------
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
  -- --------------------------------------------------------
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
  -- --------------------------------------------------------
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
  --
  -- Table : uap4_version
  -- Add version to table
  --
  -- --------------------------------------------------------
  INSERT INTO `uap4_version` (`version`) VALUES ('4.3.0');
  -- --------------------------------------------------------
