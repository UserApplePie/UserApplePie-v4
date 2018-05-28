<?php

/**
 * Auth Class Plugin for UAP
 *
 * UserApplePie
 * @author David (DaVaR) Sargent <davar@userapplepie.com>
 * @version 4.2.0
 *
 * @author Jhobanny Morillo <geomorillo@yahoo.com>
 */

namespace Libs\Auth;

use App\Models\Auth as AuthModel,
	Libs\ErrorMessages,
	Libs\Language;

class Auth {

    public $errormsg;
    public $successmsg;
    public $authorize;
    public $language;

    public function __construct() {
		/* initialise the language object */
        $this->language = new Language();
        $this->language->load('Auth');
        $this->authorize = new AuthModel();
        $this->expireAttempt(); //expire attempts
    }

    /**
     * Log user in via MySQL Database
     * @param string $username
     * @param string $password
     * @param $remember
     * @return bool
     */
    public function login($username, $password,$remember) {
        if (!Cookie::get(SESSION_PREFIX."auth_session")) {
            $attcount = $this->getAttempt($_SERVER['REMOTE_ADDR']);

            if ($attcount[0]->count >= MAX_ATTEMPTS) {
                $this->errormsg[] = $this->language->get('login_lockedout');
                $this->errormsg[] = sprintf($this->language->get('login_wait'), WAIT_TIME);
								/* Error Message Display */
								ErrorMessages::push($this->errormsg[0], 'Login');
                return false;
            } else {
                // Input verification :
                if (strlen($username) == 0) {
                    $this->errormsg[] = $this->language->get('login_username_empty');
										/* Error Message Display */
										ErrorMessages::push($this->errormsg[0], 'Login');
                    return false;
                } elseif (strlen($username) > MAX_USERNAME_LENGTH) {
                    $this->errormsg[] = $this->language->get('login_username_long');
										/* Error Message Display */
										ErrorMessages::push($this->errormsg[0], 'Login');
                    return false;
                } elseif (strlen($username) < MIN_USERNAME_LENGTH) {
                    $this->errormsg[] = $this->language->get('login_username_short');
										/* Error Message Display */
										ErrorMessages::push($this->errormsg[0], 'Login');
                    return false;
                } elseif (strlen($password) == 0) {
                    $this->errormsg[] = $this->language->get('login_password_empty');
										/* Error Message Display */
										ErrorMessages::push($this->errormsg[0], 'Login');
                    return false;
                } elseif (strlen($password) > MAX_PASSWORD_LENGTH) {
                    $this->errormsg[] = $this->language->get('login_password_long');
										/* Error Message Display */
										ErrorMessages::push($this->errormsg[0], 'Login');
                    return false;
                } elseif (strlen($password) < MIN_PASSWORD_LENGTH) {
                    $this->errormsg[] = $this->language->get('login_password_short');
										/* Error Message Display */
										ErrorMessages::push($this->errormsg[0], 'Login');
                    return false;
                } else {
                    // Input is valid

                    $query = $this->authorize->getAccountInfo($username);
                    $count = count($query);
                    $hashed_db_password = $query[0]->password;
                    $verify_password = \Libs\Password::verify($password, $hashed_db_password);
                    if ($count == 0 || $verify_password == 0) {
                        // Username or password are wrong
                        $this->errormsg[] = $this->language->get('login_incorrect');
                        $this->addAttempt($_SERVER['REMOTE_ADDR']);
                        $attcount[0]->count = $attcount[0]->count + 1;
                        $remaincount = (int) MAX_ATTEMPTS - $attcount[0]->count;
                        $this->logActivity("UNKNOWN", "AUTH_LOGIN_FAIL", "Username / Password incorrect - {$username} / {$password}");
                        $this->errormsg[] = sprintf($this->language->get('login_attempts_remaining'), $remaincount);
												if(isset($this->errormsg)){
													$error_data = "<hr>";
													foreach($this->errormsg as $row){
														$error_data .= " - ".$row."<br>";
													}
												}else{
													$error_data = "";
												}
												/* Error Message Display */
												ErrorMessages::push('Login Info is Incorect. '.$error_data, 'Login');
                        return false;
                    } else {
                        // Username and password are correct
                        if ($query[0]->isactive == "0") {
                            // Account is not activated
                            $this->logActivity($username, "AUTH_LOGIN_FAIL", "Account inactive");
                            $this->errormsg[] = $this->language->get('login_account_inactive');
														/* Error Message Display */
														ErrorMessages::push($this->errormsg[0], 'Login');
                            return false;
                        } else {
                            // Account is activated
                            $this->newSession($username,$remember); //generate new cookie session
                            $this->logActivity($username, "AUTH_LOGIN_SUCCESS", "User logged in");
                            $this->successmsg[] = $this->language->get('login_success');
                            return true;
                        }
                    }
                }
            }
        } else {
            // User is already logged in
            $this->errormsg[] = $this->language->get('login_already'); // Is an user already logged in an error?
						/* Error Message Display */
						ErrorMessages::push($this->errormsg[0], '');
            return true; // its true because is logged in if not the function would not allow to log in
        }
    }

    /**
     * Logs out an user, deletes all sessions and destroys the cookies
     */
    public function logout() {
        $auth_session = Cookie::get(SESSION_PREFIX."auth_session");
        if ($auth_session != '') {
            $this->deleteSession($auth_session);
        }
    }

    /**
     * Checks if current user is logged or  not
     * @return boolean
     */
    public function isLogged() {
        $auth_session = Cookie::get(SESSION_PREFIX."auth_session"); //get hash from browser
        //check if session is valid
        return ($auth_session != '' && $this->sessionIsValid($auth_session));
    }

    /**
     * Provides an associateve array with current user's info
     * @return array
     */
    public function currentSessionInfo() {
        if ($this->isLogged()) {
            $auth_session = Cookie::get(SESSION_PREFIX."auth_session"); //get hash from browser
            return $this->sessionInfo($auth_session);
        }
    }

    /**
     * Provides an associative array of user info based on session hash
     * @param string $hash
     * @return array $session
     */
    private function sessionInfo($hash) {
        $query = $this->authorize->sessionInfo($hash);
        $count = count($query);
        if ($count == 0) {
            // Hash doesn't exist
            $this->errormsg[] = $this->language->get('sessioninfo_invalid');
            //setcookie(SESSION_PREFIX."auth_session", $hash, time() - 3600, '/');
            Cookie::destroy(SESSION_PREFIX.'auth_session', $hash); //check if destroys deletes only a specific hash
            //   \Libs\Cookie::set(SESSION_PREFIX."auth_session", $hash, time() - 3600, "/",$_SERVER["HTTP_HOST"]);
            return false;
        } else {
            // Hash exists
            $session["uid"] = $query[0]->uid;
            $session["username"] = $query[0]->username;
            $session["expiredate"] = $query[0]->expiredate;
            $session["ip"] = $query[0]->ip;
            return $session;
        }
    }

    /**
     * Checks if a hash session is valid on database
     * @param string $hash
     * @return boolean
     */
    private function sessionIsValid($hash) {
        //if hash in db
        $session = $this->authorize->sessionInfo($hash);
        $count = count($session);
        if ($count == 0) {
            //hash did not exists deleting cookie
            Cookie::destroy(SESSION_PREFIX."auth_session", $hash);
            //Cookie::destroy(SESSION_PREFIX."auth_session", $hash, '');
            //setcookie(SESSION_PREFIX."auth_session", $hash, time() - 3600, "/");
            $this->logActivity('UNKNOWN', "AUTH_CHECKSESSION", "User session cookie deleted - Hash ({$hash}) didn't exist");
            return false;
        } else {
            $username = $session[0]->username;
            $db_expiredate = $session[0]->expiredate;
            $db_ip = $session[0]->ip;
            if ($_SERVER['REMOTE_ADDR'] != $db_ip) {
                //hash exists but ip is changed, delete session and hash
                $this->authorize->deleteSession($username);
                Cookie::destroy(SESSION_PREFIX."auth_session", $hash);
                //setcookie(SESSION_PREFIX."auth_session", $hash, time() - 3600, "/");
                $this->logActivity($username, "AUTH_CHECKSESSION", "User session cookie deleted - IP Different ( DB : {$db_ip} / Current : " . $_SERVER['REMOTE_ADDR'] . " )");
                return false;
            } else {
                $expiredate = strtotime($db_expiredate);
                $currentdate = strtotime(date("Y-m-d H:i:s"));
                if ($currentdate > $expiredate) {
                    //session has expired delete session and cookies
                    $this->authorize->deleteSession($username);
                    Cookie::destroy(SESSION_PREFIX."auth_session", $hash);
                    //setcookie(SESSION_PREFIX."auth_session", $hash, time() - 3600, "/");
                    $this->logActivity($username, "AUTH_CHECKSESSION", "User session cookie deleted - Session expired ( Expire date : {$db_expiredate} )");
                } else {
                    //all ok
                    return true;
                }
            }
        }
    }

    /**
     * Provides amount of attempts already in database based on user's IP
     * @param string $ip
     * @return int $attempt_count
     */
    private function getAttempt($ip) {
        $attempt_count = $this->authorize->getAttempt($ip);
        $count = count($attempt_count);

        if ($count == 0) {
            $attempt_count[0] = new \stdClass();
            $attempt_count[0]->count = 0;
        }
        return $attempt_count;
    }

    /*
     * Adds a new attempt to database based on user's IP
     * @param string $ip
     */

    private function addAttempt($ip) {
        $query_attempt = $this->authorize->getAttempt($ip);
        $count = count($query_attempt);
        $attempt_expiredate = date("Y-m-d H:i:s", strtotime(SECURITY_DURATION));
        if ($count == 0) {
            // No record of this IP in attempts table already exists, create new
            $attempt_count = 1;
            $info = array("ip" => $ip, "count" => $attempt_count, "expiredate" => $attempt_expiredate);
            $this->authorize->addIntoDB('attempts',$info);
        } else {
            // IP Already exists in attempts table, add 1 to current count
            $attempt_count = intval($query_attempt[0]->count) + 1;
            $info = array("count" => $attempt_count, "expiredate" => $attempt_expiredate);
            $where = array("ip" => $ip);
            $this->authorize->updateInDB('attempts',$info, $where);
        }
    }

    /**
     * Used to remove expired attempt logs from database
     * (Currently used on __construct but need more testing)
     */
    private function expireAttempt() {
        $query_attempts = $this->authorize->getAttempts();
        $count = count($query_attempts);
        $curr_time = strtotime(date("Y-m-d H:i:s"));
        if ($count != 0) {
            foreach ($query_attempts as $attempt) {
                $attempt_expiredate = strtotime($attempt->expiredate);
                if ($attempt_expiredate <= $curr_time) {
                    $where = array("ip" => $attempt->ip);
                    $this->authorize->deleteAttempt($where);
                }
            }
        }
    }

    /**
     * Creates a new session for the provided username and sets cookie
     * @param string $username
     * @param bool $rememberMe
     */
    private function newSession($username,$rememberMe) {
        $hash = md5(microtime()); // unique session hash
        // Fetch User ID :
        $queryUid = $this->authorize->getUserID($username);
        $uid = $queryUid[0]->userID;
        // Delete all previous sessions :
        $this->authorize->deleteSession($username);
        $ip = $_SERVER['REMOTE_ADDR'];
        $expiredate = $rememberMe ? date("Y-m-d H:i:s", strtotime(SESSION_DURATION_RM)) : date("Y-m-d H:i:s", strtotime(SESSION_DURATION));
        $expiretime = strtotime($expiredate);
        $info = array("uid" => $uid, "username" => $username, "hash" => $hash, "expiredate" => $expiredate, "ip" => $ip);
        $this->authorize->addIntoDB("sessions", $info);
        Cookie::set(SESSION_PREFIX.'auth_session', $hash, $expiretime, "/", FALSE);
    }

    /**
     * Deletes a session based on a hash
     * @param string $hash
     */
    private function deleteSession($hash) {

        $query_username = $this->authorize->sessionInfo($hash);
        $count = count($query_username);
        if ($count == 0) {
            // Hash doesn't exist
            $this->logActivity("UNKNOWN", "AUTH_LOGOUT", "User session cookie deleted - Database session not deleted - Hash ({$hash}) didn't exist");
            $this->errormsg[] = $this->language->get('deletesession_invalid');
        } else {
            $username = $query_username[0]->username;
            // Hash exists, Delete all sessions for that username :
            $this->authorize->deleteSession($username);
            $this->logActivity($username, "AUTH_LOGOUT", "User session cookie deleted - Database session deleted - Hash ({$hash})");
            Cookie::destroy(SESSION_PREFIX."auth_session", $hash);
        }
    }

    /**
     * Directly register an user without sending email confirmation
     * @param string $username
     * @param string $password
     * @param string $verifypassword
     * @param string $email
     * @return boolean If succesfully registered true false otherwise
     */
    public function directRegister($username, $password, $verifypassword, $email) {
        if (!Cookie::get(SESSION_PREFIX.'auth_session')) {
            // Input Verification :
            if (strlen($username) == 0) {
                $this->errormsg[] = $this->language->get('register_username_empty');
            } elseif (strlen($username) > MAX_USERNAME_LENGTH) {
                $this->errormsg[] = $this->language->get('register_username_long');
            } elseif (strlen($username) < MIN_USERNAME_LENGTH) {
                $this->errormsg[] = $this->language->get('register_username_short');
            } elseif (!preg_match("/^[a-zA-Z\p{Cyrillic}0-9]+$/u", $username)) {
							$this->errormsg[] = $this->language->get('Username is Invalid');
						}
            if (strlen($password) == 0) {
                $this->errormsg[] = $this->language->get('register_password_empty');
            } elseif (strlen($password) > MAX_PASSWORD_LENGTH) {
                $this->errormsg[] = $this->language->get('register_password_long');
            } elseif (strlen($password) < MIN_PASSWORD_LENGTH) {
                $this->errormsg[] = $this->language->get('register_password_short');
            } elseif ($password !== $verifypassword) {
                $this->errormsg[] = $this->language->get('register_password_nomatch');
            } elseif (strstr($password, $username)) {
                $this->errormsg[] = $this->language->get('register_password_username');
            }
            if (strlen($email) == 0) {
                $this->errormsg[] = $this->language->get('register_email_empty');
            } elseif (strlen($email) > MAX_EMAIL_LENGTH) {
                $this->errormsg[] = $this->language->get('register_email_long');
            } elseif (strlen($email) < MIN_EMAIL_LENGTH) {
                $this->errormsg[] = $this->language->get('register_email_short');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errormsg[] = $this->language->get('register_email_invalid');
            }
            if (!isset($this->errormsg) || count($this->errormsg) == 0) {
                // Input is valid
                $query = $this->authorize->getAccountInfo($username);
                $count = count($query);
                if ($count != 0) {
                    // Username already exists
                    $this->logActivity("UNKNOWN", "AUTH_REGISTER_FAIL", "Username ({$username}) already exists");
                    $this->errormsg[] = $this->language->get('register_username_exist');
                    return false;
                } else {
                    // Check to see if E-Mail exists
                    $query = $this->authorize->getAccountInfoEmail($email);
                    $count = count($query);
                    if ($count != 0) {
                        // Email Alread Exists
                        $this->logActivity("UNKNOWN", "AUTH_REGISTER_FAIL", "Email ({$email}) already exists");
                        $this->errormsg[] = $this->language->get('register_email_exist');
                        return false;
                    } else {
                        // Everything looks good, sign user up
                        $password = $this->hashPass($password);
                        $activekey = $this->randomKey(RANDOM_KEY_LENGTH); // Create a random key for account activation
                        $info = array("username" => $username, "password" => $password, "email" => $email, "activekey" => $activekey, "userImage"=>"default-".rand(1,5).".jpg", "pass_change_timestamp" => date("Y-m-d H:i:s"));
                        $user_id = $this->authorize->addIntoDB("users", $info);

                        $info = array('userID' => $user_id, 'groupID' => 1);
                        $this->authorize->addIntoDB("users_groups",$info);

                        $this->logActivity($username, "AUTH_REGISTER_SUCCESS", "Account created");
                        $this->successmsg[] = $this->language->get('register_success');
                        // Everything looks good.  Now activate account
                        $this->activateAccount($username, $activekey); // Activates User's Account
                        return true;
                    }
                }
            } else {
								$this->logActivity($username, "AUTH_REGISTER_FAIL", "User Info Standards Not Met");
								if(isset($this->errormsg)){
									$error_data = "<hr>";
									foreach($this->errormsg as $row){
										$error_data .= " - ".$row."<br>";
									}
								}else{
									$error_data = "";
								}
								/* Error Message Display */
								ErrorMessages::push($this->language->get('register_error')." ".$error_data, 'Register');
                return false; // Return Error
            }
        } else {
            // User is logged in
            $this->errormsg[] = $this->language->get('register_email_loggedin');
						$this->logActivity($username, "AUTH_REGISTER_FAIL", "Error With Site Cookie");
						/* Error Message Display */
            ErrorMessages::push($this->language->get('register_email_loggedin'), 'Register');
            return false;
        }
    }

    /**
     * Register a new user into the database
     * @param string $username
     * @param string $password
     * @param string $verifypassword
     * @param string $email
     * @return boolean
     */
    public function register($username, $password, $verifypassword, $email) {
        if (!Cookie::get(SESSION_PREFIX.'auth_session')) {
            // Input Verification :
            if (strlen($username) == 0) {
                $this->errormsg[] = $this->language->get('register_username_empty');
            } elseif (strlen($username) > MAX_USERNAME_LENGTH) {
                $this->errormsg[] = $this->language->get('register_username_long');
            } elseif (strlen($username) < MIN_USERNAME_LENGTH) {
                $this->errormsg[] = $this->language->get('register_username_short');
            } elseif (!preg_match("/^[a-zA-Z\p{Cyrillic}0-9]+$/u", $username)) {
								$this->errormsg[] = $this->language->get('Username is Invalid');
						}
            if (strlen($password) == 0) {
                $this->errormsg[] = $this->language->get('register_password_empty');
            } elseif (strlen($password) > MAX_PASSWORD_LENGTH) {
                $this->errormsg[] = $this->language->get('register_password_long');
            } elseif (strlen($password) < MIN_PASSWORD_LENGTH) {
                $this->errormsg[] = $this->language->get('register_password_short');
            } elseif ($password !== $verifypassword) {
                $this->errormsg[] = $this->language->get('register_password_nomatch');
            } elseif (strstr($password, $username)) {
                $this->errormsg[] = $this->language->get('register_password_username');
            }
            if (strlen($email) == 0) {
                $this->errormsg[] = $this->language->get('register_email_empty');
            } elseif (strlen($email) > MAX_EMAIL_LENGTH) {
                $this->errormsg[] = $this->language->get('register_email_long');
            } elseif (strlen($email) < MIN_EMAIL_LENGTH) {
                $this->errormsg[] = $this->language->get('register_email_short');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->errormsg[] = $this->language->get('register_email_invalid');
            }
            if (!isset($this->errormsg) || count($this->errormsg) == 0) {
                // Input is valid
                $query = $this->authorize->getAccountInfo($username);
                $count = count($query);
                if ($count != 0) {
                    // Username already exists
                    $this->logActivity("UNKNOWN", "AUTH_REGISTER_FAIL", "Username ({$username}) already exists");
                    $this->errormsg[] = $this->language->get('register_username_exist');
                    return false;
                } else {
                    // Username is not taken
                    $query = $this->authorize->getAccountInfoEmail($email);
                    $count = count($query);
                    if ($count != 0) {
                        // Email address is already used
                        $this->logActivity("UNKNOWN", "AUTH_REGISTER_FAIL", "Email ({$email}) already exists");
                        $this->errormsg[] = $this->language->get('register_email_exist');
                        return false;
                    } else {
                        // Email address isn't already used
                        $password = $this->hashPass($password);
                        $activekey = $this->randomKey(RANDOM_KEY_LENGTH);
                        $info = array("username" => $username, "password" => $password, "email" => $email, "activekey" => $activekey, "userImage"=>"default-".rand(1,5).".jpg", "pass_change_timestamp" => date("Y-m-d H:i:s"));
                        $user_id = $this->authorize->addIntoDB("users", $info);

                        $info = array('userID' => $user_id, 'groupID' => 1);
                        $this->authorize->addIntoDB("users_groups",$info);
                        //EMAIL MESSAGE USING PHPMAILER
                        $mail = new \Libs\PhpMailer\Mail();
                        $mail->addAddress($email);
												$mail->setFrom(SITEEMAIL, EMAIL_FROM_NAME);
                        $mail->subject(SITE_TITLE. " - EMAIL VERIFICATION");
                        $body = $this->language->get('regi_email_hello')." {$username}<br/><br/>";
                        $body .= $this->language->get('regi_email_recently_registered')." ".SITE_TITLE."<br/>";
                        $body .= $this->language->get('regi_email_to_activate')."<br/><br/>";
                        $body .= "<b><a href='".SITE_URL.ACTIVATION_ROUTE."/username/{$username}/key/{$activekey}'>".$this->language->get('regi_email_act_my_acc')."</a></b>";
                        $body .= "<br><br> ".$this->language->get('regi_email_you_may_copy').": <br>";
                        $body .= SITE_URL.ACTIVATION_ROUTE."/username/{$username}/key/{$activekey}";
                        $mail->body($body);
                        $mail->send();
                        $this->logActivity($username, "AUTH_REGISTER_SUCCESS", "Account created and activation email sent");
                        $this->successmsg[] = $this->language->get('register_success');
                        return true;
                    }
                }
            } else {
                //some error
								$this->logActivity($username, "AUTH_REGISTER_FAIL", "User Info Standards Not Met");
								if(isset($this->errormsg)){
									$error_data = "<hr>";
									foreach($this->errormsg as $row){
										$error_data .= " - ".$row."<br>";
									}
								}else{
									$error_data = "";
								}
								/* Error Message Display */
								ErrorMessages::push($this->language->get('register_error')." ".$error_data, 'Register');
                return false;
            }
        } else {
            // User is logged in
						$this->logActivity($username, "AUTH_REGISTER_FAIL", "User Already Logged In");
						/* Error Message Display */
            ErrorMessages::push($this->language->get('register_email_loggedin'), 'Register');
            return false;
        }
    }

    /**
     * Activates an account
     * @param string $username
     * @param string $key
     * @return boolean
     */
    public function activateAccount($username, $key) {

        //get if username is active and its activekey
        $query_active = $this->authorize->getAccountInfo($username);

        //username exists
        if(sizeof($query_active)>0){
            $db_isactive = $query_active[0]->isactive;
            $db_key = $query_active[0]->activekey;

            //username is already activated
            if($db_isactive){
                $this->logActivity($username, "AUTH_ACTIVATE_ERROR", "Activation failed. Account already activated.");
								ErrorMessages::push($this->language->get('activate_account_activated'), 'Login');
                return false;
            }
            else{
                //key is same as in database
                if($db_key == $key){

                    $info = array("isactive" => 1, "activekey" => 0);
                    $where = array("username" => $username);
                    $activated = $this->authorize->updateInDB("users", $info, $where);

                    //accounct activated only if the db class returns number of rows affected
                    if ($activated > 0) {
                        $this->logActivity($username, "AUTH_ACTIVATE_SUCCESS", "Activation successful. Key Entry deleted.");
                        return true;
                    }
                    //somehow the activation failed... After all the checks from above, it SHOULD NEVER reach this point
                    else{
                        $this->logActivity($username, "AUTH_ACTIVATE_ERROR", "Activation failed.");
                        return false;
                    }
                }
                //key is not same as in database
                else{
                    $this->logActivity($username, "AUTH_ACTIVATE_ERROR", "Activation failed. Incorrect key.");
										ErrorMessages::push($this->language->get('activate_key_incorrect'), 'Resend-Activation-Email');
                    return false;
                }
            }
        }
        //username doesn't exist
        else{
            $this->logActivity($username, "AUTH_ACTIVATE_ERROR", "Activation failed. Invalid username.");
						ErrorMessages::push($this->language->get('activate_username_incorrect'), 'Resend-Activation-Email');
            return false;
        }
    }

    /**
     * Logs users actions on the site to database for future viewing
     * @param string $username
     * @param string $action
     * @param string $additionalinfo
     * @return boolean
     */
    public function logActivity($username, $action, $additionalinfo = "none") {
        if (strlen($username) == 0) {
            $username = "GUEST";
        } elseif (strlen($username) < MIN_USERNAME_LENGTH) {
            $this->errormsg[] = $this->language->get('logactivity_username_short');
            return false;
        } elseif (strlen($username) > MAX_USERNAME_LENGTH) {
            $this->errormsg[] = $this->language->get('logactivity_username_long');
            return false;
        }
        if (strlen($action) == 0) {
            $this->errormsg[] = $this->language->get('logactivity_action_empty');
            return false;
        } elseif (strlen($action) < 3) {
            $this->errormsg[] = $this->language->get('logactivity_action_short');
            return false;
        } elseif (strlen($action) > 100) {
            $this->errormsg[] = $this->language->get('logactivity_action_long');
            return false;
        }
        if (strlen($additionalinfo) == 0) {
            $additionalinfo = "none";
        } elseif (strlen($additionalinfo) > 500) {
            $this->errormsg[] = $this->language->get('logactivity_addinfo_long');
            return false;
        }
        if (!isset($this->errormsg) || count($this->errormsg) == 0) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $date = date("Y-m-d H:i:s");
            $info = array("date" => $date, "username" => $username, "action" => $action, "additionalinfo" => $additionalinfo, "ip" => $ip);
            $this->authorize->addIntoDB("activitylog", $info);
            return true;
        }
    }

    /**
     * Hash user's password with BCRYPT algorithm and non static salt !
     * @param string $password
     * @return string $hashed_password
     */
    private function hashPass($password) {
        // this options should be on Config.php
        $options = [
            'cost' => COST
        ];
        return \Libs\Password::make($password, PASSWORD_BCRYPT, $options);
    }

    /**
     * Returns a random string, length can be modified
     * @param int $length
     * @return string $key
     */
    private function randomKey($length = 10) {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890";
        $key = "";
        for ($i = 0; $i < $length; $i++) {
            $key .= $chars{rand(0, strlen($chars) - 1)};
        }
        return $key;
    }

    /**
     * Changes a user's password, providing the current password is known
     * @param string $username
     * @param string $currpass
     * @param string $newpass
     * @param string $verifynewpass
     * @return boolean
     */
    function changePass($username, $currpass, $newpass, $verifynewpass) {
        if (strlen($username) == 0) {
            $this->errormsg[] = $this->language->get('changepass_username_empty');
        } elseif (strlen($username) > MAX_USERNAME_LENGTH) {
            $this->errormsg[] = $this->language->get('changepass_username_long');
        } elseif (strlen($username) < MIN_USERNAME_LENGTH) {
            $this->errormsg[] = $this->language->get('changepass_username_short');
        }
        if (strlen($currpass) == 0) {
            $this->errormsg[] = $this->language->get('changepass_currpass_empty');
        } elseif (strlen($currpass) < MIN_PASSWORD_LENGTH) {
            $this->errormsg[] = $this->language->get('changepass_currpass_short');
        } elseif (strlen($currpass) > MAX_PASSWORD_LENGTH) {
            $this->errormsg[] = $this->language->get('changepass_currpass_long');
        }
        if (strlen($newpass) == 0) {
            $this->errormsg[] = $this->language->get('changepass_newpass_empty');
        } elseif (strlen($newpass) < MIN_PASSWORD_LENGTH) {
            $this->errormsg[] = $this->language->get('changepass_newpass_short');
        } elseif (strlen($newpass) > MAX_PASSWORD_LENGTH) {
            $this->errormsg[] = $this->language->get('changepass_newpass_long');
        } elseif (strstr($newpass, $username)) {
            $this->errormsg[] = $this->language->get('changepass_password_username');
        } elseif ($newpass !== $verifynewpass) {
            $this->errormsg[] = $this->language->get('changepass_password_nomatch');
        }
        if (!isset($this->errormsg) || count($this->errormsg) == 0) {
            //$currpass = $this->hashPass($currpass);
            $newpass = $this->hashPass($newpass);
            $query = $this->authorize->getAccountInfo($username);
            $count = count($query);
            if ($count == 0) {
                $this->logActivity("UNKNOWN", "AUTH_CHANGEPASS_FAIL", "Username Incorrect ({$username})");
                $this->errormsg[] = $this->language->get('changepass_username_incorrect');
                return false;
            } else {
                $db_currpass = $query[0]->password;
                $verify_password = \Libs\Password::verify($currpass, $db_currpass);
                if ($verify_password) {
                    $info = array("password" => $newpass, "pass_change_timestamp" => date("Y-m-d H:i:s"));
                    $where = array("username" => $username);
                    $this->authorize->updateInDB('users',$info,$where);
                    $this->logActivity($username, "AUTH_CHANGEPASS_SUCCESS", "Password changed");
                    $this->successmsg[] = $this->language->get('changepass_success');
                    return true;
                } else {
                    $this->logActivity($username, "AUTH_CHANGEPASS_FAIL", "Current Password Incorrect ( DB : {$db_currpass} / Given : {$currpass} )");
                    $this->errormsg[] = $this->language->get('changepass_currpass_incorrect');
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Changes the stored email address based on username
     * @param string $username
     * @param string $email
     * @param string $password
     * @return boolean
     */
    function changeEmail($username, $email, $password) {

        // Get Current Password From Database
        $query = $this->authorize->getAccountInfo($username);
        $db_currpass = $query[0]->password;
        // Verify Current Password With Database Password
        $verify_password = \Libs\Password::verify($password, $db_currpass);

        // Make sure Password is good to go.
        if (strlen($password) == 0) {
            $this->errormsg[] = $this->language->get('changepass_currpass_empty');
        } elseif (strlen($password) < MIN_PASSWORD_LENGTH) {
            $this->errormsg[] = $this->language->get('changepass_currpass_short');
        } elseif (strlen($password) > MAX_PASSWORD_LENGTH) {
            $this->errormsg[] = $this->language->get('changepass_currpass_long');
        } elseif (!$verify_password){
            $this->errormsg[] = $this->language->get('changepass_currpass_incorrect');
        }
        if (strlen($username) == 0) {
            $this->errormsg[] = $this->language->get('changeemail_username_empty');
        } elseif (strlen($username) > MAX_USERNAME_LENGTH) {
            $this->errormsg[] = $this->language->get('changeemail_username_long');
        } elseif (strlen($username) < MIN_USERNAME_LENGTH) {
            $this->errormsg[] = $this->language->get('changeemail_username_short');
        }
        if (strlen($email) == 0) {
            $this->errormsg[] = $this->language->get('changeemail_email_empty');
        } elseif (strlen($email) > MAX_EMAIL_LENGTH) {
            $this->errormsg[] = $this->language->get('changeemail_email_long');
        } elseif (strlen($email) < MIN_EMAIL_LENGTH) {
            $this->errormsg[] = $this->language->get('changeemail_email_short');
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->errormsg[] = $this->language->get('changeemail_email_invalid');
        }
        if (!isset($this->errormsg) || count($this->errormsg) == 0) {
            $query = $this->authorize->getAccountInfo($username);
            $count = count($query);
            if ($count == 0) {
                $this->logActivity("UNKNOWN", "AUTH_CHANGEEMAIL_FAIL", "Username Incorrect ({$username})");
                $this->errormsg[] = $this->language->get('changeemail_username_incorrect');
                return false;
            } else {
                $db_email = $query[0]->email;
                if ($email == $db_email) {
                    $this->logActivity($username, "AUTH_CHANGEEMAIL_FAIL", "Old and new email matched ({$email})");
                    $this->errormsg[] = $this->language->get('changeemail_email_match');
                    return false;
                } else {
                    $info = array("email" => $email);
                    $where = array("username" => $username);
                    $this->authorize->updateInDB("users", $info, $where);
                    $this->logActivity($username, "AUTH_CHANGEEMAIL_SUCCESS", "Email changed from {$db_email} to {$email}");
                    $this->successmsg[] = $this->language->get('changeemail_success');
                    return true;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Give the user the ability to change their password if the current password is forgotten
     * by sending email to the email address associated to that user
     * @param string $email
     * @param string $username
     * @param string $key
     * @param string $newpass
     * @param string $verifynewpass
     * @return boolean
     */
    function resetPass($email = '0', $username = '0', $key = '0', $newpass = '0', $verifynewpass = '0') {
        $attcount = $this->getAttempt($_SERVER['REMOTE_ADDR']);
        if ($attcount[0]->count >= MAX_ATTEMPTS) {
            $this->errormsg[] = $this->language->get('resetpass_lockedout');
            $this->errormsg[] = sprintf($this->language->get('resetpass_wait'), WAIT_TIME);
            return false;
        } else {
            if ($username == '0' && $key == '0') {
                if (strlen($email) == 0) {
                    $this->errormsg[] = $this->language->get('resetpass_email_empty');
                } elseif (strlen($email) > MAX_EMAIL_LENGTH) {
                    $this->errormsg[] = $this->language->get('resetpass_email_long');
                } elseif (strlen($email) < MIN_EMAIL_LENGTH) {
                    $this->errormsg[] = $this->language->get('resetpass_email_short');
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $this->errormsg[] = $this->language->get('resetpass_email_invalid');
                }

                $query = $this->authorize->getAccountInfoEmail($email);
                $count = count($query);
                if ($count == 0) {
                    $this->errormsg[] = $this->language->get('resetpass_email_incorrect');
                    $attcount[0]->count = $attcount[0]->count + 1;
                    $remaincount = (int) MAX_ATTEMPTS - $attcount[0]->count;
                    $this->logActivity("UNKNOWN", "AUTH_RESETPASS_FAIL", "Email incorrect ({$email})");
                    $this->errormsg[] = sprintf($this->language->get('resetpass_attempts_remaining'), $remaincount);
                    $this->addAttempt($_SERVER['REMOTE_ADDR']);
                    return false;
                } else {
                    $resetkey = $this->randomKey(RANDOM_KEY_LENGTH);
                    $username = $query[0]->username;
                    $info = array("resetkey" => $resetkey);
                    $where = array("username" => $username);
                    $this->authorize->updateInDB("users",$info , $where);

                    //EMAIL MESSAGE USING PHPMAILER
                    $mail = new \Libs\PhpMailer\Mail();
                    $mail->addAddress($email);
                    $mail->subject(SITE_TITLE . " - Password reset request !");
                    $body = "Hello {$username}<br/><br/>";
                    $body .= "You recently requested a password reset on " . SITE_TITLE . "<br/>";
                    $body .= "To proceed with the password reset, please click the following link :<br/><br/>";
                    $body .= "<b><a href='".SITE_URL.RESET_PASSWORD_ROUTE."/username/{$username}/key/{$resetkey}'>Reset My Password</a></b>";
                    $mail->body($body);
                    $mail->send();
                    $this->logActivity($username, "AUTH_RESETPASS_SUCCESS", "Reset pass request sent to {$email} ( Key : {$resetkey} )");
                    $this->successmsg[] = $this->language->get('resetpass_email_sent');
                    return true;
                }
            } else {
                // if username, key  and newpass are provided
                // Reset Password
                if (strlen($key) == 0) {
                    $this->errormsg[] = $this->language->get('resetpass_key_empty');
                } elseif (strlen($key) < RANDOM_KEY_LENGTH) {
                    $this->errormsg[] = $this->language->get('resetpass_key_short');
                } elseif (strlen($key) > RANDOM_KEY_LENGTH) {
                    $this->errormsg[] = $this->language->get('resetpass_key_long');
                }
                if (strlen($newpass) == 0) {
                    $this->errormsg[] = $this->language->get('resetpass_newpass_empty');
                } elseif (strlen($newpass) > MAX_PASSWORD_LENGTH) {
                    $this->errormsg[] = $this->language->get('resetpass_newpass_long');
                } elseif (strlen($newpass) < MIN_PASSWORD_LENGTH) {
                    $this->errormsg[] = $this->language->get('resetpass_newpass_short');
                } elseif (strstr($newpass, $username)) {
                    $this->errormsg[] = $this->language->get('resetpass_newpass_username');
                } elseif ($newpass !== $verifynewpass) {
                    $this->errormsg[] = $this->language->get('resetpass_newpass_nomatch');
                }
                if (!isset($this->errormsg) || count($this->errormsg) == 0) {
                    $query = $this->authorize->getAccountInfo($username);
                    $count = count($query);
                    if ($count == 0) {
                        $this->errormsg[] = $this->language->get('resetpass_username_incorrect');
                        $attcount[0]->count = $attcount[0]->count + 1;
                        $remaincount = (int) MAX_ATTEMPTS - $attcount[0]->count;
                        $this->logActivity("UNKNOWN", "AUTH_RESETPASS_FAIL", "Username incorrect ({$username})");
                        $this->errormsg[] = sprintf($this->language->get('resetpass_attempts_remaining'), $remaincount);
                        $this->addAttempt($_SERVER['REMOTE_ADDR']);
                        return false;
                    } else {
                        $db_key = $query[0]->resetkey;
                        if ($key == $db_key) {
                            //if reset key ok update pass
                            $newpass = $this->hashpass($newpass);
                            $resetkey = '0';
                            $info = array("password" => $newpass, "resetkey" => $resetkey);
                            $where = array("username" => $username);
                            $this->authorize->updateInDB("users", $info, $where);
                            $this->logActivity($username, "AUTH_RESETPASS_SUCCESS", "Password reset - Key reset");
                            $this->successmsg[] = $this->language->get('resetpass_success');
                            return true;
                        } else {
                            $this->errormsg[] = $this->language->get('resetpass_key_incorrect');
                            $attcount[0]->count = $attcount[0]->count + 1;
                            $remaincount = (int) MAX_ATTEMPTS - $attcount[0]->count;
                            $this->logActivity($username, "AUTH_RESETPASS_FAIL", "Key Incorrect ( DB : {$db_key} / Given : {$key} )");
                            $this->errormsg[] = sprintf($this->language->get('resetpass_attempts_remaining'), $remaincount);
                            $this->addAttempt($_SERVER['REMOTE_ADDR']);
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }
        }
    }

    /**
     * Checks if the reset key is correct for provided username
     * @param string $username
     * @param string $key
     * @return boolean
     */
    function checkResetKey($username, $key) {
        $attcount = $this->getAttempt($_SERVER['REMOTE_ADDR']);
        if ($attcount[0]->count >= MAX_ATTEMPTS) {
            $this->errormsg[] = $this->language->get('resetpass_lockedout');
            $this->errormsg[] = sprintf($this->language->get('resetpass_wait'), WAIT_TIME);
            return false;
        } else {
            if (strlen($username) == 0) {
                return false;
            } elseif (strlen($username) > MAX_USERNAME_LENGTH) {
                return false;
            } elseif (strlen($username) < MIN_USERNAME_LENGTH) {
                return false;
            } elseif (strlen($key) == 0) {
                return false;
            } elseif (strlen($key) < RANDOM_KEY_LENGTH) {
                return false;
            } elseif (strlen($key) > RANDOM_KEY_LENGTH) {
                return false;
            } else {
                $query = $this->authorize->getAccountInfo($username);
                $count = count($query);
                if ($count == 0) {
                    $this->logActivity("UNKNOWN", "AUTH_CHECKRESETKEY_FAIL", "Username doesn't exist ({$username})");
                    $this->addAttempt($_SERVER['REMOTE_ADDR']);
                    $this->errormsg[] = $this->language->get('checkresetkey_username_incorrect');
                    $attcount[0]->count = $attcount[0]->count + 1;
                    $remaincount = (int) MAX_ATTEMPTS - $attcount[0]->count;
                    $this->errormsg[] = sprintf($this->language->get('checkresetkey_attempts_remaining'), $remaincount);
                    return false;
                } else {
                    $db_key = $query[0]->resetkey;
                    if ($key == $db_key) {
                        return true;
                    } else {
                        $this->logActivity($username, "AUTH_CHECKRESETKEY_FAIL", "Key provided is different to DB key ( DB : {$db_key} / Given : {$key} )");
                        $this->addAttempt($_SERVER['REMOTE_ADDR']);
                        $this->errormsg[] = $this->language->get('checkresetkey_key_incorrect');
                        $attcount[0]->count = $attcount[0]->count + 1;
                        $remaincount = (int) MAX_ATTEMPTS - $attcount[0]->count;
                        $this->errormsg[] = sprintf($this->language->get('checkresetkey_attempts_remaining'), $remaincount);
                        return false;
                    }
                }
            }
        }
    }

    /**
     * Deletes a user's account. Requires user's password
     * @param string $username
     * @param string $password
     * @return boolean
     */
    function deleteAccount($username, $password) {
        if (strlen($username) == 0) {
            $this->errormsg[] = $this->language->get('deleteaccount_username_empty');
        } elseif (strlen($username) > MAX_USERNAME_LENGTH) {
            $this->errormsg[] = $this->language->get('deleteaccount_username_long');
        } elseif (strlen($username) < MIN_USERNAME_LENGTH) {
            $this->errormsg[] = $this->language->get('deleteaccount_username_short');
        }
        if (strlen($password) == 0) {
            $this->errormsg[] = $this->language->get('deleteaccount_password_empty');
        } elseif (strlen($password) > MAX_PASSWORD_LENGTH) {
            $this->errormsg[] = $this->language->get('deleteaccount_password_long');
        } elseif (strlen($password) < MIN_PASSWORD_LENGTH) {
            $this->errormsg[] = $this->language->get('deleteaccount_password_short');
        }
        if (!isset($this->errormsg) || count($this->errormsg) == 0) {

            $query = $this->authorize->getAccountInfo($username);
            $count = count($query);
            if ($count == 0) {
                $this->logActivity("UNKNOWN", "AUTH_DELETEACCOUNT_FAIL", "Username Incorrect ({$username})");
                $this->errormsg[] = $this->language->get('deleteaccount_username_incorrect');
                return false;
            } else {
                $db_password = $query[0]->password;
                $verify_password = \Libs\Password::verify($password, $db_password);
                if ($verify_password) {
                    $this->authorize->deleteUser($username);
                    $this->authorize->deleteSession($username);
                    $this->logActivity($username, "AUTH_DELETEACCOUNT_SUCCESS", "Account deleted - Sessions deleted");
                    $this->successmsg[] = $this->language->get('deleteaccount_success');
                    return true;
                } else {
                    $this->logActivity($username, "AUTH_DELETEACCOUNT_FAIL", "Password incorrect ( DB : {$db_password} / Given : {$password} )");
                    $this->errormsg[] = $this->language->get('deleteaccount_password_incorrect');
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    /**
     * Check to see if email exists in users database
     * @param $email
     * @return bool
     */
    public function checkIfEmail($email){
        return $this->authorize->getAccountInfoEmail($email);
    }

    /**
     * Resends email verification
     * @param $email
     * @return bool
     * @throws \Libs\PhpMailer\phpmailerException
     */
    public function resendActivation($email)
    {
        if (!Cookie::get(SESSION_PREFIX.'auth_session')) {
            // Input Verification :
            if (strlen($email) == 0) {
                $auth_error[] = $this->language->get('register_email_empty');
            } elseif (strlen($email) > MAX_EMAIL_LENGTH) {
                $auth_error[] = $this->language->get('register_email_long');
            } elseif (strlen($email) < MIN_EMAIL_LENGTH) {
                $auth_error[] = $this->language->get('register_email_short');
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $auth_error[] = $this->language->get('register_email_invalid');
            }
            if (count($auth_error) == 0) {
                // Input is valid
                // Check DataBase to see if email user is activated
                $query = $this->authorize->getAccountInfoEmail($email);
                $count = count($query);
                if ($count != 0 && $query[0]->isactive == 0) {
                    // User Account Is not yet active.  Lets get data to resend their activation with new key
                    $username = $query[0]->username;
                    $activekey = $this->randomKey(RANDOM_KEY_LENGTH);
                    // Store the new key in the user's database
                    $info = array('activekey' => $activekey);
                    $where = array('username' => $username);
                    $this->authorize->updateInDB('users',$info,$where);
                    //EMAIL MESSAGE USING PHPMAILER
                    $mail = new \Libs\PhpMailer\Mail();
                    $mail->addAddress($email);
                    $mail->subject(SITE_TITLE . " - Account Activation Link");
                    $body = "Hello {$username}<br/><br/>";
                    $body .= "You recently registered a new account on " . SITE_TITLE . "<br/>";
                    $body .= "To activate your account please click the following link<br/><br/>";
                    $body .= "<b><a href='".SITE_URL.ACTIVATION_ROUTE."/username/{$username}/key/{$activekey}'>Activate my account</a></b>";
                    $body .= "<br><br> You May Copy and Paste this URL in your Browser Address Bar: <br>";
                    $body .= SITE_URL.ACTIVATION_ROUTE."/username/{$username}/key/{$activekey}";
                    $body .= "<br><br> You Requested to have this email resent to your email.";
                    $mail->body($body);
                    $mail->send();
                    $this->logActivity($username, "AUTH_REGISTER_SUCCESS", "Account created and activation email sent");
                    $this->success[] = $this->language->get('register_success');
                    return true;
                }else{
                    return false;
                }
            } else {
                //some error
                return false;
            }
        } else {
            // User is logged in
            $auth_error[] = $this->language->get('register_email_loggedin');
            return false;
        }
    }

    /**
     * Update given field in users table
     * @param $data
     * @param $where
     * @return int
     */
    public function updateUser($data,$where){
        return $this->authorize->updateInDB('users',$data,$where);
    }

    /**
  	 * Get Current Session Data
  	 */
      public function user_info(){
          return $this->currentSessionInfo()['uid'];
      }

      /**
    	 * Check to see if Current User is Admin
       * @param int $where_id (current user's userID)
       * @return boolean (true/false)
    	 */
    	public function checkIsAdmin($where_id){
        /* Get Current User's Groups */
        $user_groups = $this->authorize->getUserGroups($where_id);
        // Make sure user is logged in
        if(!empty($where_id)){
        	// Get user's group status
        	foreach($user_groups as $user_group_data){
        		$cu_groupID[] = $user_group_data->groupID;
        	}
        }else{
          $cu_groupID[] = "0";
        }
        // Set which group(s) are admin (4)
        if(in_array(4,$cu_groupID)){
          // User is Admin
          return true;
        }else{
          // User Not Admin
          return false;
        }
    	}

      /**
    	 * Check to see if Current User is Mod
       * @param int $where_id (current user's userID)
       * @return boolean (true/false)
    	 */
    	public function checkIsMod($where_id){
        /* Get Current User's Groups */
        $user_groups = $this->authorize->getUserGroups($where_id);
        // Make sure user is logged in
        if(!empty($where_id)){
        	// Get user's group status
        	foreach($user_groups as $user_group_data){
        		$cu_groupID[] = $user_group_data->groupID;
        	}
        }else{
          $cu_groupID[] = "0";
        }
        // Set which group(s) are admin (4)
        if(in_array(3,$cu_groupID)){
          // User is Admin
          return true;
        }else{
          // User Not Admin
          return false;
        }
    	}

      /**
    	 * Check to see if Current User is New User
       * @param int $where_id (current user's userID)
       * @return boolean (true/false)
    	 */
    	public function checkIsNewUser($where_id){
        /* Get Current User's Groups */
        $user_groups = $this->authorize->getUserGroups($where_id);
        // Make sure user is logged in
        if(!empty($where_id)){
        	// Get user's group status
        	foreach($user_groups as $user_group_data){
        		$cu_groupID[] = $user_group_data->groupID;
        	}
        }else{
          $cu_groupID[] = "0";
        }
        // Set which group(s) are admin (4)
        if(in_array(1,$cu_groupID)){
          // User is Admin
          return true;
        }else{
          // User Not Admin
          return false;
        }
    	}

}
