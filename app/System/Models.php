<?php namespace App\System

use App\System\Libraries;

class Models {

    public $model;

    function __construct(){
        $this->model = new Database(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

}
