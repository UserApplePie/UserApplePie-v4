<?php
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

class Members extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Page for list of activated accounts
     */
    public function members()
    {
        $onlineUsers = new MembersModel();
        $data['title'] = 'Members';
        $data['members'] = $onlineUsers->getMembers();

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
        $data['title'] = 'Members';
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
            $data['title'] = $profile[0]->username . "'s Profile";
            $data['profile'] = $profile[0];

            /** Check to see if user is logged in **/
            if($data['isLoggedIn'] = $this->auth->isLogged()){
              //** User is logged in - Get their data **/
              $u_id = $this->auth->user_info();
              $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
              $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
            }

            /** Get User's Groups **/
            $data['user_groups'] = $this->user->getUserGroupName($u_id);

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
                    $gender = Request::post('gender') == 'male' ? 'Male' : 'Female';
                    $website = !filter_var(Request::post('website'), FILTER_VALIDATE_URL) === false ? Request::post('website') : DIR.'profile/'.$username;
                    $aboutMe = nl2br(strip_tags(Request::post('aboutMe')));
                    $picture = file_exists($_FILES['profilePic']['tmp_name']) || is_uploaded_file($_FILES['profilePic']['tmp_name']) ? $_FILES ['profilePic'] : array ();
                    $userImage = Request::post('oldImg');

                    if(sizeof($picture)>0){
							        $check = getimagesize ( $picture['tmp_name'] );

        							if($picture['size'] < 5000000 && $check && ($check['mime'] == "image/jpeg" || $check['mime'] == "image/png" || $check['mime'] == "image/gif")){
        								if(!file_exists('../assets/images/profile-pics'))
        									mkdir('../assets/images/profile-pics',0777,true);

        								$image = new SimpleImage($picture['tmp_name']);
        								$dir = '../assets/images/profile-pics/'.$username[0]->username.'.jpg';
        								$image->best_fit(400,300)->save($dir);
        								$userImage = $dir;
        							}else{
                        $data['message'] = "Error Uploading profile photo";
                        // Error Message Display
                        ErrorHelper::push($data['message'], 'Edit-Profile');
                      }
                    }
                    $onlineUsers->updateProfile($u_id, $firstName, $gender, $website, $userImage, $aboutMe);
                    $data['message'] = "Successfully updated profile";
                    // Success Message Display
                    SuccessHelper::push($data['message'], 'Edit-Profile');
                }
                else{
                    $data['message'] = "Error Updating profile";
                    // Error Message Display
                    ErrorHelper::push($data['message'], 'Edit-Profile');
                }

            }

            $username = $username[0]->username;
            $profile = $onlineUsers->getUserProfile($username);

            $data['title'] = $username . "'s Profile";
            $data['profile'] = $profile[0];
            $data['csrfToken'] = Csrf::makeToken('editprofile');

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

            View::renderTemplate('header', $data);
            View::render('Members/Member-Account-Sidebar', $data);
            View::render('Members/Edit-Profile', $data);
            View::renderTemplate('footer', $data);
        }
        else
            Error::error404();
    }

    /**
     * Page for Account Settings Home
     */
    public function account()
    {
        $data['title'] = 'Account Settings';
        $data['welcomeMessage'] = 'Welcome to your account settings.  Enjoy!';

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
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
        $data['title'] = 'Privacy Settings';
        $data['welcomeMessage'] = 'Welcome to your privacy settings.  Enjoy!';

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

        View::renderTemplate('header', $data);
        View::render('Members/Member-Account-Sidebar', $data);
        View::render('Members/Account-Settings', $data);
        View::renderTemplate('footer', $data);
    }
}
