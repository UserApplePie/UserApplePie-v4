<?php
/**
 * Config - an example for setting up system settings.
 * When you are done editing, rename this file to 'Config.php'.
 *
 * Nova Framework
 * @author David Carr - dave@daveismyname.com
 * @version 3.0
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0.3
 */

namespace App;

use Helpers\Session;

/**
 * Configuration constants and options.
 */
class Config
{
    /**
     * Executed as soon as the framework runs.
     */
    public function __construct()
    {
        /**
         * Turn on output buffering.
         */
        ob_start();

        /**
         * Optional create a constant for the name of the site.
         */
        define('SITETITLE', 'UAP V3.0');

        /**
         * Define the complete site URL.
         */
        define('SITEURL', 'http://www.userapplepie.com/');

        /**
         * Define relative base path.
         */
        define('DIR', '/');

        /**
         * Set the Application Router.
         */
        // Default Routing
        define('APPROUTER', '\Core\Router');
        // Classic Routing
        // define('APPROUTER', '\Core\ClassicRouter');

        /**
         * Set default controller and method for legacy calls.
         */
        define('DEFAULT_CONTROLLER', 'Welcome');
        define('DEFAULT_METHOD', 'index');

        /**
         * Set the default template.
         */
        define('TEMPLATE', 'Default');

        /**
         * Set a default language.
         */
        define('LANGUAGE_CODE', 'En');

        /**
         * Set prefix for sessions.
         */
        define('SESSION_PREFIX', 'uap3_');

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

/********************
 *                  *
 *      EMAIL       *
 *     uses SMTP    *
 ********************/

        /**
        * SMTP Email Username
        */
        define('EMAIL_USERNAME', 'username@gmail.com');

        /**
        * SMTP Email Password
        */
        define('EMAIL_PASSWORD', 'email_pass');

        /**
        * SMTP Email sent from whom? a name
        */
        define('EMAIL_FROM_NAME','UAP NoReply');

        /**
        * SMTP Email host
        * Example : Google (smtp.gmail.com), Yahoo (smtp.mail.yahoo.com)
        */
        define('EMAIL_HOST','smtp.gmail.com');

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
        define('EMAIL_STMP_SECURE','ssl');

        /**
         * Optionall set a site email address.
         */
        define('SITEEMAIL', 'username@gmail.com');

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

/*****************
 *                *
 * Other Settings *
 *                *
 *****************/

        /**
         * Turn on custom error handling.
         */
        set_exception_handler('Core\Logger::ExceptionHandler');
        set_error_handler('Core\Logger::ErrorHandler');

        /**
         * Set timezone.
         */
        date_default_timezone_set('America/Chicago');

        /**
         * Start sessions.
         */
        Session::init();
    }
}
//////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////UserApplePie/////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////