<?php
    include_once '../../config/configHeader.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/user_controller.php';
    include_once $_SERVER['DOCUMENT_ROOT'].'/authen/authen.php';
    $authen = new Authen();

    (new CF_Header()) -> config("POST");

    if($authen -> checkToken()){
        $email = $_POST["email"];
        $latitude = $_POST["latitude"];
        $longitude = $_POST["longitude"];
        $address = $_POST["address"];

        $data = (new UserController()) -> updateLocation($email,$latitude,$longitude,$address);
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
    else{
        echo json_encode(array(
            "status"=>false,
            "code"=>1001,
        ));
    }

   
?>