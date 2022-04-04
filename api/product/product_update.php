<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/configHeader.php'; 
include_once $_SERVER['DOCUMENT_ROOT'].'/controllers/product/product_controller.php';

    (new CF_Header()) -> config("POST");

    $body = json_decode(file_get_contents("php://input"));

    if(property_exists($body, 'id') && 
     property_exists($body, 'name') && 
     property_exists($body, 'avatar')&& 
     property_exists($body, 'description')&& 
     property_exists($body, 'price')&&
     property_exists($body, 'category')
     )
     {
        $id = $body->id;
        $name = $body->name;
        $avatar = $body->avatar;
        $description = $body->description;
        $price = $body->price;
        $category = $body->category;
        $data = (new ProductController()) -> updateItem($id, $name, $avatar, $description, $price, $category);
    
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