<?php
/**
* UserApplePie v4 Forum View Plugin Topic
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

/** Forum Topic View **/

use Core\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form,
  Libs\TimeDiff,
  Libs\CurrentUserData,
  Libs\BBCode,
  Libs\Sweets,
  Libs\Images,
  Libs\ForumStats;

?>

<div class='col-lg-8 col-md-8'>

	<div class='panel panel-default'>
		<div class='panel-heading'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
      <?php
          // Display Locked Message if Topic has been locked by admin
          if($data['topic_status'] == 2){
            echo " <strong><font color='red'>Topic Locked</font></strong> ";
          }
          // Display Disallowed message if topic has been blocked
          if($data['topic_allow'] == "FALSE"){
            $hidden_userName = CurrentUserData::getUserName($data['hidden_userID']);
            echo " <strong><font color='red'>Topic Disabled By Mod/Admin</font></strong> ";
            echo "<hr>";
            echo "<b>Topic Hidden By</b>: ".$hidden_userName." <b>@</b> ".$data['hidden_timestamp']."<br>";
            echo "<b>Reason</b>: ".$data['hidden_reason'];
          }
       ?>
		</div>
		<div class='panel-body'>
			<?php
      // Check to see if topic is allowed
      if($data['topic_allow'] == "TRUE"){
        // Display Views Count
        echo "<div class='btn btn-xs btn-info'>Views <span class='badge'>".$data['PageViews']."</span></div>";
        // Display Total Sweets Count for Topic and All Replys
        echo Sweets::getTotalSweets($data['topic_id'], 'Forum_Topic', 'Forum_Topic_Reply');
        // Display total images
        echo "<div class='btn btn-success btn-xs'> Images <span class='badge'>";
          echo Images::getImageCountForum('Topic', $data['topic_id']);
        echo "</span></div>";
        echo "<hr>";

        // Topic Display
    		echo "<div class='panel panel-default'>";
    			echo "<div class='panel-heading'>";
    				echo "<div class='row'>";
    					echo "<div class='col-lg-6 col-md-6 col-sm-6'>";
    						// Show user main pic
                // Get user name from userID
                $f_p_user_name = CurrentUserData::getUserName($data['topic_creator']);
                echo "<meta name='author' content='$f_p_user_name'>";
                echo "<meta name='date' content='".date("Y-m-d", strtotime($data['topic_date']))."' scheme='YYYY-MM-DD'>";
    						echo " <a href='".DIR."Profile/$f_p_user_name' rel='author'>$f_p_user_name</a> ";
    					echo "</div>";
    					echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:right'>";
    						// Display how long ago this was posted
                            $data_topic_date = $data['topic_date'];
    						echo "<font color=green> " . TimeDiff::dateDiff("now", "$data_topic_date", 1) . " ago</font> ";
                            // Display post number
                            echo " <span class='label label-default'>Topic</span> ";
    					echo "</div>";
    				echo "</div>";
    			echo "</div>";
          //Format the content with bbcode
  				$data_topic_content = BBCode::getHtml($data['topic_content']);
  			echo "<div class='panel-body forum' style='padding: 0px; overflow: hidden; height: auto;'>";
          echo "<div class='col-lg-3 col-md-3 col-sm-3 hidden-xs' style='padding-top: 8px; padding-bottom: 8px; float: left; padding-bottom: 1500px; margin-bottom: -1500px; text-align: left; border-right: 1px solid #cccccc;'>";
            // Display User's Stats
            // Check to see if user has a profile image
            $user_image_display = CurrentUserData::getUserImage($data['topic_creator']);
            $user_signup_display = CurrentUserData::getSignUp($data['topic_creator']);
            $user_total_posts = ForumStats::getTotalPosts($data['topic_creator']);
            if(!empty($user_image_display)){
              echo "<img src='".SITE_URL.IMG_DIR_PROFILE.$user_image_display."' class='img-responsive' style='margin-bottom: 2px'>";
            }else{
              echo "<span class='glyphicon glyphicon-user icon-size' style='margin-bottom: 2px'></span>";
            }
            echo " <strong><a href='".DIR."Profile/$f_p_user_name' class='btn btn-xs btn-default'>$f_p_user_name</a></strong> <Br>";
            // Show user's membership status
            foreach(CurrentUserData::getUserGroups($data['topic_creator']) as $row){ echo "<font size='2'>".$row."</font><br>"; };
            echo "<font size='1'>";
              echo "<strong>Total Posts</strong>: $user_total_posts<br>";
              echo "<strong>Joined</strong>: $user_signup_display";
            echo "</font>";
            echo " <a href='".DIR."Profile/$f_p_user_name' class='btn btn-xs btn-default'>Profile</a> ";
            // Check to see if current user is logged in... if not then hide the pm button
            if(!empty($data['current_userID'])){
              echo " <a href='".DIR."NewMessage/$f_p_user_name' class='btn btn-xs btn-default'>PM</a> ";
            }
          echo "</div>";
          echo "<div class='col-lg-9 col-md-9 col-sm-9' style='padding-top: 8px; padding-bottom: 8px; float: left; padding-bottom: 1500px; margin-bottom: -1500px;'>";
            // Display Post DateTime
            echo "<font size='1'><strong>Posted:</strong> ".date("F d, Y @ h:i A ",strtotime($data['topic_date']))." </font><hr style='margin-top: 0px'>";
            // Display Topic Content
            if($data['action'] == "edit_topic" && $data['current_userID'] == $data['topic_creator']){
              echo "<font color='green' size='0.5'><b>Editing Topic</b></font>";
              echo Form::open(array('method' => 'post'));
              echo Form::input(array('type' => 'text', 'name' => 'forum_title', 'class' => 'form-control', 'value' => $data['title'], 'placeholder' => 'Topic Title', 'maxlength' => '100'));
              echo Form::textBox(array('type' => 'text', 'name' => 'forum_content', 'class' => 'form-control', 'value' => $data['topic_content'], 'placeholder' => 'Topic Content', 'rows' => '6'));
              // Topic Reply Edit True
              echo "<input type='hidden' name='action' value='update_topic' />";
              // CSRF Token
              echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
              // Display Submit Button
              echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Update Topic</button>";
              echo Form::close();
            }else{
              // Display Topic Content
    				  echo $data_topic_content;
              // Get user's Signature
              $user_signature = CurrentUserData::getUserSignature($data['topic_creator']);
              if(!empty($user_signature)){
                echo "<hr style='margin-bottom: 0px'><font size='1'><i>Signature</i></font><hr style='margin-top: 0px'> $user_signature";
              }
            }
            // Check to see if there are any images attaced to this post
            if(!empty($data['forum_topic_images'])){
              echo "<hr style='margin-bottom: 0px'><font size='1'><i>Image Attachments</i></font>";
              echo "<hr style='margin-top: 0px'>";
              echo "<div align='center' style='margin-bottom: 8px'><a href='".SITE_URL."{$data['forum_topic_images']}' target='_blank'><img src='".DIR."{$data['forum_topic_images']}' height='100px'></a></div>";
            }
          echo "</div>";
  			echo "</div>";
  			echo "<div class='panel-footer'>";
  				echo "<div class='row'>";
  					echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:left'>";
  						if($data['topic_edit_date'] != NULL){
  							// Display how long ago this was posted
  							$timestart = $data['topic_edit_date'];  //Time of post
  							echo " <font color=red>Edited</font><font color=red> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
  						}
  					echo "</div>";
  					echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:right'>";
  						//Start Sweet
              $sweet_url = "Topic/".$data['topic_id'];
              echo Sweets::displaySweetsButton($data['topic_id'], 'Forum_Topic', $data['current_userID'], "0", $sweet_url);
              echo Sweets::getSweets($data['topic_id'], 'Forum_Topic');
              // If user owns this content show forum buttons for edit and delete
              // Hide button if they are currently editing this topic or any replys
              if($data['action'] != "edit_reply" && $data['action'] != "edit_topic" && $data['current_userID'] == $data['topic_userID']){
                echo Form::open(array('method' => 'post', 'style' => 'display:inline'));
                // Topic Reply Edit True
                echo "<input type='hidden' name='action' value='edit_topic' />";
                // CSRF Token
                echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
                // Display Submit Button
                echo "<button class='btn btn-xs btn-info' name='submit' type='submit'>Edit Topic</button>";
                echo Form::close();
              }
  					echo "</div>";
  				echo "</div>"; // End row
  			echo "</div>";
  		echo "</div>";  // END panel

      // Display Paginator Links
      // Check to see if there is more than one page
      if($data['pageLinks'] > "1"){
        echo "<div class='panel panel-info'>";
          echo "<div class='panel-heading text-center'>";
            echo $data['pageLinks'];
          echo "</div>";
        echo "</div>";
      }

        // Set starting post number
        if($data['current_page'] <= "1"){
            $post_num = "0";
        }else{
            $post_num = ($data['topic_reply_limit'] * $data['current_page']) - $data['topic_reply_limit'];
        }
        foreach($data['topic_replys'] as $row)
      	{
          $rf_p_main_id = $row->id;
          $rf_p_id = $row->fpr_post_id;
          $rf_p_id_cat = $row->fpr_id;
          $rf_p_content = $row->fpr_content;
          $rf_p_edit_date = $row->fpr_edit_date;
          $rf_p_timestamp = $row->fpr_timestamp;
          $rf_p_user_id = $row->fpr_user_id;
          $rf_p_allow = $row->allow;
          $rf_p_hide_userID = $row->hide_userID;
          $rf_p_hide_reason = $row->hide_reason;
          $rf_p_hide_timestamp = $row->hide_timestamp;
          $rf_p_user_name = CurrentUserData::getUserName($rf_p_user_id);
          $rf_p_hide_user_name = CurrentUserData::getUserName($rf_p_hide_userID);
          $post_num ++;

          echo "<a class='anchor' name='topicreply$rf_p_main_id'></a>";

					// Reply Topic Display
					echo "<div class='panel panel-info'>";
						echo "<div class='panel-heading'>";
							echo "<div class='row'>";
								echo "<div class='col-lg-6 col-md-6 col-sm-6'>";
									echo " Reply By: ";
									// Show user main pic
									echo " <a href='".DIR."Profile/$rf_p_user_id'>$rf_p_user_name</a> ";
								echo "</div>";
								echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:right'>";
									// Display how long ago this was posted
									$timestart = "$rf_p_timestamp";  //Time of post
									echo "<font color=green> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
                                    // Display reply post number
                                    echo " <span class='label label-default'>#$post_num</span> ";
								echo "</div>";
							echo "</div>";
						echo "</div>";
						echo "<div class='panel-body forum' style='overflow: hidden; height: auto; padding: 0px;'>";
							//Format the content with bbcode
							$rf_p_content_bb = BBCode::getHtml($rf_p_content);
              echo "<div class='col-lg-3 col-md-3 col-sm-3 hidden-xs' style='padding-top: 8px; padding-bottom: 8px; float: left; padding-bottom: 1500px; margin-bottom: -1500px; text-align: left; border-right: 1px solid #cccccc;'>";
                // Display User's Stats
                // Check to see if user has a profile image
                $user_image_display = CurrentUserData::getUserImage($rf_p_user_id);
                $user_signup_display = CurrentUserData::getSignUp($rf_p_user_id);
                $user_total_posts = ForumStats::getTotalPosts($rf_p_user_id);
                if(!empty($user_image_display)){
                  echo "<img src='".SITE_URL.IMG_DIR_PROFILE.$user_image_display."' class='img-responsive' style='margin-bottom: 2px'>";
                }else{
                  echo "<span class='glyphicon glyphicon-user icon-size' style='margin-bottom: 2px'></span>";
                }
                echo " <strong><a href='".DIR."Profile/".$rf_p_user_name."' class='btn btn-xs btn-default'>$rf_p_user_name</a></strong> <Br>";
                // Show user's membership status
                foreach(CurrentUserData::getUserGroups($rf_p_user_id) as $row){ echo "<font size='2'>".$row."</font><br>"; };
                echo "<font size='1'>";
                  echo "<strong>Total Posts</strong>: $user_total_posts<br>";
                  echo "<strong>Joined</strong>: $user_signup_display";
                echo "</font>";
                echo " <a href='".DIR."Profile/$rf_p_user_name' class='btn btn-xs btn-default'>Profile</a> ";
                // Check to see if current user is logged in... if not then hide the pm button
                if(!empty($data['current_userID'])){
                  echo " <a href='".DIR."NewMessage/$rf_p_user_name' class='btn btn-xs btn-default'>PM</a> ";
                }
              echo "</div>";
              echo "<div class='col-lg-9 col-md-9 col-sm-9' style='padding-top: 8px; padding-bottom: 8px; float: left; padding-bottom: 1500px; margin-bottom: -1500px;'>";
                // Display Post DateTime
                echo "<font size='1'><strong>Posted:</strong> ".date("F d, Y @ h:i A ",strtotime($rf_p_timestamp))." </font><hr style='margin-top: 0px'>";
                // Check to see if mod/admin has disallowed this reply
                if($rf_p_allow == "TRUE"){
                  // Check to see if user is trying to edit this reply
                  // Make sure user owns this reply before they can edit it
                  // Make sure this is the reply user is trying to edit
                  if($data['action'] == "edit_reply" && $data['current_userID'] == $rf_p_user_id && $data['edit_reply_id'] == $rf_p_main_id){
                    echo "<font color='green' size='0.5'><b>Editing Topic Reply</b></font>";
                    echo Form::open(array('method' => 'post', 'action' => '#topicreply'.$rf_p_main_id));
                    echo Form::textBox(array('type' => 'text', 'name' => 'fpr_content', 'class' => 'form-control', 'value' => $rf_p_content, 'placeholder' => 'Topic Reply Content', 'rows' => '6'));
                    // Topic Reply Edit True
                    echo "<input type='hidden' name='action' value='update_reply' />";
                    // Topic Reply ID for editing
                    echo "<input type='hidden' name='edit_reply_id' value='".$rf_p_main_id."' />";
                    // CSRF Token
                    echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
                    // Display Submit Button
                    echo "<button class='btn btn-xs btn-success' name='submit' type='submit'>Update Reply</button>";
                    echo Form::close();
                  }else{
                    // Display Topic Reply Content
    							  echo "$rf_p_content_bb";
                    // Get user's Signature
                    $user_signature = CurrentUserData::getUserSignature($rf_p_user_id);
                    if(!empty($user_signature)){
                      echo "<hr style='margin-bottom: 0px'><font size='1'><i>Signature</i></font><hr style='margin-top: 0px'> $user_signature";
                    }
                  }
                  // Check to see if there are any images attaced to this post
                  $check_for_image = Images::getForumImagesTopicReply($rf_p_id, $rf_p_main_id);
                  if(!empty($check_for_image)){
                    echo "<hr style='margin-bottom: 0px'><font size='1'><i>Image Attachments</i></font>";
                    echo "<hr style='margin-top: 0px'>";
                    echo "<div align='center' style='margin-bottom: 8px'><a href='".SITE_URL."{$check_for_image}' target='_blank'><img src='".SITE_URL."{$check_for_image}' height='100px'></a></div>";
                  }
                }else{
                  // Mod/Admin has disallowed this reply.  Show message
                  echo " <strong><font color='red'>This Reply Has Been Disabled By Mod/Admin.  Contact Mod/Admin for more information.</font></strong> ";
                  echo "<hr>";
                  echo "<b>Reply Hidden By</b>: $rf_p_user_name <b>@</b> $rf_p_hide_timestamp";
                }
              echo "</div>";
						echo "</div>";
						echo "<div class='panel-footer' style='text-align:right'>";
							echo "<div class='row'>";
								echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:left'>";
									if($rf_p_edit_date != NULL){
										// Display how long ago this was posted
										$timestart = "$rf_p_edit_date";  //Time of post
										echo " <font color=red>Edited</font> <font color=red> " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago</font> ";
									}
								echo "</div>";
								echo "<div class='col-lg-6 col-md-6 col-sm-6' style='text-align:right'>";
									//Start Sweet
                  $sweet_url = "Topic/".$data['topic_id']."/".$data['current_page']."/#topicreply".$rf_p_main_id;
                  echo Sweets::displaySweetsButton($data['topic_id'], 'Forum_Topic_Reply', $data['current_userID'], $rf_p_main_id, $sweet_url);
                  echo Sweets::getSweets($data['topic_id'], 'Forum_Topic_Reply', $rf_p_main_id);
                  // If user owns this content show forum buttons for edit and delete
                  // Hide button if they are currently editing this reply
                  if($data['action'] != "edit_reply" && $data['action'] != "edit_topic" && $data['current_userID'] == $rf_p_user_id && $rf_p_allow == "TRUE"){
                    echo Form::open(array('method' => 'post', 'action' => '#topicreply'.$rf_p_main_id, 'style' => 'display:inline'));
                    // Topic Reply Edit True
                    echo "<input type='hidden' name='action' value='edit_reply' />";
                    // Topic Reply ID for editing
                    echo "<input type='hidden' name='edit_reply_id' value='".$rf_p_main_id."' />";
                    // CSRF Token
                    echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
                    // Display Submit Button
                    echo "<button class='btn btn-xs btn-info' name='submit' type='submit'>Edit Reply</button>";
                    echo Form::close();
                  }
                  // Display Admin Hide/UnHide Button
                  // Check if Admin
                  if($data['is_admin'] == true || $data['is_mod'] == true){
                    echo Form::open(array('method' => 'post', 'style' => 'display: inline;'));
                      if($rf_p_allow == "FALSE"){
                        // UnHide Button
                        $reply_url = "Topic/".$data['topic_id']."/".$data['current_page']."/#topicreply".$rf_p_main_id;
                        echo "<input type='hidden' name='reply_url' value='$reply_url' />";
                        echo "<input type='hidden' name='action' value='unhide_reply' />";
                        echo "<input type='hidden' name='reply_id' value='$rf_p_main_id' />";
                        echo "<button name='submit' type='submit' class='btn btn-xs btn-warning'>UnHide Reply</button>";
                      }else{
                        // Hide Button
                        $reply_url = "Topic/".$data['topic_id']."/".$data['current_page']."/#topicreply".$rf_p_main_id;
                        echo "<input type='hidden' name='reply_url' value='$reply_url' />";
                        echo "<input type='hidden' name='action' value='hide_reply' />";
                        echo "<input type='hidden' name='reply_id' value='$rf_p_main_id' />";
                        echo "<button name='submit' type='submit' class='btn btn-xs btn-danger' style='margin-top: 2px'>Hide Reply</button>";
                      }
                    // CSRF Token
                    echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
                    echo Form::close();
                  }
								echo "</div>";
							echo "</div>"; // End row
						echo "</div>";
					echo "</div>";

      	}

        // Display Locked Message if Topic has been locked by admin
        if($data['topic_status'] == 2){
          echo " <strong><font color='red'>Topic Locked - Replies are Disabled</font></strong> ";
        }else{
          // Display Create New Topic Reply Button if user is logged in
          if($data['isLoggedIn'] && $group_forum_perms_post){
?>
            <hr>
            <?php echo Form::open(array('method' => 'post',  'files' => '')); ?>

            <!-- Topic Reply Content -->
            <div class='input-group' style='margin-bottom: 25px'>
              <span class='input-group-addon'><i class='glyphicon glyphicon-pencil'></i> </span>
              <?php (isset($data['fpr_content'])) ? $data['fpr_content'] = $data['fpr_content'] : $data['fpr_content'] = ""; ?>
              <?php echo Form::textBox(array('type' => 'text', 'name' => 'fpr_content', 'class' => 'form-control', 'value' => $data['fpr_content'], 'placeholder' => 'Topic Reply Content', 'rows' => '6')); ?>
            </div>

            <?php
              // Check to see if user is a new user.  If so then disable image uploads
              if($data['is_new_user'] != true){
             ?>
                <!-- Image Upload -->
                <div class='input-group' style='margin-bottom: 25px'>
                  <span class='input-group-addon'><i class='glyphicon glyphicon-picture'></i> </span>
                  <?php echo Form::input(array('type' => 'file', 'name' => 'forumImage', 'id' => 'forumImage', 'class' => 'form-control', 'accept' => 'image/jpeg,image/png,image/gif')); ?>
                </div>
            <?php } ?>

              <!-- CSRF Token -->
              <input type="hidden" name="token_forum" value="<?php echo $data['csrf_token']; ?>" />
              <input type='hidden' name='action' value='new_reply' />
              <button class="btn btn-md btn-success" name="submit" type="submit">
                <?php // echo Language::show('update_profile', 'Auth'); ?>
                Submit New Reply
              </button>
            <?php echo Form::close(); ?>
            <hr>
<?php
          }
        }

        // Display Paginator Links
        // Check to see if there is more than one page
        if($data['pageLinks'] > "1"){
          echo "<div class='panel panel-info'>";
            echo "<div class='panel-heading text-center'>";
              echo $data['pageLinks'];
            echo "</div>";
          echo "</div>";
        }

        // Check to see if user is logged in
        if($data['current_userID']){
          // Display Subscribe UnSubscribe Button for Email Notifications.
          if($data['is_user_subscribed'] == true && $data['checkUserPosted'] == true){
            echo " You are subscribed to receive E-Mail notifications on this topic. ";
            echo Form::open(array('method' => 'post'));
            echo "<input type='hidden' name='action' value='unsubscribe' />";
            echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
            echo "<button type='submit' name='submit' class='btn btn-warning btn-xs'>UnSubscribe</button>";
            echo Form::close();
          }else {
            if($data['is_user_subscribed'] != true && $data['checkUserPosted'] == true){
              echo " You are NOT subscribed to receive E-Mail notifications on this topic. ";
              // Display subscribe button if user has posted in this topic
              echo Form::open(array('method' => 'post'));
              echo "<input type='hidden' name='action' value='subscribe' />";
              echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
              echo "<button type='submit' name='submit' class='btn btn-success btn-xs'>Subscribe</button>";
              echo Form::close();
            }
          }
        }

        // Display Admin Lock/UnLock Button
        // Check if Admin
        if($group_forum_perms_mod == true || $group_forum_perms_admin == true){
          echo "<br>";
          echo Form::open(array('method' => 'post'));
            if($data['topic_status'] == 2){
              // UnLock Button
              echo "<input type='hidden' name='action' value='unlock_topic' />";
              echo "<button name='submit' type='submit' class='btn btn-xs btn-warning'>UnLock Topic</button>";
            }else{
              // Lock Button
              echo "<input type='hidden' name='action' value='lock_topic' />";
              echo "<button name='submit' type='submit' class='btn btn-xs btn-danger'>Lock Topic</button>";
            }
          // CSRF Token
          echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
          echo Form::close();
          echo " ";

        }
      // If topic is not allowed show message and hide everything related to that post
      }else{
        echo " <strong><font color='red'>This Topic Has Been Disabled By Mod/Admin.  Contact Mod/Admin for more information.</font></strong> ";
      }
      // Display Admin Hide/UnHide Button
      // Check if Admin
      if($group_forum_perms_mod == true || $group_forum_perms_admin == true){
        echo "<hr>";
        echo Form::open(array('method' => 'post'));
          if($data['topic_allow'] == "FALSE"){
            // UnHide Button
            echo "<input type='hidden' name='action' value='unhide_topic' />";
            echo "<button name='submit' type='submit' class='btn btn-xs btn-warning'>UnHide Topic</button>";
          }else{
            // Hide Button
            echo "<input type='hidden' name='action' value='hide_topic' />";
            echo Form::input(array('type' => 'text', 'name' => 'hide_reason', 'class' => 'form-control', 'placeholder' => 'Reason For Hiding This Topic', 'maxlength' => '255'));
            echo "<button name='submit' type='submit' class='btn btn-xs btn-danger' style='margin-top: 2px'>Hide Topic</button>";
          }
        // CSRF Token
        echo "<input type='hidden' name='token_forum' value='".$data['csrf_token']."' />";
        echo Form::close();
      }
				?>
		</div>
	</div>

    <?php
        /* Get Forum Permissions Data */
        $gfp_post = $group_forum_perms_post ? "can" : "cannot";
        $gfp_mod = $group_forum_perms_mod ? "can" : "cannot";
        $gfp_admin = $group_forum_perms_admin ? "<b>can</b> <a href='".SITE_URL."AdminPanel-Forum-Settings'>administrate</a>" : "<b>cannot</b> administrate";
    ?>

    <div class='panel panel-default'>
        <div class='panel-heading'>
            <b>Forum Permissions</b>
        </div>
        <div class='panel-body'>
            You <b><?php echo $gfp_post; ?></b> post in this forum.<Br>
            You <b><?php echo $gfp_mod; ?></b> moderate this forum.<br>
            You <?php echo $gfp_admin; ?> this forum.<br>
        </div>
    </div>

</div>
