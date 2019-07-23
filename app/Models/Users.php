<?php
/**
 * Users Models
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
 */

 namespace App\Models;

 use App\System\Models,
    Libs\Database,
    DateTime;
/**
 * Class Users
 * @package Models
 */

class Users extends Models
{
  protected $db;

  /**
   * Call the parent construct
   */
  public function __construct()
  {
    /** connect to PDO here. **/
    $this->db = Database::get();
  }

  /**
   * Add user to online table
   * @param int $userID
   */
  public function add($userID)
  {
    $data = array('userId' => $userID ,'lastAccess' => date('Y-m-d G:i:s'));
    $this->db->insert(PREFIX."users_online",$data);
  }

  /**
   * Update user to latest login time
   * @param int $userID
   */
  public function update($userID)
  {
    $query = $this->db->select('SELECT * FROM '.PREFIX.'users_online WHERE userId = :userID ', array(':userID' => $userID));
    $count = count($query);
    if($count == 0){
      $this->add($userID);
    }else{
      $data = array('lastAccess' => date('Y-m-d G:i:s'));
      $where = array('userId' => $userID);
      $this->db->update(PREFIX."users_online",$data,$where);
    }
  }

  /**
   * Get current user's id from database
   * @param string $where_username
   * @return int data
   */
  public function getUserID($where_username)
  {
    $data = $this->db->select("SELECT userID FROM ".PREFIX."users WHERE username = :username", array(':username' => $where_username));
    return $data[0]->userID;
  }

  /**
   * Remove user from online status - Logged Out or Idle
   * @param int $userID
   * @return int
   */
  public function remove($userID)
  {
    return $this->db->delete(PREFIX.'users_online', array('userId' => $userID));
  }

  /**
   * Removes users that were 15 minutes inactive and remove them from the table
   * @return int data
   */
  public function cleanOfflineUsers()
  {
    $removed = 0;
    $onlines = $this->db->select('SELECT * FROM '.PREFIX.'users_online');
    foreach($onlines as $online){
      $format = 'Y-m-d H:i:s';
      $date = DateTime::createFromFormat($format, $online->lastAccess);
      if(date_add($date, date_interval_create_from_date_string('15 minute')) < new DateTime("now")){
        $this->remove($online->userId);
        $removed++;
      }
    }
    return $removed;
  }

  /**
   * Get current user's username from database
   * @param int $where_id
   * @return string data
   */
  public function getUserName($where_id){
    $data = $this->db->select("SELECT username FROM ".PREFIX."users WHERE userID = :userID", array(':userID' => $where_id));
    return $data[0]->username;
  }

  /**
   * Get current user's Email from database
   * @param int $where_id
   * @return string data
   */
  public function getUserEmail($where_id){
    $data = $this->db->select("SELECT email FROM ".PREFIX."users WHERE userID = :userID", array(':userID' => $where_id));
    return $data[0]->email;
  }

  /**
   * Get current user's Last Login Date from database
   * @param int $where_id
   * @return string data
   */
  public function getUserLastLogin($where_id){
    $data = $this->db->select("SELECT LastLogin FROM ".PREFIX."users WHERE userID = :userID", array(':userID' => $where_id));
    return $data[0]->LastLogin;
  }

  /**
   * Get current user's Sign Up Date from database
   * @param int $where_id
   * @return string data
   */
  public function getUserSignUp($where_id){
    $data = $this->db->select("SELECT SignUp FROM ".PREFIX."users WHERE userID = :userID", array(':userID' => $where_id));
    return $data[0]->SignUp;
  }

  /**
   * Get current user's Groups
   * @param $where_id
   * @return array dataset
   */
  public function getUserGroupName($where_id){
    // Get user's group ID
    $data = $this->db->select("SELECT groupID FROM ".PREFIX."users_groups WHERE userID = :userID ORDER BY groupID ASC", array(':userID' => $where_id));
    //$groupID = $data[0]->groupID;
    foreach($data as $row){
      // Use group ID to get the group name
      $data2 = $this->db->select("SELECT groupName, groupFontColor, groupFontWeight FROM ".PREFIX."groups WHERE groupID = :groupID",
        array(':groupID' => $row->groupID));
      $groupName = $data2[0]->groupName;
      $groupColor = "color='".$data2[0]->groupFontColor."'";
      $groupWeight = "style='font-weight:".$data2[0]->groupFontWeight."'";
      // Format the output with font style
      $groupOutput[] = "<font $groupColor $groupWeight>$groupName</font>";
    }
    return $groupOutput;
  }

  /**
   * Gets list of members that have activated accounts
   * @return array dataset
   */
  public function getMembers(){
    // Get online users userID
    $data = $this->db->select("
        SELECT
          u.userID,
          u.username,
          u.firstName,
          u.lastName,
          u.isactive,
          ug.userID,
          ug.groupID,
          g.groupID,
          g.groupName,
          g.groupFontColor,
          g.groupFontWeight
        FROM
          ".PREFIX."users u
        LEFT JOIN
          ".PREFIX."users_groups ug
          ON u.userID = ug.userID
        LEFT JOIN
          ".PREFIX."groups g
          ON ug.groupID = g.groupID
        WHERE
          u.isactive=1
        GROUP BY
          u.userID
        ORDER BY
          u.userID ASC, g.groupID DESC
    ");
    return $data;
  }

  /**
  * Get Current User's Data
  * @param int $userID
  * @return array dataset
  */
  public function getCurrentUserData($userID){
    return $this->db->select("
      SELECT
        u.userID,
        u.username,
        u.firstName,
        u.lastName,
        u.email,
        u.aboutme,
        u.privacy_massemail,
        u.privacy_pm,
        u.privacy_profile,
        ug.userID,
        ug.groupID,
        g.groupID,
        g.groupName,
        g.groupFontColor,
        g.groupFontWeight
      FROM
        ".PREFIX."users u
      LEFT JOIN
        ".PREFIX."users_groups ug
        ON u.userID = ug.userID
      LEFT JOIN
        ".PREFIX."groups g
        ON ug.groupID = g.groupID
      WHERE
        u.userID = :userID
      GROUP BY
        u.userID
      ORDER BY
        u.userID ASC, g.groupID DESC
    ",
      array(':userID' => $userID));
  }

  /**
  * Check if current user is admin
  * @param int $userID
  * @return boolean true/false
  */
  public function checkIsAdmin($userID){
    // Get user's group status
    $current_user_groups = $this->db->select("
      SELECT
        ug.userID,
        ug.groupID
      FROM
        ".PREFIX."users_groups ug
      WHERE
        ug.userID = :userID
      ORDER BY
        ug.userID ASC
    ",
      array(':userID' => $userID));

    if(!empty($current_user_groups)){
      foreach($current_user_groups as $user_group_data){
        $cu_groupID[] = $user_group_data->groupID;
      }
      /* Now to check if user is in admin group (default admin groupID: 4) */
      /* If more than one admin group just add num to array ex: array('3','4') */
      $admin_group = array('4');
      foreach ($admin_group as $key) {
        $admin_check[] = in_array($key,$cu_groupID) ? 'YesAdmin' : 'NoAdmin';
      }
      /* Return result of admin check */
      return in_array('YesAdmin',$admin_check) ? 'true' : 'false';
    }else{
      $this->db->insert(PREFIX.'users_groups', array('userID' => $userID, 'groupID' => 1));
      return false;
    }
  }

  /**
  * Get User Main Profile Image by userID
  * @param int $id
  * @return string data
  */
  public function getUserImageMain($id)
  {
    $data = $this->db->select("SELECT userImage FROM ".PREFIX."users_images WHERE userID=:id AND defaultImage = '1' ",array(":id"=>$id));
    return $data[0]->userImage;
  }

  /**
  * Check site setting for content
  * @param string $setting_title
  * @return boolean true/false
  */
  public function checkSiteSetting($setting_title)
  {
      $data = $this->db->select("SELECT setting_data FROM ".PREFIX."settings WHERE setting_title = :setting_title", array(':setting_title' => $setting_title));
      if(!empty($data[0]->setting_data)){
        return true;
      }else{
        return false;
      }
  }

}
