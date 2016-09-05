<?php namespace App\Models;

use App\System\Models,
    App\System\Libraries\Database;

class Home extends Models {

    public function test($id){
        $data = $this->db->select('SELECT * FROM '.PREFIX.'test WHERE id = :id',
            array(':id' => $id));
        return $data[0]->data;
    }

}
