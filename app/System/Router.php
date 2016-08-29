<?php namespace App\System;

/*
** System Router
** The goal of the router is to handle all the url requests.
** When you type in ?page=About it will load the About function that
** contains which model and view to load.
*/

use App\System\Error,
    App\Routes;

class Router {

    private $routes;

    function __construct(){
        $this->routes = Routes::all();
        $route = $this->findRoute();
        $controller = "App\\Controllers\\".$route['controller'];
        if(class_exists($controller)){
            $controller = new $controller();
            if(method_exists($controller, $route["method"])){
                $controller->{$route["method"]}();
            }else{
                Error::show(404);
            }
        }else{
            Error::show(404);
        }
    }

    private function routePart($route){
        if(is_array($route)){
            $route = $route['url'];
        }
        $parts = explode("/", $route);
        return $parts;
    }

    static function uri($part){
        $routes = Routes::all();
        if(isset($_GET["url"])){
            $url = $_GET['url'];
		    $url = rtrim($url,'/');
            $parts = explode("/", $url);
            if($parts[0] == $routes){
                $part++;
            }
            return (isset($parts[$part])) ? $parts[$part] : "";
        }else{
            return "";
        }
    }

    private function findRoute(){
        $uri = Router::uri(0);
        if(empty($uri)){
            $route = array(
                "url" => "",
                "controller" => DEFAULT_CONTROLLER,
                "method" => DEFAULT_METHOD
            );
            return $route;
        }
        foreach ($this->routes as $route) {
            $parts = $this->routePart($route);
            $match = false;
            foreach($parts as $value){
                if($value == $uri){
                    $match = true;
                }
                if($match){
                    return $route;
                }
            }
        }
    }

}
