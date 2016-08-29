<?php namespace App;



/*
** Router::run($url, $controller, $method, $params);
*/

//Router::run('Home', 'Home', 'Home', '');

class Routes {

    static function setRoutes(){
        $routes = array();
        $routes[] = self::add('Home', 'Home', 'Home');
        $routes[] = self::add('About', 'Home', 'About');
        $routes[] = self::add('Contact', 'Home', 'Contact');
        $routes[] = self::add('Login', 'Auth', 'Login');
        $routes[] = self::add('Register', 'Auth', 'Register');
        return $routes;
    }

    static function add($url, $controller, $method){
        $routes = array(
            "url" => $url,
            "controller" => $controller,
            "method" => $method
        );
        return $routes;
    }

    static function all(){
        $routes = self::setRoutes();
        return $routes;
    }

}
