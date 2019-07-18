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
    Libs\ForumStats,
    Libs\Form,
    Libs\BBCode,
    Libs\Sweets,
    Libs\Comments;
?>

<div class="col-lg-6 col-md-4 col-sm-12">
    <div class='card mb-3'>
      <div class='card-header h4'>
        Status Update
      </div>
      <div class='card-body'>
          <?php echo $data['welcome_message']; ?>


              <?php echo Form::open(array('method' => 'post', 'files' => '')); ?>

              <!-- Topic Title -->
              <div class='input-group' style='margin-bottom: 25px'>
                <div class="input-group-prepend">
                  <span class='input-group-text'><i class='far fa-smile'></i> &nbsp Feeling</span>
                </div>
                <select class='custom-select' id='status_feeling' name='status_feeling'>
                  <option value='Excited' <?php if($data['status_feeling'] == "Excited"){echo "SELECTED";}?> >Excited</option>
                  <option value='Happy' <?php if($data['status_feeling'] == "Happy"){echo "SELECTED";}?> >Happy</option>
                  <option value='Hopeful' <?php if($data['status_feeling'] == "Hopeful"){echo "SELECTED";}?> >Hopeful</option>
                  <option value='Cheerful' <?php if($data['status_feeling'] == "Cheerful"){echo "SELECTED";}?> >Cheerful</option>
                  <option value='Surprised' <?php if($data['status_feeling'] == "Surprised"){echo "SELECTED";}?> >Surprised</option>
                  <option value='Sad' <?php if($data['status_feeling'] == "Sad"){echo "SELECTED";}?> >Sad</option>
                  <option value='Angry' <?php if($data['status_feeling'] == "Angry"){echo "SELECTED";}?> >Angry</option>
                  <option value='Annoyed' <?php if($data['status_feeling'] == "Annoyed"){echo "SELECTED";}?> >Annoyed</option>
                  <option value='Disgusted' <?php if($data['status_feeling'] == "Disgusted"){echo "SELECTED";}?> >Disgusted</option>
                  <option value='Contempt' <?php if($data['status_feeling'] == "Contempt"){echo "SELECTED";}?> >Contempt</option>
                  <option value='Fearful' <?php if($data['status_feeling'] == "Fearful"){echo "SELECTED";}?> >Fearful</option>
                  <option value='Shameful' <?php if($data['status_feeling'] == "Shameful"){echo "SELECTED";}?> >Shameful</option>
                  <option value='Guilty' <?php if($data['status_feeling'] == "Guilty"){echo "SELECTED";}?> >Guilty</option>
                </select>
              </div>

              <!-- Topic Content -->
              <div class='input-group' style='margin-bottom: 25px'>
                <div class="input-group-prepend">
                  <span class='input-group-text'>
                    <!-- BBCode Buttons -->
                    <div class='btn-group-vertical'>
                      <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[b]','[/b]');"><i class='fas fa-bold'></i></button>
                      <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[i]','[/i]');"><i class='fas fa-italic'></i></button>
                      <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[u]','[/u]');"><i class='fas fa-underline'></i></button>
                      <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[youtube]','[/youtube]');"><i class='fab fa-youtube'></i></button>
                      <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[quote]','[/quote]');"><i class='fas fa-quote-right'></i></button>
                      <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[code]','[/code]');"><i class='fas fa-code'></i></button>
                      <button type="button" class="btn btn-sm btn-light" onclick="wrapText('edit','[url href=]','[/url]');"><i class='fas fa-link'></i></button>
                    </div>
                  </span>
                </div>
                <?php echo Form::textBox(array('type' => 'text', 'id' => 'status_content', 'name' => 'status_content', 'class' => 'form-control', 'value' => $data['status_content'], 'placeholder' => 'Status Content', 'rows' => '6')); ?>
              </div>

                <!-- CSRF Token -->
                <input type="hidden" id="token_status" name="token_status" value="<?php echo $data['csrfToken']; ?>" />
                <?php
                  /** Check to see if user is editing their status **/
                  if($data['action'] == "status_edit"){
                    echo "<input type='hidden' id='action' name='action' value='status_edit_update' />
                    <input type='hidden' id='action' name='edit_status_id' value='".$data['edit_status_id']."' />
                    <button class='btn btn-md btn-success' name='submit' type='submit' id='submit'>
                      Update Status
                    </button>";
                  }else{
                    echo "<input type='hidden' id='action' name='action' value='status_update' />
                    <button class='btn btn-md btn-success' name='submit' type='submit' id='submit'>
                      Submit New Status
                    </button>";
                  }
                ?>
              <?php echo Form::close(); ?>
              <div id="autoSave"></div>
              <div id="forum_post_id"></div>

      </div>
    </div>

    <?php
      /** Get Recent Data **/
      if(!empty($data['recent'])){
        foreach ($data['recent'] as $recent) {
          /** Setup Anchor Count **/
          if(isset($vm_id_a)){ $vm_id_a++; }else{ $vm_id_a = '1'; };
          echo "<a class='anchor' name='viewmore$vm_id_a'></a>";
          $sweet_url = "Home/".$recent_limit."/#viewmore$vm_id_a";
          /** Get Posted User Data **/
          $recent_userName = CurrentUserData::getUserName($recent->RP_06);
          $recent_userImage = CurrentUserData::getUserImage($recent->RP_06);
          $online_check = CurrentUserData::getUserStatusDot($recent->RP_06);
          /** Check to see if recent sweet **/
          if($recent->post_type == "sweet"){
            /** Check if Forum_Topic **/
            if($recent->RP_03 == "Forum_Topic"){
              /** Get Forum Post Data **/
              $forum_post_title = ForumStats::getForumPostTitle($recent->RP_04);
              /** Display the data for current recent **/
              echo "<div class='card border-secondary mb-3'>";
                echo "<div class='card-header'>";
                  echo "<a href='".DIR."Profile/{$recent_userName}'>";
                    echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                  echo "</a>";
                  echo " $online_check <a href='".DIR."Profile/{$recent_userName}'>$recent_userName</a> sweeted a Forum Topic..";
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
              echo "<div class='card border-secondary mb-3'>";
                echo "<div class='card-header'>";
                  echo "<a href='".DIR."Profile/{$recent_userName}'>";
                    echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                  echo "</a>";
                  echo " $online_check <a href='".DIR."Profile/{$recent_userName}'>$recent_userName</a> sweeted a Forum Topic Reply..";
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
            echo "<div class='card border-secondary mb-3'>";
              echo "<div class='card-header'>";
                echo "<a href='".DIR."Profile/{$recent_userName}'>";
                  echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                echo "</a>";
                echo " $online_check <a href='".DIR."Profile/{$recent_userName}'>$recent_userName</a> Created a new Forum Post..";
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
            echo "<div class='card border-secondary mb-3'>";
              echo "<div class='card-header'>";
                echo "<a href='".DIR."Profile/{$recent_userName}'>";
                  echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                echo "</a>";
                echo " $online_check <a href='".DIR."Profile/{$recent_userName}'>$recent_userName</a> Replied to a Forum Post..";
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

          /** Check to see if recent default image **/
          else if($recent->post_type == "default_images"){
            /** Display the data for current recent **/
            echo "<div class='card border-secondary mb-3'>";
              echo "<div class='card-header'>";
                echo "<a href='".DIR."Profile/{$recent_userName}'>";
                  echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                echo "</a>";
                echo " $online_check <a href='".DIR."Profile/{$recent_userName}'>$recent_userName</a> changed their default photo..";
              echo "</div>";
              echo "<div class='card-body'>";
                echo "<a href='".SITE_URL.IMG_DIR_PROFILE."$recent->RP_03' data-lightbox='photos{$recent->RP_06}{$recent->RP_01}{$recent->RP_02}'><img id='myImg' class='img-thumbnail' src='".SITE_URL.IMG_DIR_PROFILE."$recent->RP_03' style='height: 100px'></a>";
              echo "</div>";
              echo "<div class='card-footer'>";
                echo TimeDiff::dateDiff("now", "$recent->RP_01", 1) . " ago ";
              echo "</div>";
            echo "</div>";
          }

          /** Check to see if recent default image **/
          else if($recent->post_type == "profile_images"){
            /** Display the data for current recent **/
            echo "<div class='card border-secondary mb-3'>";
              echo "<div class='card-header'>";
                echo "<a href='".DIR."Profile/{$recent_userName}'>";
                  echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                echo "</a>";
                echo " $online_check <a href='".DIR."Profile/{$recent_userName}'>$recent_userName</a>";
                $multi_photo = CurrentUserData::getProfileImages10min($recent->RP_06, $recent->RP_01);
                if(count($multi_photo) > 1){
                  echo " uploaded photos to their profile..";
                }else{
                  echo " uploaded a photo to their profile..";
                }
              echo "</div>";
              echo "<div class='card-body'>";

                /** Check to see if muli photos were uploaded **/
                if(count($multi_photo) > 1){
                  foreach ($multi_photo as $photo) {
                    /** Display Image **/
                    echo "<a href='".SITE_URL.IMG_DIR_PROFILE."$photo->userImage' data-lightbox='photos{$recent->RP_06}{$recent->RP_01}{$recent->RP_02}'><img id='myImg' class='img-thumbnail' src='".SITE_URL.IMG_DIR_PROFILE."$photo->userImage' style='height: 100px'></a>";
                  }
                }else{
                  /** Display Image **/
                  echo "<a href='".SITE_URL.IMG_DIR_PROFILE."$recent->RP_03' data-lightbox='photos{$recent->RP_06}{$recent->RP_01}{$recent->RP_02}'><img id='myImg' class='img-thumbnail' src='".SITE_URL.IMG_DIR_PROFILE."$recent->RP_03' style='height: 100px'></a>";
                }

              echo "</div>";
              echo "<div class='card-footer'>";
                echo TimeDiff::dateDiff("now", "$recent->RP_01", 1) . " ago ";
              echo "</div>";
            echo "</div>";

          }

          /** Check to see if recent default image **/
          else if($recent->post_type == "status"){
            /** Display the data for current recent **/
            $status_content = BBCode::getHtml($recent->RP_04);
            if(isset($recent_limit)){}else{$recent_limit = "0";}
            echo "<div class='card border-secondary mb-3'>";
              echo "<div class='card-header'>";
                echo "<a href='".DIR."Profile/{$recent_userName}'>";
                  echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$recent_userImage." class='rounded' style='height: 25px'>";
                echo "</a>";
                echo " $online_check <a href='".DIR."Profile/{$recent_userName}'>$recent_userName</a> is feeling $recent->RP_03..";
              echo "</div>";
              echo "<div class='card-body forum' style='overflow: hidden; height: auto;'>";
                echo $status_content;
              echo "</div>";
              echo "<div class='card-footer'>";
                echo "<div class='row'><div class='col-12'>";
                echo TimeDiff::dateDiff("now", "$recent->RP_01", 1) . " ago ";
                echo "<div class='float-right'>";
                  // Hide button if they are currently editing this reply
                  if($data['action'] != "status_edit" && $data['current_userID'] == $recent->RP_06){
                    echo Form::open(array('method' => 'post', 'action' => '../', 'style' => 'display:inline'));
                    // Topic Reply Edit True
                    echo "<input type='hidden' name='action' value='status_edit' />";
                    // Topic Reply ID for editing
                    echo "<input type='hidden' name='edit_status_id' value='".$recent->RP_02."' />";
                    // CSRF Token
                    echo "<input type='hidden' name='token_status' value='".$data['csrfToken']."' />";
                    // Display Submit Button
                    echo "<button class='btn btn-sm btn-info' name='submit' type='submit'>Edit Status</button>";
                    echo Form::close();
                  }
                  /** Start Sweet **/
                  echo Sweets::displaySweetsButton($recent->RP_02, 'Status', $data['current_userID'], $recent->RP_06, $sweet_url);
                  echo Sweets::getSweets($recent->RP_02, 'Status', $recent->RP_06);
                  echo Comments::getTotalComments($recent->RP_02, 'Status', $recent->RP_06);
                echo "</div></div></div>";
                echo "<div class='col-12 p-0'>";
                  echo Comments::displayComments($recent->RP_02, 'Status', $data['current_userID'], 0, $sweet_url);
                echo "</div>";
              echo "</div>";
            echo "</div>";
          }
          echo "";
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
              echo "<hr>";
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
<br><br>
