<?php
/**
* UserApplePie v4 Messages View Plugin Message Sidebar
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.0
*/

  // View to display message sidebar

  use Libs\Language;

?>

<div class='col-lg-4 col-md-4'>
  <div class='panel panel-default'>
    <div class='panel-heading' style='font-weight: bold'>
      My Friends
    </div>
    <ul class='list-group'>
      <li class='list-group-item'><span class='glyphicon glyphicon-inbox'></span> <a href='<?php echo DIR ?>FriendRequests' rel='nofollow'>Friend Requests
        <?php
            /** Check to see if there are any pending friend requests **/
            $new_friend_count = \Libs\CurrentUserData::getFriendRequests($currentUserData[0]->userID);
            if($new_friend_count >= "1"){
                echo "<span class='badge'>".$new_friend_count." New</span>";
            }
        ?>
      </a></li>
      <li class='list-group-item'><span class='glyphicon glyphicon-user'></span> <a href='<?php echo DIR ?>Friends' rel='nofollow'>Friends
        <?php
            /** Check to see how many friends user has **/
            $total_friend_count = \Libs\CurrentUserData::getFriendsCount($currentUserData[0]->userID);
            if($total_friend_count >= "1"){
              echo "<span class='badge'>".$total_friend_count." Total</span>";
            }
        ?>
      </a></li>
    </ul>
  </div>

    <?php
    // Display Search Friends Form
    if($friends_page){
    ?>
        <div class='panel panel-default'>
            <form onSubmit="return process();" class="form" method="post">
                <div class='panel-heading' style='font-weight: bold'>
                <?=Language::show('search_friends', 'Friends'); ?>
                </div>
                <div class='panel-body'>
                    <div class="form-group">
                    <input type="forumSearch" class="form-control" id="forumSearch" placeholder="<?=Language::show('search_friends', 'Friends'); ?>" value="<?php if(isset($data['search'])){ echo $data['search']; } ?>">
                    </div>
                <button type="submit" class="btn btn-default"><?=Language::show('search', 'Friends'); ?></button>
                </div>
            </form>
        </div>
    <?php } ?>
</div>
