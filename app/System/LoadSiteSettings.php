<?php namespace App\System;

use App\Models\AdminPanel as Model;

class LoadSiteSettings {

    private $model;

    public function __construct() {

        /* Load the Admin Panel Model */
        $this->model = new Model();

        /********************
         *                  *
         *     BASICS       *
         *                  *
         ********************/

        /* Define Site Title */
        define('SITE_TITLE', $this->model->getSettings('site_title'));

        /* Define Site Description */
        define('SITE_DESCRIPTION', $this->model->getSettings('site_description'));

        /* Define Site Keywords */
        define('SITE_KEYWORDS', $this->model->getSettings('site_keywords'));

        /********************
         *                  *
         *      EMAIL       *
         *     uses SMTP    *
         ********************/
         /**
         * SMTP Email Username
         */
         define('EMAIL_USERNAME', $this->model->getSettings('site_email_username'));

         /**
         * SMTP Email Password
         */
         define('EMAIL_PASSWORD', $this->model->getSettings('site_email_password'));

         /**
         * SMTP Email sent from whom? a name
         */
         define('EMAIL_FROM_NAME', $this->model->getSettings('site_email_fromname'));

         /**
         * SMTP Email host
         * Example : Google (smtp.gmail.com), Yahoo (smtp.mail.yahoo.com)
         */
         define('EMAIL_HOST', $this->model->getSettings('site_email_host'));

         /**
         * SMTP Email port
         * default : 25 (https://www.arclab.com/en/kb/email/list-of-smtp-and-pop3-servers-mailserver-list.html)
         */
         define('EMAIL_PORT', $this->model->getSettings('site_email_port'));

         /**
         * SMTP Email authentication
         * default : ssl
         * choices : ssl, tls, (leave it empty)
         */
         define('EMAIL_STMP_SECURE', $this->model->getSettings('site_email_smtp'));

         /**
          * Optionall set a site email address.
          */
         define('SITEEMAIL', $this->model->getSettings('site_email_site'));

        /********************
         *                  *
         *     RECAPTCHA    *
         *                  *
         ********************/
        // reCAPCHA site key provided by google for testing purposes
        define("RECAP_PUBLIC_KEY", $this->model->getSettings('site_recapcha_public'));
        // reCAPCHA secret key provided by google for testing purposes
        define("RECAP_PRIVATE_KEY", $this->model->getSettings('site_recapcha_private'));
        /*****************
         *                *
         *     Account    *
         *                *
         *****************/
        // Account needs email activation, false=no true=yes
        define("ACCOUNT_ACTIVATION", $this->model->getSettings('site_user_activation'));

    }

}
