<?php
/**
 * Create the members view
 */

use Core\Language,
    Helpers\Form;

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
              }
                // Setup the order by id button
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='orderby' value='$ob_value'>";
                echo "<button type='submit' class='btn btn-info btn-sm'>ID $ob_icon</button>";
                echo "</form>";
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
              }
                // Setup the order by id button
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='orderby' value='$obu_value'>";
                echo "<button type='submit' class='btn btn-info btn-sm'>Group Name $obu_icon</button>";
                echo "</form>";
            ?>
          </th>
          <th>Display</th>
				</tr>
				<?php
					if(isset($data['groups_list'])){
						foreach($data['groups_list'] as $row) {
							echo "<tr>";
              echo "<td>$row->groupID</td>";
							echo "<td><a href='".DIR."AdminPanel-Group/$row->groupID'>$row->groupName</a></td>";
              echo "<td><font color='$row->groupFontColor' style='font-weight: $row->groupFontWeight'>$row->groupName</font></td>";
							echo "</tr>";
						}
					}
				?>
			</table>
		</div>
  </div>
</div>
