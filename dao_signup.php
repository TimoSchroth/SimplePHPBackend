<?php

class dao_signup extends _config{
    
    public function signup_user(){
        $request = $this->get_request();
        
        $data['success'] = true;
        $data['type'] = 'success';
        $data['msg'] = "";
        
        if(isset($request['imgId']) && isset($request['name']) && isset($request['password']) && isset($request['gender'])){
            $imgId = $this->mysqli->real_escape_string($request['imgId']);
            $name = $this->mysqli->real_escape_string($request['name']);
            $password = $this->mysqli->real_escape_string($request['password']);
            $gender = $this->mysqli->real_escape_string($request['gender']);
            
            $query_check_user = "SELECT * FROM users WHERE username = '".$name."'";

            if ($result_check_user = $this->mysqli->query($query_check_user)) {
                if(mysqli_num_rows($result_check_user) > 0){
                    $data['success'] = false;
                    $data['msg'] = "Benutzer schon vorhanden.";
                } else {
                    $query = "INSERT INTO users (username, password, gender, mentor_image_id, user_role_id)
                                   VALUES ('".$name."', '".sha1($password)."', '".$gender."', '".$imgId."', '1')";

                    if ($this->mysqli->query($query) !== TRUE) {
                        $data['success'] = false;
                        $data['msg'] = $this->mysqli->error; 
                    }
                }
            } else {
                $data['success'] = false;
                $data['msg'] = $this->mysqli->error;
            }
        } else {
            $data['success'] = false;
            $data['msg'] = $this->mysqli->error;           
        }
        
        return $data;
    }     
    
}

