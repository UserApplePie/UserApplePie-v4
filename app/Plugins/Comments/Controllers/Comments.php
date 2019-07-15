<?php
/**
* UserApplePie v4 Comments Controller Plugin
*
* UserApplePie - Comments Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

/** Comments controller **/

namespace App\Plugins\Comments\Controllers;

use App\System\Controller,
    App\System\Load,
    Libs\SuccessMessages,
    Libs\ErrorMessages,
    Libs\currentUserData,
    Libs\Csrf;

  class Comments extends Controller{

  	private $model;

    public function __construct(){
  		parent::__construct();
  		$this->model = new \App\Plugins\Comments\Models\Comments();
  	}

    /** Comments Home Page Display **/
    public function comments($get_location = null, $get_id = null){

      /** Check to see if user is logged in **/
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data **/
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
      }else{
        $u_id = "";
      }

    	/** Collect Data for view **/
    	$data['title'] = 'Comments Title';
    	$data['welcome_message'] = 'Welcome Message';
      $data['site_description'] = 'Description';
      $data['com_location'] = $get_location;
      $data['com_location_id'] = $get_id;

      /** Check comment location and get post data **/
      if($get_location == "Status"){
        /** Get Status Data **/
        if($data['status_data'] = $this->model->getStatus($get_id)){
          $data['title'] = currentUserData::getUserName($data['status_data'][0]->status_userID)."'s Status - Feeling ".$data['status_data'][0]->status_feeling;
          $data['welcome_message'] = $data['status_data'][0]->status_content;
          $data['site_description'] = $data['welcome_message'];
          $data['csrfToken'] = Csrf::makeToken('status');
          /** Check to see if Current user is friends with original poster **/
          /* Check to see if Friends Plugin is installed, if it is show link */
          if(file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php')){
            if($data['currentUserData'][0]->userID == $data['status_data'][0]->status_userID){
              /** If Current User owns the post then show it **/
              $friends_status = "Friends";
            }else{
              /** Check to see if users are friends or if a request is pending **/
              $friends_status = CurrentUserData::getFriendStatus($data['currentUserData'][0]->userID, $data['status_data'][0]->status_userID);
            }
          }else{
            $friends_status = "None";
          }
          /** Check to see if Current User can view status **/
          if($friends_status == "Friends"){
            /* Send data to view */
            Load::ViewPlugin("view_comments_status", $data, "", "Comments");
          }else{
            /** Comment Not Valid - Show Error Message **/
            ErrorMessages::push('You are not friends with the user that posted that status.', '');
          }
        }else{
          /** Comment Not Valid - Show Error Message **/
          ErrorMessages::push('No Comments were Found that match the ID entered.', '');
        }
      }else{
        /** Comment Not Valid - Show Error Message **/
        ErrorMessages::push('No Comments were Found that match the ID entered.', '');
      }




    }

  }
