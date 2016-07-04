<?php
/**
 * Auth Controller
 *
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0.4
 */

namespace App\Controllers;


use Core\Controller,
    Core\View,
    Helpers\Auth\Auth as AuthHelper,
    Helpers\Csrf,
    Helpers\Url,
    Helpers\Request,
    App\Models\Users,
    Helpers\ErrorHelper,
    Helpers\SuccessHelper;


class Auth extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->language->load('Auth');
    }

    /**
     * Log in the user
     */
    public function login()
    {
        if ($this->auth->isLogged())
            Url::redirect();

        if (isset($_POST['submit']) && Csrf::isTokenValid('login')) {
            $username = Request::post('username');
            $password = Request::post('password');
            //$rememberMe = Request::post('rememberMe');
            $rememberMe = null !=  Request::post('rememberMe');

            $email = $this->auth->checkIfEmail($username);
            $username = count($email) != 0 ? $email[0]->username : $username;

            if ($this->auth->login($username, $password, $rememberMe)) {
                $userId = $this->auth->currentSessionInfo()['uid'];

                $info = array('LastLogin' => date('Y-m-d G:i:s'));
                $where = array('userID' => $userId);
                $this->auth->updateUser($info,$where);

                $this->user->update($userId);

                /**
                * Login Success
                * Redirect to user
                * Check to see if user came from another page within the site
                */
                if(isset($_SESSION['login_prev_page'])){ $login_prev_page = $_SESSION['login_prev_page']; }else{ $login_prev_page = ""; }
                /**
                * Checking to see if user user was viewing anything before login
                * If they were viewing a page on this site, then after login
                * send them to that page they were on.
                */
                if(!empty($login_prev_page)){
                  /* Send member to previous page */
                  //var_dump($login_prev_page); // Debug

                  /* Clear the prev page session if set */
                  if(isset($_SESSION['login_prev_page'])){
                    unset($_SESSION['login_prev_page']);
                  }

                  $prev_page = "$login_prev_page";
                  //var_dump($prev_page); // Debug

                  /* Send user back to page they were at before login */
                  /* Success Message Display */
                  SuccessHelper::push($this->language->get('login_success'), $prev_page);
                }else{
                  /* No previous page, send member to home page */
                  //echo " send user to home page "; // Debug

                  /* Clear the prev page session if set */
                  if(isset($_SESSION['login_prev_page'])){
                    unset($_SESSION['login_prev_page']);
                  }

                  /* Redirect member to home page */
                  /* Success Message Display */
                  SuccessHelper::push($this->language->get('login_success'), '');
                }
            }
            else{
                /* Error Message Display */
                ErrorHelper::push($this->language->get('login_incorrect'), 'Login');
            }
        }

        $data['csrfToken'] = Csrf::makeToken('login');
        $data['title'] = $this->language->get('login_page_title');
        $data['welcomeMessage'] = $this->language->get('login_page_welcome_message');

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        /** Check to see if user is logged in **/
        $data['isLoggedIn'] = $this->auth->isLogged();

        View::renderTemplate('header', $data);
        View::render('Members/Login', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Log the user out
     */
    public function logout()
    {
        if ($this->auth->isLogged()) {
            $u_id = $this->auth->currentSessionInfo()['uid'];
            $this->user->remove($u_id);
            $this->auth->logout();
        }
        // Success Message Display
        SuccessHelper::push($this->language->get('logout'), '');
    }

    /**
     * Register an account
     */
    public function register()
    {
        //Redirect user to home page if he is already logged in
        if ($this->auth->isLogged())
            Url::redirect();

        //The form is submmited
        if (isset($_POST['submit'])) {

            //Check the CSRF token first
            if(Csrf::isTokenValid('register')) {
                $captcha_fail = false;

                //Check the reCaptcha if the public and private keys were provided
                if (RECAP_PUBLIC_KEY != "" && RECAP_PRIVATE_KEY != "") {
                    if (isset($_POST['g-recaptcha-response'])) {
                        $gRecaptchaResponse = $_POST['g-recaptcha-response'];

                        $recaptcha = new \ReCaptcha\ReCaptcha(RECAP_PRIVATE_KEY);
                        $resp = $recaptcha->verify($gRecaptchaResponse);
                        if (!$resp->isSuccess())
                            $captcha_fail = true;
                    }
                }

                //Only continue if captcha did not fail
                if (!$captcha_fail) {
                    $username = Request::post('username');
                    $password = Request::post('password');
                    $verifypassword = Request::post('passwordc');
                    $email = Request::post('email');

                    //register with our without email verification
                    $registered = ACCOUNT_ACTIVATION ?
                        $this->auth->register($username, $password, $verifypassword, $email) :
                        $this->auth->directRegister($username, $password, $verifypassword, $email);

                    if ($registered) {
                        $data['message'] = ACCOUNT_ACTIVATION ?
                            $this->language->get('register_success') :
                            $this->language->get('register_success_noact');
                            // Success Message Display
                            SuccessHelper::push($data['message'], 'Register');
                    }
                    else{
                        // Error Message Display
                        ErrorHelper::push($this->language->get('register_error'), 'Register');
                    }
                }
                else{
                    // Error Message Display
                    ErrorHelper::push($this->language->get('register_error_recap'), 'Register');
                }
            }
            else{
                // Error Message Display
                ErrorHelper::push($this->language->get('register_error'), 'Register');
            }
        }

        $data['csrfToken'] = Csrf::makeToken('register');
        $data['title'] = $this->language->get('register_page_title');
        $data['welcomeMessage'] = $this->language->get('register_page_welcome_message');

        /** needed for recaptcha **/
        if (RECAP_PUBLIC_KEY != "" && RECAP_PRIVATE_KEY != "") {
            $data['ownjs'] = array(
              "<script type='text/javascript'>
                  var onloadCallback = function() {
                      grecaptcha.render('html_element', {'sitekey' : '".RECAP_PUBLIC_KEY."'});
                  };
              </script>",
              "<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&amp;render=explicit' async defer></script>",);
        }

        /** Add JS Files requried for live checks **/
    		$data['js'] = "<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>";
    		$data['js'] .= "<script src='".Url::templatePath()."js/live_email.js'></script>";
    		$data['js'] .= "<script src='".Url::templatePath()."js/live_username_check.js'></script>";
    		$data['js'] .= "<script src='".Url::templatePath()."js/password_strength_match.js'></script>";

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        /** Check to see if user is logged in **/
        $data['isLoggedIn'] = $this->auth->isLogged();

        View::renderTemplate('header', $data);
        View::render('Members/Register', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Activate an account
     * @param $username
     * @param $activekey
     */
    public function activate($username,$activekey)
    {
        if ($this->auth->isLogged())
            Url::redirect();

        if($this->auth->activateAccount($username, $activekey)) {
            // Success Message Display
            SuccessHelper::push($this->language->get('activate_success'), 'Login');
        }
        else{
            // Error Message Display
            ErrorHelper::push($this->language->get('activate_fail'), 'Resend-Activation-Email');
        }

        $data['title'] = $this->language->get('activate_title');
        $data['welcomeMessage'] = $this->language->get('activate_welcomemessage');

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Activate', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Change user's password
     */
    public function changePassword()
    {
        if (!$this->auth->isLogged())
          /** User Not logged in - kick them out **/
          ErrorHelper::push($this->language->get('user_not_logged_in'), 'Login');

        if(isset($_POST['submit'])){

            if (Csrf::isTokenValid('changepassword')) {
                $currentPassword = Request::post('currpassword');
                $newPassword = Request::post('password');
                $confirmPassword = Request::post('passwordc');

                // Get Current User's UserName
                $u_username = $this->auth->currentSessionInfo()['username'];

                if($this->auth->changePass($u_username, $currentPassword, $newPassword, $confirmPassword)){
                    // Success Message Display
                    SuccessHelper::push($this->language->get('resetpass_success'), 'Change-Password');
                }
                else{
                    // Error Message Display
                    ErrorHelper::push($this->language->get('resetpass_error'), 'Change-Password');
                }
            }
        }

        $data['csrfToken'] = Csrf::makeToken('changepassword');
        $data['title'] = $this->language->get('changepass_title');
        $data['welcomeMessage'] = $this->language->get('changepass_welcomemessage');

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
          <li><a href='".DIR."Account-Settings'>".$this->language->get('account_settings_title')."</a></li>
    			<li class='active'>".$data['title']."</li>
        ";

        /** Add JS Files requried for live checks **/
    		$data['js'] = "<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>";
    		$data['js'] .= "<script src='".Url::templatePath()."js/password_strength_match.js'></script>";

        View::renderTemplate('header', $data);
        View::render('Members/Member-Account-Sidebar', $data);
        View::render('Members/Change-Password', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Change user's email
     */
    public function changeEmail()
    {
        if (!$this->auth->isLogged())
          /** User Not logged in - kick them out **/
          ErrorHelper::push($this->language->get('user_not_logged_in'), 'Login');

        if(isset($_POST['submit'])){

            if(Csrf::isTokenValid('changeemail')) {
                $password = Request::post('passwordemail');
                $newEmail = Request::post('newemail');
                $username = $this->auth->currentSessionInfo()['username'];

                if($this->auth->changeEmail($username, $newEmail, $password)){
                    // Success Message Display
                    SuccessHelper::push($this->language->get('changeemail_success'), 'Change-Email');
                }
                else{
                    // Error Message Display
                    ErrorHelper::push($this->language->get('changeemail_error'), 'Change-Email');
                }
            }
        }

        $data['csrfToken'] = Csrf::makeToken('changeemail');
        $data['title'] = $this->language->get('changeemail_title');
        $data['welcomeMessage'] = $this->language->get('changeemail_welcomemessage');

        /** Get Current User's userID and Email **/
    		$u_id = $this->auth->user_info();
    		$data['email'] = $this->user->getUserEmail($u_id);

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
          <li><a href='".DIR."Account-Settings'>".$this->language->get('account_settings_title')."</a></li>
    			<li class='active'>".$data['title']."</li>
        ";

        /** Add JS Files requried for live checks **/
    		$data['js'] = "<script src='http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js'></script>";
    		$data['js'] .= "<script src='".Url::templatePath()."js/live_email.js'></script>";

        View::renderTemplate('header', $data);
        View::render('Members/Member-Account-Sidebar', $data);
        View::render('Members/Change-Email', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Forgotten password
     */
    public function forgotPassword()
    {
        if($this->auth->isLogged())
            Url::redirect();

        if(isset($_POST['submit'])){

            if (Csrf::isTokenValid('forgotpassword')) {
                $email = Request::post('email');

                if($this->auth->resetPass($email)){
                    // Success Message Display
                    SuccessHelper::push($this->language->get('resetpass_email_sent'), 'Forgot-Password');
                }else{
                    // Error Message Display
                    ErrorHelper::push($this->language->get('resetpass_email_error'), 'Forgot-Password');
                }
            }
        }

        $data['csrfToken'] = Csrf::makeToken('forgotpassword');
        $data['title'] = $this->language->get('forgotpass_title');
        $data['welcomeMessage'] = $this->language->get('forgotpass_welcomemessage');

        /** Check to see if user is logged in **/
        $data['isLoggedIn'] = $this->auth->isLogged();

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Forgot-Password', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Reset password
     * @param $username
     * @param $resetkey
     */
    public function resetPassword($username,$resetkey)
    {
        if($this->auth->isLogged())
            Url::redirect();

        if($this->auth->checkResetKey($username, $resetkey)){
            if(isset($_POST['submit'])){
                if (Csrf::isTokenValid('resetpassword')) {
                    $password = Request::post('password');
                    $confirm_password = Request::post('confirmPassword');

                    if($this->auth->resetPass('', $username, $resetkey, $password, $confirm_password)){
                        // Success Message Display
                        SuccessHelper::push($this->language->get('resetpass_success'), 'Login');
                    }
                    else{
                        // Error Message Display
                        ErrorHelper::push($this->language->get('resetpass_error'), 'Forgot-Password');
                    }
                }
            }
        }
        else{
            $data['message'] = "Some Error Occurred";
            // Error Message Display
            ErrorHelper::push($data['message'], 'Forgot-Password');
        }

        $data['csrfToken'] = Csrf::makeToken('resetpassword');
        $data['title'] = $this->language->get('resetpass_title');
        $data['welcomeMessage'] = $this->language->get('resetpass_welcomemessage');

        /** Check to see if user is logged in **/
        $data['isLoggedIn'] = $this->auth->isLogged();

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Reset-Password', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Resend activation for email
     */
    public function resendActivation()
    {
        if ($this->auth->isLogged())
            Url::redirect();

        if (isset($_POST['submit']) && Csrf::isTokenValid('resendactivation')) {
            $email = Request::post('email');

            if($this->auth->resendActivation($email)){
                // Success Message Display
                SuccessHelper::push($this->language->get('resendactivation_success'), 'Login');
            }
            else{
                // Error Message Display
                ErrorHelper::push($this->language->get('resendactivation_error'), 'Resend-Activation-Email');
            }
        }

        $data['csrfToken'] = Csrf::makeToken('resendactivation');
        $data['title'] = $this->language->get('resendactivation_title');
        $data['welcomeMessage'] = $this->language->get('resendactivation_welcomemessage');

        /** Check to see if user is logged in **/
        $data['isLoggedIn'] = $this->auth->isLogged();

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Resend-Activation', $data);
        View::renderTemplate('footer', $data);
    }
}
