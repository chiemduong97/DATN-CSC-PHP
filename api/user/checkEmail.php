<?php
    include_once '../../config/configHeader.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/user_controller.php';
    (new CF_Header()) -> config("GET");
    $email = $_GET["email"];
    $data = (new UserController()) -> checkEmail($email);
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