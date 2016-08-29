<?php namespace App\System\Libraries;

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

}
