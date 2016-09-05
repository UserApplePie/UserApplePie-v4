<?php namespace App\System;

use App\System\Libraries\Database;

class Models {

    protected $db;

    function __construct(){
        /** Connect to PDO for all models. */
        $this->db = Database::get();
    }

}
