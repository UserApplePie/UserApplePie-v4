<?php
/**
 * Members Models
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
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
					u.isactive,
					ug.userID,
					ug.groupID,
					g.groupID,
					g.groupName,
					g.groupFontColor,
					g.groupFontWeight,
          ui.userImage
				FROM
					".PREFIX."users u
				LEFT JOIN
					".PREFIX."users_groups ug
					ON u.userID = ug.userID
				LEFT JOIN
					".PREFIX."groups g
					ON ug.groupID = g.groupID
        LEFT JOIN
          ".PREFIX."users_images ui
          ON u.userID = ui.userID
				WHERE
          (u.username LIKE :search OR u.firstName LIKE :search OR u.lastName LIKE :search)
        AND
					u.isactive = true
        AND
          ui.defaultImage = '1'
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
                    u.isactive,
                    ug.userID,
                    ug.groupID,
                    g.groupID,
                    g.groupName,
                    g.groupFontColor,
                    g.groupFontWeight,
                    ui.userImage
                FROM
                    ".PREFIX."users u
                LEFT JOIN
                    ".PREFIX."users_groups ug
                    ON u.userID = ug.userID
                LEFT JOIN
                    ".PREFIX."groups g
                    ON ug.groupID = g.groupID
                LEFT JOIN
                  ".PREFIX."users_images ui
                  ON u.userID = ui.userID
                WHERE
                  u.isactive = true
                AND
                  ui.defaultImage = '1'
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
					uo.userID,
					ug.userID,
					ug.groupID,
					g.groupID,
					g.groupName,
					g.groupFontColor,
					g.groupFontWeight,
          ui.userImage
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
        LEFT JOIN
          ".PREFIX."users_images ui
          ON u.userID = ui.userID
          WHERE defaultImage = '1'
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
        $profile_data = $this->db->select("
          SELECT
            u.userID,
            u.username,
            u.firstName,
            u.lastName,
            u.gender,
            u.LastLogin,
            u.SignUp,
            u.website,
            u.aboutme,
            u.signature
          FROM " . PREFIX . "users u
          WHERE u.userID = :userID
          ",
            array(':userID' => $user));
      }else{
        // Requested profile information based on Name
          $profile_data = $this->db->select("
  					SELECT
  						u.userID,
  						u.username,
  						u.firstName,
              u.lastName,
  						u.gender,
  						u.LastLogin,
  						u.SignUp,
  						u.website,
  						u.aboutme,
              u.signature
  					FROM " . PREFIX . "users u
  					WHERE u.username = :username
            ",
              array(':username' => $user));
      }
      if(isset($profile_data)){
        return $profile_data;
      }else{
        return false;
      }
    }

    public function getUserName($id)
    {
        return $this->db->select("SELECT userID,username FROM ".PREFIX."users WHERE userID=:id",array(":id"=>$id));
    }

    public function updateProfile($u_id, $firstName, $lastName, $gender, $website, $aboutme, $signature)
    {
        return $this->db->update(PREFIX.'users', array('firstName' => $firstName, 'lastName' => $lastName, 'gender' => $gender, 'website' => $website, 'aboutme' => $aboutme, 'signature' => $signature), array('userID' => $u_id));
    }

    public function updateUPrivacy($u_id, $privacy_massemail, $privacy_pm)
    {
        $data = $this->db->update(PREFIX.'users', array('privacy_massemail' => $privacy_massemail, 'privacy_pm' => $privacy_pm), array('userID' => $u_id));
        if($data > 0){
          return true;
        }else{
          return false;
        }
    }

    public function addUserImage($u_id, $userImage)
    {
        /* Check if image is set as default */
        $data = $this->db->select("SELECT userImage FROM ".PREFIX."users_images WHERE userID=:id AND defaultImage = '1' ",array(":id"=>$u_id));
        if(!empty($data[0]->userImage)){
          return $this->db->insert(PREFIX.'users_images', array('userID' => $u_id, 'userImage' => $userImage, 'defaultImage' => '0'));
        }else{
          return $this->db->insert(PREFIX.'users_images', array('userID' => $u_id, 'userImage' => $userImage, 'defaultImage' => '1'));
        }
    }

    public function getUserImageMain($id)
    {
        $data = $this->db->select("SELECT userImage FROM ".PREFIX."users_images WHERE userID=:id AND defaultImage = '1' ",array(":id"=>$id));

        return $data[0]->userImage;
    }

    public function getUserImages($id, $limit = '20')
    {
        $limit = "LIMIT ".$limit;
       return $this->db->select("SELECT userImage FROM ".PREFIX."users_images WHERE userID=:id ORDER BY timestamp DESC $limit",array(":id"=>$id));
    }

}
