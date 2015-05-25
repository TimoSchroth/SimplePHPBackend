<?php

class dao_login extends _config{
    
    public function login_user(){
        $request = $this->get_request();
        
        $data['success'] = true;
        $data['msg'] = '';

        if(!isset($data['token'])){
            if(isset($request['name']) && isset($request['password'])){
                $name = $this->mysqli->real_escape_string($request['name']);
                $password = $this->mysqli->real_escape_string($request['password']);  

                $query = "SELECT * FROM users us, mentor_images mi "
                       . "WHERE us.username = '".$name."' "
                       . "AND mi.mentor_image_id = us.mentor_image_id";

                if ($result = $this->mysqli->query($query)) {
                    $us = mysqli_fetch_assoc($result);
                    if(sha1($password) !== $us['password']){
                        $data['success'] = false;
                        $data['msg'] = "Benutzer oder Passwort nicht korrekt.";                     
                    } else {
                        $_SESSION['username'] = $us['username'];
                        $_SESSION['user_id'] = $us['user_id'];
                        $_SESSION['user_role_id'] = $us['user_role_id'];
                        $_SESSION['token'] = md5(uniqid(mt_rand(), true));
                        $_SESSION['url'] = $us['url'];
                        
                        $data['username'] = $_SESSION['username'];
                        $data['user_id'] = $_SESSION['user_id'];
                        $data['user_role_id'] = $_SESSION['user_role_id'];
                        $data['mentor_image'] = $_SESSION['url'];
                        $data['token'] = $_SESSION['token'];
                    }
                } else {
                    $data['success'] = false;
                    $data['msg'] = "Benutzer oder Passwort nicht korrekt.";                 
                }                   

            } else {
                $data['success'] = false;
                $data['msg'] = "Login fehlgeschlagen.";           
            }
        }
        
        return $data;
    } 
    
}

