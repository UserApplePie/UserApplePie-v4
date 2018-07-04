<?php
/**
 * Admin Panel Models
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.2.1
 */

 namespace App\Models;

 use App\System\Models,
     Libs\Database;

class AdminPanel extends Models {

    /* Get Site Settings From Database */
    public function getSettings($setting){
        $settings_data = $this->db->select("
            SELECT
                setting_data
            FROM
                ".PREFIX."settings
            WHERE
                setting_title = :setting
        ",
        array(':setting' => $setting));
        return $settings_data[0]->setting_data;
    }

    /* Update Site Setting Data */
  	public function updateSetting($setting_title, $setting_data){
      /* Check to see if data is the same */
      $cur_setting = SELF::getSettings($setting_title);
      if(isset($cur_setting)){
        if($cur_setting == $setting_data){
          return true;
        }else{
      		/* Update Setting Data */
      		$query = $this->db->update(PREFIX.'settings', array('setting_data' => $setting_data), array('setting_title' => $setting_title));
      		if(isset($query) && $query > 0){
      			return true;
      		}else{
      			return false;
      		}
        }
      }else{
        /* Insert New Setting Data */
        $query = $this->db->insert(PREFIX.'settings', array('setting_title' => $setting_title, 'setting_data' => $setting_data));
        if(isset($query) && $query > 0){
          return true;
        }else{
          return false;
        }
      }
  	}


  // Get list of all users
  public function getUsers($orderby, $limit = null){

    // Set default orderby if one is not set
    if($orderby == "ID-DESC"){
      $run_order = "userID DESC";
    }else if($orderby == "ID-ASC"){
      $run_order = "userID ASC";
    }else if($orderby == "UN-DESC"){
      $run_order = "username DESC";
    }else if($orderby == "UN-ASC"){
      $run_order = "username ASC";
    }else{
      // Default order
      $run_order = "userID ASC";
    }

    $user_data = $this->db->select("
        SELECT
          userID,
          username,
          firstName,
          lastName,
          userImage,
          isactive,
          LastLogin,
          SignUp
        FROM
          ".PREFIX."users
        ORDER BY
          $run_order
        $limit
        ");
    return $user_data;
  }

  // Get selected user's data
  public function getUser($id){
    $user_data = $this->db->select("
        SELECT
          u.*
        FROM
          ".PREFIX."users u
        WHERE
          u.userID = :userID
        ",
        array(':userID' => $id));
    return $user_data;
  }

  // Update User's Profile Data
	public function updateProfile($au_id, $au_username, $au_firstName, $au_lastName, $au_email, $au_gender, $au_website, $au_userImage, $au_aboutme, $au_signature){
		// Format the About Me for database
		$au_aboutme = nl2br($au_aboutme);
    $au_signature = nl2br($au_signature);
		// Update users table
		$query = $this->db->update(PREFIX.'users', array('username' => $au_username, 'firstName' => $au_firstName, 'lastName' => $au_lastName, 'email' => $au_email, 'gender' => $au_gender, 'userImage' => $au_userImage, 'website' => $au_website, 'aboutme' => $au_aboutme, 'signature' => $au_signature), array('userID' => $au_id));
		if($query > 0){
			return true;
		}else{
			return false;
		}
	}

  // Update users isactive status
  public function activateUser($au_id){
    // Update users table isactive status
		$query = $this->db->update(PREFIX.'users', array('isactive' => '1'), array('userID' => $au_id));
		if($query > 0){
			return true;
		}else{
			return false;
		}
  }

  // Update users isactive status
  public function deactivateUser($au_id){
    // Update users table isactive status
		$query = $this->db->update(PREFIX.'users', array('isactive' => '0'), array('userID' => $au_id));
		if($query > 0){
			return true;
		}else{
			return false;
		}
  }

  /**
  * getTotalUsers
  *
  * Gets total count of users
  *
  * @return int count
  */
  public function getTotalUsers(){
    $data = $this->db->selectCount("
        SELECT
          *
        FROM
          ".PREFIX."users
        ");
    return $data;
  }

  // Get list of all groups
  public function getAllGroups(){
    $data = $this->db->select("
        SELECT
          groupID
        FROM
          ".PREFIX."groups
        ORDER BY
          groupID
    ");
    return $data;
  }

  // Check to see if user is member of group
  public function checkUserGroup($userID, $groupID){
    $data = $this->db->selectCount("
        SELECT
          userID,
          groupID
        FROM
          ".PREFIX."users_groups
        WHERE
          userID = :userID
          AND
          groupID = :groupID
        ORDER BY
          groupID DESC
        ",
        array(':userID' => $userID, ':groupID' => $groupID));
      if($data > 0){
        return true;
      }else{
        return false;
      }
  }

  // Get group data for requested group
  public function getGroupData($id){
    $group_data = $this->db->select("
        SELECT
          groupID,
          groupName,
          groupFontColor,
          groupFontWeight,
          groupDescription
        FROM
          ".PREFIX."groups
        WHERE
          groupID = :groupID
        ORDER BY
          groupID DESC
        ",
        array(':groupID' => $id));
    return $group_data;
  }

  // Check to see how many groups user is a member of
  public function checkUserGroupsCount($userID){
    $data = $this->db->selectCount("
        SELECT
          userID
        FROM
          ".PREFIX."users_groups
        WHERE
          userID = :userID
        ",
        array(':userID' => $userID));
      if($data <= 1){
        return false;
      }else{
        return true;
      }
  }

  // Remove given user from group
  public function removeFromGroup($userID, $groupID){
    $data = $this->db->delete(PREFIX.'users_groups', array('userID' => $userID, 'groupID' => $groupID));
    if($data > 0){
      return true;
    }else{
      return false;
    }
  }

  // Add given user to group
  public function addToGroup($userID, $groupID){
    $data = $this->db->insert(PREFIX.'users_groups', array('userID' => $userID, 'groupID' => $groupID));
    if($data > 0){
      return true;
    }else{
      return false;
    }
  }

  // Get all groups data
  public function getGroups($orderby){

    // Set default orderby if one is not set
    if($orderby == "ID-DESC"){
      $run_order = "groupID DESC";
    }else if($orderby == "ID-ASC"){
      $run_order = "groupID ASC";
    }else if($orderby == "UN-DESC"){
      $run_order = "groupName DESC";
    }else if($orderby == "UN-ASC"){
      $run_order = "groupName ASC";
    }else{
      // Default order
      $run_order = "groupID ASC";
    }

    $user_data = $this->db->select("
        SELECT
          groupID,
          groupName,
          groupFontColor,
          groupFontWeight,
          groupDescription
        FROM
          ".PREFIX."groups
        ORDER BY
          $run_order
        ");
    return $user_data;
  }

  // Get selected group's data
  public function getGroup($id){
    $group_data = $this->db->select("
        SELECT
          groupID,
          groupName,
          groupDescription,
          groupFontColor,
          groupFontWeight
        FROM
          ".PREFIX."groups
        WHERE
          groupID = :groupID
        ORDER BY
          groupID ASC
        ",
        array(':groupID' => $id));
    return $group_data;
  }

  // Update Group's Data
	public function updateGroup($ag_groupID, $ag_groupName, $ag_groupDescription, $ag_groupFontColor, $ag_groupFontWeight){
		// Update groups table
		$query = $this->db->update(PREFIX.'groups', array('groupName' => $ag_groupName, 'groupDescription' => $ag_groupDescription, 'groupFontColor' => $ag_groupFontColor, 'groupFontWeight' => $ag_groupFontWeight), array('groupID' => $ag_groupID));
		// Check to make sure something was updated
		if($query > 0){
			return true;
		}else{
			return false;
		}
	}

  // delete group
  public function deleteGroup($groupID){
    $data = $this->db->delete(PREFIX.'groups', array('groupID' => $groupID));
    if($data > 0){
      return true;
    }else{
      return false;
    }
  }

  /**
   * createGroup
   *
   * inserts new user group to database.
   *
   * @param string $groupName Name of New User Group
   *
   * @return boolean returns true/false
   */
  public function createGroup($groupName){
    $data = $this->db->insert(PREFIX.'groups', array('groupName' => $groupName));
    $new_group_id = $this->db->lastInsertId('groupID');
    if($data > 0){
      return $new_group_id;
    }else{
      return false;
    }
  }

  // Get list of data for past days
  public function getPastUsersData($getData = null, $length = null){
    if($getData == 'LastLogin'){
      $data = $this->db->select("
        SELECT
          *,
          DATE_FORMAT(LastLogin, '%m/%d/%Y')
        FROM
          ".PREFIX."users
        WHERE
          LastLogin BETWEEN NOW() - INTERVAL :length DAY AND NOW()
        ORDER BY
          userID
        ", array(':length' => $length));
    }else if($getData == 'SignUp'){
      $data = $this->db->select("
        SELECT
          *,
          DATE_FORMAT(SignUp, '%m/%d/%Y')
        FROM
          ".PREFIX."users
        WHERE
          SignUp BETWEEN NOW() - INTERVAL :length DAY AND NOW()
        ORDER BY
          userID
        ", array(':length' => $length));
    }
    return $data;
  }

  // Get list of all users
  public function getUsersMassEmail(){

    $user_data = $this->db->select("
        SELECT
          userID,
          username,
          email
        FROM
          ".PREFIX."users
        WHERE
          privacy_massemail = 'true'
        ORDER BY
          userID
        ");
    return $user_data;
  }

  // Puts new/reply message data into database
  public function sendMassEmail($to_userID, $from_userID, $subject, $content, $username, $email){
    // Format the Content for database
		$content = nl2br($content);
		// Update messages table
		$query = $this->db->insert(PREFIX.'messages', array('to_userID' => $to_userID, 'from_userID' => $from_userID, 'subject' => $subject, 'content' => $content));
		// Check to make sure something was updated
		if($query > 0){
      // Message was updated in database, now we send the to user an email notification.
      // Get from user's data
      $data2 = $this->db->select("SELECT username FROM ".PREFIX."users WHERE userID = :userID",
        array(':userID' => $from_userID));
      $from_username = $data2[0]->username;
      //EMAIL MESSAGE USING PHPMAILER
      $mail = new \Libs\PhpMailer\Mail();
      $mail->setFrom(SITEEMAIL, EMAIL_FROM_NAME);
      $mail->addAddress($email);
      $mail_subject = " " . SITE_TITLE . " - $subject";
      $mail->subject($mail_subject);
      $body = "Hello {$username}";
      $body .= "<hr/> {$content}<hr/>";
      $body .= "This is a Mass Email sent by {$from_username} on " . SITE_TITLE . "<hr/>";
      $body .= "Go to <a href=\"" . SITE_URL . "\">" . SITE_TITLE . "</a> to change your privacy settings if you wish to not receive these mass emails.";
      $mail->body($body);
      $mail->send();

			return true;
		}else{
			return false;
		}
  }



    /**
    * checkForRoute
    *
    * Checks database for controller and method
    *
    * @return int count
    */
    public function checkForRoute($controller, $method){
        $data = $this->db->selectCount("
            SELECT
                *
            FROM
                ".PREFIX."routes
            WHERE
                controller = :controller
            AND
                method = :method
        ", array(':controller' => $controller, ':method' => $method));
      if($data > 0){
          return true;
      }else{
          return false;
      }
    }


    /**
     * addRoute
     *
     * adds new Route To Database
     *
     * @param string $controller Controller Class Name
     * @param string $method Method Name
     *
     * @return boolean returns true/false
     */
    public function addRoute($controller, $method){
      $data = $this->db->insert(PREFIX.'routes', array('controller' => $controller, 'method' => $method, 'url' => $method));
      if($data > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * getAllRoutes
     *
     * gets all system routes from database
     *
     * @return string array
     */
    public function getAllRoutes(){
        $system_routes = $this->db->select("
          SELECT
            *
          FROM
            ".PREFIX."routes
          ");
        return $system_routes;
    }


    /**
    * checkForRoute
    *
    * Checks database for controller and method
    *
    * @return int count
    */
    public function getRoute($id){
        $data = $this->db->select("
            SELECT
                *
            FROM
                ".PREFIX."routes
            WHERE
                id = :id
            LIMIT 1
        ", array(':id' => $id));
      if(isset($data)){
          return $data;
      }else{
          return false;
      }
    }

    /**
     * updateRoute
     *
     * adds new Route To Database
     *
     * @return boolean returns true/false
     */
  	public function updateRoute($id, $controller, $method, $url, $arguments, $enable){
  		$query = $this->db->update(PREFIX.'routes', array('controller' => $controller, 'method' => $method, 'url' => $url, 'arguments' => $arguments, 'enable' => $enable), array('id' => $id));
  		if($query > 0){
  			return true;
  		}else{
  			return false;
  		}
  	}

    /**
     * deleteRoute
     *
     * Remove Route from Database
     *
     * @param int $id Route ID
     *
     * @return boolean returns true/false
     */
    public function deleteRoute($id){
      $data = $this->db->delete(PREFIX.'routes', array('id' => $id));
      if($data > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
    * getAuthLogs
    *
    * Gets auth logs
    *
    * @return int count
    */
    public function getAuthLogs($limit = null){
      return $this->db->select("SELECT * FROM ".PREFIX."activitylog WHERE NOT action='AUTH_CHECKSESSION' ORDER BY date DESC $limit");
    }

    /**
    * getTotalAuthLogs
    *
    * Gets total count of entries in auth logs
    *
    * @return int count
    */
    public function getTotalAuthLogs(){
      return $this->db->selectCount("SELECT * FROM ".PREFIX."activitylog WHERE NOT action='AUTH_CHECKSESSION' ");
    }

}
