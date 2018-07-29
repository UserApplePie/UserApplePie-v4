<?php
/**
* System Error Class
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/

namespace App\System;

use App\System\Controller,
    App\System\Load,
    Libs\Auth\Auth as AuthHelper,
    App\Models\Users;

class Error {

    /** Standard URL Error **/
    static function show($type){
        /** initialise the AuthHelper object */
        $auth = new AuthHelper();
        /** initialise the Users object */
        $user = new Users();

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $auth->isLogged()){
            /** User is logged in - Get their data **/
            $u_id = $auth->user_info();
            $data['currentUserData'] = $user->getCurrentUserData($u_id);
            $data['isAdmin'] = $user->checkIsAdmin($u_id);
        }

        $data['error_code'] = "404";
        if($type == '404'){
            $data['bodyText'] = "Oops! Looks like something went wrong!";
            $data['bodyText'] .= "<br>The Requested URL Does Not Exist!";
            $data['bodyText'] .= "<br>Please check your spelling and try again.";
        }else{
            $data['bodyText'] = "Oops! Looks like something went wrong!";
        }
        Load::View("Home::Error", $data);
    }

    /** User Profile Error **/
    static function profileError(){

        /** initialise the AuthHelper object */
        $auth = new AuthHelper();
        /** initialise the Users object */
        $user = new Users();

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $auth->isLogged()){
            /** User is logged in - Get their data **/
            $u_id = $auth->user_info();
            $data['currentUserData'] = $user->getCurrentUserData($u_id);
            $data['isAdmin'] = $user->checkIsAdmin($u_id);
        }

        $data['error_code'] = "User Profile";
        $data['bodyText'] = "Oops! Looks like something went wrong!";
        $data['bodyText'] .= "<br>The Requested User Profile Does Not Exist!";
        $data['bodyText'] .= "<br>Please check your spelling and try again.";
        
        Load::View("Home::Error", $data);
    }

}
