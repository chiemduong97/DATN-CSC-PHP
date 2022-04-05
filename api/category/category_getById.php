<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category_controller.php';

    (new CF_Header()) -> config("POST");

    $body = json_decode(file_get_contents("php://input"));

    if(property_exists($body, 'id')){
        $category_id = $body->id;
        $data = (new CategoryController()) -> getByID($category_id);
    
        if($data){
            echo json_encode($data);
        }else{
            echo json_encode(array(
                "status"=>false,
                "code"=>$data
            ));
        }
    }else{
        echo json_encode(array(
            "status"=>false,
            "code"=>1013
        ));
    }

    
?>