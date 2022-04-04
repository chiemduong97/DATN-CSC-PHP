<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/product/product_controller.php';

    (new CF_Header()) -> config("POST");

    $body = json_decode(file_get_contents("php://input"));

    if(property_exists($body, 'category')){
        $category = $body->category;
        $data = (new ProductController()) -> getAll($category);
    
        if($data){
            echo json_encode($data);
        }else{
            echo json_encode(array(
                "status"=>false,
                "code"=>1001
            ));
        }
    }else{
        echo json_encode(array(
            "status"=>false,
            "code"=>1013
        ));
    }

    
?>