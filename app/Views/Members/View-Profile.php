<?php
/**
* Display Profile View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language,
    Libs\Sweets,
    Libs\Form,
    Libs\BBCode,
    Libs\TimeDiff,
    Libs\CurrentUserData;

    $recent_userName = CurrentUserData::getUserName($data['profile']->userID);
    $recent_userImage = CurrentUserData::getUserImage($data['profile']->userID);

    /** Check for User's Profile Privacy Settings **/
    $user_privacy = $data['profile']->privacy_profile;
    /** Get current users relationship **/
    /* Check to see if Friends Plugin is installed, if it is show link */
    if(file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php') && $currentUserData[0]->username != $data['profile']->username){
      /** Check to see if users are friends or if a request is pending **/
      $friends_status = \Libs\CurrentUserData::getFriendStatus($currentUserData[0]->userID, $data['profile']->userID);
    }
    if($friends_status == "Friends"){
      $users_relationship = "Friends";
    }else if(isset($data['current_userID'])){
      $users_relationship = "Member";
    }else{
      $users_relationship = "Guest";
    }
    /** Check to see if current user can view profile **/
    if($data['profile']->userID == $data['current_userID']){
      $allow_profile = true;
    }else if($user_privacy == 'Friends'){
      /** Users Must be friends **/
      if($users_relationship == 'Friends'){
        $allow_profile = true;
      }else{
        $allow_profile = false;
      }
    }else if($user_privacy == 'Members'){
      /** Current user must be logged in **/
      if($users_relationship != 'Guest'){
        $allow_profile = true;
      }else{
        $allow_profile = false;
      }
    }else if($user_privacy == 'Public'){
      $allow_profile = true;
    }
?>

    <div class="col-md-4 col-lg-4 col-md-12">
        <div class="card border-primary mb-3">
            <div class="card-header h4">
                <?php echo $data['profile']->username; ?>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 col-lg-12" align="center">
                      <?php if(!empty($data['main_image'])){ ?>
                        <img alt="<?php echo $data['profile']->username; ?>'s <?=Language::show('mem_act_profile_photo', 'Members'); ?>" src="<?php echo SITE_URL.IMG_DIR_PROFILE.$data['main_image']; ?>" class="rounded img-fluid">
                        <?php }else{ ?>
            							<span class='fas fa-user icon-size'></span>
            						<?php } ?>
                        <hr>
                        <?php if($data['isAdmin'] == 'true'){
                            echo " <a href='".SITE_URL."AdminPanel-User/".$data['profile']->userID."' title='Admin - Edit User' class='btn btn-warning btn-block btn-sm'>Admin - Edit User</a> ";
                        } ?>
                        <?php if($currentUserData[0]->username == $data['profile']->username){
                            echo " <a href='".SITE_URL."Edit-Profile' title='".Language::show('mem_act_edit_profile', 'Members')."' class='btn btn-danger btn-block btn-sm'>".Language::show('mem_act_edit_profile', 'Members')."</a> ";
                        } ?>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="row">
                    <div class=" col-md-12 col-lg-12 ">
                        <table class="table table-striped">
                            <tbody>
                              <?php
                                /** Make sure User Is Logged In **/
                                if($isLoggedIn){
                                    /* Check to see if Private Message Plugin is installed, if it is show link */
                                    if(file_exists(ROOTDIR.'app/Plugins/Messages/Controllers/Messages.php')){
                                      echo "<tr><td>PM</td><td><a href='".SITE_URL."NewMessage/".$data['profile']->username."' class='btn btn-sm btn-secondary'>".Language::show('members_profile_sendmsg', 'Members')."</a></td></tr>";
                                    }
                                    /* Check to see if Friends Plugin is installed, if it is show link */
                                    if(file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php') && $currentUserData[0]->username != $data['profile']->username){
                                        /** Check to see if users are friends or if a request is pending **/
                                        $friends_status = \Libs\CurrentUserData::getFriendStatus($currentUserData[0]->userID, $data['profile']->userID);
                                        if($friends_status == "Friends"){
                                            echo "<tr><td>".Language::show('friends', 'Members')."</td><td> ".Language::show('your_friend', 'Friends')." </td></tr>";
                                        }else if($friends_status == "Pending"){
                                            echo "<tr><td>".Language::show('friends', 'Members')."</td><td> ".Language::show('pending_approval', 'Friends')." </td></tr>";
                                        }else{
                                            echo "<tr><td>".Language::show('friends', 'Members')."</td><td><a href='".SITE_URL."AddFriend/".$data['profile']->username."' class='btn btn-sm btn-secondary'>".Language::show('send_friend_request', 'Friends')."</a></td></tr>";
                                        }
                                    }
                                }
                              ?>
                              <tr><td><?=Language::show('members_profile_firstname', 'Members'); ?></td><td><?php echo $data['profile']->firstName; ?></td></tr>
                              <tr><td><?=Language::show('members_profile_lastname', 'Members'); ?></td><td><?php echo $data['profile']->lastName; ?></td></tr>
                              <tr><td><?=Language::show('members_profile_location', 'Members'); ?></td><td><?php echo $data['profile']->location; ?></td></tr>
                              <?php
                                if($data['user_groups']){
                                  echo "<tr><td>".Language::show('members_profile_group', 'Members')."</td><td>";
                                  foreach($data['user_groups'] as $row){
                                    echo " $row <br>";
                                  }
                                  echo "</td></tr>";
                                }
                              ?>
                            <tr><td><?=Language::show('members_profile_gender', 'Members'); ?></td><td><?php echo $data['profile']->gender; ?></td></tr>
							              <?php if(isset($data['profile']->website)){ ?>
                              <tr><td><?=Language::show('members_profile_website', 'Members'); ?></td><td><a href="<?php echo "http://".$data['profile']->website; ?>" target="_blank">View</a></td></tr>
							              <?php } ?>
                            <tr><td><?=Language::show('members_profile_lastlogin', 'Members'); ?></td><td><?php if($data['profile']->LastLogin){ echo date("F d, Y",strtotime($data['profile']->LastLogin)); }else{ echo "Never"; } ?></td></tr>
                            <tr><td><?=Language::show('members_profile_signup', 'Members'); ?></td><td><?php echo date("F d, Y",strtotime($data['profile']->SignUp)); ?></td></tr>
                            <tr><td><?=Language::show('members_profile_privacy', 'Members'); ?></td><td><?php echo $data['profile']->privacy_profile; ?></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
<?php
  /** Check to see if profile can be displayed **/
  if($allow_profile == false){
    echo "</div>";
    echo "<div class='col-md-8 col-lg-8'>
            <div class='card mb-3 bg-danger'>
                <div class='card-header h4'>
                    ".$data['profile']->username."
                </div>
                <div class='card-body'>
                    ".Language::show('members_profile_can_not_view', 'Members')."
                </div>
            </div>
          </div>
    ";
  }else{
 ?>
        <div class='card mb-3'>
            <div class='card-header h4'>
                <h3><?php echo $data['profile']->username; ?>'s <?=Language::show('friends', 'Members'); ?></h3>
            </div>
            <ul class="list-group list-group-flush">
              <?php
                if(!empty($friends)){
                  /** Get User's Friends **/
                  foreach ($friends as $friend) {
                    /** Get User's Friends Data **/
                    $friend_userName = CurrentUserData::getUserName($friend->userID);
                    $friend_userImage = CurrentUserData::getUserImage($friend->userID);
                    echo "<li class='list-group-item'>";
                      echo "<a href='".DIR."Profile/{$friend_userName}'>";
                        echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$friend_userImage." class='rounded' style='height: 25px'>";
                      echo "</a>&nbsp;";
                      echo "<a href='".DIR."Profile/{$friend_userName}'>";
                        echo "$friend_userName";
                      echo "</a>";
                    echo "</li>";
                  }
                }else{
                  echo "<li class='list-group-item'>".Language::show('user_does_not_have_friends', 'Members').". :(</li>";
                }
              ?>
            </ul>
        </div>

        <?php if($currentUserData[0]->username != $data['profile']->username){ ?>
        <div class='card mb-3'>
            <div class='card-header h4'>
                <h3><?=Language::show('mutual_friends', 'Members'); ?></h3>
            </div>
            <ul class="list-group list-group-flush">
              <?php
                if(!empty($mutual_friends)){
                  /** Get User's Friends **/
                  foreach ($mutual_friends as $friend) {
                    /** Get User's Friends Data **/
                    $friend_userName = CurrentUserData::getUserName($friend);
                    $friend_userImage = CurrentUserData::getUserImage($friend);
                    echo "<li class='list-group-item'>";
                      echo "<a href='".DIR."Profile/{$friend_userName}'>";
                        echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$friend_userImage." class='rounded' style='height: 25px'>";
                      echo "</a>&nbsp;";
                      echo "<a href='".DIR."Profile/{$friend_userName}'>";
                        echo "$friend_userName";
                      echo "</a>";
                    echo "</li>";
                  }
                }else{
                  echo "<li class='list-group-item'>".Language::show('user_does_not_have_mutual_friends', 'Members').". :(</li>";
                }
              ?>
            </ul>
        </div>
      <?php } ?>

    </div>

    <div class="col-md-8 col-lg-8">
        <div class="card mb-3">
            <div class="card-header h4">
                <?=Language::show('members_profile_allabout', 'Members'); ?> <?php echo $data['profile']->username; ?>
            </div>
            <div class="card-body">
                <?php echo $data['profile']->aboutme; ?>
            </div>
        </div>
        <?php if(!empty($data['profile']->signature)){ ?>
        <div class="card mb-3">
            <div class="card-header h4">
                <?php echo $data['profile']->username; ?>'s <?=Language::show('members_profile_signature', 'Members'); ?>
            </div>
            <div class="card-body">
                <?php echo $data['profile']->signature; ?>
            </div>
        </div>
      <?php } ?>

      <div class="card mb-3">
    		<div class="card-header h4">
    			<?php echo $data['profile']->username; ?>'s Images
    		</div>
    		<div class="card-body">
    				<div class='row'>
    					<?php
    						if(isset($data['user_images'])){
    							foreach ($data['user_images'] as $row) {
    								echo "<div class='col-lg-2 col-md-3 col-sm-4 col-xs-6' style='padding-bottom: 6px'>";
    									echo "<a href='#imageModal".$row->id."' data-toggle='modal' data-target='#imageModal".$row->id."'><img src='".SITE_URL.IMG_DIR_PROFILE."$row->userImage' class='img-thumbnail'></a>";
    								echo "</div>";

                    /** Image Model **/
                    echo "
                      <div id='imageModal".$row->id."' class='modal fade' tabindex='-1' role='dialog'>
                        <div class='modal-dialog modal-dialog-centered modal-lg'>
                          <div class='modal-content'>
                            <img src='".SITE_URL.IMG_DIR_PROFILE."$row->userImage' class='img-responsive'>
                          </div>
                        </div>
                      </div>
                    ";
    							}
    						}
    					?>
    				</div>
    		</div>
        <?php
          // Check to see if there is more than one page
          if($data['pageLinks'] > "1"){
            echo "<div class='card-footer text-muted' style='text-align: center'>";
            echo $data['pageLinks'];
            echo "</div>";
          }
        ?>
    	</div>

      <div class="card mb-3">
        <div class="card-header h4">
            <?php echo $data['profile']->username; ?>'s <?php echo Language::show('status_updates', 'Members'); ?>
        </div>
        <div class="card-body">
          <?php
              /** Check to see if user has status updates **/
              if(!empty($data['status_updates'])){
                foreach($data['status_updates'] as $recent){
                  /** Display the data for current recent **/
                  $status_content = BBCode::getHtml($recent->status_content);

                  if(isset($recent_limit)){}else{$recent_limit = "0";}
                  echo "<div class='card border-secondary mb-3'>";
                    echo "<div class='card-header'>";
                      echo "<a href='".DIR."Profile/{$recent_userName}'>";
                        echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                      echo "</a>";
                      echo " <a href='".DIR."Profile/{$recent_userName}'>$recent_userName</a> is feeling $recent->status_feeling..";
                    echo "</div>";
                    echo "<div class='card-body forum' style='overflow: hidden; height: auto;'>";
                      echo $status_content;
                    echo "</div>";
                    echo "<div class='card-footer'>";
                      echo TimeDiff::dateDiff("now", "$recent->timestamp", 1) . " ago ";
                      echo "<div class='float-right'>";
                        // Hide button if they are currently editing this reply
                        if($data['action'] != "status_edit" && $data['current_userID'] == $recent->status_userID){
                          echo Form::open(array('method' => 'post', 'action' => '../', 'style' => 'display:inline'));
                          // Topic Reply Edit True
                          echo "<input type='hidden' name='action' value='status_edit' />";
                          // Topic Reply ID for editing
                          echo "<input type='hidden' name='edit_status_id' value='".$recent->id."' />";
                          // CSRF Token
                          echo "<input type='hidden' name='token_status' value='".$data['csrfToken']."' />";
                          // Display Submit Button
                          echo "<button class='btn btn-sm btn-info' name='submit' type='submit'>Edit Status</button>";
                          echo Form::close();
                        }
                        /** Start Sweet **/
                        echo Sweets::getSweets($recent->id, 'Status', $recent->status_userID);
                      echo "</div>";
                    echo "</div>";
                  echo "</div>";
                }
                echo "<hr>";
              }else{
                echo Language::show('no_status_update', 'Members').".";
              }
          ?>
        </div>
      </div>

    </div>
<?php } ?>
