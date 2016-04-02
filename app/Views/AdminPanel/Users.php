<?php
/**
 * Create the members view
 */

use Core\Language,
		Helpers\ErrorHelper,
		Helpers\SuccessHelper;

$orderby = $data['orderby'];

?>
<div class='col-lg-8 col-md-8 col-sm-8'>
	<div class='panel panel-default'>
		<div class='panel-heading'>
			<h3 class='jumbotron-heading'><?php echo $data['title'] ?></h3>
		</div>
		<div class='panel-body'>
			<p><?php echo $data['welcomeMessage'] ?></p>

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
          <th>FirstName</th>
          <th>LastLogin</th>
				</tr>
				<?php
					if(isset($data['users_list'])){
						foreach($data['users_list'] as $row) {
							echo "<tr>";
              echo "<td>$row->userID</td>";
							echo "<td><a href='".DIR."AdminPanel-User/$row->userID'>$row->username</a></td>";
							echo "<td>$row->firstName</td>";
              echo "<td>";
								if($row->LastLogin){ echo date("F d, Y",strtotime($row->LastLogin)); }else{ echo "Never"; }
							echo "</td>";
							echo "</tr>";
						}
					}
				?>
			</table>
		</div>
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
