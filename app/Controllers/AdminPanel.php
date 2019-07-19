<?php
/**
 * Admin Panel Controller
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
 */

namespace App\Controllers;

use App\System\Controller,
    App\System\Load,
    Libs\Auth\Auth,
    Libs\Csrf,
    Libs\Request,
    App\Models\AdminPanel as AdminPanelModel,
    App\System\Error,
    App\Models\Members as MembersModel,
    App\Update\Update,
    App\Routes;

class AdminPanel extends Controller{

  private $model;
  private $pages;

  /**
   * Call the parent construct
   */
  public function __construct(){
    parent::__construct();
    $this->model = new AdminPanelModel();
    $this->pages = new \Libs\Paginator(USERS_PAGEINATOR_LIMIT);  // How many rows per page
  }

  /**
  * Admin Panel Dashboard
  * Displays site information to Admin
  */
  public function Dashboard(){
    // Get data for dashboard
    $data['current_page'] = $_SERVER['REQUEST_URI'];
    $data['title'] = "Dashboard";
    $data['welcomeMessage'] = "Welcom to the Admin Panel Dashboard!";

    /** Get Data For Member Totals Stats Sidebar */
    $onlineUsers = new MembersModel();
    $data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
    $data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

    /** Get Count Data For Groups */
    $data['usergroups'] = count($this->model->getAllGroups());

    /** Get Count of Members that Have Logged In Past Days */
    $data['mem_login_past_1'] = count($this->model->getPastUsersData('LastLogin', '1'));
    $data['mem_login_past_7'] = count($this->model->getPastUsersData('LastLogin', '7'));
    $data['mem_login_past_30'] = count($this->model->getPastUsersData('LastLogin', '30'));
    $data['mem_login_past_90'] = count($this->model->getPastUsersData('LastLogin', '90'));
    $data['mem_login_past_365'] = count($this->model->getPastUsersData('LastLogin', '365'));

    /** Get Count of Members that Have Signed Up In Past Days */
    $data['mem_signup_past_1'] = count($this->model->getPastUsersData('SignUp', '1'));
    $data['mem_signup_past_7'] = count($this->model->getPastUsersData('SignUp', '7'));
    $data['mem_signup_past_30'] = count($this->model->getPastUsersData('SignUp', '30'));
    $data['mem_signup_past_90'] = count($this->model->getPastUsersData('SignUp', '90'));
    $data['mem_signup_past_365'] = count($this->model->getPastUsersData('SignUp', '365'));

    /** Get total page views count */
    $data['totalPageViews'] = \Libs\SiteStats::getTotalViews();

    /** Get Top Referers */
    $data['topRefer'] = $this->model->getTopRefer('30');
    $data['topReferYear'] = $this->model->getTopRefer('365');

    /** Function to check if the files exist (prevent errors when mother server is down) */
    function UR_exists($url){
      $headers=get_headers($url);
      return stripos($headers[0],"200 OK")?true:false;
    }

    /** Get Current UAP Version Data From UserApplePie.com */
    $check_url = 'https://www.userapplepie.com/uapversion.php?getversion=UAP';
    if(UR_exists($check_url)){
      $html = file_get_contents($check_url);
      preg_match("/UAP v(.*) UAP/i", $html, $match);
      $cur_uap_version = UAPVersion;
      if($cur_uap_version < $match[1]){ $data['cur_uap_version'] = $match[1]; }
    }

    /** Check to see if Forum Plugin is Installed  */
    if(file_exists(ROOTDIR.'app/Plugins/Forum/Controllers/Forum.php')){
      $forum_status = "Installed";
      /** Get Current UAP Version Data From UserApplePie.com */
      $check_url = 'https://www.userapplepie.com/uapversion.php?getversion=Forum';
      if(UR_exists($check_url)){
        $html = file_get_contents($check_url);
        preg_match("/UAP-Forum v(.*) UAP-Forum/i", $html, $match);
        require_once(ROOTDIR.'app/Plugins/Forum/ForumVersion.php');
        $cur_uap_forum_version = UAPForumVersion;
        if($cur_uap_forum_version < $match[1]){ $data['cur_uap_forum_version'] = $match[1]; }
      }
    }else{
      $forum_status = "NOT Installed";
    }
    $data['apd_plugin_forum'] = $forum_status;

    /** Check to see if Private Messages Plugin is Installed */
    if(file_exists(ROOTDIR.'app/Plugins/Messages/Controllers/Messages.php')){
      $msg_status = "Installed";
      /** Get Current UAP Version Data From UserApplePie.com */
      $check_url = 'https://www.userapplepie.com/uapversion.php?getversion=Messages';
      if(UR_exists($check_url)){
        $html = file_get_contents($check_url);
        preg_match("/UAP-Messages v(.*) UAP-Messages/i", $html, $match);
        require_once(ROOTDIR.'app/Plugins/Messages/MessagesVersion.php');
        $cur_uap_messages_version = UAPMessagesVersion;
        if($cur_uap_messages_version < $match[1]){ $data['cur_uap_messages_version'] = $match[1]; }
      }
    }else{
      $msg_status = "NOT Installed";
    }
    $data['apd_plugin_message'] = $msg_status;

    /** Check to see if Friends Plugin is Installed */
    if(file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php')){
      $friends_status = "Installed";
      /** Get Current UAP Version Data From UserApplePie.com */
      $check_url = 'https://www.userapplepie.com/uapversion.php?getversion=Friends';
      if(UR_exists($check_url)){
        $html = file_get_contents($check_url);
        preg_match("/UAP-Friends v(.*) UAP-Friends/i", $html, $match);
        require_once(ROOTDIR.'app/Plugins/Friends/FriendsVersion.php');
        $cur_uap_friends_version = UAPFriendsVersion;
        if($cur_uap_friends_version < $match[1]){ $data['cur_uap_friends_version'] = $match[1]; }
      }
    }else{
      $friends_status = "NOT Installed";
    }
    $data['apd_plugin_friends'] = $friends_status;

    /** Check to see if Comments Plugin is Installed */
    if(file_exists(ROOTDIR.'app/Plugins/Comments/Controllers/Comments.php')){
      $comments_status = "Installed";
      /** Get Current UAP Version Data From UserApplePie.com */
      $check_url = 'https://www.userapplepie.com/uapversion.php?getversion=Comments';
      if(UR_exists($check_url)){
        $html = file_get_contents($check_url);
        preg_match("/UAP-Comments v(.*) UAP-Comments/i", $html, $match);
        require_once(ROOTDIR.'app/Plugins/Comments/CommentsVersion.php');
        $cur_uap_comments_version = UAPCommentsVersion;
        if($cur_uap_comments_version < $match[1]){ $data['cur_uap_comments_version'] = $match[1]; }
      }
    }else{
      $comments_status = "NOT Installed";
    }
    $data['apd_plugin_comments'] = $comments_status;

    /** Check to see if UAP Files are Newer than Database Version */
    $data['uap_files_version'] = UAPVersion;
    $data['uap_database_version'] = $this->model->getDatabaseVersion();
    if(empty($data['uap_database_version'])){ $data['uap_database_version'] = "4.2.1"; }

    // Setup Breadcrumbs
    $data['breadcrumbs'] = "
      <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li class='breadcrumb-item active'><i class='fas fa-fw fa-tachometer-alt'></i> ".$data['title']."</li>
    ";

    /** Check to see if user is logged in */
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      /** User is logged in - Get their data */
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
        /** User Not Admin - kick them out */
        \Libs\ErrorMessages::push('You are Not Admin', '');
      }
    }else{
      /** User Not logged in - kick them out */
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    /** Push data to the view */
    Load::View("AdminPanel/AdminPanel", $data, "", "AdminPanel");
  }

    /**
    * Admin Panel Site Settings
    * Allows admins to change all site settings except database
    */
    public function Settings(){
        /** Get data for dashboard */
        $data['current_page'] = $_SERVER['REQUEST_URI'];
        $data['title'] = "Main Settings";
        $data['welcomeMessage'] = "Welcome to the Admin Panel Site Main Settings!";

        /** Check to see if user is logged in */
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            /** User is logged in - Get their data */
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
                /** User Not Admin - kick them out */
                \Libs\ErrorMessages::push('You are Not Admin', '');
            }
        }else{
            /** User Not logged in - kick them out */
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }

        /** Check to see if Admin is submiting form data */
        if(isset($_POST['submit'])){
          /** Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            /** Check to make sure the csrf token is good */
            if (Csrf::isTokenValid('settings')) {
                /** Check to make sure Admin is updating settings */
                if($_POST['update_settings'] == "true"){
                    /** Get data sbmitted by form */
                    $site_title = Request::post('site_title');
                    $site_description = Request::post('site_description');
                    $site_keywords = Request::post('site_keywords');
                    $site_recapcha_public = Request::post('site_recapcha_public');
                    $site_recapcha_private = Request::post('site_recapcha_private');
                    $site_theme = Request::post('site_theme');
                    $site_message = Request::post('site_message');
                    if(!$this->model->updateSetting('site_title', $site_title)){ $errors[] = 'Site Title Error'; }
                    if(!$this->model->updateSetting('site_description', $site_description)){ $errors[] = 'Site Description Error'; }
                    if(!$this->model->updateSetting('site_keywords', $site_keywords)){ $errors[] = 'Site Keywords Error'; }
                    if(!$this->model->updateSetting('site_recapcha_public', $site_recapcha_public)){ $errors[] = 'Site reCAPCHA Public Error'; }
                    if(!$this->model->updateSetting('site_recapcha_private', $site_recapcha_private)){ $errors[] = 'Site reCAPCHA Private Error'; }
                    if(!$this->model->updateSetting('site_theme', $site_theme)){ $errors[] = 'Site Theme Error'; }
                    if(!$this->model->updateSetting('site_message', $site_message)){ $errors[] = 'Site Wide Message Error'; }

                    // Run the update settings script
                    if(!isset($errors) || count($errors) == 0){
                        /** Success */
                        \Libs\SuccessMessages::push('You Have Successfully Updated Site Settings', 'AdminPanel-Settings');
                    }else{
                        // Error
                        if(isset($errors)){
                            $error_data = "<hr>";
                            foreach($errors as $row){
                                $error_data .= " - ".$row."<br>";
                            }
                        }else{
                            $error_data = "";
                        }
                        /** Error Message Display */
                        \Libs\ErrorMessages::push('Error Updating Site Settings'.$error_data, 'AdminPanel-Settings');
                    }
                }else{
                    /** Error Message Display */
                    \Libs\ErrorMessages::push('Error Updating Site Settings', 'AdminPanel-Settings');
                }
            }else{
                /** Error Message Display */
                \Libs\ErrorMessages::push('Error Updating Site Settings', 'AdminPanel-Settings');
            }
          }else{
          	/** Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - Settings Disabled', 'AdminPanel-Settings');
          }
        }

        /** Get Settings Data */
        $data['site_title'] = $this->model->getSettings('site_title');
        $data['site_description'] = $this->model->getSettings('site_description');
        $data['site_keywords'] = $this->model->getSettings('site_keywords');
        $data['site_recapcha_public'] = $this->model->getSettings('site_recapcha_public');
        $data['site_recapcha_private'] = $this->model->getSettings('site_recapcha_private');
        $data['site_theme'] = $this->model->getSettings('site_theme');
        $data['site_message'] = $this->model->getSettings('site_message');


        /** Setup Token for Form */
        $data['csrfToken'] = Csrf::makeToken('settings');

        /** Setup Breadcrumbs */
        $data['breadcrumbs'] = "
          <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
          <li class='breadcrumb-item active'><i class='fa fa-fw fa-cog'></i> ".$data['title']."</li>
        ";

        /** Push data to the view */
        Load::View("AdminPanel/Settings", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Site Advanced Settings
    * Allows admins to change all site settings except database
    */
    public function AdvancedSettings(){
        /** Get data for dashboard */
        $data['current_page'] = $_SERVER['REQUEST_URI'];
        $data['title'] = "Advanced Settings";
        $data['welcomeMessage'] = "Welcome to the Admin Panel Site Advanced Settings!";

        /** Check to see if user is logged in */
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            /** User is logged in - Get their data */
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
                /** User Not Admin - kick them out */
                \Libs\ErrorMessages::push('You are Not Admin', '');
            }
        }else{
            /** User Not logged in - kick them out */
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }

        /** Check to see if Admin is submiting form data */
        if(isset($_POST['submit'])){
          /** Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            /** Check to make sure the csrf token is good */
            if (Csrf::isTokenValid('settings')) {
                /** Check to make sure Admin is updating settings */
                if($_POST['update_advanced_settings'] == "true"){

                    /** Get data sbmitted by form */
                    $site_user_activation = Request::post('site_user_activation');
                    if($site_user_activation != 'true'){ $site_user_activation = 'false'; }
                    $site_user_invite_code = Request::post('site_user_invite_code');
                    $max_attempts = Request::post('max_attempts');
                    $security_duration = Request::post('security_duration');
                    $session_duration = Request::post('session_duration');
                    $session_duration_rm = Request::post('session_duration_rm');
                    $min_username_length = Request::post('min_username_length');
                    $max_username_length = Request::post('max_username_length');
                    $min_password_length = Request::post('min_password_length');
                    $max_password_length = Request::post('max_password_length');
                    $min_email_length = Request::post('min_email_length');
                    $max_email_length = Request::post('max_email_length');
                    $random_key_length = Request::post('random_key_length');
                    $default_timezone = Request::post('default_timezone');
                    $users_pageinator_limit = Request::post('users_pageinator_limit');
                    $friends_pageinator_limit = Request::post('friends_pageinator_limit');
                    $message_quota_limit = Request::post('message_quota_limit');
                    $message_pageinator_limit = Request::post('message_pageinator_limit');
                    $sweet_title_display = Request::post('sweet_title_display');
                    $sweet_button_display = Request::post('sweet_button_display');
                    $image_max_size = Request::post('image_max_size');
                    $online_bubble = Request::post('online_bubble');
                    if($online_bubble != 'true'){ $online_bubble = 'false'; }

                    if(!$this->model->updateSetting('site_user_activation', $site_user_activation)){ $errors[] = 'Site User Activation Error'; }
                    if(!$this->model->updateSetting('site_user_invite_code', $site_user_invite_code)){ $errors[] = 'site_user_invite_code Error'; }
                    if(!$this->model->updateSetting('max_attempts', $max_attempts)){ $errors[] = 'max_attempts Error'; }
                    if(!$this->model->updateSetting('security_duration', $security_duration)){ $errors[] = 'security_duration Error'; }
                    if(!$this->model->updateSetting('session_duration', $session_duration)){ $errors[] = 'session_duration Error'; }
                    if(!$this->model->updateSetting('session_duration_rm', $session_duration_rm)){ $errors[] = 'session_duration_rm Error'; }
                    if(!$this->model->updateSetting('min_username_length', $min_username_length)){ $errors[] = 'min_username_length Error'; }
                    if(!$this->model->updateSetting('max_username_length', $max_username_length)){ $errors[] = 'max_username_length Error'; }
                    if(!$this->model->updateSetting('min_password_length', $min_password_length)){ $errors[] = 'min_password_length Error'; }
                    if(!$this->model->updateSetting('max_password_length', $max_password_length)){ $errors[] = 'max_password_length Error'; }
                    if(!$this->model->updateSetting('min_email_length', $min_email_length)){ $errors[] = 'min_email_length Error'; }
                    if(!$this->model->updateSetting('max_email_length', $max_email_length)){ $errors[] = 'max_email_length Error'; }
                    if(!$this->model->updateSetting('random_key_length', $random_key_length)){ $errors[] = 'random_key_length Error'; }
                    if(!$this->model->updateSetting('default_timezone', $default_timezone)){ $errors[] = 'default_timezone Error'; }
                    if(!$this->model->updateSetting('users_pageinator_limit', $users_pageinator_limit)){ $errors[] = 'users_pageinator_limit Error'; }
                    if(!$this->model->updateSetting('friends_pageinator_limit', $friends_pageinator_limit)){ $errors[] = 'friends_pageinator_limit Error'; }
                    if(!$this->model->updateSetting('message_quota_limit', $message_quota_limit)){ $errors[] = 'message_quota_limit Error'; }
                    if(!$this->model->updateSetting('message_pageinator_limit', $message_pageinator_limit)){ $errors[] = 'message_pageinator_limit Error'; }
                    if(!$this->model->updateSetting('sweet_title_display', $sweet_title_display)){ $errors[] = 'sweet_title_display Error'; }
                    if(!$this->model->updateSetting('sweet_button_display', $sweet_button_display)){ $errors[] = 'sweet_button_display Error'; }
                    if(!$this->model->updateSetting('image_max_size', $image_max_size)){ $errors[] = 'image_max_size Error'; }
                    if(!$this->model->updateSetting('online_bubble', $online_bubble)){ $errors[] = 'online_bubble Error'; }

                    // Run the update settings script
                    if(!isset($errors) || count($errors) == 0){
                        /** Success */
                        \Libs\SuccessMessages::push('You Have Successfully Updated Site Advanced Settings', 'AdminPanel-AdvancedSettings');
                    }else{
                        // Error
                        if(isset($errors)){
                            $error_data = "<hr>";
                            foreach($errors as $row){
                                $error_data .= " - ".$row."<br>";
                            }
                        }else{
                            $error_data = "";
                        }
                        /** Error Message Display */
                        \Libs\ErrorMessages::push('Error Updating Site Advanced Settings'.$error_data, 'AdminPanel-AdvancedSettings');
                    }
                }else{
                    /** Error Message Display */
                    \Libs\ErrorMessages::push('Error Updating Site Advanced Settings', 'AdminPanel-AdvancedSettings');
                }
            }else{
                /** Error Message Display */
                \Libs\ErrorMessages::push('Error Updating Site Advanced Settings', 'AdminPanel-AdvancedSettings');
            }
          }else{
          	/** Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - Advanced Settings Disabled', 'AdminPanel-AdvancedSettings');
          }
        }

        /** Get Advanced Settings Data */
        $data['site_user_activation'] = $this->model->getSettings('site_user_activation');
        $data['site_user_invite_code'] = $this->model->getSettings('site_user_invite_code');
        $data['max_attempts'] = $this->model->getSettings('max_attempts');
        $data['security_duration'] = $this->model->getSettings('security_duration');
        $data['session_duration'] = $this->model->getSettings('session_duration');
        $data['session_duration_rm'] = $this->model->getSettings('session_duration_rm');
        $data['min_username_length'] = $this->model->getSettings('min_username_length');
        $data['max_username_length'] = $this->model->getSettings('max_username_length');
        $data['min_password_length'] = $this->model->getSettings('min_password_length');
        $data['max_password_length'] = $this->model->getSettings('max_password_length');
        $data['min_email_length'] = $this->model->getSettings('min_email_length');
        $data['max_email_length'] = $this->model->getSettings('max_email_length');
        $data['random_key_length'] = $this->model->getSettings('random_key_length');
        $data['default_timezone'] = $this->model->getSettings('default_timezone');
        $data['users_pageinator_limit'] = $this->model->getSettings('users_pageinator_limit');
        $data['friends_pageinator_limit'] = $this->model->getSettings('friends_pageinator_limit');
        $data['message_quota_limit'] = $this->model->getSettings('message_quota_limit');
        $data['message_pageinator_limit'] = $this->model->getSettings('message_pageinator_limit');
        $data['sweet_title_display'] = $this->model->getSettings('sweet_title_display');
        $data['sweet_button_display'] = $this->model->getSettings('sweet_button_display');
        $data['image_max_size'] = $this->model->getSettings('image_max_size');
        $data['online_bubble'] = $this->model->getSettings('online_bubble');

        /** Setup Token for Form */
        $data['csrfToken'] = Csrf::makeToken('settings');

        /** Setup Breadcrumbs */
        $data['breadcrumbs'] = "
          <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
          <li class='breadcrumb-item active'><i class='fa fa-fw fa-cog'></i> ".$data['title']."</li>
        ";

        /** Push data to the view */
        Load::View("AdminPanel/AdvancedSettings", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Site Email Settings
    * Allows admins to change all site email settings except database
    */
    public function EmailSettings(){
        /** Get data for dashboard */
        $data['current_page'] = $_SERVER['REQUEST_URI'];
        $data['title'] = "Site E-Mail Settings";
        $data['welcomeMessage'] = "Welcome to the Admin Panel Site E-Mail Settings!";

        /** Check to see if user is logged in */
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            /** User is logged in - Get their data */
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
                /** User Not Admin - kick them out */
                \Libs\ErrorMessages::push('You are Not Admin', '');
            }
        }else{
            /** User Not logged in - kick them out */
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }

        /** Check to see if Admin is submiting form data */
        if(isset($_POST['submit'])){
          /** Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            /** Check to make sure the csrf token is good */
            if (Csrf::isTokenValid('settings')) {
                /** Check to make sure Admin is updating settings */
                if($_POST['update_settings'] == "true"){
                    /** Get data sbmitted by form */
                    $site_email_username = Request::post('site_email_username');
                    $site_email_password = Request::post('site_email_password');
                    $site_email_fromname = Request::post('site_email_fromname');
                    $site_email_host = Request::post('site_email_host');
                    $site_email_port = Request::post('site_email_port');
                    $site_email_smtp = Request::post('site_email_smtp');
                    $site_email_site = Request::post('site_email_site');

                    if(!$this->model->updateSetting('site_email_username', $site_email_username)){ $errors[] = 'Site Email Username Error'; }
                    if(!$this->model->updateSetting('site_email_password', $site_email_password)){ $errors[] = 'Site Email Password Error'; }
                    if(!$this->model->updateSetting('site_email_fromname', $site_email_fromname)){ $errors[] = 'Site Email From Name Error'; }
                    if(!$this->model->updateSetting('site_email_host', $site_email_host)){ $errors[] = 'Site Email Host Error'; }
                    if(!$this->model->updateSetting('site_email_port', $site_email_port)){ $errors[] = 'Site Email Port Error'; }
                    if(!$this->model->updateSetting('site_email_smtp', $site_email_smtp)){ $errors[] = 'Site Email SMTP Auth Error'; }
                    if(!$this->model->updateSetting('site_email_site', $site_email_site)){ $errors[] = 'Site Email Error'; }

                    // Run the update settings script
                    if(!isset($errors) || count($errors) == 0){
                        /** Success */
                        \Libs\SuccessMessages::push('You Have Successfully Updated Site E-Mail Settings', 'AdminPanel-EmailSettings');
                    }else{
                        // Error
                        if(isset($errors)){
                            $error_data = "<hr>";
                            foreach($errors as $row){
                                $error_data .= " - ".$row."<br>";
                            }
                        }else{
                            $error_data = "";
                        }
                        /** Error Message Display */
                        \Libs\ErrorMessages::push('Error Updating Site E-Mail Settings'.$error_data, 'AdminPanel-EmailSettings');
                    }
                }else{
                    /** Error Message Display */
                    \Libs\ErrorMessages::push('Error Updating Site E-Mail Settings', 'AdminPanel-EmailSettings');
                }
            }else{
                /** Error Message Display */
                \Libs\ErrorMessages::push('Error Updating Site E-Mail Settings', 'AdminPanel-EmailSettings');
            }
          }else{
          	/** Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - E-Mail Settings Disabled', 'AdminPanel-EmailSettings');
          }
        }

        /** Get Settings Data */
        $data['site_email_username'] = $this->model->getSettings('site_email_username');
        $data['site_email_password'] = $this->model->getSettings('site_email_password');
        $data['site_email_fromname'] = $this->model->getSettings('site_email_fromname');
        $data['site_email_host'] = $this->model->getSettings('site_email_host');
        $data['site_email_port'] = $this->model->getSettings('site_email_port');
        $data['site_email_smtp'] = $this->model->getSettings('site_email_smtp');
        $data['site_email_site'] = $this->model->getSettings('site_email_site');

        /** Setup Token for Form */
        $data['csrfToken'] = Csrf::makeToken('settings');

        /** Setup Breadcrumbs */
        $data['breadcrumbs'] = "
          <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
          <li class='breadcrumb-item active'><i class='fa fa-fw fa-envelope'></i> ".$data['title']."</li>
        ";

        /** Push data to the view */
        Load::View("AdminPanel/EmailSettings", $data, "", "AdminPanel");
    }


    /**
    * Admin Panel Users
    * Allows admins to view and edit users
    * @param $set_order_by
    * @param $current_page
    * @param $url_search_users
    */
    public function Users($set_order_by = 'ID-ASC', $current_page = '1', $url_search_users = ''){
      // Check for orderby selection
      $data['orderby'] = $set_order_by;
      /** Get data from search */
      if(!empty($url_search_users)){
        $data['search_users_data'] = $url_search_users;
      }else if(isset($_POST['submit'])){
        /** Check to make sure the csrf token is good */
        if (Csrf::isTokenValid('user')) {
          /** Check to see if user is trying to search */
          if($_POST['search_users'] == "true"){
            /** Get data from $_POST */
            $data['search_users_data'] = Request::post('search_users_data');
          }
        }
      }
      // Set total number of rows for paginator
      $total_num_users = $this->model->getTotalUsers($data['search_users_data']);
      $this->pages->setTotal($total_num_users);
      // Send page links to view
      if(!empty($data['search_users_data'])){ $link_search_users = "/".$data['search_users_data']; }
      $pageFormat = SITE_URL."AdminPanel-Users/$set_order_by/"; // URL page where pages are
      $data['pageLinks'] = $this->pages->pageLinks($pageFormat, $link_search_users, $current_page);
      $data['current_page_num'] = $current_page;
      // Get data for users
      $data['current_page'] = $_SERVER['REQUEST_URI'];
      $data['title'] = "Users";
      $data['welcomeMessage'] = "Welcome to the Users Admin Panel";
      $data['users_list'] = $this->model->getUsers($data['orderby'], $this->pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT), $data['search_users_data']);
      $data['csrfToken'] = Csrf::makeToken('user');
      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-users'></i> ".$data['title']."</li>
      ";
      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }
      /** Push data to the view **/
      Load::View("AdminPanel/Users", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel User
    * Allows admins to change user data
    * @param $id
    */
    public function User($id){
      // Check for orderby selection
      $data['orderby'] = Request::post('orderby');
      // Get data for users
      $data['current_page'] = $_SERVER['REQUEST_URI'];
      $data['title'] = "User";
      $data['welcomeMessage'] = "Welcome to the User Admin Panel";
      $data['csrfToken'] = Csrf::makeToken('user');
      // Get user groups data
      $data_groups = $this->model->getAllGroups();
      // Get groups user is and is not member of
      foreach ($data_groups as $value) {
        $data_user_groups = $this->model->checkUserGroup($id, $value->groupID);
        if($data_user_groups){
          $group_member[] = $value->groupID;
        }else{
          $group_not_member[] = $value->groupID;
        }
      }
      // Gether group data for group user is member of
      if(isset($group_member)){
        foreach ($group_member as $value) {
          $group_member_data[] = $this->model->getGroupData($value);
        }
      }
      // Push group data to view
      $data['user_member_groups'] = $group_member_data;
      // Gether group data for group user is not member of
      if(isset($group_not_member)){
        foreach ($group_not_member as $value) {
          $group_notmember_data[] = $this->model->getGroupData($value);
        }
      }
      // Push group data to view
      $data['user_notmember_groups'] = $group_notmember_data;
      // Check to make sure admin is trying to update user profile
  		if(isset($_POST['submit'])){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
    			// Check to make sure the csrf token is good
    			if (Csrf::isTokenValid('user')) {
            if($_POST['update_profile'] == "true"){
              // Catch password inputs using the Request helper
              $au_id = Request::post('au_id');
              $au_username = Request::post('au_username');
              $au_email = Request::post('au_email');
              $au_firstName = Request::post('au_firstName');
              $au_lastName = Request::post('au_lastName');
              $au_gender = Request::post('au_gender');
              $au_website = Request::post('au_website');
              $au_userImage = Request::post('au_userImage');
              $au_aboutme = Request::post('au_aboutme');
              $au_signature = Request::post('au_signature');

              // Run the update profile script
              if($this->model->updateProfile($au_id, $au_username, $au_firstName, $au_lastName, $au_email, $au_gender, $au_website, $au_userImage, $au_aboutme, $au_signature)){
                  /** Success */
                  \Libs\SuccessMessages::push('You Have Successfully Updated User Profile', 'AdminPanel-User/'.$au_id);
              }else{
                  /** User Update Fail. Show Error */
                  \Libs\ErrorMessages::push('Profile Update Failed!', 'AdminPanel-User/'.$au_id);
              }
            }

            // Check to see if admin is removing user from group
            if($_POST['remove_group'] == "true"){
                // Get data from post
                $au_userID = Request::post('au_userID');
                $au_groupID = Request::post('au_groupID');
                // Check to make sure Admin is not trying to remove user's last group
                if($this->model->checkUserGroupsCount($au_userID)){
                    // Updates current user's group
                    if($this->model->removeFromGroup($au_userID, $au_groupID)){
                    	/** Success */
                        \Libs\SuccessMessages::push('You Have Successfully Removed User From Group', 'AdminPanel-User/'.$au_userID);
                    }else{
                        /** User Update Fail. Show Error */
                        \Libs\ErrorMessages::push('Remove From Group Failed!', 'AdminPanel-User/'.$au_userID);
                    }
                }else{
                    /** User Update Fail. Show Error */
                    \Libs\ErrorMessages::push('User Must Be a Member of at least ONE Group!', 'AdminPanel-User/'.$au_userID);
                }
            }

            // Check to see if admin is adding user to group
            if($_POST['add_group'] == "true"){
              // Get data from post
              $au_userID = Request::post('au_userID');
              $au_groupID = Request::post('au_groupID');
              // Updates current user's group
      				if($this->model->addToGroup($au_userID, $au_groupID)){
      					/** Success */
                \Libs\SuccessMessages::push('You Have Successfully Added User to Group', 'AdminPanel-User/'.$au_userID);
      				}else{
                        /** User Update Fail. Show Error */
                        \Libs\ErrorMessages::push('Add to Group Failed!', 'AdminPanel-User/'.$au_id);
      				}
            }

            // Check to see if admin wants to activate user
            if($_POST['activate_user'] == "true"){
              $au_id = Request::post('au_id');
              // Run the Activation script
      				if($this->model->activateUser($au_id)){
      					/** Success */
                \Libs\SuccessMessages::push('You Have Successfully Activated User', 'AdminPanel-User/'.$au_id);
      				}else{
                        /** User Update Fail. Show Error */
                        \Libs\ErrorMessages::push('Activate User Failed!', 'AdminPanel-User/'.$au_id);
      				}
            }

            // Check to see if admin wants to deactivate user
            if($_POST['deactivate_user'] == "true"){
              $au_id = Request::post('au_id');
              // Run the Activation script
      				if($this->model->deactivateUser($au_id)){
      					/** Success */
                \Libs\SuccessMessages::push('You Have Successfully Deactivated User', 'AdminPanel-User/'.$au_id);
      				}else{
                        /** User Update Fail. Show Error */
                        \Libs\ErrorMessages::push('Deactivate User Failed!', 'AdminPanel-User/'.$au_id);
      				}
            }
          }
        }else{
        	/** Error Message Display */
        	\Libs\ErrorMessages::push('Demo Limit - User Settings Disabled', 'AdminPanel-Users');
        }
  		}

      // Setup Current User data
  		// Get user data from user's database
  		$data['user_data'] = $this->model->getUser($id);

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Users'><i class='fa fa-fw fa-users'></i> Users </a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-user'></i>User - ".$data['user_data'][0]->username."</li>
      ";

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }
      /** Push data to the view **/
      Load::View("AdminPanel/User", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Groups
    * Allows admins to edit groups
    */
    public function Groups(){

      // Check for orderby selection
      $data['orderby'] = Request::post('orderby');

      // Get data for users
      $data['current_page'] = $_SERVER['REQUEST_URI'];
      $data['title'] = "Groups";
      $data['welcomeMessage'] = "Welcome to the Groups Admin Panel";
      $data['groups_list'] = $this->model->getGroups($data['orderby']);
      $data['csrfToken'] = Csrf::makeToken('groups');

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-users-cog'></i> ".$data['title']."</li>
      ";

      // Check to make sure admin is trying to create group
  		if(isset($_POST['submit'])){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
    			// Check to make sure the csrf token is good
    			if (Csrf::isTokenValid('groups')) {
            //Check for create group
            if($_POST['create_group'] == "true"){
              // Catch password inputs using the Request helper
              $ag_groupName = Request::post('ag_groupName');
              if(!empty($ag_groupName)){
                // Run the update group script
                $new_group_id = $this->model->createGroup($ag_groupName);
                if($new_group_id){
                  /** Group Create Success */
                  \Libs\SuccessMessages::push('You Have Successfully Created a New Group', 'AdminPanel-Group/'.$new_group_id);
                }else{
                  /** Group Create Error. Show Error */
                  \Libs\ErrorMessages::push('Group Creation Error!', 'AdminPanel-Groups');
                }
              }else{
                /** Group Name Field Empty. Show Error */
                \Libs\ErrorMessages::push('Group Creation Error: Group Name Field Empty!', 'AdminPanel-Groups');
              }
            }
          }
        }else{
        	/** Error Message Display */
        	\Libs\ErrorMessages::push('Demo Limit - User Group Settings Disabled', 'AdminPanel-Groups');
        }
      }

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }
      /** Push data to the view **/
      Load::View("AdminPanel/Groups", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Group Edit Page
    * Allows admins to edit a group
    * @param $id
    */
    public function Group($id){

      // Check for orderby selection
      $data['orderby'] = Request::post('orderby');

      // Get data for users
      $data['current_page'] = $_SERVER['REQUEST_URI'];
      $data['title'] = "Group";
      $data['welcomeMessage'] = "Welcome to the Group Admin Panel";
      $data['csrfToken'] = Csrf::makeToken('group');

      // Get user groups data
      $data_groups = $this->model->getAllGroups();
      // Get groups user is and is not member of
      foreach ($data_groups as $value) {
        $data_user_groups = $this->model->checkUserGroup($id, $value->groupID);
        if($data_user_groups){
          $group_member[] = $value->groupID;
        }else{
          $group_not_member[] = $value->groupID;
        }
      }
      // Gether group data for group user is member of
      if(isset($group_member)){
        foreach ($group_member as $value) {
          $group_member_data[] = $this->model->getGroupData($value);
        }
      }
      // Push group data to view
      $data['user_member_groups'] = $group_member_data;
      // Gether group data for group user is not member of
      if(isset($group_not_member)){
        foreach ($group_not_member as $value) {
          $group_notmember_data[] = $this->model->getGroupData($value);
        }
      }
      // Push group data to view
      $data['user_notmember_groups'] = $group_notmember_data;

      // Check to make sure admin is trying to update group data
  		if(isset($_POST['submit'])){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
    			// Check to make sure the csrf token is good
    			if (Csrf::isTokenValid('group')) {
            // Check for update group
            if($_POST['update_group'] == "true"){
      				// Catch password inputs using the Request helper
              $ag_groupID = Request::post('ag_groupID');
              $ag_groupName = Request::post('ag_groupName');
              $ag_groupDescription = Request::post('ag_groupDescription');
      				$ag_groupFontColor = Request::post('ag_groupFontColor');
      				$ag_groupFontWeight = Request::post('ag_groupFontWeight');

      				// Run the update group script
      				if($this->model->updateGroup($ag_groupID, $ag_groupName, $ag_groupDescription, $ag_groupFontColor, $ag_groupFontWeight)){
      					/** Success */
                \Libs\SuccessMessages::push('You Have Successfully Updated a Group', 'AdminPanel-Group/'.$ag_groupID);
      				}else{
      					/** Error */
      					$error[] = "Group Update Failed";
      				}
            }
            //Check for delete group
            if($_POST['delete_group'] == "true"){
              // Catch password inputs using the Request helper
              $ag_groupID = Request::post('ag_groupID');

              // Run the update group script
              if($this->model->deleteGroup($ag_groupID)){
                /** Success */
                \Libs\SuccessMessages::push('You Have Successfully Deleted a Group', 'AdminPanel-Groups');
              }else{
                /** Error */
                $error[] = "Group Delete Failed";
              }
            }
          }
        }else{
        	/** Error Message Display */
        	\Libs\ErrorMessages::push('Demo Limit - User Group Settings Disabled', 'AdminPanel-Groups');
        }
  		}

      // Setup Current User data
  		// Get user data from user's database
  		$current_group_data = $this->model->getGroup($id);
  		foreach($current_group_data as $group_data){
        $data['g_groupID'] = $group_data->groupID;
  			$data['g_groupName'] = $group_data->groupName;
  			$data['g_groupDescription'] = $group_data->groupDescription;
  			$data['g_groupFontColor'] = $group_data->groupFontColor;
  			$data['g_groupFontWeight'] = $group_data->groupFontWeight;
  		}

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Groups'><i class='fa fa-fw fa-users-cog'></i> Groups </a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-users'></i> Group - ".$data['g_groupName']."</li>
      ";

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }
      /** Push data to the view **/
      Load::View("AdminPanel/Group", $data, "", "AdminPanel");
    }

    /**
    * Mass Email Function
    * Allows Admin to Send an Email to All Members
    */
    public function MassEmail(){

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }

      /** Setup Title and Welcome Message */
      $data['title'] = "Mass E-mail";
      $data['welcomeMessage'] = "Welcome to the Mass E-mail Admin Feature.  This feature will send an email to All site members that have not disabled the feature.";
      $data['current_page'] = $_SERVER['REQUEST_URI'];
      $data['get_users_massemail_allow'] = $this->model->getUsersMassEmail();
      $data['csrfToken'] = Csrf::makeToken('massemail');

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-mail-bulk'></i> ".$data['title']."</li>
      ";

      (isset($_SESSION['subject'])) ? $data['subject'] = $_SESSION['subject'] : $data['subject'] = "";
      unset($_SESSION['subject']);
      (isset($_SESSION['content'])) ? $data['content'] = $_SESSION['content'] : $data['content'] = "";
      unset($_SESSION['content']);

      // Check to make sure admin is trying to create group
  		if(isset($_POST['submit'])){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
    			// Check to make sure the csrf token is good
    			if (Csrf::isTokenValid('massemail')) {
            // Catch password inputs using the Request helper
            $subject = Request::post('subject');
            $content = Request::post('content');
            if(empty($subject)){ $errormsg[] = "Subject Field Blank!"; }
            if(empty($content)){ $errormsg[] = "Content Field Blank!"; }
            if(!isset($errormsg)){
              // Run the mass email script
              foreach ($data['get_users_massemail_allow'] as $row) {
                if($this->model->sendMassEmail($row->userID, $u_id, $subject, $content, $row->username, $row->email)){
                  $count = $count + 1;
                }
              }
              if($count > 0){
                /** Success */
                \Libs\SuccessMessages::push('You Have Successfully Sent Mass Email to '.$count.' Users', 'AdminPanel-MassEmail');
              }else{
                /** Fail */
                \Libs\ErrorMessages::push('Mass Email Error', 'AdminPanel-MassEmail');
              }
            }else{
              $me_errors = "<hr>";
              foreach ($errormsg as $row) {
                $me_errors .= $row."<Br>";
              }
              /** Fail */
              $_SESSION['subject'] = $subject;
              $_SESSION['content'] = $content;
              \Libs\ErrorMessages::push('Mass Email Error'.$me_errors, 'AdminPanel-MassEmail');
            }
          }
        }else{
        	/** Error Message Display */
        	\Libs\ErrorMessages::push('Demo Limit - Mass Email Settings Disabled', 'AdminPanel-MassEmail');
        }
      }
      /** Push data to the view **/
      Load::View("AdminPanel/MassEmail", $data, "", "AdminPanel");
    }

    /**
    * System Routes Function
    * Allows Admin Quickly Find and Add new routes
    * Searches for all Classes and Methods within
    * Controller folders
    */
    public function SystemRoutes(){

        /** Check to see if user is logged in */
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            /** User is logged in - Get their data */
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
                /** User Not Admin - kick them out */
                \Libs\ErrorMessages::push('You are Not Admin', '');
            }
        }else{
            /** User Not logged in - kick them out */
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }

        /** Setup Title and Welcome Message */
        $data['title'] = "System Routes";
        $data['welcomeMessage'] = "Welcome to the System Routes.  In order for any page to work on this system, it must be setup here.";
        $data['current_page'] = $_SERVER['REQUEST_URI'];

        /** Setup Breadcrumbs */
        $data['breadcrumbs'] = "
          <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
          <li class='breadcrumb-item active'><i class='fa fa-fw fa-user'></i> ".$data['title']."</li>
        ";


        function checkCoreRoutes($class, $method){
            $auto_cm = array("controller" => $class,"method" => $method);

            /** Get Core Routes */
            $core_routes = Routes::all();
            foreach ($core_routes as $cr) {
                if($class == $cr['controller'] && $method == $cr['method']){
                    $match[] = true;
                }
            }
            if(isset($match)){
                return false;
            }else{
                return true;
            }
        }

        /** Check the following Directory for classes and methods */
        $directory = APPDIR.'Controllers';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));

        /** Extract the methods from the classes */
        foreach ($scanned_directory as $filename) {
            /** Remove the .php from the files names to get Class Names */
            $class = str_replace('.php', '', str_replace('-', ' ', $filename));
            /** Get array of class methods */
            $class_methods = get_class_methods('App\\Controllers\\'.$class);
            /** Remove blank and __construct methods from array */
            if($class_methods[0] == ""){
                unset($class_methods[0]);
            }
            if($class_methods[0] == "__construct"){
                unset($class_methods[0]);
            }
            if($class_methods[1] == "__construct"){
                unset($class_methods[1]);
            }
            if(isset($class_methods)){
              foreach ($class_methods as $method) {
                  if(checkCoreRoutes($class, $method)){
                      $routes[] = array(
                          "controller" => $class,
                          "method" => $method
                      );
                  }
              }
            }
        }

        /** Check all plugin folders for Controllers */
        $plugins_directory = APPDIR.'Plugins';
        foreach(glob($plugins_directory.'/**', GLOB_ONLYDIR) as $dir) {
          $dirname = basename($dir);
          $directory = $plugins_directory.'/'.$dirname.'/Controllers';
          $scanned_directory = array_diff(scandir($directory), array('..', '.'));

          /** Extract the methods from the classes */
          foreach ($scanned_directory as $filename) {
            /** Remove the .php from the files names to get Class Names */
            $class = str_replace('.php', '', str_replace('-', ' ', $filename));
            /** Get array of class methods */
            $class_methods = get_class_methods('App\\Plugins\\'.$dirname.'\\Controllers\\'.$class);
            /** Remove blank and __construct methods from array */
            if($class_methods[0] == ""){
                unset($class_methods[0]);
            }
            if($class_methods[0] == "__construct"){
                unset($class_methods[0]);
            }
            if($class_methods[1] == "__construct"){
                unset($class_methods[1]);
            }
            if(isset($class_methods)){
              foreach ($class_methods as $method) {
                $plugin_class = "Plugins\\".$dirname."\\Controllers\\".$class;
                if(checkCoreRoutes($plugin_class, $method)){
                  $routes[] = array(
                      "controller" => $plugin_class,
                      "method" => $method
                  );
                }
              }
            }
          }
        }

        /** Set new_routes default blank */
        $new_routes = null;

        /** Check database to see if all routes are included. */
        if(isset($routes)){
          foreach ($routes as $single_route) {
            /** Check to see if route exist in database */
            if($this->model->checkForRoute($single_route['controller'], $single_route['method'])){
              /** Controller and Modthod Already Exist */
              /** Might have it do soemthing later... */
            }else{
              /** Controller and Method Do Not Exist in Database */
              /** Check to see if URL is already in the database and add numbers if it is **/
              if($this->model->checkPagesURL($single_route['method'])){
                /** URL Exist - Make it different **/
                $route_url = $single_route['method'].rand(10, 99);
              }else{
                /** URL Does Not Exist - Keep it the same **/
                $route_url = $single_route['method'];
              }
              /** Add Controller and Method to Database */
              if($this->model->addRoute($single_route['controller'], $single_route['method'], $route_url)){
                  $new_routes[] = $single_route['controller']." - ".$single_route['method']."<Br>";
                  /** New Route added to database.  Add to site Links */
                  if($this->model->addSiteLink($single_route['method'], $route_url, $single_route['controller']." - ".$single_route['method'], 'header_main', '0', '')){
                    /** Success */
                    $new_routes[] = $single_route['controller']." - ".$route_url." Added to Site Links<Br>";
                  }
              }
            }
          }
        }

        /** Check to see if any new routes were added to database */
        if(isset($new_routes)){
            /** Format New Rutes for Success Message */
            $new_routes_display = implode(" ", $new_routes);
            /** Success */
            \Libs\SuccessMessages::push('New Routes Have Been Added to Database!<Br><br>'.$new_routes_display, 'AdminPanel-SystemRoutes');
        }

        $data['all_routes'] = $routes;

        /** Get list of System Routes from Database */
        $data['system_routes'] = $this->model->getAllRoutes();

        /** Push data to the view **/
        Load::View("AdminPanel/SystemRoutes", $data, "", "AdminPanel");

    }

    /**
    * System Route Function
    * Allows Admin Edit System Route
    * @param $id
    */
    public function SystemRoute($id){

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
            /** User Not Admin - kick them out */
            \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }

      /** Setup Title and Welcome Message */
      $data['title'] = "System Route";
      $data['welcomeMessage'] = "Welcome to the System Route.  Use Caustion when Editing System Route, it can break your site.";
      $data['csrfToken'] = Csrf::makeToken('route');
      $data['current_page'] = $_SERVER['REQUEST_URI'];

      /** Setup Breadcrumbs */
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel-SystemRoutes'><i class='fa fa-fw fa-cog'></i> System Routes</a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-user'></i>".$data['title']."</li>
      ";

      /** Get System Route from Database */
      $data['system_route'] = $this->model->getRoute($id);

      /** Check to see if Admin is updating System Route */
    	if(isset($_POST['submit'])){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
    		  /** Check to make sure the csrf token is good */
    		  if (Csrf::isTokenValid('route')) {
            // Check for update group
            if($_POST['update_route'] == "true"){
      				// Catch password inputs using the Request helper
              $id = Request::post('id');
              $controller = Request::post('controller');
              $method = Request::post('method');
              $url = Request::post('url');
              $arguments = Request::post('arguments');
              $enable = Request::post('enable');
      				// Run the update group script
      				if($this->model->updateRoute($id, $controller, $method, $url, $arguments, $enable)){
                /** Update URL in Page Permissions and Site Links */
                if($this->model->updateLinkURL($data['system_route'][0]->url, $url)){$success_msg .= '<br> - Updated Site Link URL: '.$url;}
                if($this->model->updatePagePermURL($data['system_route'][0]->url, $url)){$success_msg .= '<br> - Updated Page URL: '.$url;}
      					/** Success */
                \Libs\SuccessMessages::push('You Have Successfully Updated System Route: '.$controller.' - '.$method.' '.$success_msg, 'AdminPanel-SystemRoute/'.$id);
      				}else{
      					/** Error */
      					$error[] = "Route Update Failed";
      				}
                }
                //Check for delete route
                if($_POST['delete_route'] == "true"){
                    // Check to see what Route Admin is going to delete
                    $id = Request::post('id');
                    // Delete the Route
                    if($this->model->deleteRoute($id)){
                        /** Success */
                        \Libs\SuccessMessages::push('You Have Successfully Deleted a Route', 'AdminPanel-SystemRoutes');
                    }else{
                        /** Error */
                        $error[] = "Route Delete Failed";
                    }
                }
            }
        }else{
            /** Error Message Display */
            \Libs\ErrorMessages::push('Demo Limit - Site Routes Settings Disabled', 'AdminPanel-SystemRoutes');
        }
    	}

      /** Push data to the view **/
      Load::View("AdminPanel/SystemRoute", $data, "", "AdminPanel");

    }

    /**
    * Admin Panel Auth Logs
    * Allows admins to view auth logs
    * @param $current_page
    */
    public function AuthLogs($current_page = '1'){

      // Set total number of rows for paginator
      $total_num_auth_logs = $this->model->getTotalAuthLogs();
      $this->pages->setTotal($total_num_auth_logs);

      // Send page links to view
      $pageFormat = SITE_URL."AdminPanel-AuthLogs/"; // URL page where pages are
      $data['pageLinks'] = $this->pages->pageLinks($pageFormat, null, $current_page);
      $data['current_page_num'] = $current_page;

      // Get data for users
      $data['current_page'] = $_SERVER['REQUEST_URI'];
      $data['title'] = "Auth Logs";
      $data['welcomeMessage'] = "Welcome to the Admin Panel Auth Logs";
      $data['auth_logs'] = $this->model->getAuthLogs($this->pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT));

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-server'></i> ".$data['title']."</li>
      ";

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }

      /** Push data to the view **/
      Load::View("AdminPanel/AuthLogs", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Site Links
    * Allows admins to edit Site Links
    * @param $action
    * @param $link_location
    * @param $link_id
    */
    public function SiteLinks($action = null, $link_location = null, $link_id = null){

      // Get data for users
      $data['title'] = "Site Links";
      $data['welcome_message'] = "Welcome to the Admin Panel Site Links Editor.  You can edit links shown within assigned arears of the web site.";
      $data['current_page'] = $_SERVER['REQUEST_URI'];

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-globe'></i> ".$data['title']."</li>
      ";

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }
      /** Check to see if site is a demo site */
      if($action == "LinkUp"){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
          if($this->model->moveUpLink($link_location,$link_id)){
            /** Success */
            \Libs\SuccessMessages::push('You Have Successfully Moved Up Site Link', 'AdminPanel-SiteLinks');
          }else{
            /** Error */
            \Libs\ErrorMessages::push('Moved Up Site Link Failed', 'AdminPanel-SiteLinks');
          }
        }else{
            /** Error Message Display */
            \Libs\ErrorMessages::push('Demo Limit - Site Routes Settings Disabled', 'AdminPanel-SiteLinks');
        }
      }else if($action == "LinkDown"){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
          if($this->model->moveDownLink($link_location,$link_id)){
            /** Success */
            \Libs\SuccessMessages::push('You Have Successfully Moved Down Site Link', 'AdminPanel-SiteLinks');
          }else{
            /** Error */
            \Libs\ErrorMessages::push('Moved Down Site Link Failed', 'AdminPanel-SiteLinks');
          }
        }else{
            /** Error Message Display */
            \Libs\ErrorMessages::push('Demo Limit - Site Routes Settings Disabled', 'AdminPanel-SiteLinks');
        }
      }

      /** Get all Main Site Links */
      $data['main_site_links'] = $this->model->getSiteLinks('header_main');
      $data['link_order_last'] = $this->model->getSiteLinksLastID('header_main');

      /** Push data to the view **/
      Load::View("AdminPanel/SiteLinks", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Site Link
    * Allows admins to add or edit Site Link
    * @param $action
    * @param $main_link_id
    * @param $dd_link_id
    */
    public function SiteLink($action = null, $main_link_id = null, $dd_link_id = null){

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }

      $data['current_page'] = $_SERVER['REQUEST_URI'];

      if($action == 'New'){
        /** Admin is Creating a New Link */
        $data['title'] = "Site Link Editor - New";
        $data['welcome_message'] = "You are creating a new site link.  Fill out the form below.";
        $data['edit_type'] = "new";
        $data['csrfToken'] = Csrf::makeToken('SiteLink');
      }else if($action == "LinkDDUp"){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
          if($this->model->moveUpDDLink($main_link_id,$dd_link_id)){
            /** Success */
            \Libs\SuccessMessages::push('You Have Successfully Moved Up Drop Down Link', 'AdminPanel-SiteLink/'.$main_link_id.'/');
          }else{
            /** Error */
            \Libs\ErrorMessages::push('Move Up Drop Down Link Failed', 'AdminPanel-SiteLink/'.$main_link_id.'/');
          }
        }else{
            /** Error Message Display */
            \Libs\ErrorMessages::push('Demo Limit - Site Routes Settings Disabled', 'AdminPanel-SiteLink/'.$main_link_id.'/');
        }
      }else if($action == "LinkDDDown"){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
          if($this->model->moveDownDDLink($main_link_id,$dd_link_id)){
            /** Success */
            \Libs\SuccessMessages::push('You Have Successfully Moved Down Drop Down Link', 'AdminPanel-SiteLink/'.$main_link_id.'/');
          }else{
            /** Error */
            \Libs\ErrorMessages::push('Move Down Drop Down Link Failed', 'AdminPanel-SiteLink/'.$main_link_id.'/');
          }
        }else{
            /** Error Message Display */
            \Libs\ErrorMessages::push('Demo Limit - Site Routes Settings Disabled', 'AdminPanel-SiteLink/'.$main_link_id.'/');
        }
      }else if($action == 'LinkDelete'){
          /** Admin is Creating a New Link */
          $main_link_title = $this->model->getMainLinkTitle($main_link_id);
          $data['title'] = "Site Link Editor - Delete Link: $main_link_title";
          $data['welcome_message'] = "Do you want to delete link: $main_link_title <Br><Br>";
          $data['welcome_message'] .= "<font color=red><b>NOTE</b>: This also deletes all drop down links assigned to this link if dropdown.</font>";
          $data['main_link_id'] = $main_link_id;
          $data['edit_type'] = "deletelink";
          $data['link_data'] = $this->model->getSiteLinkData($main_link_id);
          $data['csrfToken'] = Csrf::makeToken('SiteLink');
      }else if($action == 'DropDownUpdate'){
          /** Admin is Creating a New Link */
          $main_link_title = $this->model->getMainLinkTitle($main_link_id);
          $data['title'] = "Site Link Editor - Update Drop Down Link for $main_link_title";
          $data['welcome_message'] = "You are updating a drop down link.  Fill out the form below.";
          $data['dd_link_id'] = $dd_link_id;
          $data['main_link_id'] = $main_link_id;
          $data['edit_type'] = "dropdownupdate";
          $data['link_data'] = $this->model->getSiteLinkData($dd_link_id);
          $data['csrfToken'] = Csrf::makeToken('SiteLink');
      }else if($action == 'DropDownNew'){
          /** Admin is Creating a New Link */
          $main_link_title = $this->model->getMainLinkTitle($main_link_id);
          $data['title'] = "Site Link Editor - New Drop Down Link for $main_link_title";
          $data['welcome_message'] = "You are creating a new drop down link.  Fill out the form below.";
          $data['main_link_id'] = $main_link_id;
          $data['edit_type'] = "dropdownnew";
          $data['csrfToken'] = Csrf::makeToken('SiteLink');
      }else if(ctype_digit(strval($action))){
        /** Admin is Editing a Link */
        $data['title'] = "Site Link Editor - Update";
        $data['welcome_message'] = "You are updating a site link.";
        $data['edit_type'] = "update";
        $data['link_data'] = $this->model->getSiteLinkData($action);
        $data['csrfToken'] = Csrf::makeToken('SiteLink');
        /** Get all Drop Down Links */
        $data['drop_down_links'] = $this->model->getSiteDropDownLinks($action);
        $data['drop_down_order_last'] = $this->model->getSiteDropDownLinksLastID($action);
      }else{
        /** Send User Back because the URL Input is invalid */
        \Libs\ErrorMessages::push('Invalid URL Input!', 'AdminPanel-SiteLinks');
      }

      /** Check to see if Admin is updating System Route */
      if(isset($_POST['submit'])){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
          /** Check to make sure the csrf token is good */
          if (Csrf::isTokenValid('SiteLink')) {
            /** Get Form Data */
            $link_action = Request::post('link_action');
            $id = Request::post('id');
            $title = Request::post('title');
            $url = Request::post('url');
            $alt_text = Request::post('alt_text');
            $location = "header_main";
            $drop_down = Request::post('drop_down');
            $require_plugin = Request::post('require_plugin');
            $drop_down_for = Request::post('drop_down_for');
            $dd_link_id = Request::post('dd_link_id');
            $permission = Request::post('permission');
            $icon = Request::post('icon');
            /** Check if update or new */
            if($link_action == "update"){
              if($this->model->updateSiteLink($id, $title, $url, $alt_text, $location, $drop_down, $require_plugin, $permission, $icon)){
                /** Update URL in Page Permissions and Site Routes */
                if($this->model->updateRouteURL($data['link_data'][0]->url, $url)){$success_msg .= '<br> - Updated Site Route URL: '.$url;}
                if($this->model->updatePagePermURL($data['link_data'][0]->url, $url)){$success_msg .= '<br> - Updated Page URL: '.$url;}
                if($this->model->updateLinkURL($data['link_data'][0]->url, $url)){$success_msg .= '<br> - Updated  URL: '.$url;}
                /** Success */
                \Libs\SuccessMessages::push('You Have Successfully Updated Site Link '.$success_msg, 'AdminPanel-SiteLink/'.$id);
              }else{
                /** Error */
                \Libs\ErrorMessages::push('Update Site Link Failed', 'AdminPanel-SiteLinks');
              }
            }else if($link_action == "new"){
              if($this->model->addSiteLink($title, $url, $alt_text, $location, $drop_down, $require_plugin, $permission, $icon)){
                /** Success */
                \Libs\SuccessMessages::push('You Have Successfully Added New Site Link', 'AdminPanel-SiteLinks');
              }else{
                /** Error */
                \Libs\ErrorMessages::push('Create New Site Link Failed', 'AdminPanel-SiteLinks');
              }
            }else if($link_action == "delete"){
              if($this->model->deleteSiteLink($id)){
                /** Success */
                \Libs\SuccessMessages::push('You Have Successfully Deleted Site Link', 'AdminPanel-SiteLinks');
              }else{
                /** Error */
                \Libs\ErrorMessages::push('Delete Site Link Failed', 'AdminPanel-SiteLinks');
              }
            }else if($link_action == "dropdownnew"){
              if($this->model->addSiteDDLink($title, $url, $alt_text, $location, $drop_down, $require_plugin, $drop_down_for, $permission, $icon)){
                /** Success */
                \Libs\SuccessMessages::push('You Have Successfully Added New Site Drop Down Link', 'AdminPanel-SiteLink/'.$drop_down_for);
              }else{
                /** Error */
                \Libs\ErrorMessages::push('Create New Site Drop Down Link Failed', 'AdminPanel-SiteLink/'.$drop_down_for);
              }
            }else if($link_action == "dropdownupdate"){
              if($this->model->updateSiteDDLink($dd_link_id, $title, $url, $alt_text, $location, $drop_down, $require_plugin, $permission, $icon)){
                /** Update URL in Page Permissions and Site Routes */
                if($this->model->updateRouteURL($data['link_data'][0]->url, $url)){$success_msg .= '<br> - Updated Site Route URL: '.$url;}
                if($this->model->updatePagePermURL($data['link_data'][0]->url, $url)){$success_msg .= '<br> - Updated Page URL: '.$url;}
                if($this->model->updateLinkURL($data['link_data'][0]->url, $url)){$success_msg .= '<br> - Updated  URL: '.$url;}
                /** Success */
                \Libs\SuccessMessages::push('You Have Successfully Updated Site Drop Down Link '.$success_msg, 'AdminPanel-SiteLink/'.$main_link_id);
              }else{
                /** Error */
                \Libs\ErrorMessages::push('Update Site Drop Down Link Failed', 'AdminPanel-SiteLink/'.$main_link_id);
              }
            }
          }
        }else{
            /** Error Message Display */
            \Libs\ErrorMessages::push('Demo Limit - Site Links Settings Disabled', 'AdminPanel-SiteLinks');
        }
      }

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><a href='".DIR."AdminPanel-SiteLinks'><i class='fa fa-fw fa-globe'></i>Site Links</a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-globe'></i> ".$data['title']."</li>
      ";

      /** Push data to the view **/
      Load::View("AdminPanel/SiteLink", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Adds Settings
    * Allows admins to add or edit Adds for website
    */
    public function Adds(){
        /** Get data for dashboard */
        $data['current_page'] = $_SERVER['REQUEST_URI'];
        $data['title'] = "Adds Settings";
        $data['welcomeMessage'] = "Welcome to the Admin Panel Site Adds Settings!";

        /** Check to see if user is logged in */
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            /** User is logged in - Get their data */
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
                /** User Not Admin - kick them out */
                \Libs\ErrorMessages::push('You are Not Admin', '');
            }
        }else{
            /** User Not logged in - kick them out */
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }

        /** Check to see if Admin is submiting form data */
        if(isset($_POST['submit'])){
          /** Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
              /** Check to make sure the csrf token is good */
              if (Csrf::isTokenValid('settings')) {
                  /** Check to make sure Admin is updating settings */
                  if($_POST['update_settings'] == "true"){
                      /** Get data sbmitted by form */
                      $adds_top = Request::post('adds_top');
                      $adds_bottom = Request::post('adds_bottom');
                      $adds_sidebar_top = Request::post('adds_sidebar_top');
                      $adds_sidebar_bottom = Request::post('adds_sidebar_bottom');

                      if(!$this->model->updateSetting('adds_top', $adds_top)){ $errors[] = 'Adds Main Top Error'; }
                      if(!$this->model->updateSetting('adds_bottom', $adds_bottom)){ $errors[] = 'Adds Main Bottom Error'; }
                      if(!$this->model->updateSetting('adds_sidebar_top', $adds_sidebar_top)){ $errors[] = 'Adds Sidebar Top Error'; }
                      if(!$this->model->updateSetting('adds_sidebar_bottom', $adds_sidebar_bottom)){ $errors[] = 'Adds Sidebar Bottom Error'; }

                      // Run the update settings script
                      if(!isset($errors) || count($errors) == 0){
                          /** Success */
                          \Libs\SuccessMessages::push('You Have Successfully Updated Adds Settings', 'AdminPanel-Adds');
                      }else{
                          // Error
                          if(isset($errors)){
                              $error_data = "<hr>";
                              foreach($errors as $row){
                                  $error_data .= " - ".$row."<br>";
                              }
                          }else{
                              $error_data = "";
                          }
                          /** Error Message Display */
                          \Libs\ErrorMessages::push('Error Updating Adds Settings'.$error_data, 'AdminPanel-Adds');
                      }
                  }else{
                      /** Error Message Display */
                      \Libs\ErrorMessages::push('Error Updating Adds Settings', 'AdminPanel-Adds');
                  }
              }else{
                  /** Error Message Display */
                  \Libs\ErrorMessages::push('Error Updating Adds Settings', 'AdminPanel-Adds');
              }
          }else{
              /** Error Message Display */
              \Libs\ErrorMessages::push('Demo Limit - Add Settings Disabled', 'AdminPanel-Adds');
          }
        }

        /** Get Settings Data */
        $data['adds_top'] = $this->model->getSettings('adds_top');
        $data['adds_bottom'] = $this->model->getSettings('adds_bottom');
        $data['adds_sidebar_top'] = $this->model->getSettings('adds_sidebar_top');
        $data['adds_sidebar_bottom'] = $this->model->getSettings('adds_sidebar_bottom');

        /** Setup Token for Form */
        $data['csrfToken'] = Csrf::makeToken('settings');

        /** Setup Breadcrumbs */
        $data['breadcrumbs'] = "
          <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
          <li class='breadcrumb-item active'><i class='fab fa-fw fa-adn'></i> ".$data['title']."</li>
        ";

        /** Push data to the view **/
        Load::View("AdminPanel/Adds", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Upgrade
    * Allows admins to automatically upgrade the database
    */
    public function Upgrade(){
      /** Get data for the Upgrade Page */
      $data['current_page'] = $_SERVER['REQUEST_URI'];
      $data['title'] = "Upgrade Database";
      $data['welcomeMessage'] = "Welcome to the Admin Panel Database Upgrade Page!";

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
          /** User is logged in - Get their data */
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
              /** User Not Admin - kick them out */
              \Libs\ErrorMessages::push('You are Not Admin', '');
          }
      }else{
          /** User Not logged in - kick them out */
          \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }

      /** Check to see if UAP Files are Newer than Database Version */
      $data['uap_files_version'] = UAPVersion;
      $data['uap_database_version'] = $this->model->getDatabaseVersion();
      if(!isset($data['uap_database_version'])){ $data['uap_database_version'] = "4.2.1"; }

      /** Setup Upgrade Model */
      $this->upgrade = new Update();

      /** Check to see if Admin is submiting form data */
      if(isset($_POST['submit'])){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
            /** Check to make sure the csrf token is good */
            if (Csrf::isTokenValid('upgrade')) {
                /** Check to make sure Admin is updating settings */
                if($_POST['upgrade_database'] == "update421to430"){
                  /** Run the upgrade database script */
                  $data['welcomeMessage'] = $this->upgrade->update421to430();
                }else{
                    /** Error Message Display */
                    \Libs\ErrorMessages::push('Error Upgrading Database', 'AdminPanel-Upgrade');
                }
            }else{
                /** Error Message Display */
                \Libs\ErrorMessages::push('Error Invalid Token', 'AdminPanel-Upgrade');
            }
        }else{
            /** Error Message Display */
            \Libs\ErrorMessages::push('Demo Limit - Upgrade Disabled', 'AdminPanel-Upgrade');
        }
      }

      /** Setup Token for Form */
      $data['csrfToken'] = Csrf::makeToken('upgrade');

      /** Setup Breadcrumbs */
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-dashboard'></i> ".$data['title']."</li>
      ";

      /** Push data to the view **/
      Load::View("AdminPanel/Upgrade", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Pages Permissions
    * Allows admins to set each page's permissions
    * @param $set_order_by
    */
    public function PagesPermissions($set_order_by = 'URL-ASC'){

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }

      /** Setup Page Info */
      $data['title'] = "Pages Permissions";
      $data['welcomeMessage'] = "Welcome to the Pages Permission Admin Page.";
      /** Check for orderby selection */
      $data['orderby'] = $set_order_by;

      /** Get Data from System Routes */
      $getRoutes = \App\Routes::all();

      /** Check database to see if any new pages need added */
      $new_routes = null;
      if(isset($getRoutes)){
        foreach ($getRoutes as $single_page) {
          /** Check to see if page exist in database */
          if(!$this->model->checkForPage($single_page['controller'], $single_page['method'])){
            /** Page Does Not Exist in Database */
            /** Add Page to Database */
            if($page_id = $this->model->addPage($single_page['controller'], $single_page['method'], $single_page['url'])){
              /** New Page added to database.  Let Admin Know it was added */
              $new_pages[] = "<b>URL: ".$single_page['url']."</b> (".$single_page['controller']." - ".$single_page['method'].")<Br>";
              /** Add new permission for the page and set as public */
              $this->model->addPagePermission($page_id, '0');
            }
          }
        }
      }
      /** Check to see if any new routes were added to database */
      if(isset($new_pages)){
          /** Format New Rutes for Success Message */
          $new_pages_display = implode(" ", $new_pages);
          /** Success */
          \Libs\SuccessMessages::push('New Pages Have Been Added to Database!<Br><br>'.$new_pages_display, 'AdminPanel-PagesPermissions');
      }

      /** Get All Pages Data */
      $data['all_pages'] = $this->model->getAllPages($data['orderby']);

      /** Setup Token for Form */
      $data['csrfToken'] = Csrf::makeToken('pages_permissions');

      /** Setup Breadcrumbs */
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-unlock-alt'></i> ".$data['title']."</li>
      ";

      /** Push data to the view **/
      Load::View("AdminPanel/PagesPermissions", $data, "", "AdminPanel");
    }

    /**
    * Admin Panel Page Permissions
    * Allows admins to set given each page permissions
    * @param $id
    */
    public function PagePermissions($id = null){

      /** Check to see if user is logged in */
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data */
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
          /** User Not Admin - kick them out */
          \Libs\ErrorMessages::push('You are Not Admin', '');
        }
      }else{
        /** User Not logged in - kick them out */
        \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
      }

      /** Get all Site User Groups */
      $data['site_groups'] = $this->model->getAllGroups();

      /** Check to see if Admin is updating System Route */
      if(isset($_POST['submit'])){
        /** Check to see if site is a demo site */
        if(DEMO_SITE != 'TRUE'){
          /** Check to make sure the csrf token is good */
          if (Csrf::isTokenValid('pages_permissions')) {
            /** Check to see if admin is editing page */
            if($_POST['update_page'] == "true"){
              /** Catch inputs using the Request helper */
              $page_id = Request::post('page_id');
              $group_id = Request::post('group_id');
              $sitemap = Request::post('sitemap');
              if($sitemap != 'true'){ $sitemap = 'false'; }

              /** Updated Sitemap Setting **/
              if($this->model->updatePageSiteMap($page_id, $sitemap)){
                $success[] = " - Changed SiteMap Setting to $sitemap for page: ".$page_name[0]->url;
              }
              /** Get all permissions for page */
              $get_page_groups = $this->model->getPageGroups($page_id);
              /** Get Page Name */
              $page_name = $this->model->getPage($page_id);
              /** Check to see if Public is checked */
              if(isset($group_id[0])){
                /** Add to database if not already done */
                /** Check to see if Permission is already in database */
                if(!$this->model->checkForPagePermission($page_id, 0)){
                  /** Add Page Permission to database */
                  if($this->model->addPagePermission($page_id, 0)){
                    $success[] = " - Added Public Permission for page: ".$page_name[0]->url;
                  }
                }
              }else{
                /** Remove from database if exists */
                /** Check to see if Permission is already in database */
                if($this->model->checkForPagePermission($page_id, 0)){
                  /** Add Page Permission to database */
                  if($this->model->removePagePermission($page_id, 0)){
                    $success[] = " - Removed Public Permission for page: ".$page_name[0]->url;
                  }
                }
              }
              /** Updated pages permissions database for site user groups */
              if(!empty($data['site_groups'])){
                foreach ($data['site_groups'] as $key => $value) {
                  /** Get group name for success display */
                  $get_group_data = $this->model->getGroupData($value->groupID);
                  if(isset($group_id[$value->groupID])){
                    /** Add to database if not already done */
                    /** Check to see if Permission is already in database */
                    if(!$this->model->checkForPagePermission($page_id, $value->groupID)){
                      /** Add Page Permission to database */
                      if($this->model->addPagePermission($page_id, $value->groupID)){
                        $success[] = " - Added ".$get_group_data[0]->groupName." Permission for page: ".$page_name[0]->url;
                      }
                    }
                  }else{
                    /** Remove from database if exists */
                    /** Check to see if Permission is already in database */
                    if($this->model->checkForPagePermission($page_id, $value->groupID)){
                      /** Add Page Permission to database */
                      if($this->model->removePagePermission($page_id, $value->groupID)){
                        $success[] = " - Removed ".$get_group_data[0]->groupName." Permission for page: ".$page_name[0]->url;
                      }
                    }
                  }
                }
              }
            }else if($_POST['delete_page'] == "true"){
              /** Catch inputs using the Request helper */
              $page_id = Request::post('page_id');
              /** Admin wants to delete this page **/
              if($this->model->deletePage($page_id)){
                /** Success Message Display */
                \Libs\SuccessMessages::push('Page Permissions have been updated!<Br><br>The following page has been deleted from database: '.$page_name[0]->url, 'AdminPanel-PagesPermissions');
              }
            }
            /** Check for changes **/
            if(!empty($success)){
              /** Change success from a array to a variable */
              $success_msg = "";
              foreach($success as $sm){
                $success_msg .= "$sm<Br>";
              }
              /** Success Message Display */
              \Libs\SuccessMessages::push('Page Permissions have been updated!<Br><br>'.$success_msg, 'AdminPanel-PagePermissions/'.$page_id);
            }else{
                /** Error Message Display */
                \Libs\ErrorMessages::push('Page Permissions were not changed!', 'AdminPanel-PagePermissions/'.$page_id);
            }
          }
        }else{
            /** Error Message Display */
            \Libs\ErrorMessages::push('Demo Limit - Site Routes Settings Disabled', 'AdminPanel-PagePermissions');
        }
      }

      /** Setup Page Info */
      $data['title'] = "Page Permissions";
      $data['welcomeMessage'] = "Welcome to the Page Permission Admin Page.";

      /** Get All Pages Data */
      $data['page_data'] = $this->model->getPage($id);

      /** Setup Token for Form */
      $data['csrfToken'] = Csrf::makeToken('pages_permissions');

      /** Setup Breadcrumbs */
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".SITE_URL."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><a href='".SITE_URL."AdminPanel-PagesPermissions'><i class='fas fa-fw fa-unlock-alt'></i> Pages Permissions</a></li>
        <li class='breadcrumb-item active'><i class='fas fa-fw fa-unlock-alt'></i> ".$data['title']."</li>
      ";

      /** Push data to the view **/
      Load::View("AdminPanel/PagePermissions", $data, "", "AdminPanel");
    }

}
