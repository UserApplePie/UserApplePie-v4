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
          }else{
            /** User Does not have any friends **/
            echo "You don't have any friends. :( <Br><Br>";
            echo "<a href='".DIR."Members'>Browse Site Members</a>";
          }

        ?>
        </div>
    </div>
</div>
<br><br>
