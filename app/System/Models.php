<?php namespace App\System;

use Libs\Database;

class Models {

    protected $db;

    function __construct(){
        /** Connect to PDO for all models. */
        $this->db = Database::get();
    }

}
