<?php
/**
 * Change Lang Controller
 *
 * @author David (DaVaR) Sargent
 * @version 3.0.4
 */

namespace App\Controllers;

use Core\View,
  Core\Controller,
  Libs\Session,
  Libs\Url,
  Libs\SuccessMessages;

/**
 * Language Changer Controller.
 */
class ChangeLang extends Controller
{

    /**
     * Call the parent construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->language->load('Welcome');
    }

    /**
     * Define the change lang page
     */
    public function index($new_lang_code = null)
    {
      if(isset($new_lang_code)){
        $_SESSION['cur_lang'] = $new_lang_code;
        /**
        * Check to see if user came from another page within the site
        */
        if(isset($_SESSION['lang_prev_page'])){ $lang_prev_page = $_SESSION['lang_prev_page']; }else{ $lang_prev_page = ""; }
        /**
        * Checking to see if user user was viewing anything before lang change
        * If they were viewing a page on this site, then after lange change
        * send them to that page they were on.
        */
        if(!empty($lang_prev_page)){
          /* Clear the prev page session if set */
          if(isset($_SESSION['lang_prev_page'])){
            unset($_SESSION['lang_prev_page']);
          }
          $prev_page = $lang_prev_page;
          /* Send user back to page they were at before lang change */
          /* Success Message Display */
          Url::redirect($prev_page);
        }else{
          Url::redirect();
        }
      }else{
        Url::redirect();
      }
    }

}
