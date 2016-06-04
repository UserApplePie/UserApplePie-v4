<?php
/**
* Helper to get Page Functions
*/
namespace Helpers;

class PageFunctions {

	// Function that Checks to see what user's previous page was before login
	public function prevpage(){
		// Make sure that user is not redirected back to Login, Register, ForgotPassword, etc.
		// List of Pages that user should never get redirected to when logged in
		$no_redir_pages = array("login", "register", "logout", "forgot-password", "resend-activation-email", "reset-password", "activate",
		 												"Login", "Register", "Logout", "Forgot-Password", "Resend-Activation-Email", "Reset-Password", "Activate",
														"favicon.ico", "uap3logosm.gif", "uap3logolg.gif", "uap3logo_wait_animation.gif");
		// Get Current Page so we have something to compair to
		$cur_page = $_SERVER['REQUEST_URI'];
		//Remove the extra forward slash from link
		$cur_page = ltrim($cur_page, DIR);
		// Check to see if we should log as a previous page
		if(!in_array($cur_page, $no_redir_pages)){
			$_SESSION['login_prev_page'] = $cur_page;
		}
	}

}
