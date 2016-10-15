<?php use Libs\Language; ?>

<div class="col-lg-8 col-md-8 col-sm-8">
	<div class="panel panel-default">
		<div class="panel-heading">
			<h1><?=$title;?></h1>
		</div>
    <table class="table table-striped table-hover table-bordered responsive">
				<thead><tr><th colspan='3'><?=$welcomeMessage;?></th></tr></thead>
        <thead>
            <tr>
                <th>
									<?php
			              if(empty($data['orderby'])){
			                $obu_value = "UN-DESC";
			                $obu_icon = "";
			              }
			              else if($data['orderby'] == "UN-DESC"){
			                $obu_value = "UN-ASC";
			                $obu_icon = "<i class='glyphicon glyphicon-triangle-bottom'></i>";
			              }
			              else if($data['orderby'] == "UN-ASC"){
			                $obu_value = "UN-DESC";
			                $obu_icon = "<i class='glyphicon glyphicon-triangle-top'></i>";
			              }else{
											$obu_value = "UN-ASC";
			                $obu_icon = "";
										}
			              // Setup the order by id button
			              echo "<a href='".DIR."Members/$obu_value/".$data['current_page_num']."' class=''>".Language::show('members_username', 'Members')." $obu_icon</button>";
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
			                $obg_icon = "<i class='glyphicon glyphicon-triangle-bottom'></i>";
			              }
			              else if($data['orderby'] == "UG-ASC"){
			                $obg_value = "UG-DESC";
			                $obg_icon = "<i class='glyphicon glyphicon-triangle-top'></i>";
			              }else{
											$obg_value = "UG-ASC";
			                $obg_icon = "";
										}
			              // Setup the order by id button
			              echo "<a href='".DIR."Members/$obg_value/".$data['current_page_num']."' class=''>".Language::show('members_usergroup', 'Members')." $obg_icon</button>";
			            ?>
								</th>
            </tr>
        </thead>
        <tbody>
        <?php
            foreach($data['members'] as $member){
                echo "<tr>
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
				echo "<div class='panel-footer' style='text-align: center'>";
				echo $data['pageLinks'];
				echo "</div>";
			}
		?>
  </div>
</div>
