<?php
namespace Helpers;

use Helpers\Database;
use Helpers\Cookie;

class ForumStats
{

  private static $db;

  // Get user's total post count from db
	public static function getTotalPosts($where_id){
		self::$db = Database::get();
		$data = self::$db->select("
				SELECT
					*
				FROM
					".PREFIX."forum_posts
				WHERE
			    forum_user_id = :userID
				",
			array(':userID' => $where_id));
    $data2 = self::$db->select("
				SELECT
					*
				FROM
					".PREFIX."forum_posts_replys
				WHERE
			    fpr_user_id = :userID
				",
			array(':userID' => $where_id));
      $total = count($data) + count($data2);
		return $total;
	}

  /**
   * forum_recent_posts
   *
   * get list of all recent posts in forum ordered by date.
   *
   * @return array returns all recent forum posts
   */
  public function forum_recent_posts(){
    self::$db = Database::get();
		$data = self::$db->select("
      SELECT sub.*
      FROM
      (SELECT
        fp.forum_post_id as forum_post_id, fp.forum_id as forum_id,
        fp.forum_user_id as forum_user_id, fp.forum_title as forum_title,
        fp.forum_content as forum_content, fp.forum_edit_date as forum_edit_date,
        fp.forum_timestamp as forum_timestamp, fpr.id as id,
        fpr.fpr_post_id as fpr_post_id, fpr.fpr_id as fpr_id,
        fpr.fpr_user_id as fpr_user_id, fpr.fpr_title as fpr_title,
        fpr.fpr_content as fpr_content, fpr.fpr_edit_date as fpr_edit_date,
        fpr.fpr_timestamp as fpr_timestamp,
        GREATEST(fp.forum_timestamp, COALESCE(fpr.fpr_timestamp, '00-00-00 00:00:00')) AS tstamp
        FROM ".PREFIX."forum_posts fp
        LEFT JOIN ".PREFIX."forum_posts_replys fpr
        ON fp.forum_post_id = fpr.fpr_post_id
        WHERE fp.allow = 'TRUE'
        ORDER BY fpr.fpr_timestamp DESC
      ) sub
      GROUP BY forum_post_id
      ORDER BY tstamp DESC
      LIMIT 10
    ");
    return $data;
  }

}
