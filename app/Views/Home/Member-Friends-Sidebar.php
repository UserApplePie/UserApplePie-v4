<?php
/**
* UserApplePie v4 Friends Sidebar for recent activity page
*
* UserApplePie - Friends Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

  // View to display message sidebar

  use Libs\Language;
  use Libs\CurrentUserData;

?>


  <div class='card mb-3'>
    <div class='card-header h4' style='font-weight: bold'>
      My Friends
    </div>
    <ul class='list-group list-group-flush'>
      <li class='list-group-item'><span class='fas fa-inbox'></span> <a href='<?php echo DIR ?>FriendRequests' rel='nofollow'>Friend Requests
        <?php
            /** Check to see if there are any pending friend requests **/
            $new_friend_count = \Libs\CurrentUserData::getFriendRequests($currentUserData[0]->userID);
            if($new_friend_count >= "1"){
                echo " <span class='badge badge-primary'>".$new_friend_count." New</span>";
            }
        ?>
      </a></li>
      <li class='list-group-item'><span class='fas fa-user'></span> <a href='<?php echo DIR ?>Friends' rel='nofollow'>Friends
        <?php
            /** Check to see how many friends user has **/
            $total_friend_count = \Libs\CurrentUserData::getFriendsCount($currentUserData[0]->userID);
            if($total_friend_count >= "1"){
              echo " <span class='badge badge-primary'>".$total_friend_count." Total</span>";
            }
        ?>
      </a></li>
    </ul>
  </div>

  <div class='card mb-3'>
      <form onSubmit="return process();" class="form" method="post">
          <div class='card-header h4' style='font-weight: bold'>
          <?=Language::show('search_friends', 'Friends'); ?>
          </div>
          <div class='card-body'>
              <div class="form-group">
              <input type="forumSearch" class="form-control" id="forumSearch" placeholder="<?=Language::show('search_friends', 'Friends'); ?>" value="<?php if(isset($data['search'])){ echo $data['search']; } ?>">
              </div>
          <button type="submit" class="btn btn-secondary"><?=Language::show('search', 'Friends'); ?></button>
          </div>
      </form>
  </div>

    <div class='card mb-3'>
        <div class='card-header h4'>
            <h3>Friends List</h3>
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
              echo "<li class='list-group-item'>You don't have any friends. :(</li>";
              echo "<li class='list-group-item'><a href='".DIR."Members'>Browse Site Members</a></li>";
            }
          ?>
        </ul>
    </div>

    <div class='card mb-3'>
        <div class='card-header h4'>
            <h3>Suggested Friends</h3>
        </div>
        <ul class="list-group list-group-flush">
          <?php
            if(!empty($suggested_friends)){
              /** Get User's Friends **/
              foreach ($suggested_friends as $key => $friend) {
                /** Get User's Friends Data **/
                //var_dump($key, $friend);
                $num_mutual_friends = count($friend['mutual_friends']);
                $friend_userName = CurrentUserData::getUserName($key);
                $friend_userImage = CurrentUserData::getUserImage($key);
                echo "<li class='list-group-item'>";
                  echo "<a href='".DIR."Profile/{$friend_userName}'>";
                    echo "<img src=".SITE_URL.IMG_DIR_PROFILE.$friend_userImage." class='rounded' style='height: 25px'>";
                  echo "</a>&nbsp;";
                  echo "<a href='".DIR."Profile/{$friend_userName}'>";
                    echo "$friend_userName";
                  echo "</a>";
                  echo "<div class='float-right'> $num_mutual_friends Mutual Friends </div>";
                echo "</li>";
              }
            }else{
              echo "<li class='list-group-item'>You don't have any suggested friends. :(</li>";
              echo "<li class='list-group-item'><a href='".DIR."Members'>Browse Site Members</a></li>";
            }
          ?>
        </ul>
    </div>
