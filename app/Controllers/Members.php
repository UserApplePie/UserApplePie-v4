<?php
/**
 * Members Controller
 *
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0.4
 */

namespace App\Controllers;

use Core\View,
  Core\Controller,
  Helpers\Session,
  Helpers\Csrf,
  Helpers\Request,
  Helpers\Auth\Auth as AuthHelper,
  App\Models\Users as Users,
  App\Models\Members as MembersModel,
  Helpers\ErrorHelper,
  Helpers\SuccessHelper,
  Helpers\SimpleImage,
  Core\Error;

define('USERS_PAGEINATOR_LIMIT', '20');  // Sets up users listing page limit

class Members extends Controller
{
    private $pages;

    public function __construct()
    {
        parent::__construct();
        $this->language->load('Members');
        $this->pages = new \Helpers\Paginator(USERS_PAGEINATOR_LIMIT);  // How many rows per page
    }

    /**
     * Page for list of activated accounts
     */
    public function members($set_order_by = 'ID-ASC', $current_page = '1')
    {
        $onlineUsers = new MembersModel();
        $data['title'] = $this->language->get('members_title');
        $data['welcomeMessage'] = $this->language->get('members_welcomemessage');

        // Check for orderby selection
        $data['orderby'] = $set_order_by;

        // Set total number of rows for paginator
        $total_num_users = $onlineUsers->getTotalMembers();
        $this->pages->setTotal($total_num_users);

        // Send page links to view
        $pageFormat = DIR."Members/$set_order_by/"; // URL page where pages are
        $data['pageLinks'] = $this->pages->pageLinks($pageFormat, null, $current_page);
        $data['current_page_num'] = $current_page;

        // Get list of members
        $data['members'] = $onlineUsers->getMembers($data['orderby'], $this->pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT));

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          /** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

        /** Get Data For Member Totals Stats Sidebar **/
        $onlineUsers = new MembersModel();
        $data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
        $data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Member-Stats-Sidebar', $data);
        View::render('Members/Members', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Page for list of online accounts
     */
    public function online()
    {
        $onlineUsers = new MembersModel();
        $data['title'] = $this->language->get('members_online_title');
        $data['welcomeMessage'] = $this->language->get('members_online_welcomemessage');
        $data['members'] = $onlineUsers->getOnlineMembers();

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

        /** Get Data For Member Totals Stats Sidebar **/
        $onlineUsers = new MembersModel();
        $data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
        $data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Member-Stats-Sidebar', $data);
        View::render('Members/Members', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Get profile by username
     * @param $username
     */
    public function viewProfile($user)
    {
        $onlineUsers = new MembersModel();
        $profile = $onlineUsers->getUserProfile($user);
        if(sizeof($profile)>0){
            $data['title'] = $profile[0]->username . "'s ".$this->language->get('members_profile_title');
            $data['profile'] = $profile[0];

            /** Check to see if user is logged in **/
            if($data['isLoggedIn'] = $this->auth->isLogged()){
              //** User is logged in - Get their data **/
              $u_id = $this->auth->user_info();
              $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
              $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
            }

            /** Get User's Groups **/
            $data['user_groups'] = $this->user->getUserGroupName($profile[0]->userID);

            /** Setup Breadcrumbs **/
        		$data['breadcrumbs'] = "
        			<li class='active'>".$data['title']."</li>
            ";

            View::renderTemplate('header', $data);
            View::render('Members/View-Profile', $data);
            View::renderTemplate('footer', $data);
        }
        else
            Error::profileError();
    }

    public function editProfile()
    {
        $u_id = $this->auth->currentSessionInfo()['uid'];

        $onlineUsers = new MembersModel();
        $username = $onlineUsers->getUserName($u_id);
        if(sizeof($username) > 0){

            if (isset($_POST['submit'])) {
                if(Csrf::isTokenValid('editprofile')) {
                    $firstName = strip_tags(Request::post('firstName'));
                    $lastName = strip_tags(Request::post('lastName'));
                    $gender = Request::post('gender') == 'male' ? 'Male' : 'Female';
                    $website = strip_tags(preg_replace('#^https?://#', '', Request::post('website')));
                    $aboutMe = nl2br(strip_tags(Request::post('aboutMe')));
                    $signature = nl2br(strip_tags(Request::post('signature')));
                    $picture = file_exists($_FILES['profilePic']['tmp_name']) || is_uploaded_file($_FILES['profilePic']['tmp_name']) ? $_FILES ['profilePic'] : array ();
                    $userImage = Request::post('oldImg');

                    if(sizeof($picture)>0){
							        $check = getimagesize ( $picture['tmp_name'] );

        							if($picture['size'] < 5000000 && $check && ($check['mime'] == "image/jpeg" || $check['mime'] == "image/png" || $check['mime'] == "image/gif")){
        								if(!file_exists('../assets/images/profile-pics'))
        									mkdir('../assets/images/profile-pics',0777,true);

        								$image = new SimpleImage($picture['tmp_name']);
        								$dir = '/assets/images/profile-pics/'.$username[0]->username.'.jpg';
        								$image->best_fit(400,300)->save("..".$dir);
        								$userImage = $dir;
        							}else{
                        // Error Message Display
                        ErrorHelper::push($this->language->get('edit_profile_photo_error'), 'Edit-Profile');
                      }
                    }
                    $onlineUsers->updateProfile($u_id, $firstName, $lastName, $gender, $website, $userImage, $aboutMe, $signature);
                    // Success Message Display
                    SuccessHelper::push($this->language->get('edit_profile_success'), 'Edit-Profile');
                }
                else{
                    // Error Message Display
                    ErrorHelper::push($this->language->get('edit_profile_error'), 'Edit-Profile');
                }

            }

            $username = $username[0]->username;
            $profile = $onlineUsers->getUserProfile($username);

            $data['title'] = $username . "'s ".$this->language->get('edit_profile_title');
            $data['profile'] = $profile[0];
            $data['csrfToken'] = Csrf::makeToken('editprofile');

            /** Check to see if user is logged in **/
            if($data['isLoggedIn'] = $this->auth->isLogged()){
              //** User is logged in - Get their data **/
              $u_id = $this->auth->user_info();
              $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
              $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
            }else{
              /** User Not logged in - kick them out **/
              \Helpers\ErrorHelper::push($this->language->get('user_not_logged_in'), 'Login');
            }

            /** Setup Breadcrumbs **/
        		$data['breadcrumbs'] = "
              <li><a href='".DIR."Account-Settings'>".$this->language->get('mem_act_settings_title')."</a></li>
        			<li class='active'>".$data['title']."</li>
            ";

            View::renderTemplate('header', $data);
            View::render('Members/Member-Account-Sidebar', $data);
            View::render('Members/Edit-Profile', $data);
            View::renderTemplate('footer', $data);
        }else{
          /** User Not logged in - kick them out **/
          \Helpers\ErrorHelper::push($this->language->get('user_not_logged_in'), 'Login');
        }
    }

    /**
     * Page for Account Settings Home
     */
    public function account()
    {
        $data['title'] = $this->language->get('mem_act_settings_title');
        $data['welcomeMessage'] = $this->language->get('mem_act_settings_welcomemessage');

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
          /** User Not logged in - kick them out **/
          \Helpers\ErrorHelper::push($this->language->get('user_not_logged_in'), 'Login');
        }

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Member-Account-Sidebar', $data);
        View::render('Members/Account-Settings', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Page for Privacy Settings Home
     */
    public function privacy()
    {
        $onlineUsers = new MembersModel();

        $data['title'] = $this->language->get('ps_title');
        $data['welcomeMessage'] = $this->language->get('ps_welcomemessage');
        $data['csrfToken'] = Csrf::makeToken('editprivacy');

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
          /** User Not logged in - kick them out **/
          \Helpers\ErrorHelper::push($this->language->get('user_not_logged_in'), 'Login');
        }

        if (isset($_POST['submit'])) {
            if(Csrf::isTokenValid('editprivacy')) {
                $privacy_massemail = Request::post('privacy_massemail');
                $privacy_pm = Request::post('privacy_pm');

                if($privacy_massemail != "true"){$privacy_massemail = "false";}
                if($privacy_pm != "true"){$privacy_pm = "false";}

                if($onlineUsers->updateUPrivacy($u_id, $privacy_massemail, $privacy_pm)){
                  SuccessHelper::push($this->language->get('ps_success'), 'Privacy-Settings');
                }else{
                  ErrorHelper::push($this->language->get('ps_error'), 'Privacy-Settings');
                }
            }
        }

        /** Check users settings to see if privacy mass email is enabled or not **/
        if($data['currentUserData'][0]->privacy_massemail == "true"){
          $data['pme_checked'] = "checked";
        }
        /** Check users settings to see if privacy private message is enabled or not **/
        if($data['currentUserData'][0]->privacy_pm == "true"){
          $data['ppm_checked'] = "checked";
        }

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
          <li><a href='".DIR."Account-Settings'>".$this->language->get('mem_act_settings_title')."</a></li>
    			<li class='active'>".$data['title']."</li>
        ";

        View::renderTemplate('header', $data);
        View::render('Members/Member-Account-Sidebar', $data);
        View::render('Members/Privacy-Settings', $data);
        View::renderTemplate('footer', $data);
    }
}
