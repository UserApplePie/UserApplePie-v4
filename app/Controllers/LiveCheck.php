<?php

namespace App\Controllers;

use Core\View;
use Core\Controller;
use Helpers\Database;

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
		}

		View::render('Members/LiveCheck', $data);

	}

    /**
     * Define email live check and load template file
     */
    public function userNameCheck()
    {
		$username = $_POST['username'];

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
					echo "CHAR";
				}else{
					// UserName is good
					echo "OK";
				}
			}
			else
			{
				$data['welcomeMessage'] = "INUSE";
			}

			unset($email, $ttl_un_rows);
		}

		View::render('Members/LiveCheck', $data);

	}

}

?>
