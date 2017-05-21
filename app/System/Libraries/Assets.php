<?php
/**
* Assets Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

namespace Libs;

use App\System\Error;

class Assets {

    public static function css($css_url){
        if(isset($css_url)){
            foreach ($css_url as $value) {
                $css[] = '<link rel="stylesheet" href="'.$value.'" />';
            }
            return implode("", $css);
        }
    }

    public static function js($js_url){
        if(isset($js_url)){
            foreach ($js_url as $value) {
                $js[] = '<script src="'.$value.'" type="text/javascript"></script>';
            }
            return implode("", $js);
        }
    }

    public static function loadFile($extRoutes = null, $location = null){
        /* Check to make sure a file is properly requested */
        if(isset($extRoutes)){
            $mimes = array
            (
                'jpg' => 'image/jpg',
                'jpeg' => 'image/jpg',
                'gif' => 'image/gif',
                'png' => 'image/png',
                'css' => 'text/css',
                'js' => 'application/javascript'
            );

            if(isset($extRoutes[5])){
                (isset($location)) ? $filename = $extRoutes[5] : $filename = $extRoutes[5] ;
            }else if(isset($extRoutes[4])){
                (isset($location)) ? $filename = $extRoutes[4] : $filename = $extRoutes[4] ;
            }else{
                (isset($location)) ? $filename = $extRoutes[3] : $filename = $extRoutes[4] ;
            }

            $ext = strtolower(@end((explode('.', $filename))));

            if(isset($location)){
                if(isset($extRoutes[4])){
                    $file = ROOTDIR.'assets/'.$extRoutes[1].'/'.$extRoutes[2].'/'.$extRoutes[3].'/'.$filename;
                }else{
                    $file = ROOTDIR.'assets/'.$extRoutes[1].'/'.$extRoutes[2].'/'.$filename;
                }
                $file = preg_replace('{/$}', '', $file);
            }else{
                if(isset($extRoutes[5])){
                    $file = APPDIR.'Templates/'.$extRoutes[1].'/Assets/'.$extRoutes[3].'/'.$extRoutes[4].'/'.$filename;
                }else{
                    $file = APPDIR.'Templates/'.$extRoutes[1].'/Assets/'.$extRoutes[3].'/'.$filename;
                }
            }

            if(file_exists($file)){
                header('Content-Type: '. $mimes[$ext]);
                header('Content-Disposition: inline; filename="'.$filename.'";');
                readfile($file);
            }else{
                Error::show(404);
            }
        }else{
            Error::show(404);
        }
    }

}
