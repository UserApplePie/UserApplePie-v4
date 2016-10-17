<?php
/** UAP Install Step 3 **/
/** Create Database **/


?>

<div class='panel panel-info'>
	<div class='panel-heading'>
		<h3>Create Database</h3>
	</div>
	<div class='panel-body'>
		Now we are going to Import data to the Database.  <br>
		<br>
		<b>Database Name:</b> <?=DB_NAME?><Br>
		<b>Database Prefix:</b> <?=PREFIX?>
		<hr>

		<?php
			/** Check to see if user is importing data **/
			if(isset($_GET['import_database'])){
				if($_GET['import_database'] == "true"){
					/** Include the Import DB File **/
					require ROOTDIR.'app/Install/database_import.php';
				}
			}else{
				echo "<a href='/?install_step=3&import_database=true' class='btn btn-primary btn-lg'>Import Data to Database</a><br>";
			}

			/** Database Import Success, Show button to move on **/
			if(isset($database_import)){
				echo "<hr>";
				echo "<a href='/?install_step=4' class='btn btn-primary btn-lg'>Move on to Step 4</a>";
			}else{
				/** Check for database error **/
				if(isset($database_error)){
					echo "<hr><div class='alert alert-danger'>There was an error importing data to database.  Check your database and make sure ".DB_NAME." database exist and is empty.</div>";
					echo "<a href='/?install_step=3&import_database=true' class='btn btn-warning btn-lg'>Retry Import Data to Database</a><br>";
				}
			}
		?>

	</div>
</div>
