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
  * getFriendsIDs
  *
  * get list of all friends for current user.
  *
  * @param int current userID
  *
  * @return array returns all friends ids
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
  * getSuggestedFriends
  *
  * get list of users not friends with current user, but friends of friends.
  *
  * @param int current userID
  *
  * @return array returns all friends ids
  */
  public function getSuggestedFriends($userID){
      /** Get list of User's friends and return their UserIDs **/
      $friends = SELF::getFriendsIDs($userID);
      $suggestedFriends = [];

      foreach ($friends as $friendId) {
        # Friends friends list.
        $ff_list = SELF::getFriendsIDs($friendId->userID);

        foreach ($ff_list as $ffriendId) {
          # If the friendsFriend(ff) is not us, and not our friend, he can be suggested
          if ($ffriendId->userID != $userID && !in_array($ffriendId->userID, $friends)) {

            # The key is the suggested friend
            $suggestedFriends[$ffriendId->userID] = ['mutual_friends' => []];
            $ff_friends = SELF::getFriendsIDs($ffriendId->userID);

            foreach ($ff_friends as $ff_friendId) {
              if (in_array($ff_friendId, $friends)) {
                # If he is a friend of the current user, he is a mutual friend
                $suggestedFriends[$ffriendId->userID]['mutual_friends'][] = $ff_friendId->userID;
              }
            }

          }
        }

      }
      return $suggestedFriends;
  }

  /**
  * getRecent
  *
  * get list of all friends recent stuff.
  *
  * @param int current userID
  *
  * @return array returns all friends ids
  */
  public function getRecent($userID, $limit = '10'){
      /** Setup Vars **/
      $sweets = SELF::sweets();
      $forum_posts = SELF::forum_posts();
      $forum_post_replies = SELF::forum_post_replies();

      /** Get Recents from databasse **/
      $data = $this->db->select("
        $sweets
        UNION ALL
        $forum_posts
        UNION ALL
        $forum_post_replies
        ORDER BY RP_01 DESC
        LIMIT $limit
      ",
      array(':userID' => $userID));

      return $data;
  }

  /**
  * getRecentTotal
  *
  * get total rows of all friends recent stuff.
  *
  * @param int current userID
  *
  * @return array returns all friends ids
  */
  public function getRecentTotal($userID){
      /** Setup Vars **/
      $sweets = SELF::sweets();
      $forum_posts = SELF::forum_posts();
      $forum_post_replies = SELF::forum_post_replies();

      /** Get Recents from databasse **/
      $data = $this->db->select("
        SELECT sum(count) as total_rows FROM (
          $sweets
          UNION ALL
          $forum_posts
          UNION ALL
          $forum_post_replies
        ) as num_rows
      ",
      array(':userID' => $userID));

      return $data[0]->total_rows;
  }



  /**
  * sweets
  *
  * @return array returns sql
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
        GROUP BY swe.sid)
    ";

    return $sweets;

  }

  /**
  * forum_posts
  *
  * @return array returns sql
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
  * forum_post_replies
  *
  * @return array returns sql
  */
  public function forum_post_replies(){

    /** Get Forum Posts Recent Data **/
    $forum_post_replies = "
      (SELECT
        count(*) as count,
        fpr.fpr_timestamp AS RP_01,
        fpr.fpr_post_id AS RP_02,
        fpr.fpr_id AS RP_03,
        fpr.fpr_title AS RP_04,
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

}
