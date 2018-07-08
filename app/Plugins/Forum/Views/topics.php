<?php
/**
* UserApplePie v4 Forum View Plugin Topics
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.1 for UAP v.4.2.1
*/

/** Forum Topics List View **/

use App\System\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form,
  Libs\TimeDiff,
  Libs\CurrentUserData,
  Libs\PageViews,
  Libs\Sweets,
  Libs\Images,
  Libs\ForumStats;

?>
<div class='col-lg-8 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
				<?php
        // Setup form list table stuff
				echo "
					<div class='card-body d-none d-md-block'>
						<div class='row'>

							<div class='col-md-7 col-sm-6'>
								<strong>Title</strong>
							</div>

							<div class='col-md-2 col-sm-3'>
								<strong>Statistics</strong>
							</div>

							<div class='col-md-3 col-sm-3' style='text-align: right'>
								<strong>Last Reply</strong>
							</div>
						</div>
					</div>
									<table class='table table-hover'>
				";

        foreach($data['forum_topics'] as $row2)
        {
          $f_p_id = $row2->forum_post_id;
          $f_p_id_cat = $row2->forum_id;
          $f_p_title = $row2->forum_title;
          $f_p_timestamp = $row2->forum_timestamp;
          $f_p_user_id = $row2->forum_user_id;
          $f_p_status = $row2->forum_status;
          $tstamp = $row2->tstamp;
          $f_p_user_name = CurrentUserData::getUserName($f_p_user_id);
          $has_user_read = ForumStats::checkUserRead($data['current_userID'], $f_p_id, $tstamp);

          $f_p_title = stripslashes($f_p_title);
                  echo "<tr><td>";
                  echo "<div class='d-none d-md-block'>";
                    echo "<div class='row'>";
                      echo "<div class='col-lg-7 col-md-6 col-sm-6'>";
                          // Add text to blank Topic Titles
                          if(empty($f_p_title)){ $f_p_title = "Oops! Title is Missing for this Topic."; }
                          echo "<strong>";
                            // Display icon that lets user know if they have read this topic or not
                            if($has_user_read){
                                echo "<span class='fas fa-star' aria-hidden='true'></span> ";
                            }else{
                                echo "<span class='far fa-star' aria-hidden='true' style='color: #DDD'></span> ";
                            }
                          echo "<a href='".DIR."Topic/$f_p_id/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
                          echo "</strong>";
                          echo "<div class='text small'>";
                            echo " Created by <a href='".DIR."Profile/$f_p_user_id' style='font-weight: bold'>$f_p_user_name</a> - ";
                            //Display how long ago this was posted
                            $timestart = "$f_p_timestamp";  //Time of post
                            echo " " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago ";
                            // Display Locked Message if Topic has been locked by admin
                            if($f_p_status == 2){
                              echo " <strong><font color='red'>Topic Locked</font></strong> ";
                            }
                          echo "</div>";
                      echo "</div>";

                      echo "<div class='col-lg-2 col-md-3 col-sm-3'>";
                          // Display total replys
                          // Display total topic replys
                          echo "<div class='btn btn-info btn-sm' style='margin-bottom: 3px'>";
                      		  echo "Replies <span class='badge badge-light'>$row2->total_topic_replys</span>";
                      		echo "</div>";
                          echo " ";
                          // Display total sweets
                          echo Sweets::getTotalSweets($f_p_id, 'Forum_Topic', 'Forum_Topic_Reply');
                          echo " ";
                          // Display total views
                          echo "<div class='btn btn-info btn-sm' style='margin-top: 3px'> Views <span class='badge badge-light'>";
                            echo PageViews::views('false', $f_p_id, 'Forum_Topic', $data['current_userID']);
                          echo "</span></div> ";
                          // Display total images
                          echo "<div class='btn btn-success btn-sm' style='margin-top: 3px'> Images <span class='badge badge-light'>";
                            echo Images::getImageCountForum('Topic', $f_p_id);
                          echo "</span></div>";
                      echo "</div>";

                      echo "<div class='col-lg-3 col-md-3 col-sm-3' style='text-align: right'>";
                          // Check to see if there has been a reply for this topic.  If not then don't show anything.
                          if(isset($row2->LR_UserID)){
                            // Display Last Reply User Name
                            $rp_user_name2 = CurrentUserData::getUserName($row2->LR_UserID);
                            //Display how long ago this was posted
                            echo " Last Reply by <br> <a href='".DIR."Profile/$row2->LR_UserID' style='font-weight: bold'>$rp_user_name2</a><br> " . TimeDiff::dateDiff("now", "$row2->LR_TimeStamp", 1) . " ago ";
                          }
                      echo "</div>";
                    echo "</div>";
                  echo "</div>";

                // For small devices hides extra info
                echo "<div class='d-block d-md-none'>";
                  echo "<div class='row'>";
                    echo "<div class='col-xs-10'>";
                        // Add text to blank Topic Titles
                        if(empty($f_p_title)){ $f_p_title = "Oops! Title is Missing for this Topic."; }
                        echo "<strong>";
                          // Display icon that lets user know if they have read this topic or not
                          if($has_user_read){
                              echo "<span class='fas fa-star' aria-hidden='true'></span> ";
                          }else{
                              echo "<span class='far fa-star' aria-hidden='true' style='color: #DDD'></span> ";
                          }
                        echo "<a href='".DIR."Topic/$f_p_id/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
                        echo "</strong>";
                        echo "<div class='text small'>";
                          echo " Created by <a href='".DIR."Profile/$f_p_user_id' style='font-weight: bold'>$f_p_user_name</a> - ";
                          //Display how long ago this was posted
                          $timestart = "$f_p_timestamp";  //Time of post
                          echo " " . TimeDiff::dateDiff("now", "$timestart", 1) . " ago ";
                          // Display Locked Message if Topic has been locked by admin
                          if($f_p_status == 2){
                            echo " <strong><font color='red'>Topic Locked</font></strong> ";
                          }
                        echo "</div>";
                    echo "</div>";
                    echo "<div class='col-xs-2'>
                      <button href='#Bar${f_p_id}' class='btn btn-secondary' data-toggle='collapse'>
                        <span class='fas fa-plus' aria-hidden='true'></span>
                      </button>
                    </div>";
                    echo "<div  id='Bar${f_p_id}' class='collapse col-xs-12'>";
                      // Display total replys
                      // Display total topic replys
                      echo "<div class='btn btn-info btn-sm'>";
                        echo "Replies <span class='badge badge-light'>$row2->total_topic_replys</span>";
                      echo "</div>";
                      // Display total sweets
                      echo Sweets::getTotalSweets($f_p_id, 'Forum_Topic', 'Forum_Topic_Reply');
                      // Display total views
                      echo "<div class='btn btn-info btn-sm'> Views <span class='badge badge-light'>";
                        echo PageViews::views('false', $f_p_id, 'Forum_Topic', $data['current_userID']);
                      echo "</span></div>";

                      // Check to see if there has been a reply for this topic.  If not then don't show anything.
                      if(isset($row2->LR_UserID)){
                        // Display Last Reply User Name
                        $rp_user_name2 = CurrentUserData::getUserName($row2->LR_UserID);
                        //Display how long ago this was posted
                        echo "<Br> Last Reply by <a href='".DIR."Profile/$row2->LR_UserID/' style='font-weight: bold'>$rp_user_name2</a> " . TimeDiff::dateDiff("now", "$row2->LR_TimeStamp", 1) . " ago ";
                      }
                    echo "</div>";
                echo "</div>";

            echo "</div></td></tr>";

          } // End query

            echo "</table>";

            // Display Create New Topic Button if user is logged in
            if($data['isLoggedIn'] && $group_forum_perms_post == true){
              echo "<a class='btn btn-sm btn-success' href='".DIR."NewTopic/".$data['current_topic_id']."'>";
                echo "Create New Topic";
              echo "</a><br><br>";
            }

            // Display Paginator Links
            // Check to see if there is more than one page
            if($data['pageLinks'] > "1"){
              echo "<div class='card border-info mb-3'>";
                echo "<div class='card-header h6 text-center'>";
                  echo $data['pageLinks'];
                echo "</div>";
              echo "</div>";
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

    <div class='card mb-3'>
        <div class='card-header h4'>
            <b>Forum Permissions</b>
        </div>
        <div class='card-body'>
            You <b><?php echo $gfp_post; ?></b> post in this forum.<Br>
            You <b><?php echo $gfp_mod; ?></b> moderate this forum.<br>
            You <?php echo $gfp_admin; ?> this forum.<br>
        </div>
    </div>

</div>
