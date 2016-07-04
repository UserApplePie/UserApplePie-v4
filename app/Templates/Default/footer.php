		<div class='col-lg-12 col-md-12 col-sm-12'>
			<!-- Footer (sticky) -->
			<footer class='navbar navbar-default'>
				<div class='container'>
					<div class='navbar-text'>

						<!-- Footer links / text -->
						<?=Language::show('uap_poweredby', 'Welcome');?> <a href='http://www.userapplepie.com' title='View UserApplePie Website' ALT='UserApplePie' target='_blank'>UserApplePie v3</a>

						<!-- Display Copywrite stuff with auto year -->
						<Br> &copy; <?php echo date("Y") ?> <?php echo SITETITLE;?> <?=Language::show('uap_all_rights', 'Welcome');?>.
							<br>
							<?php
								/** Get List of all enabled Languages **/
								$languages = \Core\Language::getlangs();
								$cur_lang_code = LANG_CODE;
								foreach ($languages as $code => $info) {
									/** List Lang Links **/
									if($cur_lang_code == $code){
										echo "<strong>".$info['name']."</strong> ";
									}else{
										echo "<a href='".DIR."ChangeLang/".$code."' title='".$info['info']."'>".$info['name']."</a> ";
									}
								}
							?>
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
		'https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js',
]);
echo $js; //place to pass data / plugable hook zone
echo $footer; //place to pass data / plugable hook zone
?>

</body>
</html>
