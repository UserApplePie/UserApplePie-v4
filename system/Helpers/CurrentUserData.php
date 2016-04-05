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

}
