<?php
    include_once '../../config/configHeader.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/user_controller.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';
    $authen = new Authen();

    (new CF_Header()) -> config("POST");

    if($authen -> checkToken()){
        $email = $_POST["email"];
        $device_token = isset($_POST["device_token"])?$_POST["device_token"]:null;
        $data = (new UserController()) -> updateDeviceToken($email,$device_token);
        if($data == 1000){
            echo json_encode(array(
                "status"=>true
            ));
        }
        else{
            echo json_encode(array(
                "status"=>false,
                "code"=>$data
            ));
        }
    }
    else
    {
        echo json_encode(array(
            "status"=>false,
            "code"=>1001,
        ));
    }   

    
?>