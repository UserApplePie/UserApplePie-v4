<?php
/**
* Site Members List View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

use Libs\Language;
?>

<div class="col-lg-8 col-md-8 col-sm-12">
	<div class="card mb-3">
		<div class="card-header h4">
			<h1><?=$title?></h1>
		</div>
    <table class="table table-striped table-hover table-bordered responsive">
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
					echo "<a href='".DIR."Members/$obu_value/".$data['current_page_num'].$search_url."' class=''>".Language::show('members_username', 'Members')." $obu_icon</button>";
					?>
				</th>
                <th><?=Language::show('members_firstname', 'Members'); ?></th>
                <th>
					<?php
					if(empty($data['orderby'])){
						$obg_value = "UG-DESC";
						$obg_icon = "";
					}
					else if($data['orderby'] == "UG-DESC"){
						$obg_value = "UG-ASC";
						$obg_icon = "<i class='fas fa-caret-down'></i>";
					}
					else if($data['orderby'] == "UG-ASC"){
						$obg_value = "UG-DESC";
						$obg_icon = "<i class='fas fa-caret-up'></i>";
					}else{
						$obg_value = "UG-ASC";
						$obg_icon = "";
					}
					// Setup the order by id button
					echo "<a href='".DIR."Members/$obg_value/".$data['current_page_num'].$search_url."' class=''>".Language::show('members_usergroup', 'Members')." $obg_icon</button>";
					?>
								</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($data['members'] as $member){
                echo "<tr>
                        <td width='20px'><img src=".SITE_URL.IMG_DIR_PROFILE.$member->userImage." class='rounded' style='height: 25px'></td>
						<td><a href='".DIR."Profile/{$member->username}'> {$member->username}</a></td>
                        <td>{$member->firstName}</td>
                        <td><font color='{$member->groupFontColor}' style='font-weight:{$member->groupFontWeight}'>{$member->groupName}</font></td></tr>";
            }
        ?>
        </tbody>
    </table>
		<?php
			// Check to see if there is more than one page
			if($data['pageLinks'] > "1"){
				echo "<div class='card-footer text-muted' style='text-align: center'>";
				echo $data['pageLinks'];
				echo "</div>";
			}
		?>
  </div>
</div>
