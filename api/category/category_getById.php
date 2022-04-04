<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category/category_controller.php';

    (new CF_Header()) -> config("POST");

    $body = json_decode(file_get_contents("php://input"));

    if(property_exists($body, 'category_id')){
        $category_id = $body->category_id;
        $data = (new CategoryController()) -> getByID($category_id);
    
        if($data){
            echo json_encode($data);
        }else{

        }
    }

    
?>