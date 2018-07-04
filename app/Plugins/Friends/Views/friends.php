<?php
/**
* UserApplePie v4 Friends View Plugin Friends Display
*
* UserApplePie - Friends Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.0 for UAP v.4.2.0
*/

use Libs\Language,
    Libs\CurrentUserData;

/** Check to make sure current user has friends **/
if(!empty($friends_list)){
?>

<script>
function process()
  {
  var url="<?php echo SITE_URL; ?>Friends/UN-ASC/1/" + document.getElementById("forumSearch").value;
  location.href=url;
  return false;
  }
</script>

<div class="col-lg-8 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<?=$title?>
		</div>
    <table class="table table-striped table-hover responsive">
				<thead><tr><th colspan='4'><?=$welcomeMessage;?></th></tr></thead>
        <thead>
            <tr>
                <th colspan='2'>
					<?php
					if(empty($data['orderby'])){
						$obu_value = "UN-DESC";
						$obu_icon = "";
					}else if($data['orderby'] == "UN-DESC"){
						$obu_value = "UN-ASC";
						$obu_icon = "<i class='fas fa-caret-down'></i>";
					}else if($data['orderby'] == "UN-ASC"){
						$obu_value = "UN-DESC";
						$obu_icon = "<i class='fas fa-caret-up'></i>";
					}else{
						$obu_value = "UN-ASC";
						$obu_icon = "";
					}
					if(isset($search)){
						$search_url = "/$search";
					}else{
						$search_url = "";
					}
					// Setup the order by id button
					echo "<a href='".DIR."Friends/$obu_value/".$data['current_page_num'].$search_url."' class=''>".Language::show('friends_username', 'Friends')." $obu_icon</button>";
					?>
				</th>
        <th><?=Language::show('members_firstname', 'Members'); ?></th>
        <th></th>
            </tr>
        </thead>
        <tbody>

                <?php
                    foreach ($friends_list as $var) {
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
                            echo "<tr>
                                    <td width='20px'><img src=".SITE_URL.IMG_DIR_PROFILE.$member_userImage." class='rounded' style='height: 25px'></td>
                                    <td><a href='".DIR."Profile/{$member_username}'> {$member_username}</a></td>
                                    <td>{$member_firstName}</td>
                                    <td><div class='float-right'><a href='".SITE_URL."Profile/{$member_username}' class='btn btn-sm btn-primary'>View Profile</a> <a href='".SITE_URL."UnFriend/{$member_username}' class='btn btn-sm btn-secondary'>UnFriend</a></div></td>
                                  </tr>";
                        }
                    }
                ?>
        </tbody>
    </table>

		<?php
			/** Check to see if there is more than one page **/
			if($pageLinks > "1"){
				echo "<div class='card-footer text-muted' style='text-align: center'>";
				echo $pageLinks;
				echo "</div>";
			}
		?>
  </div>
</div>

<?php
}else{
?>
<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="card mb-3">
		<div class="card-header h4">
			<h1><?=$title?></h1>
		</div>
        <div class='card-body'>
            Sorry, You don't have any friends yet. :(
        </div>
    </div>
</div>
<?php
}
?>
