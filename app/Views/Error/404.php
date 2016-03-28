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

			<h3>The page you were looking for could not be found</h3>
			<p>This could be the result of the page being removed, the name being changed or the page being temporarily unavailable</p>
			<h3>Troubleshooting</h3>

			<ul>
			  <li>If you spelled the URL manually, double check the spelling</li>
			  <li>Go to our website's home page, and navigate to the content in question</li>
			</ul>

		</div>
	</div>
</div>
