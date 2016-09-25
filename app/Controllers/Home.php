<?php namespace App\Controllers;

/*
** Home Pages Controller
*/

use App\System\Controller,
    App\System\Load,
    App\Models\Home as HomeModel,
    App\System\Libraries\Assets,
    App\System\Error;

class Home extends Controller {

    public function Home($one = '', $two = ''){
        $data['bodyText'] = "Home Page <br> Pie Yo!  Enjoy every slice!";
        $data['bodyText'] .= "<br>This content can be changed in <code>/app/Views/Home/Home.php</code>";
        $data['bodyText'] .= "<br>$one - $two<br>";
        Load::View("Home::Home", $data, "Home::Sidebar::Right");
    }

    public function About(){
        $data['pageTitle'] = "About";
        $data['bodyText'] = "Welcome to the About Page!";
        $data['bodyText'] .= "<br>This content can be changed in <code>/app/Views/Home/About.php</code>";

        $test = new HomeModel();
        $testout = $test->test('1');
        $testout2 = $test->test('2');
        $data['bodyText'] .= "<br>$testout<br>";
        $data['bodyText'] .= "$testout2<br>";

        Load::View("Home::About", $data, "Home::Sidebar::Left");
    }

    public function Contact(){
        $data['pageTitle'] = "Contact";
        $data['bodyText'] = "Welcome to the Contact Page!";
        $data['bodyText'] .= "<br>This content can be changed in <code>/app/Views/Home/Contact.php</code>";
        Load::View("Home::Contact", $data, "Home::Sidebar::Right");
    }

    public function Templates(){
        $extRoutes = $this->routes;
        if(sizeof($extRoutes) == '5'){
            Assets::loadFile($extRoutes);
        }else{
            Error::show(404);
        }
    }

}
