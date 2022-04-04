<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category/category_controller.php';

    (new CF_Header()) -> config("POST");

    $body = json_decode(file_get_contents("php://input"));

    if(property_exists($body, 'name') && property_exists($body, 'avatar')){
        $name = $body->name;
        $avatar = $body->avatar;
        $data = (new CategoryController()) -> insertItem($name, $avatar);
    
        if($data == 1000){
            echo json_encode(array(
                "status"=>true,
                "code"=>$data
            ));
        }
        else{
            echo json_encode(array(
                "status"=>false,
                "code"=>$data
            ));
        }
    } else{
        echo json_encode(array(
            "status"=>false,
            "code"=>1013
        ));
    }
    
?>