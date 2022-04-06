<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/product_controller.php';

    (new CF_Header()) -> config("POST");

    $body = json_decode(file_get_contents("php://input"));

    if(property_exists($body, 'id')){
        $id = $body->id;
      
        $data = (new ProductController()) -> removeItem($id);
    
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