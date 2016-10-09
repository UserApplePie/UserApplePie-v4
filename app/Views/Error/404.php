<?php

use Core\Error;

?>
<div class="col-lg-12 col-md-12 col-sm-12">
	<div class="panel panel-danger">
		<div class="panel-heading">

			<h1>404</h1>

			<?php echo $data['error'];?>

		</div>
		<div class="panel-body">

			<?php echo Language::show('404content', '404'); ?>

		</div>
	</div>
</div>
