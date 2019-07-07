<?php
/**
* UserApplePie v4 Messages Controller Plugin
*
* UserApplePie - Messages Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 2.1.1 for UAP v.4.3.0
*/

namespace App\Plugins\Messages\Controllers;

use App\System\Controller,
  App\System\Load,
  Libs\Auth\Auth,
  Libs\Csrf,
  Libs\Request,
  Libs\Url,
  Libs\SuccessMessages,
  Libs\ErrorMessages;

class Messages extends Controller{

	private $model;
    private $pages;

	public function __construct(){
		parent::__construct();
		$this->model = new \App\Plugins\Messages\Models\Messages();
        $this->pages = new \Libs\Paginator(MESSAGE_PAGEINATOR_LIMIT);
	}

  // Inbox - Displays all
	public function messages(){

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      //** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

		// Collect Data for view
		$data['title'] = "My Private Messages";
		$data['welcome_message'] = "Welcome to Your Private Messages";

    // Get total unread messages count
    $data['unread_messages'] = $this->model->getUnreadMessages($u_id);

    // Get total messages count
    $data['total_messages'] = $this->model->getTotalMessages($u_id);
    $data['total_messages_outbox'] = $this->model->getTotalMessagesOutbox($u_id);

    // Let view know inbox is in use
    $data['inbox'] = "true";

    // Setup Breadcrumbs
		$data['breadcrumbs'] = "
			<li class='breadcrumb-item active'>Private Messages</li>
		";

        // Ready the token!
        $data['csrf_token'] = Csrf::makeToken('messages');

        // Check for new messages in inbox
        $data['new_messages_inbox'] = $this->model->getUnreadMessages($u_id);

        // Message Quota Goods
        // Get total count of messages
        $data['quota_msg_ttl'] = $data['total_messages'];
        $data['quota_msg_limit'] = MESSAGE_QUOTA_LIMIT;
        $data['quota_msg_percentage'] = $this->model->getPercentage($data['quota_msg_ttl'], $data['quota_msg_limit']);

        // Message Quota Goods
        // Get total count of messages
        $data['quota_msg_ttl_ob'] = $data['total_messages_outbox'];
        $data['quota_msg_limit_ob'] = MESSAGE_QUOTA_LIMIT;
        $data['quota_msg_percentage_ob'] = $this->model->getPercentage($data['quota_msg_ttl_ob'], $data['quota_msg_limit_ob']);

        /* Send data to view */
        Load::ViewPlugin("messages", $data, "messages_sidebar::Left", "Messages");

	}

	// Inbox - Displays all
	public function inbox($current_page = null){

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      //** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    // Hidden Auto Check to make sure that messages that are marked
    // for delete by both TO and FROM users are removed from database
    $this->model->cleanUpMessages();

    // Check to make sure user is trying to delete messages
		if(isset($_POST['submit'])){

			// Check to make sure the csrf token is good
			if (Csrf::isTokenValid('messages')) {
				// Get Post Data
				$actions = Request::post('actions');
				$msg_id = Request::post('msg_id');

        // Check to see if user is deleteing messages
        if($actions == "delete"){
  				// Delete selected messages from Inbox
          if(isset($msg_id)){
            foreach($msg_id as $del_msg_id){
      				if($this->model->deleteMessageInbox($u_id, $del_msg_id)){
      					// Success
                $m_del_success[] = true;
      				}else{
      					// Fail
                $m_del_error[] = true;
      				}
            }
            if(count($m_del_success) >= 1){
              // Message Delete Success Display
              SuccessMessages::push('You Have Successfully Deleted Messages', 'MessagesInbox');
            }else if(count($m_del_error) >= 1){
              // Message Delete Error Display
              ErrorMessages::push('Messages Delete Failed', 'MessagesInbox');
            }
          }else{
            // Fail
            ErrorMessages::push('Nothing Was Selected to be Deleted', 'MessagesInbox');
          }
        }
        // Check to see if user is marking messages as read
        if($actions == "mark_read"){
  				// Mark messages as read for all requested messages
          if(isset($msg_id)){
            foreach($msg_id as $del_msg_id){
      				if($this->model->markReadMessageInbox($u_id, $del_msg_id)){
      					// Success
                $m_read_success[] = true;
      				}else{
      					// Fail
                $m_read_error[] = true;
      				}
            }
            if(isset($m_read_success) && count($m_read_success) >= 1){
              // Message Delete Success Display
              SuccessMessages::push('You Have Successfully Marked Messages as Read', 'MessagesInbox');
            }else if(isset($m_read_error) && count($m_read_error) >= 1){
              // Message Delete Error Display
              ErrorMessages::push('Mark Messages Read Failed', 'MessagesInbox');
            }

          }else{
            // Fail
            ErrorMessages::push('Nothing Was Selected to be Marked as Read', 'MessagesInbox');
          }
        }
			}
		}

		// Collect Data for view
		$data['title'] = "My Private Messages Inbox";
		$data['welcome_message'] = "Welcome to Your Private Messages Inbox";

    // Sets "by" username display
    $data['tofrom'] = " by ";
    $data['inboxoutbox'] = "inbox";

    // Get all message that are to current user
    $data['messages'] = $this->model->getInbox($u_id, $this->pages->getLimit($current_page, MESSAGE_PAGEINATOR_LIMIT));

    // Set total number of messages for paginator
    $total_num_messages = $this->model->getTotalMessages($u_id);
    $this->pages->setTotal($total_num_messages);

    // Send page links to view
    $pageFormat = SITE_URL."MessagesInbox/"; // URL page where pages are
    $data['pageLinks'] = $this->pages->pageLinks($pageFormat, null, $current_page);

    // Message Quota Goods
    // Get total count of messages
    $data['quota_msg_ttl'] = $total_num_messages;
    $data['quota_msg_limit'] = MESSAGE_QUOTA_LIMIT;
    $data['quota_msg_percentage'] = $this->model->getPercentage($data['quota_msg_ttl'], $data['quota_msg_limit']);

    // Check to see if user has reached message limit, if so show warning
    if($data['quota_msg_percentage'] >= "100"){
      $error = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                  <b>Your Inbox is Full!</b>  Other Site Members Can NOT send you any messages!";
    }else if($data['quota_msg_percentage'] >= "90"){
      $error = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                  <b>Warning!</b> Your Inbox is Almost Full!";
    }

    // Output errors if any
    if(isset($error)){ $data['error'] = $error; };

    // Let view know inbox is in use
    $data['inbox'] = "true";
    // What box are we showing
    $data['what_box'] = "Inbox";

    // Setup Breadcrumbs
		$data['breadcrumbs'] = "
			<li class='breadcrumb-item'><a href='".DIR."Messages'>Private Messages</a></li>
			<li class='breadcrumb-item active'>".$data['title']."</li>
		";
    $data['csrf_token'] = Csrf::makeToken('messages');

    // Include Java Script for check all feature
    $data['js'] = "<script src='".Url::templatePath()."js/form_check_all.js'></script>";

    // Check for new messages in inbox
    $data['new_messages_inbox'] = $this->model->getUnreadMessages($u_id);

        /* Send data to view */
        Load::ViewPlugin("messages_list", $data, "messages_sidebar::Left", "Messages");

	}

  // Outbox - Displays all
	public function outbox($current_page = null){

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      //** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    // Check to make sure user is trying to delete messages
		if(isset($_POST['submit'])){

			// Check to make sure the csrf token is good
			if (Csrf::isTokenValid('messages')) {
				// Get Post Data
				$actions = Request::post('actions');
				$msg_id = Request::post('msg_id');

        // Check to see if user is deleteing messages
        if($actions == "delete"){
  				// Delete selected messages from Outbox
          if(isset($msg_id)){
            foreach($msg_id as $del_msg_id){
      				if($this->model->deleteMessageOutbox($u_id, $del_msg_id)){
      					// Success
                $m_del_success[] = true;
      				}else{
      					// Fail
                $m_del_error[] = true;
      				}
            }
            if(count($m_del_success) >= 1){
              // Message Delete Success Display
              SuccessMessages::push('You Have Successfully Deleted Messages', 'MessagesOutbox');
            }else if(count($m_del_error) >= 1){
              // Message Delete Error Display
              ErrorMessages::push('Messages Delete Failed', 'MessagesOutbox');
            }

          }else{
            // Fail
            ErrorMessages::push('Nothing Was Selected to be Deleted', 'MessagesOutbox');
          }
        }
			}
		}

		// Collect Data for view
		$data['title'] = "My Private Messages Outbox";
		$data['welcome_message'] = "Welcome to your Private Messages Outbox";

    // Sets "to" username display
    $data['tofrom'] = " to ";
    $data['inboxoutbox'] = "outbox";

    // Get all message that are to current user
    $data['messages'] = $this->model->getOutbox($u_id, $this->pages->getLimit($current_page, MESSAGE_PAGEINATOR_LIMIT));

    // Set total number of messages for paginator
    $total_num_messages = $this->model->getTotalMessagesOutbox($u_id);
    $this->pages->setTotal($total_num_messages);
    // Send page links to view
    $pageFormat = SITE_URL."MessagesOutbox/"; // URL page where pages are
    $data['pageLinks'] = $this->pages->pageLinks($pageFormat, null, $current_page);

    // Message Quota Goods
    // Get total count of messages
    $data['quota_msg_ttl'] = $total_num_messages;
    $data['quota_msg_limit'] = MESSAGE_QUOTA_LIMIT;
    $data['quota_msg_percentage'] = $this->model->getPercentage($data['quota_msg_ttl'], $data['quota_msg_limit']);

    // Check to see if user has reached message limit, if so show warning
    if($data['quota_msg_percentage'] >= "100"){
      $error[] = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                  <b>Your Outbox is Full!</b>  You Can NOT send any messages!";
    }else if($data['quota_msg_percentage'] >= "90"){
      $error[] = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                  <b>Warning!</b> Your Outbox is Almost Full!";
    }

    // What box are we showing
    $data['what_box'] = "Outbox";

    // Output errors if any
    if(isset($error)){ $data['error'] = $error; };

    // Setup Breadcrumbs
		$data['breadcrumbs'] = "
			<li class='breadcrumb-item'><a href='".DIR."Messages'>Private Messages</a></li>
			<li class='breadcrumb-item active'>".$data['title']."</li>
		";
    $data['csrf_token'] = Csrf::makeToken('messages');

    // Include Java Script for check all feature
    $data['js'] = "<script src='".Url::templatePath()."js/form_check_all.js'></script>";

    // Check for new messages in inbox
    $data['new_messages_inbox'] = $this->model->getUnreadMessages($u_id);

        /* Send data to view */
        Load::ViewPlugin("messages_list", $data, "messages_sidebar::Left", "Messages");

	}

  // View Message - Displays requested message
	public function view($m_id){

    /** Check to see if user is logged in **/
    if($data['isLoggedIn'] = $this->auth->isLogged()){
      //** User is logged in - Get their data **/
      $u_id = $this->auth->user_info();
      $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
      $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
    }else{
      /** User Not logged in - kick them out **/
      \Libs\ErrorMessages::push('You are Not Logged In', 'Login');
    }

    // Check to make sure user is trying to send new message
    if(isset($_POST['submit'])){
      // Check to make sure the csrf token is good
      if (Csrf::isTokenValid('messages')) {
        $msg_id = Request::post('message_id');
        if(isset($msg_id)){
          if($this->model->deleteMessageInbox($u_id, $msg_id)){
            // Success
            $m_del_success_inbox = true;
          }else{
            if($this->model->deleteMessageOutbox($u_id, $msg_id)){
              // Success
              $m_del_success_outbox = true;
            }else{
              // Fail
              $m_del_error = true;
            }
          }
        }
        if($m_del_success_inbox == true){
          // Message Delete Success Display
          SuccessMessages::push('You Have Successfully Deleted Message', 'MessagesInbox');
        }else if($m_del_success_outbox == true){
          // Message Delete Success Display
          SuccessMessages::push('You Have Successfully Deleted Message', 'MessagesOutbox');
        }else if($m_del_error == true){
          // Message Delete Error Display
          ErrorMessages::push('Message Delete Failed', 'Messages');
        }
      }
    }else{

      // Check to see if requested message exists and user is related to it
      if($this->model->checkMessagePerm($u_id, $m_id)){
        // Message exist and user is related
    		// Collect Data for view
    		$data['title'] = "My Private Message";
    		$data['welcome_message'] = "Welcome to Your Private Message";

        // Get requested message data
        $data['message'] = $this->model->getMessage($m_id, $u_id);
      }else{
        // User Does not own message or it does not exist
        $data['title'] = "My Private Message - Error!";
        $data['welcome_message'] = "The requested private message does not exist!";
        $data['msg_error'] = "true";
      }
      // Setup Breadcrumbs
  		$data['breadcrumbs'] = "
  			<li class='breadcrumb-item'><a href='".DIR."Messages'>Private Messages</a></li>
  			<li class='breadcrumb-item active'>".$data['title']."</li>
  		";
      $data['csrf_token'] = Csrf::makeToken('messages');

      // Check for new messages in inbox
      $data['new_messages_inbox'] = $this->model->getUnreadMessages($u_id);

    }

    /* Send data to view */
    Load::ViewPlugin("message_display", $data, "messages_sidebar::Left", "Messages");

	}

    /*
    ** New Message
    ** Displays form to create a new message or reply
    */
	public function newmessage($to_user = NULL, $subject = NULL){

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
            //** User is logged in - Get their data **/
            $u_id = $this->auth->user_info();
            $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
            $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }else{
            /** User Not logged in - kick them out **/
            ErrorMessages::push('You are Not Logged In', 'Login');
        }

        // Check to see if user is over quota
        // Disable New Message Form is they are
        if($this->model->checkMessageQuota($u_id)){
        // user is over limit, disable new message form
        $data['hide_form'] = "true";
            $error[] = "<span class='fas fa-exclamation-sign' aria-hidden='true'></span>
                      <b>Your Outbox is Full!</b>  You Can NOT send any messages!";
        }

		// Check to make sure user is trying to send new message
		if(isset($_POST['submit'])){
			// Check to make sure the csrf token is good
			if (Csrf::isTokenValid('messages')) {
				// Get data from post
        (isset($_POST['to_username'])) ? $to_username = strip_tags(Request::post('to_username')) : $to_username = "";
        (isset($_POST['subject'])) ? $subject = strip_tags(Request::post('subject')) : $subject = "";
        (isset($_POST['content'])) ? $content = htmlspecialchars(Request::post('content')) : $content = "";
        (isset($_POST['reply'])) ? $reply = htmlspecialchars(Request::post('reply')) : $reply = "";

                // Check to see if this is coming from a reply button
                if($reply != "true"){
                    // Check to make sure user completed all required fields in form
                    if(empty($to_username)){
                        // Username field is empty
                        $error[] = 'Username Field is Blank!';
                    }
                    if(empty($subject)){
                        // Subject field is empty
                        $error[] = 'Subject Field is Blank!';
                    }
                    if(empty($content)){
                        // Username field is empty
                        $error[] = 'Message Content Field is Blank!';
                    }
                    // Check for errors before sending message
                    if(!isset($error)){
                        // Get the userID of to username
                        $to_userID = $this->model->getUserIDFromUsername($to_username);
                        // Check to make sure user exists in Database
                        if(isset($to_userID)){
                            // Check to see if to user's inbox is not full
                            if($this->model->checkMessageQuotaToUser($to_userID)){
			                    // Run the Activation script
                                if($this->model->sendmessage($to_userID, $u_id, $subject, $content)){
                                    // Success
                                    SuccessMessages::push('You Have Successfully Sent a Private Message', 'Messages');
                                    $data['hide_form'] = "true";
		                        }else{
                                    // Fail
                                    $error[] = 'Message Send Failed';
                                }
                            }else{
                                // To user's inbox is full.  Let sender know message was not sent
                                $error[] = '<b>${to_username}&#39;s Inbox is Full!</b>  Sorry, Message was NOT sent!';
                            }
                        }else{
                            // User does not exist
                            $error[] = 'Message Send Failed - To User Does Not Exist';
                        }
                    }// End Form Complete Check
                }else{
                    // Get data from reply $_POST
                    (isset($_POST['subject'])) ? $subject = strip_tags(Request::post('subject')) : $subject = "";
                    (isset($_POST['content'])) ? $content = htmlspecialchars(str_replace("<br />", " ", Request::post('content'))) : $content = "";
                    (isset($_POST['date_sent'])) ? $date_sent = strip_tags(Request::post('date_sent')) : $date_sent = "";
                    // Add Reply details to subject ex: RE:
                    $data['subject'] = "RE: ".$subject;
                    // Clean up content so it looks pretty
                    $content_reply = "&#10;&#10;&#10;&#10; ##############################";
                    $content_reply .= "&#10; # PREVIOUS MESSAGE";
                    $content_reply .= "&#10; # From: $to_username";
                    $content_reply .= "&#10; # Sent: $date_sent ";
                    $content_reply .= "&#10; ############################## &#10;&#10;";
                    $content_reply .= $content;
                    $content_reply = str_replace("<br />", " ", $content_reply);
                    $data['content'] = $content_reply;
                }// End Reply Check
			}
		}

        // Check to see if there were any errors, if so then auto load form data
        if(isset($error) && !isset($data['subject']) && !isset($data['content'])){
            // Auto Fill form to make things eaiser for user
            $data['subject'] = Request::post('subject');
            $data['content'] = Request::post('content');
            // Output errors if any
            if(!empty($error)){ $data['error'] = $error; };
        }
		if(!isset($data['subject'])){ $data['subject'] = $subject; }

        // Collect Data for view
        $data['title'] = "My Private Message";
        $data['welcome_message'] = "Welcome to Your Private Message Creator";
        $data['csrf_token'] = Csrf::makeToken('messages');

        // Check to see if username is in url or post
        if(isset($to_user)){
            $data['to_username'] = $to_user;
        }else{
            $data['to_username'] = Request::post('to_username');
        }

        // Setup Breadcrumbs
		$data['breadcrumbs'] = "
			<li class='breadcrumb-item'><a href='".DIR."Messages'>Private Messages</a></li>
			<li class='breadcrumb-item active'>".$data['title']."</li>
		";

        // Get requested message data
        //$data['message'] = $this->model->getMessage($m_id);

        // Check for new messages in inbox
        $data['new_messages_inbox'] = $this->model->getUnreadMessages($u_id);

        /* Send data to view */
        Load::ViewPlugin("message_new", $data, "messages_sidebar::Left", "Messages");

	}

}
