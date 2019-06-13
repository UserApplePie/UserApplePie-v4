<?php namespace App\Controllers;

/*
* Home Pages Controller
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

use App\System\Controller,
    App\System\Load,
    App\Models\Home as HomeModel,
    Libs\Assets,
    App\System\Error,
    Libs\Auth\Auth as AuthHelper,
    App\Models\Users as Users,
    App\Models\Members as MembersModel,
    App\Models\Recent as Recent,
    Libs\Csrf,
    Libs\SuccessMessages,
    Libs\ErrorMessages,
    Libs\Url,
    Libs\Request;

class Home extends Controller {

    /* Call the parent construct */
    public function __construct()
    {
        parent::__construct();
        $this->language->load('Welcome');
    }

    /* Home Method */
    public function Home($limit = '10'){

        $data['title'] = $this->language->get('homeText');
        $data['bodyText'] = $this->language->get('homeMessage');
        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
          $data['current_userID'] = $u_id;
        }
        /** Get Data For Member Totals Stats Sidebar **/
        $onlineUsers = new MembersModel();
        $data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
        $data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

        /** Check to see if user is logged in - if so then display recent stuff **/
        if($data['isLoggedIn'] = $this->auth->isLogged() && file_exists(ROOTDIR.'app/Plugins/Forum/Controllers/Forum.php') && file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php')){
          /** Load the Recent Model **/
          $Recent = new Recent();
          /** Get Current User's Friends **/
          $data['friends'] = $Recent->getFriendsIDs($u_id, '15');
          $data['suggested_friends'] = $Recent->getSuggestedFriends($u_id);
          $data['recent'] = $Recent->getRecent($u_id, $limit);
          $data['recent_total'] = $Recent->getRecentTotal($u_id);
          $data['recent_limit'] = $limit;
          /** Check for user status Update **/
          $data['csrfToken'] = Csrf::makeToken('status');
          if (isset($_POST['submit'])) {
            if(Csrf::isTokenValid('status')) {
              $status_feeling = htmlspecialchars(Request::post('status_feeling'));
              $status_content = htmlspecialchars(Request::post('status_content'));
              $edit_status_id = strip_tags(Request::post('edit_status_id'));
              $data['action'] = strip_tags(Request::post('action'));
              if($data['action'] == 'status_update'){
                if($Recent->addStatus($u_id, $status_feeling, $status_content)){
                  /** Success Message Display **/
                  SuccessMessages::push($this->language->get('status_update_success'), '');
                }else{
                  /** Error Message Display **/
                  ErrorMessages::push($this->language->get('status_update_error'), '');
                }
              }else if($data['action'] == 'status_edit' && isset($edit_status_id)){
                /** Get data for status user is editing **/
                $get_status = $Recent->getStatus($u_id, $edit_status_id);
                $data['status_feeling'] = $get_status[0]->status_feeling;
                $data['status_content'] = $get_status[0]->status_content;
                $data['edit_status_id'] = $get_status[0]->id;
              }else if($data['action'] == 'status_edit_update' && isset($edit_status_id)){
                /** Update status user is editing **/
                if($Recent->updateStatus($u_id, $edit_status_id, $status_feeling, $status_content)){
                  /** Success Message Display **/
                  SuccessMessages::push($this->language->get('status_update_success'), '');
                }else{
                  /** Error Message Display **/
                  ErrorMessages::push($this->language->get('status_update_error'), '');
                }
              }
            }else{
              /** Error Message Display **/
              ErrorMessages::push($this->language->get('status_update_error'), '');
            }
          }
          /** Setup Friends Search Feature **/
          $data['js'] = "
            <script>
            function process()
              {
              var url='".SITE_URL."Friends/UN-ASC/1/' + document.getElementById('forumSearch').value;
              location.href=url;
              return false;
              }
            </script>
          ";

          /* Add Java Stuffs */
          $data['js'] = "<script src='".Url::templatePath()."js/bbcode.js'></script>";
          //$data['js'] .= "<script src='https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js'></script>";
          //$data['js'] .= "<script src='".Url::templatePath()."js/forum_autosave_topic.js'></script>";

          Load::View("Home::Recent", $data, "Home::Member-Forum-Sidebar::Right", DEFAULT_TEMPLATE, true, "Home::Member-Friends-Sidebar::Left");
        }else{
          Load::View("Home::Home", $data, "Members::Member-Stats-Sidebar::Right");
        }
    }

    /* About Method */
    public function About(){

        $data['title'] = $this->language->get('aboutText');
        $data['bodyText'] = $this->language->get('aboutMessage');
        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }
        /** Get Data For Member Totals Stats Sidebar **/
        $onlineUsers = new MembersModel();
        $data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
        $data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

        Load::View("Home::About", $data, "Members::Member-Stats-Sidebar::Left");
    }

    /* Contact Method */
    public function Contact(){

        $data['title'] = $this->language->get('contactText');
        $data['bodyText'] = $this->language->get('contactMessage');
        /** Check to see if user is logged in **/
        if($data['isLoggedIn'] = $this->auth->isLogged()){
          //** User is logged in - Get their data **/
          $u_id = $this->auth->user_info();
          $data['currentUserData'] = $this->user->getCurrentUserData($u_id);
          $data['isAdmin'] = $this->user->checkIsAdmin($u_id);
        }
        /** Get Data For Member Totals Stats Sidebar **/
        $onlineUsers = new MembersModel();
        $data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
        $data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

        Load::View("Home::Contact", $data, "Members::Member-Stats-Sidebar::Right");
    }

    /* Templates Method
    * Used to load files within the template assets folder
    */

    public function Templates(){
        $extRoutes = $this->routes;
        if(sizeof($extRoutes) == '5' || sizeof($extRoutes) == '6'){
            Assets::loadFile($extRoutes);
        }else{
            Error::show(404);
        }
    }

    /* Assets Method
    * Used to load files within the root assets folder
    */
    public function assets(){
        $extRoutes = $this->routes;
        if(sizeof($extRoutes) == '4' || sizeof($extRoutes) == '5' || sizeof($extRoutes) == '6'){
            Assets::loadFile($extRoutes, 'assets');
        }else{
            Error::show(404);
        }
    }

}
