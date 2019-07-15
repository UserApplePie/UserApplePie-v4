<?php
/**
* UserApplePie v4 Comments View Plugin Home
*
* UserApplePie - Comments Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

/** Comments Home Page View **/

use Core\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form,
  Libs\Request,
  Libs\TimeDiff,
  Libs\CurrentUserData,
  Libs\Sweets,
  Libs\BBCode,
  Libs\Comments;

  $get_userName = CurrentUserData::getUserName($data['status_data'][0]->status_userID);
  $get_userImage = CurrentUserData::getUserImage($data['status_data'][0]->status_userID);
  $get_online_check = CurrentUserData::getUserStatusDot($data['status_data'][0]->status_userID);

?>

<div class='col-lg-12 col-md-12'>

	<div class='card mb-3'>
		<div class='card-header h4'>
      <?php
        echo "<a href='".DIR."Profile/{$get_userName}'>";
          echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$get_userImage." class='rounded' style='height: 25px'>";
        echo "</a>";
        echo " $get_online_check <a href='".DIR."Profile/{$get_userName}'>$get_userName</a> is feeling ".$data['status_data'][0]->status_feeling."..";
      ?>
		</div>
		<div class='card-body'>
			<div class='forum'><?php echo BBCode::getHtml($data['welcome_message']); ?></div>
    </div>
    <div class='card-footer'>

      <?php
        echo "<div class='row'><div class='col-12'>";
        echo TimeDiff::dateDiff("now", $data['status_data'][0]->timestamp, 1) . " ago ";
        echo "<div class='float-right'>";
        /** Hide button if they are currently editing this reply **/
        if($data['action'] != "status_edit" && $data['currentUserData'][0]->userID == $data['status_data'][0]->status_userID){
          echo Form::open(array('method' => 'post', 'action' => SITE_URL, 'style' => 'display:inline'));
          // Topic Reply Edit True
          echo "<input type='hidden' name='action' value='status_edit' />";
          // Topic Reply ID for editing
          echo "<input type='hidden' name='edit_status_id' value='".$data['status_data'][0]->id."' />";
          // CSRF Token
          echo "<input type='hidden' name='token_status' value='".$data['csrfToken']."' />";
          // Display Submit Button
          echo "<button class='btn btn-sm btn-info' name='submit' type='submit'>Edit Status</button>";
          echo Form::close();
        }
        /** Start Sweet **/
        echo Sweets::displaySweetsButton($data['com_location_id'], $data['com_location'], $data['currentUserData'][0]->userID, $data['status_data'][0]->status_userID, 'Comments/'.$data['com_location'].'/'.$data['com_location_id']."/");
        echo Sweets::getSweets($data['com_location_id'], $data['com_location'], $data['status_data'][0]->status_userID);
        /** Start Comments **/
        echo Comments::getTotalComments($data['com_location_id'], $data['com_location'], $data['status_data'][0]->status_userID);
        echo "</div></div></div>";
        echo "<div class='col-12 p-0'>";
          echo comments::displayComments($data['com_location_id'], $data['com_location'], $data['currentUserData'][0]->userID, 0, 'Comments/'.$data['com_location'].'/'.$data['com_location_id']."/", null, 'view_comments');
        echo "</div>";
      ?>

    </div>
  </div>
</div>
