<?php
/**
 * Admin Panel Models
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
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
            ORDER BY
                setting_id DESC
        ",
        array(':setting' => $setting));
        return $settings_data[0]->setting_data;
    }

    /* Check Site Setting in Database */
    public function checkSetting($setting){
        $settings_data = $this->db->selectCount("
            SELECT
                setting_title
            FROM
                ".PREFIX."settings
            WHERE
                setting_title = :setting
        ",
        array(':setting' => $setting));

        if($settings_data > 0){
          return true;
        }else{
          return false;
        }
    }

    /* Update Site Setting Data */
  	public function updateSetting($setting_title, $setting_data){
      /* Check to see if data is the same */
      $check_title = SELF::checkSetting($setting_title);
      $cur_setting = SELF::getSettings($setting_title);

      if($check_title){
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
	public function updateProfile($au_id, $au_username, $au_firstName, $au_lastName, $au_email, $au_gender, $au_website, $au_aboutme, $au_signature){
		// Format the About Me for database
		$au_aboutme = nl2br($au_aboutme);
    $au_signature = nl2br($au_signature);
		// Update users table
		$query = $this->db->update(PREFIX.'users', array('username' => $au_username, 'firstName' => $au_firstName, 'lastName' => $au_lastName, 'email' => $au_email, 'gender' => $au_gender, 'website' => $au_website, 'aboutme' => $au_aboutme, 'signature' => $au_signature), array('userID' => $au_id));
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
        AND
          isactive = '1'
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
    * @return array dataset
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

    /**
    * getSiteLinks
    *
    * Gets all Main Links that are not dropdown links
    *
    * @return array dataset
    */
    public function getSiteLinks($location){
      return $this->db->select("SELECT * FROM ".PREFIX."links WHERE location = :location AND drop_down_for='0' ORDER BY link_order ASC", array(':location'=>$location));
    }

    /**
    * getSiteLinksLastID
    *
    * Gets id of last link order link
    *
    * @return array dataset
    */
    public function getSiteLinksLastID($location){
      $last_link = $this->db->select("SELECT link_order FROM ".PREFIX."links WHERE location = :location AND drop_down_for='0' ORDER BY link_order DESC LIMIT 1", array(':location'=>$location));
      return $last_link[0]->link_order;
    }

    /**
     * moveUpLink
     *
     * update position of given object.
     *
     * @param int id of the object
     *
     * @return boolean returns true/false
     */
    public function moveUpLink($location,$link_id){
      $current_link_order = $this->db->select("SELECT link_order FROM ".PREFIX."links WHERE location = :location AND id = :id LIMIT 1", array(':location'=>$location,':id'=>$link_id));
      $old = $current_link_order[0]->link_order;
      // Moving up one spot
      $new = $old - 1;
      // Make sure this object is not already at top
      if($new > 0){
        // Update groups table
        $query = $this->db->raw("
          UPDATE
            ".PREFIX."links
          SET
            `link_order` = CASE
            WHEN (`link_order` = $old) THEN
              $new
            WHEN (`link_order` > $old and `link_order` <= $new) THEN
              `link_order`- 1
            WHEN (`link_order` < $old and `link_order` >= $new) THEN
              `link_order`+ 1
            ELSE
              `link_order`
          END
          WHERE `location` = '$location'
          ");
        // Check to make sure something was updated
        if(isset($query)){
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }

    /**
     * moveDownLink
     *
     * update position of given object.
     *
     * @param int id of the object
     *
     * @return boolean returns true/false
     */
    public function moveDownLink($location,$link_id){
      $current_link_order = $this->db->select("SELECT link_order FROM ".PREFIX."links WHERE location = :location AND id = :id LIMIT 1", array(':location'=>$location,':id'=>$link_id));
      $old = $current_link_order[0]->link_order;
      // Moving down one spot
      $new = $old + 1;
      // Update groups table
      $query = $this->db->raw("
        UPDATE
          ".PREFIX."links
        SET
          `link_order` = CASE
          WHEN (`link_order` = $old) THEN
            $new
          WHEN (`link_order` < $old and `link_order` >= $new) THEN
            `link_order`+ 1
          WHEN (`link_order` > $old and `link_order` <= $new) THEN
            `link_order`- 1
          ELSE
            `link_order`
        END
        WHERE `location` = '$location'
        ");
      // Check to make sure something was updated
      if(isset($query)){
        return true;
      }else{
        return false;
      }
    }

    /**
    * getSiteLinks
    *
    * Gets all Main Links that are not dropdown links
    *
    * @return array dataset
    */
    public function getSiteLinkData($id){
      return $this->db->select("SELECT * FROM ".PREFIX."links WHERE id = :id LIMIT 1", array(':id'=>$id));
    }

    /**
     * addSiteLink
     *
     * adds new Site Link To Database
     *
     * @return boolean returns true/false
     */
    public function addSiteLink($title, $url, $alt_text, $location, $drop_down, $require_plugin = null, $permission = 0){
      $link_order_last = SELF::getSiteLinksLastID($location);
      if(isset($link_order_last)){
        $link_order = $link_order_last + 1;
      }else{
        $link_order = "1";
      }
      $data = $this->db->insert(PREFIX.'links', array('title' => $title, 'url' => $url, 'alt_text' => $alt_text, 'location' => $location, 'drop_down' => $drop_down, 'require_plugin' => $require_plugin, 'link_order' => $link_order, 'permission' => $permission));
      if($data > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
     * updateSiteLink
     *
     * updates Site Link in Database
     *
     * @return boolean returns true/false
     */
  	public function updateSiteLink($id, $title, $url, $alt_text, $location, $drop_down, $require_plugin = null, $permission = 0){
  		$query = $this->db->update(PREFIX.'links', array('title' => $title, 'url' => $url, 'alt_text' => $alt_text, 'location' => $location, 'drop_down' => $drop_down, 'require_plugin' => $require_plugin, 'permission' => $permission), array('id' => $id));
  		if($query > 0){
  			return true;
  		}else{
  			return false;
  		}
  	}

    /**
     * deleteSiteLink
     *
     * Remove Site Link from Database
     *
     * @param int $id site link ID
     *
     * @return boolean returns true/false
     */
    public function deleteSiteLink($id){
      $data = $this->db->delete(PREFIX.'links', array('id' => $id));
      if($data > 0){
        $this->db->delete(PREFIX.'links', array('drop_down_for' => $id), 1000);
        return true;
      }else{
        return false;
      }
    }

    /**
    * getSiteDropDownLinks
    *
    * Gets all links for Drop Down link
    *
    * @return array dataset
    */
    public function getSiteDropDownLinks($id){
      return $this->db->select("SELECT * FROM ".PREFIX."links WHERE drop_down_for = :id ORDER BY link_order_drop_down ASC", array(':id'=>$id));
    }

    /**
    * getSiteDropDownLinksLastID
    *
    * Gets id of last link order link
    *
    * @return array dataset
    */
    public function getSiteDropDownLinksLastID($id){
      $last_link = $this->db->select("SELECT link_order_drop_down FROM ".PREFIX."links WHERE drop_down_for = :id ORDER BY link_order_drop_down DESC LIMIT 1", array(':id'=>$id));
      return $last_link[0]->link_order_drop_down;
    }

    /**
     * moveUpDDLink
     *
     * update position of given object.
     *
     * @param int id of the object
     *
     * @return boolean returns true/false
     */
    public function moveUpDDLink($main_link_id,$dd_link_id){
      $current_link_order = $this->db->select("SELECT link_order_drop_down FROM ".PREFIX."links WHERE drop_down_for = :drop_down_for AND id = :id LIMIT 1", array(':drop_down_for'=>$main_link_id,':id'=>$dd_link_id));
      $old = $current_link_order[0]->link_order_drop_down;
      // Moving up one spot
      $new = $old - 1;
      // Make sure this object is not already at top
      if($new > 0){
        // Update groups table
        $query = $this->db->raw("
          UPDATE
            ".PREFIX."links
          SET
            `link_order_drop_down` = CASE
            WHEN (`link_order_drop_down` = $old) THEN
              $new
            WHEN (`link_order_drop_down` > $old and `link_order_drop_down` <= $new) THEN
              `link_order_drop_down`- 1
            WHEN (`link_order_drop_down` < $old and `link_order_drop_down` >= $new) THEN
              `link_order_drop_down`+ 1
            ELSE
              `link_order_drop_down`
          END
          WHERE `drop_down_for` = '$main_link_id'
          ");
        // Check to make sure something was updated
        if(isset($query)){
          return true;
        }else{
          return false;
        }
      }else{
        return false;
      }
    }

    /**
     * moveDownDDLink
     *
     * update position of given object.
     *
     * @param int id of the object
     *
     * @return boolean returns true/false
     */
    public function moveDownDDLink($main_link_id,$dd_link_id){
      $current_link_order = $this->db->select("SELECT link_order_drop_down FROM ".PREFIX."links WHERE drop_down_for = :drop_down_for AND id = :id LIMIT 1", array(':drop_down_for'=>$main_link_id,':id'=>$dd_link_id));
      $old = $current_link_order[0]->link_order_drop_down;
      // Moving down one spot
      $new = $old + 1;
      // Update groups table
      $query = $this->db->raw("
        UPDATE
          ".PREFIX."links
        SET
          `link_order_drop_down` = CASE
          WHEN (`link_order_drop_down` = $old) THEN
            $new
          WHEN (`link_order_drop_down` < $old and `link_order_drop_down` >= $new) THEN
            `link_order_drop_down`+ 1
          WHEN (`link_order_drop_down` > $old and `link_order_drop_down` <= $new) THEN
            `link_order_drop_down`- 1
          ELSE
            `link_order_drop_down`
        END
        WHERE `drop_down_for` = '$main_link_id'
        ");
      // Check to make sure something was updated
      if(isset($query)){
        return true;
      }else{
        return false;
      }
    }

    /**
     * addSiteDDLink
     *
     * adds new Site Drop Down Link To Database
     *
     * @return boolean returns true/false
     */
    public function addSiteDDLink($title, $url, $alt_text, $location, $drop_down, $require_plugin = null, $drop_down_for, $permission = 0){
      $link_order_last = SELF::getSiteDropDownLinksLastID($drop_down_for);
      if(isset($link_order_last)){
        $link_order = $link_order_last + 1;
      }else{
        $link_order = "1";
      }
      $current_link_order = $this->db->select("SELECT link_order FROM ".PREFIX."links WHERE id = :id LIMIT 1", array(':id'=>$drop_down_for));
      $get_link_order = $current_link_order[0]->link_order;
      $data = $this->db->insert(PREFIX.'links', array('title' => $title, 'url' => $url, 'alt_text' => $alt_text, 'location' => $location, 'drop_down' => $drop_down, 'require_plugin' => $require_plugin,
                                                          'link_order_drop_down' => $link_order, 'drop_down_for' => $drop_down_for, 'link_order' => $get_link_order, 'permission' => $permission));
      if($data > 0){
        return true;
      }else{
        return false;
      }
    }

    /**
    * getMainLinkTitle
    *
    * Gets Link Title based on ID
    *
    * @return array dataset
    */
    public function getMainLinkTitle($id){
      $data = $this->db->select("SELECT title FROM ".PREFIX."links WHERE id = :id LIMIT 1", array(':id'=>$id));
      return $data[0]->title;
    }

    /**
     * updateSiteDDLink
     *
     * updates Site Drop Down Link in Database
     *
     * @return boolean returns true/false
     */
  	public function updateSiteDDLink($id, $title, $url, $alt_text, $location, $drop_down, $require_plugin = null, $permission = 0){
  		$query = $this->db->update(PREFIX.'links', array('title' => $title, 'url' => $url, 'alt_text' => $alt_text, 'location' => $location, 'drop_down' => $drop_down, 'require_plugin' => $require_plugin, 'permission' => $permission), array('id' => $id));
  		if($query > 0){
  			return true;
  		}else{
  			return false;
  		}
  	}

    /* Get Site UAP Database Version From Database */
    public function getDatabaseVersion(){
        $check = $this->db->select("
          SELECT
          IF( EXISTS
              (SELECT * FROM information_schema.COLUMNS
                  WHERE TABLE_SCHEMA = '".DB_NAME."'
                  AND TABLE_NAME = '".PREFIX."version'
                  LIMIT 1),
          1, 0)
          AS if_exists
        ");
        $ver_db_check = $check[0]->if_exists == 1;

        if($ver_db_check){
          $data = $this->db->select("
              SELECT
                  version
              FROM
                  ".PREFIX."version
              WHERE
                  id = 1
          ");
          return $data[0]->version;
        }else{
          return "4.2.1";
        }
    }

    /**
    * getTopRefer
    *
    * Checks database for controller and method
    *
    * @return int count
    */
    public function getTopRefer($days = '10'){
        $thissite = SITE_URL;
        $thissite = trim($thissite, '/');
        $thissite = parse_url($thissite);
        $data = $this->db->select("
          SELECT refer, COUNT(refer) as refer_count FROM `".PREFIX."sitelogs`
            WHERE server = '".$_SERVER['SERVER_NAME']."'
            AND refer != ''
            AND refer NOT LIKE :thissite
            AND refer NOT LIKE '%localhost%'
            AND timestamp >= CURDATE() - INTERVAL :days DAY
            GROUP BY refer
            ORDER BY refer_count DESC
            LIMIT 5
        ", array(':days' => $days, ':thissite' => '%'.$thissite['host'].'%'));
      if(isset($data)){
          return $data;
      }else{
          return false;
      }
    }

}
