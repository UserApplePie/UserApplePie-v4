<?php
/**
* Site Adds Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

namespace Libs;

use Libs\Database;

class Adds
{
  private static $db;

  /**
  *  Load Adds if any set
  */
  public static function getAdds($location = null){
    if(!empty($location)){
      self::$db = Database::get();
      $data = self::$db->select("SELECT setting_data FROM ".PREFIX."settings WHERE setting_title = :location",
        array(':location' => $location));
          (isset($data[0]->setting_data)) ? $adds_data = $data[0]->setting_data : $adds_data = "";
      if(!empty($adds_data)){
        return $adds_data;
      }else{
        return false;
      }
    }else{
      return false;
    }
  }

}
