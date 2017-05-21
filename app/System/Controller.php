<?php
/**
* System Controller Class
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

namespace App\System;

use App\System\Router,
  Libs\Language as Language,
  Libs\Auth\Auth as AuthHelper,
  App\Models\Users,
  Libs\PageFunctions;

abstract class Controller {

    /**
     * Routes variable to use the routes class.
     *
     * @var string
     */
    public $routes;

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

    function __construct(){

        /** Initialise the Router object **/
        $this->routes = Router::extendedRoutes();
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
        }else{
            $u_id = null;
        }
        /** Log All Activity to Site Logs **/
        if($u_id != null){
            \Libs\SiteStats::log(\Libs\CurrentUserData::getUserName($u_id));
        }else{
            \Libs\SiteStats::log();
        }
        /** Clean offline users from DB */
        $this->user->cleanOfflineUsers();
        /** initialise the language object */
        $this->language = new Language();


    }

}
