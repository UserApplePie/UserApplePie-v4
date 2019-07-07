<?php
/**
* Home Models
*
* UserApplePie
* @author David (DaVaR) Sargent <davar@userapplepie.com>
* @version 4.3.0
*/

namespace App\Models;

use App\System\Models,
    Libs\Database;

class Home extends Models {

  /**
  * This is just a sample test model function
  * If called it will not work due to test table not being in database
  * @param int $id
  * @return int data
  */
  public function test($id){
    $data = $this->db->select('SELECT * FROM '.PREFIX.'test WHERE id = :id',
      array(':id' => $id));
    return $data[0]->id;
  }

}
