<?php

class _auth extends _config{
    
    public function ping(){
        if($_SERVER['REQUEST_METHOD']=="POST" || $_SERVER['REQUEST_METHOD']=="GET"){
            $headerToken = $_SERVER['HTTP_TOKEN'];
            $sessionToken = $_SESSION['token'];
            if($sessionToken == "" || $sessionToken == "" || $headerToken != $sessionToken){
               $this->logout_user();
               exit;
            }
        }
    }
    
    public function logout_user(){
        session_destroy(); 
        header('HTTP/1.0 401 Unauthorized');
        die;
    }  
    
}
