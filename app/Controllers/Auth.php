<?php

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
        $this->language->load('Welcome');
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

                  $prev_page = SITEURL."$login_prev_page";
                  //var_dump($prev_page); // Debug

                  /* Send user back to page they were at before login */
                  /* Success Message Display */
                  SuccessHelper::push('You Have Successfully Logged In', $prev_page);
                }else{
                  /* No previous page, send member to home page */
                  //echo " send user to home page "; // Debug

                  /* Clear the prev page session if set */
                  if(isset($_SESSION['login_prev_page'])){
                    unset($_SESSION['login_prev_page']);
                  }

                  /* Redirect member to home page */
                  /* Success Message Display */
                  SuccessHelper::push('You Have Successfully Logged In', '');
                }
            }
            else{
                /* Error Message Display */
                ErrorHelper::push('Incorrect username and password combination', 'Login');
            }
        }

        $data['csrfToken'] = Csrf::makeToken('login');
        $data['title'] = 'Login to Account';

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
        SuccessHelper::push('You Have Successfully Logged Out', '');
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
                            "Registration Successful! Check Your Email For Activating your Account." :
                            "Registration Successful! Click <a href='".DIR."login'>Login</a> to login.";
                            // Success Message Display
                            SuccessHelper::push($data['message'], 'Register');
                    }
                    else{
                        $data['message'] = "Registration Error: Please try again";
                        // Error Message Display
                        ErrorHelper::push($data['message'], 'Register');
                    }
                }
                else{
                    $data['message'] = "Stop being a spambot";
                    // Error Message Display
                    ErrorHelper::push($data['message'], 'Register');
                }
            }
            else{
                $data['message'] = "Stop trying to hack!";
                // Error Message Display
                ErrorHelper::push($data['message'], 'Register');
            }
        }

        $data['csrfToken'] = Csrf::makeToken('register');
        $data['title'] = 'Register for an Account';

        /** needed for recaptcha **/
        if (RECAP_PUBLIC_KEY != "" && RECAP_PRIVATE_KEY != "") {
            $data['ownjs'] = array(
                "<script src='https://www.google.com/recaptcha/api.js?onload=onloadCallback&amp;render=explicit' async defer></script>",
                "<script type='text/javascript'>
                    var onloadCallback = function() {
                        grecaptcha.render('html_element', {'sitekey' : '".RECAP_PUBLIC_KEY."'});
                    };
                </script>");
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
            $data['message'] = "Your Account Has Been Activated!  You may <a href='" . DIR . "Login'>Login</a> now.";
            // Success Message Display
            SuccessHelper::push($data['message'], 'Login');
        }
        else{
            $data['message'] = "Account Activation <strong>Failed</strong>! Try again by <a href='".DIR."Resend-Activation-Email'>requesting a new activation key</a>";
            // Error Message Display
            ErrorHelper::push($data['message'], 'Resend-Activation-Email');
        }

        $data['title'] = 'Account Activation';

        /** Check to see if user is logged in **/
        $data['isLoggedIn'] = $this->auth->isLogged();

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Activate', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Account settings
     */
    public function settings()
    {
        if (!$this->auth->isLogged())
          /** User Not logged in - kick them out **/
          \Helpers\ErrorHelper::push('You are Not Logged In', 'Login');
    }

    /**
     * Change user's password
     */
    public function changePassword()
    {
        if (!$this->auth->isLogged())
          /** User Not logged in - kick them out **/
          \Helpers\ErrorHelper::push('You are Not Logged In', 'Login');

        if(isset($_POST['submit'])){

            if (Csrf::isTokenValid('changepassword')) {
                $currentPassword = Request::post('currpassword');
                $newPassword = Request::post('password');
                $confirmPassword = Request::post('passwordc');

                // Get Current User's UserName
                $u_username = $this->auth->currentSessionInfo()['username'];

                if($this->auth->changePass($u_username, $currentPassword, $newPassword, $confirmPassword)){
                    $data['message'] = "Your password has been changed.";
                    // Success Message Display
                    SuccessHelper::push($data['message'], 'Change-Password');
                }
                else{
                    $data['message'] = "An error occurred while changing your password.";
                    // Error Message Display
                    ErrorHelper::push($data['message'], 'Change-Password');
                }
            }
        }

        $data['csrfToken'] = Csrf::makeToken('changepassword');
        $data['title'] = 'Change Password';

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
          <li><a href='".DIR."Account-Settings'>Account Settings</a></li>
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
          \Helpers\ErrorHelper::push('You are Not Logged In', 'Login');

        if(isset($_POST['submit'])){

            if(Csrf::isTokenValid('changeemail')) {
                $password = Request::post('passwordemail');
                $newEmail = Request::post('newemail');
                $username = $this->auth->currentSessionInfo()['username'];

                if($this->auth->changeEmail($username, $newEmail, $password)){
                    $data['message'] = "Your email has been changed to {$newEmail}.";
                    // Success Message Display
                    SuccessHelper::push($data['message'], 'Change-Email');
                }
                else{
                    $data['message'] = "An error occurred while changing your email.";
                    // Error Message Display
                    ErrorHelper::push($data['message'], 'Change-Email');
                }
            }
        }

        $data['csrfToken'] = Csrf::makeToken('changeemail');
        $data['title'] = 'Change Email';

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
          <li><a href='".DIR."Account-Settings'>Account Settings</a></li>
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
                    $data['message'] = "A link has been sent to your email to reset your password";
                    // Success Message Display
                    SuccessHelper::push($data['message'], 'Forgot-Password');
                }else{
                    $data['message'] = "No email is affiliated with any accounts on this website.";
                    // Error Message Display
                    ErrorHelper::push($data['message'], 'Forgot-Password');
                }
            }
        }

        $data['title'] = "Forgot Password";
        $data['csrfToken'] = Csrf::makeToken('forgotpassword');

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
                        $data['message'] = "Your password has been changed, make sure to keep your password somewhere safe.";
                        // Success Message Display
                        SuccessHelper::push($data['message'], 'Login');
                    }
                    else{
                        $data['message'] = "Some error occurred, please try again.";
                        // Error Message Display
                        ErrorHelper::push($data['message'], 'Forgot-Password');
                    }
                }
            }
        }
        else{
            $data['message'] = "Some Error Occurred";
            // Error Message Display
            ErrorHelper::push($data['message'], 'Forgot-Password');
        }

        $data['title'] = "Reset Password";
        $data['csrfToken'] = Csrf::makeToken('resetpassword');

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
                $data['message'] = "An activation code has been sent to your email";
                // Success Message Display
                SuccessHelper::push($data['message'], 'Resend-Activation-Email');
            }
            else{
                $data['message'] = "No account is affiliated with the {$email} or it may have already been activated.";
                // Error Message Display
                ErrorHelper::push($data['message'], 'Resend-Activation-Email');
            }
        }

        $data['csrfToken'] = Csrf::makeToken('resendactivation');
        $data['title'] = 'Resend Activation Email';

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
