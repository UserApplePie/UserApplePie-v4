<?php
/**
 * Create the members view
 */

use Libs\Language,
		Libs\ErrorMessages,
		Libs\SuccessMessages;

$orderby = $data['orderby'];

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<?php echo $data['title'] ?>
		</div>
			<table class='table table-hover responsive'>
				<tr>
					<th>
            <?php
              if(empty($data['orderby'])){
                $ob_value = "ID-DESC";
                $ob_icon = "";
              }
              else if($data['orderby'] == "ID-DESC"){
                $ob_value = "ID-ASC";
                $ob_icon = "<i class='glyphicon glyphicon-triangle-bottom'></i>";
              }
              else if($data['orderby'] == "ID-ASC"){
                $ob_value = "ID-DESC";
                $ob_icon = "<i class='glyphicon glyphicon-triangle-top'></i>";
              }else{
								$ob_value = "ID-ASC";
                $ob_icon = "";
							}
                // Setup the order by id button
								echo "<a href='".DIR."AdminPanel-Users/$ob_value/".$data['current_page_num']."' class='btn btn-info btn-sm'>UID $ob_icon</button>";
            ?>
          </th>
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
              echo "<a href='".DIR."AdminPanel-Users/$obu_value/".$data['current_page_num']."' class='btn btn-info btn-sm'>UserName $obu_icon</button>";
            ?>
          </th>
          <th>Name</th>
          <th class="hidden-xs">LastLogin</th>
					<th class="hidden-xs">SignUp</th>
					<th></th>
				</tr>
				<?php
					if(isset($data['users_list'])){
						foreach($data['users_list'] as $row) {
							echo "<tr>";
              echo "<td>$row->userID</td>";
							echo "<td><button type='button' class='btn btn-default btn-xs' data-toggle='modal' data-target='#myModal-$row->userID'>$row->username</button></td>";
							echo "<td>$row->firstName $row->lastName</td>";
              echo "<td class='hidden-xs'>";
								if($row->LastLogin){ echo date("M d, y",strtotime($row->LastLogin)); }else{ echo "Never"; }
							echo "</td>";
							echo "<td class='hidden-xs'>";
							echo date("M d, y",strtotime($row->SignUp));
							echo "</td>";
							echo "<td align='right'>";
							echo "<a href='".DIR."AdminPanel-User/$row->userID' class='btn btn-xs btn-primary'><span class='glyphicon glyphicon-pencil'></span></a>";
							echo "</td>";
							echo "</tr>";
							echo "
								<!-- Modal -->
								<div class='modal fade' id='myModal-$row->userID' tabindex='-1' role='dialog' aria-labelledby='myModalLabel'>
								  <div class='modal-dialog' role='document'>
								    <div class='modal-content'>
								      <div class='modal-header'>
								        <button type='button' class='close' data-dismiss='modal' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
								        <h4 class='modal-title' id='myModalLabel'><span class='glyphicon glyphicon-user'></span> ".$row->username."&#39;s Information</h4>
								      </div>
								      <div class='modal-body'>
												<div class='row'>
							";
													if(!empty($row->userImage)){
														echo "<div class='col-lg-6 col-md-6 col-sm-6'>";
														echo "<img alt='$row->username's Profile Picture' src='".SITE_URL.$row->userImage."' class='img-rounded img-responsive'>";
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
							echo "
													</div>
												</div>
								      </div>
								      <div class='modal-footer'>
												<a class='btn btn-primary btn-sm' href='".DIR."AdminPanel-User/$row->userID'>Edit ".$row->username."&#39;s Info</a>
								        <button type='button' class='btn btn-default btn-sm' data-dismiss='modal'>Close</button>
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
				echo "<div class='panel-footer' style='text-align: center'>";
				echo $data['pageLinks'];
				echo "</div>";
			}
		?>
	</div>
</div>
