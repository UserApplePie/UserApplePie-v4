<?php
/**
* System Load Class
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

namespace App\System;

use   Libs\Auth\Auth as AuthHelper,
      App\Models\Users,
      App\Models\Members as MembersModel;

/**
* Load Class Loads Views Based on settings in Controllers
*/
class Load {

    /*
    ** Load View
    ** Loads files needed to display a page.
    */
    static function View($viewFile, $viewVars = array(), $sidebarFile = "", $template = DEFAULT_TEMPLATE, $useHeadFoot = true, $sidebarFile2 = ""){

        /** Get Common User Data For Site **/
        /** initialise the AuthHelper object */
        $auth = new AuthHelper();
        /** initialise the Users object */
        $user = new Users();
        /** Check to see if user is logged in **/
        if($user_data['isLoggedIn'] = $auth->isLogged()){
          /** User is logged in - Get their data **/
          $u_id = $auth->user_info();
          $user_data['currentUserData'] = $user->getCurrentUserData($u_id);
          $user_data['isAdmin'] = $user->checkIsAdmin($u_id);
          $user_data['current_userID'] = $u_id;
        }
        /** Get Data For Member Totals Stats Sidebar **/
        $onlineUsers = new MembersModel();
        $user_data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
        $user_data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());
        /** Check to if Terms and Privacy are enabled **/
        $user_data['terms_enabled'] = $user->checkSiteSetting('site_terms_content');
        $user_data['privacy_enabled'] = $user->checkSiteSetting('site_privacy_content');

        (empty($template)) ? $template = DEFAULT_TEMPLATE : "";
        $data = array_merge($user_data, $viewVars);
        /** Extract the $data array to vars **/
        extract($user_data);
        extract($viewVars);

        /* Setup Main View File */
        $viewFileCheck = explode(".", $viewFile);
        if(!isset($viewFileCheck[1])){
            $viewFile .= ".php";
        }
        $viewFile = str_replace("::", "/", $viewFile);
        $viewFile = APPDIR."Views/".$viewFile;

        /* Setup Sidebar File */
        if(!empty($sidebarFile)){
            $sidebarFileCheck = explode(".", $sidebarFile);
            $esbfc = explode("/", str_replace("::", "/", $sidebarFile));
            $sidebarLocation = end($esbfc);
            $sidebarFile = str_replace($sidebarLocation, "", $sidebarFile);
            $sidebarFile = rtrim(rtrim($sidebarFile,'/'),'::');
            if(!isset($sidebarFileCheck[1])){
                $sidebarFile .= ".php";
            }
            $sidebarFile = str_replace("::", "/", $sidebarFile);
            $sidebarFile = APPDIR."Views/".$sidebarFile;
            ($sidebarLocation == "Right" || $sidebarLocation == "right") ? $rightSidebar = $sidebarFile : "";
            ($sidebarLocation == "Left" || $sidebarLocation == "left") ? $leftSidebar = $sidebarFile : "";
        }

        /* Setup Sidebar File */
        if(!empty($sidebarFile2)){
            $sidebarFile2Check = explode(".", $sidebarFile2);
            $esbfc2 = explode("/", str_replace("::", "/", $sidebarFile2));
            $sidebarLocation2 = end($esbfc2);
            $sidebarFile2 = str_replace($sidebarLocation2, "", $sidebarFile2);
            $sidebarFile2 = rtrim(rtrim($sidebarFile2,'/'),'::');
            if(!isset($sidebarFile2Check[1])){
                $sidebarFile2 .= ".php";
            }
            $sidebarFile2 = str_replace("::", "/", $sidebarFile2);
            $sidebarFile2 = APPDIR."Views/".$sidebarFile2;
            ($sidebarLocation2 == "Right" || $sidebarLocation2 == "right") ? $rightSidebar2 = $sidebarFile2 : "";
            ($sidebarLocation2 == "Left" || $sidebarLocation2 == "left") ? $leftSidebar2 = $sidebarFile2 : "";
        }

        /* Setup Template Files */
        if($useHeadFoot == true){
            $templateHeader = APPDIR."Templates/".$template."/Header.php";
            $templateFooter = APPDIR."Templates/".$template."/Footer.php";
        }

        /* todo - setup a file checker that sends error to log file or something if something is missing */

        /* Check to see if Adds are enabled for current page */
        if(preg_match('/(Members)/', $viewFile) || preg_match('/(AdminPanel)/', $data['current_page'])){
          $addsEnable = false;
        }else{
          $addsEnable = true;
        }

        /* Setup Adds if Demo is FALSE */
        $mainAddsTop = APPDIR."Views/Adds/AddsTop.php";
        $mainAddsBottom = APPDIR."Views/Adds/AddsBottom.php";
        $sidebarAddsTop = APPDIR."Views/Adds/AddsSidebarTop.php";
        $sidebarAddsBottom = APPDIR."Views/Adds/AddsSidebarBottom.php";

        /* Load files needed to make the page work */
        (isset($templateHeader)) ? require_once $templateHeader : "";
        ($addsEnable) ? require_once $mainAddsTop : "";

        if(isset($leftSidebar) || isset($leftSidebar2)){ echo "<div class='col-lg-3 col-md-4 col-sm-12'>"; }
        ((isset($leftSidebar) || isset($leftSidebar2)) && $addsEnable) ? require_once $sidebarAddsTop : "";
        (isset($leftSidebar)) ? require_once $leftSidebar : "";
        (isset($leftSidebar2)) ? require_once $leftSidebar2 : "";
        ((isset($leftSidebar) || isset($leftSidebar2)) && $addsEnable) ? require_once $sidebarAddsBottom : "";
        if(isset($leftSidebar) || isset($leftSidebar2)){ echo "</div>"; }

        require_once $viewFile;

        if(isset($rightSidebar) || isset($rightSidebar2)){ echo "<div class='col-lg-3 col-md-4 col-sm-12'>"; }
        ((isset($rightSidebar) || isset($rightSidebar2)) && $addsEnable) ? require_once $sidebarAddsTop : "";
        (isset($rightSidebar)) ? require_once $rightSidebar : "";
        (isset($rightSidebar2)) ? require_once $rightSidebar2 : "";
        ((isset($rightSidebar) || isset($rightSidebar2)) && $addsEnable) ? require_once $sidebarAddsBottom : "";
        if(isset($rightSidebar) || isset($rightSidebar2)){ echo "</div>"; }

        ($addsEnable) ? require_once $mainAddsBottom : "";
        (isset($templateFooter)) ? require_once $templateFooter : "";
    }

    /*
    ** Load Plugin View
    ** Loads files needed to display a plugin page.
    */
    static function ViewPlugin($viewFile, $viewVars = array(), $sidebarFile = "", $pluginFolder = "", $template = DEFAULT_TEMPLATE, $useHeadFoot = true){

        /** Get Common User Data For Site **/
        /** initialise the AuthHelper object */
        $auth = new AuthHelper();
        /** initialise the Users object */
        $user = new Users();
        /** Check to see if user is logged in **/
        if($user_data['isLoggedIn'] = $auth->isLogged()){
          /** User is logged in - Get their data **/
          $u_id = $auth->user_info();
          $user_data['currentUserData'] = $user->getCurrentUserData($u_id);
          $user_data['isAdmin'] = $user->checkIsAdmin($u_id);
          $user_data['current_userID'] = $u_id;
        }
        /** Get Data For Member Totals Stats Sidebar **/
        $onlineUsers = new MembersModel();
        $user_data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
        $user_data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

        (empty($template)) ? $template = DEFAULT_TEMPLATE : "";
        $data = array_merge($user_data, $viewVars);
        /** Extract the $data array to vars **/
        extract($user_data);
        extract($viewVars);

        /* Setup Main View File */
        $viewFileCheck = explode(".", $viewFile);
        if(!isset($viewFileCheck[1])){
            $viewFile .= ".php";
        }
        $viewFile = str_replace("::", "/", $viewFile);
        $viewFile = APPDIR."Plugins/".$pluginFolder."/Views/".$viewFile;

        /* Setup Sidebar File */
        if(!empty($sidebarFile)){
            $sidebarFileCheck = explode(".", $sidebarFile);
            $esbfc = explode("/", str_replace("::", "/", $sidebarFile));
            $sidebarLocation = $esbfc[1];
            $sidebarFile = str_replace($sidebarLocation, "", $sidebarFile);
            $sidebarFile = rtrim(rtrim($sidebarFile,'/'),'::');
            if(!isset($sidebarFileCheck[1])){
                $sidebarFile .= ".php";
            }
            $sidebarFile = str_replace("::", "/", $sidebarFile);
            if($esbfc[0] == 'AdminPanel'){
                $sidebarLocation = $esbfc[2];
                $sidebarFile = APPDIR."Views/AdminPanel/".$esbfc[1].".php";
            }else{
                $sidebarFile = APPDIR."Plugins/".$pluginFolder."/Views/".$sidebarFile;
            }
            ($sidebarLocation == "Right" || $sidebarLocation == "right") ? $rightSidebar = $sidebarFile : "";
            ($sidebarLocation == "Left" || $sidebarLocation == "left") ? $leftSidebar = $sidebarFile : "";
        }

        /* Setup Template Files */
        if($useHeadFoot == true){
            $templateHeader = APPDIR."Templates/".$template."/Header.php";
            $templateFooter = APPDIR."Templates/".$template."/Footer.php";
        }

        /* todo - setup a file checker that sends error to log file or something if something is missing */

        /* Check to see if Adds are enabled for current page */
        if(preg_match('/(Members)/', $data['current_page']) || preg_match('/(AdminPanel)/', $data['current_page']) || preg_match('/(Friend)/', $data['title']) || preg_match('/(Message)/', $data['title'])){
          $addsEnable = false;
        }else{
          $addsEnable = true;
        }

        /* Setup Adds if Demo is FALSE */
        $mainAddsTop = APPDIR."Views/Adds/AddsTop.php";
        $mainAddsBottom = APPDIR."Views/Adds/AddsBottom.php";
        $sidebarAddsTop = APPDIR."Views/Adds/AddsSidebarTop.php";
        $sidebarAddsBottom = APPDIR."Views/Adds/AddsSidebarBottom.php";

        /* Load files needed to make the page work */
        (isset($templateHeader)) ? require_once $templateHeader : "";
        ($addsEnable) ? require_once $mainAddsTop : "";
        if(isset($leftSidebar)){ echo "<div class='col-lg-3 col-md-4 col-sm-12'>"; }
        (isset($leftSidebar) && $addsEnable) ? require_once $sidebarAddsTop : "";
        (isset($leftSidebar)) ? require_once $leftSidebar : "";
        (isset($leftSidebar) && $addsEnable) ? require_once $sidebarAddsBottom : "";
        if(isset($leftSidebar)){ echo "</div>"; }
        require_once $viewFile;
        if(isset($rightSidebar)){ echo "<div class='col-lg-3 col-md-4 col-sm-12'>"; }
        (isset($rightSidebar) && $addsEnable) ? require_once $sidebarAddsTop : "";
        (isset($rightSidebar)) ? require_once $rightSidebar : "";
        (isset($rightSidebar) && $addsEnable) ? require_once $sidebarAddsBottom : "";
        if(isset($rightSidebar)){ echo "</div>"; }
        ($addsEnable) ? require_once $mainAddsBottom : "";
        (isset($templateFooter)) ? require_once $templateFooter : "";
    }


}
