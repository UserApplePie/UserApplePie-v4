<?php namespace App\System;

class Load {

    static function View($viewFile, $viewVars = array(), $sidebarFile = "", $template = DEFAULT_TEMPLATE, $useHeadFoot = true){
        (empty($template)) ? $template = DEFAULT_TEMPLATE : "";
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

        (isset($templateHeader)) ? require_once $templateHeader : "";
        (isset($leftSidebar)) ? require_once $leftSidebar : "";
        require_once $viewFile;
        (isset($rightSidebar)) ? require_once $rightSidebar : "";
        (isset($templateFooter)) ? require_once $templateFooter : "";
    }

}
