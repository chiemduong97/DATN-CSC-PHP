<?php
    include_once '../../config/configHeader.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/user_controller.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';
    $authen = new Authen();
    (new CF_Header()) -> config("GET");
    
    
    if($authen -> checkToken()){
        $email = $_GET["email"];
        $data = (new UserController()) -> getUserByEmail($email);
        echo json_encode($data);
    }
    else
    {
        echo json_encode(array(
            "status"=>false,
        ));
    }   
?>