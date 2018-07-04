<?php
/**
* System Error View
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

use Core\Error;

?>
<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="card card-danger">
		<div class="card-header h4">

			<h1>404</h1>

			<?php echo $data['error'];?>

		</div>
		<div class="card-body">

			<?php echo Language::show('404content', '404'); ?>

		</div>
	</div>
</div>
