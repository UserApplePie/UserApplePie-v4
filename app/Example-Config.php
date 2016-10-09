<?php namespace App;

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
    define('SITE_URL', 'http://localhost/');

    /* Define Site Title */
    define('SITE_TITLE', 'UAP4');

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
    define('DB_NAME', 'uap4_demo');
    /**
     * Database username.
     */
    define('DB_USER', 'uap4_demo');
    /**
     * Database password.
     */
    define('DB_PASS', 'dbpass');
    /**
     * PREFER to be used in database calls default is uap3_
     */
    define('PREFIX', 'uap4_');

    /********************
     *                  *
     *      EMAIL       *
     *     uses SMTP    *
     ********************/
     /**
     * SMTP Email Username
     */
     define('EMAIL_USERNAME', 'noreply@userapplepie.com');

     /**
     * SMTP Email Password
     */
     define('EMAIL_PASSWORD', 'emailpass');

     /**
     * SMTP Email sent from whom? a name
     */
     define('EMAIL_FROM_NAME','UAP NoReply');

     /**
     * SMTP Email host
     * Example : Google (smtp.gmail.com), Yahoo (smtp.mail.yahoo.com)
     */
     define('EMAIL_HOST','localhost');

     /**
     * SMTP Email port
     * default : 25 (https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html)
     */
     define('EMAIL_PORT', '25');

     /**
     * SMTP Email authentication
     * default : ssl
     * choices : ssl, tls, (leave it empty)
     */
     define('EMAIL_STMP_SECURE','');

     /**
      * Optionall set a site email address.
      */
     define('SITEEMAIL', 'noreply@userapplepie.com');

    /********************
     *                  *
     *     RECAPTCHA    *
     *                  *
     ********************/
    // reCAPCHA site key provided by google for testing purposes
    define("RECAP_PUBLIC_KEY", '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
    // reCAPCHA secret key provided by google for testing purposes
    define("RECAP_PRIVATE_KEY", '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
    /*****************
     *                *
     *     Account    *
     *                *
     *****************/
    // Account needs email activation, false=no true=yes
    define("ACCOUNT_ACTIVATION",false);
    // Max attempts for login before user is locked out
    define("MAX_ATTEMPTS", 5);
    // How long a user is locked out after they reach the max attempts
    define("SECURITY_DURATION", "+5 minutes");
    // Account activation route
    define("ACTIVATION_ROUTE", 'Activate');
    // Account password reset route
    define("RESET_PASSWORD_ROUTE", 'ResetPassword');
    //How long a session lasts : Default = +1 day
    define("SESSION_DURATION", "+1 day");
    //How long a REMEMBER ME SESSION lasts : Default = +1 month
    define("SESSION_DURATION_RM", "+1 month");
    //INT cost of BCRYPT algorithm
    define("COST", 10);
    //INT hash length of BCRYPT algorithm
    define("HASH_LENGTH", 22);
    // min length of username
    define('MIN_USERNAME_LENGTH', 5);
    // max length of username
    define('MAX_USERNAME_LENGTH', 30);
    // min length of password
    define('MIN_PASSWORD_LENGTH', 5);
    // max length of password
    define('MAX_PASSWORD_LENGTH', 30);
    //max length of email
    define('MAX_EMAIL_LENGTH', 100);
    // min length of email
    define('MIN_EMAIL_LENGTH', 5);
    //random key used for password reset or account activation
    define('RANDOM_KEY_LENGTH', 15);
    $waittime = preg_replace("/[^0-9]/", "", SECURITY_DURATION); //DO NOT MODIFY
    // this is the same as SECURITY_DURATION but in number format
    define('WAIT_TIME', $waittime); //DO NOT MODIFY

    /* Set timezone */
    date_default_timezone_set('America/Chicago');

    $GLOBALS["instances"] = array();

  }

}
