<?php namespace App\System;

class Controller {

    function __construct(){
        $GLOBALS["instances"][] = &$this;
    }

}
