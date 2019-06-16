<?php
/**
 * Members Controller
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
 */

namespace App\Controllers;

use App\System\Controller,
    App\System\Load,
    Libs\Session,
    Libs\Csrf,
    Libs\Request,
    Libs\Auth\Auth as AuthHelper,
    App\Models\Users as Users,
    App\Models\Members as MembersModel,
    App\Models\Recent as Recent,
    Libs\ErrorMessages,
    Libs\SuccessMessages,
    Libs\SimpleImage,
    App\System\Error;

class Members extends Controller
{
    private $pages;

    public function __construct()
    {
        parent::__construct();
        $this->language->load('Members');
        $this->pages = new \Libs\Paginator(USERS_PAGEINATOR_LIMIT);  // How many rows per page
    }

    /**
     * Page for list of activated accounts
     */
    public function members($set_order_by = 'ID-ASC', $current_page = '1', $search = null)
    {
        // Load the members model
        $onlineUsers = new MembersModel();

        // Let sidebar Know we are on the members page
        $data['members_page'] = true;

        // Check for orderby selection
        $data['orderby'] = $set_order_by;

        // Check to see if member is searching for a user
        if(isset($search)){
            // Set total number of rows for paginator
            $total_num_users = $onlineUsers->getTotalMembersSearch($search);
            $this->pages->setTotal($total_num_users);
            $search_url = "/".$search;
        }else{
            // Set total number of rows for paginator
            $total_num_users = $onlineUsers->getTotalMembers();
            $this->pages->setTotal($total_num_users);
            $search_url = "";
        }

        // Send page links to view
        $pageFormat = SITE_URL."Members/$set_order_by/"; // URL page where pages are
        $data['pageLinks'] = $this->pages->pageLinks($pageFormat, $search_url, $current_page);
        $data['current_page_num'] = $current_page;

        // Check to see if member is searching for a user
        if(isset($search)){
            // Display Search Info
            $data['title'] = $this->language->get('members_search_title');
            $data['welcomeMessage'] = $this->language->get('search_found').' '.$total_num_users.' '.$this->language->get('matches_for').': '.$search;
            // Get list of members that match search criteria
            $data['members'] = $onlineUsers->getMembers($data['orderby'], $this->pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT), $search);
            // Let the view know user is searching
            $data['search'] = $search;
        }else{
            // Display all members
            $data['title'] = $this->language->get('members_title');
            $data['welcomeMessage'] = $this->language->get('members_welcomemessage');
            // Get list of members
            $data['members'] = $onlineUsers->getMembers($data['orderby'], $this->pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT));
            // Let the view know user is searching
            $data['search'] = false;
        }

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
    			<li class='breadcrumb-item active'>".$data['title']."</li>
        ";

        Load::View("Members/Members", $data, "Members/Member-Stats-Sidebar::Left");
    }

    /**
     * Page for list of online accounts
     */
    public function online($set_order_by = 'ID-ASC', $current_page = '1')
    {
        $onlineUsers = new MembersModel();
        $data['title'] = $this->language->get('members_online_title');
        $data['welcomeMessage'] = $this->language->get('members_online_welcomemessage');

        // Check for orderby selection
        $data['orderby'] = $set_order_by;

        // Set total number of rows for paginator
        $total_num_users = count($onlineUsers->getOnlineMembers());
        $this->pages->setTotal($total_num_users);

        // Send page links to view
        $pageFormat = SITE_URL."Members/$set_order_by/"; // URL page where pages are
        $data['pageLinks'] = $this->pages->pageLinks($pageFormat, null, $current_page);
        $data['current_page_num'] = $current_page;

        // Get list of online memebers
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
    			<li class='breadcrumb-item active'>".$data['title']."</li>
        ";

        Load::View("Members/Members", $data, "Members/Member-Stats-Sidebar::Left");
    }

    /**
     * Get profile by username
     * @param $username
     */
    public function viewProfile($user = '', $current_page = '1')
    {
        /** Load the Members Model **/
        $onlineUsers = new MembersModel();

        /** Load the Recent Model **/
        $Recent = new Recent();

        $profile = $onlineUsers->getUserProfile($user);
        $main_image = $onlineUsers->getUserImageMain($profile[0]->userID);

        // Set total number of rows for paginator
        $total_num_images = $onlineUsers->getTotalImages($profile[0]->userID);
        $this->pages->setTotal($total_num_images);

        // Send page links to view
        $pageFormat = SITE_URL."Profile/".$profile[0]->username."/"; // URL page where pages are
        $data['pageLinks'] = $this->pages->pageLinks($pageFormat, '', $current_page);
        $data['current_page_num'] = $current_page;

        if($profile){
            $data['title'] = $profile[0]->username . "'s ".$this->language->get('members_profile_title');
            $data['profile'] = $profile[0];
            $data['main_image'] = $main_image;

            /** Check to see if user is logged in **/
            if($data['isLoggedIn'] = $this->auth->isLogged()){
              //** User is logged in - Get their data **/
              $u_id = $this->auth->user_info();
              $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
              $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
              $data['current_userID'] = $u_id;
            }

            /** Set the CSRF Token **/
            $data['csrfToken'] = Csrf::makeToken('status');

            /** Get 15 of the users friends **/
            $data['friends'] = $Recent->getFriendsIDs($profile[0]->userID, '15');

            /** Get 15 of the users friends **/
            $data['mutual_friends'] = $Recent->getMutualFriendsIDs($profile[0]->userID, $u_id, '15');

            /** Get Users Status Updates **/
            $data['status_updates'] = $onlineUsers->getUserStatusUpdates($profile[0]->userID);

            /** Get Users Images **/
            $data['user_images'] = $onlineUsers->getUserImages($profile[0]->userID, $this->pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT));

            /** Get User's Groups **/
            $data['user_groups'] = $this->user->getUserGroupName($profile[0]->userID);

            /** Setup Breadcrumbs **/
        		$data['breadcrumbs'] = "
        			<li class='breadcrumb-item active'>".$data['title']."</li>
            ";

            Load::View("Members/View-Profile", $data);
        }
        else
            Error::profileError();
    }

    /**
     * Edit User Profile
     */
    public function editProfile()
    {
        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
          /** User Not logged in - kick them out **/
          \Libs\ErrorMessages::push($this->language->get('user_not_logged_in'), 'Login');
        }

        $onlineUsers = new MembersModel();
        $username = $onlineUsers->getUserName($u_id);

        $main_image = $onlineUsers->getUserImageMain($u_id);

        if(sizeof($username) > 0){
            if (isset($_POST['submit'])) {
                if(Csrf::isTokenValid('editprofile')) {
                    $firstName = strip_tags(Request::post('firstName'));
                    $lastName = strip_tags(Request::post('lastName'));
                    $gender = Request::post('gender') == 'male' ? 'Male' : 'Female';
                    $website = strip_tags(preg_replace('#^https?://#', '', Request::post('website')));
                    $aboutMe = nl2br(strip_tags(Request::post('aboutMe')));
                    $signature = nl2br(strip_tags(Request::post('signature')));

                    /* Check to make sure First Name does not have any html char in it */
                    if($firstName != strip_tags($firstName)){
                        /* Error Message Display */
                        ErrorMessages::push($this->language->get('edit_profile_firstname_error'), 'Edit-Profile');
                    }
                    /* Check to make sure Last Name does not have any html char in it */
                    if($lastName != strip_tags($lastName)){
                        /* Error Message Display */
                        ErrorMessages::push($this->language->get('edit_profile_lastname_error'), 'Edit-Profile');
                    }
                    /* Check to make sure Website url is valid */
                    if (!empty($website)){
                        if (filter_var('http://'.$website, FILTER_VALIDATE_URL) === FALSE) {
                            /* Error Message Display */
                            ErrorMessages::push($this->language->get('edit_profile_website_error'), 'Edit-Profile');
                        }
                    }
                    /* Clean Up Aboutme and Signature from using HTML */
                    $aboutMe = strip_tags($aboutMe, "<br>");
                    $signature = strip_tags($signature, "<br>");

                    $onlineUsers->updateProfile($u_id, $firstName, $lastName, $gender, $website, $aboutMe, $signature);
                    /** Success Message Display **/
                    SuccessMessages::push($this->language->get('edit_profile_success'), 'Edit-Profile');
                }else{
                    /** Error Message Display **/
                    ErrorMessages::push($this->language->get('edit_profile_error'), 'Edit-Profile');
                }

            }

            /** Get User data **/
            $username = $username[0]->username;
            $profile = $onlineUsers->getUserProfile($username);

            $data['title'] = $username . "'s ".$this->language->get('edit_profile_title');
            $data['profile'] = $profile[0];
            $data['csrfToken'] = Csrf::makeToken('editprofile');
            $data['main_image'] = $main_image;

            /** Setup Breadcrumbs **/
        		$data['breadcrumbs'] = "
              <li class='breadcrumb-item'><a href='".SITE_URL."Account-Settings'>".$this->language->get('mem_act_settings_title')."</a></li>
        			<li class='breadcrumb-item active'>".$data['title']."</li>
            ";

            Load::View("Members/Edit-Profile", $data, "Members/Member-Account-Sidebar::Left");

        }else{
          /** User Not logged in - kick them out **/
          \Libs\ErrorMessages::push($this->language->get('user_not_logged_in'), 'Login');
        }
    }

    /**
     * Edit User Profile Images
     */
    public function editProfileImages($imageID = '', $current_page = '1')
    {

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          /** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
          /** User Not logged in - kick them out **/
          \Libs\ErrorMessages::push($this->language->get('user_not_logged_in'), 'Login');
        }

        $onlineUsers = new MembersModel();
        $username = $onlineUsers->getUserName($u_id);

        $main_image = $onlineUsers->getUserImageMain($u_id);

        /** Get Users Images **/
        $data['user_images'] = $onlineUsers->getUserImages($u_id, $this->pages->getLimit($current_page, USERS_PAGEINATOR_LIMIT));

        // Set total number of rows for paginator
        $total_num_images = $onlineUsers->getTotalImages($u_id);
        $this->pages->setTotal($total_num_images);

        // Send page links to view
        $pageFormat = SITE_URL."Edit-Profile-Images/View/"; // URL page where pages are
        $data['pageLinks'] = $this->pages->pageLinks($pageFormat, '', $current_page);
        $data['current_page_num'] = $current_page;

        if(empty($imageID) || $imageID == 'View'){
            if (isset($_POST['submit'])) {
                if(Csrf::isTokenValid('editprofile')) {
                    $userImage = Request::post('oldImg');
                    /** Ready site to upload Files **/
                    $countfiles = count($_FILES['profilePic']['name']);
                    if(!empty($_FILES['profilePic']['name'][0])){
                      for($i=0;$i<$countfiles;$i++){
                        // Check to see if an image is being uploaded
                        if(!empty($_FILES['profilePic']['tmp_name'][$i])){
                            $picture = file_exists($_FILES['profilePic']['tmp_name'][$i]) || is_uploaded_file($_FILES['profilePic']['tmp_name'][$i]) ? $_FILES ['profilePic']['tmp_name'][$i] : array ();
                            if($picture != ""){
                                // Set the User's Profile Image Directory
                                $img_dir_profile = IMG_DIR_PROFILE.$username[0]->username.'/';
        				                $check = getimagesize ( $picture );
                                // Check to make sure image is good
        						            if($check['size'] < 5000000 && $check && ($check['mime'] == "image/jpeg" || $check['mime'] == "image/png" || $check['mime'] == "image/gif")){
                                    // Check to see if Img Upload Directory Exists, if not create it
        							              if(!file_exists(ROOTDIR.$img_dir_profile))
        								                mkdir(ROOTDIR.$img_dir_profile,0777,true);
                                    // Format new image and upload it to server
        							              $image = new SimpleImage($picture);
                                    $rand_string = substr(str_shuffle(md5(time())), 0, 10);
                                    $img_name = $username[0]->username.'_PROFILE_'.$rand_string.'.jpg';
        							              $dir = $img_dir_profile.$img_name;
                                    $img_max_size = explode(',', IMG_MAX_SIZE);
        							              $image->best_fit($img_max_size[0],$img_max_size[1])->save(ROOTDIR.$dir);
        						            }else{
                                    /** Error Message Display **/
                                    ErrorMessages::push($this->language->get('edit_profile_photo_error'), 'Edit-Profile');
                                }
                                /** Check to see if Image name is set **/
                                if(!empty($img_name)){
                                    $db_image = $username[0]->username.'/'.$img_name;
                                }else{
                                    $db_image = $userImage;
                                }
                                if(!$onlineUsers->addUserImage($u_id, $db_image)){
                                  $image_error[] = true;
                                }
                            }
                        }
                      }
                    }else{
                      $image_error[] = true;
                    }
                    /* Check for Image Errors */
                    if(empty($image_error)){
                        /** Success Message Display **/
                        SuccessMessages::push($this->language->get('edit_profile_images_success'), 'Edit-Profile-Images');
                    }else{
                        /* Error Message Display */
                        ErrorMessages::push($this->language->get('edit_profile_photo_error'), 'Edit-Profile-Images');
                    }
                }else{
                    /** Error Message Display **/
                    ErrorMessages::push($this->language->get('edit_profile_error'), 'Edit-Profile-Images');
                }

            }

            /** Get user data **/
            $username = $username[0]->username;
            $profile = $onlineUsers->getUserProfile($username);

            $data['title'] = $this->language->get('mem_act_edit_profile_images');
            $data['profile'] = $profile[0];
            $data['csrfToken'] = Csrf::makeToken('editprofile');
            $data['main_image'] = $main_image;

            /** Setup Breadcrumbs **/
        		$data['breadcrumbs'] = "
              <li class='breadcrumb-item'><a href='".SITE_URL."Account-Settings'>".$this->language->get('mem_act_settings_title')."</a></li>
        			<li class='breadcrumb-item active'>".$data['title']."</li>
            ";

            Load::View("Members/Edit-Profile-Images", $data, "Members/Member-Account-Sidebar::Left");

        }else{
          /** User is editing an image **/
          $data['title'] = $this->language->get('mem_act_edit_profile_image');
          $data['profile'] = $profile[0];
          $data['csrfToken'] = Csrf::makeToken('editprofile');
          $data['edit_image'] = $onlineUsers->getUserImage($u_id, $imageID);
          $data['main_image'] = $main_image;

          /** Check if Image requested exists and belongs to member **/
          if(empty($data['edit_image'])){
            /** Error Message Display **/
            ErrorMessages::push($this->language->get('edit_profile_image_error'), 'Edit-Profile-Images');
          }else{
            /** Check to see if user is editing a photo **/
            $data['imageID'] = $imageID;
            if (isset($_POST['submit'])) {
                if(Csrf::isTokenValid('editprofile')) {
                    /** Get Data from the POST **/
                    $image_action = Request::post('image_action');
                    $imageID = Request::post('imageID');
                    /** Check to see if user is setting an image as default or deleting **/
                    if($image_action == "default"){
                      /** Change image to default and change old default to regular **/
                      $main_image_id = $onlineUsers->getUserImageMainID($u_id);
                      if($onlineUsers->updateUserImage($u_id, $main_image_id, '0')){
                        if($onlineUsers->updateUserImage($u_id, $imageID, '1')){
                          /** Error Message Display **/
                          SuccessMessages::push($this->language->get('edit_profile_image_success'), 'Edit-Profile-Images');
                        }else{
                          /** Error Message Display **/
                          ErrorMessages::push($this->language->get('edit_profile_image_error'), 'Edit-Profile-Images');
                        }
                      }else{
                        /** Error Message Display **/
                        ErrorMessages::push($this->language->get('edit_profile_image_error'), 'Edit-Profile-Images');
                      }

                    }else if($image_action == "delete"){
                      /** Remove the Photo from the server and delete the image **/
                      if($data['edit_image'] == 'default-1.jpg' || $data['edit_image'] == 'default-2.jpg' || $data['edit_image'] == 'default-3.jpg' || $data['edit_image'] == 'default-4.jpg' || $data['edit_image'] == 'default-5.jpg'){
                        if($onlineUsers->deleteUserImage($u_id, $imageID)){
                          /** Error Message Display **/
                          SuccessMessages::push($this->language->get('edit_profile_image_success'), 'Edit-Profile-Images');
                        }
                      }else{
                        if(file_exists(ROOTDIR.IMG_DIR_PROFILE.$data['edit_image'])) {
                            unlink(ROOTDIR.IMG_DIR_PROFILE.$data['edit_image']);
                            if($onlineUsers->deleteUserImage($u_id, $imageID)){
                              /** Error Message Display **/
                              SuccessMessages::push($this->language->get('edit_profile_image_success'), 'Edit-Profile-Images');
                            }
                        }
                      }
                    }
                }else{
                  /** Error Message Display **/
                  ErrorMessages::push($this->language->get('edit_profile_image_error'), 'Edit-Profile-Images');
                }
            }
          }

          /** Setup Breadcrumbs **/
          $data['breadcrumbs'] = "
            <li class='breadcrumb-item'><a href='".SITE_URL."Account-Settings'>".$this->language->get('mem_act_settings_title')."</a></li>
            <li class='breadcrumb-item active'>".$data['title']."</li>
          ";

          Load::View("Members/Edit-Profile-Image", $data, "Members/Member-Account-Sidebar::Left");
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
          \Libs\ErrorMessages::push($this->language->get('user_not_logged_in'), 'Login');
        }

        /** Setup Breadcrumbs **/
    		$data['breadcrumbs'] = "
    			<li class='breadcrumb-item active'>".$data['title']."</li>
        ";

        Load::View("Members/Account-Settings", $data, "Members/Member-Account-Sidebar::Left");
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
          \Libs\ErrorMessages::push($this->language->get('user_not_logged_in'), 'Login');
        }

        if (isset($_POST['submit'])) {
            if(Csrf::isTokenValid('editprivacy')) {
                $privacy_massemail = Request::post('privacy_massemail');
                $privacy_pm = Request::post('privacy_pm');

                if($privacy_massemail != "true"){$privacy_massemail = "false";}
                if($privacy_pm != "true"){$privacy_pm = "false";}

                if($onlineUsers->updateUPrivacy($u_id, $privacy_massemail, $privacy_pm)){
                  SuccessMessages::push($this->language->get('ps_success'), 'Privacy-Settings');
                }else{
                  ErrorMessages::push($this->language->get('ps_error'), 'Privacy-Settings');
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
          <li class='breadcrumb-item'><a href='".SITE_URL."Account-Settings'>".$this->language->get('mem_act_settings_title')."</a></li>
    			<li class='breadcrumb-item active'>".$data['title']."</li>
        ";

        Load::View("Members/Privacy-Settings", $data, "Members/Member-Account-Sidebar::Left");
    }
}
