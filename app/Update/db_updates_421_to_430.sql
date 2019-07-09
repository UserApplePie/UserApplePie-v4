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
  --
  -- Table : uap4_version
  -- Add version to table
  --
  -- --------------------------------------------------------
  INSERT INTO `uap4_version` (`version`) VALUES ('4.3.0');
  -- --------------------------------------------------------
