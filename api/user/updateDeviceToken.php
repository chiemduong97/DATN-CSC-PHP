<?php
    include_once '../../config/configHeader.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/user_controller.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';
    $authen = new Authen();

    (new CF_Header()) -> config("POST");

    if($authen -> checkToken()){
        $email = $_POST["email"];
        $deviceToken = isset($_POST["deviceToken"])?$_POST["deviceToken"]:null;
        $data = (new UserController()) -> updateDeviceToken($email,$deviceToken);
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