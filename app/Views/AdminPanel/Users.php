<?php
/**
 * Create the members view
 */

use Core\Language,
		Helpers\ErrorHelper,
		Helpers\SuccessHelper;

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
							echo "<td><a href='".DIR."AdminPanel-User/$row->userID'>$row->username</a></td>";
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
