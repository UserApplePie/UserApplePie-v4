<?php
/**
* UserApplePie v4 Friends Controller Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

namespace App\Plugins\Friends\Controllers;

use App\System\Controller,
    App\System\Load,
    Libs\Auth\Auth,
    Libs\Csrf,
    Libs\Request,
    Libs\Url,
    Libs\SuccessMessages,
    Libs\ErrorMessages;

define('FRIENDS_PAGEINATOR_LIMIT', '20');  // Sets up friends listing page limit

/** Friends Controller Class **/
class Friends extends Controller {

    private $model;
    private $pages;

    /** Construct Function **/
	public function __construct(){
		parent::__construct();
        $this->language->load('Friends');
		$this->model = new \App\Plugins\Friends\Models\Friends();
        $this->pages = new \Libs\Paginator(FRIENDS_PAGEINATOR_LIMIT);
	}

    /** Friends List Function **/
    public function friends($set_order_by = 'ID-ASC', $current_page = '1', $search = null){

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            //** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['u_id'] = $u_id;
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            /** User Not logged in - kick them out **/
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }

        /** Let sidebar Know we are on the friends page **/
        $data['friends_page'] = true;

        /** Check for orderby selection **/
        $data['orderby'] = $set_order_by;

        /** Set total number of rows for paginator **/
        // Check to see if member is searching for a user
        if(isset($search)){
            $total_num_friends = $this->model->getFriendsCountSearch($u_id, $search);
            $search_url = "/".$search;
        }else{
            $total_num_friends = \Libs\CurrentUserData::getFriendsCount($u_id);
            $search_url = "";
        }
        $this->pages->setTotal($total_num_friends);

        /** Send page links to view **/
        $pageFormat = SITE_URL."Friends/$set_order_by/"; // URL page where pages are
        $data['pageLinks'] = $this->pages->pageLinks($pageFormat, $search_url, $current_page);
        $data['current_page_num'] = $current_page;

        /** Collect Data page for view **/
        /** Check to see if user is searching friends **/
        if(isset($search)){
            $data['title'] = "Search My Friends";
            $data['welcomeMessage'] = $this->language->get('search_found').' '.$total_num_friends.' '.$this->language->get('matches_for').': '.$search;
            // Let the view know user is searching
            $data['search'] = $search;
        }else{
            $data['title'] = "My Friends";
            $data['welcomeMessage'] = "Welcome to Your Friends List";
            // Let the view know user is not searching
            $data['search'] = false;
        }

        /** Setup Breadcrumbs **/
        $data['breadcrumbs'] = "
          <li class='active'>My Friends</li>
        ";

        /** Get List of All Current User's Friends **/
        $data['friends_list'] = $this->model->friends_list($u_id, $set_order_by, $this->pages->getLimit($current_page, FRIENDS_PAGEINATOR_LIMIT), $search);

        /* Send data to view */
        Load::ViewPlugin("friends", $data, "friends_sidebar::Left", "Friends");

    }

    /** UnFriend Function **/
    public function unfriend($username){
        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            //** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['u_id'] = $u_id;
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            /** User Not logged in - kick them out **/
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }
        /** Get friend's userID **/
        $friend_id = $this->model->getFriendID($username);
        if(isset($friend_id)){
            /** Check to make sure user is currently a friend **/
            if($this->model->check_friend($u_id, $friend_id)){
                /** Remove friend from friends list **/
                if($this->model->unfriend($u_id, $friend_id)){
                    /* Success Message Display */
                    \Libs\SuccessMessages::push($username.' has been removed from your friends list!', 'Friends');
                }else{
                    /* Error Message Display */
                    \Libs\ErrorMessages::push('Friend was NOT removed!', 'Friends');
                }
            }else{
                /* Error Message Display */
                \Libs\ErrorMessages::push($username.' is not your friend!', 'Friends');
            }
        }else{
            /* Error Message Display */
            \Libs\ErrorMessages::push('Friend ID not found!', 'Friends');
        }
    }

    /** AddFriend Function **/
    public function addfriend($username){
        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            //** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['u_id'] = $u_id;
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            /** User Not logged in - kick them out **/
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }
        /** Get friend's userID **/
        $friend_id = $this->model->getFriendID($username);
        if(isset($friend_id)){
            /** Check to make sure user is currently a friend **/
            if($this->model->check_friend($u_id, $friend_id)){
                /* Error Message Display */
                \Libs\ErrorMessages::push($username.' is already your friend, or has yet to approve your request!', 'Friends');
            }else{
                /** Remove friend from friends list **/
                if($this->model->addfriend($u_id, $friend_id)){
                    /* Success Message Display */
                    \Libs\SuccessMessages::push('Friend Request Sent to '.$username.'!', 'FriendRequests');
                }else{
                    /* Error Message Display */
                    \Libs\ErrorMessages::push('Friend Request NOT sent!', 'FriendRequests');
                }
            }
        }else{
            /* Error Message Display */
            \Libs\ErrorMessages::push('Friend ID not found!', 'FriendRequests');
        }
    }


    /** Friends List Function **/
    public function friendrequests(){

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            //** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['u_id'] = $u_id;
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            /** User Not logged in - kick them out **/
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }

        /** Collect Data page for view **/
        $data['title'] = "My Friend Requests";
        $data['welcomeMessage'] = "Welcome to Your Friend Requests List";

        /** Setup Breadcrumbs **/
        $data['breadcrumbs'] = "
          <li class='active'>My Friend Requests</li>
        ";

        /** Setup form token! **/
        $data['csrf_token'] = Csrf::makeToken('friends');

        /** Get List of All Current User's Sent Friend Requests **/
        $data['friends_requests_recv'] = $this->model->friends_requests_recv($u_id);

        /** Get List of All Current User's Sent Friend Requests **/
        $data['friends_requests_sent'] = $this->model->friends_requests_sent($u_id);

        /* Send data to view */
        Load::ViewPlugin("friend_requests", $data, "friends_sidebar::Left", "Friends");

    }

    /** Approve Friend Function **/
    public function approvefriend($username){
        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            //** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['u_id'] = $u_id;
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            /** User Not logged in - kick them out **/
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }
        /** Get friend's userID **/
        $friend_id = $this->model->getFriendID($username);
        if(isset($friend_id)){
            /** Check to make sure user is currently a friend **/
            if($this->model->check_friend($u_id, $friend_id)){
                /* Error Message Display */
                \Libs\ErrorMessages::push($username.' is already your friend!', 'Friends');
            }else{
                /** Remove friend from friends list **/
                if($this->model->approvefriend($u_id, $friend_id)){
                    /* Success Message Display */
                    \Libs\SuccessMessages::push('You have approved a friend request with '.$username.'!', 'FriendRequests');
                }else{
                    /* Error Message Display */
                    \Libs\ErrorMessages::push('Friend Request NOT approved!', 'FriendRequests');
                }
            }
        }else{
            /* Error Message Display */
            \Libs\ErrorMessages::push('Friend ID not found!', 'FriendRequests');
        }
    }

    /** Cancel Friend Function **/
    public function cancelfriend($username){
        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            //** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['u_id'] = $u_id;
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            /** User Not logged in - kick them out **/
            \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
        }
        /** Get friend's userID **/
        $friend_id = $this->model->getFriendID($username);
        if(isset($friend_id)){
            /** Remove friend from friends list **/
            if($this->model->unfriend($u_id, $friend_id)){
                /* Success Message Display */
                \Libs\SuccessMessages::push('You have canceled a friend request with '.$username , 'FriendRequests');
            }else{
                /* Error Message Display */
                \Libs\ErrorMessages::push('Friend was NOT removed!', 'FriendRequests');
            }
        }else{
            /* Error Message Display */
            \Libs\ErrorMessages::push('Friend ID not found!', 'FriendRequests');
        }
    }

}
