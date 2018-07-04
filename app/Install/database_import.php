<?php
/**
 * Imports Table(s) Data to Database
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.2.0
 */


/** Test The Database Settings and Make sure it works **/
try {
  $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "<font color=green>Database Connected Successfully!</font>";
  $database_working = true;
}
catch(PDOException $e){
  echo "<font color=red>Database Connection failed:</font> " . $e->getMessage();
  echo "<a href='/?install_step=2' class='btn btn-danger btn-lg'>Go Back to Step 2 and Fix Database Settings</a>";
}

if(isset($database_working)){
  /** Database Settings are working. Now Import Database Data **/

  // Reconnect to Database for Import
  $link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
  if (!$link) {
  echo "Error: Unable to connect to MySQL." . PHP_EOL;
  echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
  echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
  exit;
  }

  // Location of SQL file
  $sqlfile = ROOTDIR.'database.sql';

  // Temporary variable, used to store current query
  $templine = '';
  // Read in entire file
  $lines = file($sqlfile);
  // Loop through each line
  foreach ($lines as $line)
  {
    // Skip it if it's a comment
    if (substr($line, 0, 2) == '--' || $line == '')
        continue;

    // Add this line to the current segment
    $templine .= $line;

    // If it has a semicolon at the end, it's the end of the query
    if (substr(trim($line), -1, 1) == ';')
    {
        // Change the prefix if not default
        $set_prefix = PREFIX;
        $default_prefix = "uap4_";
        if($set_prefix != $default_prefix){
          $templine = str_replace($default_prefix, $set_prefix, $templine);
        }
        // Perform the query
        if(!mysqli_query($link,$templine)){
          print('<hr><font color=red><b>Error performing query</b></font> \'' . $templine . '\': ' . mysqli_connect_error() . ' ');
          $errors[] = "true";
        }
        // Reset temp variable to empty
        $templine = '';

    }
  }
}
if(!isset($errors)){
  echo "<hr><font color=green><b>Database Imported Successfully!</b></font>";
  $database_import = true;
}else{
	$database_error = true;
}
