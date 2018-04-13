<?php
/**
 * Members Models
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.2.0
 */

 namespace App\Models;

 use App\System\Models,
 Libs\Database;

class Members extends Models
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
    public function getMembers($orderby, $limit = null, $search = null)
    {
        // Set default orderby if one is not set
        if($orderby == "UG-DESC"){
          $run_order = "g.groupName DESC";
        }else if($orderby == "UG-ASC"){
          $run_order = "g.groupName ASC";
        }else if($orderby == "UN-DESC"){
          $run_order = "u.username DESC";
        }else if($orderby == "UN-ASC"){
          $run_order = "u.username ASC";
        }else{
          // Default order
          $run_order = "u.userID ASC";
        }

        if(isset($search)){
            // Load users that match search criteria and are active
            $users = $this->db->select("
				SELECT
					u.userID,
					u.username,
					u.firstName,
                    u.lastName,
                    u.userImage,
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
                    (u.username LIKE :search OR u.firstName LIKE :search OR u.lastName LIKE :search)
                AND
					u.isactive = true
				GROUP BY
					u.userID
                ORDER BY
                    $run_order
                    $limit
            ", array(':search' => '%'.$search.'%'));
        }else{
            // Load all active site members
            $users = $this->db->select("
                SELECT
                    u.userID,
                    u.username,
                    u.firstName,
                    u.lastName,
                    u.userImage,
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
                    $run_order
                $limit
            ");
        }

        return $users;
    }

    /**
    * getTotalMembers
    *
    * Gets total count of users that are active
    *
    * @return int count
    */
    public function getTotalMembers(){
      $data = $this->db->select("
          SELECT
            *
          FROM
            ".PREFIX."users
          WHERE
  					isactive = true
          ");
      return count($data);
    }

    /**
    * getTotalMembersSearch
    *
    * Gets total count of users found in search
    *
    * @return int count
    */
    public function getTotalMembersSearch($search = null){
      $data = $this->db->select("
            SELECT
                username,
                firstName,
                lastName
            FROM
                ".PREFIX."users
            WHERE
                (username LIKE :search OR firstName LIKE :search OR lastName LIKE :search)
            AND
  			    isactive = true
            GROUP BY
                userID
          ", array(':search' => '%'.$search.'%'));
      return count($data);
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
                    u.lastName,
                    u.userImage,
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
            u.lastName,
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
              u.lastName,
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

    public function updateProfile($u_id, $firstName, $lastName, $gender, $website, $userImage, $aboutme, $signature)
    {
        return $this->db->update(PREFIX.'users', array('firstName' => $firstName, 'lastName' => $lastName, 'gender' => $gender, 'userImage' => $userImage, 'website' => $website, 'aboutme' => $aboutme, 'signature' => $signature), array('userID' => $u_id));
    }

    public function updateUPrivacy($u_id, $privacy_massemail, $privacy_pm)
    {
        $data = $this->db->update(PREFIX.'users', array('privacy_massemail' => $privacy_massemail, 'privacy_pm' => $privacy_pm), array('userID' => $u_id));
        if(count($data) > 0){
          return true;
        }else{
          return false;
        }
    }
}
