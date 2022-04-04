<?php
    include_once '../../config/configHeader.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/user_controller.php';
    (new CF_Header()) -> config("POST");
    $email = $_POST["email"];
    $password = $_POST["password"];
    $data = (new UserController()) -> resetPassword($email,$password);
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
?>