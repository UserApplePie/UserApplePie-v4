<?php namespace App;

class Config {

  public function __construct() {
    /* Enable output buffering */
    ob_start();

    /* Define Site Url Address */
    define('SITE_URL', 'http://localhost/');

    /* Define Site Title */
    define('SITE_TITLE', 'UAP4');

    /* Define Controller */
    define('DEFAULT_CONTROLLER', 'Home');

    /* Default Method */
    define('DEFAULT_METHOD', 'Home');

    /* Default Template */
    define('DEFAULT_TEMPLATE', 'Default');



    /********************
     *                  *
     *     DATABASE     *
     *                  *
     ********************/
    /**
     * Database engine default is mysql.
     */
    define('DB_TYPE', 'mysql');
    /**
     * Database host default is localhost.
     */
    define('DB_HOST', 'localhost');
    /**
     * Database name.
     */
    define('DB_NAME', 'db_dbname');
    /**
     * Database username.
     */
    define('DB_USER', 'db_username');
    /**
     * Database password.
     */
    define('DB_PASS', 'db_password');
    /**
     * PREFER to be used in database calls default is uap3_
     */
    define('PREFIX', 'uap3_');


    /* Set timezone */
    date_default_timezone_set('America/Chicago');

    $GLOBALS["instances"] = array();

  }

}
