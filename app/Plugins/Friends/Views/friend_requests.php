<?php
/**
* UserApplePie v4 Friends View Plugin Friends Display
*
* UserApplePie - Friends Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.1 for UAP v.4.3.0
*/

use Libs\Language,
    Libs\CurrentUserData;
?>

<div class="col-lg-9 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			Pending Received Friend Requests
		</div>
    <table class="table table-striped table-hover responsive">
            <?php
                /** Check to make sure current user has friends **/
                if(!empty($friends_requests_recv)){
                    echo "<thead><tr>
                            <th colspan='2'>User Name</th>
                            <th>First Name</th>
                            <th colspan='3'>Actions</th>
                          </tr></thead>";
                    echo "<tbody>";
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
                            $member_firstName = CurrentUserData::getUserFirstName($friend_id);
                            $member_userImage = CurrentUserData::getUserImage($friend_id);
                            $online_check = CurrentUserData::getUserStatusDot($friend_id);
                            echo "<tr>
                                    <td width='20px'><img src=".SITE_URL.IMG_DIR_PROFILE.$member_userImage." class='rounded' style='height: 25px'></td>
                                    <td> $online_check <a href='".DIR."Profile/{$member_username}'> {$member_username}</a></td>
                                    <td>{$member_firstName}</td>
                                    <td><a href='".SITE_URL."Profile/{$member_username}' class='btn btn-sm btn-primary'>View Profile</a></td>
                                    <td><a href='".SITE_URL."ApproveFriend/{$member_username}' class='btn btn-sm btn-success'>Approve</a></td>
                                    <td><a href='".SITE_URL."CancelFriend/{$member_username}' class='btn btn-sm btn-danger'>Reject Request</a></td>
                                  </tr>";
                        }
                    }
                    echo "</tbody>";
                }else{
                  echo "<tbody>";
                      echo "<tr><th colspan='5'>You don't have any pending friend requests.</th></tr>";
                  echo "</tbody>";
                }
            ?>
    </table>
  </div>

	<div class="card mb-3">
		<div class="card-header h4">
			Pending Sent Friend Requests
		</div>
    <table class="table table-striped table-hover responsive">
            <?php
                /** Check to make sure current user has friends **/
                if(!empty($friends_requests_sent)){
                    echo "<thead><tr>
                            <th colspan='2'>User Name</th>
                            <th>First Name</th>
                            <th colspan='2'>Actions</th>
                          </tr></thead>";
                    echo "<tbody>";
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
                            $member_firstName = CurrentUserData::getUserFirstName($friend_id);
                            $member_userImage = CurrentUserData::getUserImage($friend_id);
                            $online_check = CurrentUserData::getUserStatusDot($friend_id);
                            echo "<tr>
                                    <td width='20px'><img src=".SITE_URL.IMG_DIR_PROFILE.$member_userImage." class='rounded' style='height: 25px'></td>
                                    <td> $online_check <a href='".DIR."Profile/{$member_username}'> {$member_username}</a></td>
                                    <td>{$member_firstName}</td>
                                    <td><a href='".SITE_URL."Profile/{$member_username}' class='btn btn-sm btn-primary'>View Profile</a></td>
                                    <td><a href='".SITE_URL."CancelFriend/{$member_username}' class='btn btn-sm btn-danger'>Cancel</a></td>
                                  </tr>";
                        }
                    }
                    echo "</tbody>";
                }else{
                    echo "<tbody>";
                        echo "<tr><th colspan='5'>You don't have any pending friend requests.</th></tr>";
                    echo "</tbody>";
                }
            ?>
    </table>
  </div>
</div>
