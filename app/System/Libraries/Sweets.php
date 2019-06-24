<?php
/**
 * Sweets Plugin
 * Displays current sweet count for given Page
 * Automaticly adds sweet count to db when enabled
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
 */

namespace Libs;

use Libs\Database,
    Libs\Form,
    Libs\Request;

class Sweets
{

  /**
   * Ready the database for use in this helper.
   *
   * @var string
   */
  private static $db;

  /**
 * getSweets
 *
 * gets sweet count
 *
 * @param int $sweet_id (ID of post where sweet is)
 * @param string $sweet_location (Section of site where sweet is)
 * @param int $sweet_sec_id (ID of secondary post)
 *
 * @return string returns sweet data
 */
  public static function getSweets($sweet_id = null, $sweet_location = null, $sweet_sec_id = null, $display_type = "btn"){
    // Get sweet count from db
    // Check to see if this is a sweet for a secondary post
//    var_dump($sweet_sec_id);
    if($sweet_sec_id == null){
      // Sweet is for main post
      self::$db = Database::get();
      $sweet_count = self::$db->select("
          SELECT
            *
          FROM
            ".PREFIX."sweets
          WHERE
            sweet_id = :sweet_id
              AND sweet_location = :sweet_location
              AND sweet_sec_id = :sweet_sec_id
          ",
        array(':sweet_id' => $sweet_id,
              ':sweet_location' => $sweet_location,
              ':sweet_sec_id' => '0'));
      $sweet_total = count($sweet_count);
    }else{
      // Sweet is for secondary post
      self::$db = Database::get();
      $sweet_count = self::$db->select("
          SELECT
            *
          FROM
            ".PREFIX."sweets
          WHERE
            sweet_id = :sweet_id
              AND sweet_location = :sweet_location
              AND sweet_sec_id = :sweet_sec_id
          ",
        array(':sweet_id' => $sweet_id,
              ':sweet_location' => $sweet_location,
              ':sweet_sec_id' => $sweet_sec_id));
      $sweet_total = count($sweet_count);
    }
    if($display_type == "btn"){
      $sweet_display = " <div class='btn btn-success btn-sm'>".SWEET_TITLE_DISPLAY." <span class='badge badge-light'>$sweet_total</span></div> ";
    }else if($display_type = "num"){
      $sweet_display = $sweet_total;
    }
    return $sweet_display;
  }

  /**
 * displaySweetsButton
 *
 * display sweets button
 * update/add sweets type
 *
 * @param int $sweet_id (ID of post where sweet is)
 * @param string $sweet_location (Section of site where sweet is)
 * @param int $sweet_owner_userid (ID of user sweeting)
 * @param int $sweet_type (sweet/unsweet)
 * @param int $sweet_sec_id (ID of secondary post)
 * @param string $sweet_url (redirect url)
 *
 * @return string returns sweet button data
 */
  public static function displaySweetsButton($sweet_id = null, $sweet_location = null, $sweet_owner_userid = null, $sweet_sec_id = null, $sweet_url = null){
    // Make sure that there is a user logged in
    if($sweet_owner_userid != null){
      // Check to see if current user has already sweeted page
      self::$db = Database::get();
      // Check to see if this is main post
      if($sweet_sec_id == null){
        // Sweet is for main post
        $sweet_data = self::$db->select("
            SELECT
              *
            FROM
              ".PREFIX."sweets
            WHERE
              sweet_id = :sweet_id
                AND sweet_location = :sweet_location
                AND sweet_owner_userid = :sweet_owner_userid
            ",
          array(':sweet_id' => $sweet_id,
                ':sweet_location' => $sweet_location,
                ':sweet_owner_userid' => $sweet_owner_userid));
          // Get count to see if user has already submitted a sweet
          $sweet_count = count($sweet_data);
      }else{
        // Sweet is for secondary post
        $sweet_data = self::$db->select("
            SELECT
              *
            FROM
              ".PREFIX."sweets
            WHERE
              sweet_id = :sweet_id
                AND sweet_location = :sweet_location
                AND sweet_owner_userid = :sweet_owner_userid
                AND sweet_sec_id = :sweet_sec_id
            ",
          array(':sweet_id' => $sweet_id,
                ':sweet_location' => $sweet_location,
                ':sweet_owner_userid' => $sweet_owner_userid,
                ':sweet_sec_id' => $sweet_sec_id));
          // Get count to see if user has already submitted a sweet
          $sweet_count = count($sweet_data);
        }
        //echo " ($sweet_count) ";
        // Setup Sweet Button Form
        $sweet_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline'));
        // Check to see if user has alreadyed sweeted
        if($sweet_count > 0){
          // Display UnSweet button if user has already sweeted
          $sweet_button_display .= " <input type='hidden' name='delete_sweet' value='true' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_id' value='$sweet_id' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_sec_id' value='$sweet_sec_id' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_location' value='$sweet_location' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_owner_userid' value='$sweet_owner_userid' /> ";
          $sweet_button_display .= " <button type='submit' class='btn btn-warning btn-sm' value='Sweet' name='sweet'> Un".SWEET_BUTTON_DISPLAY." </button> ";
        }else{
          // Display Sweet Button if user has not yet sweeted
          $sweet_button_display .= " <input type='hidden' name='submit_sweet' value='true' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_id' value='$sweet_id' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_sec_id' value='$sweet_sec_id' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_location' value='$sweet_location' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_owner_userid' value='$sweet_owner_userid' /> ";
          $sweet_button_display .= " <button type='submit' class='btn btn-success btn-sm' value='Sweet' name='sweet'> ".SWEET_BUTTON_DISPLAY." </button> ";
        }
        // Close the Sweet Button Form
        $sweet_button_display .= Form::close();;

        // Check to see if user is submitting a new sweet
        $submit_sweet = Request::post('submit_sweet');
        $delete_sweet = Request::post('delete_sweet');
        $post_sweet_id = Request::post('sweet_id');
        $post_sweet_location = Request::post('sweet_location');
        $post_sweet_owner_userid = Request::post('sweet_owner_userid');
        $post_sweet_sec_id = Request::post('sweet_sec_id');
        $post_sweet_url = Request::post('sweet_url');
        if($submit_sweet == "true" && $post_sweet_id == $sweet_id && $post_sweet_sec_id == $sweet_sec_id && $post_sweet_location == $sweet_location){
          self::addSweet($post_sweet_id, $post_sweet_location, $post_sweet_owner_userid, $post_sweet_sec_id, $sweet_url);
        }else if($delete_sweet == "true" && $post_sweet_id == $sweet_id && $post_sweet_sec_id == $sweet_sec_id && $post_sweet_location == $sweet_location){
          self::removeSweet($post_sweet_id, $post_sweet_location, $post_sweet_owner_userid, $post_sweet_sec_id, $sweet_url);
        }
        // Ouput the sweet/unsweet button
        return $sweet_button_display;
    }
  }

  /**
 * displaySweetsLink
 *
 * display sweets link with badge
 * update/add sweets type
 *
 * @param int $sweet_id (ID of post where sweet is)
 * @param string $sweet_location (Section of site where sweet is)
 * @param int $sweet_owner_userid (ID of user sweeting)
 * @param int $sweet_type (sweet/unsweet)
 * @param int $sweet_sec_id (ID of secondary post)
 * @param string $sweet_url (redirect url)
 *
 * @return string returns sweet button data
 */
  public static function displaySweetsLink($sweet_id = null, $sweet_location = null, $sweet_owner_userid = null, $sweet_sec_id = null, $sweet_url = null){
    // Make sure that there is a user logged in
    if($sweet_owner_userid != null){
      // Check to see if current user has already sweeted page
      self::$db = Database::get();
      // Check to see if this is main post
      if($sweet_sec_id == null){
        // Sweet is for main post
        $sweet_data = self::$db->select("
            SELECT
              *
            FROM
              ".PREFIX."sweets
            WHERE
              sweet_id = :sweet_id
                AND sweet_location = :sweet_location
                AND sweet_owner_userid = :sweet_owner_userid
            ",
          array(':sweet_id' => $sweet_id,
                ':sweet_location' => $sweet_location,
                ':sweet_owner_userid' => $sweet_owner_userid));
          // Get count to see if user has already submitted a sweet
          $sweet_count = count($sweet_data);
      }else{
        // Sweet is for secondary post
        $sweet_data = self::$db->select("
            SELECT
              *
            FROM
              ".PREFIX."sweets
            WHERE
              sweet_id = :sweet_id
                AND sweet_location = :sweet_location
                AND sweet_owner_userid = :sweet_owner_userid
                AND sweet_sec_id = :sweet_sec_id
            ",
          array(':sweet_id' => $sweet_id,
                ':sweet_location' => $sweet_location,
                ':sweet_owner_userid' => $sweet_owner_userid,
                ':sweet_sec_id' => $sweet_sec_id));
          // Get count to see if user has already submitted a sweet
          $sweet_count = count($sweet_data);
        }
        //echo " ($sweet_count) ";
        // Setup Sweet Button Form
        $sweet_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline'));
        $total_badge = self::getTotalSweetsBadge($sweet_id, $sweet_location, $sweet_sec_location);
        // Check to see if user has alreadyed sweeted
        if($sweet_count > 0){
          // Display UnSweet button if user has already sweeted
          $sweet_button_display .= " <input type='hidden' name='delete_sweet' value='true' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_id' value='$sweet_id' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_sec_id' value='$sweet_sec_id' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_location' value='$sweet_location' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_owner_userid' value='$sweet_owner_userid' /> ";
          $sweet_button_display .= " <button type='submit' class='btn btn-link btn-sm font-weight-bold' style='padding: 2px' value='Sweet' name='sweet'> Un".SWEET_BUTTON_DISPLAY."$total_badge</button> ";
        }else{
          // Display Sweet Button if user has not yet sweeted
          $sweet_button_display .= " <input type='hidden' name='submit_sweet' value='true' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_id' value='$sweet_id' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_sec_id' value='$sweet_sec_id' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_location' value='$sweet_location' /> ";
          $sweet_button_display .= " <input type='hidden' name='sweet_owner_userid' value='$sweet_owner_userid' /> ";
          $sweet_button_display .= " <button type='submit' class='btn btn-link btn-sm font-weight-bold' style='padding: 2px' value='Sweet' name='sweet'> ".SWEET_BUTTON_DISPLAY."$total_badge</button> ";
        }
        // Close the Sweet Button Form
        $sweet_button_display .= Form::close();;

        // Check to see if user is submitting a new sweet
        $submit_sweet = Request::post('submit_sweet');
        $delete_sweet = Request::post('delete_sweet');
        $post_sweet_id = Request::post('sweet_id');
        $post_sweet_location = Request::post('sweet_location');
        $post_sweet_owner_userid = Request::post('sweet_owner_userid');
        $post_sweet_sec_id = Request::post('sweet_sec_id');
        if($submit_sweet == "true" && $post_sweet_id == $sweet_id && $post_sweet_sec_id == $sweet_sec_id && $post_sweet_location == $sweet_location){
          self::addSweet($post_sweet_id, $post_sweet_location, $post_sweet_owner_userid, $post_sweet_sec_id, $sweet_url);
        }else if($delete_sweet == "true" && $post_sweet_id == $sweet_id && $post_sweet_sec_id == $sweet_sec_id && $post_sweet_location == $sweet_location){
          self::removeSweet($post_sweet_id, $post_sweet_location, $post_sweet_owner_userid, $post_sweet_sec_id, $sweet_url);
        }
        // Ouput the sweet/unsweet button
        return $sweet_button_display;
    }
  }

  /**
 * addSweet
 *
 * add sweet to database
 *
 * @param int $sweet_id (ID of post where sweet is)
 * @param string $sweet_location (Section of site where sweet is)
 * @param int $sweet_owner_userid (ID of user sweeting)
 * @param int $sweet_sec_id (ID of secondary post)
 * @param string $sweet_url (redirect url)
 *
 */
  public static function addSweet($sweet_id = null, $sweet_location = null, $sweet_owner_userid = null, $sweet_sec_id = null, $sweet_url = null){
      // Insert New Sweet Into Database
      self::$db = Database::get();
      $sweet_add_data = self::$db->insert(
        PREFIX.'sweets',
          array('sweet_id' => $sweet_id,
                'sweet_location' => $sweet_location,
                'sweet_owner_userid' => $sweet_owner_userid,
                'sweet_sec_id' => $sweet_sec_id));
      if($sweet_add_data > 0){
        // Success
        SuccessMessages::push('You Have Successfully Submitted a '.SWEET_BUTTON_DISPLAY, $sweet_url);
      }else{
        ErrorMessages::push('There Was an Error Submitting '.SWEET_BUTTON_DISPLAY, $sweet_url);
      }
  }

  /**
 * removeSweet
 *
 * delete sweet from database
 *
 * @param int $sweet_id (ID of post where sweet is)
 * @param string $sweet_location (Section of site where sweet is)
 * @param int $sweet_owner_userid (ID of user sweeting)
 * @param int $sweet_sec_id (ID of secondary post)
 * @param string $sweet_url (redirect url)
 *
 */
  public static function removeSweet($sweet_id = null, $sweet_location = null, $sweet_owner_userid = null, $sweet_sec_id = null, $sweet_url = null){
      // Insert New Sweet Into Database
      self::$db = Database::get();
      $sweet_remove_data = self::$db->delete(
        PREFIX.'sweets',
          array('sweet_id' => $sweet_id,
                'sweet_location' => $sweet_location,
                'sweet_owner_userid' => $sweet_owner_userid,
                'sweet_sec_id' => $sweet_sec_id));
      if($sweet_remove_data > 0){
        // Success
        SuccessMessages::push('You Have Successfully Deleted a '.SWEET_BUTTON_DISPLAY, $sweet_url);
      }else{
        ErrorMessages::push('There Was an Error Deleting '.SWEET_BUTTON_DISPLAY, $sweet_url);
      }
  }

  /**
 * getTotalSweets
 *
 * gets sweet count for all sweets releated to sweet_id
 *
 * @param int $sweet_id (ID of post where sweets are)
 * @param string $sweet_location (Section of site where sweets are)
 * @param string $sweet_sec_location (Related location where sweets are)
 *
 * @return string returns sweet data
 */
  public static function getTotalSweets($sweet_id = null, $sweet_location = null, $sweet_sec_location = null){
    self::$db = Database::get();
    $sweet_count = self::$db->select("
        SELECT
          *
        FROM
          ".PREFIX."sweets
        WHERE
          sweet_id = :sweet_id
            AND (sweet_location = :sweet_location OR sweet_location = :sweet_sec_location)
        ",
      array(':sweet_id' => $sweet_id,
            ':sweet_location' => $sweet_location,
            ':sweet_sec_location' => $sweet_sec_location));
    $sweet_total = count($sweet_count);
    $sweet_display = " <div class='btn btn-success btn-sm'>".SWEET_TITLE_DISPLAY." <span class='badge badge-light'>$sweet_total</span></div> ";
    return $sweet_display;
  }

  /**
 * getTotalSweetsBadge
 *
 * gets sweet count for all sweets releated to sweet_id
 *
 * @param int $sweet_id (ID of post where sweets are)
 * @param string $sweet_location (Section of site where sweets are)
 * @param string $sweet_sec_location (Related location where sweets are)
 *
 * @return string returns sweet data
 */
  public static function getTotalSweetsBadge($sweet_id = null, $sweet_location = null, $sweet_sec_location = null){
    self::$db = Database::get();
    $sweet_count = self::$db->select("
        SELECT
          *
        FROM
          ".PREFIX."sweets
        WHERE
          sweet_id = :sweet_id
            AND (sweet_location = :sweet_location OR sweet_location = :sweet_sec_location)
        ",
      array(':sweet_id' => $sweet_id,
            ':sweet_location' => $sweet_location,
            ':sweet_sec_location' => $sweet_sec_location));
    $sweet_total = count($sweet_count);
    $sweet_display = " <span class='badge badge-pill badge-success'>$sweet_total</span> ";
    return $sweet_display;
  }

}
