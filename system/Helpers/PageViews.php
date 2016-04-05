<?php
/**
 * Page Views Helper
 * Displays current view count for given Page
 * Automaticly adds view count to db when enabled
 *
 * @author David "DaVaR" Sargent - davar@thedavar.net
 * @version 2.2
 * @date Jan 14, 2016
 * @date updated Jan 14, 2016
 */

namespace Helpers;

use Helpers\Database;

class PageViews
{
  private static $db;

  /**
 * views
 *
 * gets view count
 * updates view count if enabled
 *
 * @param boolean $addView (true/false)
 * @param string $view_location (Section of site where view is)
 * @param int $view_id (ID of post where view is)
 * @param int $view_owner_userid (ID of user viewing)
 *
 * @return string returns views count
 */
  public static function views($addView = null, $view_id = null, $view_location = null, $view_owner_userid = "0"){
    // Get data from server
    // Get current user's IP address
    $view_user_ip = $_SERVER['REMOTE_ADDR'];
    // Get full URL address for current page
    $view_uri = $_SERVER['REQUEST_URI'];
    $view_server = $_SERVER['SERVER_NAME'];

    // Check to see if user is a guest... then set their id to 0
    if($view_owner_userid == null){$view_owner_userid = "0";};

    // Check to see if current user has already viewed page
    self::$db = Database::get();
    $already_view_data = self::$db->select("
        SELECT
          *
        FROM
          ".PREFIX."views
        WHERE
          view_id = :view_id
            AND view_location = :view_location
            AND view_owner_userid = :view_owner_userid
        ",
      array(':view_id' => $view_id,
            ':view_location' => $view_location,
            ':view_owner_userid' => $view_owner_userid));
      $already_view_count = count($already_view_data);

      // Check to see if current user has already viewed this page
      if($already_view_count < 1){
        // Check to see if this is a page that we want to add view to
        if($addView == "true"){
          // Insert New View Into Database
          self::$db = Database::get();
          $view_add_data = self::$db->insert(
            PREFIX.'views',
              array('view_id' => $view_id, 'view_sec_id' => $view_sec_id,
                    'view_location' => $view_location,
                    'view_user_ip' => $view_user_ip, 'view_server' => $view_server,
                    'view_uri' => $view_uri, 'view_owner_userid' => $view_owner_userid));
        }// End addView Check
      }// End already viewed check

    // Output View Count for display on page
    // Get view count from db
    self::$db = Database::get();
    $view_data = self::$db->select("
        SELECT
          *
        FROM
          ".PREFIX."views
        WHERE
          view_id = :view_id
            AND view_location = :view_location
        ",
      array(':view_id' => $view_id,
            ':view_location' => $view_location));
    return count($view_data);
  }

}
