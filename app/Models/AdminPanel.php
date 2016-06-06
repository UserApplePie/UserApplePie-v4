<?php
/**
 * Admin Panel Models
 *
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0.4
 */

namespace App\Models;

use Core\Model;

class AdminPanel extends Model {

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
		$count = count($query);
		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

  // Update users isactive status
  public function activateUser($au_id){
    // Update users table isactive status
		$query_a = $this->db->update(PREFIX.'users', array('isactive' => '1'), array('userID' => $au_id));
		$count_a = count($query_a);
		if($count_a > 0){
			return true;
		}else{
			return false;
		}
  }

  // Update users isactive status
  public function deactivateUser($au_id){
    // Update users table isactive status
		$query_a = $this->db->update(PREFIX.'users', array('isactive' => '0'), array('userID' => $au_id));
		$count_a = count($query_a);
		if($count_a > 0){
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
    $data = $this->db->select("
        SELECT
          *
        FROM
          ".PREFIX."users
        ");
    return count($data);
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
    $data = $this->db->select("
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
      $count = count($data);
      if($count > 0){
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
          groupFontWeight
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

  // Remove given user from group
  public function removeFromGroup($userID, $groupID){
    $data = $this->db->delete(PREFIX.'users_groups', array('userID' => $userID, 'groupID' => $groupID));
    $count = count($data);
    if($count > 0){
      return true;
    }else{
      return false;
    }
  }

  // Add given user to group
  public function addToGroup($userID, $groupID){
    $data = $this->db->insert(PREFIX.'users_groups', array('userID' => $userID, 'groupID' => $groupID));
    $count = count($data);
    if($count > 0){
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
          groupFontWeight
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
		$count = count($query);
		// Check to make sure something was updated
		if($count > 0){
			return true;
		}else{
			return false;
		}
	}

  // delete group
  public function deleteGroup($groupID){
    $data = $this->db->delete(PREFIX.'groups', array('groupID' => $groupID));
    $count = count($data);
    if($count > 0){
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
    $count = count($data);
    if($count > 0){
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
		$count = count($query);
		// Check to make sure something was updated
		if($count > 0){
      // Message was updated in database, now we send the to user an email notification.
      // Get from user's data
      $data2 = $this->db->select("SELECT username FROM ".PREFIX."users WHERE userID = :userID",
        array(':userID' => $from_userID));
      $from_username = $data2[0]->username;
      //EMAIL MESSAGE USING PHPMAILER
      $mail = new \Helpers\PhpMailer\Mail();
      $mail->setFrom(SITEEMAIL, EMAIL_FROM_NAME);
      $mail->addAddress($email);
      $mail_subject = " " . SITETITLE . " - $subject";
      $mail->subject($mail_subject);
      $body = "Hello {$username}";
      $body .= "<hr/> {$content}<hr/>";
      $body .= "This is a Mass Email sent by {$from_username} on " . SITETITLE . "<hr/>";
      $body .= "Go to <a href=\"" . SITEURL . "\">" . SITETITLE . "</a> to change your privacy settings if you wish to not receive these mass emails.";
      $mail->body($body);
      $mail->send();

			return true;
		}else{
			return false;
		}
  }

}
