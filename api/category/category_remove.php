<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category/category_controller.php';

    (new CF_Header()) -> config("POST");

    $body = json_decode(file_get_contents("php://input"));

    if(property_exists($body, 'id')){
        $id = $body->id;
      
        $data = (new CategoryController()) -> removeItem($id);
    
        if($data){
            echo json_encode($data);
        }else{

        }
    }

    
?>