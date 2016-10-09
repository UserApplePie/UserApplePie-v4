<?php namespace Libs;

class Url {

    static function part($number){
        $parts = explode("/", $_SERVER["REQUEST_URI"]);
        return (isset($parts[$number])) ? $parts[$number] : false;
    }

}
