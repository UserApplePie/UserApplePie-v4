<?php
namespace Libs;

use Libs\Database;
use Libs\Cookie;

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
					".PREFIX."forum_post_replies
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
    public static function forum_recent_posts($limit = "10", $forum_id = ""){
        // Setup to get data based on forum_id if one is set
        if(!empty($forum_id)){
            $forum_id_data = "AND forum_id = $forum_id";
        }else{
            $forum_id_data = "";
        }
        self::$db = Database::get();
        $data = self::$db->select("
            SELECT sub1.* FROM (
                SELECT sub2.* FROM (
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
                        $forum_id_data
                ) sub2
                    ORDER BY tstamp DESC
                    LIMIT $limit
            ) sub1
                GROUP BY forum_post_id
                ORDER BY tstamp DESC
        ");
        return $data;
    }

    /**
     * checkUserRead
     *
     * check forum tracker to see if user has read topic since last post
     *
     * @param int user_id
     * @param int post_id
     * @param datetime post_timestamp
     *
     * @return boolean true/false true=read false=unread
     */

    public static function checkUserRead($user_id, $post_id, $post_timestamp){
        if(isset($user_id)){
            self::$db = Database::get();
            $data = self::$db->select("
                SELECT *
                    FROM ".PREFIX."forum_tracker
                    WHERE user_id = :user_id
                    AND post_id = :post_id
                LIMIT 1
            ", array('user_id' => $user_id, ':post_id' => $post_id));
            /* Check to see if any data */
            $count = count($data);
            if($count > 0){
                if($data[0]->last_visit < $post_timestamp){
                    return true;
                }else{
                    return false;
                }
            }else{
                return true;
            }
        }
    }

    /**
     * checkUserRead
     *
     * check forum tracker to see if user has read topic since last post
     *
     * @param int user_id
     * @param int forum_id
     *
     * @return boolean true/false true=read false=unread
     */

    public static function checkUserReadCat($user_id, $forum_id){
        if(isset($user_id)){
            self::$db = Database::get();
            /* Get Latest Post related to forum_id */
            $data_forum = self::$db->select("
                SELECT
                    sub1.tstamp, sub1.forum_post_id
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
                            WHERE fp.forum_id = :forum_id
                            AND fp.allow = 'TRUE'
                    ) sub2
                        ORDER BY tstamp DESC
                ) sub1
                    GROUP BY forum_post_id
                    ORDER BY tstamp DESC
            ",
            array(':forum_id' => $forum_id));
            /* Check to see if any data */
            $count = count($data_forum);
            if($count > 0){
                foreach ($data_forum as $data) {
                    /* Get latest update by user */
                    $data_tracker = self::$db->select("
                        SELECT last_visit
                            FROM ".PREFIX."forum_tracker
                            WHERE user_id = :user_id
                            AND post_id = :post_id
                            AND forum_id = :forum_id
                            ORDER BY last_visit DESC
                            LIMIT 1
                    ", array('user_id' => $user_id, 'post_id' => $data->forum_post_id, ':forum_id' => $forum_id));
                    /* Check to see if any data */
                    $count_tracker = count($data_tracker);
                    if($count_tracker > 0){
                        if($data_tracker[0]->last_visit < $data->tstamp){
                            $unread[] = true;
                        }
                    }else{
                        $unread[] = true;
                    }
                }
                if(isset($unread)){ $unread_count = count($unread); }else{ $unread_count = 0; }
                if($unread_count > 0){
                    return true;
                }else{
                    return false;
                }

            }else{
                return true;
            }
        }
    }

}
