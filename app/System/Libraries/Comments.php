<?php
/**
 * Comments Plugin
 * Displays current comments count for given Page
 * Automaticly adds comments count to db when enabled
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
 */

namespace Libs;

use Libs\Database,
    Libs\Form,
    Libs\Request,
    Libs\TimeDiff,
    Libs\CurrentUserData,
    Libs\Sweets;

class Comments
{

  /**
   * Ready the database for use in this helper.
   *
   * @var string
   */
  private static $db;

  /**
 * getComments
 *
 * gets comment count
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_sec_id (ID of secondary post)
 *
 * @return string returns comment data
 */
  public static function getComments($com_id = null, $com_location = null, $com_sec_id = null, $display_type = "btn"){
    // Get comment count from db
    // Check to see if this is a comment for a secondary post
//    var_dump($com_sec_id);
    if($com_sec_id == null){
      // Comment is for main post
      self::$db = Database::get();
      $com_count = self::$db->select("
          SELECT
            *
          FROM
            ".PREFIX."comments
          WHERE
            com_id = :com_id
              AND com_location = :com_location
              AND com_sec_id = :com_sec_id
          ",
        array(':com_id' => $com_id,
              ':com_location' => $com_location,
              ':com_sec_id' => '0'));
      $com_total = count($com_count);
    }else{
      // Comment is for secondary post
      self::$db = Database::get();
      $com_count = self::$db->select("
          SELECT
            *
          FROM
            ".PREFIX."comments
          WHERE
            com_id = :com_id
              AND com_location = :com_location
              AND com_sec_id = :com_sec_id
          ",
        array(':com_id' => $com_id,
              ':com_location' => $com_location,
              ':com_sec_id' => $com_sec_id));
      $com_total = count($com_count);
    }
    if($display_type == "btn"){
      $com_display = " <div class='btn btn-success btn-sm'>Comments <span class='badge badge-light'>$com_total</span></div> ";
    }else if($display_type = "num"){
      $com_display = $com_total;
    }
    return $com_display;
  }

  /**
 * displayCommentsButton
 *
 * display comments button
 * update/add comments type
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_owner_userid (ID of user commenting)
 * @param int $com_type (comment/uncomment)
 * @param int $com_sec_id (ID of secondary post)
 * @param string $com_url (redirect url)
 *
 * @return string returns comment button data
 */
  public static function displayComments($com_id = null, $com_location = null, $com_owner_userid = null, $com_sec_id = null, $com_url = null, $com_secondary = null){
    // Make sure that there is a user logged in
    if($com_owner_userid != null){
      // Check to see if current user has already commented page
      self::$db = Database::get();
      // Check to see if this is main post
      if($com_sec_id == null){
        // Comment is for main post
        $com_data = self::$db->select("
            SELECT
              *
            FROM
              ".PREFIX."comments
            WHERE
              com_id = :com_id
                AND com_location = :com_location
            ",
          array(':com_id' => $com_id,
                ':com_location' => $com_location
              ));
          // Get count to see if user has already submitted a comment
          $com_count = count($com_data);
      }else{
        // Comment is for secondary post
        $com_data = self::$db->select("
            SELECT
              *
            FROM
              ".PREFIX."comments
            WHERE
              com_id = :com_id
                AND com_location = :com_location
                AND com_sec_id = :com_sec_id
            ",
          array(':com_id' => $com_id,
                ':com_location' => $com_location,
                ':com_sec_id' => $com_sec_id));
          // Get count to see if user has already submitted a comment
          $com_count = count($com_data);
        }
        // Clean url by removing the anchor
        $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
        // Setup Current Comments display
        if($com_secondary != 'secondary'){
          $button_title = "Post Comment";
          $button_color = "success";
          $button_img_size = "col-2";
          $table_style = "";
        }else{
          $button_title = "Post Reply";
          $button_color = "info";
          $button_img_size = "col-1 align-top";
          $table_style = "";
        }
        $display_comments = "<table class='table table-sm table-borderless $table_style p-0 m-0 text-break'>";
          foreach ($com_data as $com) {
            // Get user data
            $com_userName = CurrentUserData::getUserName($com->com_owner_userid);
            $com_userImage = CurrentUserData::getUserImage($com->com_owner_userid);
            $com_timeago = TimeDiff::dateDiff("now", "$com->timestamp", 1) . " ago ";
            // Display comment
            $display_comments .= "<tr><td class='$button_img_size'>";
            $display_comments .= "<a class='anchor' name='viewcom$com->id'></a>";
            $display_comments .= "<a href='".SITE_URL."Profile/{$com_userName}'>";
            $display_comments .= "<img src=".SITE_URL.IMG_DIR_PROFILE.$com_userImage." class='img-fluid rounded'>";
            $display_comments .= "</a>";
            $display_comments .= "</td><td class='col-12 forum text-break'>";
            $display_comments .= CurrentUserData::getUserStatusDot($com->com_owner_userid);
            $display_comments .= "<b><a href='".SITE_URL."Profile/{$com_userName}'>$com_userName</a> <font class='text-muted' size='1'> $com_timeago</font></b><br>";
            $display_comments .= $com->com_content;
            $display_comments .= Sweets::displaySweetsLink($com->id, 'Comment'.$com_location, $com_owner_userid, $com_sec_id, $clean_com_url."/#viewcom$com->id");
            if($com_secondary != 'secondary'){
              $display_comments .= "</tr></td><tr><td></td><td>";
              $display_comments .= self::displayComments($com->id, 'Sec'.$com_location, $com_owner_userid, $com_id, $clean_com_url."/#viewcom$com->id", 'secondary');
            }
            $display_comments .= "</td></tr>";
          }
        $display_comments .= "</table>";
        // Setup Comment Button Form
        $com_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline-grid; width:100%'));
          // Display Comment Button if user has not yet commented
          $com_button_display .= "<div class='input-group'>";
          $com_button_display .= " <input type='hidden' name='submit_comment' value='true' /> ";
          $com_button_display .= " <input type='hidden' name='com_id' value='$com_id' /> ";
          $com_button_display .= " <input type='hidden' name='com_sec_id' value='$com_sec_id' /> ";
          $com_button_display .= " <input type='hidden' name='com_location' value='$com_location' /> ";
          $com_button_display .= " <input type='hidden' name='com_owner_userid' value='$com_owner_userid' /> ";
          $com_button_display .= Form::textBox(array('type' => 'text', 'id' => 'com_content', 'name' => 'com_content', 'class' => 'form-control', 'value' => '', 'placeholder' => 'Comment', 'rows' => '1'));
          $com_button_display .= "<div class='input-group-append'>";
          $com_button_display .= " <button type='submit' class='btn btn-$button_color btn-sm float-right' value='Comment' name='comment'> $button_title </button> ";
          $com_button_display .= "</div></div>";
        // Close the Comment Button Form
        $com_button_display .= Form::close();;

        // Check to see if user is submitting a new comment
        $submit_comment = Request::post('submit_comment');
        $delete_comment = Request::post('delete_comment');
        $post_com_id = Request::post('com_id');
        $post_com_location = Request::post('com_location');
        $post_com_owner_userid = Request::post('com_owner_userid');
        $post_com_sec_id = Request::post('com_sec_id');
        $post_com_content = Request::post('com_content');
        if($submit_comment == "true" && $post_com_id == $com_id && $post_com_id == $com_id && $post_com_location == $com_location){
          self::addComment($post_com_id, $post_com_location, $post_com_owner_userid, $post_com_sec_id, $com_url, $post_com_content);
        }else if($delete_comment == "true" && $post_com_id == $com_id && $post_com_id == $com_id && $post_com_location == $com_location){
          self::removeComment($post_com_id, $post_com_location, $post_com_owner_userid, $post_com_sec_id, $com_url);
        }
        // Check to see if any comments
        if(!empty($com_data)){
          $display_comments = $display_comments;
        }else{
          $display_comments = '<hr>';
        }
        // Ouput the comment/uncomment button
        return $display_comments.$com_button_display;
    }
  }

  /**
 * addComment
 *
 * add comment to database
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_owner_userid (ID of user commenting)
 * @param int $com_sec_id (ID of secondary post)
 * @param string $com_url (redirect url)
 * @param string $com_content (Comment content)
 *
 */
  public static function addComment($com_id = null, $com_location = null, $com_owner_userid = null, $com_sec_id = null, $com_url = null, $com_content = null){
      // Insert New Comment Into Database
      self::$db = Database::get();
      $com_add_data = self::$db->insert(
        PREFIX.'comments',
          array('com_id' => $com_id,
                'com_location' => $com_location,
                'com_owner_userid' => $com_owner_userid,
                'com_sec_id' => $com_sec_id,
                'com_content' => $com_content
              ));
      if($com_add_data > 0){
        // Clean url by removing the anchor
        $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
        // Success
        SuccessMessages::push('You Have Successfully Submitted a Comment', $clean_com_url."/#viewcom$com_add_data");
      }else{
        ErrorMessages::push('There Was an Error Submitting Comment', $com_url);
      }
  }

  /**
 * removeComment
 *
 * delete comment from database
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_owner_userid (ID of user commenting)
 * @param int $com_sec_id (ID of secondary post)
 * @param string $com_url (redirect url)
 *
 */
  public static function removeComment($com_id = null, $com_location = null, $com_owner_userid = null, $com_sec_id = null, $com_url = null){
      // Insert New Comment Into Database
      self::$db = Database::get();
      $com_remove_data = self::$db->delete(
        PREFIX.'comments',
          array('com_id' => $com_id,
                'com_location' => $com_location,
                'com_owner_userid' => $com_owner_userid,
                'com_sec_id' => $com_sec_id));
      if($com_remove_data > 0){
        // Success
        SuccessMessages::push('You Have Successfully Deleted a Comment', $com_url);
      }else{
        ErrorMessages::push('There Was an Error Deleting Comment', $com_url);
      }
  }

  /**
 * getTotalComments
 *
 * gets comment count for all comments releated to com_id
 *
 * @param int $com_id (ID of post where comments are)
 * @param string $com_location (Section of site where comments are)
 * @param string $com_sec_location (Related location where comments are)
 *
 * @return string returns comment data
 */
  public static function getTotalComments($com_id = null, $com_location = null, $com_sec_location = null){
    self::$db = Database::get();
    $com_location2 = "Sec".$com_location;
    $com_count = self::$db->select("
        SELECT
          *
        FROM
          ".PREFIX."comments
        WHERE
          (com_id = :com_id OR com_sec_id = :com_id)
        AND
          (com_location = :com_location OR com_location = :com_location2)
        ",
      array(':com_id' => $com_id,
            ':com_location' => $com_location,
            ':com_location2' => $com_location2
          ));
    $com_total = count($com_count);
    $com_display = " <div class='btn btn-success btn-sm'>Comments <span class='badge badge-light'>$com_total</span></div> ";
    return $com_display;
  }

}
