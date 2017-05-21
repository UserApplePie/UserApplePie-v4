<?php
/**
* ZipCode Plugin
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.0.1
*/

namespace Libs;

use Libs\Database,
    Libs\Cookie,
    Libs\BBCode;

class ZipCode
{
    private static $db;

	// Get City, State based on Zip Code
	public static function getCityState($zip){
		self::$db = Database::get();
		$data = self::$db->select("
				SELECT
					*
				FROM
					".PREFIX."cities_extended
				WHERE
					zip = :zip
				",
			array(':zip' => $zip));
		return $data[0]->city.", ".$data[0]->state_code;
	}

}
