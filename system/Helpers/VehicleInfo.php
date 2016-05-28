<?php
namespace Helpers;

use Helpers\Database,
    Helpers\Cookie,
    Helpers\BBCode;

class VehicleInfo
{
    private static $db;

	// Get vehicle photo based on profile id and most recent
	public static function getVPImage($where_id){
		self::$db = Database::get();
		$data = self::$db->select("
				SELECT
					imgname
				FROM
					".PREFIX."vehicle_images
				WHERE
					vehicle_id = :where_id
				",
			array(':where_id' => $where_id));
		return $data[0]->imgname;
	}

  // Get random vehicle photos based on profile id and most recent
	public static function getRandomImages(){
		self::$db = Database::get();
		$data = self::$db->select("
				SELECT
          vehicle_id,
					imgname
				FROM
					".PREFIX."vehicle_images
				WHERE
					allow = 'true' AND isnew = 'true'
        ORDER BY RAND()
        LIMIT 10
				");
		return $data;
	}

}
