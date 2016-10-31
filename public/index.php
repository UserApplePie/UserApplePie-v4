<?php
/*
** UserApplePie v4
** http://www.userapplepie.com
*/

/* Define the absolute paths for configured directories */
define('APPDIR', realpath(__DIR__.'/../app/').'/');
define('SYSTEMDIR', realpath(__DIR__.'/../system/').'/');
define('PUBLICDIR', realpath(__DIR__).'/');
define('ROOTDIR', realpath(__DIR__.'/../').'/');

/** Define Current Version of UAP **/
define('UAPVersion', 'v4.0.0');

/* load Composer Autoloader */
if (file_exists(ROOTDIR.'vendor/autoload.php')) {
    require ROOTDIR.'vendor/autoload.php';
} else {
    echo "<h1>Please install via composer.json</h1>";
    echo "<p>Install Composer instructions: <a href='https://getcomposer.org/doc/00-intro.md#globally'>https://getcomposer.org/doc/00-intro.md#globally</a></p>";
    echo "<p>Once composer is installed navigate to the working directory in your terminal/command promt and enter 'composer install'</p>";
    exit;
}

/* Start the Sesstion */
session_start();

/* Error Settings */
error_reporting(E_ALL);
ini_set("display_errors", 1);

/* Make sure Config File Exists */
if (is_readable(APPDIR.'Config.php')) {

  /* Load the Site Config */
  new \App\Config();

  /* Load Site Settings From Database */
  new \App\System\LoadSiteSettings();

  /* Load the Page Router */
  new \App\System\Router();

} else {
    /** No Config Setup, Start Install */
    if (file_exists(APPDIR.'Install/Install.php')) {
        require APPDIR.'Install/Install.php';
    } else {
        echo "<h1>Update and Rename Example-Config.php to Config.php</h1>";
        echo "<p>Make sure to rename <code>/app/Example-Config.php</code> to <code>/app/Config.php</code> for this application to start working.</p>";
        echo "<p>Read the README for details.</p>";
    }
}
