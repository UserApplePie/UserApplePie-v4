<?php
/**
* Recent View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language,
    Libs\CurrentUserData,
    Libs\TimeDiff,
    Libs\ForumStats;
?>

<div class="col-lg-4 col-md-4 col-sm-12">
	<div class="card mb-3">
        <div class="card-header h4">
            <?php echo Language::show('recent_posts', 'Welcome'); ?>
        </div>
        <div class="card-body">
        <?php
          /** Get Recent Data **/
          if(!empty($data['recent'])){
            foreach ($data['recent'] as $recent) {
              /** Setup Anchor Count **/
              if(isset($vm_id_a)){ $vm_id_a++; }else{ $vm_id_a = '1'; };
              echo "<a class='anchor' name='viewmore$vm_id_a'></a>";
              /** Get Posted User Data **/
              $recent_userName = CurrentUserData::getUserName($recent->RP_06);
              $recent_userImage = CurrentUserData::getUserImage($recent->RP_06);
              /** Check to see if recent sweet **/
              if($recent->post_type == "sweet"){
                /** Check if Forum_Topic **/
                if($recent->RP_03 == "Forum_Topic"){
                  /** Get Forum Post Data **/
                  $forum_post_title = ForumStats::getForumPostTitle($recent->RP_04);
                  /** Display the data for current recent **/
                  echo "<div class='card bg-secondary mb-3'>";
                    echo "<div class='card-header'>";
                      echo "<a href='".DIR."Profile/{$recent_userName}'>";
                        echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                      echo "</a>";
                      echo " $recent_userName sweeted a Forum Topic..";
                    echo "</div>";
                    echo "<div class='card-body'>";
                      echo "<strong>";
                      echo "<a href='".SITE_URL."Topic/$recent->RP_04/' title='$forum_post_title' ALT='$forum_post_title'>$forum_post_title</a>";
                      echo "</strong>";
                    echo "</div>";
                    echo "<div class='card-footer'>";
                      echo TimeDiff::dateDiff("now", "$recent->RP_01", 1) . " ago ";
                    echo "</div>";
                  echo "</div>";
                }

                /** Check if Forum_Topic **/
                else if($recent->RP_03 == "Forum_Topic_Reply"){
                  /** Get Forum Post Data **/
                  $forum_post_title = ForumStats::getForumPostTitle($recent->RP_04);
                  /** Display the data for current recent **/
                  echo "<div class='card bg-secondary mb-3'>";
                    echo "<div class='card-header'>";
                      echo "<a href='".DIR."Profile/{$recent_userName}'>";
                        echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                      echo "</a>";
                      echo " $recent_userName sweeted a Forum Topic Reply..";
                    echo "</div>";
                    echo "<div class='card-body'>";
                      echo "<strong>";
//  TODO  Firgure out what page the post reply was on so that it can center that post
                      echo "<a href='".SITE_URL."Topic/$recent->RP_04/1/#topicreply$recent->RP_05' title='$forum_post_title' ALT='$forum_post_title'>$forum_post_title</a>";
                      echo "</strong>";
                    echo "</div>";
                    echo "<div class='card-footer'>";
                      echo TimeDiff::dateDiff("now", "$recent->RP_01", 1) . " ago ";
                    echo "</div>";
                  echo "</div>";
                }

              }
              /** Check to see if recent forum post **/
              else if($recent->post_type == "forum_posts"){
                /** Display the data for current recent **/
                echo "<div class='card bg-secondary mb-3'>";
                  echo "<div class='card-header'>";
                    echo "<a href='".DIR."Profile/{$recent_userName}'>";
                      echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                    echo "</a>";
                    echo " $recent_userName Created a new Forum Post..";
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo "<strong>";
                    echo "<a href='".SITE_URL."Topic/$recent->RP_02/' title='$recent->RP_04' ALT='$recent->RP_04'>$recent->RP_04</a>";
                    echo "</strong>";
                  echo "</div>";
                  echo "<div class='card-footer'>";
                    echo TimeDiff::dateDiff("now", "$recent->RP_01", 1) . " ago ";
                  echo "</div>";
                echo "</div>";
              }

              /** Check to see if recent forum post **/
              else if($recent->post_type == "forum_post_replies"){
                /** Get Forum Post Data **/
                $forum_post_title = ForumStats::getForumPostTitle($recent->RP_02);
                /** Display the data for current recent **/
                echo "<div class='card bg-secondary mb-3'>";
                  echo "<div class='card-header'>";
                    echo "<a href='".DIR."Profile/{$recent_userName}'>";
                      echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                    echo "</a>";
                    echo " $recent_userName Replied to a Forum Post..";
                  echo "</div>";
                  echo "<div class='card-body'>";
                    echo "<strong>";
//  TODO  Firgure out what page the post reply was on so that it can center that post
                    echo "<a href='".SITE_URL."Topic/$recent->RP_02/1/#topicreply$recent->RP_05' title='$forum_post_title' ALT='$forum_post_title'>$forum_post_title</a>";
                    echo "</strong>";
                  echo "</div>";
                  echo "<div class='card-footer'>";
                    echo TimeDiff::dateDiff("now", "$recent->RP_01", 1) . " ago ";
                  echo "</div>";
                echo "</div>";
              }

              echo "<hr>";
            }
            /** Check to see if there are most recents than currently shown **/
            echo "<div class='card'><div class='card-body text-center'>";
              if(isset($recent_limit)){}else{$recent_limit = "0";}
              if($recent_limit >= $recent_total){
                echo "Currently Showing $recent_total of $recent_total Recent Posts";
              }else{
                echo "Currently Showing $recent_limit of $recent_total Recent Posts";
              }
              if(!isset($recent_total)){$recent_total = "0";}else{
                if(isset($limitfriendstatus)){}else{ $limitfriendstatus = "10"; }
                if($recent_limit < $recent_total){
                  $vm_id = $recent_limit + 1;
                  echo "<span class='btn btn-default'>";
                    echo "<a href=\"".SITE_URL."Home/" . ($recent_limit + 10) . "#viewmore$vm_id\">Show More Recent Posts</a> ";
                  echo "</span>";
                }
              }
            echo "</div></div>";
            }else{
              /** User Does not have any friends **/
              echo "Your friends don't have any recent activity. :( <Br><Br>";
              echo "<a href='".SITE_URL."Members'>Browse Site Members</a>";
            }
        ?>
        </div>
    </div>
</div>
<br><br>
