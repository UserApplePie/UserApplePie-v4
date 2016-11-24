<?php
/**
 * Checks mySQL database settings.
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.0.0
 */

try {
  $conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
  // set the PDO error mode to exception
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  echo "<font color=green>Connected Successfully!</font>";
  $updated_config = true;
}
catch(PDOException $e){
  echo "<font color=red>Connection failed:</font> " . $e->getMessage();
}
