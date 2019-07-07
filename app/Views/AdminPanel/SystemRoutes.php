<?php
/**
* Admin Panel System Routes View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use Libs\Language,
    Libs\Form,
    Libs\PageFunctions;

?>


<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			System Routes
      <?php echo PageFunctions::displayPopover('Site System Routes', 'Site System Routes let the site know where to direct the requested URL.  It takes the URL and searches the routes to know which Controller and Method to load. When a new Controller and/or Method is added to the site, this page will automatically detect the changes.  It will add the new data to the Routes table, and can be edited.', false, 'btn btn-sm btn-light'); ?>
		</div>
        <div class='card-body'>
            <?=$welcomeMessage?>
        </div>
		<table class='table table-hover responsive'>
			<tr>
				<th class='d-none d-md-table-cell'>Controller</th>
        <th class='d-none d-md-table-cell'>Method</th>
        <th>URL Name</th>
        <th class='d-none d-md-table-cell'>Arguments</th>
        <th>Enabled</th>
        <th></th>
			</tr>
			<?php
				if(isset($data['system_routes'])){
					foreach($data['system_routes'] as $row) {
                        echo "<tr>";
                            echo "<td class='d-none d-md-table-cell'>$row->controller</td>";
                            echo "<td class='d-none d-md-table-cell'>$row->method</td>";
                            echo "<td>$row->url</td>";
                            echo "<td class='d-none d-md-table-cell'>$row->arguments</td>";
                            echo "<td>$row->enable</td>";
                            echo "<td align='right'>";
                            echo "<a href='".DIR."AdminPanel-SystemRoute/$row->id' class='btn btn-sm btn-primary'><span class='fas fa-fw fa-edit'></span></a>";
                            echo "</td>";
                        echo "</tr>";
					}
				}
			?>
		</table>
  </div>
</div>
