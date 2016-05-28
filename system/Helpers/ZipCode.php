<?php
namespace Helpers;

use Helpers\Database,
    Helpers\Cookie,
    Helpers\BBCode;

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
