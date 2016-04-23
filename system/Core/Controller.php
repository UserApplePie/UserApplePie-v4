<?php
/**
 * Controller - base controller
 *
 * Nova Framework
 * @author David Carr - dave@daveismyname.com
 * @version 3.0
 * UserApplePie
 * @author David (DaVaR) Sargent
 * @version 3.0.2
 */

namespace Core;

use Core\Language,
  Helpers\Auth\Auth as AuthHelper,
  App\Models\Users,
  Helpers\PageFunctions;

/**
 * Core controller, all other controllers extend this base controller.
 */
abstract class Controller
{
    /**
     * Language variable to use the languages class.
     *
     * @var string
     */
    public $language;

    /**
     * Auth variable to use the Auth class.
     *
     * @var string
     */
    public $auth;

    /**
     * User variable to use the User class.
     *
     * @var string
     */
    public $user;

    /**
     * PageFunctions variable to use the PageFunctions class.
     *
     * @var string
     */
    public $PageFunctions;

    /**
     * On run make an instance of the config class and view class.
     */
    public function __construct()
    {
        /** initialise the AuthHelper object */
        $this->auth = new AuthHelper();

        /** initialise the Users object */
        $this->user = new Users();

        /** initialise the PageFunctions object */
        $this->PageFunctions = new PageFunctions();

        /** Get Current User Data if logged in */
        if ($this->auth->isLogged()) {
            $u_id = $this->auth->currentSessionInfo()['uid'];
            $this->user->update($u_id);
        }

        /** Clean offline users from DB */
        $this->user->cleanOfflineUsers();

        /** initialise the language object */
        $this->language = new Language();
    }
}
