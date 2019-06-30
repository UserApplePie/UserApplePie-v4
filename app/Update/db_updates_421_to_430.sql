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
  -- Table : uap4_users
  -- Add privacy_profile if not already updated
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
  -- Table : uap4_version
  -- Add version to table
  --
  -- --------------------------------------------------------
  INSERT INTO `uap4_version` (`version`) VALUES ('4.3.0');
