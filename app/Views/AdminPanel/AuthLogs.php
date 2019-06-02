<?php
/**
* Admin Panel Auth Log Viewer
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language;

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
		</div>
			<table class='table table-hover responsive'>
				<tr>
					<th>Date</th>
					<th>Username</th>
          <th>Action</th>
          <th class="hidden-xs">Info</th>
					<th class="hidden-xs">IP</th>
				</tr>
				<?php
					if(isset($data['auth_logs'])){
						foreach($data['auth_logs'] as $row) {
							echo "<tr>";
              echo "<td>$row->date</td>";
							echo "<td>$row->username</td>";
							echo "<td>$row->action</td>";
              echo "<td class='hidden-xs'>$row->additionalinfo</td>";
							echo "<td class='hidden-xs'>$row->ip</td>";
							echo "</tr>";
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
