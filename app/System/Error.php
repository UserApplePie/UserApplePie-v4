<?php namespace App\System;

class Error {

    static function show($type){
        $data['error_code'] = "404";
        if($type == '404'){
            $data['bodyText'] = "Oops! Looks like something went wrong!";
            $data['bodyText'] .= "<br>The Requested URL Does Not Exist!";
            $data['bodyText'] .= "<br>Please check your spelling and try again.";
        }else{
            $data['bodyText'] = "Oops! Looks like something went wrong!";
        }
        Load::View("Home::Error", $data);
    }

}
