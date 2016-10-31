<?php namespace App;



/*
* Router::run($url, $controller, $method, $params);
*/

//Router::run('Home', 'Home', 'Home', '');

class Routes {

    private $forum;
    private $forum_on_off;

    static function setRoutes(){
        $routes = array();

        /* Default Routing */
        $routes[] = self::add('Home', 'Home', 'Home', '(:any)/(:num)');
        $routes[] = self::add('About', 'Home', 'About');
        $routes[] = self::add('Contact', 'Home', 'Contact');
        $routes[] = self::add('Templates', 'Home', 'Templates');
        $routes[] = self::add('assets', 'Home', 'assets');
        /* End default routes */

        /* Auth Routing */
        $routes[] = self::add('Register', 'Auth', 'register');
        $routes[] = self::add('activate', 'Auth', 'activate', '(:any)/(:any)/(:any)/(:any)');
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
        $routes[] = self::add('Members', 'Members', 'members', '(:any)/(:any)');
        $routes[] = self::add('Online-Members', 'Members', 'online',  '(:any)/(:any)');
        $routes[] = self::add('Profile', 'Members', 'viewProfile', '(:any)');
        /* End Member Routing */

        /* Admin Panel Routing */
        $routes[] = self::add('AdminPanel', 'AdminPanel', 'Dashboard');
        $routes[] = self::add('AdminPanel-Settings', 'AdminPanel', 'Settings');
        $routes[] = self::add('AdminPanel-Users', 'AdminPanel', 'Users', '(:any)/(:any)');
        $routes[] = self::add('AdminPanel-User', 'AdminPanel', 'User', '(:any)');
        $routes[] = self::add('AdminPanel-Groups', 'AdminPanel', 'Groups');
        $routes[] = self::add('AdminPanel-Group', 'AdminPanel', 'Group', '(:any)');
        $routes[] = self::add('AdminPanel-MassEmail', 'AdminPanel', 'MassEmail');
        /* End Admin Panel Routing */

        /* Language Code Change */
        $routes[] = self::add('ChangeLang', 'ChangeLang', 'index', '(:any)');
        /* End Language Code Change Routing */

        /* Forum Plugin Routing */
        $forum = new \App\Plugins\Forum\Models\Forum();
        $forum_on_off = $forum->globalForumSetting('forum_on_off');
        if($forum_on_off == 'Enabled'){
            $routes[] = self::add('Forum', 'Plugins\Forum\Controllers\Forum', 'forum');
            $routes[] = self::add('Topics', 'Plugins\Forum\Controllers\Forum','topics','(:num)/(:num)');
            $routes[] = self::add('Topic', 'Plugins\Forum\Controllers\Forum','topic','(:num)/(:num)');
            $routes[] = self::add('NewTopic', 'Plugins\Forum\Controllers\Forum','newtopic','(:num)');
            $routes[] = self::add('AdminPanel-Forum-Settings', 'Plugins\Forum\Controllers\ForumAdmin','forum_settings');
            $routes[] = self::add('AdminPanel-Forum-Categories', 'Plugins\Forum\Controllers\ForumAdmin','forum_categories','(:any)/(:any)/(:any)');
            $routes[] = self::add('AdminPanel-Forum-Blocked-Content', 'Plugins\Forum\Controllers\ForumAdmin','forum_blocked');
            $routes[] = self::add('SearchForum', 'Plugins\Forum\Controllers\Forum','forumSearch','(:any)/(:num)');
        }
        /* End Forum Plugin Routing */

        /* Messages Plugin Routing */
        $routes[] = self::add('Messages', 'Plugins\Messages\Controllers\Messages', 'messages');
        $routes[] = self::add('ViewMessage', 'Plugins\Messages\Controllers\Messages', 'view', '(:any)');
        $routes[] = self::add('MessagesInbox', 'Plugins\Messages\Controllers\Messages', 'inbox', '(:any)');
        $routes[] = self::add('MessagesOutbox/(:any)', 'Plugins\Messages\Controllers\Messages', 'outbox', '(:any)');
        $routes[] = self::add('NewMessage', 'Plugins\Messages\Controllers\Messages', 'newmessage', '(:any)/(:any)');
        /* Messages Plugin Routing */

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
