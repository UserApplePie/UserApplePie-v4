<?php
/**
* Page Functions Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/
namespace Libs;

class PageFunctions {

	// Function that Checks to see what user's previous page was before login
	public static function prevpage(){
		// Make sure that user is not redirected back to Login, Register, ForgotPassword, etc.
		// List of Pages that user should never get redirected to when logged in
		$no_redir_pages = array("login", "register", "logout", "forgot-password", "resend-activation-email", "reset-password", "activate",
								"Login", "Register", "Logout", "Forgot-Password", "Resend-Activation-Email", "Reset-Password", "Activate",
								"Templates", "assets");
		// Get Current Page so we have something to compair to
		$cur_page = $_SERVER['REQUEST_URI'];
		//Remove the extra forward slash from link
		$cur_page_a = ltrim($cur_page, DIR);
		// Get first part of the url (page name)
		$cur_page_b = explode('/', $cur_page_a);
		//Add current page to lang change session for redirect
		$_SESSION['lang_prev_page'] = $cur_page_a;
		// Check to see if we should log as a previous page
		if(strpos ($cur_page,"." ) === FALSE){
			if(!in_array($cur_page_b[0], $no_redir_pages)){
				$_SESSION['login_prev_page'] = $cur_page_a;
			}
		}
	}

}
