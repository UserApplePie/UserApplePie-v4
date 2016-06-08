<?php
namespace Helpers;

use Helpers\Database,
    Helpers\Cookie,
    Helpers\BBCode;

class CurrentUserData
{
    private static $db;

	// Get user data for requested user's profile
	public static function getCUD($where_id){
		self::$db = Database::get();
		$user_data = self::$db->select("
				SELECT
					u.userID,
					u.username,
					u.firstName,
					u.gender,
					u.userImage,
					u.email,
					u.LastLogin,
					u.SignUp,
					u.website,
					u.aboutme
				FROM
					".PREFIX."users u
				WHERE
					u.userID = :userID
				",
			array(':userID' => $where_id));
		return $user_data;
	}

  // Get current user's groups
  public static function getCUGroups($where_id){
    self::$db = Database::get();
    $user_groups = self::$db->select("
        SELECT
          ug.userID, ug.groupID, g.groupID, g.groupName, g.groupDescription, g.groupFontColor, g.groupFontWeight
        FROM
          ".PREFIX."users_groups ug
        LEFT JOIN
          ".PREFIX."groups g
          ON g.groupID = ug.groupID
        WHERE
          ug.userID = :userID
        ",
      array(':userID' => $where_id));
    return $user_groups;
  }

  /**
  * Get Group members count
  */
  public static function getGroupMembersCount($where_id){
    self::$db = Database::get();
    $user_groups = self::$db->select("
        SELECT
          *
        FROM
          ".PREFIX."users_groups
        WHERE
          groupID = :groupID
        ",
      array(':groupID' => $where_id));
    return count($user_groups);
  }

  /**
	 * Get current user's username from database
	 */
	public static function getUserName($where_id){
    self::$db = Database::get();
		$data = self::$db->select("SELECT username FROM ".PREFIX."users WHERE userID = :userID",
			array(':userID' => $where_id));
		return $data[0]->username;
	}

  /**
  * Get Current User's Groups Data For Display
  */

  public function getUserGroups($where_id){
    self::$db = Database::get();
    $user_groups = self::$db->select("
        SELECT
          ug.userID, ug.groupID, g.groupID, g.groupName, g.groupDescription, g.groupFontColor, g.groupFontWeight
        FROM
          ".PREFIX."users_groups ug
        LEFT JOIN
          ".PREFIX."groups g
          ON g.groupID = ug.groupID
        WHERE
          ug.userID = :userID
        ",
      array(':userID' => $where_id));
      if(isset($user_groups)){
        foreach($user_groups as $row){
          $usergroup[] = " <font color='$row->groupFontColor' weight='$row->groupFontWeight'>$row->groupName</font> ";
        }
      }
    return $usergroup;
  }

  /**
	 * Get selected user's profile image from db
	 */
	public static function getUserImage($where_id){
    self::$db = Database::get();
		$data = self::$db->select("SELECT userImage FROM ".PREFIX."users WHERE userID = :userID",
			array(':userID' => $where_id));
		return $data[0]->userImage;
	}

  /**
	 * Get selected user's signup date from db
	 */
	public static function getSignUp($where_id){
    self::$db = Database::get();
		$data = self::$db->select("SELECT SignUp FROM ".PREFIX."users WHERE userID = :userID",
			array(':userID' => $where_id));
		return date("F d, Y",strtotime($data[0]->SignUp));
	}

  /**
	 * Get current user's signature from database
	 */
	public static function getUserSignature($where_id){
    self::$db = Database::get();
		$data = self::$db->select("SELECT signature FROM ".PREFIX."users WHERE userID = :userID",
			array(':userID' => $where_id));
		$signature_data = BBCode::getHtml($data[0]->signature);
    return $signature_data;
	}

  // Gets total number of unread messages from database for selected user
  public function getUnreadMessages($where_id){
    if(ctype_digit($where_id)){
      self::$db = Database::get();
			$data = self::$db->select("
					SELECT
            *
					FROM
						".PREFIX."messages
					WHERE
						to_userID = :userID
          AND
            date_read IS NULL
          AND
            to_delete = :to_delete
					",
				array(':userID' => $where_id, ':to_delete' => 'false'));
        $count = count($data);
        if($count > 0){
          return $count;
        }else{
          $count = "0";
          return $count;
        }
			return $count;
		}else{
      $count = "0";
      return $count;
    }
  }

  /**
   * Get all members that are activated with info
   * @return array
   */
  public function getMembers()
  {
    self::$db = Database::get();
    return count(self::$db->select("
      SELECT
        u.userID,
        u.username,
        u.firstName,
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
        u.isactive = true
      GROUP BY
        u.userID
      ORDER BY
        u.userID ASC, g.groupID DESC"));
  }

  /**
   * Get all info on members that are online
   * @return array
   */
  public function getOnlineMembers()
  {
    self::$db = Database::get();
    return count(self::$db->select("
      SELECT
        u.userID,
        u.username,
        u.firstName,
        uo.userID,
        ug.userID,
        ug.groupID,
        g.groupID,
        g.groupName,
        g.groupFontColor,
        g.groupFontWeight
      FROM
        ".PREFIX."users_online uo
      LEFT JOIN
        ".PREFIX."users u
        ON u.userID = uo.userID
      LEFT JOIN
        ".PREFIX."users_groups ug
        ON uo.userID = ug.userID
      LEFT JOIN
        ".PREFIX."groups g
        ON ug.groupID = g.groupID
      GROUP BY
        u.userID
      ORDER BY
        u.userID ASC, g.groupID DESC"));
  }

}
