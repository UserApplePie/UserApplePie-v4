<?php namespace App\System;

use App\System\Router;

class Controller {

    public $routes;

    function __construct(){

        $this->routes = Router::extendedRoutes();

    }

}
