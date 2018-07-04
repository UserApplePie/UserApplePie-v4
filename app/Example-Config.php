<?php
/**
* Main Config File
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

namespace App;

class Config {

  public function __construct() {
    /* Enable output buffering */
    ob_start();

    /********************
     *                  *
     *     BASICS       *
     *                  *
     ********************/
     /* Define Folder Location */
     define('DIR', '/');

    /* Define Site Url Address */
    define('SITE_URL', 'https://uap4demo.userapplepie.com/');

    /* Define Controller */
    define('DEFAULT_CONTROLLER', 'Home');

    /* Default Method */
    define('DEFAULT_METHOD', 'Home');

    /* Default Template */
    define('DEFAULT_TEMPLATE', 'Default');

    /* Default Language Code */
    define('LANGUAGE_CODE', 'En');

    /* Default Session Prefix */
    define('SESSION_PREFIX', 'uap4_');

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
    define('DB_NAME', 'db_name');
    /**
     * Database username.
     */
    define('DB_USER', 'db_username');
    /**
     * Database password.
     */
    define('DB_PASS', 'db_password');
    /**
     * PREFIX to be used in database calls default is uap4_
     */
    define('PREFIX', 'uap4_');


    /*****************
     *                *
     *     Account    *
     *                *
     *****************/

    // Account activation route
    define("ACTIVATION_ROUTE", 'Activate');
    // Account password reset route
    define("RESET_PASSWORD_ROUTE", 'ResetPassword');
    // this is the same as SECURITY_DURATION but in number format
    $waittime = preg_replace("/[^0-9]/", "", SECURITY_DURATION); //DO NOT MODIFY
    define('WAIT_TIME', $waittime); //DO NOT MODIFY
    //INT cost of BCRYPT algorithm
    define("COST", 10);
    //INT hash length of BCRYPT algorithm
    define("HASH_LENGTH", 22);
    /**
     * Image Settings
     */
    // User's Profile Image Directory
    define('IMG_DIR_PROFILE', 'assets/images/profile-pics/');
    // Forum Topic Replies Image Directory
    define('IMG_DIR_FORUM_TOPIC', 'assets/images/forum-pics/topics/');
    // Forum Topic Replies Image Directory
    define('IMG_DIR_FORUM_REPLY', 'assets/images/forum-pics/replies/');

    /**
     * Turn on custom error handling.
     */
    set_exception_handler('App\System\ErrorLogger::ExceptionHandler');
    set_error_handler('App\System\ErrorLogger::ErrorHandler');

    $GLOBALS["instances"] = array();

  }

}

/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
//////////////////UAP////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////
/////////////////////////////////////////////////////