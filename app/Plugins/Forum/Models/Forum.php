<?php
/**
* UserApplePie v4 Forum Plugin
* @author David (DaVaR) Sargent
* @email davar@thedavar.net
* @website http://www.userapplepie.com
* @version 1.0.0
* @release_date 04/27/2016
**/

/** Forum model **/

namespace App\Plugins\Forum\Models;

use App\System\Models;

class Forum extends Models {

  /**
   * globalForumSetting
   *
   * get setting for requested setting.
   *
   * @param $action which setting to get
   *
   * @return string returns requested setting
   */
  public function globalForumSetting($action){
    $data = $this->db->select("
      SELECT setting_value
      FROM ".PREFIX."forum_settings
      WHERE setting_title = :action
      LIMIT 1
    ",
    array(':action' => $action));
    return $data[0]->setting_value;
  }

  /**
   * forum_categories
   *
   * get list of all enabled forum categories.
   *
   * @return array returns all categories
   */
  public function forum_categories(){
    $data = $this->db->select("
        SELECT
          *
        FROM
          ".PREFIX."forum_cat
        WHERE
          forum_name = 'forum'
        GROUP BY
          forum_title
        ORDER BY
          forum_order_title
        ASC
        ");
    return $data;
  }

  /**
   * forum_titles
   *
   * get list of all enabled forum titles.
   *
   * @return array returns all titles
   */
    public function forum_titles(){
      $data = $this->db->select("
          SELECT
            a.*,
            COUNT(DISTINCT t.forum_post_id) AS total_topics_display,
            COUNT(DISTINCT r.id) AS total_topic_replys_display
          FROM
            ".PREFIX."forum_cat AS a
          LEFT OUTER JOIN
            ".PREFIX."forum_posts AS t
            ON a.forum_id = t.forum_id
          LEFT OUTER JOIN
            ".PREFIX."forum_post_replies AS r
            ON a.forum_id = r.fpr_id
          WHERE
            a.forum_name = 'forum'
          GROUP BY
            a.forum_id
          ORDER BY
            a.forum_order_cat
          ASC
          ");
      return $data;
    }

    /**
     * forum_recent_posts
     *
     * get list of all recent posts in forum ordered by date.
     *
     * @return array returns all recent forum posts
     */
    public function forum_recent_posts($limit = "10"){
      $data = $this->db->select("
            SELECT sub.* FROM (
                SELECT
                    fp.forum_post_id as forum_post_id, fp.forum_id as forum_id,
                    fp.forum_user_id as forum_user_id, fp.forum_title as forum_title,
                    fp.forum_edit_date as forum_edit_date,
                    fp.forum_timestamp as forum_timestamp, fpr.id as id,
                    fpr.fpr_post_id as fpr_post_id, fpr.fpr_id as fpr_id,
                    fpr.fpr_user_id as fpr_user_id, fpr.fpr_title as fpr_title,
                    fpr.fpr_edit_date as fpr_edit_date,
                    fpr.fpr_timestamp as fpr_timestamp,
                GREATEST(fp.forum_timestamp, COALESCE(fpr.fpr_timestamp, '00-00-00 00:00:00')) AS tstamp
                FROM ".PREFIX."forum_posts fp
                LEFT JOIN ".PREFIX."forum_post_replies fpr
                ON fp.forum_post_id = fpr.fpr_post_id
                WHERE fp.allow = 'TRUE'
                ORDER BY tstamp DESC
            ) sub
            GROUP BY forum_post_id
      ");
      return $data;
    }

    /**
     * forum_cat
     *
     * get category data for current topic (Category)
     *
     * @param int $where_id = forum_id
     *
     * @return string returns forum category data (forum_cat)
     */
    public function forum_cat($where_id){
      $data = $this->db->select("
        SELECT forum_cat
        FROM ".PREFIX."forum_cat
        WHERE forum_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_cat;
    }

    /**
     * forum_cat_des
     *
     * get category data for current topic (Description)
     *
     * @param int $where_id = forum_id
     *
     * @return string returns forum category data (forum_des)
     */
    public function forum_cat_des($where_id){
      $data = $this->db->select("
        SELECT forum_des
        FROM ".PREFIX."forum_cat
        WHERE forum_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_des;
    }

    /**
     * forum_title
     *
     * get category data for current topic (Title)
     *
     * @param int $where_id = forum_id
     *
     * @return string returns forum category data (forum_title)
     */
    public function forum_title($where_id){
      $data = $this->db->select("
        SELECT forum_title
        FROM ".PREFIX."forum_cat
        WHERE forum_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_title;
    }

    /**
     * forum_topics
     *
     * get list of topics for given category
     *
     * @param int $where_id = forum_id
     * @param string $limit data from Paginator class
     *
     * @return array returns forum topics list data
     */
    public function forum_topics($where_id, $limit = null){
      $data = $this->db->select("
          SELECT
              sub1.*,
              fpr_post_id,
              fpr_user_id AS LR_UserID,
              fpr_timestamp AS LR_TimeStamp,
              COUNT(fpr_post_id) AS total_topic_replys
          FROM (
              SELECT sub2.* FROM (
                  SELECT
                      fp.forum_post_id as forum_post_id, fp.forum_id as forum_id,
                      fp.forum_user_id as forum_user_id, fp.forum_title as forum_title,
                      fp.forum_edit_date as forum_edit_date,
                      fp.forum_timestamp as forum_timestamp,
                      fp.forum_status as forum_status, fpr.id as id,
                      fpr.fpr_post_id as fpr_post_id, fpr.fpr_id as fpr_id,
                      fpr.fpr_user_id as fpr_user_id, fpr.fpr_title as fpr_title,
                      fpr.fpr_edit_date as fpr_edit_date,
                      fpr.fpr_timestamp as fpr_timestamp,
                      GREATEST(fp.forum_timestamp, COALESCE(fpr.fpr_timestamp, '00-00-00 00:00:00')) AS tstamp
                      FROM ".PREFIX."forum_posts fp
                      LEFT JOIN ".PREFIX."forum_post_replies fpr
                      ON fp.forum_post_id = fpr.fpr_post_id
                      WHERE forum_id = :where_id
                      AND fp.allow = 'TRUE'
              ) sub2
                  ORDER BY tstamp DESC
                  $limit
          ) sub1
              GROUP BY forum_post_id
              ORDER BY tstamp DESC
      ",
      array(':where_id' => $where_id));
      return $data;
    }

    /**
     * forum_topic_cat_id
     *
     * get category ID that Topic is related to
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum category data (forum_id)
     */
    public function forum_topic_cat_id($where_id){
      $data = $this->db->select("
        SELECT forum_id
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_id;
    }

    /**
     * topic_title
     *
     * get Topic Title for header
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum topic data (forum_title)
     */
    public function topic_title($where_id){
      $data = $this->db->select("
        SELECT forum_title
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_title;
    }

    /**
     * topic_creator
     *
     * get Topic Creator userID
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum topic data (forum_user_id)
     */
    public function topic_creator($where_id){
      $data = $this->db->select("
        SELECT forum_user_id
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_user_id;
    }

    /**
     * topic_date
     *
     * get Topic timestamp for age
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum topic data (forum_timestamp)
     */
    public function topic_date($where_id){
      $data = $this->db->select("
        SELECT forum_timestamp
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_timestamp;
    }

    /**
     * topic_content
     *
     * get Topic Content data
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum topic data (forum_content)
     */
    public function topic_content($where_id){
      $data = $this->db->select("
        SELECT forum_content
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_content;
    }

    /**
     * topic_edit_date
     *
     * get Topic Content last edit date for age
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum topic data (forum_edit_date)
     */
    public function topic_edit_date($where_id){
      $data = $this->db->select("
        SELECT forum_edit_date
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_edit_date;
    }

    /**
     * topic_userID
     *
     * get Topic Content owner userID
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum topic data (forum_user_id)
     */
    public function topic_userID($where_id){
      $data = $this->db->select("
        SELECT forum_user_id
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_user_id;
    }

    /**
     * topic_status
     *
     * get Topic Status (Locked = 2 or Open = 1)
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum topic data (forum_status)
     */
    public function topic_status($where_id){
      $data = $this->db->select("
        SELECT forum_status
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_status;
    }

    /**
     * topic_allow
     *
     * get Topic Allowed Status (TRUE = show | FALSE = hide)
     *
     * @param int $where_id = forum_post_id
     *
     * @return string
     */
    public function topic_allow($where_id){
      $data = $this->db->select("
        SELECT allow
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->allow;
    }

    /**
     * topic_hidden_userID
     *
     * get Topic hidden userID
     *
     * @param int $where_id = forum_post_id
     *
     * @return int
     */
    public function topic_hidden_userID($where_id){
      $data = $this->db->select("
        SELECT hide_userID
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->hide_userID;
    }

    /**
     * topic_hidden_reason
     *
     * get Topic Hidden Reason
     *
     * @param int $where_id = forum_post_id
     *
     * @return string
     */
    public function topic_hidden_reason($where_id){
      $data = $this->db->select("
        SELECT hide_reason
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->hide_reason;
    }

    /**
     * topic_hidden_timestamp
     *
     * get Topic Hidden DateTime
     *
     * @param int $where_id = forum_post_id
     *
     * @return string
     */
    public function topic_hidden_timestamp($where_id){
      $data = $this->db->select("
        SELECT hide_timestamp
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->hide_timestamp;
    }

    /**
     * forum_topic_replys
     *
     * get list of topic replies for given topic
     *
     * @param int $where_id = fpr_post_id
     * @param string $limit data from Paginator class
     *
     * @return array returns forum topic reply list data
     */
    public function forum_topic_replys($where_id, $limit = null){
      $data = $this->db->select("
        SELECT
          *
        FROM
          ".PREFIX."forum_post_replies
        WHERE
          fpr_post_id = :where_id
        ORDER BY
          id
        ASC
        $limit
      ",
      array(':where_id' => $where_id));
      return $data;
    }

    /**
     * getTotalReplys
     *
     * get total count of topic replys for topic
     *
     * @param int $where_id = fpr_post_id
     *
     * @return int returns forum topic reply count
     */
    public function getTotalReplys($where_id){
      $data = $this->db->select("
        SELECT
          *
        FROM
          ".PREFIX."forum_post_replies
        WHERE
          fpr_post_id = :where_id
        ORDER BY
          id
        ASC
      ",
      array(':where_id' => $where_id));
      return count($data);
    }

    /**
     * sendTopic
     *
     * create new topic
     *
     * @param int $forum_user_id Current user's ID
     * @param int $forum_id Current Category's ID
     * @param string $forum_title New topic's title
     * @param string $forum_content New topic's content
     *
     * @return booleen true/false
     */
    public function sendTopic($forum_user_id, $forum_id, $forum_title, $forum_content){
      // Format the Content for database
      //$forum_content = nl2br($forum_content);
      // Update messages table
      $query = $this->db->insert(PREFIX.'forum_posts', array('forum_id' => $forum_id, 'forum_user_id' => $forum_user_id, 'forum_title' => $forum_title, 'forum_content' => $forum_content));
      $count = count($query);
      $last_insert_id = $this->db->lastInsertId('forum_post_id');
      // Check to make sure Topic was Created
      if($count > 0){
        return $last_insert_id;
      }else{
        return false;
      }
    }

    /**
     * sendNewImage
     *
     * add uploaded image to database
     *
     * @param in $user_id
     * @param string $imageName
     * @param string $imageLocation
     * @param int $imageSize
     * @param int $forumID
     * @param int $forumTopicID
     * @param int $forumTopicReplyID
     *
     * @return booleen true/false
     */
    public function sendNewImage($user_id, $imageName, $imageLocation, $imageSize, $forumID, $forumTopicID, $forumTopicReplyID = null){
      // Update forum images table
      $query = $this->db->insert(PREFIX.'forum_images', array('user_id' => $user_id, 'imageName' => $imageName, 'imageLocation' => $imageLocation, 'imageSize' => $imageSize, 'forumID' => $forumID, 'forumTopicID' => $forumTopicID, 'forumTopicReplyID' => $forumTopicReplyID));
      $count = count($query);
      // Check to make sure row was inserted
      if($count > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * getForumImagesTopic
     *
     * get topic images
     *
     * @param int $id
     *
     * @return string returns image url
     */
    public function getForumImagesTopic($topic_id){
      $data = $this->db->select("
        SELECT imageLocation
        FROM ".PREFIX."forum_images
        WHERE forumTopicID = :topic_id
        AND forumTopicReplyID IS NULL
      ",
      array(':topic_id' => $topic_id));
      $count = count($data);
      if($count > 0){
          return $data[0]->imageLocation;
      }
    }

    /**
     * updateTopic
     *
     * edit/update topic
     *
     * @param int $id Current Topic's ID
     * @param string $forum_title Topic's title
     * @param string $forum_content Topic's content
     *
     * @return booleen true/false
     */
    public function updateTopic($id, $forum_title, $forum_content){
      // Update messages table
      $query = $this->db->update(PREFIX.'forum_posts', array('forum_title' => $forum_title, 'forum_content' => $forum_content, 'forum_edit_date' => date('Y-m-d H:i:s')), array('forum_post_id' => $id));
      $count = count($query);
      // Check to make sure Topic was Created
      if($count > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * getTotalOwner
     *
     * get topic owner's user ID
     *
     * @param int $where_id = forum_post_id
     *
     * @return string returns forum topic data (forum_user_id)
     */
    public function getTopicOwner($where_id){
      $data = $this->db->select("
        SELECT forum_user_id
        FROM ".PREFIX."forum_posts
        WHERE forum_post_id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->forum_user_id;
    }

    /**
     * sendTopicReply
     *
     * create new topic reply
     *
     * @param int $fpr_user_id Current user ID
     * @param int $fpr_post_id Current Category ID
     * @param int $fpr_id Current Topic ID
     * @param string $forum_content New Reply's Content
     *
     * @return booleen true/false
     */
    public function sendTopicReply($fpr_user_id, $fpr_post_id, $fpr_id, $fpr_content, $subscribe){
      // Check for email subscription status
      if($subscribe == true){$subscribe = "true";}else{$subscribe = "false";}
      // Update messages table
      $query = $this->db->insert(PREFIX.'forum_post_replies', array('fpr_post_id' => $fpr_post_id, 'fpr_user_id' => $fpr_user_id, 'fpr_id' => $fpr_id, 'fpr_content' => $fpr_content, 'subscribe_email' => $subscribe));
      $count = count($query);
      // Check to make sure Topic was Created
      if($count > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * lastTopicReplyID
     *
     * get last topic reply inserted ID
     *
     * @param int $where_id = fpr_post_id
     *
     * @return string returns forum topic reply data (id)
     */
    public function lastTopicReplyID($where_id){
      $data = $this->db->select("
        SELECT id
        FROM ".PREFIX."forum_post_replies
        WHERE fpr_post_id = :where_id
        ORDER BY id DESC
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->id;
    }

    /**
     * updateTopicReply
     *
     * edit topic reply
     *
     * @param int $id Current Topic ID
     * @param string $fpr_content New Reply's Content
     *
     * @return booleen true/false
     */
    public function updateTopicReply($id, $fpr_content){
      // Update messages table
      $query = $this->db->update(PREFIX.'forum_post_replies', array('fpr_content' => $fpr_content, 'fpr_edit_date' => date('Y-m-d H:i:s')), array('id' => $id));
      $count = count($query);
      // Check to make sure Topic was Created
      if($count > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * getReplyOwner
     *
     * get topic reply owner user ID
     *
     * @param int $where_id = id
     *
     * @return string returns forum topic reply data (fpr_user_id)
     */
    public function getReplyOwner($where_id){
      $data = $this->db->select("
        SELECT fpr_user_id
        FROM ".PREFIX."forum_post_replies
        WHERE id = :where_id
        LIMIT 1
      ",
      array(':where_id' => $where_id));
      return $data[0]->fpr_user_id;
    }

    /**
     * updateTopicLockStatus
     *
     * edit topic lock/unlock status
     *
     * @param int $id Current Topic ID
     * @param int $setting (1)Open (2)Locked
     *
     * @return booleen true/false
     */
    public function updateTopicLockStatus($id, $setting){
      // Update messages table
      $query = $this->db->update(PREFIX.'forum_posts', array('forum_status' => $setting), array('forum_post_id' => $id));
      $count = count($query);
      // Check to make sure Topic was Created
      if($count > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * updateTopicHideStatus
     *
     * edit topic hide/unhide status
     *
     * @param int $id Current Topic ID
     * @param int $setting (FALSE)Show (TRUE)Hide
     * @param int $user_id
     * @param string $reason
     *
     * @return booleen true/false
     */
    public function updateTopicHideStatus($id, $setting, $user_id = "0", $reason = ""){
      // Update messages table
      $current_datetime = date("Y-m-d H:i:s");
      $query = $this->db->update(PREFIX.'forum_posts', array('allow' => $setting, 'hide_userID' => $user_id, 'hide_reason' => $reason, 'hide_timestamp' => $current_datetime), array('forum_post_id' => $id));
      $count = count($query);
      // Check to make sure Topic was Created
      if($count > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * updateReplyHideStatus
     *
     * edit topic reply hide/unhide status
     *
     * @param int $id Current Topic Reply ID
     * @param int $setting (FALSE)Show (TRUE)Hide
     * @param int $user_id
     * @param string $reason
     *
     * @return booleen true/false
     */
    public function updateReplyHideStatus($id, $setting, $user_id = "0", $reason = ""){
      // Update messages table
      $current_datetime = date("Y-m-d H:i:s");
      $query = $this->db->update(PREFIX.'forum_post_replies', array('allow' => $setting, 'hide_userID' => $user_id, 'hide_reason' => $reason, 'hide_timestamp' => $current_datetime), array('id' => $id));
      $count = count($query);
      // Check to make sure Topic was Created
      if($count > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * checkUserPosted
     *
     * checks if current user has posted to topic
     *
     * @param int $post_id = current topic ID
     * @param int $userID = current user ID
     *
     * @return boolean has user posted (true/false)
     */
    public function checkUserPosted($post_id, $user_id){
      $data = $this->db->select("
      SELECT * FROM (
  			 (
  			 SELECT *
  			 FROM ".PREFIX."forum_post_replies
  			 WHERE fpr_post_id = :post_id
  			 AND fpr_user_id = :user_id
  			 )
  			 UNION ALL
  			 (
  			 SELECT *
  			 FROM ".PREFIX."forum_posts
  			 WHERE forum_post_id = :post_id
  			 AND forum_user_id = :user_id
  			 )
  		) AS uniontable
      ",
      array(':post_id' => $post_id, ':user_id' => $user_id));
      $count = count($data);
      if($count > 0){
        return true;
      }else {
        return false;
      }
    }

    /**
     * checkTopicSubscribe
     *
     * checks if current user is subscribed to topic
     *
     * @param int $post_id = current topic ID
     * @param int $userID = current user ID
     *
     * @return boolean is user subscribed (true/false)
     */
    public function checkTopicSubscribe($post_id, $user_id){
      $data = $this->db->select("
      SELECT * FROM (
  			 (
  			 SELECT *
  			 FROM ".PREFIX."forum_post_replies
  			 WHERE fpr_post_id = :post_id
  			 AND fpr_user_id = :user_id
         AND subscribe_email = 'true'
  			 )
  			 UNION ALL
  			 (
  			 SELECT *
  			 FROM ".PREFIX."forum_posts
  			 WHERE forum_post_id = :post_id
  			 AND forum_user_id = :user_id
         AND subscribe_email = 'true'
  			 )
  		) AS uniontable
      ",
      array(':post_id' => $post_id, ':user_id' => $user_id));
      $count = count($data);
      if($count > 0){
        return true;
      }else {
        return false;
      }
    }

    /**
     * updateTopicSubcrition
     *
     * edit user's topic subcrition status (true/false)
     *
     * @param int $id Current Topic ID
     * @param int $user_id
     * @param int $setting (1)Open (2)Locked
     *
     * @return booleen true/false
     */
    public function updateTopicSubcrition($id, $user_id, $setting){
      // Update messages table
      $query_a = $this->db->update(PREFIX.'forum_posts', array('subscribe_email' => $setting), array('forum_post_id' => $id, 'forum_user_id' => $user_id));
      $query_b = $this->db->update(PREFIX.'forum_post_replies', array('subscribe_email' => $setting), array('fpr_post_id' => $id, 'fpr_user_id' => $user_id));
      $count_a = count($query_a);
      $count_b = count($query_b);
      $count = $count_a + $count_b;
      // Check to make sure Topic was Created
      if($count > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * getTopicSubscribeEmail
     *
     * get list of emails of all subscribed users to topic
     * sends email to all the users subscribed
     *
     * @param int $post_id = current topic ID
     * @param int $userID = current user ID
     *
     */
    public function sendTopicSubscribeEmails($post_id, $user_id, $topic_title, $topic_cat, $reply_content){
      // Get userID's for all that are set to be notified except current user
      $data = $this->db->select("
        SELECT * FROM (
          (
          SELECT fpr_user_id AS F_UID
          FROM ".PREFIX."forum_post_replies
          WHERE fpr_post_id = :post_id
          AND subscribe_email = 'true'
          AND NOT fpr_user_id = :userID
          GROUP BY fpr_user_id
          ORDER BY fpr_timestamp DESC
          )
          UNION ALL
          (
          SELECT forum_user_id AS F_UID
          FROM ".PREFIX."forum_posts
          WHERE forum_post_id = :post_id
          AND subscribe_email = 'true'
          AND NOT forum_user_id = :userID
          GROUP BY forum_user_id
          ORDER BY forum_timestamp DESC
          )
        ) AS uniontable
        GROUP BY `F_UID`
        ORDER BY `F_UID` ASC
      ",
      array(':post_id' => $post_id, ':userID' => $user_id));
      foreach($data as $row){
        // Get to user data
        $email_data = $this->db->select("
          SELECT email, username
          FROM ".PREFIX."users
          WHERE userID = :where_id
          LIMIT 1
        ",
        array(':where_id' => $row->F_UID));
        // Get from user data
        $email_from_data = $this->db->select("
          SELECT username
          FROM ".PREFIX."users
          WHERE userID = :where_id
          LIMIT 1
        ",
        array(':where_id' => $user_id));
        //EMAIL MESSAGE USING PHPMAILER
        $mail = new \Libs\PhpMailer\Mail();
        $mail->setFrom(SITEEMAIL, EMAIL_FROM_NAME);
        $mail->addAddress($email_data[0]->email);
        $mail_subject = SITE_TITLE . " - Forum - ".$email_from_data[0]->username." replied to {$topic_title}";
        $mail->subject($mail_subject);
        $body = "Hello ".$email_data[0]->username."<br/><br/>";
        $body .= SITE_TITLE . " - Forum Notification<br/>
                              <br/>
															Category: $topic_cat<br/>
															Topic: $topic_title<br/>
															Reply by: ".$email_from_data[0]->username."<br/>
                              <br/>
															Reply Content:<br/>
															************************<br/>
															$reply_content<br/>
															************************<br/>";
        $body .= "You may check the reply at: <b><a href=\"" . SITE_URL . "/Topic/$id\">" . SITE_TITLE . " Forum - $topic_title</a></b>";
        $mail->body($body);
        $mail->send();
      }
    }


    /**
     * forum_search
     *
     * get list of topics for given category
     *
     * @param int $search = Search String
     * @param string $limit data from Paginator class
     *
     * @return array returns forum topics list data
     */
    public function forum_search($search, $limit = null){
      $data = $this->db->select("
      (SELECT
          forum_id,
          forum_post_id,
          forum_timestamp as tstamp,
          forum_content as content,
          forum_title as title,
          forum_user_id,
          forum_status,
          allow,
          'main_post' AS post_type
        FROM
          `".PREFIX."forum_posts`
        WHERE
          (forum_content LIKE :search
        OR
          forum_title LIKE :search)
        AND
          allow = 'TRUE')
      UNION ALL
      (SELECT
          fpr.fpr_id,
          fpr.fpr_post_id,
          fpr.fpr_timestamp as tstamp,
          fpr.fpr_content as content,
          fp.forum_title as title,
          fpr.fpr_user_id as forum_user_id,
          fp.forum_status as forum_status,
          fpr.allow,
          'reply_post' AS post_type
        FROM
          `".PREFIX."forum_post_replies` fpr
        LEFT JOIN
          `".PREFIX."forum_posts` fp
        ON
          fp.forum_post_id = fpr.fpr_post_id
        WHERE
          fpr_content LIKE :search
        AND
          fpr.allow = 'TRUE')
      ORDER BY tstamp DESC
      $limit
      ",
      array(':search' => '%'.$search.'%'));
      return $data;
    }

    /**
    * forum_tracker
    *
    * updates/adds forum user tracking informatinon
    * tracking information used to track if a user has
    * viewed a new topic or reply
    *
    * @param int $user_id = User's ID
    * @param int $post_id = Forum Post ID
    *
    */

    public function forum_tracker($user_id = "", $post_id = "", $forum_id = ""){
        /* Make sure there is a user id and post id */
        if(!empty($user_id) && !empty($post_id) && !empty($forum_id)){
            /* Check to see is user has a record for post_id */
            $check_table = count($this->db->select("SELECT * FROM ".PREFIX."forum_tracker WHERE user_id=:user_id AND post_id=:post_id AND forum_id=:forum_id",array(":user_id"=>$user_id, ":post_id"=>$post_id, 'forum_id'=>$forum_id)));
            $current_date = date("Y-m-d H:i:s");
            if($check_table == 0){
                /* No Data for user - Create new entry */
                return $this->db->insert(PREFIX.'forum_tracker', array('user_id' => $user_id, 'post_id' => $post_id, 'forum_id' => $forum_id, 'last_visit' => $current_date));
            }else{
                /* Data found for user - Update current date */
                return $this->db->update(PREFIX.'forum_tracker', array('last_visit' => $current_date), array('user_id' => $user_id, 'post_id' => $post_id, 'forum_id' => $forum_id));
            }
        }
    }


}
