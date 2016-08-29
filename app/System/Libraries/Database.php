<?php namespace App\System\Libraries;

class Database {

    private $obj;
    private $result = null;
    public $current_field = "";
    public $lengths = "";
    public $num_rows = "";

    function __construct($host, $username, $password, $database = null){
        if(is_null($database)){
            $this->obj = new mysqli($host, $username, $password);
        }else{
            $this->obj = new mysqli($host, $username, $password, $database);
        }

        function changeDB($database){
            $this->obj->select_db($database);
        }

        function refValues($arr){
            if(strnatcmp(phpversion(), "5.3") >= 0){
                $refs = array();
                foreach ($arr as $key => $value) {
                    $refs[$key] = &$arr[$key];
                }
                return $refs;
            }
            return $arr;
        }

        function query($query, $args = null){
            if(is_null($args)){
                $this->result =  $this->obj->query($query);
                $this->current_field = $this->result->current_field;
                $this->lengths = $this->result->lengths;
                $this->num_rows = $this->result->num_rows;
                return $this->result;
            }else{
                if(!is_array($args)){
                    $argsBkp = $args;
                    $args = array($argsBkp);
                }
                if($stmt = $this->obj->prepare($query)){
                    $datatypes = "";
                    foreach ($args as $value) {
                        if(is_int($value)){
                            $datatypes .= "i";
                        }else if(is_double($value)){
                            $datatypes .= "d";
                        }else if(is_string($value)){
                            $datatypes .= "s";
                        }else{
                            $datatypes .= "b";
                        }
                    }
                    array_unshift($args, $datatypes);
                    if(call_user_func_array(array($stmt, "bind_param"), $this->refValues($args))){
                        $stmt->execute();
                        $this->result = $stmt->get_result();
                        if($this->result){
                            $this->current_field =$this->result->current_field;
                            $this->lengths = $this->result->lengths;
                            $this->num_rows = $this->result->num_rows;
                        }else{
                            $this->current_field = "";
                            $this->lengths = 0;
                            $this->num_rows = 0;
                        }
                        $this->error = $stmt->error;
                        return $this->result;
                    }else{
                        $this->current_field = "";
                        $this->lengths = 0;
                        $this->num_rows = 0;
                        return false;
                    }
                }else{
                    $this->current_field = "";
                    $this->lengths = 0;
                    $this->num_rows = 0;
                    return false;
                }
            }
        }
    }

    function data_seek($offset = 0){
        return $this->result->data_seek($offset);
    }

    function fetch_all(){
        return $this->result->fetch_all();
    }

    function fetch_array(){
        return $this->result->fetch_array();
    }

    function fetch_assoc(){
        return $this->result->fetch_assoc();
    }

    function fetch_field_direct($field){
        return $this->result->fetch_field_direct();
    }

    function fetch_field(){
        return $this->result->fetch_field();
    }

    function fetch_fields(){
        return $this->result->fetch_fields();
    }

    function fetch_object($class_name = "stdClass", $params = null){
        if(is_null($params)){
            return $this->result->fetch_object($class_name);
        }else{
            return $this->result->fetch_object($class_name, $params);
        }
    }

    function fetch_row(){
        return $this->result->fetch_row();
    }

    function fetch_seek($field){
        return $this->result->field_seek($field);
    }

    function insert_id(){
        return $this->result->insert_id;
    }

    function fetch_all_kv(){
        $out = array();
        while ($row = $this->result->fetch_assoc()) {
            $out[] = $row;
        }
        return $out;
    }

    function __destruct(){
        $this->obj->close();
    }

}
