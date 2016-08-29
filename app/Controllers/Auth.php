<?php namespace App\Controllers;

/*
** Home Pages Controller
*/

use App\System\Controller,
    App\System\Load;

class Auth extends Controller {

    public function Login(){
        $data['bodyText'] = "Welcome to the Login Page!";
        Load::View("Home::Home", $data, "Home::Sidebar::Left");
    }

    public function Register(){
        $data['pageTitle'] = "Register";
        $data['bodyText'] = "Welcome to the Register Page!";
        Load::View("Home::Home", $data, "Home::Sidebar::Right");
    }

}
