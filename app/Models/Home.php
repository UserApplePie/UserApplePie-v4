<?php
/**
* Home Models
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

namespace App\Models;

use App\System\Models,
    Libs\Database;

class Home extends Models {

  /**
  * This is just a sample test model function
  * If called it will not work due to test table not being in database
  * @param int $id
  * @return int data
  */
  public function test($id){
    $data = $this->db->select('SELECT * FROM '.PREFIX.'test WHERE id = :id',
      array(':id' => $id));
    return $data[0]->id;
  }

  /**
  * gets all system pages from database that are public
  * @param string $orderby
  * @return array dataset
  */
  public function getPublicURLs(){
    $system_pages = $this->db->select("
      SELECT
        p.url as url, p.edit_timestamp as edit_timestamp, p.timestamp as timestamp
      FROM
        ".PREFIX."pages p
      LEFT JOIN
        ".PREFIX."pages_permissions pp
      ON
        p.id = pp.page_id
      WHERE
        pp.group_id = '0'
      AND
        p.sitemap = 'true'
      ORDER BY
        url
      ");
    return $system_pages;
  }

  /**
   * get list of all recent posts in forum ordered by date.
   * @param int $limit
   * @return array dataset
   */
  public function getForumPosts($limit = "1000"){
    $data = $this->db->select("
        SELECT
            fp.forum_post_id as forum_post_id,
            fp.forum_url as forum_url,
            fp.forum_timestamp as forum_timestamp,
            fp.forum_edit_date as forum_edit_date
            FROM ".PREFIX."forum_posts fp
            WHERE fp.allow = 'TRUE'
            AND fp.forum_publish = '1'
        ORDER BY forum_timestamp DESC
        LIMIT $limit
    ");
    return $data;
  }

  /**
   * get latest forum post reply based on forum_post_id
   * @param int $forum_post_id
   * @param string data
   */
  public function getLatestForumReply($forum_post_id){
    $data = $this->db->select("
        SELECT
            fpr.fpr_timestamp as fpr_timestamp
            FROM ".PREFIX."forum_post_replies fpr
            WHERE fpr.allow = 'TRUE'
            AND fpr.forum_publish = '1'
            AND fpr.fpr_post_id = :forum_post_id
        ORDER BY fpr_timestamp DESC
        LIMIT 1
    ", array(':forum_post_id' => $forum_post_id));
    return $data[0]->fpr_timestamp;
  }
}
