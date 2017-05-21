<?php
/**
* Core System Routes
* Editing the core routes may crash your site
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

namespace App;

use Libs\Database;

/*
* Router::run($url, $controller, $method, $params);
* Router::run('Home', 'Home', 'Home', '');
*/

class Routes {

    private $forum;
    private $forum_on_off;
    private static $db;

    static function setRoutes(){
        $routes = array();

        /* Default Routing */
        $routes[] = self::add('Home', 'Home', 'Home', '(:any)/(:num)');
        //$routes[] = self::add('About', 'Home', 'About');
        //$routes[] = self::add('Contact', 'Home', 'Contact');
        $routes[] = self::add('Templates', 'Home', 'Templates');
        $routes[] = self::add('assets', 'Home', 'assets');
        /* End default routes */

        /* Auth Routing */
        $routes[] = self::add('Register', 'Auth', 'register');
        $routes[] = self::add('Activate', 'Auth', 'activate', '(:any)/(:any)/(:any)/(:any)');
        $routes[] = self::add('Forgot-Password', 'Auth', 'forgotPassword');
        $routes[] = self::add('ResetPassword', 'Auth', 'resetPassword', '(:any)/(:any)/(:any)/(:any)');
        $routes[] = self::add('Resend-Activation-Email', 'Auth', 'resendActivation');
        $routes[] = self::add('Login', 'Auth', 'login');
        $routes[] = self::add('Logout', 'Auth', 'logout');
        $routes[] = self::add('Settings', 'Auth', 'settings');
        $routes[] = self::add('Change-Email', 'Auth', 'changeEmail');
        $routes[] = self::add('Change-Password', 'Auth', 'changePassword');
        $routes[] = self::add('Edit-Profile','Members', 'editProfile');
        $routes[] = self::add('Privacy-Settings','Members', 'privacy');
        $routes[] = self::add('Account-Settings','Members', 'account');
        /* End Auth Routing */

        /* Live Checks */
        $routes[] = self::add('LiveCheckEmail', 'LiveCheck', 'emailCheck');
        $routes[] = self::add('LiveCheckUserName', 'LiveCheck', 'userNameCheck');
        /* End Live Checks */

        /* Member Routing */
        $routes[] = self::add('Members', 'Members', 'members', '(:any)/(:any)/(:any)');
        $routes[] = self::add('Online-Members', 'Members', 'online',  '(:any)/(:any)');
        $routes[] = self::add('Profile', 'Members', 'viewProfile', '(:any)');
        /* End Member Routing */

        /* Admin Panel Routing */
        $routes[] = self::add('AdminPanel', 'AdminPanel', 'Dashboard');
        $routes[] = self::add('AdminPanel-Settings', 'AdminPanel', 'Settings');
        $routes[] = self::add('AdminPanel-Users', 'AdminPanel', 'Users' , '(:any)/(:any)');
        $routes[] = self::add('AdminPanel-User', 'AdminPanel', 'User', '(:any)');
        $routes[] = self::add('AdminPanel-Groups', 'AdminPanel', 'Groups');
        $routes[] = self::add('AdminPanel-Group', 'AdminPanel', 'Group', '(:any)');
        $routes[] = self::add('AdminPanel-MassEmail', 'AdminPanel', 'MassEmail');
        $routes[] = self::add('AdminPanel-SystemRoutes', 'AdminPanel', 'SystemRoutes');
        $routes[] = self::add('AdminPanel-SystemRoute', 'AdminPanel', 'SystemRoute', '(:any)');
        /* End Admin Panel Routing */

        /* Language Code Change */
        $routes[] = self::add('ChangeLang', 'ChangeLang', 'index', '(:any)');
        /* End Language Code Change Routing */

        /* Forum Plugin Routing */
        /** Check to see if Forum Plugin is installed, if it is show link **/
        if(file_exists(ROOTDIR.'app/Plugins/Forum/Controllers/Forum.php')){
            $forum = new \App\Plugins\Forum\Models\Forum();
            $forum_on_off = $forum->globalForumSetting('forum_on_off');
            if(empty($forum_on_off)){ $forum_on_off = "Enabled"; }
            if($forum_on_off == 'Enabled'){
                $routes[] = self::add('Forum', 'Plugins\Forum\Controllers\Forum', 'forum');
                $routes[] = self::add('Topics', 'Plugins\Forum\Controllers\Forum','topics','(:num)/(:num)');
                $routes[] = self::add('Topic', 'Plugins\Forum\Controllers\Forum','topic','(:num)/(:num)');
                $routes[] = self::add('NewTopic', 'Plugins\Forum\Controllers\Forum','newtopic','(:num)');
                $routes[] = self::add('AdminPanel-Forum-Categories', 'Plugins\Forum\Controllers\ForumAdmin','forum_categories','(:any)/(:any)/(:any)');
                $routes[] = self::add('AdminPanel-Forum-Blocked-Content', 'Plugins\Forum\Controllers\ForumAdmin','forum_blocked');
                $routes[] = self::add('SearchForum', 'Plugins\Forum\Controllers\Forum','forumSearch','(:any)/(:num)');
            }
            $routes[] = self::add('AdminPanel-Forum-Settings', 'Plugins\Forum\Controllers\ForumAdmin','forum_settings');
        }
        /* End Forum Plugin Routing */

        /* Messages Plugin Routing */
        /** Check to see if Private Messages Plugin is installed, if it is show link **/
        if(file_exists(ROOTDIR.'app/Plugins/Messages/Controllers/Messages.php')){
            $routes[] = self::add('Messages', 'Plugins\Messages\Controllers\Messages', 'messages');
            $routes[] = self::add('ViewMessage', 'Plugins\Messages\Controllers\Messages', 'view', '(:any)');
            $routes[] = self::add('MessagesInbox', 'Plugins\Messages\Controllers\Messages', 'inbox', '(:any)');
            $routes[] = self::add('MessagesOutbox/(:any)', 'Plugins\Messages\Controllers\Messages', 'outbox', '(:any)');
            $routes[] = self::add('NewMessage', 'Plugins\Messages\Controllers\Messages', 'newmessage', '(:any)/(:any)');
        }
        /* End Messages Plugin Routing */

        /* Friends Plugin Routing */
        /** Check to see if Friends Plugin is installed, if it is show link **/
        if(file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php')){
            $routes[] = self::add('Friends', 'Plugins\Friends\Controllers\Friends', 'friends', '(:any)/(:any)/(:any)');
            $routes[] = self::add('FriendRequests', 'Plugins\Friends\Controllers\Friends', 'friendrequests');
            $routes[] = self::add('UnFriend', 'Plugins\Friends\Controllers\Friends', 'unfriend', '(:any)');
            $routes[] = self::add('AddFriend', 'Plugins\Friends\Controllers\Friends', 'addfriend', '(:any)');
            $routes[] = self::add('ApproveFriend', 'Plugins\Friends\Controllers\Friends', 'approvefriend', '(:any)');
            $routes[] = self::add('CancelFriend', 'Plugins\Friends\Controllers\Friends', 'cancelfriend', '(:any)');
        }
        /* End Friends Plugin Routing */

        /** Get Routes from Database **/
        self::$db = Database::get();
        $db_routes = self::$db->select("
            SELECT
                *
            FROM
                ".PREFIX."routes
            WHERE
                enable = 1
            ");
        foreach ($db_routes as $db_route) {
            $routes[] = self::add($db_route->url, $db_route->controller, $db_route->method, $db_route->arguments);
        }
        /** End Get Routes From Database **/

        /* Send the routes to system */
        return $routes;
    }

    static function add($url, $controller, $method, $arguments = null){
        $routes = array(
            "url" => $url,
            "controller" => $controller,
            "method" => $method,
            "arguments" => $arguments
        );
        return $routes;
    }

    static function all(){
        $routes = self::setRoutes();
        return $routes;
    }

}
