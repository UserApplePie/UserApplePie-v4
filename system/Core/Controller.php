<?php
/**
 * Controller - base controller
 *
 * @author David Carr - dave@novaframework.com
 * @version 3.0
 */

namespace Core;

use Core\Language,
  Helpers\Auth\Auth as AuthHelper,
  App\Models\Users;

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

    public $auth;
    public $user;

    /**
     * On run make an instance of the config class and view class.
     */
    public function __construct()
    {
        $this->auth = new AuthHelper();
        $this->user = new Users();

        if ($this->auth->isLogged()) {
            $u_id = $this->auth->currentSessionInfo()['uid'];
            $this->user->update($u_id);
        }

        $this->user->cleanOfflineUsers();

        /** initialise the language object */
        $this->language = new Language();
    }
}
