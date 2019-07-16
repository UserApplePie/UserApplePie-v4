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
    Libs\CurrentUserData,
    Libs\Comments;

    $recent_userName = CurrentUserData::getUserName($data['profile']->userID);
    $recent_userImage = CurrentUserData::getUserImage($data['profile']->userID);
    $user_status = CurrentUserData::getUserStatus($data['profile']->userID);

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
                <?php echo "<font class='float-right' size='2'>".$user_status."</font>"; ?>
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
 <?php
   /** Check if Status is enabled **/
   if($data['status_disable'] != true){
  ?>
        <div class='card mb-3'>
            <div class='card-header h4'>
                <?php echo $data['profile']->username; ?>'s <?=Language::show('friends', 'Members'); ?>
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
<?php } ?>
<?php
  /** Check if Status is enabled **/
  if($data['forum_disable'] != true){
 ?>
        <div class='card mb-3'>
          <div class='card-header h4'>
            <?php echo $data['profile']->username; ?>'s <?=Language::show('user_profile_forum_recent_posts', 'Members');?>
          </div>
          <ul class='list-group list-group-flush'>
            <?php
            if(!empty($data['forum_recent_posts'])){
              foreach($data['forum_recent_posts'] as $row_rp)
              {
                $f_p_id = $row_rp->forum_post_id;
                $f_p_id_cat = $row_rp->forum_id;
                $f_p_title = $row_rp->forum_title;
                $f_p_url = $row_rp->forum_url;
                $f_p_timestamp = $row_rp->forum_timestamp;
                $f_p_user_id = $row_rp->forum_user_id;
                $f_p_user_name = CurrentUserData::getUserName($f_p_user_id);
                $f_p_title = stripslashes($f_p_title);

                /** Check to see if topic has url set **/
                if(isset($f_p_url)){
                  $url_link = $f_p_url;
                }else{
                  $url_link = $f_p_id;
                }

                //Reply information
                $rp_user_id2 = $row_rp->fpr_user_id;
                $rp_timestamp2 = $row_rp->fpr_timestamp;

                // Set the incrament of each post
                if(isset($vm_id_a_rp)){ $vm_id_a_rp++; }else{ $vm_id_a_rp = "1"; };

                $f_p_title = strlen($f_p_title) > 30 ? substr($f_p_title, 0, 34) . ".." : $f_p_title;

                //If no reply show created by
                if(!isset($rp_timestamp2)){
                  echo "<ul class='list-group-item'>";
                  echo "<a href='".DIR."Profile/$f_p_user_id'>$f_p_user_name</a> created.. <br>";
                  echo "<strong>";
                  echo "<a href='".DIR."Topic/$url_link/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
                  echo "</strong>";
                  echo "<br>";
                  //Display how long ago this was posted
                  $timestart = $f_p_timestamp;  //Time of post
                  echo " <font color=green> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
                  //echo "($f_p_timestamp)"; // Test timestamp
                  unset($timestart, $f_p_timestamp);
                  echo "</ul>";
                }else{
                  $rp_user_name2 = CurrentUserData::getUserName($rp_user_id2);
                  //If reply show the following
                  echo "<ul class='list-group-item'>";
                  echo "<a href='".DIR."Profile/$rp_user_id2'>$rp_user_name2</a> posted on.. <br>";
                  echo "<strong>";
                  echo "<a href='".DIR."Topic/$url_link/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
                  echo "</strong>";
                  //Display how long ago this was posted
                  $timestart = $rp_timestamp2;  //Time of post
                  echo "<br><font color=green> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
                  unset($timestart, $rp_timestamp2);
                  echo "</ul>";
                }// End reply check
              } // End query
            }else{
              echo "<ul class='list-group-item'>".Language::show('members_profile_user_no_posts', 'Members')."</ul>";
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
        <?php if(!empty($data['profile']->signature) && $data['forum_disable'] != true){ ?>
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
    									echo "<a href='#myImg".$row->id."' data-toggle='modal' data-target='#myImg".$row->id."'><img id='myImg' src='".SITE_URL.IMG_DIR_PROFILE."$row->userImage' class='img-thumbnail'></a>";
    								echo "</div>";

                    /** Image Model **/
                    echo "
                      <div id='myImg".$row->id."' class='modal-img' tabindex='-1' role='dialog'>
                          <span class='close-img'>&times;</span>
                        <img id='img".$row->id."' src='".SITE_URL.IMG_DIR_PROFILE."$row->userImage' class='modal-content-img'>
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

      <?php
        /** Check if Status is enabled **/
        if($data['status_disable'] != true){
       ?>
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
                  if(isset($vm_id_a)){ $vm_id_a++; }else{ $vm_id_a = '1'; };
                  echo "<a class='anchor' name='viewmore$vm_id_a'></a>";
                  $sweets_url = "Profile/".$data['profile']->username."/".$current_page_num."/".$status_limit."/#viewmore$vm_id_a";
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
                      echo "<div class='row'><div class='col-12'>";
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
                        echo Comments::getComments($recent->id, 'Status', $recent->status_userID);
                        echo "</div></div></div>";
                        echo "<div class='col-12 p-0'>";
                          echo Comments::displayComments($recent->id, 'Status', $currentUserData[0]->userID, $recent->status_userID, $sweets_url);
                        echo "</div>";
                    echo "</div>";
                  echo "</div>";
                }
                echo "<hr>";
              }else{
                echo Language::show('no_status_update', 'Members').".";
              }
              /** Check to see if there are most recents than currently shown **/
              echo "<div class='card'><div class='card-body text-center'>";
                if(isset($status_limit)){}else{$status_limit = "0";}
                if($status_limit >= $status_total){
                  echo "Currently Showing $status_total of $status_total Status Updates";
                }else{
                  echo "Currently Showing $status_limit of $status_total Status Updates";
                }
                if(!isset($status_total)){$status_total = "0";}else{
                  if(isset($limitfriendstatus)){}else{ $limitfriendstatus = "10"; }
                  if($status_limit < $status_total){
                    $vm_id = $status_limit + 1;
                    echo "<hr>";
                    echo "<span class='btn btn-default'>";
                      echo "<a href=\"".$status_url."" . ($status_limit + 10) . "#viewmore$vm_id\">Show More Status Updates</a> ";
                    echo "</span>";
                  }
                }
              echo "</div></div>";
          ?>
        </div>
      </div>
    <?php } ?>

    </div>
<?php } ?>
