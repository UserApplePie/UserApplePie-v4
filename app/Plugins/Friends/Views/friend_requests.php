<?php
/**
* UserApplePie v4 Friends View Plugin Friends Display
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.0
*/

use Libs\Language,
    Libs\CurrentUserData;
?>

<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1><?=$title?></h1>
		</div>
    <table class="table table-bordered responsive">
				<thead><tr><th colspan='4'><?=$welcomeMessage;?></th></tr></thead>
        <thead>
            <tr>
                <th colspan='2'>
                    Users that have requested to be your friend.
				</th>
            </tr>
        </thead>
    </table>
    <div class='panel-body'>
        <div class='row'>

            <?php
                /** Check to make sure current user has friends **/
                if(!empty($friends_requests_recv)){

                    foreach ($friends_requests_recv as $var) {
                        /** Make sure we are showing friend and not current user **/
                        if($var->uid1 == $u_id){
                            $friend_id = $var->uid2;
                        }else if($var->uid2 == $u_id){
                            $friend_id = $var->uid1;
                        }
                        /** Check to make sure there is a friend id **/
                        if(isset($friend_id)){
                            $member_username = CurrentUserData::getUserName($friend_id);
                            $member_userImage = CurrentUserData::getUserImage($friend_id);
                            echo "<div class='col-sm-6 col-md-4'>
                                    <div class='thumbnail' align='center'>
                                        <img src=".SITE_URL.IMG_DIR_PROFILE.$member_userImage." class='img-rounded img-responsive'>
                                        <div class='caption'>
                                            <h4>{$member_username}</h4>
                                            <p>
                                                <a href='".SITE_URL."Profile/{$member_username}' class='btn btn-sm btn-primary'>View Profile</a>
                                                <a href='".SITE_URL."ApproveFriend/{$member_username}' class='btn btn-sm btn-success'>Approve</a>
                                            </p>
                                            <p>
                                                <a href='".SITE_URL."CancelFriend/{$member_username}' class='btn btn-sm btn-danger'>Cancel</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>";
                        }
                    }
                }else{
                    echo "<div class='col-sm-12 col-md-12'>";
                        echo "You don't have any friend requests.";
                    echo "</div>";
                }
            ?>
        </div>
    </div>
    <table class="table table-bordered responsive">
            <tr>
                <th colspan='2'>
                    Users that you have requested to be your friend.
				</th>
            </tr>
        </thead>
    </table>
    <div class='panel-body'>
        <div class='row'>

            <?php
                /** Check to make sure current user has friends **/
                if(!empty($friends_requests_sent)){

                    foreach ($friends_requests_sent as $var) {
                        /** Make sure we are showing friend and not current user **/
                        if($var->uid1 == $u_id){
                            $friend_id = $var->uid2;
                        }else if($var->uid2 == $u_id){
                            $friend_id = $var->uid1;
                        }
                        /** Check to make sure there is a friend id **/
                        if(isset($friend_id)){
                            $member_username = CurrentUserData::getUserName($friend_id);
                            $member_userImage = CurrentUserData::getUserImage($friend_id);
                            echo "<div class='col-sm-6 col-md-4'>
                                    <div class='thumbnail' align='center'>
                                        <img src=".SITE_URL.IMG_DIR_PROFILE.$member_userImage." class='img-rounded img-responsive'>
                                        <div class='caption'>
                                            <h4>{$member_username}</h4>
                                            <p>
                                                <a href='".SITE_URL."Profile/{$member_username}' class='btn btn-sm btn-primary'>View Profile</a>
                                                <a href='".SITE_URL."CancelFriend/{$member_username}' class='btn btn-sm btn-danger'>Cancel</a>
                                            </p>
                                        </div>
                                    </div>
                                </div>";
                        }
                    }
                }else{
                    echo "<div class='col-sm-12 col-md-12'>";
                        echo "You don't have any friend requests.";
                    echo "</div>";
                }
            ?>
        </div>
    </div>
  </div>
</div>
