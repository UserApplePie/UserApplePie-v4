<?php namespace App\System\Libraries;

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

    public static function loadFile($extRoutes = null){
        /* Check to make sure a file is properly requested */
        if(isset($extRoutes)){
            $mimes = array
            (
                'jpg' => 'image/jpg',
                'jpeg' => 'image/jpg',
                'gif' => 'image/gif',
                'png' => 'image/png',
                'css' => 'text/css'
            );
            $filename = $extRoutes[4];

            $ext = strtolower(@end((explode('.', $filename))));

            $file = APPDIR.'Templates/'.$extRoutes[1].'/Assets/'.$extRoutes[3].'/'.$filename;
            //var_dump($ext, $file, $mimes[$ext]);
            if(file_exists($file)){
                header('Content-Type: '. $mimes[$ext]);
                header('Content-Disposition: inline; filename="'.$extRoutes[4].'";');
                readfile($file);
            }else{
                Error::show(404);
            }
        }else{
            Error::show(404);
        }
    }

}
