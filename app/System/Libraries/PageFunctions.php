<?php
/**
* Page Functions Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.1
*/
namespace Libs;

use Libs\Database;

class PageFunctions {

	/* Ready the DB for usage */
	private static $db;

	/* Function that Checks to see what user's previous page was before login */
	public static function prevpage(){
		/* Make sure that user is not redirected back to Login, Register, ForgotPassword, etc. */
		/* List of Pages that user should never get redirected to when logged in */
		$no_redir_pages = array("login", "register", "logout", "forgot-password", "resend-activation-email", "reset-password", "activate",
								"Login", "Register", "Logout", "Forgot-Password", "Resend-Activation-Email", "Reset-Password", "Activate",
								"Templates", "assets");
		/* Get Current Page so we have something to compair to */
		$cur_page = $_SERVER['REQUEST_URI'];
		/* Remove the extra forward slash from link */
		$cur_page_a = ltrim($cur_page, DIR);
		/* Get first part of the url (page name) */
		$cur_page_b = explode('/', $cur_page_a);
		/* Add current page to lang change session for redirect */
		$_SESSION['lang_prev_page'] = $cur_page_a;
		/* Check to see if we should log as a previous page */
		if(strpos ($cur_page,"." ) === FALSE){
			if(!in_array($cur_page_b[0], $no_redir_pages)){
				$_SESSION['login_prev_page'] = $cur_page_a;
			}
		}
	}

	/* Function that gets urls based on location */
	public static function getLinks($location){
		self::$db = Database::get();
		$data = self::$db->select("
				SELECT
					*
				FROM
					".PREFIX."links u
				WHERE
					location = :location
				AND
					drop_down_for = '0'
				ORDER BY link_order ASC
				",
			array(':location' => $location));
			$links_output = "";
			if(isset($data)){
				foreach ($data as $link) {
					/* Check to see if is a plugin link and if that plugin exists */
					if(isset($link->require_plugin)){
						if(file_exists(ROOTDIR.'app/Plugins/'.$link->require_plugin.'/Controllers/'.$link->require_plugin.'.php')){
							$link_enable = true;
						}else{
							$link_enable = false;
						}
					}else{
						$link_enable = true;
					}
					if($link_enable == true){
						if($link->location == "header_main"){ $set_class = "nav-link"; }
						if($link->drop_down == "1"){
							$links_output .= "<li class='nav-item dropdown'>";
							$links_output .= "<a href='#' title='".$link->alt_text."' class='nav-link dropdown-toggle' data-toggle='dropdown' id='links_".$link->id."'> ".$link->title." </a>";
							$links_output .= SELF::getDropDownLinks($link->id);
							$links_output .= "</li>";
						}else{
							$links_output .= "<li><a class='$set_class' href='".SITE_URL.$link->url."' title='".$link->alt_text."'> ".$link->title." </a></li>";
						}
					}
				}
			}
			(isset($links_output)) ? $links_output = $links_output : $links_output = "";
		return $links_output;
	}

	/* Function that gets urls for dropdown menus */
	public static function getDropDownLinks($drop_down_for){
		self::$db = Database::get();
		$data = self::$db->select("
				SELECT
					*
				FROM
					".PREFIX."links u
				WHERE
					drop_down_for = :drop_down_for
				ORDER BY link_order ASC
				",
			array(':drop_down_for' => $drop_down_for));
			$links_output = "";
			if(isset($data)){
				$links_output .= "<div class='dropdown-menu' aria-labelledby='links_".$drop_down_for."'>";
				foreach ($data as $link) {
					$links_output .= "<a class='dropdown-item' href='".SITE_URL.$link->url."' title='".$link->alt_text."'> ".$link->title." </a>";
				}
				$links_output .= "</div>";
			}
			(isset($links_output)) ? $links_output = $links_output : $links_output = "";
		return $links_output;
	}

}
