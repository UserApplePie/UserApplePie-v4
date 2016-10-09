<?php
/**
 * Error Message Helper
 *
 * @author DaVaR - davar@userapplepie.com
 * @version 1.0
 * @date Jan 25 2016
 */

namespace Libs;

/**
 * collection of methods for working with error messages.
 */
class ErrorMessages
{
  /**
   * Get and display recent error message from error session
   * @return string
   */
  public static function display(){
    // Check to see if session error_message exists
    if(isset($_SESSION['error_message'])){
      // Get data from session then display it
  		$error_msg = $_SESSION['error_message'];
  		$display_msg = "
        <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
          <div class='alert alert-danger alert-dismissible' role='alert'>
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
              <span aria-hidden='true'>&times;</span>
            </button>
            <strong>".\Libs\Language::show('uap_error', 'Welcome')."!</strong> $error_msg
          </div>
        </div>";
  		unset($_SESSION['error_message']);
      return $display_msg;
  	}
  }

  /**
  * Push Error Message to Session for display on page user is redirected to
  * @param $error_msg  string  Message Text
  * @param $redirect_to_page  string  URL Page Name for Redirect
  */
  public static function push($error_msg, $redirect_to_page = null){
    // Check to see if there is already a error message session
    if(isset($_SESSION['error_message'])){
      // Clean error message Session
      unset($_SESSION['error_message']);
    }
    // Send error message to session
    $_SESSION['error_message'] = $error_msg;
    // Check to see if a redirect to page is supplied
    if(isset($redirect_to_page)){
      // Redirect User to Given Page
      Url::redirect($redirect_to_page);
    }
  }

  /**
  * Displays Message without sessions to keep form data for retry
  * @param $e_msg  string  Message Text
  * @return string
  */
  public static function display_raw($e_msg = null){
    // Make sure an Error Message should be displayed
    if(isset($e_msg)){
      // Check to see if we are displaying an array of errors
      if(is_array($e_msg)){
        // Not an array, display single error
        $error_msg = "";
        foreach($e_msg as $em){
          $error_msg .= "<br>$em";
        }
      }else{
        $error_msg = $e_msg;
      }
        // Not an array, display single error
        $display_msg = "
          <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12'>
            <div class='alert alert-danger alert-dismissible' role='alert'>
              <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                <span aria-hidden='true'>&times;</span>
              </button>
              <strong>".\Libs\Language::show('uap_error', 'Welcome')."!</strong> $error_msg
            </div>
          </div>";
        return $display_msg;
    }
  }


}
