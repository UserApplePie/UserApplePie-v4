<?php
/**
 * Members Recent
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
 */

 namespace App\Models;

 use App\System\Models,
 Libs\Database;

class Recent extends Models {

  /**
  * get list of all friends for current user.
  * @param int current $userID
  * @param int $limit
  * @return array dataset
  */
  public function getFriendsIDs($userID, $limit = '100'){
      /** Get list of User's friends and return their UserIDs **/
          $data = $this->db->select("
              SELECT
                  u.userID
              FROM
                  ".PREFIX."friends f
              LEFT JOIN
                  ".PREFIX."users u
                  ON (u.userID = f.uid1 OR u.userID = f.uid2)
              WHERE
              (
                  f.uid1 = :userID
              OR
                  f.uid2 = :userID
              )
              AND
              (
                  f.status1 = 1 AND f.status2 = 1
              )
              AND
                  u.userID != :userID
              GROUP BY
                  u.username
              LIMIT $limit
          ",
          array(':userID' => $userID));
      return $data;
  }

  /**
  * get list of users not friends with current user, but friends of friends.
  * @param int current userID
  * @return array dataset
  */
  public function getSuggestedFriends($userID){
      /** Get list of User's friends and return their UserIDs **/
      $friends = SELF::getFriendsIDs($userID);
      $suggestedFriends = [];
      /** Clean up Friends array to only userIDs **/
      foreach ($friends as $friend) {
        $user_friends[] = $friend->userID;
      }
      foreach ($friends as $friendId) {
        /** Friends friends list. **/
        $ff_list = SELF::getFriendsIDs($friendId->userID);
        foreach ($ff_list as $ffriendId) {
          /** If the friendsFriend(ff) is not us, and not our friend, he can be suggested **/
          if ($ffriendId->userID != $userID && !in_array($ffriendId->userID, $user_friends)) {
            /** The key is the suggested friend **/
            $suggestedFriends[$ffriendId->userID] = ['mutual_friends' => []];
            $ff_friends = SELF::getFriendsIDs($ffriendId->userID);
            foreach ($ff_friends as $ff_friendId) {
              /** If the friendsFriend(ff) is not us, and not our friend, he can be suggested **/
              if($ff_friendId->userID != $userID){
                /** If he is a friend of the current user, he is a mutual friend **/
                $suggestedFriends[$ffriendId->userID]['mutual_friends'] = count(SELF::getMutualFriendsIDs($userID, $ffriendId->userID));
              }
            }
          }
        }
      }
      return $suggestedFriends;
  }

  /**
  * get list of users not friends with current user, but friends of friends.
  * @param int $userID
  * @param int $cur_userID
  * @return array dataset
  */
  public function getMutualFriendsIDs($userID, $cur_userID){
      /** Get list of User's friends and return their UserIDs **/
      $friends = SELF::getFriendsIDs($userID);
      $user_friends = [];
      /** Clean up Friends array to only userIDs **/
      foreach ($friends as $friend) {
        $user_friends[] = $friend->userID;
      }
      $mutual_friends = [];
      $ff_list = SELF::getFriendsIDs($cur_userID);
      foreach ($ff_list as $ffriendId) {
        /** If the friendsFriend(ff) is not us, and our friend, he can be mutual **/
        if ($ffriendId->userID != $userID && $ffriendId->userID != $cur_userID && in_array($ffriendId->userID, $user_friends)) {
          $mutual_friends[] = $ffriendId->userID;
        }
      }
      return $mutual_friends;
  }

  /**
  * get list of all friends recent stuff.
  * @param int $userID
  * @param int $limit
  * @return array dataset
  */
  public function getRecent($userID, $limit = '10'){
      /** Setup Vars **/
      $sweets = SELF::sweets();
      $forum_posts = SELF::forum_posts();
      $forum_post_replies = SELF::forum_post_replies();
      $default_images = SELF::default_images();
      $profile_images = SELF::profile_images();
      $status = SELF::status();
      $status_cur_user = SELF::status_cur_user();
      /** Get Recents from databasse **/
      $data = $this->db->select("
        $sweets
        UNION ALL
        $forum_posts
        UNION ALL
        $forum_post_replies
        UNION ALL
        $default_images
        UNION ALL
        $profile_images
        UNION ALL
        $status
        UNION ALL
        $status_cur_user
        ORDER BY RP_01 DESC
        LIMIT $limit
      ",
      array(':userID' => $userID));
      return $data;
  }

  /**
  * get total rows of all friends recent stuff.
  * @param int $userID
  * @return int count
  */
  public function getRecentTotal($userID){
      /** Setup Vars **/
      $sweets = SELF::sweets();
      $forum_posts = SELF::forum_posts();
      $forum_post_replies = SELF::forum_post_replies();
      $default_images = SELF::default_images();
      $profile_images = SELF::profile_images();
      $status = SELF::status();
      $status_cur_user = SELF::status_cur_user();
      /** Get Recents from databasse **/
      $data = $this->db->select("
        SELECT sum(count) as total_rows FROM (
          $sweets
          UNION ALL
          $forum_posts
          UNION ALL
          $forum_post_replies
          UNION ALL
          $default_images
          UNION ALL
          $profile_images
          UNION ALL
          $status
          UNION ALL
          $status_cur_user
        ) as num_rows
      ",
      array(':userID' => $userID));
      return $data[0]->total_rows;
  }

  /**
  * recent friends sweets
  * @return array dataset
  */
  public function sweets(){
    /** Get Sweets Recent Data **/
    $sweets = "
      (SELECT
        count(*) as count,
        swe.timestamp AS RP_01,
        swe.sid AS RP_02,
        swe.sweet_location AS RP_03,
        swe.sweet_id AS RP_04,
        swe.sweet_sec_id AS RP_05,
        swe.sweet_owner_userid AS RP_06,
        fri.uid1 AS RP_07,
        fri.uid2 AS RP_08,
        fri.status1 AS RP_09,
        fri.status2 AS RP_10,
        'sweet' AS post_type
      FROM ".PREFIX."sweets swe
        LEFT JOIN ".PREFIX."friends fri
          ON (swe.sweet_owner_userid = fri.uid1 AND fri.uid2 = :userID)
          OR (swe.sweet_owner_userid = fri.uid2 AND fri.uid1 = :userID)
        WHERE ( fri.status1 = '1' AND fri.status2 = '1'
          AND NOT swe.sweet_owner_userid = :userID )
          AND NOT swe.sweet_location = 'Status'
          AND NOT swe.sweet_location = 'CommentStatus'
          AND NOT swe.sweet_location = 'CommentSecStatus'
        GROUP BY swe.sid)
    ";
    return $sweets;
  }

  /**
  * recent friends forum posts
  * @return array dataset
  */
  public function forum_posts(){
    /** Get Forum Posts Recent Data **/
    $forum_posts = "
      (SELECT
        count(*) as count,
        fp.forum_timestamp AS RP_01,
        fp.forum_post_id AS RP_02,
        fp.forum_id AS RP_03,
        fp.forum_title AS RP_04,
        fp.forum_publish AS RP_05,
        fp.forum_user_id AS RP_06,
        fri.uid1 AS RP_07,
        fri.uid2 AS RP_08,
        fri.status1 AS RP_09,
        fri.status2 AS RP_10,
        'forum_posts' AS post_type
      FROM ".PREFIX."forum_posts fp
        LEFT JOIN ".PREFIX."friends fri
          ON (fp.forum_user_id = fri.uid1 AND fri.uid2 = :userID)
          OR (fp.forum_user_id = fri.uid2 AND fri.uid1 = :userID)
        WHERE ( fri.status1 = '1' AND fri.status2 = '1'
          AND NOT fp.forum_user_id = :userID )
          AND fp.allow = 'TRUE'
          AND (fp.forum_publish = '1')
        GROUP BY fp.forum_post_id)
    ";
    return $forum_posts;
  }

  /**
  * recent friends forum post replies
  * @return array dataset
  */
  public function forum_post_replies(){
    /** Get Forum Posts Recent Data **/
    $forum_post_replies = "
      (SELECT
        count(*) as count,
        fpr.fpr_timestamp AS RP_01,
        fpr.fpr_post_id AS RP_02,
        fpr.fpr_id AS RP_03,
        fpr.fpr_id AS RP_04,
        fpr.id AS RP_05,
        fpr.fpr_user_id AS RP_06,
        fri.uid1 AS RP_07,
        fri.uid2 AS RP_08,
        fri.status1 AS RP_09,
        fri.status2 AS RP_10,
        'forum_post_replies' AS post_type
      FROM ".PREFIX."forum_post_replies fpr
        LEFT JOIN ".PREFIX."friends fri
          ON (fpr.fpr_user_id = fri.uid1 AND fri.uid2 = :userID)
          OR (fpr.fpr_user_id = fri.uid2 AND fri.uid1 = :userID)
        WHERE ( fri.status1 = '1' AND fri.status2 = '1'
          AND NOT fpr.fpr_user_id = :userID )
          AND fpr.allow = 'TRUE'
          AND (fpr.forum_publish = '1')
        GROUP BY fpr.id)
    ";
    return $forum_post_replies;
  }

  /**
  * recent friends default images
  * @return array dataset
  */
  public function default_images(){
    /** Get Forum Posts Recent Data **/
    $default_images = "
      (SELECT
        count(*) as count,
        ui.update_timestamp AS RP_01,
        ui.id AS RP_02,
        ui.userImage AS RP_03,
        ui.defaultImage AS RP_04,
        ui.timestamp AS RP_05,
        ui.userID AS RP_06,
        fri.uid1 AS RP_07,
        fri.uid2 AS RP_08,
        fri.status1 AS RP_09,
        fri.status2 AS RP_10,
        'default_images' AS post_type
      FROM ".PREFIX."users_images ui
        LEFT JOIN ".PREFIX."friends fri
          ON (ui.userID = fri.uid1 AND fri.uid2 = :userID)
          OR (ui.userID = fri.uid2 AND fri.uid1 = :userID)
        WHERE ( fri.status1 = '1' AND fri.status2 = '1'
          AND NOT ui.userID = :userID )
          AND ui.defaultImage = '1'
        GROUP BY ui.id)
    ";
    return $default_images;
  }

  /**
  * recent friends profile images
  * @return array dataset
  */
  public function profile_images(){
    /** Get Forum Posts Recent Data **/
    $profile_images = "
      (SELECT
        count(*) as count,
        ui.timestamp AS RP_01,
        ui.id AS RP_02,
        ui.userImage AS RP_03,
        ui.defaultImage AS RP_04,
        ui.timestamp AS RP_05,
        ui.userID AS RP_06,
        fri.uid1 AS RP_07,
        fri.uid2 AS RP_08,
        fri.status1 AS RP_09,
        fri.status2 AS RP_10,
        'profile_images' AS post_type
      FROM ".PREFIX."users_images ui
        LEFT JOIN ".PREFIX."friends fri
          ON (ui.userID = fri.uid1 AND fri.uid2 = :userID)
          OR (ui.userID = fri.uid2 AND fri.uid1 = :userID)
        WHERE ( fri.status1 = '1' AND fri.status2 = '1'
          AND NOT ui.userID = :userID )
        GROUP BY UNIX_TIMESTAMP(ui.timestamp) DIV 600)
    ";
    return $profile_images;
  }

  /**
  * recent friends status updates
  * @return array dataset
  */
  public function status(){
    /** Get Status Recent Data **/
    $status = "
      (SELECT
        count(*) as count,
        s.timestamp AS RP_01,
        s.id AS RP_02,
        s.status_feeling AS RP_03,
        s.status_content AS RP_04,
        s.timestamp AS RP_05,
        s.status_userID AS RP_06,
        fri.uid1 AS RP_07,
        fri.uid2 AS RP_08,
        fri.status1 AS RP_09,
        fri.status2 AS RP_10,
        'status' AS post_type
      FROM ".PREFIX."status s
        LEFT JOIN ".PREFIX."friends fri
          ON (s.status_userID = fri.uid1 AND fri.uid2 = :userID)
          OR (s.status_userID = fri.uid2 AND fri.uid1 = :userID)
        WHERE ( fri.status1 = '1' AND fri.status2 = '1'
          AND NOT s.status_userID = :userID )
        GROUP BY s.id)
    ";
    return $status;
  }

  /**
  * recent current user status updates
  * @return array dataset
  */
  public function status_cur_user(){

    /** Get Status Recent Data **/
    $status_cur_user = "
      (SELECT
        count(*) as count,
        s.timestamp AS RP_01,
        s.id AS RP_02,
        s.status_feeling AS RP_03,
        s.status_content AS RP_04,
        s.timestamp AS RP_05,
        s.status_userID AS RP_06,
        fri.uid1 AS RP_07,
        fri.uid2 AS RP_08,
        fri.status1 AS RP_09,
        fri.status2 AS RP_10,
        'status' AS post_type
      FROM ".PREFIX."status s
        LEFT JOIN ".PREFIX."friends fri
          ON (s.status_userID = fri.uid1 AND fri.uid2 = :userID)
          OR (s.status_userID = fri.uid2 AND fri.uid1 = :userID)
        WHERE ( s.status_userID = :userID )
        GROUP BY s.id)
    ";
    return $status_cur_user;
  }

  /**
  * add status update for current user
  * @param int $userID
  * @param string $status_feeling
  * @param string $status_content
  * @return int count
  */
  public function addStatus($userID = '', $status_feeling = '', $status_content = ''){
    return $this->db->insert(PREFIX.'status', array('status_userID' => $userID, 'status_feeling' => $status_feeling, 'status_content' => $status_content));
  }

  /**
  * get status data based on user and status id.
  * @param int $userID
  * @param int $id
  * @return array dataset
  */
  public function getStatus($userID, $id){
      /** Get Status Data **/
      $data = $this->db->select("
        SELECT
          s.timestamp,
          s.id,
          s.status_feeling,
          s.status_content,
          s.timestamp,
          s.status_userID
        FROM ".PREFIX."status s
          WHERE s.status_userID = :userID
          AND s.id = :id
          GROUP BY s.id
      ",
      array(':userID' => $userID, ':id' => $id));
      return $data;
  }

  /**
  * update status update for current user
  * @param int $userID
  * @param int $id
  * @param string $status_feeling
  * @param string $status_content
  * @return int count
  */
  public function updateStatus($userID = '', $id = '', $status_feeling = '', $status_content = ''){
    return $this->db->update(PREFIX.'status', array('status_feeling' => $status_feeling, 'status_content' => $status_content), array('id' => $id, 'status_userID' => $userID));
  }

}
