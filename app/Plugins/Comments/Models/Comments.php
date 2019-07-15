<?php
/**
* UserApplePie v4 Comments Models Plugin
*
* UserApplePie - Comments Plugin
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 1.0.0 for UAP v.4.3.0
*/

/** Comments model **/

namespace App\Plugins\Comments\Models;

use App\System\Models,
    Libs\Url;

class Comments extends Models {

  /**
  * Get commets by id
  * @param int $where_id
  * @return array dataset
  */
  public function getCommentsByID($where_id){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."comments
      WHERE
        id = :id
      ORDER BY
        id
      DESC LIMIT 1      
    ", array(':id' => $where_id));
    return $data;
  }

  /**
  * Get commets by id
  * @param int $where_id
  * @return array dataset
  */
  public function getStatus($where_id){
    $data = $this->db->select("
      SELECT
        *
      FROM
        ".PREFIX."status
      WHERE
        id = :id
      ORDER BY
        id
      DESC LIMIT 1
    ", array(':id' => $where_id));
    return $data;
  }

}
