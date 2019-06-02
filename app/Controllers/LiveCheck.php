<?php
/**
 * Live Check Controller
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.3.0
 */

namespace App\Controllers;

use App\System\Load;
use App\System\Controller;
use Libs\Database;

/**
 * Live Check for Email
 */

class LiveCheck extends Controller
{

    protected $db;

    public function __construct() {
        $this->db = Database::get();
    }

    /**
     * Define email live check and load template file
     */
    public function emailCheck()
    {
		if(isset($_POST['email'])){
			$email = $_POST['email'];
		}else if(isset($_POST['newemail'])){
			$email = $_POST['newemail'];
		}

		if(isSet($email))
		{
			$query = $this->db->select('SELECT email FROM '.PREFIX.'users WHERE email=:email',
					array(':email' => $email));
			$count = count($query);

			if($count == "0")
			{
				// Check input to be sure it meets the site standards for emails
				if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$data['welcomeMessage'] = "OK";
				}else{
					$data['welcomeMessage'] = "BAD";
				}
			}
			else
			{
				$data['welcomeMessage'] = "INUSE";
			}

			unset($email, $ttl_un_rows);
		}else{
            $data['welcomeMessage'] = "BAD";
        }

		Load::View('Members/LiveCheck', $data, '', '', false);

	}

    /**
     * Define email live check and load template file
     */
    public function userNameCheck()
    {
		(isset($_POST['username'])) ? $username = $_POST['username'] : $username = "";

		if(isSet($username))
		{
			$query = $this->db->select('SELECT username FROM '.PREFIX.'users WHERE username=:username',
					array(':username' => $username));
			$count = count($query);

			if($count == "0")
			{
				// Check input to be sure it meets the site standards for usernames
				if(!preg_match("/^[a-zA-Z\p{Cyrillic}0-9]+$/u", $username)){
					// UserName Chars wrong
					$data['welcomeMessage'] = "CHAR";
				}else{
					// UserName is good
					$data['welcomeMessage'] = "OK";
				}
			}
			else
			{
				$data['welcomeMessage'] = "INUSE";
			}

			unset($username, $ttl_un_rows);
		}

		Load::View('Members/LiveCheck', $data, '', '', false);

	}

}

?>
