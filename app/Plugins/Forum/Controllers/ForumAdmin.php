<?php
/**
* UserApplePie v4 Forum Admin Controller Plugin
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.1 for UAP v.4.2.1
*/

/** Forum Admin Panel Controller **/

namespace App\Plugins\Forum\Controllers;

use App\System\Controller,
    App\System\Load,
    Libs\Auth\Auth,
    Libs\Csrf,
    Libs\Request,
    App\Models\AdminPanel as AdminPanelModel;

class ForumAdmin extends Controller{

  /* Ready the Forum Admin Model */
  private $forum;
  private $model;

  public function __construct(){
    parent::__construct();
    $this->forum = new \App\Plugins\Forum\Models\ForumAdmin();
    $this->model = new AdminPanelModel();
  }

  /**
  * forum_settings
  *
  * Function that handles all the Admin Functions for Forum Settings
  */
  public function forum_settings(){
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

    // Check to make sure admin is trying to update user profile
		if(isset($_POST['submit'])){
      /* Check to see if site is a demo site */
      if(DEMO_SITE != 'TRUE'){
  			// Check to make sure the csrf token is good
  			if (Csrf::isTokenValid('ForumAdmin')) {
          // Check to see if admin is editing forum global settings
          if($_POST['update_global_settings'] == "true"){
            // Get data from post
            $forum_on_off = Request::post('forum_on_off');
            $forum_title = Request::post('forum_title');
            $forum_description = Request::post('forum_description');
            $forum_topic_limit = Request::post('forum_topic_limit');
            $forum_topic_reply_limit = Request::post('forum_topic_reply_limit');
            $forum_posts_group_change_enable = Request::post('forum_posts_group_change_enable');
            $forum_posts_group_change = Request::post('forum_posts_group_change');
            $forum_max_image_size = Request::post('forum_max_image_size');
            // Run Forum Settings Update
            if($this->forum->updateGlobalSettings($forum_on_off,$forum_title,$forum_description,$forum_topic_limit,$forum_topic_reply_limit,$forum_posts_group_change_enable,$forum_posts_group_change,$forum_max_image_size)){
              // Success
              \Libs\SuccessMessages::push('You Have Successfully Updated Forum Global Settings', 'AdminPanel-Forum-Settings');
            }else{
              $errors[] = "There was an Error Updating Forum Global Settings";
            }
          }
          // Check to see if admin is editing forum groups
          if($_POST['remove_group_user'] == "true"){
            $forum_edit_group = "users";
            $forum_edit_group_action = "remove";
          }else if($_POST['add_group_user'] == "true"){
            $forum_edit_group = "users";
            $forum_edit_group_action = "add";
          }else if($_POST['remove_group_mod'] == "true"){
            $forum_edit_group = "mods";
            $forum_edit_group_action = "remove";
          }else if($_POST['add_group_mod'] == "true"){
            $forum_edit_group = "mods";
            $forum_edit_group_action = "add";
          }else if($_POST['remove_group_admin'] == "true"){
            $forum_edit_group = "admins";
            $forum_edit_group_action = "remove";
          }else if($_POST['add_group_admin'] == "true"){
            $forum_edit_group = "admins";
            $forum_edit_group_action = "add";
          }
          if(isset($forum_edit_group) && isset($forum_edit_group_action)){
            // Get data from post
            $groupID = Request::post('groupID');
            // Updates current user's group
            if($this->forum->editForumGroup($forum_edit_group, $forum_edit_group_action, $groupID)){
              // Success
              \Libs\SuccessMessages::push('You Have Successfully Updated Forum Group ('.$forum_edit_group.')', 'AdminPanel-Forum-Settings');
            }else{
              // Fail
              $error[] = "Edit Forum Group Failed";
            }
          }
        }
      }else{
      	/* Error Message Display */
      	\Libs\ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum-Settings');
      }
    }


    // Get data for users
    $data['current_page'] = $_SERVER['REQUEST_URI'];
    $data['title'] = "Forum Global Settings";
    $data['welcome_message'] = "Welcome to the Forum Settings Admin Panel";

    // Get data for global forum settings
    $data['forum_on_off'] = $this->forum->globalForumSetting('forum_on_off');
    $data['forum_title'] = $this->forum->globalForumSetting('forum_title');
    $data['forum_description'] = $this->forum->globalForumSetting('forum_description');
    $data['forum_topic_limit'] = $this->forum->globalForumSetting('forum_topic_limit');
    $data['forum_topic_reply_limit'] = $this->forum->globalForumSetting('forum_topic_reply_limit');
    $data['forum_posts_group_change_enable'] = $this->forum->globalForumSetting('forum_posts_group_change_enable');
    $data['forum_posts_group_change'] = $this->forum->globalForumSetting('forum_posts_group_change');
    $data['forum_max_image_size'] = $this->forum->globalForumSetting('forum_max_image_size');

    // Get user groups data
    $data_groups = $this->model->getAllGroups();
    ////////////////////////////////////////////////////////////////////////////
    // Forum Users
    // Get groups forum user is and is not member of
    foreach ($data_groups as $value) {
      $data_forum_users_groups = $this->forum->checkGroupForum('users', $value->groupID);
      if($data_forum_users_groups){
        $f_users_member[] = $value->groupID;
      }else{
        $f_users_notmember[] = $value->groupID;
      }
    }
    // Gether group data for group user is member of
    if(isset($f_users_member)){
      foreach ($f_users_member as $value) {
        $f_users_member_data[] = $this->model->getGroupData($value);
      }
    }else{
        $f_users_member_data = "";
    }
    // Push group data to view
    $data['f_users_member_groups'] = $f_users_member_data;
    // Gether group data for group user is not member of
    if(isset($f_users_notmember)){
      foreach ($f_users_notmember as $value) {
        $f_users_notmember_groups[] = $this->model->getGroupData($value);
      }
    }else{
        $f_users_notmember_groups = "";
    }
    // Push group data to view
    $data['f_users_notmember_groups'] = $f_users_notmember_groups;
    ////////////////////////////////////////////////////////////////////////////
    // Forum Mods
    // Get groups forum user is and is not member of
    foreach ($data_groups as $value) {
      $data_forum_mods_groups = $this->forum->checkGroupForum('mods', $value->groupID);
      if($data_forum_mods_groups){
        $f_mods_member[] = $value->groupID;
      }else{
        $f_mods_notmember[] = $value->groupID;
      }
    }
    // Gether group data for group user is member of
    if(isset($f_mods_member)){
      foreach ($f_mods_member as $value) {
        $f_mods_member_data[] = $this->model->getGroupData($value);
      }
    }else{
        $f_mods_member_data = "";
    }
    // Push group data to view
    $data['f_mods_member_groups'] = $f_mods_member_data;
    // Gether group data for group user is not member of
    if(isset($f_mods_notmember)){
      foreach ($f_mods_notmember as $value) {
        $f_mods_notmember_groups[] = $this->model->getGroupData($value);
      }
    }
    // Push group data to view
    $data['f_mods_notmember_groups'] = $f_mods_notmember_groups;
    ////////////////////////////////////////////////////////////////////////////
    // Forum Admins
    // Get groups forum user is and is not member of
    foreach ($data_groups as $value) {
      $data_forum_admins_groups = $this->forum->checkGroupForum('admins', $value->groupID);
      if($data_forum_admins_groups){
        $f_admins_member[] = $value->groupID;
      }else{
        $f_admins_notmember[] = $value->groupID;
      }
    }
    // Gether group data for group user is member of
    if(isset($f_admins_member)){
      foreach ($f_admins_member as $value) {
        $f_admins_member_data[] = $this->model->getGroupData($value);
      }
    }else{
        $f_admins_member_data = "";
    }
    // Push group data to view
    $data['f_admins_member_groups'] = $f_admins_member_data;
    // Gether group data for group user is not member of
    if(isset($f_admins_notmember)){
      foreach ($f_admins_notmember as $value) {
        $f_admins_notmember_groups[] = $this->model->getGroupData($value);
      }
    }
    // Push group data to view
    $data['f_admins_notmember_groups'] = $f_admins_notmember_groups;
    ////////////////////////////////////////////////////////////////////////////

    // Setup CSRF token
    $data['csrf_token'] = Csrf::makeToken('ForumAdmin');

    // Setup Breadcrumbs
    $data['breadcrumbs'] = "
      <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li class='breadcrumb-item active'><i class='fa fa-fw fa-cog'></i> ".$data['title']."</li>
    ";

    Load::ViewPlugin("forum_settings", $data, "", "Forum", "AdminPanel");
  }

  /**
  * forum_categories
  *
  * Function that handles all the Admin Functions for Forum Categories
  *
  * @param string $action - action to take within function
  * @param int/string
  * @param int/string
  *
  */
  public function forum_categories($action = null, $id = null, $id2 = null){

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

    // Get data for users
    $data['current_page'] = $_SERVER['REQUEST_URI'];
    $data['title'] = "Forum Categories";

    // Check to see if there is an action
    if($action != null && $id != null){
      // Check to see if action is edit
      if($action == 'CatMainEdit'){
        // Check to make sure admin is trying to update
        if(isset($_POST['submit'])){
          /* Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            // Check to make sure the csrf token is good
            if (Csrf::isTokenValid('ForumAdmin')) {
              if($_POST['action'] == "update_cat_main_title"){
                // Catch password inputs using the Request helper
                $new_forum_title = Request::post('forum_title');
                $prev_forum_title = Request::post('prev_forum_title');
                if($this->forum->updateCatMainTitle($prev_forum_title,$new_forum_title)){
                  // Success
                  \Libs\SuccessMessages::push('You Have Successfully Updated Forum Main Category Title to <b>'.$new_forum_title.'</b>', 'AdminPanel-Forum-Categories');
                }else{
                  // Fail
                  $error[] = "Edit Forum Main Category Failed";
                }
              }
            }
          }else{
          	/* Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum-Categories');
          }
        }else{
          // Get data for CatMainEdit Form
          $data['edit_cat_main'] = true;
          $data['data_cat_main'] = $this->forum->getCatMain($id);

          $data['welcome_message'] = "You are about to Edit Selected Forum Main Category.";

          // Setup Breadcrumbs
          $data['breadcrumbs'] = "
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Forum-Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
            <li class='breadcrumb-item active'><i class='fa fa-fw fa-pencil'></i> Edit Main Category</li>
          ";
        }
      }else if($action == "CatMainUp"){
        if($this->forum->moveUpCatMain($id)){
          // Success
          \Libs\SuccessMessages::push('You Have Successfully Moved Up Forum Main Category', 'AdminPanel-Forum-Categories');
        }else{
          // Fail
          $error[] = "Move Up Forum Main Category Failed";
        }
      }else if($action == "CatMainDown"){
        if($this->forum->moveDownCatMain($id)){
          // Success
          \Libs\SuccessMessages::push('You Have Successfully Moved Down Forum Main Category', 'AdminPanel-Forum-Categories');
        }else{
          // Fail
          $error[] = "Move Down Forum Main Category Failed";
        }
      }else if($action == 'CatMainNew'){
        // Check to make sure admin is trying to update
        if(isset($_POST['submit'])){
          /* Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            // Check to make sure the csrf token is good
            if (Csrf::isTokenValid('ForumAdmin')) {
              // Add new cate main title to database
              if($_POST['action'] == "new_cat_main_title"){
                // Catch inputs using the Request helper
                $forum_title = Request::post('forum_title');
                // Get last order title number from db
                $last_order_num = $this->forum->getLastCatMain();
                // Attempt to add new Main Category Title to DB
                if($this->forum->newCatMainTitle($forum_title,'forum',$last_order_num)){
                  // Success
                  \Libs\SuccessMessages::push('You Have Successfully Created New Forum Main Category Title <b>'.$new_forum_title.'</b>', 'AdminPanel-Forum-Categories');
                }else{
                  // Fail
                  $error[] = "New Forum Main Category Failed";
                }
              }
            }
          }else{
          	/* Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum-Categories');
          }
        }
      }else if($action == "CatSubList"){
        // Check to make sure admin is trying to update
        if(isset($_POST['submit'])){
          /* Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            // Check to make sure the csrf token is good
            if (Csrf::isTokenValid('ForumAdmin')) {
              // Add new cate main title to database
              if($_POST['action'] == "new_cat_sub"){
                // Catch inputs using the Request helper
                $forum_title = Request::post('forum_title');
                $forum_cat = Request::post('forum_cat');
                $forum_des = Request::post('forum_des');
                // Check to see if we are adding to a new main cat
                if($this->forum->checkSubCat($forum_title)){
                  // Get last cat sub order id
                  $last_cat_order_id = $this->forum->getLastCatSub($forum_title);
                  // Get forum order title id
                  $forum_order_title = $this->forum->getForumOrderTitle($forum_title);
                  // Run insert for new sub cat
                  $run_sub_cat = $this->forum->newSubCat($forum_title,$forum_cat,$forum_des,$last_cat_order_id,$forum_order_title);
                }else{
                  // Run update for new main cat
                  $run_sub_cat = $this->forum->updateSubCat($id,$forum_cat,$forum_des);
                }
                // Attempt to update/insert sub cat in db
                if($run_sub_cat){
                  // Success
                  \Libs\SuccessMessages::push('You Have Successfully Created Forum Sub Category', 'AdminPanel-Forum-Categories/CatSubList/'.$id);
                }else{
                  // Fail
                  $error[] = "Create Forum Sub Category Failed";
                }
              }
            }
          }else{
          	/* Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum-Categories');
          }
        }else{
          // Set goods for Forum Sub Categories Listing
          $data['cat_sub_list'] = true;
          $data['cat_main_title'] = $this->forum->getCatMain($id);
          $data['cat_sub_titles'] = $this->forum->getCatSubs($data['cat_main_title']);
          $data['fourm_cat_sub_last'] = $this->forum->getLastCatSub($data['cat_main_title']);

          $data['welcome_message'] = "You are viewing a complete list of sub categories for requeted main category.";

          // Setup Breadcrumbs
          $data['breadcrumbs'] = "
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Forum-Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
            <li class='breadcrumb-item active'><i class='fa fa-fw fa-pencil'></i> Sub Categories List</li>
          ";
        }
      }else if($action == "CatSubEdit"){
        // Check to make sure admin is trying to update
        if(isset($_POST['submit'])){
          /* Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            // Check to make sure the csrf token is good
            if (Csrf::isTokenValid('ForumAdmin')) {
              // Add new cate main title to database
              if($_POST['action'] == "edit_cat_sub"){
                // Catch inputs using the Request helper
                $forum_cat = Request::post('forum_cat');
                $forum_des = Request::post('forum_des');
                // Attempt to update sub cat in db
                if($this->forum->updateSubCat($id,$forum_cat,$forum_des)){
                  // Success
                  \Libs\SuccessMessages::push('You Have Successfully Updated Forum Sub Category', 'AdminPanel-Forum-Categories/CatSubList/'.$id);
                }else{
                  // Fail
                  $error[] = "Update Forum Sub Category Failed";
                }
              }
            }
          }else{
          	/* Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum-Categories');
          }
        }else{
          // Display Edit Forum for Selected Sub Cat
          $data['cat_sub_edit'] = true;
          $data['cat_sub_data'] = $this->forum->getCatSubData($id);

          $data['welcome_message'] = "You are about to edit requeted sub category.";

          // Setup Breadcrumbs
          $data['breadcrumbs'] = "
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Forum-Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Forum-Categories/CatSubList/$id'><i class='fa fa-fw fa-list'></i> Sub Categories List</a></li>
            <li class='breadcrumb-item active'><i class='fa fa-fw fa-pencil'></i> Edit Sub Category</li>
          ";
        }
      }else if($action == "DeleteSubCat"){
        // Check to make sure admin is trying to update
        if(isset($_POST['submit'])){
          /* Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            // Check to make sure the csrf token is good
            if (Csrf::isTokenValid('ForumAdmin')) {
              // Add new cate main title to database
              if($_POST['action'] == "delete_cat_sub"){
                // Catch inputs using the Request helper
                $delete_cat_sub_action = Request::post('delete_cat_sub_action');

                // Get title basted on forum_id
                $forum_title = $this->forum->getCatMain($id);

                // Get title basted on forum_cat
                $forum_cat = $this->forum->getCatSub($id);

                // Check to see what delete function admin has selected
                if($delete_cat_sub_action == "delete_all"){
                  // Admin wants to delete Sub Cat and Everything Within it
                  // First we delete all related topic Replies
                  if($this->forum->deleteTopicsForumID($id)){
                    $success_count = $success_count + 1;
                  }
                  // Second we delete all topics
                  if($this->forum->deleteTopicRepliesForumID($id)){
                    $success_count = $success_count + 1;
                  }
                  // Finally we delete the main cat and all related sub cats
                  if($this->forum->deleteCatForumID($id)){
                    $success_count = $success_count + 1;
                  }
                  // Check to see if everything was deleted Successfully
                  if($success_count > 0){
                    // Success
                    \Libs\SuccessMessages::push('You Have Successfully Deleted Sub Category: <b>'.$forum_title.' > '.$forum_cat.'</b> and Everything Within it!', 'AdminPanel-Forum-Categories');
                  }
                }else{
                  // Extract forum_id from move_to_# string
                  $forum_id = str_replace("move_to_", "", $delete_cat_sub_action);
                  if(!empty($forum_id)){
                    // First Update Topic Replies forum_id
                    if($this->forum->updateTopicRepliesForumID($id, $forum_id)){
                      $success_count = $success_count + 1;
                    }
                    // Second Update Topics forum_id
                    if($this->forum->updateTopicsForumID($id, $forum_id)){
                      $success_count = $success_count + 1;
                    }
                    // Last delete the sub Category
                    if($this->forum->deleteCatForumID($id)){
                      $success_count = $success_count + 1;
                    }
                    // Check to see if anything was done
                    if($success_count > 0){
                      // Success
                      \Libs\SuccessMessages::push('You Have Successfully Moved Main Category From <b>'.$old_forum_title.'</b> to <b>'.$new_forum_title.'</b>', 'AdminPanel-Forum-Categories/CatSubList/'.$forum_id);
                    }
                  }else{
                    // User has not selected to delete or move main cat
                    \Libs\ErrorMessages::push('No Action Selected.  No actions executed.', 'AdminPanel-Forum-Categories/DeleteSubCat/'.$id);
                  }
                }

              }
            }
          }else{
          	/* Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum-Categories');
          }
        }else{
          // Display Delete Cat Sub Form
          $data['delete_cat_sub'] = true;

          // Get list of all sub cats except current
          $data['list_all_cat_sub'] = $this->forum->catSubListExceptSel($id);

          // Setup Breadcrumbs
          $data['breadcrumbs'] = "
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Forum-Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Forum-Categories/CatSubList/".$id."'><i class='fa fa-fw fa-list'></i> Sub Categories List</a></li>
            <li class='breadcrumb-item active'><i class='fa fa-fw fa-pencil'></i> Delete Sub Category</li>
          ";
        }
      }else if($action == "CatSubUp"){
        // Get forum_title for cat
        $data['cat_main_title'] = $this->forum->getCatMain($id);
        // Try to move up
        if($this->forum->moveUpCatSub($data['cat_main_title'],$id2)){
          // Success
          \Libs\SuccessMessages::push('You Have Successfully Moved Up Forum Sub Category', 'AdminPanel-Forum-Categories/CatSubList/'.$id);
        }else{
          // Fail
          $error[] = "Move Up Forum Main Category Failed";
        }
      }else if($action == "CatSubDown"){
        // Get forum_title for cat
        $data['cat_main_title'] = $this->forum->getCatMain($id);
        // Try to move down
        if($this->forum->moveDownCatSub($data['cat_main_title'],$id2)){
          // Success
          \Libs\SuccessMessages::push('You Have Successfully Moved Down Forum Sub Category', 'AdminPanel-Forum-Categories/CatSubList/'.$id);
        }else{
          // Fail
          $error[] = "Move Down Forum Main Category Failed";
        }
      }else if($action == "DeleteMainCat"){
        // Check to make sure admin is trying to update
        if(isset($_POST['submit'])){
          /* Check to see if site is a demo site */
          if(DEMO_SITE != 'TRUE'){
            // Check to make sure the csrf token is good
            if (Csrf::isTokenValid('ForumAdmin')) {
              // Add new cate main title to database
              if($_POST['action'] == "delete_cat_main"){
                // Catch inputs using the Request helper
                $delete_cat_main_action = Request::post('delete_cat_main_action');

                // Get title basted on forum_id
                $forum_title = $this->forum->getCatMain($id);

                // Check to see what delete function admin has selected
                if($delete_cat_main_action == "delete_all"){
                  // Admin wants to delete Main Cat and Everything Within it
                  // Get list of all forum_id's for this Main Cat
                  $forum_id_all = $this->forum->getAllForumTitleIDs($forum_title);
                  $success_count = "0";
                  if(isset($forum_id_all)){
                    foreach ($forum_id_all as $row) {
                      // First we delete all related topic Replies
                      if($this->forum->deleteTopicsForumID($row->forum_id)){
                        $success_count = $success_count + 1;
                      }
                      // Second we delete all topics
                      if($this->forum->deleteTopicRepliesForumID($row->forum_id)){
                        $success_count = $success_count + 1;
                      }
                      // Finally we delete the main cat and all related sub cats
                      if($this->forum->deleteCatForumID($row->forum_id)){
                        $success_count = $success_count + 1;
                      }
                    }
                  }
                  if($success_count > 0){
                    // Success
                    \Libs\SuccessMessages::push('You Have Successfully Deleted Main Category: <b>'.$forum_title.'</b> and Everything Within it!', 'AdminPanel-Forum-Categories');
                  }
                }else{
                  // Extract forum_id from move_to_# string
                  $forum_id = str_replace("move_to_", "", $delete_cat_main_action);
                  if(!empty($forum_id)){
                    // Get new and old forum titles
                    $new_forum_title = $this->forum->getCatMain($forum_id);
                    $old_forum_title = $this->forum->getCatMain($id);
                    // Get forum_order_title id for forum_title we are moving to
                    $new_forum_order_title = $this->forum->getForumOrderTitle($new_forum_title);
                    // Get last order id for new forum_title we are moving to
                    $new_forum_order_cat = $this->forum->getLastCatSub($new_forum_title);
                    // Update with the new forum title from the old one
                    if($this->forum->moveForumSubCat($old_forum_title,$new_forum_title,$new_forum_order_title,$new_forum_order_cat)){
                      // Success
                      \Libs\SuccessMessages::push('You Have Successfully Moved Main Category From <b>'.$old_forum_title.'</b> to <b>'.$new_forum_title.'</b>', 'AdminPanel-Forum-Categories/CatSubList/'.$forum_id);
                    }
                  }else{
                    // User has not selected to delete or move main cat
                    \Libs\ErrorMessages::push('No Action Selected.  No actions executed.', 'AdminPanel-Forum-Categories/DeleteMainCat/'.$id);
                  }
                }

              }
            }
          }else{
          	/* Error Message Display */
          	\Libs\ErrorMessages::push('Demo Limit - Forum Settings Disabled', 'AdminPanel-Forum-Categories');
          }
        }else{
          // Show delete options for main cat
          $data['delete_cat_main'] = true;
          $data['welcome_message'] = "You are about to delete requested main category.  Please proceed with caution.";
          // Get title for main cat admin is about to delete
          $data['delete_cat_main_title'] = $this->forum->getCatMain($id);
          // Get all other main cat titles
          $data['list_all_cat_main'] = $this->forum->catMainListExceptSel($data['delete_cat_main_title']);

          // Setup Breadcrumbs
          $data['breadcrumbs'] = "
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
            <li class='breadcrumb-item'><a href='".DIR."AdminPanel-Forum-Categories'><i class='fa fa-fw fa-list'></i> ".$data['title']."</a></li>
            <li class='breadcrumb-item active'><i class='fa fa-fw fa-pencil'></i> Delete Main Category</li>
          ";
        }
      }
    }else{
      // Get data for main categories
      $data['cat_main'] = $this->forum->catMainList();

      // Welcome message
      $data['welcome_message'] = "You are viewing a complete list of main categories.";

      // Setup Breadcrumbs
      $data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
        <li class='breadcrumb-item active'><i class='fa fa-fw fa-list'></i> ".$data['title']."</li>
      ";
    }

    // Clean up for errors messages
    if(!isset($data['edit_cat_main'])){ $data['edit_cat_main'] = ""; }
    if(!isset($data['cat_sub_list'])){ $data['cat_sub_list'] = ""; }
    if(!isset($data['cat_sub_edit'])){ $data['cat_sub_edit'] = ""; }
    if(!isset($data['delete_cat_main'])){ $data['delete_cat_main'] = ""; }
    if(!isset($data['delete_cat_sub'])){ $data['delete_cat_sub'] = ""; }

    // Get Last main cat order number
    $data['fourm_cat_main_last'] = $this->forum->getLastCatMain();

    // Setup CSRF token
    $data['csrf_token'] = Csrf::makeToken('ForumAdmin');

    Load::ViewPlugin("forum_categories", $data, "", "Forum", "AdminPanel");
  }

  public function forum_blocked(){
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

    // Get data for dashboard
    $data['current_page'] = $_SERVER['REQUEST_URI'];
    $data['title'] = "Forum Blocked Content";
    $data['welcome_message'] = "Welcom to the Admin Panel Blocked Content Listing!";

    // Get list of blocked topics
    $data['blocked_topics'] = $this->forum->getBlockedTopics();

    // Get list of blocked topic replies
    $data['blocked_replies'] = $this->forum->getBlockedReplies();

    // Setup Breadcrumbs
    $data['breadcrumbs'] = "
      <li class='breadcrumb-item'><a href='".DIR."AdminPanel'><i class='fa fa-fw fa-cog'></i> Admin Panel</a></li>
      <li class='breadcrumb-item active'><i class='fa fa-fw fa-ban'></i> ".$data['title']."</li>
    ";

    Load::ViewPlugin("forum_blocked", $data, "", "Forum", "AdminPanel");

  }

}
