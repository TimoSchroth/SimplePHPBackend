<?php

include_once("./_speakingurl.php");
include_once("./_config.php");
include_once("./_auth.php");

session_start();

$db_data = array();
$db_data['localhost'] = "localhost";
$db_data['user'] = "";
$db_data['password'] = "";
$db_data['database'] = "";
        
$call = new _speakingurl($_SERVER['REQUEST_URI']);
$call->controller = 2;
$call->method = 3;

$model_url = "./dao_" . $call->controller . ".php";
$model_obj = "dao_" . $call->controller;
$method = $call->method;

if(file_exists($model_url)){
    include_once($model_url);
    $model = new $model_obj();
    $model->set_request(array_merge($_GET, $_POST));
    
    if($call->controller != "signup" && $call->controller != "login"){
        $model->ping();
    }    
    
    $model->set_db_connection($db_data);
    if (mysqli_connect_errno()) {
        echo json_encode("Connect failed: %s\n", mysqli_connect_error());
    } else {
        if(method_exists($model, $call->method)){
            echo json_encode($model->$method());
        }
        $model->set_close_connection();
    }
}