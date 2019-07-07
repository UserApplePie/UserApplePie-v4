<?php
/**
* Admin Panel Auth Log Viewer
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language,
		Libs\PageFunctions;

?>
<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			<?php echo $data['title'] ?>
			<?php echo PageFunctions::displayPopover('Site Auth Logs', 'Site Auth Logs displays all logs related to user registration and login.  This is best used to detect an attack on the site, and enable the admin to fix possible security issues.', false, 'btn btn-sm btn-light'); ?>
		</div>
			<table class='table table-hover responsive'>
				<tr>
					<th class='d-none d-md-table-cell'>Date</th>
					<th>Username</th>
          <th>Action</th>
          <th class='d-none d-md-table-cell'>Info</th>
					<th class='d-none d-md-table-cell'>IP</th>
				</tr>
				<?php
					if(isset($data['auth_logs'])){
						foreach($data['auth_logs'] as $row) {
							echo "<tr>";
              echo "<td class='d-none d-md-table-cell'>$row->date</td>";
							echo "<td>$row->username</td>";
							echo "<td>$row->action</td>";
              echo "<td class='d-none d-md-table-cell'>$row->additionalinfo</td>";
							echo "<td class='d-none d-md-table-cell'>$row->ip</td>";
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
