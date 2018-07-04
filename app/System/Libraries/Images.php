<?php
/**
* Images Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

namespace Libs;

use Libs\Database,
    Libs\Form,
    Libs\Request;

class Images
{

  /**
   * Ready the database for use in this helper.
   *
   * @var string
   */
  private static $db;

  /**
 * getImageCountForum
 *
 * gets total number of images releated to Forum
 *
 * @param string $location
 * @param int $topic_id
 * @param int $topic_id
 *
 * @return string returns count data
 */
  public static function getImageCountForum($location = null, $topic_id = null, $topic_reply_id = null){
    // Check to see if we are getting count for Forum Topic
    if($location == "Topic"){
      // Get image count for Forum Topics
      self::$db = Database::get();
      $data = self::$db->select("
        SELECT imageLocation
        FROM ".PREFIX."forum_images
        WHERE forumTopicID = :topic_id
      ",
      array(':topic_id' => $topic_id));
      return count($data);
    }
  }

  /**
   * getForumImagesTopicReply
   *
   * get topic images
   *
   * @param int $topic_id
   * @param int $topic_reply_id
   *
   * @return string returns image url
   */
  public static function getForumImagesTopicReply($topic_id = null, $topic_reply_id = null){
    // Get images for Forum Topic Reply
    self::$db = Database::get();
    $data = self::$db->select("
      SELECT imageName, imageLocation
      FROM ".PREFIX."forum_images
      WHERE forumTopicID = :topic_id
      AND forumTopicReplyID = :topic_reply_id
    ",
    array(':topic_id' => $topic_id, ':topic_reply_id' => $topic_reply_id));
    (isset($data[0]->imageName)) ? $image = $data[0]->imageLocation.$data[0]->imageName : $image = "";
    return $image;
  }
}
