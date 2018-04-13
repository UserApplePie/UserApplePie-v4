<?php
/**
* System Load Class
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

namespace App\System;

/**
* Load Class Loads Views Based on settings in Controllers
*/
class Load {

    /*
    ** Load View
    ** Loads files needed to display a page.
    */
    static function View($viewFile, $viewVars = array(), $sidebarFile = "", $template = DEFAULT_TEMPLATE, $useHeadFoot = true){
        (empty($template)) ? $template = DEFAULT_TEMPLATE : "";
        $data = $viewVars;
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
            $sidebarLocation = $esbfc[2];
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

        /* Setup Template Files */
        if($useHeadFoot == true){
            $templateHeader = APPDIR."Templates/".$template."/Header.php";
            $templateFooter = APPDIR."Templates/".$template."/Footer.php";
        }

        /* todo - setup a file checker that sends error to log file or something if something is missing */

        /* Load files needed to make the page work */
        (isset($templateHeader)) ? require_once $templateHeader : "";
        (isset($leftSidebar)) ? require_once $leftSidebar : "";
        require_once $viewFile;
        (isset($rightSidebar)) ? require_once $rightSidebar : "";
        (isset($templateFooter)) ? require_once $templateFooter : "";
    }

    /*
    ** Load Plugin View
    ** Loads files needed to display a plugin page.
    */
    static function ViewPlugin($viewFile, $viewVars = array(), $sidebarFile = "", $pluginFolder = "", $template = DEFAULT_TEMPLATE, $useHeadFoot = true){
        (empty($template)) ? $template = DEFAULT_TEMPLATE : "";
        $data = $viewVars;
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

        /* Load files needed to make the page work */
        (isset($templateHeader)) ? require_once $templateHeader : "";
        (isset($leftSidebar)) ? require_once $leftSidebar : "";
        require_once $viewFile;
        (isset($rightSidebar)) ? require_once $rightSidebar : "";
        (isset($templateFooter)) ? require_once $templateFooter : "";
    }


}
