<?php
namespace App\Models;

use Core\Model;
/**
 * This Model Class is used by Helpter/Auth/Auth
 * Class Auth
 * @package Models
 */
class Auth extends Model
{
    /**
     * Gets user account info by username
     * @param $username
     * @return array
     */
    public function getAccountInfo($username)
    {
        return $this->db->select("SELECT * FROM ".PREFIX."users WHERE username=:username", array(":username" => $username));
    }

    /**
     * Gets user account info by email
     * @param $email
     * @return array
     */
    public function getAccountInfoEmail($email)
    {
        return $this->db->select("SELECT * FROM ".PREFIX."users WHERE email=:email", array(":email" => $email));
    }

    /**
     * Delete user by username
     * @param $username
     * @return int : rows deleted
     */
    public function deleteUser($username)
    {
        return $this->db->delete(PREFIX."users", array("username" => $username));
    }

    /**
     * Gets session info by the hash
     * @param $hash
     * @return array
     */
    public function sessionInfo($hash)
    {
        return $this->db->select("SELECT uid, username, expiredate, ip FROM ".PREFIX."sessions WHERE hash=:hash", array(':hash' => $hash));
    }

    /**
     * Delete session by username
     * @param $username
     * @return int : rows deleted
     */
    public function deleteSession($username)
    {
        return $this->db->delete(PREFIX."sessions", array('username' => $username));
    }

    /**
     * Gets all attempts to login all accounts
     * @return array
     */
    public function getAttempts()
    {
        return $this->db->select("SELECT ip, expiredate FROM ".PREFIX."attempts");
    }

    /**
     * Gets login attempt by ip address
     * @param $ip
     * @return array
     */
    public function getAttempt($ip)
    {
        return $this->db->select("SELECT count FROM ".PREFIX."attempts WHERE ip=:ip", array(":ip" => $ip));
    }

    /**
     * Delete attempts of logging in
     * @param $where
     * @return int : deleted rows
     */
    public function deleteAttempt($where)
    {
        return $this->db->delete(PREFIX."attempts", $where);
    }

    /**
     * Add into DB
     * @param $table
     * @param $info
     * @return int : row id
     */
    public function addIntoDB($table,$info)
    {
        return $this->db->insert(PREFIX.$table,$info);
    }

    /**
     * Update in DB
     * @param $table
     * @param $info
     * @param $where
     * @return int
     */
    public function updateInDB($table,$info,$where)
    {
        return $this->db->update(PREFIX.$table,$info,$where);
    }

    /**
     * Get the user id by username
     * @param $username
     * @return array
     */
    public function getUserID($username)
    {
        return $this->db->select("SELECT userID FROM ".PREFIX."users WHERE username=:username", array(":username" => $username));
    }

    /**
     * Check is user is a New Member (groupID = 1)
     * @param $userID
     * @return array
     */
    public function getUserGroups($userID)
    {
        return $this->db->select("SELECT groupID FROM ".PREFIX."users_groups WHERE userID = :userID",array(':userID' => $userID));
    }

}
