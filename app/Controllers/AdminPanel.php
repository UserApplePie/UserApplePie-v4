<?php
/**
 * Admin Panel Controller
 *
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 4.0.0
 */

namespace App\Controllers;

use App\System\Controller,
    App\System\Load,
    Libs\Auth\Auth,
    Libs\Csrf,
    Libs\Request,
    App\Models\AdminPanel as AdminPanelModel,
    App\System\Error,
    App\Models\Members as MembersModel;

define('USERS_PAGEINATOR_LIMIT', '20');  // Sets up users listing page limit

class AdminPanel extends Controller{

  private $model;
  private $pages;

  public function __construct(){
    parent::__construct();
    $this->model = new AdminPanelModel();
    $this->pages = new \Libs\Paginator(USERS_PAGEINATOR_LIMIT);  // How many rows per page
  }

  public function dashboard(){
    // Get data for dashboard
    $data['current_page'] = $_SERVER['REQUEST_URI'];
    $data['title'] = "Dashboard";
    $data['welcomeMessage'] = "Welcom to the Admin Panel Dashboard!";

    /** Get Data For Member Totals Stats Sidebar **/
    $onlineUsers = new MembersModel();
    $data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
    $data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

    /** Get Count Data For Groups **/
    $data['usergroups'] = count($this->model->getAllGroups());

    /** Get Count of Members that Have Logged In Past Days **/
    $data['mem_login_past_1'] = count($this->model->getPastUsersData('LastLogin', '1'));
    $data['mem_login_past_7'] = count($this->model->getPastUsersData('LastLogin', '7'));
    $data['mem_login_past_30'] = count($this->model->getPastUsersData('LastLogin', '30'));
    $data['mem_login_past_90'] = count($this->model->getPastUsersData('LastLogin', '90'));
    $data['mem_login_past_365'] = count($this->model->getPastUsersData('LastLogin', '365'));

    /** Get Count of Members that Have Signed Up In Past Days **/
    $data['mem_signup_past_1'] = count($this->model->getPastUsersData('SignUp', '1'));
    $data['mem_signup_past_7'] = count($this->model->getPastUsersData('SignUp', '7'));
    $data['mem_signup_past_30'] = count($this->model->getPastUsersData('SignUp', '30'));
    $data['mem_signup_past_90'] = count($this->model->getPastUsersData('SignUp', '90'));
    $data['mem_signup_past_365'] = count($this->model->getPastUsersData('SignUp', '365'));

    /** Get total page views count **/
    $data['totalPageViews'] = \Libs\SiteStats::getTotalViews();

    /** Get Current UAP Version Data From UserApplePie.com **/
    $html = file_get_contents('http://www.userapplepie.com/uapversion.php?getversion=UAP');
    preg_match("/UAP (.*) UAP/i", $html, $match);
    $cur_uap_version = UAPVersion;
    if($cur_uap_version < $match[1]){ $data['cur_uap_version'] = $match[1]; }

    /** Check to see if Forum Plugin is Installed  **/
    if(file_exists('../app/Modules/Forum/forum.module.php')){
      $forum_status = "Installed";
      /** Get Current UAP Version Data From UserApplePie.com **/
      $html = file_get_contents('http://www.userapplepie.com/uapversion.php?getversion=Forum');
      preg_match("/UAP-Forum (.*) UAP-Forum/i", $html, $match);
      $cur_uap_forum_version = UAPForumVersion;
      if($cur_uap_forum_version < $match[1]){ $data['cur_uap_forum_version'] = $match[1]; }
    }else{
      $forum_status = "NOT Installed";
    }
    $data['apd_plugin_forum'] = $forum_status;

    /** Check to see if Private Messages Plugin is Installed **/
    if(file_exists('../app/Modules/Messages/messages.module.php')){
      $msg_status = "Installed";
      /** Get Current UAP Version Data From UserApplePie.com **/
      $html = file_get_contents('http://www.userapplepie.com/uapversion.php?getversion=Messages');
      preg_match("/UAP-Messages (.*) UAP-Messages/i", $html, $match);
      $cur_uap_messages_version = UAPMessagesVersion;
      if($cur_uap_messages_version < $match[1]){ $data['cur_uap_messages_version'] = $match[1]; }
    }else{
      $msg_status = "NOT Installed";
    }
    $data['apd_plugin_message'] = $msg_status;

    // Setup Breadcrumbs
    $data['breadcrumbs'] = "
      <li><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li class='active'><i class='fa fa-fw fa-dashboard'></i> ".$data['title']."</li>
    ";

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      /** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
        /** User Not Admin - kick them out **/
        \Libs\ErrorMessages::push('You are Not Admin', '');
      }
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    Load::View("AdminPanel/AdminPanel", $data, "AdminPanel::AP-Sidebar::Left", "AdminPanel");
  }

    /*
    ** Admin Panel Site Settings
    ** Allows admins to change all site settings except database
    */
    public function settings(){
        /* Get data for dashboard */
        $data['current_page'] = $_SERVER['REQUEST_URI'];
        $data['title'] = "Settings";
        $data['welcomeMessage'] = "Welcom to the Admin Panel Site Settings!";

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            /** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
                /** User Not Admin - kick them out **/
                \Libs\ErrorMessages::push('You are Not Admin', '');
            }
        }else{
            /** User Not logged in - kick them out **/
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }

        /* Check to see if Admin is submiting form data */
        if(isset($_POST['submit'])){
            /* Check to make sure the csrf token is good */
            if (Csrf::isTokenValid('settings')) {
                /* Check to make sure Admin is updating settings */
                if($_POST['update_settings'] == "true"){
                    /* Get data sbmitted by form */
                    $site_title = Request::post('site_title');
                    $site_description = Request::post('site_description');
                    $site_keywords = Request::post('site_keywords');
                    $site_user_activation = Request::post('site_user_activation');
                    $site_email_username = Request::post('site_email_username');
                    $site_email_password = Request::post('site_email_password');
                    $site_email_fromname = Request::post('site_email_fromname');
                    $site_email_host = Request::post('site_email_host');
                    $site_email_port = Request::post('site_email_port');
                    $site_email_smtp = Request::post('site_email_smtp');
                    $site_email_site = Request::post('site_email_site');
                    $site_recapcha_public = Request::post('site_recapcha_public');
                    $site_recapcha_private = Request::post('site_recapcha_private');

                    if($this->model->updateSetting('site_title', $site_title)){}else{ $errors[] = 'Site Title Error'; }
                    if($this->model->updateSetting('site_description', $site_description)){}else{ $errors[] = 'Site Description Error'; }
                    if($this->model->updateSetting('site_keywords', $site_keywords)){}else{ $errors[] = 'Site Keywords Error'; }
                    if($this->model->updateSetting('site_user_activation', $site_user_activation)){}else{ $errors[] = 'Site User Activation Error'; }
                    if($this->model->updateSetting('site_email_username', $site_email_username)){}else{ $errors[] = 'Site Email Username Error'; }
                    if($this->model->updateSetting('site_email_password', $site_email_password)){}else{ $errors[] = 'Site Email Password Error'; }
                    if($this->model->updateSetting('site_email_fromname', $site_email_fromname)){}else{ $errors[] = 'Site Email From Name Error'; }
                    if($this->model->updateSetting('site_email_host', $site_email_host)){}else{ $errors[] = 'Site Email Host Error'; }
                    if($this->model->updateSetting('site_email_port', $site_email_port)){}else{ $errors[] = 'Site Email Port Error'; }
                    if($this->model->updateSetting('site_email_smtp', $site_email_smtp)){}else{ $errors[] = 'Site Email SMTP Auth Error'; }
                    if($this->model->updateSetting('site_email_site', $site_email_site)){}else{ $errors[] = 'Site Email Error'; }
                    if($this->model->updateSetting('site_recapcha_public', $site_recapcha_public)){}else{ $errors[] = 'Site reCAPCHA Public Error'; }
                    if($this->model->updateSetting('site_recapcha_private', $site_recapcha_private)){}else{ $errors[] = 'Site reCAPCHA Private Error'; }

                    // Run the update profile script
                    if(count($errors) == 0){
                        // Success
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
                        /* Error Message Display */
                        \Libs\ErrorMessages::push('Error Updating Site Settings'.$error_data, 'AdminPanel-Settings');
                    }
                }else{
                    /* Error Message Display */
                    \Libs\ErrorMessages::push('Error Updating Site Settings', 'AdminPanel-Settings');
                }
            }else{
                /* Error Message Display */
                \Libs\ErrorMessages::push('Error Updating Site Settings', 'AdminPanel-Settings');
            }
        }

        /* Get Settings Data */
        $data['site_title'] = $this->model->getSettings('site_title');
        $data['site_description'] = $this->model->getSettings('site_description');
        $data['site_keywords'] = $this->model->getSettings('site_keywords');
        $data['site_user_activation'] = $this->model->getSettings('site_user_activation');
        $data['site_email_username'] = $this->model->getSettings('site_email_username');
        $data['site_email_password'] = $this->model->getSettings('site_email_password');
        $data['site_email_fromname'] = $this->model->getSettings('site_email_fromname');
        $data['site_email_host'] = $this->model->getSettings('site_email_host');
        $data['site_email_port'] = $this->model->getSettings('site_email_port');
        $data['site_email_smtp'] = $this->model->getSettings('site_email_smtp');
        $data['site_email_site'] = $this->model->getSettings('site_email_site');
        $data['site_recapcha_public'] = $this->model->getSettings('site_recapcha_public');
        $data['site_recapcha_private'] = $this->model->getSettings('site_recapcha_private');

        /* Setup Token for Form */
        $data['csrfToken'] = Csrf::makeToken('settings');

        /* Setup Breadcrumbs */
        $data['breadcrumbs'] = "
          <li><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
          <li class='active'><i class='fa fa-fw fa-dashboard'></i> ".$data['title']."</li>
        ";

        Load::View("AdminPanel/Settings", $data, "AdminPanel::AP-Sidebar::Left", "AdminPanel");
    }



  public function users($set_order_by = 'ID-ASC', $current_page = '1'){

    // Check for orderby selection
    $data['orderby'] = $set_order_by;

    // Set total number of rows for paginator
    $total_num_users = $this->model->getTotalUsers();
    $this->pages->setTotal($total_num_users);

    // Send page links to view
    $pageFormat = DIR."AdminPanel-Users/$set_order_by/"; // URL page where pages are
    $data['pageLinks'] = $this->pages->pageLinks($pageFormat, null, $current_page);
    $data['current_page_num'] = $current_page;

    // Get data for users
    $data['current_page'] = $_SERVER['REQUEST_URI'];
    $data['title'] = "Users";
    $data['welcomeMessage'] = "Welcome to the Users Admin Panel";
    $data['users_list'] = $this->model->getUsers($data['orderby'], $this->pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT));

    // Setup Breadcrumbs
    $data['breadcrumbs'] = "
      <li><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li class='active'><i class='fa fa-fw fa-user'></i>".$data['title']."</li>
    ";

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      /** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
        /** User Not Admin - kick them out **/
        \Libs\ErrorMessages::push('You are Not Admin', '');
      }
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    Load::View("AdminPanel/Users", $data, "AdminPanel::AP-Sidebar::Left", "AdminPanel");
  }

  public function user($id){

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
  					// Success
            \Libs\SuccessMessages::push('You Have Successfully Updated User Profile', 'AdminPanel-User/'.$au_id);
  				}else{
  					// Fail
  					$error[] = "Profile Update Failed";
  				}
        }

        // Check to see if admin is removing user from group
        if($_POST['remove_group'] == "true"){
          // Get data from post
          $au_userID = Request::post('au_userID');
          $au_groupID = Request::post('au_groupID');
          // Updates current user's group
  				if($this->model->removeFromGroup($au_userID, $au_groupID)){
  					// Success
            \Libs\SuccessMessages::push('You Have Successfully Removed User From Group', 'AdminPanel-User/'.$au_userID);
  				}else{
  					// Fail
  					$error[] = "Remove From Group Failed";
  				}
        }

        // Check to see if admin is adding user to group
        if($_POST['add_group'] == "true"){
          // Get data from post
          $au_userID = Request::post('au_userID');
          $au_groupID = Request::post('au_groupID');
          // Updates current user's group
  				if($this->model->addToGroup($au_userID, $au_groupID)){
  					// Success
            \Libs\SuccessMessages::push('You Have Successfully Added User to Group', 'AdminPanel-User/'.$au_userID);
  				}else{
  					// Fail
  					$error[] = "Add to Group Failed";
  				}
        }

        // Check to see if admin wants to activate user
        if($_POST['activate_user'] == "true"){
          $au_id = Request::post('au_id');
          // Run the Activation script
  				if($this->model->activateUser($au_id)){
  					// Success
            \Libs\SuccessMessages::push('You Have Successfully Activated User', 'AdminPanel-User/'.$au_id);
  				}else{
  					// Fail
  					$error[] = "Activate User Failed";
  				}
        }

        // Check to see if admin wants to deactivate user
        if($_POST['deactivate_user'] == "true"){
          $au_id = Request::post('au_id');
          // Run the Activation script
  				if($this->model->deactivateUser($au_id)){
  					// Success
            \Libs\SuccessMessages::push('You Have Successfully Deactivated User', 'AdminPanel-User/'.$au_id);
  				}else{
  					// Fail
  					$error[] = "Deactivate User Failed";
  				}
        }

      }
		}

    // Setup Current User data
		// Get user data from user's database
		$data['user_data'] = $this->model->getUser($id);

    // Setup Breadcrumbs
    $data['breadcrumbs'] = "
      <li><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li><a href='".DIR."AdminPanel-Users'><i class='fa fa-fw fa-user'></i> Users </a></li>
      <li class='active'><i class='fa fa-fw fa-user'></i>User - ".$data['user_data'][0]->username."</li>
    ";

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      /** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
        /** User Not Admin - kick them out **/
        \Libs\ErrorMessages::push('You are Not Admin', '');
      }
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    Load::View("AdminPanel/User", $data, "AdminPanel::AP-Sidebar::Left", "AdminPanel");
  }

  // Setup Groups Page
  public function groups(){

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
      <li><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li class='active'><i class='fa fa-fw fa-user'></i>".$data['title']."</li>
    ";

    // Check to make sure admin is trying to create group
		if(isset($_POST['submit'])){
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
              /** Group Create Success **/
              \Libs\SuccessMessages::push('You Have Successfully Created a New Group', 'AdminPanel-Group/'.$new_group_id);
            }else{
              /** Group Create Error. Show Error **/
              \Libs\ErrorMessages::push('Group Creation Error!', 'AdminPanel-Groups');
            }
          }else{
            /** Group Name Field Empty. Show Error **/
            \Libs\ErrorMessages::push('Group Creation Error: Group Name Field Empty!', 'AdminPanel-Groups');
          }
        }
      }
    }

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      /** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
        /** User Not Admin - kick them out **/
        \Libs\ErrorMessages::push('You are Not Admin', '');
      }
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    Load::View("AdminPanel/Groups", $data, "AdminPanel::AP-Sidebar::Left", "AdminPanel");
  }

  // Setup Group Page
  public function group($id){

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
  					// Success
            \Libs\SuccessMessages::push('You Have Successfully Updated a Group', 'AdminPanel-Group/'.$ag_groupID);
  				}else{
  					// Fail
  					$error[] = "Group Update Failed";
  				}
        }
        //Check for delete group
        if($_POST['delete_group'] == "true"){
          // Catch password inputs using the Request helper
          $ag_groupID = Request::post('ag_groupID');

          // Run the update group script
          if($this->model->deleteGroup($ag_groupID)){
            // Success
            \Libs\SuccessMessages::push('You Have Successfully Deleted a Group', 'AdminPanel-Groups');
          }else{
            // Fail
            $error[] = "Group Delete Failed";
          }
        }
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
      <li><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li><a href='".DIR."AdminPanel-Groups'><i class='fa fa-fw fa-user'></i> Groups </a></li>
      <li class='active'><i class='fa fa-fw fa-user'></i>Group - ".$data['g_groupName']."</li>
    ";

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      /** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
        /** User Not Admin - kick them out **/
        \Libs\ErrorMessages::push('You are Not Admin', '');
      }
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    Load::View("AdminPanel/Group", $data, "AdminPanel::AP-Sidebar::Left", "AdminPanel");
  }

  /**
  * Mass Email Function
  * Allows Admin to Send an Email to All Members
  **/
  public function MassEmail(){

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      /** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      if($data['isAdmin'] = $this->user->checkIsAdmin($u_id) == 'false'){
        /** User Not Admin - kick them out **/
        \Libs\ErrorMessages::push('You are Not Admin', '');
      }
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    /** Setup Title and Welcome Message **/
    $data['title'] = "Mass Email";
    $data['welcomeMessage'] = "Welcome to the Mass Email Admin Feature.  This feature will send an email to All site members that have not disabled the feature.";

    $data['get_users_massemail_allow'] = $this->model->getUsersMassEmail();
    $data['csrfToken'] = Csrf::makeToken('massemail');

    // Setup Breadcrumbs
    $data['breadcrumbs'] = "
      <li><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li class='active'><i class='fa fa-fw fa-user'></i>".$data['title']."</li>
    ";

    (isset($_SESSION['subject'])) ? $data['subject'] = $_SESSION['subject'] : $data['subject'] = "";
    unset($_SESSION['subject']);
    (isset($_SESSION['content'])) ? $data['content'] = $_SESSION['content'] : $data['content'] = "";
    unset($_SESSION['content']);

    // Check to make sure admin is trying to create group
		if(isset($_POST['submit'])){
			// Check to make sure the csrf token is good
			if (Csrf::isTokenValid('massemail')) {
        // Catch password inputs using the Request helper
        $subject = Request::post('subject');
        $content = Request::post('content');
        if(empty($subject)){ $errormsg[] = "Subject Field Blank!"; }
        if(empty($content)){ $errormsg[] = "Content Field Blank!"; }
        $error_count = count($errormsg);
        if($error_count == 0){
          // Run the mass email script
          foreach ($data['get_users_massemail_allow'] as $row) {
            if($this->model->sendMassEmail($row->userID, $u_id, $subject, $content, $row->username, $row->email)){
              $count = $count + 1;
            }
          }
          if($count > 0){
            /** Success **/
            \Libs\SuccessMessages::push('You Have Successfully Sent Mass Email to '.$count.' Users', 'AdminPanel-MassEmail');
          }else{
            /** Fail **/
            \Libs\ErrorMessages::push('Mass Email Error', 'AdminPanel-MassEmail');
          }
        }else{
          $me_errors = "<hr>";
          foreach ($errormsg as $row) {
            $me_errors .= $row."<Br>";
          }
          /** Fail **/
          $_SESSION['subject'] = $subject;
          $_SESSION['content'] = $content;
          \Libs\ErrorMessages::push('Mass Email Error'.$me_errors, 'AdminPanel-MassEmail');
        }
      }
    }

    Load::View("AdminPanel/MassEmail", $data, "AdminPanel::AP-Sidebar::Left", "AdminPanel");
  }

}
