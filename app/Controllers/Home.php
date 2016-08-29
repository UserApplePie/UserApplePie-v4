<?php namespace App\Controllers;

/*
** Home Pages Controller
*/

use App\System\Controller,
    App\System\Load;

class Home extends Controller {

    public function Home(){
        $data['bodyText'] = "Home Page <br> Pie Yo!  Enjoy every slice!";
        $data['bodyText'] .= "<br>This content can be changed in <code>/app/Views/Home/Home.php</code>";
        Load::View("Home::Home", $data, "Home::Sidebar::Right");
    }

    public function About(){
        $data['pageTitle'] = "About";
        $data['bodyText'] = "Welcome to the About Page!";
        $data['bodyText'] .= "<br>This content can be changed in <code>/app/Views/Home/About.php</code>";
        Load::View("Home::About", $data, "Home::Sidebar::Left");
    }

    public function Contact(){
        $data['pageTitle'] = "Contact";
        $data['bodyText'] = "Welcome to the Contact Page!";
        $data['bodyText'] .= "<br>This content can be changed in <code>/app/Views/Home/Contact.php</code>";
        Load::View("Home::Contact", $data, "Home::Sidebar::Right");
    }

}
