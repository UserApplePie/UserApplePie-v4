<?php namespace App\System;

/*
** System Router Class
** The goal of the router is to handle all the url requests.
** When you type in ?page=About it will load the About function that
** contains which model and view to load.
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.0
*/

use App\System\Error,
    App\Routes;

class Router {

    private $routes;

    function __construct(){
        $this->routes = Routes::all();
        $route = $this->findRoute();
        $crc_array = array();
        $crc_array = explode("\\", $route['controller']);
        if($crc_array[0] == "Plugins"){
            $controller = "App\\".$route['controller'];
        }else{
            $controller = "App\\Controllers\\".$route['controller'];
        }
        if(class_exists($controller)){
            $controller = new $controller();
            if(method_exists($controller, $route["method"])){
                if(isset($route["arguments"])){
                    /** Split up the arguments from routes **/
                    $arguments = array();
                    $arg_paths = array();
                    $arg = rtrim($route["arguments"],'/');
                    $arguments = explode("/", $arg);
                    /** For each argument we get data from url **/
                    $params = array_slice(SELF::extendedRoutes(), 1);
                    foreach ($arguments as $key => $value) {
                        /** Check to see if argument is any **/
                        if($value == "(:any)"){
                            if(isset($params[$key])){
                                if(preg_match('/^[A-Za-z0-9_-]*$/', $params[$key])){
                                    $new_params[] = $params[$key];
                                }else{
                                    $error_check = true;
                                }
                            }
                        }
                        /** Check to see if argument is a number **/
                        if($value == "(:num)"){
                            if(isset($params[$key])){
                                if(preg_match('/^[1-9][0-9]{0,15}$/', $params[$key])){
                                    $new_params[] = $params[$key];
                                }else{
                                    $error_check = true;
                                }
                            }
                        }
                    }
                    /** The called Method should be defined right in the called Controller **/
                    if (! in_array(strtolower($route["method"]), array_map('strtolower', get_class_methods($controller)))) {
                        $error_check = true;
                    }
                    (isset($error_check)) ? '' : $error_check = false;
                    if($error_check != true){
                        if(isset($new_params)){
                            /** Execute the Controller's Method with the given arguments **/
                            call_user_func_array(array($controller, $route["method"]), $new_params);
                        }else{
                            $controller->{$route["method"]}();
                        }
                    }else{
                        Error::show(404);
                    }
                }else{
                    $controller->{$route["method"]}();
                }
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

    public static function extendedRoutes(){
        if(!empty($_GET['url'])){
            $url = $_GET['url'];
            $url = rtrim($url,'/');
            $parts = explode("/", $url);
            return $parts;
        }
    }

}
