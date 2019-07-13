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
    Libs\Request,
    Libs\Language;

class Home extends Controller {

    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->language->load('Welcome');
    }

    /**
    * Home Method
    * @param int $limit
    */
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
          /** Check to see if user is missing anything in their profile **/
          $firstNameCheck = $data['currentUserData'][0]->firstName;
          $aboutmeCheck = $data['currentUserData'][0]->aboutme;
          $defaultImageCheck = $this->user->getUserImageMain($u_id);
          if(empty($firstNameCheck)){
            $data['info_alert'] = Language::show('edit_profile_first_name_not_set', 'Members')." <a href='".SITE_URL."Edit-Profile'>".Language::show('edit_profile', 'Members')."</a>";
          }else if(empty($aboutmeCheck)){
            $data['info_alert'] = Language::show('edit_profile_aboutme_not_set', 'Members')." <a href='".SITE_URL."Edit-Profile'>".Language::show('edit_profile', 'Members')."</a>";
          }else{
            if(strpos($defaultImageCheck, 'default-') !== false){
              $data['info_alert'] = Language::show('edit_profile_default_image_not_set', 'Members')." <a href='".SITE_URL."Edit-Profile-Images'>".Language::show('mem_act_edit_profile_images', 'Members')."</a>";
            }
          }
        }
        /** Get Data For Member Totals Stats Sidebar **/
        $onlineUsers = new MembersModel();
        $data['activatedAccounts'] = count($onlineUsers->getActivatedAccounts());
        $data['onlineAccounts'] = count($onlineUsers->getOnlineAccounts());

        /** Check to see if user is logged in - if so then display recent stuff **/
        if($data['isLoggedIn'] && file_exists(ROOTDIR.'app/Plugins/Forum/Controllers/Forum.php') && file_exists(ROOTDIR.'app/Plugins/Friends/Controllers/Friends.php')){
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

          /* Add Java Stuffs */
          $data['js'] = "<script src='".Url::templatePath()."js/bbcode_status.js'></script>";

          /** Push data to the view **/
          Load::View("Home::Recent", $data, "Home::Member-Forum-Sidebar::Right", DEFAULT_TEMPLATE, true, "Home::Member-Friends-Sidebar::Left");
        }else{
          /** Push data to the view **/
          Load::View("Home::Home", $data, "Members::Member-Stats-Sidebar::Right");
        }
    }

    /**
    * About Method
    */
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

        /** Push data to the view **/
        Load::View("Home::About", $data, "Members::Member-Stats-Sidebar::Left");
    }

    /**
    * Contact Method
    */
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

        /** Push data to the view **/
        Load::View("Home::Contact", $data, "Members::Member-Stats-Sidebar::Right");
    }

    /**
    * Templates Method
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

    /**
    * Assets Method
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

    /**
    * SiteMap Method
    * Auto Generates a SiteMap for SEO
    */
    public function sitemap(){

      /** Load Home Model **/
      $Home = new HomeModel();

      header('Content-type: text/xml');
      echo "<?xml version='1.0' encoding='UTF-8'?>\n";
      echo "<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9'>\n";

      /** Get Enabled Pages from Pages **/
      $getPublicURLs = $Home->getPublicURLs();
      if(isset($getPublicURLs)){
        foreach ($getPublicURLs as $key => $value) {
          if(isset($value->edit_timestamp)){
            $loc_date = $value->edit_timestamp;
          }else{
            $loc_date = $value->timestamp;
          }
          echo "<url>\n";
            echo "<loc>".SITE_URL.$value->url."</loc>\n";
            echo "<lastmod>".date('Y-m-d',strtotime($loc_date))."</lastmod>\n";
          echo "</url>\n";
        }
      }

      /** Get Forum Posts **/
      $getForumPosts = $Home->getForumPosts();
      if(isset($getForumPosts)){
        foreach ($getForumPosts as $key => $value) {
          /** Check Forum Post Replies for latest post date **/
          $latest_forum_reply = $Home->getLatestForumReply($value->forum_post_id);
          if(isset($latest_forum_reply)){
            $loc_date = $latest_forum_reply;
          }else if(isset($value->forum_edit_date)){
            $loc_date = $value->forum_edit_date;
          }else{
            $loc_date = $value->forum_timestamp;
          }
          /** Check to see if topic has url set **/
          if(isset($value->forum_url)){
            $url_link = $value->forum_url;
          }else{
            $url_link = $value->forum_post_id;
          }
          echo "<url>\n";
            echo "<loc>".SITE_URL."Topic/".$url_link."/</loc>\n";
            echo "<lastmod>".date('Y-m-d',strtotime($loc_date))."</lastmod>\n";
          echo "</url>\n";
        }
      }

      echo "</urlset>";

    }
}
