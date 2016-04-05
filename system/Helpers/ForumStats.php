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

}
