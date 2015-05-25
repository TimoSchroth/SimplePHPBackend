<?php

class _config{
    
    public $mysqli;
    public $request;
    
    public function __construct(){}
    
    public function set_db_connection($db_data){
        $this->mysqli = new mysqli($db_data['localhost'], $db_data['user'], $db_data['password'], $db_data['database']);
    }
    
    public function get_mysqli(){
        return $this->mysqli;
    }
    
    public function set_close_connection(){
        $this->mysqli->Close();
    }
    
    public function set_request($request){
        $this->request = $request;
    }
    
    public function get_request(){
        return $this->request;
    }
    
}