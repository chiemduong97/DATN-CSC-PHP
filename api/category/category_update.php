<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/category/category_controller.php';

    (new CF_Header()) -> config("POST");

    $body = json_decode(file_get_contents("php://input"));

    if(property_exists($body, 'id') && 
     property_exists($body, 'name') && 
     property_exists($body, 'avatar'))
     {
        $id = $body->id;
        $name = $body->name;
        $avatar = $body->avatar;
        $data = (new CategoryController()) -> updateItem($id, $name, $avatar);
    
        if($data){
            echo json_encode($data);
        }else{

        }
    }

    
?>