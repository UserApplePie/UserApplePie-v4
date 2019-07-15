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
 * @param string $display_type
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
  public static function displayComments($com_id = null, $com_location = null, $com_owner_userid = null, $com_sec_id = null, $com_url = null, $com_secondary = null, $page = null){
    // Make sure that there is a user logged in
    if($com_owner_userid != null){
      // Check to see if current user has already commented page
      self::$db = Database::get();
      // Check to see if comments should have limit
      if($page !== 'view_comments'){
        $set_comments_limit = 'LIMIT 3';
      }
      // Check to see if this is main post
      if($com_sec_id == null){
        // Comment is for main post
        $com_data = self::$db->select("
            SELECT * FROM (
              SELECT
                *
              FROM
                ".PREFIX."comments
              WHERE
                com_id = :com_id
                  AND com_location = :com_location
              ORDER BY id DESC
              $set_comments_limit
            ) c ORDER BY id ASC
            ",
          array(':com_id' => $com_id,
                ':com_location' => $com_location
              ));
          // Get count to see if user has already submitted a comment
          $com_count = count($com_data);
          // Check to see if not view_comments and greater than 3
          $get_total_comments = self::getTotalCommentsCount($com_id, $com_location);
          if($page !== 'view_comments' && $get_total_comments > 3){
            $view_comments_link = "<a href='".SITE_URL."Comments/".$com_location."/".$com_id."/'>View All Comments</a>";
          }
      }else{
        // Comment is for secondary post
        $com_data = self::$db->select("
            SELECT * FROM (
              SELECT
                *
              FROM
                ".PREFIX."comments
              WHERE
                com_id = :com_id
                  AND com_location = :com_location
                  AND com_sec_id = :com_sec_id
              ORDER BY id DESC
              $set_comments_limit
            ) cc ORDER BY id ASC
            ",
          array(':com_id' => $com_id,
                ':com_location' => $com_location,
                ':com_sec_id' => $com_sec_id));
          // Get count to see if user has already submitted a comment
          $com_count = count($com_data);
          // Check to see if not view_comments and greater than 3
          $get_total_comments = self::getTotalCommentsCount($com_id, $com_location, $com_sec_id);
          if($page !== 'view_comments' && $get_total_comments > 3){
            $clean_com_location = str_replace("Sec", "", $com_location);
            $view_comments_link = "<a href='".SITE_URL."Comments/".$clean_com_location."/".$com_id."/'>View All Comments</a>";
          }
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

          foreach ($com_data as $com) {
            // Setup Comment Edit Form
            $edit_comment = Request::post('edit_comment');
            $post_edit_id = Request::post('edit_id');
            $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
            $com_edit_display = Form::open(array('method' => 'post', 'style' => 'display:inline-grid; width:100%'));
              // Display Comment Edit Button
              $com_edit_display .= "<div class='input-group comment-box'>";
              $com_edit_display .= " <input type='hidden' name='update_comment' value='true' /> ";
              $com_edit_display .= " <input type='hidden' name='edit_id' value='$com->id' /> ";
              $com_edit_display .= " <input type='hidden' name='com_id' value='$com_id' /> ";
              $com_edit_display .= " <input type='hidden' name='com_sec_id' value='$com_sec_id' /> ";
              $com_edit_display .= " <input type='hidden' name='com_location' value='$com_location' /> ";
              $com_edit_display .= " <input type='hidden' name='com_owner_userid' value='$com_owner_userid' /> ";
              $com_edit_display .= Form::textBox(array('type' => 'text', 'id' => 'com_content', 'name' => 'com_content', 'class' => 'form-control', 'value' => $com->com_content, 'placeholder' => 'Comment', 'rows' => '1'));
              $com_edit_display .= "<div class='input-group-append'>";
              $com_edit_display .= " <button type='submit' class='btn btn-$button_color btn-sm float-right' value='Comment' name='comment'> Update Comment </button> ";
              $com_edit_display .= "</div></div>";
            // Close the Comment Edit Button Form
            $com_edit_display .= Form::close();
            // Setup Comment Delete Form
            $com_delete_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline'));
              // Display Comment Delete Button
              $com_delete_button_display .= "<input type='hidden' name='delete_comment' value='true' />";
              $com_delete_button_display .= "<input type='hidden' name='com_id' value='$com->id' />";
              $com_delete_button_display .= "<input type='hidden' name='com_location' value='$com_location' />";
              $com_delete_button_display .= "<input type='hidden' name='com_owner_userid' value='$com_owner_userid' />";
              $com_delete_button_display .= "<button type='submit' class='btn btn-danger' value='submit' name='submit'>Delete</button>";
            // Close the Comment Delete Button Form
            $com_delete_button_display .= Form::close();
            $com_delete_model_dispaly = "
               <a href='#DeleteModal".$com->id."' class='btn btn-sm btn-link trigger-btn' data-toggle='modal'>Delete</a>
              <div class='modal fade' id='DeleteModal".$com->id."' tabindex='-1' role='dialog' aria-labelledby='DeleteLabel' aria-hidden='true'>
                <div class='modal-dialog' role='document'>
                  <div class='modal-content'>
                    <div class='modal-header'>
                      <h5 class='modal-title' id='DeleteLabel'>Delete Comment</h5>
                      <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                    </div>
                    <div class='modal-body'>
                      Do you want to delete comment?
                    </div>
                    <div class='modal-footer'>
                      <button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>
                      $com_delete_button_display
                    </div>
                  </div>
                </div>
              </div>
            ";
            // Setup Comment Delete Form
            $com_edit_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline', 'action' => '#viewcom'.$com->id));
              // Display Comment Delete Button
              $com_edit_button_display .= "<input type='hidden' name='edit_comment' value='true' />";
              $com_edit_button_display .= "<input type='hidden' name='edit_id' value='$com->id' />";
              $com_edit_button_display .= "<input type='hidden' name='com_location' value='$com_location' />";
              $com_edit_button_display .= "<input type='hidden' name='com_owner_userid' value='$com_owner_userid' />";
              $com_edit_button_display .= "<button type='submit' class='btn btn-sm btn-link' value='submit' name='submit'>Edit</button>";
            // Close the Comment Delete Button Form
            $com_edit_button_display .= Form::close();
            // Get user data
            $com_userName = CurrentUserData::getUserName($com->com_owner_userid);
            $com_userImage = CurrentUserData::getUserImage($com->com_owner_userid);
            $com_timeago = TimeDiff::dateDiff("now", "$com->timestamp", 1) . " ago ";
            // Display comment
            $display_comments .= "<div class='media comment-box'>";
            $display_comments .= "<div class='media-left'>";
            $display_comments .= "<a class='anchor' name='viewcom$com->id'></a>";
            $display_comments .= "<a href='".SITE_URL."Profile/{$com_userName}'>";
            $display_comments .= "<img src=".SITE_URL.IMG_DIR_PROFILE.$com_userImage." class='img-fluid user-photo rounded'>";
            $display_comments .= "</a>";
            $display_comments .= "</div>";
            $display_comments .= "<div class='media-body text-break'>";
            $display_comments .= "<div class='media-heading'>";
            $display_comments .= CurrentUserData::getUserStatusDot($com->com_owner_userid);
            $display_comments .= "<b><a href='".SITE_URL."Profile/{$com_userName}'>$com_userName</a> <font class='text-muted' size='1'> $com_timeago</font></b>";
            $display_comments .= "</div>";
            $display_comments .= "<div class='media-content border p-1 bg-light'>";
            if($edit_comment == 'true' && $post_edit_id == $com->id && $com->com_owner_userid == $com_owner_userid){
              $display_comments .= $com_edit_display;
            }else{
              $display_comments .= "<div class='forum'>".$com->com_content."</div>";
            }
            $display_comments .= Sweets::displaySweetsLink($com->id, 'Comment'.$com_location, $com_owner_userid, $com_sec_id, $clean_com_url."/#viewcom$com->id");
            if($com->com_owner_userid == $com_owner_userid){ $display_comments .= $com_edit_button_display; }
            if($com->com_owner_userid == $com_owner_userid){ $display_comments .= $com_delete_model_dispaly; }
            $display_comments .= "</div>";
            if($com_secondary != 'secondary'){
              $display_comments .= self::displayComments($com->com_id, 'Sec'.$com_location, $com_owner_userid, $com->id, $clean_com_url."/#viewcom$com->id", 'secondary', $page);
            }
            $display_comments .= "</div></div>";
          }
          // Setup Comment Button Form
          $com_button_display = Form::open(array('method' => 'post', 'style' => 'display:inline-grid; width:100%'));
            // Display Comment Button
            $com_button_display .= "<div class='input-group comment-box'>";
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
          $com_button_display .= Form::close();

          $display_comments .= $view_comments_link;

        // Check to see if user is submitting a new comment
        $submit_comment = Request::post('submit_comment');
        $update_comment = Request::post('update_comment');
        $delete_comment = Request::post('delete_comment');
        $post_com_id = Request::post('com_id');
        $post_com_location = Request::post('com_location');
        $post_com_owner_userid = Request::post('com_owner_userid');
        $post_com_sec_id = Request::post('com_sec_id');
        $post_com_content = Request::post('com_content');
        if($submit_comment == "true" && $post_com_id == $com_id && $post_com_location == $com_location){
          self::addComment($post_com_id, $post_com_location, $post_com_owner_userid, $post_com_sec_id, $com_url, $post_com_content);
        }else if($update_comment == "true" && $post_com_id == $com_id && $post_com_location == $com_location){
          var_dump($_POST);
          self::updateComment($post_edit_id, $post_com_location, $post_com_owner_userid, $com_url, $post_com_content);
        }else if($delete_comment == "true" && $post_com_owner_userid == $com_owner_userid && $post_com_location == $com_location){
          self::removeComment($post_com_id, $post_com_location, $post_com_owner_userid, $com_url);
        }
        // Check to see if any comments
        if(!empty($com_data)){
          $display_comments = $display_comments;
        }else{
          $display_comments = ' ';
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
  public static function removeComment($com_id = null, $com_location = null, $com_owner_userid = null, $com_url = null){
      // Insert New Comment Into Database
      self::$db = Database::get();
      $com_remove_data = self::$db->delete(
        PREFIX.'comments',
          array('id' => $com_id,
                'com_location' => $com_location,
                'com_owner_userid' => $com_owner_userid
              ));
      if($com_remove_data > 0){
        // Success
        // Clean url by removing the anchor
        $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
        SuccessMessages::push('You Have Successfully Deleted a Comment', $clean_com_url);
      }else{
        ErrorMessages::push('There Was an Error Deleting Comment', $com_url);
      }
  }

  /**
 * updateComment
 *
 * update comment in database
 *
 * @param int $com_id (ID of post where comment is)
 * @param string $com_location (Section of site where comment is)
 * @param int $com_owner_userid (ID of user commenting)
 * @param int $com_sec_id (ID of secondary post)
 * @param string $com_content
 * @param redirect $com_url (redirect url)
 *
 */
  public static function updateComment($com_id = null, $com_location = null, $com_owner_userid = null, $com_url = null, $com_content = null){
      // Insert New Comment Into Database
      self::$db = Database::get();
      $com_remove_data = self::$db->update(
        PREFIX.'comments',
          array('com_content' => $com_content),
          array('id' => $com_id,
                'com_location' => $com_location,
                'com_owner_userid' => $com_owner_userid
              ));
      if($com_remove_data > 0){
        // Success
        // Clean url by removing the anchor
        $clean_com_url = substr($com_url, 0, strrpos( $com_url, '/'));
        SuccessMessages::push('You Have Successfully Updated a Comment', $clean_com_url);
      }else{
        ErrorMessages::push('There Was an Error Updating Comment', $com_url);
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
          (com_id = :com_id)
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
  public static function getTotalCommentsCount($com_id = null, $com_location = null, $com_sec_id = null){
    self::$db = Database::get();
    if(isset($com_sec_id)){
      $com_count = self::$db->select("
          SELECT
            *
          FROM
            ".PREFIX."comments
          WHERE
            (com_id = :com_id)
          AND
            (com_location = :com_location)
          AND
            (com_sec_id = :com_sec_id)
          ",
        array(':com_id' => $com_id,
              ':com_location' => $com_location,
              ':com_sec_id' => $com_sec_id
            ));
      $com_total = count($com_count);
    }else{
      $com_count = self::$db->select("
          SELECT
            *
          FROM
            ".PREFIX."comments
          WHERE
            (com_id = :com_id OR com_sec_id = :com_id)
          AND
            (com_location = :com_location)
          ",
        array(':com_id' => $com_id,
              ':com_location' => $com_location
            ));
      $com_total = count($com_count);
    }
    return $com_total;
  }

}
