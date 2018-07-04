<?php
/**
* Admin Panel System Routes View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

use Libs\Language,
    Libs\Form;

?>


<div class='col-lg-12 col-md-12 col-sm-12'>
	<div class='card mb-3'>
		<div class='card-header h4'>
			System Routes
		</div>
        <div class='card-body'>
            <?=$welcomeMessage?>
        </div>
		<table class='table table-hover responsive'>
			<tr>
				<th>Controller</th>
                <th>Method</th>
                <th>URL Name</th>
                <th>Arguments</th>
                <th>Enabled</th>
                <th></th>
			</tr>
			<?php
				if(isset($data['system_routes'])){
					foreach($data['system_routes'] as $row) {
                        echo "<tr>";
                            echo "<td>$row->controller</td>";
                            echo "<td>$row->method</td>";
                            echo "<td>$row->url</td>";
                            echo "<td>$row->arguments</td>";
                            echo "<td>$row->enable</td>";
                            echo "<td align='right'>";
                            echo "<a href='".DIR."AdminPanel-SystemRoute/$row->id' class='btn btn-xs btn-primary'><span class='fa fa-fw  fa-pencil'></span></a>";
                            echo "</td>";
                        echo "</tr>";
					}
				}
			?>
		</table>
  </div>
</div>
