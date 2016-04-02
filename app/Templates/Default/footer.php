<div class='col-lg-12'>
	<!-- Footer (sticky) -->
	<footer class='navbar navbar-default'>
		<div class='container'>
			<div class='navbar-text'>

				<!-- Footer links / text -->
				Powered By <a href='http://www.userapplepie.com' title='View UserApplePie Website' ALT='UserApplePie' target='_blank'>UserApplePie v3</a>

				<!-- Display Copywrite stuff with auto year -->
				<Br> &copy; <?php echo date("Y") ?> <?php echo SITETITLE;?> All Rights Reserved.
			</div>
		</div>
	</footer>
</div>


	</div>
</div>

<?php
if(isset($data['ownjs'])){
	foreach($data['ownjs'] as $ownjs){
		echo $ownjs;
	}
}
Assets::js([
	'https://code.jquery.com/jquery-1.12.1.min.js',
    'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js',
]);
echo $js; //place to pass data / plugable hook zone
echo $footer; //place to pass data / plugable hook zone
?>

</body>
</html>
