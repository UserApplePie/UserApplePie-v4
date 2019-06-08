<?php
/**
* Display Profile View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language;
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
                        <img alt="<?php echo $data['profile']->username; ?>'s Profile Picture" src="<?php echo SITE_URL.IMG_DIR_PROFILE.$data['main_image']; ?>" class="rounded img-fluid">
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
                                            echo "<tr><td>".Language::show('Friend', 'Friends')."</td><td> ".Language::show('your_friend', 'Friends')." </td></tr>";
                                        }else if($friends_status == "Pending"){
                                            echo "<tr><td>".Language::show('Friend', 'Friends')."</td><td> ".Language::show('pending_approval', 'Friends')." </td></tr>";
                                        }else{
                                            echo "<tr><td>".Language::show('Friend', 'Friends')."</td><td><a href='".SITE_URL."AddFriend/".$data['profile']->username."' class='btn btn-sm btn-secondary'>".Language::show('send_friend_request', 'Friends')."</a></td></tr>";
                                        }
                                    }
                                }
                              ?>
                              <tr><td><?=Language::show('members_profile_firstname', 'Members'); ?></td><td><?php echo $data['profile']->firstName; ?></td></tr>
                              <tr><td><?=Language::show('members_profile_lastname', 'Members'); ?></td><td><?php echo $data['profile']->lastName; ?></td></tr>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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

    </div>
