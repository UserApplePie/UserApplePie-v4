<?php
/**
 * Admin Panel Database Upgrade Models
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
 */

 namespace App\Models;

 use App\System\Models,
     Libs\Database;

class DatabaseUpgrades extends Models {

  /* Update from UAP 4.2.1 to 4.3.0 */
  public function update421to430(){

    $sql_data[] = "
      SET @dbname = DATABASE();
      SET @tablename = '".PREFIX."forum_posts';
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
    ";
    $sql_data[] = "
      SET @dbname = DATABASE();
      SET @tablename = '".PREFIX."forum_post_replies';
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
    ";
    $sql_data[] = "
      ALTER TABLE ".PREFIX."forum_posts MODIFY COLUMN forum_publish int(1) NOT NULL DEFAULT '0';
    ";
    $sql_data[] = "
      ALTER TABLE ".PREFIX."forum_post_replies MODIFY COLUMN forum_publish int(1) NOT NULL DEFAULT '0';
    ";
    $sql_data[] = "
      ALTER TABLE ".PREFIX."links MODIFY COLUMN id int(11) NOT NULL AUTO_INCREMENT;
    ";
    $sql_data[] = "
      INSERT INTO ".PREFIX."forum_settings (setting_title, setting_value) VALUES ('forum_max_image_size', '800,600');
    ";
    $sql_data[] = "
      INSERT INTO ".PREFIX."settings (setting_title, setting_data) VALUES ('image_max_size', '800,600');
    ";
    $sql_data[] = "
      CREATE TABLE IF NOT EXISTS `".PREFIX."version` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `version` varchar(30) DEFAULT NULL,
        PRIMARY KEY (`id`)
      ) ENGINE=MyISAM  DEFAULT CHARSET=latin1;
    ";
    $sql_data[] = "
      INSERT INTO `".PREFIX."version` (`version`) VALUES ('4.3.0');
    ";
    $sql_data[] = "
      CREATE TABLE IF NOT EXISTS `".PREFIX."users_images` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `userID` int(11) DEFAULT NULL,
        `userImage` varchar(255) DEFAULT NULL,
        `defaultImage` int(11) NOT NULL DEFAULT '1',
        `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `update_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    ";
    $sql_data[] = "
      Insert into ".PREFIX."users_images (userID, userImage)  select userID, userImage from ".PREFIX."users;
    ";
    $sql_data[] = "
      ALTER TABLE ".PREFIX."users_images MODIFY COLUMN defaultImage int(11) NOT NULL DEFAULT '0';
    ";
    $sql_data[] = "
      ALTER TABLE ".PREFIX."users DROP userImage;
    ";
    $sql_data[] = "
      CREATE TABLE IF NOT EXISTS `".PREFIX."status` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `status_userID` int(11) DEFAULT NULL,
        `status_feeling` varchar(255) DEFAULT NULL,
        `status_content` text,
        `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=latin1;
    ";
    $sql_data[]  = "
      CREATE INDEX index_username ON ".PREFIX."users(username);
    ";
    $sql_data[]  = "
      CREATE INDEX index_timestamp ON ".PREFIX."sitelogs(timestamp);
    ";
    $sql_data[]  = "
      CREATE INDEX index_posts_title ON ".PREFIX."forum_posts(forum_title);
    ";
    $sql_data[]  = "
      CREATE FULLTEXT INDEX index_posts_content ON ".PREFIX."forum_posts(forum_content);
    ";
    $sql_data[]  = "
      CREATE FULLTEXT INDEX index_posts_content ON ".PREFIX."forum_post_replies(fpr_content);
    ";
    $sql_data[] = "
      ALTER TABLE `".PREFIX."users` ADD `privacy_profile` VARCHAR(20) NOT NULL DEFAULT 'Public' AFTER `privacy_pm`;
    ";
    $sql_data[] = "
      ALTER TABLE `".PREFIX."users` ADD `location` VARCHAR(255) NULL DEFAULT NULL AFTER `website`;
    ";

    foreach ($sql_data as $query) {
      if(!$this->db->upgrade($query)){ $error[] = true; }
    }

    if(isset($error)){
      return false;
    }else{
      return true;
    }

  }



}
