<?php
/** UAP Install Step 1 **/
/** System Check **/

/** PHP Version Check 5.5 or Greater **/
if (version_compare(phpversion(), '5.5.0', '<')) {
	/** PHP Version too old **/
	$php_version = phpversion();
	$php_display = "<font color=red><b>Out of Date!</b></font>";
	$php_status = false;
} else {
	/** PHP Version Good **/
	$php_version = phpversion();
	$php_display = "<font color=green><b>Good To Go!</b></font>";
	$php_status = true;
}

/** mySQL Server Check **/
/** Get mySQL version **/
function getMySQLVersion() {
	$output = shell_exec('mysql -V');
	preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
	return $version[0];
}
if (!function_exists('mysqli_connect')) {
	/** mySQL Bad **/
	$mysql_version = getMySQLVersion();
	$mysql_display = "<font color=red><b>No Server Found!</b></font>";
	$mysql_status = false;
} else {
	/** mySQL Good **/
	$mysql_version = getMySQLVersion();
	$mysql_display = "<font color=green><b>Good To Go!</b></font>";
	$mysql_status = true;
}

/** URL Rewrite Module Check **/
$mrw_isEnabled = in_array('mod_rewrite', apache_get_modules());
if (!$mrw_isEnabled) {
	/** URL Rewrite Module Bad **/
	$mrw_version = "N/A";
	$mrw_display = "<font color=red><b>Not Enabled!</b></font>";
	$mrw_status = false;
} else {
	/** URL Rewrite Module Good **/
	$mrw_version = "N/A";
	$mrw_display = "<font color=green><b>Good To Go!</b></font>";
	$mrw_status = true;
}

/** Fileinfo Extension Check **/
$fi_isEnabled = !extension_loaded('php_fileinfo');
if (!$fi_isEnabled) {
	/** Fileinfo Extension Bad **/
	$fi_version = "N/A";
	$fi_display = "<font color=red><b>Not Enabled!</b></font>";
	$fi_status = false;
} else {
	/** Fileinfo Extension Good **/
	$fi_version = "N/A";
	$fi_display = "<font color=green><b>Good To Go!</b></font>";
	$fi_status = true;
}

/** Get Current Install Directory **/
$my_folder = dirname( realpath( __FILE__ ) ) . DIRECTORY_SEPARATOR;
$my_folder = preg_replace( '~[/\\\\][^/\\\\]*[/\\\\]$~' , DIRECTORY_SEPARATOR , $my_folder );
$my_folder = str_replace("app/", "", $my_folder);

/** Folder Writeable Check **/
function folder_writable($folder){
	$folder = ROOTDIR.''.$folder.'';
	if (is_writable($folder)) {
		echo "<font color=green><strong>Writeable!</strong></font>";
	} else {
		echo "<font color=red><strong>Not Writeable!</strong></font>";
	}
}

/** Check if all requirements are good **/
$requirements_good = true;

?>

<div class='panel panel-info'>
	<div class='panel-heading'>
		<h3>UAP 3 Installation</h3>
	</div>
	<div class='panel-body'>
		Welcome to the UAP 3 Installation Process.  <br>
		First we are going to check and make sure you have everything needed to install. <br>
		<br>

		<div class="panel panel-default">
		  <!-- Default panel contents -->
		  <div class="panel-heading">UserApplePie requires the following to be installed and working before installation can continue.</div>
		  <!-- Table -->
		  <table class="table">
		    <th> Requirement </th><th> Version </th><th> Status </th>
				<tr><td> PHP 5.5 or Greater </td><td> <?=$php_version?> </td><td> <?=$php_display?> </td></tr>
				<tr><td> mySQL Database Server </td><td> <?=$mysql_version?> </td><td> <?=$mysql_display?> </td></tr>
				<tr><td> URL Rewrite Module </td><td> <?=$mrw_version?> </td><td> <?=$mrw_display?> </td></tr>
				<tr><td> PHP Fileinfo Enabled </td><td> <?=$fi_version?> </td><td> <?=$fi_display?> </td></tr>
		  </table>
		</div>

		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">UserApplePie requires the following files/folders to have write permission by server.</div>
			<!-- Table -->
			<table class="table">
				<th> Folder </th><th> Status </th>
				<tr><td> ../app/Config.example.php </td><td> <?=folder_writable('app/Config.example.php')?> </td></tr>
				<tr><td> ../app/Logs/error.log </td><td> <?=folder_writable('app/Logs/error.log')?> </td></tr>
				<tr><td> ../assets/images/ </td><td> <?=folder_writable('assets/images/')?> </td></tr>
			</table>
		</div>

		<div class="panel panel-default">
			<!-- Default panel contents -->
			<div class="panel-heading">Other Server Data</div>
			<!-- Table -->
			<table class="table">
				<th> Information </th><th> More Information </th>
				<tr><td> Install Location </td><td> <?=$my_folder?> </td></tr>
			</table>
		</div>

		<?php
			if($requirements_good){
				echo "<a href='/?install_step=2' class='btn btn-primary btn-lg'>Move on to Step 2</a>";
			}else{
				echo "Make sure there is no <font color=red><b>RED</b></font> text before you can move on to Step 2!";
			}
		?>

	</div>
</div>
