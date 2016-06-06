<?php
/**
 * Error class - calls a 404 page.
 *
 * Nova Framework
 * @author David Carr - dave@daveismyname.com
 * @version 3.0
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0.4
 */

namespace Core;

use Core\Controller,
  Core\View,
  Helpers\Auth\Auth as AuthHelper,
  App\Models\Users;

/**
 * Error class to generate 404 pages.
 */
class Error extends Controller
{
    /**
     * $error holder.
     *
     * @var string
     */
    private $error = null;
    public $auth;
    public $user;

    /**
     * Save error to $this->error.
     *
     * @param string $error
     */
    public function __construct($error = null)
    {
        parent::__construct();
        $this->error = $error;

        $this->auth = new AuthHelper();
        $this->user = new Users();

        if ($this->auth->isLogged()) {
            $u_id = $this->auth->currentSessionInfo()['uid'];
            $this->user->update($u_id);
        }

        $this->user->cleanOfflineUsers();
    }

    /**
     * Load a 404 page with the error message.
     */
    public function index($error = null)
    {
        header("HTTP/1.0 404 Not Found");

        $data['title'] = '404';
        $data['error'] = $error ? $error : $this->error;

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          /** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

        View::renderTemplate('header', $data);
        View::render('Error/404', $data);
        View::renderTemplate('footer', $data);
    }

    /**
     * Display errors.
     *
     * @param  array  $error an error of errors
     * @param  string $class name of class to apply to div
     *
     * @return string return the errors inside divs
     */
    public static function display($error, $class = 'alert alert-danger')
    {
        if (is_array($error)) {
            foreach ($error as $error) {
                $row.= "<div class='$class'>$error</div>";
            }
            return $row;
        } else {
            if (isset($error)) {
                return "<div class='$class'>$error</div>";
            }
        }
    }

    /*
     * Create 404 error
     */
    public static function error404(){
        $c = new self('404 Page Not Found');
        $c->index();
        exit();
    }

    /*
     * Create view profile error
     */
    public static function profileError(){
        $c = new self('The member profile you requeted was not found or does not exist!');
        $c->index();
        exit();
    }

}
