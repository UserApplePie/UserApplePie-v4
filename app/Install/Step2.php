<?php
/**
* Install Script Step 2
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/


// TODO
// Firgure out a better way to run the tests/or have a refreash of some sort.

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
	/** Update Site URL in Config **/
	if(!empty($_REQUEST['SITE_URL'])){
		$site_url = rtrim($_REQUEST['SITE_URL'], '/') . '/';
		update_config(SITE_URL, $site_url);
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
			echo "<div class='card border-primary bg-light mb-3 text-center'><card class='card-body'>";
			echo "<img src='uap4logo_wait_animation.gif'>";
			echo "<h3>Please Wait While The Config File Is Updated!</h3></card></div>";
			echo "<meta http-equiv='refresh' content='5; url=/?install_step=2&update_config_file=true'>";
		}
	}else{
		echo "<div class='row'>";
			echo SuccessMessages::display_nolang();
		echo "</div>";
?>

<div class='card border-info mb-3'>
	<div class='card-header h4'>
		<h3>UAP 4 System Settings</h3>
	</div>
	<div class='card-body'>
		Now we are going to setup all the settings needed to create your Config.php file.  <br>
		Make sure to fill in all the required fields. <br>
		<hr>
		<form class="form" method="post">
			<!-- Site Settings -->
			<div class='card mb-3'>
				<div class='card-header h4'>
					<h3>Site Settings</h3>
				</div>
				<div class='card-body'>
					<div class="form-group">
						<label for="SITE_URL">Website URL</label><span class="label label-danger pull-right">Required</span>
						<input type="text" class="form-control" name="SITE_URL" id="SITE_URL" placeholder="<?=SITE_URL?>" value="<?php if(!empty($_REQUEST['SITE_URL'])){echo $_REQUEST['SITE_URL'];} ?>">
					</div>
				</div>
			</div>
			<!-- Database Settings -->
			<div class='card mb-3'>
				<div class='card-header h4'>
					<a name='database'></a>
					<h3>Database Settings</h3>
				</div>
				<div class='card-body'>
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
						<input type="password" class="form-control" name="DB_PASS" id="DB_PASS" placeholder="<?=DB_PASS?>" value="<?php if(!empty($_REQUEST['DB_PASS'])){echo $_REQUEST['DB_PASS'];} ?>">
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
							echo "<div class='card-footer text-muted'>";
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
