<?php
/**
* UserApplePie v4 Forum View Plugin Home
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.0 for UAP v.4.2.0
*/

/** Forum Home Page View **/

use Core\Language,
  Libs\ErrorMessages,
  Libs\SuccessMessages,
  Libs\Form,
  Libs\ForumStats,
  Libs\CurrentUserData,
  Libs\TimeDiff;

?>

<div class='col-lg-8 col-md-8'>

	<div class='card mb-3'>
		<div class='card-header h4'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
		</div>
		<div class='card-body'>
			<p><?php echo $data['welcome_message'] ?></p>
				<?php
        foreach($data['forum_categories'] as $row)
      	{
      		$f_title = $row->forum_title;
      		$f_id = $row->forum_id;
      		$f_order_title = $row->forum_order_title;

      		echo "<div class='card mb-3'>";
      			echo "<div class='card-header h4' style='font-weight: bold'>";

              // Title Output
              echo "$f_title";

      			echo "</div>";
      			echo "<ul class='list-group list-group-flush'>";
      				foreach($data['forum_titles'] as $row2)
      				{
                if($f_title == $row2->forum_title){
        					echo "<ul class='list-group-item'>";
        						$f_cat = $row2->forum_cat;
        						$f_des = $row2->forum_des;
        						$f_id2 = $row2->forum_id;
        						$cat_order_id = $row2->forum_order_cat;

                                $has_user_read = ForumStats::checkUserReadCat($data['current_userID'], $f_id2);

        						$f_des = stripslashes($f_des);
        						$f_cat = stripslashes($f_cat);

        						echo "<div class='media'>";
    							echo "<div class='media-body'>";
        								// Display Link To View Topics for Category.
                                    echo "<h4>";
                                    // Display icon that lets user know if they have read this topic or not
                                    if($has_user_read){
                                        echo "<span class='fas fa-star' aria-hidden='true'></span> ";
                                    }else{
                                        echo "<span class='far fa-star' aria-hidden='true' style='color: #DDD'></span> ";
                                    }
                                    echo "<a href='".DIR."Topics/$f_id2/' title='$f_cat' ALT='$f_cat'>$f_cat</a></h4>";
                                    echo "<div class='' style='text-align: left; font-size: x-small'>";
                                        if($frp = ForumStats::forum_recent_posts("1", $f_id2)){
                                            $uid1 = $frp[0]->forum_user_id;
                                            $uid2 = $frp[0]->fpr_user_id;
                                            $frp_topic_id = $frp[0]->forum_post_id;
                                            $frp_tstamp = $frp[0]->tstamp;
                                            if(empty($uid2)){ $frp_user_id = $uid1; }else{ $frp_user_id = $uid2; }
                                            // Display Last Reply User Name
                                            $frp_user_name = CurrentUserData::getUserName($frp_user_id);
                                            //Display how long ago this was posted
                                            echo " <a href='".DIR."Topic/$frp_topic_id' style='font-weight: bold'>Last Post</a> ";
                                            echo " by <a href='".DIR."Profile/$frp_user_id' style='font-weight: bold'>$frp_user_name</a> " . TimeDiff::dateDiff("now", "$frp_tstamp", 1) . " ago ";
                                        }
                                    echo "</div>";
    							echo "</div>";


								// Displays when on mobile device
								echo "<button href='#Bar$f_id2' class='btn btn-secondary btn-sm d-block d-sm-none' data-toggle='collapse' style='position: absolute; top: 3px; right: 3px'>";
									echo "<span class='fas fa-plus' aria-hidden='true'></span>";
								echo "</button>";

        								echo "<div id='Bar$f_id2' class='collapse d-sm-none'>";
                            echo "<div class='col-12'>";
        								        echo "<div style='text-align: center'>";

                                            // Display total number of topics for this category
                                            echo "<div class='btn btn-info btn-xs' style='margin-top: 5px'>";
                                            echo "Topics <span class='badge badge-light'>$row2->total_topics_display</span>";
                                            echo "</div>";

                                            // Display total number of topic replys for this category
                                            echo "<div class='btn btn-info btn-xs' style='margin-top: 3px'>";
                                            echo "Replies <span class='badge badge-light'>$row2->total_topic_replys_display</span>";
                                            echo "</div>";

        								        echo "</div>";
                            echo "</div>";
        								echo "</div>";

        								// Displays when not on mobile device
        								echo "<div class='media-right d-none d-sm-block' style='text-align: right'>";
                                            // Display total number of topics for this category
                                            echo "<div class='btn btn-info btn-xs' style='margin-top: 5px'>";
                                            echo "Topics <span class='badge badge-light'>$row2->total_topics_display</span>";
                                            echo "</div>";
                                            echo "<br>";
                                            // Display total number of topic replys for this category
                                            echo "<div class='btn btn-info btn-xs' style='margin-top: 3px'>";
                                            echo "Replies <span class='badge badge-light'>$row2->total_topic_replys_display</span>";
                                            echo "</div>";
        								echo "</div>";
        						echo "</div>";
        					echo "</ul>";
                }
      				}
      			echo "</ul>";
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
