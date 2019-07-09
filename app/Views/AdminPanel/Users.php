<?php
/**
* Admin Panel Users View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language,
		Libs\ErrorMessages,
		Libs\SuccessMessages,
		Libs\CurrentUserData,
		Libs\PageFunctions,
		Libs\Form;

$orderby = $data['orderby'];
/** Setup Sort By User ID **/
if(empty($data['orderby'])){
	$ob_value = "ID-DESC";
	$ob_icon = "";
}
else if($data['orderby'] == "ID-DESC"){
	$ob_value = "ID-ASC";
	$ob_icon = "<i class='fa fa-fw  fa-caret-down'></i>";
}
else if($data['orderby'] == "ID-ASC"){
	$ob_value = "ID-DESC";
	$ob_icon = "<i class='fa fa-fw  fa-caret-up'></i>";
}else{
	$ob_value = "ID-ASC";
	$ob_icon = "";
}
/** Setup Sort By Username **/
if(empty($data['orderby'])){
	$obu_value = "UN-DESC";
	$obu_icon = "";
}
else if($data['orderby'] == "UN-DESC"){
	$obu_value = "UN-ASC";
	$obu_icon = "<i class='fa fa-fw  fa-caret-down'></i>";
}
else if($data['orderby'] == "UN-ASC"){
	$obu_value = "UN-DESC";
	$obu_icon = "<i class='fa fa-fw  fa-caret-up'></i>";
}else{
	$obu_value = "UN-ASC";
	$obu_icon = "";
}
?>


<div class='col-lg-12 col-md-12 col-sm-12'>

	<!-- User Search -->
  <div class="card mb-3">
    <div class="card-header h4" role="tab" id="headingUnfiled">
			<a class="collapsed d-block search-users" data-toggle="collapse" href="#collapse-collapsed" aria-expanded="true" aria-controls="collapse-collapsed" id="heading-collapsed">
					<i class="fa fa-fw fa-users"></i> <span>Search Users</span>
			</a>
		</div>
		<div id="collapse-collapsed" class="collapse <?php if(!empty($data['search_users_data'])){echo "show";} ?>" aria-labelledby="heading-collapsed">
			<div class="card-body">
				Use this Search form to find the user your looking for.

				<?php echo Form::open(array('method' => 'post', 'action' => SITE_URL.'AdminPanel-Users')); ?>
				<div class='row'>
					<div class='col-12'>
						<?php echo Form::input(array('type' => 'text', 'name' => 'search_users_data', 'class' => 'form-control', 'value' => $search_users_data, 'placeholder' => 'Type Username, First Name, or Last Name.', 'maxlength' => '255')); ?>
					</div>
				</div>
				<!-- CSRF Token -->
				<?php echo Form::input(array('type' => 'hidden', 'name' => 'token_user', 'value' => $data['csrfToken'])); ?>
				<?php echo Form::input(array('type' => 'hidden', 'name' => 'search_users', 'value' => 'true')); ?>
				<br>
				<?php echo Form::button(array('class' => 'btn btn-success', 'name' => 'submit', 'type' => 'submit', 'value' => 'Search Users')); ?>
				<?php echo Form::close(); ?>

			</div>
		</div>
	</div>


	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
			<?php echo PageFunctions::displayPopover('Site Users Admin', 'Site Users Admin list all users that are registered for the site.  Click on the UserName for more info.  Click the Edit button to edit the user.', false, 'btn btn-sm btn-light'); ?>
		</div>
			<table class='table table-hover responsive'>
				<tr>
					<th>
            <?php
                // Setup the order by id button
								echo "<a href='".DIR."AdminPanel-Users/$ob_value/".$data['current_page_num']."/$search_users_data' class='btn btn-info btn-sm'>UID $ob_icon</button>";
            ?>
          </th>
					<th>
            <?php
              // Setup the order by id button
              echo "<a href='".DIR."AdminPanel-Users/$obu_value/".$data['current_page_num']."/$search_users_data' class='btn btn-info btn-sm'>UserName $obu_icon</button>";
            ?>
          </th>
          <th>Name</th>
          <th class='d-none d-md-table-cell'>LastLogin</th>
					<th class='d-none d-md-table-cell'>SignUp</th>
					<th></th>
				</tr>
				<?php
					if(isset($data['users_list'])){
						foreach($data['users_list'] as $row) {
							$online_check = CurrentUserData::getUserStatusDot($row->userID);
							$online_check_status = CurrentUserData::getUserStatus($row->userID);
							echo "<tr>";
              echo "<td>$row->userID</td>";
							echo "<td><button type='button' class='btn btn-secondary btn-sm' data-toggle='modal' data-target='#myModal-$row->userID'> $online_check $row->username</button></td>";
							echo "<td>$row->firstName $row->lastName</td>";
              echo "<td class='d-none d-md-table-cell'>";
								if($row->LastLogin){ echo date("M d, y",strtotime($row->LastLogin)); }else{ echo "Never"; }
							echo "</td>";
							echo "<td class='d-none d-md-table-cell'>";
							echo date("M d, y",strtotime($row->SignUp));
							echo "</td>";
							echo "<td align='right'>";
							echo "<a href='".DIR."AdminPanel-User/$row->userID' class='btn btn-sm btn-primary'><span class='fas fa-edit'></span></a>";
							echo "</td>";
							echo "</tr>";
							echo "
								<!-- Modal -->
								<div class='modal fade' id='myModal-$row->userID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
								  <div class='modal-dialog' role='document'>
								    <div class='modal-content'>
								      <div class='modal-header'>
								        <h4 class='modal-title' id='myModalLabel'><span class='fa fa-fw  fa-user'></span> ".$row->username."&#39;s Information</h4>
												<button type='button' class='close float-right' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
								      </div>
								      <div class='modal-body'>
												<div class='row'>
							";
													$user_image_display = \Libs\CurrentUserData::getUserImage($row->userID);
													if(!empty($user_image_display)){
														echo "<div class='col-lg-6 col-md-6 col-sm-6'>";
														echo "<img alt='$row->username's Profile Picture' src='".SITE_URL.IMG_DIR_PROFILE.$user_image_display."' class='rounded img-fluid'>";
														echo "</div>";
														echo "<div class='col-lg-6 col-md-6 col-sm-6'>";
													}else{
														echo "<div class='col-lg-12 col-md-12 col-sm-12'>";
													}
							echo "
														<b style='border-bottom: 1px solid #ccc'>User's Groups</b><Br>
							";
														$users_groups = \Libs\CurrentUserData::getUserGroups($row->userID);
														if(isset($users_groups)){
															foreach($users_groups as $ug_row){ echo " - <font size='2'>".$ug_row."</font> <br>"; };
														}else{
															echo " - <font size='2'>User Not a Member of Any Groups</font> <br>";
														}
							echo "
														<br><b style='border-bottom: 1px solid #ccc'>Account Status:</b><br>
							";
														if($row->isactive == 1){ echo "- Account is <font color=green>Active</font>"; }else{ echo "- Account is <font color=red>Not Active</font>"; }
														echo "<br>- User is $online_check_status";
							echo "
													</div>
												</div>
								      </div>
								      <div class='modal-footer'>
												<a class='btn btn-primary btn-sm' href='".DIR."AdminPanel-User/$row->userID'>Edit ".$row->username."&#39;s Info</a>
								        <button type='button' class='btn btn-secondary btn-sm' data-dismiss='modal'>Close</button>
								      </div>
								    </div>
								  </div>
								</div>
							";
						}
					}
				?>
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
