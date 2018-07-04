<?php
/**
* Site Stats Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.2.0
*/

namespace Libs;

use Libs\Database;

class SiteStats
{
  private static $db;

  /**
  *  Log Current Activity
  */
  public static function log($username = null){
    /** Check if username is empty **/
    if(empty($username)){$username = "Guest";}
    /** Get the Refering Page if There is one **/
    if(isset($_SERVER['HTTP_REFERER'])){ $refer = $_SERVER['HTTP_REFERER']; }else{ $refer = ""; }
    /** Will return the type of web browser or user agent that is being used to access the current script. **/
    $useragent = $_SERVER['HTTP_USER_AGENT'];
    /** The filename of the currently executing script, relative to the document root. **/
    $cfile = $_SERVER['PHP_SELF'];
    /** Prints the exact path and filename relative to the DOCUMENT_ROOT of your site. **/
	$uri = $_SERVER['REQUEST_URI'];
    /** Contains the IP address of the user requesting the PHP script from the server. **/
	$ipaddy = $_SERVER['REMOTE_ADDR'];
    /** Get Server Name Site is Accessed On. **/
    $server = $_SERVER['SERVER_NAME'];

    // List of Pages that user should never get logged
    $no_log_pages = array("Templates", "assets");
    //Remove the extra forward slash from link
    $cur_page_a = ltrim($uri, DIR);
    // Get first part of the url (page name)
    $cur_page_b = explode('/', $cur_page_a);

    // Check to see if we should log as a previous page
    if(strpos ($uri,"." ) === FALSE){
        if(!in_array($cur_page_b[0], $no_log_pages)){
            self::$db = Database::get();
            self::$db->insert(
              PREFIX.'sitelogs',
                array('membername' => $username, 'refer' => $refer,
                      'useragent' => $useragent, 'cfile' => $cfile,
                      'uri' => $uri, 'ipaddy' => $ipaddy,
                      'server' => $server));
        }
    }
  }

  /**
	 * Get total number of site logs
	 */
	public static function getTotalViews(){
    self::$db = Database::get();
		$data = self::$db->select("SELECT count(id) as num_rows FROM ".PREFIX."sitelogs");
		$number = $data[0]->num_rows;
    $abbrevs = array(12 => "T", 9 => "B", 6 => "M", 3 => "K", 0 => "");
    foreach($abbrevs as $exponent => $abbrev) {
        if($number >= pow(10, $exponent)) {
        	$display_num = $number / pow(10, $exponent);
        	$decimals = ($exponent >= 3 && round($display_num) < 100) ? 1 : 0;
            return number_format($display_num,$decimals) . $abbrev;
        }
    }
	}


  /**
	 * Get total number of site logs for given month
	 */
	public static function getCurrentMonth($date = null, $when = null){
    if($when == "lastYear"){$date = date('F Y', strtotime($date.' -1 year'));}
    $month = date('n', strtotime($date));
    $year = date('Y', strtotime($date));
    self::$db = Database::get();
		$data = self::$db->select("SELECT count(id) as num_rows FROM ".PREFIX."sitelogs WHERE YEAR(timestamp) = :year AND MONTH(timestamp) = :month", array(':year' => $year, ':month' => $month));
    return $data[0]->num_rows;
	}


}
