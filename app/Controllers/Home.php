<?php namespace App\Controllers;

/*
** Home Pages Controller
*/

use App\System\Controller,
    App\System\Load,
    App\Models\Home as HomeModel,
    Libs\Assets,
    App\System\Error;

class Home extends Controller {

    public function Home($one = '', $two = ''){
        $data['bodyText'] = "Home Page <br> Pie Yo!  Enjoy every slice!";
        $data['bodyText'] .= "<br>This content can be changed in <code>/app/Views/Home/Home.php</code>";
        $data['bodyText'] .= "<br>$one - $two<br>";

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

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

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

        Load::View("Home::About", $data, "Home::Sidebar::Left");
    }

    public function Contact(){
        $data['pageTitle'] = "Contact";
        $data['bodyText'] = "Welcome to the Contact Page!";
        $data['bodyText'] .= "<br>This content can be changed in <code>/app/Views/Home/Contact.php</code>";

        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }

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
