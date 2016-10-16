<?php
/**
* UserApplePie v3 Forum Plugin
* @author David (DaVaR) Sargent
* @email davar@thedavar.net
* @website http://www.userapplepie.com
* @version 1.0.0
* @release_date 04/27/2016
**/

/** Forum Topics List View **/

use App\System\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form,
  Libs\TimeDiff,
  Libs\CurrentUserData,
  Libs\PageViews,
  Libs\Sweets,
  Libs\Images;

?>
<div class='col-lg-8 col-md-8'>

	<div class='panel panel-default'>
		<div class='panel-heading'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
		</div>
		<div class='panel-body'>
			<p><?php echo $data['welcome_message'] ?></p>
				<?php
        // Setup form list table stuff
				echo "
					<div class='panel-body hidden-xs'>
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

          $f_p_title = stripslashes($f_p_title);
                  echo "<tr><td>";
                  echo "<div class='row'>";
                  echo "<div class='col-md-7 col-sm-6 col-xs-12'>";
                    echo "<div class='col-xs-10'>";
                      // Add text to blank Topic Titles
                      if(empty($f_p_title)){ $f_p_title = "Oops! Title is Missing for this Topic."; }
                      echo "<h4>";
                      echo "<a href='".DIR."Topic/$f_p_id/' title='$f_p_title' ALT='$f_p_title'>$f_p_title</a>";
                      echo "</h4>";
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
                      <button href='#Bar${f_p_id}' class='btn btn-default visible-xs' data-toggle='collapse'>
                        <span class='glyphicon glyphicon-plus' aria-hidden='true'></span>
                      </button>
                    </div>";
                  echo "</div>";
                  echo "<div class='hidden-xs'>";
                    echo "<div class='col-md-2 col-sm-3 col-xs-6'>";
                      // Display total replys
                      // Display total topic replys
                      echo "<div class='btn btn-info btn-xs' style='margin-bottom: 3px'>";
                  		  echo "Replies <span class='badge'>$row2->total_topic_replys</span>";
                  		echo "</div>";
                      echo " ";
                      // Display total sweets
                      echo Sweets::getTotalSweets($f_p_id, 'Forum_Topic', 'Forum_Topic_Reply');
                      echo " ";
                      // Display total views
                      echo "<div class='btn btn-info btn-xs' style='margin-top: 3px'> Views <span class='badge'>";
                        echo PageViews::views('false', $f_p_id, 'Forum_Topic', $data['current_userID']);
                      echo "</span></div> ";
                      // Display total images
                      echo "<div class='btn btn-success btn-xs' style='margin-top: 3px'> Images <span class='badge'>";
                        echo Images::getImageCountForum('Topic', $f_p_id);
                      echo "</span></div>";
                    echo "</div>";
                    echo "<div class='col-md-3 col-sm-3 col-xs-6' style='text-align: right'>";
                      // Check to see if there has been a reply for this topic.  If not then don't show anything.
                      if(isset($row2->LR_UserID)){
                        // Display Last Reply User Name
                        $rp_user_name2 = CurrentUserData::getUserName($row2->LR_UserID);
                        //Display how long ago this was posted
                        echo " Last Reply by <br> <a href='".DIR."Profile/$row2->LR_UserID' style='font-weight: bold'>$rp_user_name2</a><br> " . TimeDiff::dateDiff("now", "$row2->LR_TimeStamp", 1) . " ago ";
                      }
                    echo "</div>";
                  echo "</div>";

                // For small devices hides extra info
                echo "<div id='Bar${f_p_id}' class='collapse hidden-sm hidden-md hidden-lg'>";
                  echo "<div class='col-xs-12'>";
                    // Display total replys
                    // Display total topic replys
                    echo "<div class='btn btn-info btn-xs'>";
                      echo "Replies <span class='badge'>$row2->total_topic_replys</span>";
                    echo "</div>";
                    // Display total sweets
                    echo Sweets::getTotalSweets($f_p_id, 'Forum_Topic', 'Forum_Topic_Reply');
                    // Display total views
                    echo "<div class='btn btn-info btn-xs'> Views <span class='badge'>";
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
            if(isset($data['current_userID'])){
              echo "<a class='btn btn-sm btn-success' href='".DIR."NewTopic/".$data['current_topic_id']."'>";
                echo "Create New Topic";
              echo "</a>";
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

				?>
		</div>
	</div>
</div>
