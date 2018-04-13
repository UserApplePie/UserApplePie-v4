<?php
/**
* Current User Data Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

namespace Libs;

use Libs\Database,
    Libs\Cookie,
    Libs\BBCode;

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
        (isset($data[0]->username)) ? $username = $data[0]->username : $username = "Guest";
    	return $username;
    }

  /**
  * Get Current User's Groups Data For Display
  */
  public static function getUserGroups($where_id){
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
      (isset($usergroup)) ? $usergroup = $usergroup : $usergroup[] = "";
    return $usergroup;
  }

  /**
	 * Get selected user's profile image from db
	 */
	public static function getUserImage($where_id){
    self::$db = Database::get();
		$data = self::$db->select("SELECT userImage FROM ".PREFIX."users WHERE userID = :userID",
			array(':userID' => $where_id));
        (isset($data[0]->userImage)) ? $userImage = $data[0]->userImage : $userImage = "";
		return $userImage;
	}

  /**
	 * Get selected user's signup date from db
	 */
	public static function getSignUp($where_id){
    self::$db = Database::get();
		$data = self::$db->select("SELECT SignUp FROM ".PREFIX."users WHERE userID = :userID",
			array(':userID' => $where_id));
        (isset($data[0]->SignUp)) ? $SignUp = date("F d, Y",strtotime($data[0]->SignUp)) : $SignUp = "";
		return $SignUp;
	}

  /**
	 * Get current user's signature from database
	 */
	public static function getUserSignature($where_id){
    self::$db = Database::get();
		$data = self::$db->select("SELECT signature FROM ".PREFIX."users WHERE userID = :userID",
			array(':userID' => $where_id));
        (isset($data[0]->signature)) ? $signature_data = BBCode::getHtml($data[0]->signature) : $signature_data = "";
    return $signature_data;
	}

  // Gets total number of unread messages from database for selected user
  public static function getUnreadMessages($where_id){
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
  public static function getMembers()
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
  public static function getOnlineMembers()
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

    /**
    *
    *
    *
    */
    public static function getFriendRequests($where_id){
        if(ctype_digit($where_id)){
            self::$db = Database::get();
            $data = self::$db->select("
                SELECT
                *
                FROM
                ".PREFIX."friends
                WHERE
                uid2 = :userID
                AND
                status2 = :status2
                ",
                array(':userID' => $where_id, ':status2' => '0'));
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
    *
    *
    *
    */
    public static function getFriendStatus($userID,$friend_id){
        self::$db = Database::get();
        $count = count(self::$db->select("
            SELECT
                *
            FROM
                ".PREFIX."friends
            WHERE
            (
                (uid1 = :userID AND uid2 = :friend_id)
            OR
                (uid2 = :userID AND uid1 = :friend_id)
            )
            AND
            (
                status1 = 1 AND status2 = 1
            )
            GROUP BY
                uid1, uid2
            ORDER BY
                id
            ASC
        ",
        array(':userID' => $userID, ':friend_id' => $friend_id)));
        if($count > 0){
            /** Users are friends **/
            return "Friends";
        }else{
            /** Check for pending friend requests **/
            $count2 = count(self::$db->select("
                SELECT
                    *
                FROM
                    ".PREFIX."friends
                WHERE
                (
                    (uid1 = :userID AND uid2 = :friend_id)
                    OR
                    (uid2 = :userID AND uid1 = :friend_id)
                )
                AND
                (
                    status1 = 1 AND status2 = 0
                )
            ",
            array(':userID' => $userID, ':friend_id' => $friend_id)));
            if($count2 > 0){
                return "Pending";
            }else{
                return "None";
            }
        }
    }

    /**
    *
    *
    *
    */
    public static function getFriendsCount($userID){
        self::$db = Database::get();
        return count(self::$db->select("
            SELECT
                *
            FROM
                ".PREFIX."friends
            WHERE
            (
                uid1 = :userID
            OR
                uid2 = :userID
            )
            AND
            (
                status1 = 1 AND status2 = 1
            )
            GROUP BY
                uid1, uid2
        ", array(':userID' => $userID)));
    }

}
