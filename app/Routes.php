<?php namespace App;



/*
** Router::run($url, $controller, $method, $params);
*/

//Router::run('Home', 'Home', 'Home', '');

class Routes {

    static function setRoutes(){
        $routes = array();
        $routes[] = self::add('Home', 'Home', 'Home', '(:any)/(:num)');
        $routes[] = self::add('About', 'Home', 'About');
        $routes[] = self::add('Contact', 'Home', 'Contact');
        $routes[] = self::add('Login', 'Auth', 'Login');
        $routes[] = self::add('Register', 'Auth', 'Register');
        $routes[] = self::add('Templates', 'Home', 'Templates');
        return $routes;
    }

    static function add($url, $controller, $method, $arguments = null){
        $routes = array(
            "url" => $url,
            "controller" => $controller,
            "method" => $method,
            "arguments" => $arguments
        );
        return $routes;
    }

    static function all(){
        $routes = self::setRoutes();
        return $routes;
    }

}
