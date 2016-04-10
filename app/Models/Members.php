<?php
/**
 * Members Models
 *
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0
 */

namespace App\Models;

use Core\Model;

class Members extends Model
{
    /**
     * Get all accounts that were activated
     * @return array
     */
    public function getActivatedAccounts()
    {
        return $this->db->select('SELECT * FROM '.PREFIX.'users WHERE isactive = true');
    }

    /**
     * Get all accounts that are on the Online table
     * @return array
     */
    public function getOnlineAccounts()
    {
        return $this->db->select('SELECT * FROM '.PREFIX.'users_online ');
    }

    /**
     * Get all members that are activated with info
     * @return array
     */
    public function getMembers()
    {
        return $this->db->select("
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
					u.userID ASC, g.groupID DESC");
    }

    /**
     * Get all info on members that are online
     * @return array
     */
    public function getOnlineMembers()
    {
        return $this->db->select("
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
					u.userID ASC, g.groupID DESC");
    }

    /**
     * Get specific user's info
     * @param $username
     * @return array
     */
    public function getUserProfile($user)
    {
      // Check to see if profile is being requeted by userID
      if(ctype_digit($user)){
        // Requeted profile information based on ID
        return $this->db->select("
          SELECT
            u.userID,
            u.username,
            u.firstName,
            u.gender,
            u.userImage,
            u.LastLogin,
            u.SignUp,
            u.website,
            u.aboutme,
            u.signature
          FROM " . PREFIX . "users u
          WHERE u.userID = :userID",
            array(':userID' => $user));
      }else{
        // Requested profile information based on Name
          return $this->db->select("
  					SELECT
  						u.userID,
  						u.username,
  						u.firstName,
  						u.gender,
  						u.userImage,
  						u.LastLogin,
  						u.SignUp,
  						u.website,
  						u.aboutme,
              u.signature
  					FROM " . PREFIX . "users u
  					WHERE u.username = :username",
              array(':username' => $user));
      }
    }

    public function getUserName($id)
    {
        return $this->db->select("SELECT userID,username FROM ".PREFIX."users WHERE userID=:id",array(":id"=>$id));
    }

    public function updateProfile($u_id, $firstName, $gender, $website, $userImage, $aboutme, $signature)
    {
        return $this->db->update(PREFIX.'users', array('firstName' => $firstName, 'gender' => $gender, 'userImage' => $userImage, 'website' => $website, 'aboutme' => $aboutme, 'signature' => $signature), array('userID' => $u_id));
    }
}
