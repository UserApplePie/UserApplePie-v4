<?php
// TODO
// Firgure out a better way to run the tests/or have a refreash of some sort.

/** UAP Install Step 2 **/
/** System Settings **/

use Libs\ErrorMessages,
	Libs\SuccessMessages,
	Libs\Url;

/** Function to update Config.example.php file **/
function update_config($default, $new){
	$fname = ROOTDIR."app/Example-Config.php";
	$fhandle = fopen($fname,"r");
	$content = fread($fhandle,filesize($fname));

	$content = str_replace($default, $new, $content);

	$fhandle = fopen($fname,"w");
	fwrite($fhandle,$content);
	fclose($fhandle);
	return true;
}

/** Check to see if user has updated config file **/
if(isset($_GET['update_config_file']) && $_GET['update_config_file'] == "true"){ $updated_config = true; }else{ $updated_config = false; }

/** Check to see if user is submitting data **/
if(isset($_POST['submit'])){
	/** Update Site Title in Config **/
	if(!empty($_REQUEST['SITE_TITLE'])){
		update_config(SITE_TITLE, $_REQUEST['SITE_TITLE']);
	}
	/** Update Site URL in Config **/
	if(!empty($_REQUEST['SITE_URL'])){
		update_config(SITE_URL, $_REQUEST['SITE_URL']);
	}
	/** Update Site Prefix in Config **/
	if(!empty($_REQUEST['PREFIX'])){
		update_config(PREFIX, $_REQUEST['PREFIX']);
	}
	/** Update DB Host in Config **/
	if(!empty($_REQUEST['DB_HOST'])){
		update_config(DB_HOST, $_REQUEST['DB_HOST']);
	}
	/** Update DB Name in Config **/
	if(!empty($_REQUEST['DB_NAME'])){
		update_config(DB_NAME, $_REQUEST['DB_NAME']);
	}
	/** Update DB Username in Config **/
	if(!empty($_REQUEST['DB_USER'])){
		update_config(DB_USER, $_REQUEST['DB_USER']);
	}
	/** Update DB Password in Config **/
	if(!empty($_REQUEST['DB_PASS'])){
		update_config(DB_PASS, $_REQUEST['DB_PASS']);
	}
	/** Update Email Username in Config **/
	if(!empty($_REQUEST['EMAIL_USERNAME'])){
		update_config(EMAIL_USERNAME, $_REQUEST['EMAIL_USERNAME']);
	}
	/** Update Email Password in Config **/
	if(!empty($_REQUEST['EMAIL_PASSWORD'])){
		update_config(EMAIL_PASSWORD, $_REQUEST['EMAIL_PASSWORD']);
	}
	/** Update Email From Name in Config **/
	if(!empty($_REQUEST['EMAIL_FROM_NAME'])){
		update_config(EMAIL_FROM_NAME, $_REQUEST['EMAIL_FROM_NAME']);
	}
	/** Update Email Host Address in Config **/
	if(!empty($_REQUEST['EMAIL_HOST'])){
		update_config(EMAIL_HOST, $_REQUEST['EMAIL_HOST']);
	}
	/** Update Email Host Address Port in Config **/
	if(!empty($_REQUEST['EMAIL_PORT'])){
		update_config(EMAIL_PORT, $_REQUEST['EMAIL_PORT']);
	}
	/** Update Email Host Security Type in Config **/
	if(!empty($_REQUEST['EMAIL_STMP_SECURE'])){
		update_config(EMAIL_STMP_SECURE, $_REQUEST['EMAIL_STMP_SECURE']);
	}
	/** Update reCAPTCHA public key in Config **/
	if(!empty($_REQUEST['RECAP_PUBLIC_KEY'])){
		update_config(RECAP_PUBLIC_KEY, $_REQUEST['RECAP_PUBLIC_KEY']);
	}
	/** Update reCAPTCHA secret key in Config **/
	if(!empty($_REQUEST['RECAP_PRIVATE_KEY'])){
		update_config(RECAP_PRIVATE_KEY, $_REQUEST['RECAP_PRIVATE_KEY']);
	}

	/** Config File Has been Updated. Refreash Page with success message **/
	SuccessMessages::push('You Have Successfully Updated The Config File! Please Double Check The Data!', '?install_step=2&update_config_file_refresh=true');
}

	/** Check to see if user has updated config
	* If they have just updated the config file
	* Show a please wait animation
	* Then refresh the page back to step
	**/
	if(isset($_GET['update_config_file_refresh'])){
		if($_GET['update_config_file_refresh'] == "true"){
			echo "<div class='well'><center>";
			echo "<img src='uap4logo_wait_animation.gif'>";
			echo "<h3>Please Wait While The Config File Is Updated!</h3></center></div>";
			echo "<meta http-equiv='refresh' content='5; url=/?install_step=2&update_config_file=true'>";
		}
	}else{
		echo "<div class='row'>";
			echo SuccessMessages::display_nolang();
		echo "</div>";
?>

<div class='panel panel-info'>
	<div class='panel-heading'>
		<h3>UAP 4 System Settings</h3>
	</div>
	<div class='panel-body'>
		Now we are going to setup all the settings needed to create your Config.php file.  <br>
		Make sure to fill in all the required fields. <br>
		<hr>
		<form class="form" method="post">
			<!-- Site Settings -->
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3>Site Settings</h3>
				</div>
				<div class='panel-body'>
					<div class="form-group">
						<label for="SITE_TITLE">Website Title</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="SITE_TITLE" id="SITE_TITLE" placeholder="<?=SITE_TITLE?>" value="<?php if(!empty($_REQUEST['SITE_TITLE'])){echo $_REQUEST['SITE_TITLE'];} ?>">
					</div>
					<div class="form-group">
						<label for="SITE_URL">Website URL</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="SITE_URL" id="SITE_URL" placeholder="<?=SITE_URL?>" value="<?php if(!empty($_REQUEST['SITE_URL'])){echo $_REQUEST['SITE_URL'];} ?>">
					</div>
				</div>
			</div>
			<!-- Database Settings -->
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<a name='database'></a>
					<h3>Database Settings</h3>
				</div>
				<div class='panel-body'>
					<div class="form-group">
						<label for="DB_HOST">Host</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="DB_HOST" id="DB_HOST" placeholder="<?=DB_HOST?>" value="<?php if(!empty($_REQUEST['DB_HOST'])){echo $_REQUEST['DB_HOST'];} ?>">
					</div>
					<div class="form-group">
						<label for="DB_NAME">Datebase Name</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="DB_NAME" id="DB_NAME" placeholder="<?=DB_NAME?>" value="<?php if(!empty($_REQUEST['DB_NAME'])){echo $_REQUEST['DB_NAME'];} ?>">
					</div>
					<div class="form-group">
						<label for="DB_USER">Username</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="DB_USER" id="DB_USER" placeholder="<?=DB_USER?>" value="<?php if(!empty($_REQUEST['DB_USER'])){echo $_REQUEST['DB_USER'];} ?>">
					</div>
					<div class="form-group">
						<label for="DB_PASS">Password</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="DB_PASS" id="DB_PASS" placeholder="<?=DB_PASS?>" value="<?php if(!empty($_REQUEST['DB_PASS'])){echo $_REQUEST['DB_PASS'];} ?>">
					</div>
					<div class="form-group">
						<label for="PREFIX">Prefix</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="PREFIX" id="PREFIX" placeholder="<?=PREFIX?>" value="<?php if(!empty($_REQUEST['PREFIX'])){echo $_REQUEST['PREFIX'];} ?>">
					</div>
				</div>
					<?php
						(isset($_GET['test_db'])) ? $test_db = $_GET['test_db'] : $test_db = "" ;
						if($updated_config || $test_db == "true"){
						/** Test Database Settings **/
							echo "<div class='panel-footer'>";
							echo "<b>Database Status:</b> ";
							if($test_db == "true"){
								require ROOTDIR.'app/Install/database_check.php';
								$updated_config = true;
							}else{
								echo "<a href='/?install_step=2&test_db=true#database'>Test Database Settings</a>";
							}
							echo "</div>";
						}
					?>
			</div>
			<!-- Email Settings -->
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3>Site Email Settings</h3>
					<a name='email'></a>
				</div>
				<div class='panel-body'>
					<div class="form-group">
						<label for="EMAIL_USERNAME">Username</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="EMAIL_USERNAME" id="EMAIL_USERNAME" placeholder="<?=EMAIL_USERNAME?>" value="<?php if(!empty($_REQUEST['EMAIL_USERNAME'])){echo $_REQUEST['EMAIL_USERNAME'];} ?>">
					</div>
					<div class="form-group">
						<label for="EMAIL_PASSWORD">Password</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="EMAIL_PASSWORD" id="EMAIL_PASSWORD" placeholder="<?=EMAIL_PASSWORD?>" value="<?php if(!empty($_REQUEST['EMAIL_PASSWORD'])){echo $_REQUEST['EMAIL_PASSWORD'];} ?>">
					</div>
					<div class="form-group">
						<label for="EMAIL_FROM_NAME">From Name</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="EMAIL_FROM_NAME" id="EMAIL_FROM_NAME" placeholder="<?=EMAIL_FROM_NAME?>" value="<?php if(!empty($_REQUEST['EMAIL_FROM_NAME'])){echo $_REQUEST['EMAIL_FROM_NAME'];} ?>">
					</div>
					<div class="form-group">
						<label for="EMAIL_HOST">Host</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="EMAIL_HOST" id="EMAIL_HOST" placeholder="<?=EMAIL_HOST?>" value="<?php if(!empty($_REQUEST['EMAIL_HOST'])){echo $_REQUEST['EMAIL_HOST'];} ?>">
					</div>
					<div class="form-group">
						<label for="EMAIL_PORT">Port</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="EMAIL_PORT" id="EMAIL_PORT" placeholder="<?=EMAIL_PORT?>" value="<?php if(!empty($_REQUEST['EMAIL_PORT'])){echo $_REQUEST['EMAIL_PORT'];} ?>">
					</div>
					<div class="form-group">
						<label for="EMAIL_STMP_SECURE">Security Type</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="EMAIL_STMP_SECURE" id="EMAIL_STMP_SECURE" placeholder="<?=EMAIL_STMP_SECURE?>" value="<?php if(!empty($_REQUEST['EMAIL_STMP_SECURE'])){echo $_REQUEST['EMAIL_STMP_SECURE'];} ?>">
					</div>
				</div>
					<?php
						(isset($_GET['test_email'])) ? $test_email = $_GET['test_email'] : $test_email = "" ;
						if($updated_config || $test_email == "true"){
							echo "<div class='panel-footer'>";
							echo "<b>Email Status:</b>";
							if($test_email == "true"){
								require ROOTDIR.'app/Install/email_check.php';
								$updated_config = true;
							}else{
								echo "<a href='/?install_step=2&test_email=true#email'>Send Test Email to ".EMAIL_USERNAME."</a>";
							}
							echo "</div>";
						}
					?>
			</div>
			<!-- Google reCAPTCHA Settings -->
			<div class='panel panel-default'>
				<div class='panel-heading'>
					<h3>Google reCAPTCHA Settings</h3>
				</div>
				<div class='panel-body'>
					<div class="form-group">
						<label for="RECAP_PUBLIC_KEY">Public Key</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="RECAP_PUBLIC_KEY" id="RECAP_PUBLIC_KEY" placeholder="<?=RECAP_PUBLIC_KEY?>" value="<?php if(!empty($_REQUEST['RECAP_PUBLIC_KEY'])){echo $_REQUEST['RECAP_PUBLIC_KEY'];} ?>">
					</div>
					<div class="form-group">
						<label for="RECAP_PRIVATE_KEY">Private Key</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="RECAP_PRIVATE_KEY" id="RECAP_PRIVATE_KEY" placeholder="<?=RECAP_PRIVATE_KEY?>" value="<?php if(!empty($_REQUEST['RECAP_PRIVATE_KEY'])){echo $_REQUEST['RECAP_PRIVATE_KEY'];} ?>">
					</div>
				</div>
				<div class='panel-footer'>
					<b>reCAPTCHA Test:</b> reCAPTCHA should be display_nolanged below if your keys are correct.
						<div class="g-recaptcha" data-sitekey="<?=RECAP_PUBLIC_KEY?>"></div>
					<script src="https://www.google.com/recaptcha/api.js" async defer></script>

				</div>
			</div>
			<!-- Save to Config Button -->
			<button class="btn btn-primary btn-lg" name="submit" type="submit">Update Config File</button>
		</form>

		<hr>
		<?php
			/** Check to see if config has been updated **/
			if($updated_config){
				echo "<B>Double check the above site settings.  If everything looks good Move on to Step 3.</b><hr>";
				echo "<a href='/?install_step=3' class='btn btn-primary btn-lg'>Move on to Step 3</a>";
			}
		?>


	</div>
</div>
<?php } ?>
