<?php
/**
* UserApplePie v4 Forum Controller Plugin
*
* UserApplePie - Forum Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.2 for UAP v.4.3.0
*/

/** Forum controller **/

namespace App\Plugins\Forum\Controllers;

use App\System\Controller,
  App\System\Load,
  Libs\Auth\Auth,
  Libs\Csrf,
  Libs\Request,
  Libs\Url,
  Libs\SuccessMessages,
  Libs\ErrorMessages,
  Libs\PageViews,
  Libs\Sweets,
  Libs\SimpleImage;

  class Forum extends Controller{

  	private $model;
    private $pagesTopic;
    private $pagesReply;
    private $forum_title;
    private $forum_description;
    private $forum_topic_limit;
    private $forum_topic_reply_limit;
    private $forum_max_image_size;

  	public function __construct(){
  		parent::__construct();
  		$this->model = new \App\Plugins\Forum\Models\Forum();
        // Get data for global forum settings
        $this->forum_title = $this->model->globalForumSetting('forum_title');
        $this->forum_description = $this->model->globalForumSetting('forum_description');
        $this->forum_topic_limit = $this->model->globalForumSetting('forum_topic_limit');
        $this->forum_topic_reply_limit = $this->model->globalForumSetting('forum_topic_reply_limit');
        $this->forum_max_image_size = $this->model->globalForumSetting('forum_max_image_size');
        $this->pagesTopic = new \Libs\Paginator($this->forum_topic_limit);
        $this->pagesReply = new \Libs\Paginator($this->forum_topic_reply_limit);
  	}

    // Forum Home Page Display
    public function forum(){

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            /** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            $u_id = "";
        }

    	// Collect Data for view
    	$data['title'] = $this->forum_title;
    	$data['welcome_message'] = $this->forum_description;

        // Get list of all forum categories
        $data['forum_categories'] = $this->model->forum_categories();

        // Get list of all forum categories
        $data['forum_titles'] = $this->model->forum_titles();

        // Output current user's ID
        $data['current_userID'] = $u_id;

        // Get Recent Posts List for Sidebar
        $data['forum_recent_posts'] = $this->model->forum_recent_posts();

        // Setup Breadcrumbs
        	$data['breadcrumbs'] = "
        		<li class='breadcrumb-item active'>".$this->forum_title."</li>
        	";
        $data['csrf_token'] = Csrf::makeToken('forum');

        /* Get user's forum groups data */
        $data['group_forum_perms_post'] = $this->model->group_forum_perms($u_id, "users");
        $data['group_forum_perms_mod'] = $this->model->group_forum_perms($u_id, "mods");
        $data['group_forum_perms_admin'] = $this->model->group_forum_perms($u_id, "admins");

        /* Send data to view */
        Load::ViewPlugin("forum_home", $data, "forum_sidebar::Right", "Forum");
    }

    // Forum Topic List Display
    public function topics($id = null, $current_page = null){

      /** Check to see if user entered a Topic ID **/
      if(!$id){
        ErrorMessages::push('The Forum Topics entered do not exist!', 'Forum');
      }

      /** Check to see if user is logged in **/
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data **/
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
      }else{
          $u_id = "";
      }

      // Get Requested Topic's Title and Description
      $data['forum_title'] = $this->model->forum_title($id);
      $data['forum_cat'] = $this->model->forum_cat($id);
      $data['forum_cat_des'] = $this->model->forum_cat_des($id);
      $data['forum_topics'] = $this->model->forum_topics($id, $this->pagesTopic->getLimit($current_page, $this->forum_topic_limit));

      // Set total number of messages for paginator
      $total_num_topics = count($this->model->forum_topics($id));
      $this->pagesTopic->setTotal($total_num_topics);

      // Send page links to view
      $pageFormat = SITE_URL."Topics/$id/"; // URL page where pages are
      $data['pageLinks'] = $this->pagesTopic->pageLinks($pageFormat, null, $current_page);

  		// Collect Data for view
  		$data['title'] = $data['forum_cat'];
  		$data['welcome_message'] = $data['forum_cat_des'];

      // Output current user's ID
      $data['current_userID'] = $u_id;

      // Output current topic ID
      $data['current_topic_id'] = $id;

      // Get Recent Posts List for Sidebar
      $data['forum_recent_posts'] = $this->model->forum_recent_posts();

      // Setup Breadcrumbs
  		$data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."Forum'>".$this->forum_title."</a></li>
  			<li class='breadcrumb-item active'>".$data['title']."</li>
  		";

      // Ready the token!
      $data['csrf_token'] = Csrf::makeToken('forum');

      /* Get user's forum groups data */
      $data['group_forum_perms_post'] = $this->model->group_forum_perms($u_id, "users");
      $data['group_forum_perms_mod'] = $this->model->group_forum_perms($u_id, "mods");
      $data['group_forum_perms_admin'] = $this->model->group_forum_perms($u_id, "admins");

        /* Send data to view */
        Load::ViewPlugin("topics", $data, "forum_sidebar::Right", "Forum");
    }

    // Forum Topic Display
    public function topic($id = null, $current_page = null){

        /** Check to see if user entered a Topic ID **/
        if(!$id){
          ErrorMessages::push('The Forum Topic entered does not exist!', 'Forum');
        }

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            /** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            $u_id = "";
        }

        // Get Cat ID for this topic
        $topic_forum_id = $this->model->forum_topic_cat_id($id);

        // Get Requested Topic's Title and Description
        $data['forum_cat'] = $this->model->forum_cat($topic_forum_id);
        $data['forum_cat_des'] = $this->model->forum_cat_des($topic_forum_id);
        $data['forum_topics'] = $this->model->forum_topics($topic_forum_id);

        // Get Requested Topic Information
        $data['topic_id'] = $id;
        $data['title'] = $this->model->topic_title($id);
        $data['topic_creator'] = $this->model->topic_creator($id);
        $data['topic_date'] = $this->model->topic_date($id);
        $data['topic_content'] = $this->model->topic_content($id);
        $data['topic_edit_date'] = $this->model->topic_edit_date($id);
        $data['topic_status'] = $this->model->topic_status($id);
        $data['topic_allow'] = $this->model->topic_allow($id);

        // Get hidden information if there is any
        $data['hidden_userID'] = $this->model->topic_hidden_userID($id);
        $data['hidden_reason'] = $this->model->topic_hidden_reason($id);
        $data['hidden_timestamp'] = $this->model->topic_hidden_timestamp($id);

        // Check to see if current user owns the origianal post
        $data['current_userID'] = $u_id;
        $data['topic_userID'] = $this->model->topic_userID($id);

        // Get current page number
        if($current_page > 1){
            $data['current_page'] = $current_page;
        }else{
            $data['current_page'] = "1";
        }

        // Get set ammount of replies per page
        $data['topic_reply_limit'] = $this->forum_topic_reply_limit;

        // Check to see if current user is admin
        $data['is_admin'] = $this->auth->checkIsAdmin($u_id);

        // Check to see if current user is moderator
        $data['is_mod'] = $this->auth->checkIsMod($u_id);

        // Check to see if current user is a new user
        $data['is_new_user'] = $this->auth->checkIsNewUser($u_id);

        // Get replys that are related to Requested Topic
        $data['topic_replys'] = $this->model->forum_topic_replys($id, $this->pagesReply->getLimit($current_page, $this->forum_topic_reply_limit));

        // Check to see if user has posted on this topic
        $data['checkUserPosted'] = $this->model->checkUserPosted($id, $u_id);

        // If user has not yet posted, then we set subcribe to true for new posts
        if($data['checkUserPosted'] == true){
            // Check to see if current user is subscribed to this topic
            $data['is_user_subscribed'] = $this->model->checkTopicSubscribe($id, $u_id);
        }else{
            $data['is_user_subscribed'] = true;
        }

        // Track user activity for current topic
        $data['update_tracking'] = $this->model->forum_tracker($u_id,$id,$topic_forum_id);

        // Set total number of messages for paginator
        $total_num_replys = $this->model->getTotalReplys($id);
        $this->pagesReply->setTotal($total_num_replys);

        // Send page links to view
        $pageFormat = SITE_URL."Topic/$id/"; // URL page where pages are
        $data['pageLinks'] = $this->pagesReply->pageLinks($pageFormat, null, $current_page);

        // Get related images if any
        $data['forum_topic_images'] = $this->model->getForumImagesTopic($id);

        // Get Action from POST
        (isset($_POST['action'])) ? $data['action'] = Request::post('action') : $data['action'] = "";
        (isset($_POST['edit_reply_id'])) ? $data['edit_reply_id'] = Request::post('edit_reply_id') : $data['edit_reply_id'] = "";

        // Check to see if user is submitting a new topic reply
  		if(isset($_POST['submit'])){

  			// Check to make sure the csrf token is good
  			if (Csrf::isTokenValid('forum')) {

          // Check to see if user is editing topic
          if($data['action'] == "update_topic"){
            // Get data from post
    				$data['forum_content'] = htmlspecialchars(Request::post('forum_content'));
            $data['forum_title'] = strip_tags(Request::post('forum_title'));
              // Check to make sure user completed all required fields in form
              if(empty($data['forum_title'])){
                // Subject field is empty
                $error[] = 'Topic Title Field is Blank!';
              }
              if(empty($data['forum_content'])){
                // Subject field is empty
                $error[] = 'Topic Content Field is Blank!';
              }
              // Check to make sure user owns the content they are trying to edit
              // Get the id of the user that owns the post that is getting edited
              if($u_id != $this->model->getTopicOwner($id)){
                // User does not own this content
                $error[] = 'You Do Not Own The Content You Were Trying To Edit!';
              }
              // Check for errors before sending message
              if(!isset($error)){
                  // No Errors, lets submit the new topic to db
          				if($this->model->updateTopic($id, $data['forum_title'], $data['forum_content'])){
          					// Success
                    SuccessMessages::push('You Have Successfully Updated a Topic', 'Topic/'.$id);
          				}else{
          					// Fail
                    $error[] = 'Edit Topic Failed';
          				}
              }// End Form Complete Check
          }
          // Check to see if user is editing or creating topic reply
          else if($data['action'] == "update_reply"){
            // Get data from post
    				$data['fpr_content'] = htmlspecialchars(Request::post('fpr_content'));
              // Check to make sure user completed all required fields in form
              if(empty($data['fpr_content'])){
                // Subject field is empty
                $error[] = 'Topic Reply Content Field is Blank!';
              }
              // Check to make sure user owns the content they are trying to edit
              // Get the id of the user that owns the post that is getting edited
              if($u_id != $this->model->getReplyOwner($data['edit_reply_id'])){
                // User does not own this content
                $error[] = 'You Do Not Own The Content You Were Trying To Edit!';
              }
              // Check for errors before sending message
              if(!isset($error)){
                  // No Errors, lets submit the new topic to db
          				if($this->model->updateTopicReply($data['edit_reply_id'], $data['fpr_content'])){
          					// Success
                    SuccessMessages::push('You Have Successfully Updated a Topic Reply', 'Topic/'.$id.'/'.$redirect_page_num.'#topicreply'.$data['edit_reply_id']);
          				}else{
          					// Fail
                    $error[] = 'Edit Topic Reply Failed';
          				}
              }// End Form Complete Check
          }else if($data['action'] == "new_reply"){
    				// Get data from post
    				$data['fpr_content'] = htmlspecialchars(Request::post('fpr_content'));
              // Check to make sure user completed all required fields in form
              if(empty($data['fpr_content'])){
                // Subject field is empty
                $error[] = 'Topic Reply Content Field is Blank!';
              }
              // Check for errors before sending message
              if(!isset($error)){
                  // No Errors, lets submit the new topic to db
          				if($this->model->sendTopicReply($u_id, $id, $topic_forum_id, $data['fpr_content'], $data['is_user_subscribed'], "1")){
                    // Get Submitted Reply ID
                    $reply_id = $this->model->lastTopicReplyID($id);
                    // Check to see if post is going on a new page
                    $page_reply_limit = $this->forum_topic_reply_limit;
                    $redirect_page_num = ceil(($total_num_replys + 1) / $page_reply_limit);
                    // Send emails to those who are subscribed to this topic
                    $this->model->sendTopicSubscribeEmails($id, $u_id, $data['title'], $data['forum_cat'], $data['fpr_content']);

                    // Check for image upload with this topic
                    $picture = file_exists($_FILES['forumImage']['tmp_name']) || is_uploaded_file($_FILES['forumImage']['tmp_name']) ? $_FILES['forumImage'] : array ();
                      // Make sure image is being uploaded before going further
                      if(sizeof($picture)>0 && ($data['is_new_user'] != true)){
                        // Get image size
                        $check = getimagesize ( $picture['tmp_name'] );
                        // Get file size for db
                        $file_size = $picture['size'];
                        $img_dir_forum_reply = IMG_DIR_FORUM_REPLY;
                        // Make sure image size is not too large
                        if($picture['size'] < 5000000 && $check && ($check['mime'] == "image/jpeg" || $check['mime'] == "image/png" || $check['mime'] == "image/gif")){
                          if(!file_exists(ROOTDIR.$img_dir_forum_reply)){
                            mkdir(ROOTDIR.$img_dir_forum_reply,0777,true);
                          }
                          // Upload the image to server
                          $image = new SimpleImage($picture['tmp_name']);
                          $new_image_name = "forum-image-topic-reply-uid{$u_id}-fid{$id}-ftid{$reply_id}";
                          $img_name = $new_image_name.'.gif';
                          $img_max_size = explode(',', $this->forum_max_image_size);
                          $image->best_fit($img_max_size[0],$img_max_size[1])->save(ROOTDIR.$img_dir_forum_reply.$img_name);
                          // Make sure image was Successfull
                          if($img_name){
                            // Add new image to database
                            if($this->model->sendNewImage($u_id, $img_name, $img_dir_forum_reply, $file_size, $topic_forum_id, $id, $reply_id)){
                              $img_success = "<br> Image Successfully Uploaded";
                            }else{
                              $img_success = "<br> No Image Uploaded";
                            }
                          }
                        }else{
                          $img_success = "<br> Image was NOT uploaded because the file size was too large!";
                        }
                      }else{
                        $img_success = "<br> No Image Selected to Be Uploaded";
                      }

          					// Success
                    SuccessMessages::push('You Have Successfully Created a New Topic Reply'.$img_success, 'Topic/'.$id.'/'.$redirect_page_num.'#topicreply'.$reply_id);
                    $data['hide_form'] = "true";
          				}else{
          					// Fail
                    $error[] = 'New Topic Reply Create Failed';
          				}
              }// End Form Complete Check
          }else if($data['action'] == "lock_topic" && ($data['is_admin'] == true || $data['is_mod'] == true)){
            // Update database with topic locked (2)
            if($this->model->updateTopicLockStatus($id, "2")){
              SuccessMessages::push('You Have Successfully Locked This Topic', 'Topic/'.$id);
            }
          }else if($data['action'] == "unlock_topic" && ($data['is_admin'] == true || $data['is_mod'] == true)){
            // Update the database with topic unlocked (1)
            if($this->model->updateTopicLockStatus($id, "1")){
              SuccessMessages::push('You Have Successfully UnLocked This Topic', 'Topic/'.$id);
            }
          }else if($data['action'] == "hide_topic" && ($data['is_admin'] == true || $data['is_mod'] == true)){
            // Update database with topic hidden (TRUE)
            $hide_reason = Request::post('hide_reason');
            if($this->model->updateTopicHideStatus($id, "FALSE", $u_id, $hide_reason)){
              SuccessMessages::push('You Have Successfully Hidden This Topic', 'Topic/'.$id);
            }
          }else if($data['action'] == "unhide_topic" && ($data['is_admin'] == true || $data['is_mod'] == true)){
            // Update the database with topic unhide (FALSE)
            if($this->model->updateTopicHideStatus($id, "TRUE", $u_id, "UnHide")){
              SuccessMessages::push('You Have Successfully UnHide This Topic', 'Topic/'.$id);
            }
          }else if($data['action'] == "hide_reply" && ($data['is_admin'] == true || $data['is_mod'] == true)){
            // Update database with topic reply hidden (TRUE)
            $hide_reason = Request::post('hide_reason');
            $reply_id = Request::post('reply_id');
            $reply_url = Request::post('reply_url');
            if($this->model->updateReplyHideStatus($reply_id, "FALSE", $u_id, $hide_reason)){
              SuccessMessages::push('You Have Successfully Hidden Topic Reply', $reply_url);
            }
          }else if($data['action'] == "unhide_reply" && ($data['is_admin'] == true || $data['is_mod'] == true)){
            // Update the database with topic reply unhide (FALSE)
            $reply_id = Request::post('reply_id');
            $reply_url = Request::post('reply_url');
            if($this->model->updateReplyHideStatus($reply_id, "TRUE", $u_id, "UnHide")){
              SuccessMessages::push('You Have Successfully UnHide Topic Reply', $reply_url);
            }
          }else if($data['action'] == "subscribe" && isset($u_id)){
            // Update users topic subcrition status as true
            if($this->model->updateTopicSubcrition($id, $u_id, "true")){
              SuccessMessages::push('You Have Successfully Subscribed to this Topic', 'Topic/'.$id);
            }
          }else if($data['action'] == "unsubscribe" && isset($u_id)){
            // Update users topic subcrition status as false
            if($this->model->updateTopicSubcrition($id, $u_id, "false")){
              SuccessMessages::push('You Have Successfully UnSubscribed from this Topic', 'Topic/'.$id);
            }
          }// End Action Check
  			} // End token check
  		} // End post check

        // Output errors if any
        if(!empty($error)){ $data['error'] = $error; };

      // Update and Get Views Data
      $data['PageViews'] = PageViews::views('true', $id, 'Forum_Topic', $u_id);

      // Get Recent Posts List for Sidebar
      $data['forum_recent_posts'] = $this->model->forum_recent_posts();

      // Setup Breadcrumbs
  		$data['breadcrumbs'] = "
        <li class='breadcrumb-item'><a href='".DIR."Forum'>".$this->forum_title."</a></li>
        <li class='breadcrumb-item'><a href='".DIR."Topics/$topic_forum_id'>".$data['forum_cat']."</a>
  			<li class='breadcrumb-item active'>".$data['title']."</li>
  		";

      // Ready the token!
      $data['csrf_token'] = Csrf::makeToken('forum');

      /* Get user's forum groups data */
      $data['group_forum_perms_post'] = $this->model->group_forum_perms($u_id, "users");
      $data['group_forum_perms_mod'] = $this->model->group_forum_perms($u_id, "mods");
      $data['group_forum_perms_admin'] = $this->model->group_forum_perms($u_id, "admins");

      /* Add Java Stuffs */
      $data['js'] = "<script src='".Url::templatePath()."js/bbcode.js'></script>";

        /* Send data to view */
        Load::ViewPlugin("topic", $data, "forum_sidebar::Right", "Forum");
    }

    // Forum New Topic Form Display
    public function newtopic($id){

      /** Check to see if user is logged in **/
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data **/
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
      }else{
          $u_id = "";
      }

      // Output Current User's ID
      $data['current_userID'] = $u_id;

      // Get Requested Topic's Title and Description
      $data['forum_cat_id'] = $id;
      $data['forum_cat'] = $this->model->forum_cat($id);
      $data['forum_cat_des'] = $this->model->forum_cat_des($id);
      $data['forum_topics'] = $this->model->forum_topics($id);

      // Ouput Page Title
      $data['title'] = "New Topic for ".$data['forum_cat'];

      // Output Welcome Message
      $data['welcome_message'] = "Welcome to the new topic page.";

      // Check to see if current user is a new user
      $data['is_new_user'] = $this->auth->checkIsNewUser($u_id);

      // Get data from post
      (isset($_POST['forum_post_id'])) ? $data['forum_post_id'] = strip_tags(Request::post('forum_post_id')) : $data['forum_post_id'] = "";
      (isset($_POST['forum_title'])) ? $data['forum_title'] = strip_tags(Request::post('forum_title')) : $data['forum_title'] = "";
      (isset($_POST['forum_content'])) ? $data['forum_content'] = htmlspecialchars(Request::post('forum_content')) : $data['forum_content'] = "";

      //var_dump($_POST);
      // Check for autosave
      if(isset($_POST['forum_topic_autosave'])){
        if($_POST['forum_topic_autosave'] == "autosave_topic"){
          /** Forum Auto Save **/
          // Check to make sure the csrf token is good
          if (Csrf::isTokenValid('forum')) {
            /** Token Good **/
            if(!empty($data['forum_post_id'])){
              //var_dump($_POST);
              $update_topic = $this->model->updateSavedTopic($data['forum_post_id'], $data['forum_title'], $data['forum_content']);
              //echo $update_topic;
            }else{
              /** New Forum Post - Create new post **/
              $new_topic = $this->model->sendTopic($u_id, $id, $data['forum_title'], $data['forum_content']);
              echo $new_topic;
            }
          }
        }
      }else{

        // Check to see if user is submitting a new topic
    		if(isset($_POST['submit'])){

    			// Check to make sure the csrf token is good
    			if (Csrf::isTokenValid('forum')) {

              // Check to make sure user completed all required fields in form
              if(empty($data['forum_title'])){
                // Username field is empty
                $error[] = 'Topic Title Field is Blank!';
              }
              if(empty($data['forum_content'])){
                // Subject field is empty
                $error[] = 'Topic Content Field is Blank!';
              }
              // Check for errors before sending message
              if(!isset($error)){
                if(!empty($data['forum_post_id'])){
                  //Update if already saved as draft
                  $update_topic = $this->model->updateSavedTopic($data['forum_post_id'], $data['forum_title'], $data['forum_content'], "1");
                }else{
                  // No Errors, lets submit the new topic to db
                  $new_topic = $this->model->sendTopic($u_id, $id, $data['forum_title'], $data['forum_content'], "1");
                }
                if(empty($new_topic)){ $new_topic = $data['forum_post_id']; }
                  if($new_topic){
                    // New Topic Successfully Created Now Check if User is Uploading Image
                    // Check for image upload with this topic
                    $picture = file_exists($_FILES['forumImage']['tmp_name']) || is_uploaded_file($_FILES['forumImage']['tmp_name']) ? $_FILES['forumImage'] : array ();
                      // Make sure image is being uploaded before going further
                      if(sizeof($picture)>0 && ($data['is_new_user'] != true)){
                        // Get image size
                        $check = getimagesize ( $picture['tmp_name'] );
                        // Get file size for db
                        $file_size = $picture['size'];
                        // Get the Img Forum Topic Directory
                        $img_dir_forum_topic = IMG_DIR_FORUM_TOPIC;
                        // Make sure image size is not too large
                        if($picture['size'] < 5000000 && $check && ($check['mime'] == "image/jpeg" || $check['mime'] == "image/png" || $check['mime'] == "image/gif")){
                            if(!file_exists(ROOTDIR.$img_dir_forum_topic)){
                              mkdir(ROOTDIR.$img_dir_forum_topic,0777,true);
                            }
                            // Upload the image to server
                            $image = new SimpleImage($picture['tmp_name']);
                            $new_image_name = "forum-image-topic-reply-uid{$u_id}-fid{$id}-ftid{$reply_id}";
                            $img_name = $new_image_name.'.gif';
                            $img_max_size = explode(',', $this->forum_max_image_size);
                            $image->best_fit($img_max_size[0],$img_max_size[1])->save(ROOTDIR.$img_dir_forum_topic.$img_name);
                            // Make sure image was Successfull
                            if($img_name){
                              // Add new image to database
                              if($this->model->sendNewImage($u_id, $img_name, $img_dir_forum_topic, $file_size, $id, $new_topic)){
                                $img_success = "<br> Image Successfully Uploaded";
                              }else{
                                $img_success = "<br> No Image Uploaded";
                              }
                            }
                        }else{
                          $img_success = "<br> Image was NOT uploaded because the file size was too large!";
                        }
                      }else{
                        $img_success = "<br> No Image Selected to Be Uploaded";
                      }
          					// Success
                    SuccessMessages::push('You Have Successfully Created a New Topic'.$img_success, 'Topic/'.$new_topic);
                    $data['hide_form'] = "true";
          				}else{
          					// Fail
                    $error[] = 'New Topic Create Failed';
          				}
              }// End Form Complete Check
    			}
    		}else{
          // Check to see if user has unpublished work.  If so then display it.
          $data['forum_post_id'] = $this->model->getUnPublishedWork($data['current_userID'], $data['forum_cat_id']);
          if($data['forum_post_id']){
            $data['forum_title'] = $this->model->topic_title($data['forum_post_id']);
            $data['forum_content'] = $this->model->topic_content($data['forum_post_id']);
          }
        }

          // Output errors if any
          if(!empty($error)){ $data['error'] = $error; };

        // Get Recent Posts List for Sidebar
        $data['forum_recent_posts'] = $this->model->forum_recent_posts();

        // Setup Breadcrumbs
    		$data['breadcrumbs'] = "
          <li class='breadcrumb-item'><a href='".DIR."Forum'>".$this->forum_title."</a></li>
          <li class='breadcrumb-item'><a href='".DIR."Topics/$id'>".$data['forum_cat']."</a>
    			<li class='breadcrumb-item active'>".$data['title']."</li>
    		";

        // Ready the token!
        $data['csrf_token'] = Csrf::makeToken('forum');

        /* Get user's forum groups data */
        $data['group_forum_perms_post'] = $this->model->group_forum_perms($u_id, "users");
        $data['group_forum_perms_mod'] = $this->model->group_forum_perms($u_id, "mods");
        $data['group_forum_perms_admin'] = $this->model->group_forum_perms($u_id, "admins");

        /* Add Java Stuffs */
        $data['js'] = "<script src='".Url::templatePath()."js/bbcode.js'></script>";
        $data['js'] .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'></script>";
        $data['js'] .= "<script src='".Url::templatePath()."js/forum_autosave_topic.js'></script>";

          /* Send data to view */
          Load::ViewPlugin("newtopic", $data, "forum_sidebar::Right", "Forum");
      }
    }

    /* Forum Search Function */
    public function forumSearch($search = null, $current_page = null){

      /** Check to see if user is logged in **/
      if($data['isLoggedIn'] = $this->auth->isLogged()){
        /** User is logged in - Get their data **/
        $u_id = $this->auth->user_info();
        $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
        $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
      }else{
          $u_id = "";
      }

  		// Collect Data for view
  		$data['title'] = "Search ".$this->forum_title;
  		$data['welcome_message'] = $this->forum_description;

      // Display What user is searching for
      $data['search_text'] = urldecode($search);

      // Make sure search entry is not too short
      if(strlen($data['search_text']) > 2){
        // Ready the search words for database
        $search_db = str_replace(' ', '%', $data['search_text']);

        // Get data related to search
        $data['forum_topics'] = $this->model->forum_search($search_db, $this->pagesTopic->getLimit($current_page, $this->forum_topic_limit));

        // Set total number of messages for paginator
        $total_num_topics = count($this->model->forum_search($search_db));
        $this->pagesTopic->setTotal($total_num_topics);

        // Send page links to view
        (isset($id)) ? $id = $id : $id = "";
        $pageFormat = SITE_URL."SearchForum/$search/$id/"; // URL page where pages are
        $data['pageLinks'] = $this->pagesTopic->pageLinks($pageFormat, null, $current_page);

        // Display How Many Results
        $data['results_count'] = $total_num_topics;
      }else{
        $data['error'] = "Search context is too small.  Please try again!";
        $data['results_count'] = 0;
      }

      // Get Recent Posts List for Sidebar
      $data['forum_recent_posts'] = $this->model->forum_recent_posts();

      // Setup Breadcrumbs
  		$data['breadcrumbs'] = "
  			<li class='breadcrumb-item active'>".$this->forum_title."</li>
  		";
      $data['csrf_token'] = Csrf::makeToken('forum');

      /* Get user's forum groups data */
      $data['group_forum_perms_post'] = $this->model->group_forum_perms($u_id, "users");
      $data['group_forum_perms_mod'] = $this->model->group_forum_perms($u_id, "mods");
      $data['group_forum_perms_admin'] = $this->model->group_forum_perms($u_id, "admins");

        /* Send data to view */
        Load::ViewPlugin("searchForum", $data, "forum_sidebar::Right", "Forum");
    }

  }
